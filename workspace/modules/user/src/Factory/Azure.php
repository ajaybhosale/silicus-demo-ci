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

use Modules\User\Factory\CloudAbstract;
use GuzzleHttp\Client;
use Modules\Infrastructure\Services\TokenService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Azure class loads secure cloud services platform using Microsoft Azure
 * to build sophisticated applications with increased flexibility,
 * scalability and reliability.
 *
 * @name     Azure.php
 * @category CloudFactory
 * @package  Factory
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class Azure extends CloudAbstract
{

    use TokenService;

    protected $accessToken;

    /**
     * Default constructor if factory class loads all cloud factory configurations.
     * Constructor sets all private member parameters from configurations
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->accessToken = $this->getAdminToken();
        if (!isset($this->accessToken) && $this->accessToken == '') {
            throw new UnauthorizedHttpException('restrict', 'Invalid access token');
        }
    }

    /**
     * Get the list of users from Azure AD
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   all
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function all($request)
    {
        $arrResponse = [];
        $settings = $this->b2cConfig;
        $searchUrl = '';
        $objTokenInfo = $this->decodeToken($request);
        $strUserApiUrl = $this->generateGraphApiUrl('/users');
        $params = $request->all();
        if (isset($strUserApiUrl) && $strUserApiUrl <> '') {
            $strUserApiUrl = $strUserApiUrl . '&$filter=accountEnabled eq true and ' . $this->envExtension . ' eq ' . "'$this->env'";
            $strUserApiUrl = $this->_commonSearch($objTokenInfo, $strUserApiUrl, $params);
        }
        $arrResponse = $this->makeGraphApiCall('GET', $strUserApiUrl, $this->accessToken, 'application/json');
        return json_decode($arrResponse);
    }

    /**
     * Function for search active directory user using skiptoken and name
     *
     * @param Obj $objTokenInfo     is user token information object retrieved from user access token
     * @param Obj $strUserApiUrl    is fixed search criteria for the user search
     * @param Obj $arrRequestParams is used for search user api from provided search criteria
     *
     * @name   _commonSearch
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    private function _commonSearch($objTokenInfo, $strUserApiUrl = '', $arrRequestParams = [])
    {
        if (isset($objTokenInfo->extension_CompanyId) && $objTokenInfo->extension_CompanyId <> '') {
            $strUserApiUrl = $strUserApiUrl . ' and ' . $this->companyIdExtension . " eq  '" . $objTokenInfo->extension_CompanyId . "' ";
        }
        $strUserApiUrl = $this->_searchUserNameUrl($arrRequestParams, $strUserApiUrl);
        $strUserApiUrl = $strUserApiUrl . '&$top=' . $this->top;
        $strUserApiUrl = $this->_skipTokenUrl($arrRequestParams, $strUserApiUrl);
        return $strUserApiUrl;
    }

    /**
     * Function for search active directory user using first or last
     *
     * @param Obj $arrRequestParams is user request information parameters
     * @param Obj $strUserApiUrl    is fixed search criteria for the user search
     *
     * @name   _searchUserNameUrl
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    private function _searchUserNameUrl($arrRequestParams = [], $strUserApiUrl = '')
    {
        if (isset($arrRequestParams['name']) && $arrRequestParams['name'] <> '') {
            $strSearchUrl = " startswith(givenName,'" . $arrRequestParams['name'] . "') or startswith(surname,'" . $arrRequestParams['name'] . "') or givenName eq '" . $arrRequestParams['name'] . "' or surname eq '" . $arrRequestParams['name'] . "' or $this->alterEmail eq '" . $arrRequestParams['name'] . "' ";
            $strUserApiUrl = $strUserApiUrl . " and (" . $strSearchUrl . ')';
        }
        return $strUserApiUrl;
    }

    /**
     * Function for search active directory user using skiptoken and name
     *
     * @param Obj $arrRequestParams is user request information parameters
     * @param Obj $strUserApiUrl    is fixed search criteria for the user search
     *
     * @name   _skipTokenUrl
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    private function _skipTokenUrl($arrRequestParams = [], $strUserApiUrl = '')
    {
        if (isset($arrRequestParams['skiptoken']) && $arrRequestParams['skiptoken'] <> '') {
            $strUserApiUrl = $strUserApiUrl . '&$skiptoken=' . $arrRequestParams['skiptoken'];
        }
        return $strUserApiUrl;
    }

    /**
     * Display individual user information from active directory
     *
     * @param String $strUserId is used to get data sent by client
     *
     * @name   show
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function show($strUserId = '')
    {
        if ($strUserId == '') {
            $this->errorUnauthorized('Invalid user id');
        }
        $strUserApiUrl = $this->generateGraphApiUrl('/users/' . $strUserId);
        $arrResponse = $this->makeGraphApiCall('GET', $strUserApiUrl, $this->accessToken, 'application/json');
        return $arrResponse;
    }

    /**
     * Function create the user using azure active directory graph api
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   save
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function save($request)
    {
        $arrPostAzureUser = $this->_transform($request->all());
        $strUserApiUrl = $this->generateGraphApiUrl('/users');

        $arrSaveAzureUser = $this->saveCommonAttribute($arrPostAzureUser);
        $arrAzureUser = $this->commonAttribute($arrPostAzureUser);
        $arrAzureUser = array_merge($arrAzureUser, $arrSaveAzureUser);

        $arrResponse = $this->makeGraphApiCall('POST', $strUserApiUrl, $this->accessToken, 'application/json', json_encode($arrAzureUser));
        return $arrResponse;
    }

    /**
     * Function update user information into azure cloud AD
     *
     * @param String $strUserId is used to get data sent by client
     * @param Obj    $request   is used to get data sent by client
     *
     * @name   update
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function update($strUserId, $request)
    {
        $arrAzureUser = [];
        $arrPostAzureUser = $this->_transform($request->all());
        $strUserApiUrl = $this->generateGraphApiUrl('/users/' . $strUserId);
        $arrAzureUser = $this->commonAttribute($arrPostAzureUser);
        $arrResponse = $this->makeGraphApiCall('PATCH', $strUserApiUrl, $this->accessToken, 'application/json', json_encode($arrAzureUser));
        return $arrResponse;
    }

    /**
     * Disable user from azure active directory
     *
     * @param String $id      is used to get data sent by client
     * @param Obj    $request is used to get data sent by client
     *
     * @name   destroy
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function destroy($id, $request)
    {
        $arrAzureUser = [];
        $strUserApiUrl = $this->generateGraphApiUrl('/users/' . $id);
        $arrAzureUser['accountEnabled'] = true;
        if (!$request->isMethod('patch')) {
            $arrAzureUser['accountEnabled'] = false;
        }
        $arrResponse = $this->makeGraphApiCall('PATCH', $strUserApiUrl, $this->accessToken, 'application/json', json_encode($arrAzureUser));
        return $arrResponse;
    }

    /**
     * Function convert form attributes into the azure portal attributes
     *
     * @param Array $arrInput is used to get data sent by client
     *
     * @name   _transform
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _transform($arrInput = [])
    {
        $arrAzureUser = [
            'givenName' => trim($arrInput['first_name']), 'surname' => trim($arrInput['last_name']),
            'streetAddress' => $arrInput['address'], 'postalCode' => trim($arrInput['pincode']),
            'mobile' => isset($arrInput['mobile_no']) ? trim($arrInput['mobile_no']) : ''
        ];
        $arrAzureCompany = $this->_createAzureCompany($arrInput);
        $arrAzureEmail = $this->_createAzureEmail($arrInput);
        $arrAzurePassword = $this->_createAzurePassword($arrInput);
        $arrAzureUser = array_merge($arrAzureUser, $arrAzureCompany, $arrAzureEmail, $arrAzurePassword);
        return $arrAzureUser;
    }

    /**
     * Function create the user email format into azure active directory email
     * format at the time of user creation
     *
     * @param Array $arrInput is user information array with email address
     *
     * @name   _createAzureEmail
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _createAzureEmail($arrInput = [])
    {
        $arrAzureEmailAddress = [];
        $alterEmail = $this->alterEmail;
        if (isset($arrInput['email']) && $arrInput['email'] <> '') {
            $arr['type'] = 'emailAddress';
            $arr['value'] = $arrInput['email'];
            $arrAzureEmailAddress['signInNames'][] = $arr;
            $arrAzureEmailAddress[$alterEmail] = $arrInput['email'];
        }
        return $arrAzureEmailAddress;
    }

    /**
     * Function create the user password format into azure active directory
     * password format at the time of user creation
     *
     * @param Array $arrInput is user information array with password
     *
     * @name   _createAzurePassword
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _createAzurePassword($arrInput = [])
    {
        $arrAzurePassword = [];
        if (isset($arrInput['password']) && $arrInput['password'] <> '') {
            $arrAzurePassword['passwordProfile']['password'] = $arrInput['password'];
            $arrAzurePassword['passwordProfile']['forceChangePasswordNextLogin'] = false;
        }
        return $arrAzurePassword;
    }

    /**
     * Function create the user company object into azure active directory
     *
     * @param Array $arrInput is user information array with password
     *
     * @name   _createAzureCompany
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _createAzureCompany($arrInput = [])
    {
        $strCompanyId = $this->companyIdExtension;
        $arrAzureCompany = [];
        if (isset($arrInput['company_id']) && $arrInput['company_id'] <> '') {
            $arrAzureCompany[$strCompanyId] = $arrInput['company_id'];
        }
        return $arrAzureCompany;
    }
}
