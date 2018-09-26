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
 * @package  MarketingRepository
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Repositories;

use Illuminate\Database\Eloquent\Model;
use Modules\Company\Repositories\Contracts\AmendmentNoteInterface;
use Modules\Company\Models\AmendmentNote;
use Modules\Note\Models\Note;

/**
 * Two line description of class
 *
 * @name     AmendmentNoteRepository
 * @category Repository
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class AmendmentNoteRepository implements AmendmentNoteInterface
{

    protected $model;

    /**
     * Constructor
     *
     * @param Obj $amendmentNote marketing model object
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function __construct(AmendmentNote $amendmentNote)
    {
        $this->model = $amendmentNote;
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
     * @param Model $amendmentNote model
     * @param array $data          array of resource data
     *
     * @name   update
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function update(Model $amendmentNote, array $data)
    {
        $fillAbleProperties = $amendmentNote->getFillable();
        foreach ($data as $key => $value) {
            if (in_array($key, $fillAbleProperties)) {
                $amendmentNote->$key = $value;
            }
        }

        $amendmentNote->save();

        $updateAmendmentNote = $this->findOne($amendmentNote->AmendmentNoteId);

        return $updateAmendmentNote;
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
        return $this->findOneBy(['AmendmentNoteId' => $id]);
    }

    /**
     * Delete a resource
     *
     * @param Model $amendmentNote model
     *
     * @name   delete
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function delete(Model $amendmentNote)
    {
        return $amendmentNote->delete();
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
     * Function to get notes
     *
     * @param String $strCompanyId Company id
     *
     * @name   getNotes
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return Object
     */
    public function getNotes($strCompanyId)
    {
        $objNote = new Note();
        $strNoteTable = $objNote->getTable();

        return $this->model->leftJoin($strNoteTable, $strNoteTable . '.NoteId', '=', $this->model->getTable() . '.NoteId')->select($this->model->getTable() . '.*', $strNoteTable . '.Title')->where('CompanyId', $strCompanyId)->get();
    }
}
