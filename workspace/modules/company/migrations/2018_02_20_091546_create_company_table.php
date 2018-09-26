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
 * CreateCompanyTable class is used to create Company.Company table
 *
 * @name     CreateCompanyTable.php
 * @category Migration
 * @package  CreateCompanyTable
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CreateCompanyTable extends Migration
{

    use MigrationFunctions;

    public $tableName;
    public $seperator;

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
        $tablePrefix = 'Company'; // Schema Name to Identify Table Company
        $tableName = 'Company'; // Table Name
        $this->seperator = config('app.db_schema_seperator');
        //$this->tableName = $tablePrefix . $this->seperator . $tableName;
        $this->tableName = $tableName;
    }

    /**
     * Function used to Create table Company.Company
     *
     * @name   up
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->increments('CompanyId');
            $table->string('Type', '16')->default('NULL');
            $table->string('Name', '256')->nullable();
            $table->string('DoingBusinessAs', '1024')->nullable();
            $table->string('TaxId', '64')->nullable();
            $table->integer('CreatedAt')->nullable()->default(0);
            $table->integer('Etag');
            $table->dateTime('DeletedAt')->nullable();
        });
    }

    /**
     * Function used to Drop table Company.Company
     *
     * @name   down
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->tableName);
    }
}
