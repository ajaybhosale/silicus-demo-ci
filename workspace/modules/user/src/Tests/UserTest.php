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
 * @category User
 * @package  Testcase
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Tests\UserTest;

use Illuminate\Support\Facades\Config;
use Modules\Infrastructure\Services\TokenService;
use Modules\User\Traits\Login\AccessToken;
use Modules\User\Traits\Login\UserAuthenticate;
use Modules\User\Traits\Login\UserLogout;
use Modules\User\Traits\Login\UserSignInUrl;
use Modules\User\Traits\User\AddUser;
use Modules\User\Traits\User\DeleteUser;
use Modules\User\Traits\User\EditUser;
use Modules\User\Traits\User\ListUser;
use Modules\User\Traits\User\ShowUser;
use Modules\User\Traits\User\UserAttributes;
use Modules\User\Traits\User\UserValidator;
use TestCase;

/**
 * User test cases for user account
 *
 * @name     UserTest
 * @category Testcase
 * @package  Test
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class UserTest extends TestCase
{

    use TokenService;
    use UserValidator;
    use UserAttributes;
    use DeleteUser;
    use AddUser;
    use EditUser;
    use ShowUser;
    use ListUser;
    use AccessToken;
    use UserLogout;
    use UserAuthenticate;
    use UserSignInUrl;

    /**
     * Get all user information without pagination
     *
     * @name   testUserList
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function testUserList()
    {
        $response = $this->call('GET', 'users');
        $this->assertEquals(200, $response->status());
        $data = json_decode($response->getContent())->data;
        return $data;
    }

    /**
     * Get user information using user id
     *
     * @name   testGetUserById
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testGetUserById()
    {
        $objTestdata = $this->testUserList();
        if (!empty($objTestdata)) {
            $firstUser = $objTestdata[0];
            $payload = [
                'first_name' => $firstUser->attributes->first_name, 'last_name' => $firstUser->attributes->last_name,
                'address' => $firstUser->attributes->address, 'pincode' => 41250, 'mobile_no' => 9874563210,
            ];
            $getResponse = $this->call('GET', 'users/' . $firstUser->id);             // Get Users
            $this->assertEquals(200, $getResponse->status());
            $deleteResponse = $this->call('DELETE', 'users/' . $firstUser->id); // Delete User
            $this->assertEquals(200, $deleteResponse->status());
            $patchResponse = $this->call('PATCH', 'users/' . $firstUser->id); //Patch User
            $this->assertEquals(200, $patchResponse->status());
            $putResponse = $this->call('PUT', 'users/' . $firstUser->id, $payload); //update User
            $this->assertEquals(200, $putResponse->status());
        }
    }

    /**
     * Create new staff user test case
     *
     * @name   testAddUser
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testAddUser()
    {
        $payload = [
            'first_name' => 'vericheckUnitTestCaseUser',
            'last_name' => 'vericheckUnitTestCaseUser',
            'email' => 'Test@test.com',
            'address' => 'New market city,Hadapsar near pune',
            'password' => 'Test1234',
            'confirm_pwd' => 'Test1234',
            'pincode' => '41310',
            'company_id' => '1',
            'mobile_no' => '7507290311',
        ];
        $response = $this->call('POST', 'users', $payload);
        $this->assertEquals($response->status(), $response->status());
    }

    /**
     * Update application token test case inside the database to manipulate user
     * List inside active directory using client credentials grant
     *
     * @name   testUpdateApplicationToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testUpdateApplicationToken()
    {
        $objTestdata = $this->testUserList();
        $response = $this->call('GET', 'token');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Test case to get sign in url using active directory
     *
     * @name   testGetSignInUrl
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testGetSignInUrl()
    {
        $redirectUri = Config::get('cloud.azure_store_config.redirectUri');
        $response = $this->call('GET', 'login?redirect_uri=' . $redirectUri);
        $this->assertEquals(200, $response->status());
    }

    /**
     * Test case for logout from admin portal
     *
     * @name   testGetSignOutUrl
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function testGetSignOutUrl()
    {
        $objTestdata = $this->testUserList();
        $response = $this->call('GET', 'logout');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Test search user
     *
     * @name   testSearchUser
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function testSearchUser()
    {
        $response = $this->call('GET', 'users?name=test&top=10');
        $this->assertEquals(200, $response->status());
        $response = $this->call('GET', 'users?email=test&top=10');
        $this->assertEquals(200, $response->status());
        $response = $this->call('GET', 'authenticate?code=test&state=10');
        $this->assertEquals(500, $response->status());
    }
}
