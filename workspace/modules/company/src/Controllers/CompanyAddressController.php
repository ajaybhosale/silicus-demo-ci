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
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Modules\Company\Models\Company;
use Modules\Company\Models\CompanyAddress;
use Modules\Company\Repositories\AddressRepository;
use Modules\Company\Repositories\CompanyRepository;
use Modules\Company\Traits\Address\AddCompanyAddress;
use Modules\Company\Traits\Address\AddressAttributes;
use Modules\Company\Traits\Address\AddressValidator;
use Modules\Company\Traits\Address\DeleteCompanyAddress;
use Modules\Company\Traits\Address\ListCompanyAddress;
use Modules\Company\Traits\Address\ShowCompanyAddress;
use Modules\Company\Traits\Address\UpdateCompanyAddress;
use Modules\Company\Transformers\AddressTransformer;
use Modules\Infrastructure\Services\ValidateIsExists;

/**
 * CompanyAddressController is controller class for Company Address Module
 *
 * @name     CompanyAddressController
 * @category Controller
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CompanyAddressController extends Controller
{

    private $_companyRepository;
    private $_addressRepository;
    private $_addressTransformer;
    private $_companyAddressModel;

    use AddressValidator;
    use AddressAttributes;
    use AddCompanyAddress;
    use UpdateCompanyAddress;
    use DeleteCompanyAddress;
    use ListCompanyAddress;
    use ShowCompanyAddress;
    use ValidateIsExists;

    /**
     * Default constructor function
     *
     * @param Obj $_addressRepository  Repository Object
     * @param Obj $_addressTransformer Transformer Object
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct(AddressRepository $_addressRepository, AddressTransformer $_addressTransformer)
    {
        $this->_addressRepository = $_addressRepository;
        $this->_addressTransformer = $_addressTransformer;
        $this->_companyAddressModel = new CompanyAddress();
        $this->_companyRepository = new CompanyRepository(new Company());

        parent::__construct();
    }

    /**
     * To get all company address list
     *
     * @param object $request      request object
     * @param string $strCompanyId Company ID
     *
     * @return object returns json object
     *
     * @SWG\Get(
     *     path="/companies/{companyId}/addresses",
     *     summary="Get all Company Addresses",
     *     description="get all company address data",
     *     operationId="getCompanyAddress",
     *     tags={"addresses"},
     * @SWG\Parameter(
     *         description="ID of company to fetch",
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
     *         description="Address type: primary or secondary",
     *         format="string",
     *         in="query",
     *         name="address_type",
     *         required=false,
     *         type="string"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Parameter(
     *         description="Zip Code",
     *         format="integer",
     *         in="query",
     *         name="zip",
     *         required=false,
     *         type="integer"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object",
     *         ref="#/definitions/AddressGetData")
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
     *         description="Not acceptable - The response content type does not match the ‘Accept’ header value"
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
     *         response="422",
     *         description="Un-processable entity"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function index(Request $request, $strCompanyId)
    {
        return $this->listCompanyAddress($request, $strCompanyId);
    }

    /**
     * To get all company address by ID
     *
     * @param string $request      Object
     * @param string $strAddressId Address ID
     *
     * @return object returns json object
     */
    public function listAddress(Request $request, $strCompanyId)
    {
        return view('listAddress', compact('strCompanyId'));
    }

    /**
     * To get all company address by ID
     *
     * @param string $strCompanyId Company ID
     * @param string $strAddressId Address ID
     *
     * @return object returns json object
     *
     * @SWG\Get(
     *     path="/companies/{companyId}/addresses/{addressId}",
     *     summary="Show Company Address",
     *     description="get company address data by company id and address id",
     *     operationId="showCompanyAddress",
     *     tags={"addresses"},
     * @SWG\Parameter(
     *         description="ID of company to fetch",
     *         format="string",
     *         in="path",
     *         name="companyId",
     *         required=true,
     *         type="string"
     *     ),
     * @SWG\Parameter(
     *         description="ID of company address to fetch",
     *         format="string",
     *         in="path",
     *         name="addressId",
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
     *         ref="#/definitions/AddressGetData")
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
     *         description="Not acceptable - The response content type does not match the ‘Accept’ header value"
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
     *         response="422",
     *         description="Un-processable entity"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function show($strCompanyId, $strAddressId)
    {
        return $this->showCompanyAddress($strCompanyId, $strAddressId);
    }

    /**
     * Function to add post address details
     *
     * @param object $requestAddress request object
     * @param object $strCompanyId   Company Id
     *
     * @return object returns json object
     *
     * @SWG\Post(
     *     path="/companies/{companyId}/addresses",
     *     summary="Add Address",
     *     description="Add Address",
     *     operationId="addAddress",
     *     tags={"addresses"},
     * @SWG\Parameter(
     *         description="ID of company to fetch",
     *         format="string",
     *         in="path",
     *         name="companyId",
     *         required=true,
     *         type="string"
     *     ),
     * @SWG\Parameter(
     *         name="body",
     *         in="body",
     * @SWG\Schema(ref="#/definitions/AddressPostData")
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object",
     *         ref="#/definitions/GetSingleAddressData")
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
     *         description="Not acceptable - The response content type does not match the accepted header value"
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
    public function store(Request $requestAddress, $strCompanyId)
    {
        return $this->addCompanyAddress($requestAddress, $strCompanyId);
    }

    /**
     * Function to update address details
     *
     * @param object $request      request object
     * @param string $id           id
     * @param string $strCompanyId company id
     *
     * @return object returns json object
     *
     * @SWG\Put(
     *     path="/companies/{companyId}/addresses/{addressId}",
     *     summary="Update Address",
     *     description="Update Address",
     *     operationId="updateAddress",
     *     tags={"addresses"},     *
     * @SWG\Parameter(
     *         description="ID of company to fetch",
     *         format="string",
     *         in="path",
     *         name="companyId",
     *         required=true,
     *         type="string"
     *     ),
     * @SWG\Parameter(
     *         description="ID of Address to fetch",
     *         format="string",
     *         in="path",
     *         name="addressId",
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
     * @SWG\Schema(ref="#/definitions/AddressPutData")
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object",
     *         ref="#/definitions/GetSingleAddressData")
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
    public function update(Request $request, $strCompanyId, $id)
    {
        return $this->updateCompanyAddress($request, $id, $strCompanyId);
    }

    /**
     * To delete address data by Id
     *
     * @param String $id           Address ID
     * @param Obj    $strCompanyId Request company id
     *
     * @return object returns json object
     *
     * @SWG\Delete(
     *     path="/companies/{companyId}/addresses/{addressId}",
     *     summary="Delete address by ID",
     *     description="Delete existing address by given ID",
     *     operationId="deleteAddressById",
     *     tags={"addresses"},
     * @SWG\Parameter(
     *         description="ID of company to fetch",
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
     *         description="ID of Address to delete",
     *         format="string",
     *         in="path",
     *         name="addressId",
     *         required=true,
     *         type="string"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object", ref="#/definitions/AddressDeleteData")
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
     *         description="Not acceptable - The response content type does not match the ‘Accept’ header value"
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
     *         response="422",
     *         description="Un-processable entity"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function destroy($strCompanyId, $id)
    {
        return $this->deleteCompanyAddress($id, $strCompanyId);
    }
}
