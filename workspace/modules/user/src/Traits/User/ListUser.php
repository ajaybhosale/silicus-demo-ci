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
 * List user details from AD
 *
 * @name     ListUser
 * @category Trait
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ListUser
{

    /**
     * List merchant/iso/admin user's using active directory graph api service
     *
     * @param Object $request  Object of request class
     * @param Object $objCloud Object of Azure Cloud
     *
     * @name   listUser
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function listUser($request, $objCloud)
    {
        $arrResponse = $arrMeta = $objUser = [];
        $collectUser = collect();
        $objUser = $objCloud->all($request);
        $baseCollection = collect($objUser);
        $collectUser = $this->_createUserCollection($objUser);
        if (isset($baseCollection['odata.nextLink']) && $baseCollection['odata.nextLink'] <> '') {
            $arrMeta['next'] = $arrMeta['prev'] = $baseCollection['odata.nextLink'];
            $arrMeta['url'] = '';
        }
        $arrResponse = $this->response()->collection($collectUser, new UserTransformer, ['key' => 'user'])->setMeta($arrMeta);
        return $arrResponse;
    }

    /**
     * Function create active directory user object into the collection
     *
     * @param Object $objUser is active directory user collection object
     *
     * @name   _createUserCollection
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return collect
     */
    private function _createUserCollection($objUser)
    {
        if (isset($objUser->value) && !empty($objUser)) {
            $collectUser = collect($objUser->value);
        } else {
            $collectUser = collect($objUser);
        }
        return $collectUser;
    }
}
