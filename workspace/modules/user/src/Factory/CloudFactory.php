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
 * @category CloudFactory
 * @package  Factory
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Factory;

/**
 * ClodFactory class loads secure cloud services platform like AWS/Microsoft Azure
 * to build sophisticated applications with increased flexibility,
 * scalability and reliability
 *
 * @name     CloudFactory.php
 * @category CloudFactory
 * @package  Factory
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CloudFactory
{

    /**
     * Static function create the cloud service object runtime
     * Cloud object may be Azure or AWS
     *
     * @param string $strCloudObjectName is used to create the class of cloud engine
     *
     * @name   build
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return Object
     */
    public static function build($strCloudObjectName = '')
    {
        $arrResponse = [];
        $objCloud = '';
        $strCloudPath = "Modules\User\Factory\\" . $strCloudObjectName;
        if ("" != $strCloudPath && true == class_exists($strCloudPath)) {
            $objCloud = new $strCloudPath();
        } else {
            $arrResponse['statusCode'] = 404;
            $arrResponse['message'] = "Unable to load class" . $strCloudPath;
            return $arrResponse;
        }
        return $objCloud;
    }
}
