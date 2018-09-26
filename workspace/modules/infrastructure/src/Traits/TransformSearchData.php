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
 * @category Trait
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Traits;

/**
 * Trait having methods for transforming search request data
 *
 * @name     TransformSearchData
 * @category Trait
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait TransformSearchData
{

    /**
     * Function to transform search request data
     *
     * @param Array $arrSearch           Array of sort data
     * @param Array $arrTransformRequest Transform mapping data array
     *
     * @name   transformSortRequestData
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function transformSearchRequestData($arrSearch, $arrTransformRequest)
    {
        $arrTransformSearch = [];
        if (!empty($arrSearch)) {
            foreach ($arrSearch as $key => $strSearch) {
                if (isset($arrTransformRequest[$key])) {
                    $arrTransformSearch[$arrTransformRequest[$key]] = $strSearch;
                }
                $arrExceptKeys[] = $key;
            }

            return ['merge' => $arrTransformSearch, 'except' => $arrExceptKeys];
        }
        return [];
    }
}
