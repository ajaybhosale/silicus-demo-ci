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
 * PHP version 7
 *
 * @category Category
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Traits\User;

use Modules\User\Transformers\UserTransformer;

/**
 * Add user trait create new user into active directory B2C
 *
 * @name     AddUser
 * @category Trait
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait AddUser
{

    /**
     * Create user method
     *
     * @param Request $objRequest is Request Object
     * @param Object  $objCloud   is Cloud   Object
     *
     * @name   addUser
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function addUser($objRequest, $objCloud)
    {
        $arrResponse = [];
        $this->validateRequest($objRequest, $this->_userValidationRules('store'), $this->_userValidationMessage('store'), $this->_setUserAttributes(), $this->_setUserAttributeCode());
        $objUserInfo = $objCloud->save($objRequest);
        $arrUserInfo = json_decode($objUserInfo, true);
        if (isset($arrUserInfo['data']['error']) && $arrUserInfo['data']['error'] <> '') {
            $arrResponse = array('status_code' => 422, 'message' => 'Validation error');
            $arrResponse = array_merge($arrResponse, $this->_errorInvalidFirstName($arrUserInfo));
            $arrResponse = array_merge($arrResponse, $this->_errorEmailExist($arrUserInfo));
            return $arrResponse;
        }
        return $this->response()->item(json_decode($objUserInfo), new UserTransformer, ['key' => 'user']);
    }

    /**
     * Function check invalid first/last name
     *
     * @param Array $arrUserInfo is user error information array
     *
     * @name   _errorInvalidFirstName
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _errorInvalidFirstName($arrUserInfo)
    {
        $arrResponse = [];
        if (isset($arrUserInfo['data']['error']['message']) && strpos($arrUserInfo['data']['error']['message'], 'mailNickname') !== false) {
            $arrResponse['errors'][] = array('code' => 'UR001', 'type' => 'not_valid', 'message' => 'Invalid value specified for property first or last name', 'more_info' => 'UR001');
        }
        return $arrResponse;
    }

    /**
     * Function checks email exist or not at azure end
     *
     * @param Array $arrUserInfo is user error information array
     *
     * @name   _errorEmailExist
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _errorEmailExist($arrUserInfo)
    {
        $arrResponse = [];
        if (isset($arrUserInfo['data']['error']['message']) && strpos($arrUserInfo['data']['error']['message'], 'signInNames ') !== false) {
            $arrResponse['errors'][] = array('code' => 'UR003', 'type' => 'Not_Valid', 'message' => 'An account for the specified email address already exists. Try another email address', 'more_info' => 'UR001');
        }
        return $arrResponse;
    }
}
