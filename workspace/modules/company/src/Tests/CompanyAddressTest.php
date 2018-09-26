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
namespace Modules\Company\Tests\CompanyAddressTest;

use Modules\Company\Models\Address;
use Modules\Company\Traits\Address\AddCompanyAddress;
use Modules\Company\Traits\Address\UpdateCompanyAddress;
use Modules\Company\Traits\Address\DeleteCompanyAddress;
use Modules\Company\Traits\Address\AddressAttributes;
use Modules\Company\Traits\Address\AddressValidator;
use Webpatser\Uuid\Uuid;
use Tests\TestCase;

/**
 * Class for test cases
 *
 * @name     UnitTestCase
 * @category TestCase
 * @package  Address
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CompanyAddressTest extends TestCase
{

    protected $model;

    use AddCompanyAddress;
    use AddressAttributes;
    use AddressValidator;
    use UpdateCompanyAddress;
    use DeleteCompanyAddress;

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
        $this->model = new Address();
    }

    /**
     * Test CRUD operation on API
     *
     * @name   testCRUDAddressApi
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testCRUDAddressApi()
    {
        $strCompanyId = $this->createCompany();
        $arrFakeAddr = factory(Address::class)->make()->toArray();
        $strCompId = $arrFakeAddr["company_id"];
        $this->refreshApplication();

        $addResponse = $this->_addAddress($arrFakeAddr); // add address

        $this->assertEquals(200, $addResponse->status());
        $intAddressId = json_decode($addResponse->getContent())->data->id;  // get last inserted row by id
        $this->refreshApplication();

        $editAddrResponse = $this->_updateAddress($strCompId, $intAddressId);
        $this->assertEquals(200, $editAddrResponse->status());

        $strAddressIds = json_decode($editAddrResponse->getContent())->data->id;
        $deleteResponse = $this->_deleteAddress($strCompId, $strAddressIds);  // delete address by ID
        $this->assertEquals(200, $deleteResponse->status());
    }

    /**
     * Test CRUD operation on API for failure
     *
     * @name   testCRUDAddressFail
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testCRUDAddressFail()
    {
        $arrFakeAddress = factory(Address::class)->make()->toArray();
        $companyId = $arrFakeAddress["company_id"];
        $this->refreshApplication();
        $strFakeID = $this->_getFakeAddrId();
        $arrFakeAddress["company_id"] = Uuid::generate(4)->string;
        $addResponse = $this->_addAddress($arrFakeAddress);
        // $this->assertEquals(404, $addResponse->status());
        $this->refreshApplication();

        // $editResBlank = $this->_updateAddress($companyId, ''); // blank ID in edit
        //  $this->refreshApplication();
        //  $this->assertEquals(405, $editResBlank->status());
        $editResInvalid = $this->_updateAddress($companyId, 'fdfsfsdfdsf');  // invalid ID in edit
        $this->refreshApplication();
        $this->assertEquals(404, $editResInvalid->status());
        $this->refreshApplication();

        $delResponse = $this->_deleteAddress($companyId, $strFakeID);    //delete address by ID
        $this->assertEquals(404, $delResponse->status());
        $this->refreshApplication();
        $strTestID = 'ab7ad310-6985-11e8-9b53-213c65f8c3b7';

        $deleResponse = $this->_deleteAddress($companyId, $strTestID);    //delete address by invalid ID
        $this->assertEquals(404, $deleResponse->status());
        $this->refreshApplication();
    }

    /**
     * Function to test add address
     *
     * @param Array $arrFakeAddr Company array data
     *
     * @name   _addAddress
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _addAddress($arrFakeAddr)
    {
        $strCompId = $arrFakeAddr["company_id"];
        $addResponse = $this->call('POST', 'companies/' . $strCompId . '/addresses', $arrFakeAddr);  // add address
        $this->refreshApplication();
        return $addResponse;
    }

    /**
     * Function to test update address
     *
     * @param String $strCompId Company id
     * @param String $strAddrId Address id
     *
     * @name   _updateAddress
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _updateAddress($strCompId, $strAddrId)
    {
        // edit address by ID
        $arrFakeAddr = factory(Address::class)->make()->toArray();
        $arrFakeAddr["company_id"] = $strCompId;
        $this->refreshApplication();
        $arrFakeAddr = factory(Address::class)->make()->toArray();
        $arrFakeAddr['id'] = $strAddrId;
        $editAddrResponse = $this->call('PUT', 'companies/' . $strCompId . '/addresses/' . $strAddrId, $arrFakeAddr);
        $this->refreshApplication();
        return $editAddrResponse;
    }

    /**
     * Function to delete address
     *
     * @param String $strCompId     Company id
     * @param String $strAddressIds Address id
     *
     * @name   _deleteAddress
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _deleteAddress($strCompId, $strAddressIds)
    {
        $deleteResponse = $this->call('DELETE', 'companies/' . $strCompId . '/addresses/' . $strAddressIds);
        $this->refreshApplication();
        return $deleteResponse;
    }

    /**
     * Function to get fake address id
     *
     * @name   _getFakeAddrId
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return integer
     */
    private function _getFakeAddrId()
    {
        $strFakeID = factory(Address::class)->make()->toArray()['created_at'];
        $this->refreshApplication();
        return $strFakeID;
    }
}
