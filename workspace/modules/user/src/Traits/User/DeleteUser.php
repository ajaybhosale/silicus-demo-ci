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

use Modules\User\Events\ACL\DeleteACLMappingEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * User module delete AD user
 *
 * @name     DeleteUser
 * @category Trait
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait DeleteUser
{

    /**
     * Delete User from Active Directory
     *
     * @param String  $strUserId  User Id
     * @param Request $objRequest is request object
     * @param Object  $objCloud   is object of Cloud factory
     *
     * @name   deleteUser
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function deleteUser($strUserId, $objRequest, $objCloud)
    {
        $arrResponse = [];
        if (isset($strUserId) && $strUserId <> '') {
            $objCloudError = $objCloud->destroy($strUserId, $objRequest);
            if (!empty($objCloudError)) {
                $arrResponse = json_decode($objCloudError, true);
            } else {
                event(new DeleteACLMappingEvent($strUserId));
                $arrResponse['data'] = array('message' => 'User sucessfully deleted', 'status_code' => static::SUCCESS_CODE);
            }
            return $arrResponse['data'];
        } else {
            throw new UnauthorizedHttpException('Restrict', 'Problem with user deletion.');
        }
        return $this->response->array(['id' => $strUserId, 'status' => 'deleted']);
    }
}
