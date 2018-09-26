<?php
/**
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
 *
 * @category  Company
 * @package   Company
 * @author    Swati Jadhav <swati.jadhav@silicus.com>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   GIT: <git_id>
 * @link      http://pear.php.net/package/PackageName
 * */
Route::group(['middleware' => ['web'], 'namespace' => 'Modules\Company\Controllers'], function () {
    Route::get('companies/list', 'CompanyWebController@index');
    Route::get('companies/{id}/address/list', 'CompanyAddressWebController@index');
    Route::get('company/{id}/getAddresses', ['uses' => 'CompanyAddressWebController@getAddresses',]);

    Route::get('company/{id}/address/add', ['uses' => 'CompanyAddressWebController@addAddress']);
    Route::get('company/{id}/address/{addressId}', ['uses' => 'CompanyAddressWebController@editAddress']);
    //  Route::post('company/{id}/address/add', ['uses' => 'CompanyAddressWebController@storeAddress']);
    Route::post('company/{id}/address/add', 'CompanyAddressWebController@storeAddress');
    Route::post('company/{id}/address/{addressId}', ['as' => 'updateAddress', 'uses' => 'CompanyAddressWebController@updateAddress']);
    Route::delete('company/{id}/address/{addressId}', ['as' => 'deleteAddress', 'uses' => 'CompanyAddressWebController@deleteAddress']);
});
