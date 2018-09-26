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
 * @category ServiceProvider
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company;

use Illuminate\Support\ServiceProvider;

/**
 * CompanyServiceProvider for publish images, css, js
 *
 * @name     CompanyServiceProvider.php
 * @category ServiceProvider
 * @package  CompanyServiceProvider
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/
 */
class CompanyServiceProvider extends ServiceProvider
{

    /**
     * This function is for send migration files in migration folder.
     *
     * @name   boot
     * @access public
     * @author
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';
        $sourceMigration = realpath(__DIR__ . '/../migrations');
        $this->loadMigrationsFrom($sourceMigration);
        //get theme name
        $theme = \Config::get('app.theme');
        //set theme path
        $this->loadViewsFrom(__DIR__ . '/views/', 'address');
        $this->loadViewsFrom(__DIR__ . '/views/', 'company');

        $sourceFrontJs = realpath(__DIR__ . '/../public/theme/front');


        $this->publishes([$sourceFrontJs => base_path('public/theme/' . $theme . '/assets/'),
        ]);
    }
}
