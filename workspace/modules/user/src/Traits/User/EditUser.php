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

/**
 * User module edit AD user
 *
 * @name     EditUser
 * @category Trait
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait EditUser
{

    /**
     * Update user basic information using user Id
     *
     * @param String  $strUserId  is User Id
     * @param Request $objRequest is Object of Request
     * @param Object  $objCloud   is Object of Azure Cloud
     *
     * @name   editUser
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function editUser($strUserId, $objRequest, $objCloud)
    {
        $arrResponse = [];
        $this->validateRequest($objRequest, $this->_userValidationRules('update'), $this->_userValidationMessage('update'), $this->_setUserAttributes(), $this->_setUserAttributeCode());
        $objCloudError = $objCloud->update($strUserId, $objRequest);
        if (!empty($objCloudError)) {
            $arrResponse = json_decode($objCloudError, true);
            $arrResponse = ['status_code' => 422, 'message' => 'Validation error'];
            $arrResponse['errors'][] = [
                'code' => 'UR001', 'type' => 'not_valid',
                'message' => 'Invalid value specified for property first name', 'more_info' => 'UR001'
            ];
        } else {
            $strUserFullName = $objRequest->first_name . ' ' . $objRequest->last_name;
            $aro = $this->_aroRepository->updateUserNameByModelId($strUserFullName, $strUserId);
            $arrResponse = array('message' => 'User sucessfully updated', 'status_code' => static::SUCCESS_CODE);
        }
        return $arrResponse;
    }
}
