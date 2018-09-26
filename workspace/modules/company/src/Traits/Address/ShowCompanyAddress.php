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

use Illuminate\Database\QueryException;
use Modules\Company\Models\Address;
use Modules\Company\Models\Company;
use Modules\Company\Models\CompanyAddress;
use Modules\Company\Transformers\AddressTransformer;

/**
 * Show Company Address trait for company address module methods
 *
 * @name     ShowCompanyAddress
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ShowCompanyAddress
{

    /**
     * Function showCompanyAddress to delete company address
     *
     * @param String $strCompanyId Company ID
     * @param String $strAddressId Address ID
     *
     * @name   deleteAddress
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function showCompanyAddress($strCompanyId, $strAddressId)
    {
        try {
            $objCompany = $this->checkRecordExistsById(new Company(), $strCompanyId, 'company'); // check for valid company id
            $objAddress = $this->checkRecordExistsById(new Address(), $strAddressId, 'address'); // check for valid address id
            $objCompanyAddress = CompanyAddress::Where('CompanyId', '=', $objCompany["CompanyId"])->Where('AddressId', '=', $strAddressId)->first(); //get company address

            if (!$objCompanyAddress instanceof CompanyAddress) {
                return $this->errorNotFound("The company address doesn't exist.");
            }

            return $this->response->item($objAddress, new AddressTransformer(), ['key' => 'address']);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }
}
