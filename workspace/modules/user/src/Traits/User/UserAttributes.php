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
 * User module attributes methods
 *
 * @name     UserAttributes
 * @category Attribute
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait UserAttributes
{

    /**
     * Function set attributes for User validation messages
     *
     * @name   _setUserAttributes
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _setUserAttributes()
    {
        $arrUserAttributes = [];
        $arrUserAttributes = [
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'email' => 'Email',
            'password' => 'Password',
            'confirm_pwd' => 'Confirm password',
            'address' => 'Address',
            'pincode' => 'Pincode',
            'company_id' => 'Company id',
            'mobile_no' => 'Phone no',
        ];
        return $arrUserAttributes;
    }

    /**
     * This Function set User attributes for validation messages
     *
     * @name   _setUserAttributeCode
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _setUserAttributeCode()
    {
        $userAttrbtCode = [
            'first_name' => 'UR001',
            'last_name' => 'UR002',
            'email' => 'UR003',
            'address' => 'UR004',
            'password' => 'UR005',
            'confirm_pwd' => 'UR006',
            'pincode' => 'UR007',
            'mobile_no' => 'UR008',
            'company_id' => 'UR009',
        ];
        return $userAttrbtCode;
    }
}
