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
 * @category UnitTestCase
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Tests\CompanyTest;

use Modules\Company\Models\Company;
use Modules\Company\Repositories\CompanyRepository;
use Modules\Company\Transformers\CompanyTransformer;
use Tests\TestCase;
use GuzzleHttp\Client;

/**
 * Class for test cases
 *
 * @name     UnitTestCase
 * @category TestCase
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CompanyTest extends TestCase
{

    protected $companyRepository;
    protected $companyTransformer;
    protected $model;

    /**
     * Setup repository
     *
     * @name   setup
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function setup()
    {
        parent::setUp();
        $this->url = \Config::get('app.url');
        $this->client = new Client();
        $this->companyRepository = new CompanyRepository(new Company());
        $this->companyTransformer = new CompanyTransformer(new Company());
        $this->model = new Company();
    }

    /**
     * Function to test CRUD operation on API
     *
     * @name   testCRUDCompanyApi
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testCRUDCompanyApi()
    {
        $listResponse = $this->_listCompanies(); // list companies

        $this->assertEquals(200, $listResponse);
        $this->refreshApplication();
        $listPagi = $this->_listCompaniesPagi(); // list companies paginationation and sorting
        $this->assertEquals(200, $listPagi);
        $this->refreshApplication();

        $strCompanyId = $this->createCompany();

        //$this->assertEquals(200, $addResponse->getStatusCode());
        // $intCompanyId = json_decode($addResponse->getContent())->data->id;

        $editCompanyRes = $this->_editCompany($strCompanyId); //edit company
        $this->assertEquals(200, $editCompanyRes);
        $this->refreshApplication();
    }

    /**
     * Function to get list companies
     *
     * @name   _listCompanies
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _listCompanies()
    {
        $listRes = $this->client->get($this->url . '/companies');
        $this->refreshApplication();
        return $listRes->getStatusCode();
    }

    /**
     * Function to get list companies with pagination
     *
     * @name   _listCompaniesPagi
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _listCompaniesPagi()
    {
        $listResPagi = $this->client->get($this->url . '/companies?page=3&limit=5&sort=-Name');
        $this->refreshApplication();
        return $listResPagi->getStatusCode();
    }

    /**
     * Function to add company
     *
     * @name   _addCompany
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _addCompany()
    {
        $arrFakeCompany = factory(Company::class)->make()->toArray();
        $addCompanyResponse = $this->call('POST', 'companies', $arrFakeCompany);
        $this->refreshApplication();
        return $addCompanyResponse->getStatusCode();
    }

    /**
     * Function to edit company
     *
     * @param Obj $strCompanyId Company id
     *
     * @name   _editCompany
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _editCompany($strCompanyId)
    {
        $arrEditFakeCompany = factory(Company::class)->make()->toArray();
        $editCompanyResponse = $this->call('PUT', 'companies/' . $strCompanyId, $arrEditFakeCompany);
        $this->refreshApplication();
        return $editCompanyResponse->getStatusCode();
    }
}
