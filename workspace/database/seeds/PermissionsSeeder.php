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
 * @category Controller
 * @package  ACL
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Illuminate\Database\Seeder;
use Modules\ACL\Models\ACO;
use Modules\ACL\Models\ARO;
use Modules\ACL\Models\AROACO;
use Webpatser\Uuid\Uuid;
use Modules\User\Factory\Azure;

/**
 * Permissions seeder
 *
 * @name     PermissionsSeeder Name
 * @category Seeder
 * @package  Seeder
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class PermissionsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modelArr = ['users-get', 'users-put', 'users-patch', 'users-post', 'users-delete', 'logout-get', 'groups-get', 'groups-put', 'groups-post',
            'groups-delete', 'aros-get', 'aros-put', 'aros-post', 'aros-delete', 'acos-get', 'acos-put', 'acos-post', 'acos-delete',
            'aroacos-get', 'aroacos-put', 'aroacos-post', 'aroacos-delete', 'permissions-get'];

        foreach ($modelArr as $modelArrList) {
            ACO::create([
                'AcoId' => Uuid::generate()->string,
                'ParentId' => null,
                'Alias' => $modelArrList,
                'Name' => $modelArrList,
                'Etag' => time()
            ]);
        }

        $parentId = ARO::create([
                'AroId' => Uuid::generate()->string,
                'ParentId' => null,
                'ModelId' => null,
                'Model' => 'role',
                'Name' => 'root',
                'Alias' => 'root',
                'Etag' => time()
        ]);

        $tokenObj = new Azure();
        $token = $tokenObj->getAdminAccessToken();
        $decodedToken = explode('.', $token['token']);
        if (isset($decodedToken[1]) && $decodedToken[1] <> null) {
            $tokenVal = json_decode(base64_decode($decodedToken[1]));
        }
        $testUserId = $tokenVal->oid;

        $aroIdUser = ARO::create([
                'AroId' => Uuid::generate()->string,
                'ParentId' => $parentId->AroId,
                'ModelId' => $testUserId,
                'Model' => 'user',
                'Name' => 'testuser',
                'Alias' => 'testuser',
                'Etag' => time()
        ]);

        $acoIds = ACO::all();

        for ($i = 0; $i < count($modelArr); $i++) {
            AROACO::create([
                'AroAcoId' => Uuid::generate()->string,
                'AroId' => $parentId->AroId,
                'AcoId' => $acoIds[$i]['AcoId'],
                'Access' => 0,
                'Etag' => time()
            ]);
        }

        for ($i = 0; $i < count($modelArr); $i++) {
            AROACO::create([
                'AroAcoId' => Uuid::generate()->string,
                'AroId' => $aroIdUser->AroId,
                'AcoId' => $acoIds[$i]['AcoId'],
                'Access' => 1,
                'Etag' => time()
            ]);
        }
    }
}
