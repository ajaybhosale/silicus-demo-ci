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
 * @category Auth
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Models;

/**
 * Get all directory users from azure active directory
 *
 * @SWG\Definition(
 *  definition="UserGetData",
 *  type="object",
 *  @SWG\Property(property="data",  type="array",  @SWG\Items(ref="#/definitions/UserData")),
 *  @SWG\Property(property="meta",  type="object", ref="#/definitions/Meta"),
 *  @SWG\Property(property="links", type="object", ref="#/definitions/Links")
 * )
 */

/**
 * Get the user's list with dingo api
 *
 * @SWG\Definition(
 *  definition="UserFetchData",
 *  type="object",
 *  @SWG\Property(property="data", type="object", ref="#/definitions/Data")
 * )
 */


/**
 * Create vericheck staff user inside azure active directory
 *
 * @SWG\Definition(
 *  definition="UserCreateData",
 *  type="object",
 *          @SWG\Property(property="first_name",  type="string", description="Firstname of user"),
 *          @SWG\Property(property="last_name",   type="string", description="Surname of user"),
 *          @SWG\Property(property="email",       type="string", description="Personnel email id"),
 *          @SWG\Property(property="password",    type="string", description="Password"),
 *          @SWG\Property(property="confirm_pwd", type="string", description="confirm password field"),
 *          @SWG\Property(property="mobile_no",   type="string", description="Primary mobile number"),
 *          @SWG\Property(property="address",     type="string", description="Address of the user"),
 *          @SWG\Property(property="pincode",     type="string", description="Postal code"),
 *          @SWG\Property(property="company_id",  type="string", description="Individual company id of user"),
 * )
 */

/**
 * Update user profile api.
 *
 * @SWG\Definition(
 *  definition="UserPutData",
 *  type="object",
 *  @SWG\Property(property="first_name", type="string", description="first name of user"),
 *  @SWG\Property(property="last_name",  type="string", description="Last name of user"),
 *  @SWG\Property(property="mobile_no",  type="string", description="Phone no of user"),
 *  @SWG\Property(property="address",    type="string", description="Address of user"),
 *  @SWG\Property(property="pincode",    type="string", description="Pincode of user"),
 * )
 */



/**
 * Enable/Disable user from active directory
 *
 * @SWG\Definition(
 *  definition="UserDeleteData",
 *  type="object",
 *  @SWG\Property(property="id",     type="string", description="user id"),
 *  @SWG\Property(property="status", type="string", description="status as deleted")
 * )
 */

/**
 * Definition
 *
 * @SWG\Definition(
 *  definition="UserData",
 *  type="object",
 *  @SWG\Property(property="id",           type="string", description="id"),
 *  @SWG\Property(property="type",         type="string", description="type"),
 *  @SWG\Property(
 *          property="attributes",
 *          type="object",
 *          @SWG\Property(property="first_name",   type="string", description="first name of user"),
 *          @SWG\Property(property="last_name",    type="string", description="Last name of user"),
 *          @SWG\Property(property="email",        type="string", description="Email of user"),
 *          @SWG\Property(property="mobile_no",    type="string", description="Phone no of user"),
 *          @SWG\Property(property="address",      type="string", description="Address of user"),
 *          @SWG\Property(property="pincode",      type="string", description="Pincode of user"),
 *          @SWG\Property(property="status",       type="boolean", description="User current status"),
 *          @SWG\Property(property="etag",         type="string", description="Etag of user"),
 *          @SWG\Property(property="created_date", type="string", description="Created date of user"),
 *      )
 * )
 */
