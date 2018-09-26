<?php
namespace Tests;

use Modules\Company\Models\Company;
use Modules\Group\Models\Group;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use GuzzleHttp\Client;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication;

    /**
     * Function create faker company with response ID
     *
     * @name   createCompany
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $strCompanyId
     */
    public function createCompany()
    {
        $this->model = new Company();
        $arrFakeCompany = factory(Company::class)->make()->toArray();
        //  dd($arrFakeCompany);
        $objCompanyResponse = $this->call('POST', 'companies', $arrFakeCompany);
        $this->refreshApplication();
        $strCompanyId = json_decode($objCompanyResponse->getContent())->data->id;
        return $strCompanyId;
    }
}
