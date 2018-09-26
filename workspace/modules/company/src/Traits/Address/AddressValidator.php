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
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Traits\Address;

use Illuminate\Validation\Rule;
use Modules\Company\Models\Address;

/**
 * Trait AddressValidator for address validation rules
 *
 * @name     AddressValidator
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait AddressValidator
{

    /**
     * Function _addressValidationRules is used for API validation for Address
     *
     * @name   _addressValidationRules
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _addressValidationRules()
    {
        $objAddress = new Address();

        $arrAddressRules = [
            'nickname' => 'bail|required|string|max:256',
            'address_line1' => 'bail|required|string|max:1024', 'address_line2' => 'bail|required|string|max:1024',
            'city' => 'bail|required|string|max:64', 'state' => 'bail|required|alpha|max:2',
            'zip' => 'bail|required|integer|digits:5', 'primary_phone' => 'bail|required|digits:10|integer',
            'secondary_phone' => 'bail|digits:10|integer|nullable', 'primary_email' => 'bail|email|max:256|nullable',
            'secondary_email' => 'bail|email|max:256|nullable', 'fax' => 'bail|integer|digits_between:0,16|nullable',
            'address_type' => 'bail|required|string|max:16|' . Rule::in(array_keys($objAddress->addressType))
        ];

        return $arrAddressRules;
    }

    /**
     * Function _addressValidationMessage is used for API validation message for Address
     *
     * @name   _addressValidationMessage
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _addressValidationMessage()
    {
        return $messages = [
            'fax.digits_between' => 'The :attribute must be maximum 16 digits.',
        ];
    }
}
