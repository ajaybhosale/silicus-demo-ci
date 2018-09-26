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

use Illuminate\Validation\Rule;
use Modules\Company\Models\Company;
use Modules\Bank\Models\Odfi;
use Modules\Gateway\Models\Gateway;
use Modules\Sec\Models\Sec;

/**
 * CompanyValidator trait for group module methods
 *
 * @name     Company
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait CompanyValidator
{

    /**
     * Function for company input validation
     *
     * @param Array $arrRequest Request Array
     *
     * @name   _validationRules
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _validationRules($arrRequest = [])
    {
        //$strTable = config('database.default') . config('app.db_schema_seperator') . $this->_companyRepository->getTableName();
        $strTable = $this->_companyRepository->getTableName();
        $objCompany = new Company();
        $rules = $this->_basicRules($arrRequest, $objCompany, $strTable);
        return $rules;
    }

    /**
     * Function is used basic API validation rules
     *
     * @param Array  $arrRequest Request Array
     * @param Object $objCompany Company Id
     * @param String $strTable   Company Table Name
     *
     * @name   _basicRules
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _basicRules($arrRequest, $objCompany, $strTable)
    {
        $strCompanyId = (!empty($arrRequest) && isset($arrRequest['id'])) ? $arrRequest['id'] : 'NULL';
        return [
            'type' => 'bail|required|string|max:16|' . Rule::in(array_keys($objCompany->companyType)),
            'doing_business_as' => 'bail|required|string|max:1024',
            'name' => 'bail|required|unique:' . $strTable . ',Name,' . $strCompanyId . ',CompanyId,DeletedAt,NULL|regex:' . static::$REGEX_ALPHA_SPACE . '|max:255',
            'tax_id' => 'bail|required|integer|digits:9',
        ];
    }

    /**
     * Function is used for API validation message
     *
     * @name   _validationMessage
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _validationMessage()
    {
        return $messages = [
            'can_issue_credit.min' => 'The :attribute must be either 1 or 0.', 'can_issue_credit.max' => 'The :attribute must be either 1 or 0.',
            'can_issue_credit_approved.min' => 'The :attribute must be either 1 or 0.', 'can_issue_credit_approved.max' => 'The :attribute must be either 1 or 0.',
            'can_issue_credit_active.min' => 'The :attribute must be either 1 or 0.', 'can_issue_credit_active.max' => 'The :attribute must be either 1 or 0.',
            'has_bankstatements.min' => 'The :attribute must be either 1 or 0.', 'has_bankstatements.max' => 'The :attribute must be either 1 or 0.',
            'has_driver_license.min' => 'The :attribute must be either 1 or 0.', 'has_driver_license.max' => 'The :attribute must be either 1 or 0.',
            'has_other.min' => 'The :attribute must be either 1 or 0.', 'has_other.max' => 'The :attribute must be either 1 or 0.',
            'status.min' => 'The :attribute must be either 1 or 0.', 'status.max' => 'The :attribute must be either 1 or 0.',
            'sec_codes.*.in' => 'The :attribute data may have an invalid code.'
        ];
    }
}
