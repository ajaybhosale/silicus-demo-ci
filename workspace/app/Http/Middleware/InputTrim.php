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
 * @package  Package_Name
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace App\Http\Middleware;

use Closure;

/**
 * Trim all request input
 *
 * @name     InputTrim
 * @category Middleware
 * @package  Middleware
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class InputTrim
{

    const SKIP_INPUT = ['password', 'confirm_pwd', 'code', 'state', 'driver_license_state'];

    /**
     * Handle to trim all request input
     *
     * @param Obj $request is used to get data sent by client
     * @param Obj $next    closure object
     *
     * @name   handle
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        if (!empty($input)) {
            array_walk_recursive($input, function (&$item, $key) {
                if (!in_array($key, static::SKIP_INPUT, true)) {
                    $item = trim($item);
                    $item = ($item == "") ? null : $item;
                }
            });
            $request->merge($input);
        }
        return $next($request);
    }
}
