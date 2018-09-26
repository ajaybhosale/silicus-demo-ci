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
 * @category Configuration
 * @package  AzureConfig
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Modules\Infrastructure\Services\Setting;

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
 * @category Azure
 * @package  Config
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
return [
    /*
      |--------------------------------------------------------------------------
      | Azure Stores
      |--------------------------------------------------------------------------
      |
      | Here you may define all of the azure configuration for your application as
      | well as their graph apis. You may even define multiple configurations for the
      | same.
      |
     */
    'cloud_env' => env('CLOUD_ENV', Setting::get('CLOUD_ENV')),
    'middleware' => env('CLOUD_MIDDLEWARE', Setting::get('CLOUD_MIDDLEWARE')),
    'azure_store_config' => [
        'clientId' => env('CLOUD_CLIENT_ID', Setting::get('CLOUD_CLIENT_ID')),
        'clientSecret' => env('CLOUD_SECRET', Setting::get('CLOUD_SECRET')),
        'redirectUri' => env('CLOUD_REDIRECT_URL', Setting::get('CLOUD_REDIRECT_URL')),
        'urlAuthorize' => 'https://login.microsoftonline.com/' . env('CLOUD_TENANT', Setting::get('CLOUD_TENANT')) . '/oauth2/authorize',
        'urlAccessToken' => 'https://login.microsoftonline.com/' . env('CLOUD_TENANT', Setting::get('CLOUD_TENANT')) . '/oauth2/token',
        'urlResourceOwnerDetails' => 'https://admin.vericheck.in/logout',
        'scopes' => 'Directory.ReadWrite.All offline_access',
        'logoutUri' => '',
        'prompt' => 'login',
        'tenant' => env('CLOUD_TENANT', Setting::get('CLOUD_TENANT')),
        'resource' => 'https://graph.windows.net',
        'username' => env('CLOUD_B2C_TEST_CASE_USERNAME', Setting::get('CLOUD_B2C_TEST_CASE_USERNAME')),
        'password' => env('CLOUD_B2C_TEST_CASE_PASSWORD', Setting::get('CLOUD_B2C_TEST_CASE_PASSWORD')), // This credentials comes from azure keyvalue service after integration
    ],
    /*
      |--------------------------------------------------------------------------
      | Azure B2C api config
      |--------------------------------------------------------------------------
      |
      | Here you may define all of the azure graph apis for your application
      | You may even define multiple graph api's
      | same.
      |
     */
    'b2c_config' => [
        'clientId' => env('CLOUD_B2C_CLIENT_ID', Setting::get('CLOUD_B2C_CLIENT_ID')),
        'clientSecret' => env('CLOUD_B2C_SECRET', Setting::get('CLOUD_B2C_SECRET')),
        'urlAuthorize' => 'https://login.microsoftonline.com/tfp/' . env('CLOUD_TENANT', Setting::get('CLOUD_TENANT')) . '/oauth2/v2.0/authorize',
        'urlAccessToken' => 'https://login.microsoftonline.com/tfp/' . env('CLOUD_TENANT', Setting::get('CLOUD_TENANT')) . '/oauth2/v2.0/token?p=' . env('CLOUD_B2C_SIGN_IN_POLICY', 'b2c_1_sign_up_sign_in'),
        'urlResourceOwnerDetails' => '',
        'scopes' => 'https://' . env('CLOUD_TENANT', Setting::get('CLOUD_TENANT')) . '/api/read openid profile',
        'urlResourceOwnerDetails' => '',
        'logoutUri' => env('SITE_URL', Setting::get('SITE_URL')),
        'prompt' => 'login',
        'response_type' => 'id_token',
        'resource' => 'https://graph.windows.net/',
        'tenant' => env('CLOUD_TENANT', Setting::get('CLOUD_TENANT')),
        'version' => env('CLOUD_GRAPH_VERSION', '1.6'),
    ],
    /*
      |--------------------------------------------------------------------------
      | Default session scopes
      |--------------------------------------------------------------------------
      |
      | Here you may define all of the azure graph apis for your application
      | You may even define multiple graph api's
      | same.
      |
     */
    'company_id_extension' => 'extension_' . env('DB_FIELD_EXTENSION', Setting::get('DB_FIELD_EXTENSION')) . '_CompanyId',
    'user_grant_extension' => 'extension_' . env('DB_FIELD_EXTENSION', Setting::get('DB_FIELD_EXTENSION')) . '_UserGrant',
    'alternate_email_extension' => 'extension_' . env('DB_FIELD_EXTENSION', Setting::get('DB_FIELD_EXTENSION')) . '_AlterEmail',
    'sign_up_sign_in_policy' => env('CLOUD_B2C_SIGN_IN_POLICY', Setting::get('CLOUD_B2C_SIGN_IN_POLICY')),
    'env_extension' => 'extension_' . env('DB_FIELD_EXTENSION', Setting::get('DB_FIELD_EXTENSION')) . '_Env',
    'environment' => env('USER_ENV', Setting::get('USER_ENV'))
];
