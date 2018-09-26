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
 * @category Migration
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Infrastructure\Traits\MigrationFunctions;

/**
 * CreateCompanyaddressTable class is used to create Company.CompanyAddress table
 *
 * @name     CreateCompanyaddressTable
 * @category Migration
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CreateCompanyaddressTable extends Migration
{

    use MigrationFunctions;

    public $tableName;

    /**
     * Constructor Function
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct()
    {
        $tablePrefix = 'Company'; // Schema Name to Identify Table group
        $tableName = 'CompanyAddress'; // Table Name
        $seperator = config('app.db_schema_seperator');
        //$this->tableName = $tablePrefix . $seperator . $tableName;
        $this->tableName = $tableName;
    }

    /**
     * Function used to Create table Company.CompanyAddress
     *
     * @name   up
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function up()
    {
        /* Schema::create($this->tableName, function (Blueprint $table) {

          $this->_addPrimaryKey($table, 'CompanyAddressId');
          $this->_addUUID($table, 'CompanyId');
          $this->_addUUID($table, 'AddressId');
          $table->foreign('CompanyId', 'CompanyAddress_Company_CompanyId')->references('CompanyId')->on('Company')->onDelete('cascade');
          $table->foreign('AddressId', 'CompanyAddress_Address_AddressId')->references('AddressId')->on('Address')->onDelete('cascade');
          }); */
        Schema::create($this->tableName, function (Blueprint $table) {

            $table->increments('CompanyAddressId');
            $table->bigInteger('CompanyId');
            $table->bigInteger('AddressId');
            // $table->foreign('CompanyId', 'CompanyAddress_Company_CompanyId')->references('CompanyId')->on('Company')->onDelete('cascade');
            //$table->foreign('AddressId', 'CompanyAddress_Address_AddressId')->references('AddressId')->on('Address')->onDelete('cascade');
        });
    }

    /**
     * Function used to Drop table Company.CompanyAddress
     *
     * @name   down
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tableName, function ($table) {
            $table->dropForeign('CompanyAddress_Company_CompanyId');
            $table->dropForeign('CompanyAddress_Address_AddressId');
        });
        Schema::drop($this->tableName);
    }
}
