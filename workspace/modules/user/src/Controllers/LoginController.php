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
 * @category OAuth
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Modules\User\Factory\CloudFactory;
use Modules\User\Transformers\LoginTransformer;
use Modules\User\Transformers\ForgotPasswordTransformer;
use Modules\User\Traits\Login\AccessToken;
use Modules\User\Traits\Login\UserLogout;
use Modules\User\Traits\Login\UserAuthenticate;
use Modules\User\Traits\Login\UserSignInUrl;

/**
 * Logincontroller class manage user redirection to portal and also logout
 * from vericheck account it generates login url for every user
 *
 * @name     LoginApiController
 * @category Auth
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class LoginController extends Controller
{

    private $_cloudObj;
    protected $loginTransformer;

    use AccessToken;
    use UserLogout;
    use UserAuthenticate;
    use UserSignInUrl;

    /**
     * Default constructor set's cloud environment with factory approach
     *
     * @param LoginTransformer $loginTransformer is used to get data sent by client
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct(LoginTransformer $loginTransformer)
    {
        $cloudEnv = Config::get('cloud.cloud_env');
        if (isset($cloudEnv) && $cloudEnv <> '') {
            $this->_cloudObj = CloudFactory::build($cloudEnv);
        } else {
            $this->_cloudObj = 'Azure';
        }
        $this->loginTransformer = $loginTransformer;
        parent::__construct();
    }

    /**
     * Function get the azure active directory sign-In url with sign in policy.
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   signInUrl
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function signInUrl(Request $request)
    {
        return $this->userSignInUrl($request, $this->_cloudObj);
    }

    /**
     * Authenticate for getting the authorization code and state from the azure
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   authenticate
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function authenticate(Request $request)
    {
        return $this->userAuthentication($request, $this->_cloudObj);
    }

    /**
     * Logout from the system
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   logout
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function logout(Request $request)
    {
        return $this->logoutUser($request, $this->_cloudObj);
    }

    /**
     * Create new access token and save into the database
     *
     * @param Obj $request is used to get data sent by client
     *
     * @name   getAdminAccessToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $urlResponse
     */
    public function getAdminAccessToken(Request $request)
    {
        return $this->getAccessToken($request, $this->_cloudObj);
    }
}
