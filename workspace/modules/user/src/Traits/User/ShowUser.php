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
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Traits\User;

use Modules\User\Transformers\UserTransformer;

/**
 * User module list AD user
 *
 * @name     ShowUser
 * @category Trait
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ShowUser
{

    /**
     * Show merchant/iso/admin user information using user Id
     *
     * @param String $strUserId user Id
     * @param Object $objCloud  object of Azure Cloud
     *
     * @name   showUser
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $arrResponse
     */
    public function showUser($strUserId, $objCloud)
    {
        $arrResponse = [];
        $objUserInfo = $objCloud->show($strUserId);
        $arrErrors = json_decode($objUserInfo, true);
        if (isset($arrErrors['data']['error']) && $arrErrors['data']['error'] <> '' && strpos($arrErrors['data']['error']['message'], $strUserId) !== false) {
            $arrResponse = ['status_code' => 404, 'message' => 'Resource not found'];
            $arrResponse['errors'][] = [
                'code' => 'UR404', 'type' => 'Not_Exists',
                'message' => 'User with given information not found', 'more_info' => 'UR404'
            ];
        } else {
            $arrResponse = $this->response()->item(json_decode($objUserInfo), new UserTransformer, ['key' => 'user']);
        }
        return $arrResponse;
    }
}
