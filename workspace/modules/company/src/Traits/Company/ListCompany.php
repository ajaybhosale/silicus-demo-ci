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
use Modules\Company\Transformers\CompanyTransformer;
use Modules\Infrastructure\Services\QueryMapper;
use Modules\Infrastructure\Services\TransformRequest;

/**
 * Master Company module methods
 *
 * @name     ListCompany
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ListCompany
{

    use TransformRequest;

    /**
     * Function to get company list
     *
     * @param Obj $request Request Object
     *
     * @name   listCompany
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function listCompany(Request $request)
    {
        try {
            $companyTrans = new CompanyTransformer();
            $request = $this->transformSearchOrSortRequest($request, $companyTrans->transformRequest()); // transform request parameter for search and sort
            $objCompany = new Company();
            $queryMapper = new QueryMapper($request);
            $companies = $queryMapper->createFromModel($objCompany);
            $companies = $companies->build()->paginate();
            return $this->response->paginator($companies, new CompanyTransformer, ['key' => 'company']);
        } catch (QueryException $exception) {
            dd($exception);
            $this->errorInternal();
        }
    }

    /**
     * Function to convert token to actual value
     *
     * @param Array $arrCompany Company Array
     *
     * @name   _getTokenValue
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return arary
     */
    private function _getTokenValue($arrCompany)
    {

        if (!empty($arrCompany->all())) {
            foreach ($arrCompany->all() as $company) {
                $company['TaxId'] = $this->getToken($company['TaxId']);
            }
        }

        return $arrCompany;
    }
}
