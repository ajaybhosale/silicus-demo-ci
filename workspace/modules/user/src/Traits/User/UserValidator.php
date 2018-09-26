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
 * User module Validation methods
 *
 * @name     UserValidator
 * @category Trait
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait UserValidator
{

    /**
     * Validation rules for add AD user module for POST and PUT
     *
     * @param Obj $strAction is used to get data sent by client
     *
     * @name   _userValidationRules
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _userValidationRules($strAction)
    {
        $arrValidateRules = [
            'first_name' => 'bail|required|max:50|string|regex:' . static::$REGEX_ALPHA_SPACE,
            'last_name' => 'bail|required|max:50|string|regex:' . static::$REGEX_ALPHA_SPACE,
            'address' => 'bail|required|max:255|string',
            'pincode' => 'bail|required|digits:5|integer',
            'mobile_no' => 'bail|required|digits:10|integer'
        ];
        if ('store' == $strAction) {
            $arrValidateRules = [
                'email' => 'bail|required|email|max:100',
                'password' => 'bail|required|min:8|regex:' . static::$REGEX_PASSWORD,
                'confirm_pwd' => 'bail|required|same:password',
                'company_id' => 'bail|required|string',
            ];
        }
        return $arrValidateRules;
    }

    /**
     * Function for USER validation messages
     *
     * @param action $strAction follows the name of activity
     *
     * @name   _userValidationMessage
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _userValidationMessage($strAction)
    {
        return $arrUserMsg = [
            'password.regex' => 'The :attribute must *contain at least (1) upper case letter. * contain at least (1) lower case letter. * contain at least (1) number or special character.',
        ];
    }
}
