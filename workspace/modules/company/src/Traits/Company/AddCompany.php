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

/**
 * Add Company Trait to store company data
 *
 * @name     AddCompany
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait AddCompany
{

    /**
     * Function to add company details
     *
     * @param Obj $request Request Object
     *
     * @name   addCompany
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function addCompany(Request $request)
    {
        try {
            $arrRequest = $request->all();
            $arrRequest['status'] = 0;
            $this->validateRequest($request, $this->_validationRules($arrRequest), $this->_validationMessage(), $this->_setAttributes(), $this->_setAttributeCode());
            $arrCompany = $this->_companyRepository->save($this->_companyTransformer->transformRequestParameters($arrRequest, 'create'));
            return $this->response->item($arrCompany, $this->_companyTransformer, ['key' => 'company']);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }
}
