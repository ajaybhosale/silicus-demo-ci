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
 * @package  Migration
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Class for Migration Test cases
 *
 * @name     MigrationTest
 * @category TestCase
 * @package  MigrationTest
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class MigrationUpTest extends TestCase
{

    /**
     * Test case for migration to create table
     *
     * @name   testUp
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testUp()
    {

        //$this->artisan('migrate:reset');
        //Artisan::call('migrate:reset');
        $this->assertTrue(true);
        $this->refreshApplication();

        $this->artisan('migrate');
        $this->assertTrue(true);
        $this->refreshApplication();

        $this->artisan('db:seed');
        $this->assertTrue(true);
        $this->refreshApplication();
    }
}
