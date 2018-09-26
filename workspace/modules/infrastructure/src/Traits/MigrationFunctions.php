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
 * @category Trait
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Traits;

/**
 * Trait having methods for db migrations
 *
 * @name     MigrationFunctions
 * @category Trait
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait MigrationFunctions
{

    /**
     * Function to add primary key
     *
     * @param Obj    $table         Table object
     * @param String $strColumnName Coloumn name
     *
     * @name   _addPrimaryKey
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    private function _addPrimaryKey($table, $strColumnName)
    {
        config('database.default') == 'sqlsrv' ? $table->uuid($strColumnName)->primary() : $table->bigIncrements($strColumnName)->nullable()->primary()->unsigned();
    }

    /**
     * Function to add uuid to table
     *
     * @param Obj     $table          Table object
     * @param String  $strColumnName  Coloumn name
     * @param boolean $boolIsNullable If nullable : true or false
     *
     * @name   _addUUID
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    private function _addUUID($table, $strColumnName, $boolIsNullable = false)
    {
        if ($boolIsNullable) {
            config('database.default') == 'sqlsrv' ? $table->uuid($strColumnName)->nullable() : $table->bigInteger($strColumnName)->unsigned()->index()->nullable();
        } else {
            config('database.default') == 'sqlsrv' ? $table->uuid($strColumnName) : $table->bigInteger($strColumnName)->unsigned()->index();
        }
    }
}
