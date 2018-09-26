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
 * @category Category
 * @package  Package_Name
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
/*
  |--------------------------------------------------------------------------
  | Model Factories
  |--------------------------------------------------------------------------
  |
  | Here you may define all of your model factories. Model factories give
  | you a convenient way to create models for testing and seeding your
  | database. Just tell the factory how a default model should look.
  |
 */
use Webpatser\Uuid\Uuid;

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});
$factory->define(\Modules\Company\Models\Company::class, function (Faker\Generator $faker) {
    $company = \Modules\Company\Models\Company::get()->where('DeletedAt', null)->first();

    $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $strCompanyName = substr(str_shuffle(str_repeat($pool, 6)), 0, 6);

    if (is_null($company)) {
        $companyType = 'iso';
    } else {
        $companyType = 'merchant';
    }
    $companyType = 'merchant';
    return [
        "name" => $strCompanyName,
        "type" => $companyType,
        "doing_business_as" => $faker->word,
        "tax_id" => $faker->numberBetween(111111111, 999999999),
        "created_by" => $faker->randomDigit,
        "status" => 1
    ];
});

$factory->define(\Modules\Company\Models\Address::class, function (Faker\Generator $faker) {
    $objAddress = new \Modules\Company\Models\Address();
    $company = \Modules\Company\Models\Company::get()->where('DeletedAt', null)->first();
    return [
        "company_id" => $company->CompanyId,
        // "id" => Uuid::generate(4)->string,
        "nickname" => $faker->word,
        "address_line1" => $faker->address,
        "address_line2" => $faker->address,
        "city" => $faker->city,
        "state" => strtoupper($faker->countryCode),
        "zip" => substr(str_shuffle('123456789'), -5),
        "address_type" => array_rand($objAddress->addressType, 1),
        "created_at" => time(),
        "etag" => time(),
        "primary_phone" => str_shuffle('1234567891'),
        "secondary_phone" => str_shuffle('1987654321'),
        "primary_email" => $faker->email,
        "secondary_email" => $faker->email,
        "fax" => str_shuffle('1987654321'),
    ];
});
