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
return [
    /*
     * Are filter queries allowed? If set to true, queries like age>18 are allowed
     */
    'allowFilters'       => true,
    /*
     * The default values
     */
    'defaults'           => [
        'limit' => 20,
        'sort'  => [
            [
                'column'    => 'Etag',
                'direction' => 'desc'
            ]
        ],
    ],
    /*
     * The parameters to be excluded
     */
    'excludedParameters' => [
        'include', // because of Fractal Transformers
        'token', // because of JWT Auth
    ],
];
