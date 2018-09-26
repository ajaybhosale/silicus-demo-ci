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
 * @category Interfaces
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * This is an base interface for all modules interfaces
 *
 * @name     BaseInterface
 * @category Interfaces
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
interface BaseInterface
{

    /**
     * Find all resource by criteria
     *
     * @param array $criteria description
     *
     * @name   all
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function all(array $criteria);

    /**
     * Find one resource by id
     *
     * @param integer $id description
     *
     * @name   findOne
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function findOne($id);

    /**
     * Save resource
     *
     * @param array $data array of data
     *
     * @name   save
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return Model
     */
    public function save(array $data);

    /**
     * Update a resource
     *
     * @param Model $model object of model
     * @param array $data  array of data
     *
     * @name   update
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return Model
     */
    public function update(Model $model, array $data);

    /**
     * Delete a resource
     *
     * @param Model $model object of model
     *
     * @name   delete
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return mixed
     */
    public function delete(Model $model);
}
