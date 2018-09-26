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
use Modules\Company\Models\Company;

/**
 * CompanyActivateDeactivate trait to activate or deactivate the company
 *
 * @name     CompanyActivateDeactivate
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait CompanyActivateDeactivate
{

    /**
     * Function activateOrDeactivate to activate/deactivate company
     *
     * @param Obj    $request      Request Object
     * @param String $strCompanyId Company Id
     * @param String $strAction    Activate/deactivate action
     *
     * @name   activateOrDeactivate
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function activateOrDeactivate(Request $request, $strCompanyId, $strAction)
    {
        try {
            $arrAction = [];
            // check for valid company id
            $objCompany = $this->checkRecordExistsById(new Company(), $strCompanyId, 'company');
            $arrAction['status'] = (isset($strAction) && $strAction == 'activate') ? 1 : 0;
            $arrAction = array_merge($request->all(), $arrAction);
            $intStatus = $this->_checkValidDeactivationReason($arrAction);
            if ($intStatus === false) {
                return $this->errorInternal('Reason for deactivation is required');
            }
            //update company
            $objCompany = $this->_companyRepository->update($objCompany, $this->_companyTransformer->transformRequestParameters($arrAction));
            return $this->response->item($objCompany, $this->_companyTransformer, ['key' => 'company']);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }

    /**
     * Function _checkValidDeactivationReason check deactivation reason
     *
     * @param Obj $arrAction is deactivation reason data
     *
     * @name   _checkValidDeactivationReason
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return boolean
     */
    private function _checkValidDeactivationReason($arrAction)
    {
        if (!isset($arrAction['reason_for_deactivation']) && $arrAction['status'] == 0) {
            return false;
        }
        return true;
    }
}
