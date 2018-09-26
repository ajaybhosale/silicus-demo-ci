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

use Modules\User\Transformers\LoginTransformer;

/**
 * User Sign In Url for AD
 *
 * @name     UserSignInUrl
 * @category Trait
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait UserSignInUrl
{

    /**
     * Function get the azure active directory sign-In url with sign in policy.
     *
     * @param Object $request  is used to get data sent by client
     * @param Object $cloudObj Object of Azure Cloud
     *
     * @name   userSignInUrl
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function userSignInUrl($request, $cloudObj)
    {
        $params = $request->all();
        $redirectUrl = '';
        if (isset($params['redirect_uri']) && $params['redirect_uri'] <> '') {
            $redirectUrl = $params['redirect_uri'];
        }
        $response = $cloudObj->getAuthorizeUrl($redirectUrl, 'company');
        $response = $this->response()->item((Object) $response, new LoginTransformer, ['key' => 'sign_in']);
        return $response;
    }
}
