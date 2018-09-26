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
 * @category ServiceProvider
 * @package  Group
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure;

use Illuminate\Support\ServiceProvider;
/**
 * Service provider for Master module
 *
 * @name     MasterHolidayServiceProvider
 * @category Provider
 * @package  Master
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Modules\Infrastructure\Services\Setting;
use PDO;

/**
 * Infrastructure Service Provider
 *
 * @name     InfrastructureServiceProvider
 * @category InfrastructureServiceProvider
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class InfrastructureServiceProvider extends ServiceProvider
{

    /**
     * Boot method to load services
     *
     * @name   boot
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function boot()
    {
        $sourceMigration = realpath(__DIR__ . '/../migrations');
        $this->loadMigrationsFrom($sourceMigration);
    }

    /**
     * Register method to register services
     *
     * @name   register
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function register()
    {
// $this->app['config']['database'] = $this->_database();
        $this->_setApiVersion();
    }

    /**
     * Set API version dynamically
     *
     * @name   setApiVersion
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    private function _setApiVersion()
    {
        $apiVersion = config('api.version');
        $apiVersionName = config('api.subtype');
        $apiStandardTree = config('api.standardsTree');
        $apiVersionKey = trim($apiVersionName) != '' ? str_replace('-', '_', strtoupper($apiVersionName)) : 'VERICHECK_VERSION';

        $version = isset($_SERVER['HTTP_' . $apiVersionKey]) ? $_SERVER['HTTP_' . $apiVersionKey] : $apiVersion;
        $max = $min = $patch = 0;

        list($max, $min, $patch) = explode('.', $apiVersion);
        $values = explode('.', $version);

        $maxLength = count($values);
        $finalVersion = $this->_getApiVersion($maxLength, $version);
        $_SERVER['HTTP_ACCEPT'] = "application/$apiStandardTree.$apiVersionName.$finalVersion+json";
    }

    /**
     * Get database setting configurations
     *
     * @name   database
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _database()
    {
        return [
            'fetch' => PDO::FETCH_CLASS,
            'default' => Setting::get('DB_CONNECTION'),
            'connections' => [
                'testing' => ['driver' => 'sqlite', 'database' => ':memory:'],
                'sqlsrv' => [
                    'driver' => Setting::get('DB_CONNECTION'), 'host' => Setting::get('DB_HOST'),
                    'database' => Setting::get('DB_DATABASE'), 'username' => Setting::get('DB_USERNAME'),
                    'password' => Setting::get('DB_PASSWORD'), 'port' => Setting::get('DB_PORT'),
                    'charset' => Setting::get('DB_CHARSET'), 'prefix' => Setting::get('DB_PREFIX')
                ],
            ],
        ];
    }

    /**
     * Function to get API final version
     *
     * @param Obj $maxLength is used to get max length
     * @param Obj $version   is used to get version
     *
     * @name   _getApiVersion
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return string
     */
    private function _getApiVersion($maxLength, $version)
    {
        $max = $min = $patch = 0;

        $max = $version;

        if ($maxLength === 2) {
            list ($max, $min) = explode('.', $version);
        } elseif ($maxLength !== 1) {
            list($max, $min, $patch) = explode('.', $version);
        }

        $strVersion = "$max.$min.$patch";

        return $strVersion;
    }
}
