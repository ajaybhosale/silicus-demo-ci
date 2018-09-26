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
class CompanyAddressWebController extends Controller
{

    public $theme;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->theme = \Config::get('app.theme');
    }

    /**
     * It displays list and Manage Landing page
     *
     * @param string $companyId Company ID
     *
     * @name   index
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function index($companyId)
    {

        $jsFiles = [
            'https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js',
            'https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js',
            'https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js',
        ];

        $cssFiles = [
            'https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css'
        ];

        $this->loadJsCSS($jsFiles, $cssFiles);

        $url = $this->url;
        //return view('address::index')->with(['results' => $response, 'companyId' => $companyId, 'url' => $this->url]);
        return view('address::index', compact('response', 'companyId', 'url'));
    }

    /**
     * To format address
     *
     * @param string $request      Request object
     * @param string $strCompanyId Company ID
     *
     * @return object returns json object
     */
    public function getAddresses(Request $request, $strCompanyId)
    {

        //$searchTerm = $request->input('search')['value'];
        //$searchQuery = $searchTerm == '' ? '' : '&primary_email=' + '*' + $searchTerm + '*';
        $columns = ['nickname'];   // List of columns (field name) those are sortable and searchable
        $filter['search'] = $request->input('search')['value'];
        $filter['start'] = $request->input('start');
        $filter['length'] = $request->input('length');
        $filter['sortBy'] = $columns[$request->input('order')[0]['column']];
        $filter['sortOrder'] = $request->input('order')[0]['dir'];
        $companyId = $request->input('companyId');
        $searchTerm = $filter['search'];
        $sortBy = $filter['sortBy'];
        if ('asc' == $filter['sortOrder']) {
            $sortOrder = '';
        } else {
            $sortOrder = '-';
        }
        //dd($searchTerm);
        try {
            $searchQuery = $searchTerm == '' ? '' : "&primary_email=*$searchTerm*";
            $sortQuery = $sortBy == '' ? '' : "&sort=" . $sortOrder . "nickname";

//        /$searchQuery = "primary_email=aaa*";
            //dd($searchQuery);
            $client = new Client();
            $request = $client->get($this->url . '/companies/' . $strCompanyId . '/addresses?' . $searchQuery . $sortQuery);
            $response = json_decode($request->getBody());
            $index = 0;
            $addresses = [];
            foreach ($response->data as $address) {
                $addresses[$index]['edit'] = $address->id;
                $addresses[$index]['view'] = $address->id;
                $addresses[$index]['delete'] = $address->id;
                $addresses[$index]['nickname'] = $address->nickname;
                $addresses[$index]['email'] = $address->primary_email;
                $addresses[$index]['phone'] = $address->primary_phone;

                $index++;
            }
            $adddressList = $addresses;
            $total = $response->meta->pagination->total;
            $result = ["draw" => uniqid(), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $adddressList];

            return response()->json($result);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            dd($response);
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            $errors = $responseBodyAsString->errors;
            $errors = $this->handleError($errors);
            $result = ["draw" => uniqid(), "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []];
        }
    }

    /**
     * Add address through view
     *
     * @param string $request      Request Object
     * @param string $strCompanyId Company ID
     *
     * @return object returns json object
     */
    public function addAddress(Request $request, $strCompanyId)
    {
        $errors = [];
        return view('address::add', compact('strCompanyId', 'errors'));
    }

    /**
     * Edit address through view
     *
     * @param string $request      Object request
     * @param string $strCompanyId Company ID
     * @param string $addressId    Address ID
     *
     * @return object returns json object
     */
    public function editAddress(Request $request, $strCompanyId, $addressId)
    {
        $errors = [];
        try {
            $client = new Client();
            $request = $client->get($this->url . '/companies/' . $strCompanyId . '/addresses/' . $addressId);

            $response = json_decode($request->getBody())->data;
            $url = $this->url;
            return view('address::edit', compact('strCompanyId', 'errors', 'addressId', 'response', 'url'));
        } catch (ClientException $ex) {
            print_r($ex->getMessage());
        }
    }

    /**
     * Add address
     *
     * @param string $request Object request
     *
     * @return object returns json object
     */
    public function storeAddress(Request $request)
    {

        $input = Input::all();
        $strCompanyId = $request['companyId'];
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json']
        ]);
        try {
            $response = $client->post($this->url . '/companies/' . $strCompanyId . '/addresses', ['body' => json_encode($input)]);
            return redirect($this->url . '/companies/' . $strCompanyId . '/address/list')->with('successMessage', 'Company address added successfully');
            //return redirect()->to(url() . '/company/' . $companyId . '/address');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            $errors = $responseBodyAsString->errors;
            $errors = $this->handleError($errors);
            return view('address::add', compact('strCompanyId', 'errors', 'input'));
        }
    }

    /**
     * Add address
     *
     * @param array $errors array
     *
     * @return object returns array
     */
    public function handleError($errors)
    {
        foreach ($errors as $error) {
            $arrErrors[$error->code] = $error->message;
        };

        return $arrErrors;
    }

    /**
     * Add address
     *
     * @param object $request request object
     *
     * @return object returns json object
     */
    public function updateAddress(Request $request)
    {
        $input = $response = Input::all();
        $strCompanyId = $request['companyId'];
        $addressId = $request['addressId'];
        $client = new Client([
            'headers' => ['Content-Type' => 'application/json']
        ]);
        try {
            $response = $client->put($this->url . '/companies/' . $strCompanyId . '/addresses/' . $addressId, ['body' => json_encode($input)]);
            return redirect($this->url . '/companies/' . $strCompanyId . '/address/list')->with('successMessage', 'Company address updated successfully');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            $errors = $responseBodyAsString->errors;
            $errors = $this->handleError($errors);
            //get data
            $client = new Client();
            $request = $client->get($this->url . '/companies/' . $strCompanyId . '/addresses/' . $addressId);

            $response = json_decode($request->getBody())->data;
            return view('address::edit', compact('strCompanyId', 'errors', 'response', 'addressId'));
        }
    }

    /**
     * Delete address
     *
     * @param string $companyId Company ID
     * @param string $addressId Address ID
     *
     * @return object returns json object
     */
    public function deleteAddress($companyId, $addressId)
    {
        $client = new Client();
        try {
            $response = $client->delete($this->url . '/companies/' . $companyId . '/addresses/' . $addressId);
            // return redirect($this->url . '/companies/' . $companyId . '/address/list')->with('successMessage', 'Company address deleted successfully');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            $errors = $responseBodyAsString->errors;
        }
    }
}
