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

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\ACL\Repositories\ARORepository;
use Modules\Infrastructure\Services\TokenService;
use Modules\User\Factory\CloudFactory;
use Modules\User\Transformers\UserTransformer;
use Modules\User\Traits\User\UserValidator;
use Modules\User\Traits\User\UserAttributes;
use Modules\User\Traits\User\DeleteUser;
use Modules\User\Traits\User\AddUser;
use Modules\User\Traits\User\EditUser;
use Modules\User\Traits\User\ShowUser;
use Modules\User\Traits\User\ListUser;

/**
 * Usercontroller handles all user management
 *
 * @name     UserController
 * @category User
 * @package  User
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class UserController extends Controller
{

    private $_cloudObj = 'Azure';
    protected $userTransformer;
    private $_aroAcoRepository;

    use TokenService;
    use UserValidator;
    use UserAttributes;
    use DeleteUser;
    use AddUser;
    use EditUser;
    use ShowUser;
    use ListUser;

    /**
     * Default parameterized constructor inject the UserTransformer class
     * To beautify the user services response in defined fields format
     *
     * @param Object          $aroRepository   is object of Aro repository
     * @param UserTransformer $userTransformer is used to transform the
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct(ARORepository $aroRepository, UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
        $this->_aroRepository = $aroRepository;
        $cloudEnv = Config::get('cloud.cloud_env');
        if (isset($cloudEnv) && $cloudEnv <> '') {
            $this->_cloudObj = CloudFactory::build($cloudEnv);
        }
        parent::__construct();
    }

    /**
     * To show all users
     *
     * @param obj $request object of request
     *
     * @return object returns json object
     *
     * @SWG\Get(
     *     path="/users",
     *     summary="List all users",
     *     description="Get all users list",
     *     operationId="getUsers",
     *     produces={"application/json"},
     *     tags={"users"},
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(type="object", ref="#/definitions/UserData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the Accept header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="422",
     *         description="Un-processable entity"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $this->listUser($request, $this->_cloudObj);
    }

    /**
     * To show user using id
     *
     * @param obj $strUserId user id belongs to active directory
     *
     * @return object returns json object

     * @SWG\Get(
     *     path="/users/{id}",
     *     summary="Get user by ID",
     *     description="Get users details using user ID.This can only be done by the logged in user",
     *     operationId="getUserById",
     *     tags={"users"},
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Parameter(
     *         description="Fetch user using ID",
     *         format="string",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object", ref="#/definitions/UserData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the �Accept� header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="422",
     *         description="Un-processable entity"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function show($strUserId = '')
    {
        return $this->showUser($strUserId, $this->_cloudObj);
    }

    /**
     * To show user details of id
     *
     * @param request $request default user id
     *
     * @return object returns json object

     * @SWG\Post(
     *     path="/users",
     *     summary="Create user",
     *     description="Create user using user details",
     *     operationId="addUser",
     *     tags={"users"},
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Parameter(
     *         name="body",
     *         in="body",
     * @SWG\Schema(ref="#/definitions/UserCreateData")
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object", ref="#/definitions/UserFetchData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the �Accept� header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="422",
     *         description="Un-processable entity"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->addUser($request, $this->_cloudObj);
    }

    /**
     * To update user details
     *
     * @param request $request   request
     * @param integer $strUserId userId
     *
     * @return object returns json object
     *
     * @SWG\Put(
     *     path="/users/{id}",
     *     summary="Update user by ID",
     *     description="Update user basic information",
     *     operationId="updateUser",
     *     tags={"users"},
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Parameter(
     *         description="ID of user to fetch",
     *         format="string",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     * @SWG\Parameter(
     *         name="body",
     *         in="body",
     * @SWG\Schema(ref="#/definitions/UserPutData")
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object", ref="#/definitions/UserPutData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the �Accept� header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="422",
     *         description="Un-processable entity"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function update(Request $request, $strUserId)
    {
        return $this->editUser($strUserId, $request, $this->_cloudObj);
    }

    /**
     * Delete user data
     *
     * @param request $request   request object
     * @param integer $strUserId userId  user id
     *
     * @return object returns json object

     * @SWG\Delete(
     *     path="/users/{id}",
     *     summary="Delete user",
     *     description="Delete user by ID",
     *     operationId="DeleteUser",
     *     tags={"users"},
     * @SWG\Parameter(
     *         in="header",
     *         name="VeriCheck-Version",
     *         type="string",
     *         default="1.0.0",
     *     ),
     * @SWG\Parameter(
     *         description="ID of User to delete",
     *         format="string",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="string"
     *     ),
     *     produces={
     *         "application/json"
     *     },
     * @SWG\Response(
     *         response=200,
     *         description="OK - Everything worked as expected",
     * @SWG\Schema(
     *         type="object", ref="#/definitions/UserDeleteData")
     *     ),
     * @SWG\Response(
     *         response="400",
     *         description="Bad Request - Often due to a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized - An invalid element token, user secret and/or org secret provided"
     *     ),
     * @SWG\Response(
     *         response="403",
     *         description="Forbidden - Access to the resource by the provider is forbidden"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Not found - The requested resource is not found"
     *     ),
     * @SWG\Response(
     *         response="405",
     *         description="Method not allowed - Incorrect HTTP verb used, e.g., GET used when POST expected"
     *     ),
     * @SWG\Response(
     *         response="406",
     *         description="Not acceptable - The response content type does not match the �Accept� header value"
     *     ),
     * @SWG\Response(
     *         response="409",
     *         description="Conflict - If a resource being created already exists"
     *     ),
     * @SWG\Response(
     *         response="415",
     *         description="Unsupported media type - The server cannot handle the requested Content-Type"
     *     ),
     * @SWG\Response(
     *         response="422",
     *         description="Un-processable entity"
     *     ),
     * @SWG\Response(
     *         response="500",
     *         description="Server error - Something went wrong on the Cloud Elements server"
     *     )
     * )
     */
    public function destroy(Request $request, $strUserId)
    {
        return $this->deleteUser($strUserId, $request, $this->_cloudObj);
    }
}
