<?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */

$api = app('Dingo\Api\Routing\Router');
$defaultApiVersion = config('api.version');

$api->version($defaultApiVersion, function ($api) {
    $limit = config('api.throttling.limit');
    $expires = config('api.throttling.expires');

    $api->group(['namespace' => '\Modules\Company\Controllers', 'middleware' => 'api.throttle', 'limit' => $limit, 'expires' => $expires], function () use ($api) {
        // api for company
        $api->get('/companies', ['uses' => 'CompanyController@index',]);
        $api->post('/companies', ['uses' => 'CompanyController@store',]);
        $api->get('/companies/{id}', ['uses' => 'CompanyController@show',]);
        $api->put('/companies/{id}', ['uses' => 'CompanyController@update',]);
        $api->delete('/companies/{id}', ['uses' => 'CompanyController@destroy',]);
        $api->put('/companies/{id}/activate', ['uses' => 'CompanyController@activate',]);
        $api->put('/companies/{id}/deactivate', ['uses' => 'CompanyController@deactivate',]);

        //API for company address
        $api->get('companies/{companyId}/addresses', ['uses' => 'CompanyAddressController@index',]);
        $api->get('companies/{companyId}/addresses/{id}', ['uses' => 'CompanyAddressController@show',]);
        $api->post('companies/{companyId}/addresses', ['uses' => 'CompanyAddressController@store',]);
        $api->put('companies/{companyId?}/addresses/{id?}', ['uses' => 'CompanyAddressController@update',]);
        $api->delete('companies/{companyId}/addresses/{id}', ['uses' => 'CompanyAddressController@destroy',]);
    });
});

