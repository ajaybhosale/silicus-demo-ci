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

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Company\Models\Company;
use Webpatser\Uuid\Uuid;

/**
 * UpdateCompany trait to update company details
 *
 * @name     UpdateCompany
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait UpdateCompany
{

    /**
     * Function to add company details
     *
     * @param Obj $request      Request Object
     * @param Obj $strCompanyId Company Id
     *
     * @name   updateCompany
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function updateCompany(Request $request, $strCompanyId)
    {
        try {
            // check for valid company id
            $objCompany = $this->checkRecordExistsById(new Company(), $strCompanyId, 'company');
            $arrRequest = $request->all();
            $arrRequest['id'] = $strCompanyId;
            $this->validateRequest($request, $this->_validationRules($arrRequest), $this->_validationMessage(), $this->_setAttributes(), $this->_setAttributeCode());

            $objCompany = $this->_processUpdateCompanyTransaction($arrRequest, $objCompany, $request);

            return $this->response->item($objCompany, $this->_companyTransformer, ['key' => 'company']);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }

    /**
     * Function to update Contact DB Transactions
     *
     * @param Array  $arrRequest Request Array
     * @param Object $objCompany Company Object
     * @param Object $request    Company Request
     *
     * @name   _processEditContactTransaction
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return Object
     */
    private function _processUpdateCompanyTransaction($arrRequest, $objCompany, $request)
    {
        return
            DB::transaction(function () use ($arrRequest, $objCompany, $request) {
                $arrCompanyTransform = $this->_companyTransformer->transformRequestParameters($arrRequest);
                $objCompany = $this->_companyRepository->update($objCompany, $arrCompanyTransform);
                return $objCompany;
            });
    }
}
