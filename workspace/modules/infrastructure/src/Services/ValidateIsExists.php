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
 * @category Uuid
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Services;

use Illuminate\Database\QueryException;
use Symfony\Component\Debug\Exception\ClassNotFoundException;

/**
 * This trait is used to check Id passed exists in table and not deleted
 *
 * @name     ValidateIsExists
 * @category Infrastructure
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ValidateIsExists
{

    /**
     * Check Record Exists By Id For given Model class
     *
     * @param Object $objModel   Model Class Object
     * @param String $strId      Primary Key Id to get record
     * @param String $routingMsg routing no for model
     *
     * @name   checkRecordExistsById
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function checkRecordExistsById($objModel, $strId, $routingMsg = '')
    {
        $strClassPath = get_class($objModel);

        $this->_chkClassExist($strClassPath);

        try {
            $objData = $objModel->find($strId);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }

        $this->_chkObjOfClass($objData, $strClassPath, $routingMsg, $strId);

        return $objData;
    }

    /**
     * Function to check is class exist
     *
     * @param Obj $strClassPath class path
     *
     * @name   _chkClassExist
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _chkClassExist($strClassPath)
    {
        if (!class_exists($strClassPath)) {
            throw new ClassNotFoundException();
        }
    }

    /**
     * Function to check object of class
     *
     * @param Obj $objData      Object data
     * @param Obj $strClassPath Class path
     * @param Obj $routingMsg   Routing message
     * @param Obj $strId        Record id
     *
     * @name   _chkObjOfClass
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _chkObjOfClass($objData, $strClassPath, $routingMsg, $strId)
    {
        if (!$objData instanceof $strClassPath) {
            return $this->errorNotFound("The record with {$routingMsg} id {$strId} doesn't exist.");
        }
    }
}
