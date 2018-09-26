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
 * @category Repository
 * @package  AddressRepository
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Repositories;

use Illuminate\Database\Eloquent\Model;
use Modules\Company\Models\Address;
use Modules\Company\Models\CompanyAddress;
use Modules\Company\Models\ContactAddress;
use Modules\Company\Repositories\Contracts\AddressInterface;

/**
 * Address model repository methods
 *
 * @name     AddressRepository.php
 * @category Repository
 * @package  AddressRepository
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class AddressRepository implements AddressInterface
{

    protected $model;

    /**
     * Constructor
     *
     * @param Obj $address Address model object
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function __construct(Address $address)
    {
        $this->model = $address;
    }

    /**
     * Find all resources using pagination
     *
     * @param array $searchCriteria array of search criteria
     *
     * @name   all
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function all(array $searchCriteria)
    {
        return $this->model->all();
    }

    /**
     * Save a resource
     *
     * @param array $data array of values
     *
     * @name   save
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function save(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a resource
     *
     * @param Model $address model
     * @param array $data    array of resource data
     *
     * @name   update
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function update(Model $address, array $data)
    {
        $address->save();
        return $address;
    }

    /**
     * Description
     *
     * @param array $searchCriteria array of resource data
     *
     * @name   findBy
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function findBy(array $searchCriteria = [])
    {
        return $this->model->where($searchCriteria)->get();
    }

    /**
     * Find a resource by id
     *
     * @param integer $id id of resource
     *
     * @name   findOne
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function findOne($id)
    {
        return $this->findOneBy(['AddressId' => $id]);
    }

    /**
     * Delete a resource
     *
     * @param Model $address model
     *
     * @name   delete
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function delete(Model $address)
    {
        return $address->delete();
    }

    /**
     * Find a resource by criteria
     *
     * @param array $criteria array of search criteria
     *
     * @name   findOneBy
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function findOneBy(array $criteria)
    {
        return $this->model->where($criteria)->first();
    }

    /**
     * Search All resources by any values of a key
     *
     * @param string $key    key
     * @param array  $values array
     *
     * @name   findIn
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function findIn($key, array $values)
    {
        return $this->model->whereIn($key, $values)->get();
    }

    /**
     * Get model table name
     *
     * @name   getTableName
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->model->getTable();
    }

    /**
     * Get all Addresses by Contact ID
     *
     * @param String $strContactId Company ID
     *
     * @name   getAddressesByContactId
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function getAllAddressesByContactId($strContactId)
    {
        $objContactAddress = new ContactAddress();

        $addressTableName = $this->model->getTable();

        return $this->model->select($addressTableName . '.*', $objContactAddress->getTable() . '.ContactId')->join($objContactAddress->getTable(), $addressTableName . '.AddressId', '=', $objContactAddress->getTable() . '.AddressId')->where($objContactAddress->getTable() . '.ContactId', $strContactId)->get();
    }

    /**
     * Get all Addresses by Company ID
     *
     * @param String $strCompanyId Company ID
     *
     * @name   getAllAddressesByCompanyId
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function getAllAddressesByCompanyId($strCompanyId)
    {
        $companyAddress = new CompanyAddress();

        $addressTableName = $this->model->getTable();

        return $this->model->select($addressTableName . '.*', $companyAddress->getTable() . '.CompanyId')->join($companyAddress->getTable(), $addressTableName . '.AddressId', '=', $companyAddress->getTable() . '.AddressId')->where($companyAddress->getTable() . '.CompanyId', $strCompanyId)->get();
    }
}
