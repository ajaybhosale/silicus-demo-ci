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
namespace Modules\User\Tests\AzureTest;

use Illuminate\Support\Facades\Config;
use Modules\User\Factory\CloudFactory;
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
class AzureTest extends TestCase
{

    private $_cloudEnv;
    private $_authorizeUrl;

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
        $response = $this->call('GET', 'token');
        $this->assertEquals(200, $response->status());
    }

    /**
     * Test azure cloud factory object is created or not
     *
     * @name   testCloudFactoyObject
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function testCloudFactoyObject()
    {
        $this->_cloudEnv = Config::get('cloud.cloud_env');
        if (isset($this->_cloudEnv) && $this->_cloudEnv <> '') {
            $cloudObj = CloudFactory::build($this->_cloudEnv);
        }
        $this->assertObjectHasAttribute('signInPolicy', $cloudObj);
        return $cloudObj;
    }

    /**
     * Test azure cloud factory GetAuthorizeUrl m
     *
     * @name   testCloudFactoyGetAuthorizeUrl
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function testCloudFactoyGetAuthorizeUrl()
    {
        $cloudObj = $this->testCloudFactoyObject();
        if (is_object($cloudObj)) {
            $response = $cloudObj->getAuthorizeUrl('https://', 'company');
        }
        $this->assertArrayHasKey('redirectUrl', $response);
        $this->assertArrayHasKey('statusCode', $response);
    }

    /**
     * Test azure cloud factory GetAdminAccessToken method response
     *
     * @name   testGetAdminAccessToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function testGetAdminAccessToken()
    {
        $cloudObj = $this->testCloudFactoyObject();
        if (is_object($cloudObj)) {
            $response = $cloudObj->getAdminAccessToken();
        }
        $this->assertArrayHasKey('token', $response);
        return $response;
    }

    /**
     * Test checks cloudfactory object is created or not
     *
     * @name   testCloudFactoryObject
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function testCloudFactoryObject()
    {
        $this->_cloudEnv = Config::get('cloud.cloud_env');
        $cloudObj = CloudFactory::build($this->_cloudEnv);
        $this->assertObjectHasAttribute('signInPolicy', $cloudObj);
        $cloudObj = CloudFactory::build('Aws');
        $this->assertArrayHasKey('message', $cloudObj);
        $this->assertArrayHasKey('statusCode', $cloudObj);
    }
}
