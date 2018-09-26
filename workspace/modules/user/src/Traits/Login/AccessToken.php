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
namespace Modules\User\Traits\Login;

/**
 * Get Access token for AdminGateway
 *
 * @name     AccessToken
 * @category Trait
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait AccessToken
{

    /**
     * Create new access token and save into the database
     *
     * @param Object $request  is used to get data sent by client
     * @param Object $cloudObj Object of Azure Cloud
     *
     * @name   getAccessToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $urlResponse
     */
    public function getAccessToken($request, $cloudObj)
    {
        $urlResponse = [];
        $urlResponse = $cloudObj->getAdminAccessToken($request);
        if (isset($urlResponse['token']) && $urlResponse['token']) {
            $urlResponse['message'] = "Token successfully created and saved";
        } else {
            $urlResponse['message'] = "Problem with token creation";
        }
        return json_encode($urlResponse);
    }
}
