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

/**
 * DeleteCompanyAddress trait for company address module methods
 *
 * @name     DeleteAddress
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait DeleteCompanyAddress
{

    /**
     * Function deleteCompanyAddress to delete company address
     *
     * @param String $strAddressId Address Id
     * @param String $strCompanyId Company Id
     *
     * @name   deleteAddress
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function deleteCompanyAddress($strAddressId, $strCompanyId)
    {
        try {
            $objCompany = $this->checkRecordExistsById(new Company(), $strCompanyId, 'company'); // check for valid company id
            $objAddress = $this->checkRecordExistsById(new Address(), $strAddressId, 'address'); // check for valid address id
            $objCompanyAddress = CompanyAddress::Where('CompanyId', '=', $objCompany["CompanyId"])->Where('AddressId', '=', $strAddressId)->first(); //get company address

            if (!$objCompanyAddress instanceof CompanyAddress) {
                return $this->errorNotFound("The company address doesn't exist.");
            }
            $this->_addressRepository->delete($objAddress);  //delete company address
            return $this->response->array(['id' => $strAddressId, 'status' => 'deleted']);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }
}
