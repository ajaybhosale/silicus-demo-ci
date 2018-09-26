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
 * @category Controller
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Company\Repositories\CompanyRepository;
use Modules\Company\Traits\Company\AddCompany;
use Modules\Company\Traits\Company\CompanyActivateDeactivate;
use Modules\Company\Traits\Company\CompanyAttributes;
use Modules\Company\Traits\Company\CompanyValidator;
use Modules\Company\Traits\Company\ListCompany;
use Modules\Company\Traits\Company\ShowCompany;
use Modules\Company\Traits\Company\UpdateCompany;
use Modules\Company\Transformers\CompanyTransformer;
use Modules\Infrastructure\Services\ValidateIsExists;
use Modules\Infrastructure\Traits\ManageToken;
use Webpatser\Uuid\Uuid;

/**
 * Controller class for Company Module
 *
 * @name     CompanyController
 * @category Controller
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CompanyController extends Controller
{

    private $_companyRepository;
    private $_companyTransformer;

    use ValidateIsExists;
    use ListCompany;
    use AddCompany;
    use UpdateCompany;
    use ShowCompany;
    use CompanyAttributes;
    use CompanyValidator;
    use ManageToken;
    use CompanyActivateDeactivate;

    /**
     * Default constructor function
     *
     * @param Obj $companyRepository  Repository
     * @param Obj $companyTransformer Transformer
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct(CompanyRepository $companyRepository, CompanyTransformer $companyTransformer)
    {
        $this->_companyRepository = $companyRepository;
        $this->_companyTransformer = $companyTransformer;
        parent::__construct();
    }

    /**
     * Function to get company list
     *
     * @param object $request request object

     * @return object returns json object
     *
     * @SWG\Get(
     *     path="/companies",
     *     summary="Get company list",
     *     description="Get company list",
     *     operationId="getCompany",
     *     produces={"application/json"},
     *     tags={"companies"},
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Parameter(
     *         description="records per page count",
     *         format="integer",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         type="integer"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Parameter(
     *         description="page number",
     *         format="integer",
     *         in="query",
     *         name="page",
     *         required=false,
     *         type="integer"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Parameter(
     *         description="sorting parameter. Sort by any get parameter eg -name,type. '-' for descending",
     *         format="string",
     *         in="query",
     *         name="sort",
     *         required=false,
     *         type="string"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Parameter(
     *         description="search by name",
     *         format="string",
     *         in="query",
     *         name="name",
     *         required=false,
     *         type="string"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Parameter(
     *         description="search by type",
     *         format="string",
     *         in="query",
     *         name="type",
     *         required=false,
     *         type="string"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(type="object", ref="#/definitions/CompanyGetData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the �Accept� header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $this->listCompany($request);
    }

    /**
     * To post company details
     *
     * @param object $request request object
     *
     * @return object returns json object
     *
     * @SWG\Post(
     *     path="/companies",
     *     summary="Add Company",
     *     description="Add Company",
     *     operationId="addCompany",
     *     tags={"companies"},
     * @SWG\Parameter(
     *         name="body",
     *         in="body",
     * @SWG\Schema(ref="#/definitions/CompanyPostData")
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object",
     *         ref="#/definitions/CompanyGetData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the â€˜Acceptâ€™ header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->addCompany($request);
    }

    /**
     * Function to update company details
     *
     * @param object $request      Request object
     * @param string $strCompanyId Company Id
     *
     * @return object returns json object
     *
     * @SWG\Put(
     *     path="/companies/{companyId}",
     *     summary="Update Company",
     *     description="Update Company",
     *     operationId="updateCompany",
     *     tags={"companies"},     *
     * @SWG\Parameter(
     *         description="ID of Company to fetch",
     *         format="string",
     *         in="path",
     *         name="companyId",
     *         required=true,
     *         type="string"
     *     ),
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Parameter(
     *         name="body",
     *         in="body",
     * @SWG\Schema(ref="#/definitions/CompanyPutData")
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object",
     *         ref="#/definitions/CompanyGetData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the â€˜Acceptâ€™ header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function update(Request $request, $strCompanyId)
    {
        return $this->updateCompany($request, $strCompanyId);
    }

    /**
     * To Activate the company i.e Merchant/ISO
     *
     * @param object $request request object
     * @param string $id      company id
     *
     * @return object returns json object
     *
     * @SWG\Put(
     *     path="/companies/{companyId}/activate",
     *     summary="Activate company (Merchant/ISO)",
     *     description="Activate company (Merchant/ISO)",
     *     operationId="activate",
     *     tags={"companies"},     *
     * @SWG\Parameter(
     *         description="ID of Company to fetch",
     *         format="string",
     *         in="path",
     *         name="companyId",
     *         required=true,
     *         type="string"
     *     ),
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object",
     *         ref="#/definitions/CompanyActivate")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the â€˜Acceptâ€™ header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function activate(Request $request, $id)
    {
        return $this->activateOrDeactivate($request, $id, 'activate');
    }

    /**
     * To Deactivate the company i.e Merchant/ISO
     *
     * @param object $request request object
     * @param string $id      company id
     *
     * @return object returns json object
     *
     * @SWG\Put(
     *     path="/companies/{companyId}/deactivate",
     *     summary="Deactivate company (Merchant/ISO)",
     *     description="Deactivate company (Merchant/ISO)",
     *     operationId="deactivate",
     *     tags={"companies"},     *
     * @SWG\Parameter(
     *         description="ID of Company to fetch",
     *         format="string",
     *         in="path",
     *         name="companyId",
     *         required=true,
     *         type="string"
     *     ),
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Parameter(
     *         name="body",
     *         in="body",
     * @SWG\Schema(ref="#/definitions/CompanyDeactivate")
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object",
     *         ref="#/definitions/CompanyDeactivate")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the â€˜Acceptâ€™ header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function deactivate(Request $request, $id)
    {
        return $this->activateOrDeactivate($request, $id, 'deactivate');
    }

    /**
     * Function to get company details by id
     *
     * @param string $strCompanyId Company Id
     *
     * @return object returns json object
     *
     * @SWG\Get(
     *     path="/companies/{companyId}",
     *     summary="fetch Company by company id",
     *     description="Get Company by id",
     *     operationId="getCompanyById",
     *     tags={"companies"},
     * @SWG\Parameter(
     *         description="ID of Company to fetch",
     *         format="string",
     *         in="path",
     *         name="companyId",
     *         required=true,
     *         type="string"
     *     ),
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object",
     *         ref="#/definitions/CompanyData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the â€˜Acceptâ€™ header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function show($strCompanyId)
    {
        return $this->showCompany($strCompanyId);
    }

    /**
     * Function to convert value to token
     *
     * @param Array $arrRequest Request Array
     *
     * @name   _setToken
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _setToken($arrRequest)
    {
        $arrRequest['tax_id'] = $this->setToken($arrRequest['tax_id']);

        return $arrRequest;
    }

    /**
     * Function to convert token to actual value
     *
     * @param Array $arrCompany Company Array
     *
     * @name   _getToken
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return arary
     */
    private function _getToken($arrCompany)
    {
        $arrCompany['TaxId'] = $this->getToken($arrCompany['TaxId']);

        return $arrCompany;
    }
}
