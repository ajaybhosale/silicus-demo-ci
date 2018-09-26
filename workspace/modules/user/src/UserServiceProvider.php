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
 * @category UserServiceprovider
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */

namespace Modules\User;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider loads all submodule of base module
 * Provider loads all config/db migrations
 *
 * @name     UserServiceprovider
 * @category User
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class UserServiceProvider extends ServiceProvider
{

    /**
     * Load all module dependencies also configure module routes/migrations.
     * Module config files also merged when-ever user module is accessed
     *
     * @name   boot
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';
        $sourceMigration = realpath(__DIR__ . '/../migrations');
        $configPath      = realpath(__DIR__ . '/Config/cloud.php');
        $this->loadMigrationsFrom($sourceMigration);
        $this->mergeConfigFrom($configPath, 'cloud');
    }

    /**
     * Register additional dependencies
     *
     * @name   register
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function register()
    {
    }
}
