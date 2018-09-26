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
 * PHP version 7.*
 *
 * @category Setting
 * @package  Setting
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Services;

use Illuminate\Support\Facades\Cache;
use bentaylorwork\azure\authorisation\Token as azureAuthorisation;
use bentaylorwork\azure\keyvault\Secret as keyVaultSecret;
use Illuminate\Support\Facades\Config;

/**
 * A common setting class which design to manage systemâ€™s settings.
 * All settings will pull from Azure Key Vault and then it will store into Redis
 * cache. System will pull all required setting values from Redis cache.
 * Also when system launch it will clear all cache from Redis and rebuild it
 * from Azure Key Vault.
 *
 * @name     Setting
 * @category Setting
 * @package  Setting
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class Setting
{

    private $_keyVaultToken = null;
    private $_configKeys = ['phnx-backend-api-db',
        'phnx-backend-api-redis',
        'phnx-backend-api-app',
        'phnx-backend-api-api',
        'phnx-backend-api-az-cloud',
        'phnx-backend-api-az-b2c',
        'phnx-backend-api-email'];
    private $_postFix = null;
    //90 days
    private $_cacheTime = 129600;

    /**
     * Default constructor sets postfix and keyVault default values
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct()
    {
        $this->_postFix = env('APP_ENV');
        // $this->setKeyVaultToken();
        // $this->setConfigToCache();
    }

    /**
     * Function sets active directory client and secret id from using keyVault service
     *
     * @name   setKeyVaultToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function setKeyVaultToken()
    {

        if (empty($this->_keyVaultToken)) {
            $this->_keyVaultToken = new keyVaultSecret(
                [
                'accessToken' => azureAuthorisation::getKeyVaultToken(
                    [
                        'appTenantDomainName' => 'vericheck.onmicrosoft.com',
                        'clientId' => 'e169c3b2-35de-4a93-933c-3d86be904719',
                        'clientSecret' => 'M7bF5zLF/59ERmGitlxdMVCA8uqHKpdTpgu0iXg2tlc='
                    ]
                ),
                'keyVaultName' => 'phnx-sha-kv'
                ]
            );
        }
    }

    /**
     * Function get configuration values from key vault service
     *
     * @param Obj $key is used to get data sent by client
     *
     * @name   getConfigFromKeyVault
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return string
     */
    public function getConfigFromKeyVault($key)
    {
        try {
            $keyObject = $this->_keyVaultToken->get($key);

            if (!empty($keyObject) && isset($keyObject['data']['value'])) {
                return $keyObject['data']['value'];
            } else {
                return null;
            }
        } catch (\ErrorException $e) {
            return 'Sorry, There was a technical problem. Please try again soon';
        }
    }

    /**
     * Function set configuration values into the cache
     *
     * @name   setConfigToCache
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function setConfigToCache()
    {
        foreach ($this->_configKeys as $key) {
            $key = $key . '-' . $this->_postFix;
            $configs = $this->getConfigFromKeyVault($key);

            $items = json_decode($configs);

            foreach ($items as $key => $value) {
                Cache::set($key, $value, $this->_cacheTime);
            }
        }
    }

    /**
     * Function get parameters from cache service
     *
     * @param Obj $key is used to get data sent by client
     *
     * @name   get
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public static function get($key)
    {
        return Cache::get($key);
    }

    /**
     * Function clear parameters from cache service
     *
     * @param Obj $key is used to get data sent by client
     *
     * @name   clear
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public static function clear($key)
    {
        Cache::forget($key);
    }
}
