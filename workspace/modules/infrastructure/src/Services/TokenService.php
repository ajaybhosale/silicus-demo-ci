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
 * @category Token
 * @package  TokenManagement
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Services;

use GuzzleHttp\Client;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Modules\Configuration\Models\Configuration;
use Modules\Configuration\Repositories\ConfigurationRepository;

/**
 * Common TokenService trait class which covers all token management functions.
 * Class also verifies user identity for every user request and gives valid user response
 *
 * @name     TokenService
 * @category Token
 * @package  TokenManagement
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait TokenService
{

    protected $tablePrefix = 'Master';
    protected $table = 'Configuration';

    /**
     * Function handles JWT token decodes in three different categories like
     * Header/Payload/identity.
     * Header and payload contains user information and application information
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   decodeToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function decodeToken($request)
    {
        $strAccessToken = '';
        // Get access token from the authorization header part
        $strAccessToken = $request->header('Authorization', '');
        $strAccessToken = substr($strAccessToken, 7);
        $arrResponse = [];
        // Decode access token payload segment for validate token
        if (isset($strAccessToken) && $strAccessToken <> '') {
            $arrTokenSegment = explode('.', $strAccessToken);
            $arrResponse = json_decode(base64_decode($arrTokenSegment[1]));
        }
        return $arrResponse;
    }

    /**
     * Function create/update AD admin token into database and used when user
     * information get updated by administrator user
     *
     * @param string $strAccessToken is used to get data sent by client
     *
     * @name   saveAdminToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return boolean
     */
    public function saveAdminToken($strAccessToken)
    {
        $configurationRepo = new ConfigurationRepository(new Configuration());
        try {
            $defaultConfigToken = $configurationRepo->findBy(['ConfigKey' => 'Access-Token']);
            $arrTokenInfo = $configurationRepo->update(new Configuration(), ['ConfigValue' => $strAccessToken]);
        } catch (QueryException $exception) {
            $this->errorInternal('Database token error ...!');
        }
        return ($arrTokenInfo) ? true : false;
    }

    /**
     * Function for getAdminToken using client and secret id
     *
     * @name   getAdminToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    public function getAdminToken()
    {
        $strAccessToken = '';
        try {
            $configurationRepo = new ConfigurationRepository(new Configuration());
            $accessToken = $configurationRepo->findBy(['ConfigKey' => 'Access-Token']);
            $arrAccessToken = json_decode($accessToken);
            if (isset($arrAccessToken) && $arrAccessToken <> '') {
                $strAccessToken = $this->_getSavedAccessToken($arrAccessToken);
            }
            $strAccessToken = $this->validateJWT($strAccessToken);
        } catch (QueryException $exception) {
            $this->errorInternal('Database token errors  while saving ...!');
        }
        return $strAccessToken;
    }

    /**
     * Function for getAdminToken using client and secret id
     *
     * @param Array $arrAccessToken is array of saved access token information
     *
     * @name   _getSavedAccessToken
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    private function _getSavedAccessToken($arrAccessToken)
    {
        $strAccessToken = '';
        foreach ($arrAccessToken as $objTokenPayload) {
            if ($objTokenPayload->ConfigKey == 'Access-Token') {
                $strAccessToken = $objTokenPayload->ConfigValue;
            }
        }
        return $strAccessToken;
    }

    /**
     * Function validate admin token i.e ( AD token ) not AD B2C token.
     * Function re-generate admin token if token is expired this token is used
     * to manage all user personal information.
     *
     * @param string $strAccessToken is database admin token
     *
     * @name   validateJWT
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    public function validateJWT($strAccessToken = '')
    {
        $arrCloudSettings = Config::get('cloud.b2c_config.resource');
        $arrTokenPayloadInfo = explode('.', $strAccessToken);
        // Decode token payload segment and compare
        $objTokenPayload = json_decode(base64_decode($arrTokenPayloadInfo[1]));
        // Check token validity if expire create new token and save
        if ($objTokenPayload->aud <> $arrCloudSettings || $objTokenPayload->exp < time()) {
            $strAccessToken = $this->generateAdminToken();
        }
        return $strAccessToken;
    }

    /**
     * Common function which generates admin access token using client_credentials
     *
     * @name   generateAdminToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    public function generateAdminToken()
    {
        $azureGuzzleClient = new Client();
        $strAccessToken = '';
        $arrAzureSettings = Config::get('cloud.azure_store_config');
        // Create new AD admin token from using user credentials
        $objTokenInfo = json_decode($azureGuzzleClient->post($arrAzureSettings['urlAccessToken'], ['form_params' => ['client_id' => $arrAzureSettings['clientId'], 'client_secret' => $arrAzureSettings['clientSecret'], 'resource' => 'https://graph.windows.net', 'grant_type' => 'client_credentials',]])->getBody()->getContents());
        $strAccessToken = $objTokenInfo->access_token;
        try {
            // Save token into database
            $this->saveAdminToken($strAccessToken);
        } catch (QueryException $exception) {
            $this->errorInternal('Database error ...!');
        }
        return $strAccessToken;
    }
}
