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
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Traits\Address;

use Modules\Company\Models\Address;

/**
 * Company Address module methods
 *
 * @name     AddressAttributes
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait AddressAttributes
{

    /**
     * Function set attributes for validation messages
     *
     * @name   _setAddressAttributes
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _setAddressAttributes()
    {
        $addressAttributes = [];
        $addressAttributes = [
            'NickName' => 'Nickname',
            'AddressLine1' => 'Address Line1',
            'AddressLine2' => 'Address Line2',
            'City' => 'City', 'State' => 'State',
            'PrimaryPhone' => 'Primary Phone',
            'SecondaryPhone' => 'Secondary Phone',
            'PrimaryEmail' => 'Primary Email',
            'SecondaryEmail' => 'Secondary Email',
            'Fax' => 'Fax', 'Type' => 'Type',
            'Zip' => 'Zip', 'CreatedAt' => 'Created At',
            'Etag' => 'Etag'
        ];

        return $addressAttributes;
    }

    /**
     * Function set attributes for address validation messages
     *
     * @name   _setAddressAttributeCode
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _setAddressAttributeCode()
    {
        $assignAddress = new Address();
        return $assignAddress->addressErrorCodes;
    }
}
