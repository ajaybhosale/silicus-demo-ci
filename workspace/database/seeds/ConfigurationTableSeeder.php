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
 * @category Auth
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Illuminate\Database\Seeder;
use Modules\Configuration\Models\Configuration;

/**
 * Configuration table seeder for default token settings
 *
 * @name     ConfigurationTableSeeder
 * @category Seed
 * @package  ConfigurationTableSeeder
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class ConfigurationTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::create([
            'ConfigKey' => 'Access-Token',
            'ConfigValue' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6IkZTaW11RnJGTm9DMHNKWEdtdjEzbk5aY2VEYyIsImtpZCI6IkZTaW11RnJGTm9DMHNKWEdtdjEzbk5aY2VEYyJ9.eyJhdWQiOiJodHRwczovL2dyYXBoLndpbmRvd3MubmV0IiwiaXNzIjoiaHR0cHM6Ly9zdHMud2luZG93cy5uZXQvZGUzNzU4ZDYtOTA5ZS00YzQ5LWJiZDQtMzljZTZkOTI1N2YwLyIsImlhdCI6MTUyMTcyODMzMSwibmJmIjoxNTIxNzI4MzMxLCJleHAiOjE1MjE3MzIyMzEsImFpbyI6IlkyTmdZSmkyVm5ISk10MDVHK2ZjKy9qRTZkUC83UUE9IiwiYXBwaWQiOiI3MGVjYWIzNi0wNDY3LTQ5ZTQtYWIwZi1lNTFkYTA0MTc3YzkiLCJhcHBpZGFjciI6IjEiLCJpZHAiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC9kZTM3NThkNi05MDllLTRjNDktYmJkNC0zOWNlNmQ5MjU3ZjAvIiwib2lkIjoiY2E5MWNkYzQtNjE0ZC00NDhjLWJiZDQtM2FkYTRjNTNlZjRlIiwicm9sZXMiOlsiRGlyZWN0b3J5LlJlYWQuQWxsIiwiRGlyZWN0b3J5LlJlYWRXcml0ZS5BbGwiLCJEb21haW4uUmVhZFdyaXRlLkFsbCJdLCJzdWIiOiJjYTkxY2RjNC02MTRkLTQ0OGMtYmJkNC0zYWRhNGM1M2VmNGUiLCJ0ZW5hbnRfcmVnaW9uX3Njb3BlIjoiTkEiLCJ0aWQiOiJkZTM3NThkNi05MDllLTRjNDktYmJkNC0zOWNlNmQ5MjU3ZjAiLCJ1dGkiOiJBc3NES29icjYwYWVLVHNnbzd3VkFBIiwidmVyIjoiMS4wIn0.xdHgjMNfx-pDxm210v6OhEkGOXfrAg6aAGyWkLP88Kk9DzqiMwkwO3gC2m6t79K33LCYNS7yuvLZZKdDJuF8BDYcsf59RAdBZBQDB5Ohy-ZFv7NYHtxc21_tJG_0ICHRv0g0kfuYLQygiuNwWHe_0Un3pe8lpOLwJqiJu4TJvUMEjWjjkANom_bCkP_MyYVbAdb1Dd7iYT99zhlbkARlbI4Q-FuvWWO22MUThFtQhYRb_S8yy4vxNxAsAZhmuB5U4dnzhkBR8OF_fFYwfiCRIHWkoB49EfecIc5ioPPBo4m7GJTwnR-l0yK_EZTnqMSn1FqQjU7iNUVI_JCV9TVeDQ',
            'ConfigType' => 'Token',
            'EffectiveStartDate' => time(),
            'EffectiveEndDate' => time(),
            'Status' => 1,
            'Etag' => time(),
            'CreatedAt' => time(),
        ]);
    }
}
