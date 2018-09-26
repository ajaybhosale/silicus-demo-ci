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

use Illuminate\Database\ClientException;
use Illuminate\Http\Request;
use Modules\Company\Models\Address;
use Modules\Company\Models\Company;
use Modules\Company\Models\CompanyAddress;
use Modules\Company\Transformers\AddressTransformer;
use Modules\Company\Traits\AmendmentNote\AddNote;

/**
 * UpdateCompanyAddress trait to update company address
 *
 * @name     UpdateAddress
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait UpdateCompanyAddress
{

    use AddNote;

    /**
     * Function to update company address
     *
     * @param Obj    $request      Request Object
     * @param String $strAddressId Address Id
     * @param String $strCompanyId company id
     *
     * @name   updateCompanyAddress
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function updateCompanyAddress(Request $request, $strAddressId, $strCompanyId)
    {
        try {
            $objCompany = $this->checkRecordExistsById(new Company(), $strCompanyId, 'company'); //check for valid company id
            $objAddress = $this->checkRecordExistsById(new Address(), $strAddressId, 'address'); // check for valid address id
            $this->validateRequest($request, $this->_addressValidationRules($strAddressId), $this->_addressValidationMessage(), $this->_setAddressAttributes(), $this->_setAddressAttributeCode());
            $objCompanyAddress = CompanyAddress::Where('CompanyId', '=', $objCompany["CompanyId"])->Where('AddressId', '=', $strAddressId)->first();
            if (!$objCompanyAddress instanceof CompanyAddress) {
                return $this->errorNotFound("The company address doesn't exist.");
            }
            $objAddress = $this->getDifference($objAddress, $this->_addressTransformer->transformRequestParameters($request->all()));
            $arrOldValue = $objAddress->getOriginal();
            $arrNewValue = $objAddress->getAttributes();
            //if (!empty(array_diff($arrOldValue, $arrNewValue))) {
            $objAddress = $this->_addressRepository->update($objAddress, $this->_addressTransformer->transformRequestParameters($request->all()));
            // }
            return $this->response->item($objAddress, $this->_addressTransformer, ['key' => 'address']);
        } catch (ClientException $exception) {
            $this->errorUnauthorized('Invalid Access Token');
        }
    }
}
