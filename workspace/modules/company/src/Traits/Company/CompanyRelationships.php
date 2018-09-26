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
 * @package  Company_Contact
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Traits\Company;

use Dingo\Api\Routing\Helpers;
use Illuminate\Database\QueryException;
use Modules\Company\Models\AssignSec;
use Modules\Sec\Models\Sec;
use Modules\Sec\Transformers\SecCodeTransformer;

/**
 * CompanyRelationships Trait have all relationship methods
 *
 * @name     CompanyRelationships
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait CompanyRelationships
{

    use Helpers;

    /**
     * Function to get company sec codes by com[any ID
     *
     * @param Object $objCompany Company object
     *
     * @name   includeCompanySecCodes
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function includeCompanySecCodes($objCompany)
    {
        try {
            $objAssignSec = new AssignSec();
            $objSec = new Sec();

            $arrCompanySec = $objAssignSec->where('CompanyId', $objCompany->CompanyId)->get();

            $arrSecCodes = $objSec->whereIn('SecId', $arrCompanySec->pluck('SecId'))->orderBy('code')->get();
            $objSecTranformer = new SecCodeTransformer();
            $arrCompanySec = [];
            if (!empty($arrSecCodes->all())) {
                foreach ($arrSecCodes->all() as $arrSecCodes) {
                    $arrCompanySec[] = $objSecTranformer->transform($arrSecCodes);
                }
            }

            return $arrCompanySec;
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }
}
