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

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\Company;
use Modules\Company\Transformers\AddressTransformer;
use Webpatser\Uuid\Uuid;

/**
 * Trait AddCompanyAddress to add company address
 *
 * @name     AddCompanyAddress
 * @category Trait
 * @package  Address
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait AddCompanyAddress
{

    /**
     * Function addCompanyAddress to add Company Address
     *
     * @param Obj $request      Request $request
     * @param Obj $strCompanyId Request $strCompanyId
     *
     * @name   addAddress
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function addCompanyAddress(Request $request, $strCompanyId)
    {

        try {
            // check for valid company id
            $this->checkRecordExistsById(new Company(), $strCompanyId, 'company');
            $requestAddress = $request->all();
            $requestAddress['company_id'] = $strCompanyId;
            // Validation
            $this->validateRequest($request, $this->_addressValidationRules(), $this->_addressValidationMessage(), $this->_setAddressAttributes(), $this->_setAddressAttributeCode());

            $objAddress = $this->_addCompanyAddressTransaction($requestAddress);

            return $this->response->item($objAddress, new AddressTransformer(), ['key' => 'address']);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }

    /**
     * Function _addCompanyAddressTransaction to add Company Address DB Transactions
     *
     * @param Array $requestAddress Request Array
     *
     * @name   _addCompanyAddressTransaction
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return Object
     */
    private function _addCompanyAddressTransaction($requestAddress)
    {
        return
            DB::transaction(function () use ($requestAddress) {

                // Add Address data
                $objAddress = $this->_addressRepository->save($this->_addressTransformer->transformRequestParameters($requestAddress));

                // Add company address mapping
                // $companyAddress['CompanyAddressId'] = Uuid::generate()->string;
                $companyAddress['CompanyId'] = $requestAddress["company_id"];
                $companyAddress['AddressId'] = $objAddress->AddressId;

                $this->_companyAddressModel->create($companyAddress);

                return $objAddress;
            });
    }
}
