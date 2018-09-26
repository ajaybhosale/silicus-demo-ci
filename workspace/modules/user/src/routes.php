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
 * @category User_Routes
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id: $
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
$api = app('Dingo\Api\Routing\Router');

$defaultApiVersion = config('api.version');
$api->version($defaultApiVersion, function ($api) {
    $limit = config('api.throttling.limit');
    $expires = config('api.throttling.expires');
    $api->group(['namespace' => 'Modules\User\Controllers', 'middleware' => 'api.throttle', 'limit' => $limit, 'expires' => $expires], function ($api) {
        $api->get('login', 'LoginController@signInUrl');
        $api->get('authenticate', 'LoginController@authenticate');
        $api->get('logout', 'LoginController@logout');
        $api->get('token', 'LoginController@getAdminAccessToken');
    });

    $api->group(['namespace' => 'Modules\User\Controllers', 'middleware' => 'api.throttle', 'limit' => $limit, 'expires' => $expires], function ($api) {
        $api->get('users', 'UserController@index');
        $api->get('users/{strUserId}', 'UserController@show');
        $api->post('users', 'UserController@store');
        $api->put('users/{strUserId}', 'UserController@update');
        $api->delete('users/{strUserId}', 'UserController@destroy');
        $api->patch('users/{strUserId}', 'UserController@destroy');
    });
});
