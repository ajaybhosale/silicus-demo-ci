<?php
/**
 * VERICHECK INC CONFIDENTIAL
 *
 * Vericheck Incorporated
 * All Rights Reserved.
 *
 * NOTICE:
 * All information contained herein is, and remains the property of
 * Vericheck Inc, if any.  The intellectual and technical concepts
 * contained herein are proprietary to Vericheck Inc and may be covered
 * by U.S. and Foreign Patents, patents in process, and are protected
 * by trade secret or copyright law. Dissemination of this information
 * or reproduction of this material is strictly forbidden unless prior
 * written permission is obtained from Vericheck Inc.
 *
 * @category CloudFactory
 * @package  Factory
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Factory;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use League\OAuth2\Client\Provider\GenericProvider;

/**
 * Azure class loads secure cloud services platform using Microsoft Azure
 * to build sophisticated applications with increased flexibility,
 * scalability and reliability.
 *
 * @name     CloudAbstract.php
 * @category CloudFactory
 * @package  Factory
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
abstract class CloudAbstract extends Controller
{

    protected $signInPolicy;
    protected $adConfig;
    protected $b2cConfig;
    protected $companyExtension;
    protected $alterEmail;
    protected $tenent;
    protected $env;
    protected $envExtension;
    protected $top;

    /**
     * Default abstract class constructor defines all variables
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct()
    {
        $cloudConfig = Config::get('cloud');
        $this->signInPolicy = $cloudConfig['sign_up_sign_in_policy'];
        $this->adConfig = $cloudConfig['azure_store_config'];
        $this->b2cConfig = $cloudConfig['b2c_config'];
        $this->companyIdExtension = $cloudConfig['company_id_extension'];
        $this->alterEmail = $cloudConfig['alternate_email_extension'];
        $this->env = $cloudConfig['environment'];
        $this->envExtension = $cloudConfig['env_extension'];
        $this->top = 100;
    }

    /**
     * Returns the authorize url returned from azure AD
     *
     * @param string $strRedirectUrl used to pass runtime redirect url according to different portal
     * @param string $strUserType    used to set the login type
     *
     * @name   getAuthorizeUrl
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    public function getAuthorizeUrl($strRedirectUrl, $strUserType = 'company')
    {
        $arrResponse = [];
        if ($strUserType == 'company') {
            $arrAzureConfig = $this->b2cConfig;
        }
        $arrAzureConfig['redirectUri'] = $strRedirectUrl;
        $arrClientProvider = new GenericProvider($arrAzureConfig);
        $arrResponse['redirectUrl'] = $arrClientProvider->getAuthorizationUrl([
            'prompt' => 'login', 'p' => $this->signInPolicy
        ]);
        $arrResponse['statusCode'] = static::SUCCESS_CODE;
        $arrResponse['id'] = $arrClientProvider->getState();
        return $arrResponse;
    }

    /**
     * Authenticate method is two step verification used to login the user
     * Method accepts code and validate with azure service
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   authenticate
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function authenticate($request)
    {
        $arrResponse = [];
        $arrAzureConfig = $this->b2cConfig;
        $arrClientProvider = new GenericProvider($arrAzureConfig);
        $objAccessToken = $arrClientProvider->getAccessToken('authorization_code', [
            'code' => $request->input('code'), 'grant_type' => 'authorization_code', 'response_type' => 'id_token'
        ]);
        if ($objAccessToken->getToken() !== null) {
            $arrResponse = ['accessToken' => $objAccessToken->getToken(), 'expiresIn' => $objAccessToken->getExpires(),
                'refreshToken' => $objAccessToken->getRefreshToken(), 'id' => $request->input('code')
            ];
        } else {
            $arrResponse['error'] = "Invalid access token";
        }
        return $arrResponse;
    }

    /**
     * Function destroys user identity from Vericheck portal
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   logout
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function logout($request)
    {
        $arrAzureConfig = $this->b2cConfig;
        $arrClientProvider = new GenericProvider($arrAzureConfig);
        $strLogoutUri = $this->b2cConfig['logoutUri']; // The logout destination after the user is logged out from their account.
        $arrLogoutInfo['redirectUrl'] = $this->getLogoutUrl($strLogoutUri);
        $arrLogoutInfo['id'] = $this->b2cConfig['clientId'];  // Todo add user id after accesstoken header
        return $arrLogoutInfo;
    }

    /**
     * Destroys user access token from active directory
     *
     * @param String $strLogoutUri sent logout url logout from the system
     *
     * @name   getLogoutUrl
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function getLogoutUrl($strLogoutUri = '')
    {
        return 'https://login.microsoftonline.com/' . $this->tenent . '/oauth2/logout?post_logout_redirect_uri=' . rawurlencode($strLogoutUri);
    }

    /**
     * Create new access token when new environment build is created
     *
     * @name   getAdminAccessToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function getAdminAccessToken()
    {
        $objGuzzleClient = new Client();
        $arrResponse = [];
        $boolTokenStatus = false;
        $arrAzureConfig = $this->adConfig;
        $objToken = json_decode($objGuzzleClient->post($arrAzureConfig['urlAccessToken'], ['form_params' => ['client_id' => $arrAzureConfig['clientId'], 'client_secret' => $arrAzureConfig['clientSecret'], 'resource' => 'https://graph.windows.net', 'grant_type' => 'password', 'username' => $arrAzureConfig['username'], 'Password' => $arrAzureConfig['password']]])->getBody()->getContents());
        $strAccessToken = $objToken->access_token;
        $boolTokenStatus = $this->saveAdminToken($strAccessToken);
        if (isset($strAccessToken) && $strAccessToken <> '' && $boolTokenStatus) {
            $arrResponse['token'] = $strAccessToken;
        } else {
            $arrResponse['message'] = "problem with token updation";
        }
        return $arrResponse;
    }

    /**
     * Protected function which creates graph api user entity api routes
     *
     * @param String $strEntityName postfix of the user graph api url
     *
     * @name   generateGraphApiUrl
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    public function generateGraphApiUrl($strEntityName = '/users')
    {
        $arrSettings = $this->b2cConfig;
        return $arrSettings['resource'] . $arrSettings['tenant'] . $strEntityName . '?api-version=' . $arrSettings['version'];
    }

    /**
     * Common attributes for the user creation
     *
     * @param Array $arrAzureUser all user attributes submitted from the user create and update interface
     *
     * @name   commonAttribute
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function commonAttribute($arrAzureUser = [])
    {
        if (isset($arrAzureUser['givenName']) && isset($arrAzureUser['surname'])) {
            $arrAzureUser['givenName'] = ucfirst(strtolower($arrAzureUser['givenName']));
            $arrAzureUser['surname'] = ucfirst(strtolower($arrAzureUser['surname']));
            $arrAzureUser['displayName'] = ucwords(strtolower($arrAzureUser['givenName'] . " " . $arrAzureUser['surname']));
            $arrAzureUser['mailNickname'] = ucfirst(strtolower($arrAzureUser['givenName'])) . substr($arrAzureUser['surname'], 0, 1);
        }
        return $arrAzureUser;
    }

    /**
     * Common attributes for the user creation
     *
     * @param Array $arrAzureUser all user attributes submitted from the user create and update interface
     *
     * @name   saveCommonAttribute
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function saveCommonAttribute($arrAzureUser = [])
    {
        $arrAzureUser['accountEnabled'] = true;
        $arrAzureUser['creationType'] = 'LocalAccount';
        $arrAzureUser[$this->envExtension] = $this->env;
        return $arrAzureUser;
    }
}
