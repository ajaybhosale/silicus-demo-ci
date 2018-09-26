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
 * @category Uuid
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Services;

/**
 * This trait is used to generate UUID for respected model
 *
 * @name     ValidationRegex
 * @category Infrastructure
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ValidationRegex
{

    public static $REGEX_UUID = '/^\{?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}\}?$/';
    public static $REGEX_ALPHA_SPACE = "/(^[a-zA-Z ]+$)+/";
    public static $REGEX_ALPHA_NUMERIC_SPACE = "/(^[a-zA-Z0-9 ]+$)+/";
    public static $REGEX_NUMERIC = "/(^[0-9]+$)+/";
    public static $REGEX_PASSWORD = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/';
    public static $REGEX_ALPHA = '/^[a-z0-9 .\-]+$/i';
    public static $REGEX_TIN = '/[0-9]{3}\-[0-9]{2}\-[0-9]{4}$/';
    public static $REGEX_DECIMAL = '/^\d*(\.\d{1,2})?$/';
}
