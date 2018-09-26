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
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * CreateAddressTable class is used to create Company.Address table
 *
 * @name     CreateAddressTable
 * @category Migration
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CreateAddressTable extends Migration
{

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
        $tablePrefix = 'Company'; // Schema Name to Identify Table Company
        $tableName = 'Address'; // Table Name
        $seperator = config('app.db_schema_seperator');
        //$this->tableName = $tablePrefix . $seperator . $tableName;
        $this->tableName = $tableName;
    }

    /**
     * Function used to Create table Company.Address
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
            $table->increments('AddressId');
            $table->string('NickName', '255')->default('NULL');
            $table->string('AddressLine1', '1024')->default('NULL');
            $table->string('AddressLine2', '1024')->default('NULL');
            $table->string('City', '64')->default('NULL');
            $table->string('State', '2')->default('');
            $table->string('Zip', '8')->default('NULL');
            $table->string('PrimaryPhone', '16')->nullable();
            $table->string('SecondaryPhone', '16')->nullable();
            $table->string('PrimaryEmail', '256')->nullable();
            $table->string('SecondaryEmail', '256')->nullable();
            $table->string('Fax', '16')->nullable();
            $table->string('Type', '16')->default('primary');
            $table->integer('CreatedAt')->default(0);
            $table->integer('Etag')->default(0);
            $table->dateTime('DeletedAt')->nullable();
        });
    }

    /**
     * Function used to Drop table Company.Address
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
