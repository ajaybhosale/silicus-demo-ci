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
namespace Modules\Company\Traits\Company;

use Modules\Company\Models\Company;
use Modules\Sec\Models\Sec;

/**
 * CompanyAttributes trait forr Company module methods
 *
 * @name     CompanyAttributes
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait CompanyAttributes
{

    /**
     * Function to set attributes for validation messages
     *
     * @name   _setAttributes
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _setAttributes()
    {
        return $attributes = [
            'type' => 'Type', 'odfi_id' => 'ODFI ID',
            'name' => 'Name', 'doing_business_as' => 'Doing Business As', 'tax_id' => 'Tax ID',
            'created_by' => 'Created By',
            "sec_codes" => 'Sec Codes',
            "sec_codes.*" => 'Sec Codes'
        ];
    }

    /**
     * Function to set attributes for validation messages
     *
     * @name   _setAttributes
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _setAttributeCode()
    {
        $company = new Company();
        return $company->companyErrCodes;
    }
}
