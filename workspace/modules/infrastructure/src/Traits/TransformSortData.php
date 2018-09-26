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
 * Trait having methods for transforming sort request data
 *
 * @name     TransformSortData
 * @category Trait
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait TransformSortData
{

    public static $DESC_SEPARATOR = '-';

    /**
     * Function to transform sort request data
     *
     * @param Array $arrSort             Array of sort data
     * @param Array $arrTransformRequest Transform mapping data array
     *
     * @name   transformSortRequestData
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function transformSortRequestData($arrSort, $arrTransformRequest)
    {
        if (!empty($arrSort)) {
            $arrExplodeSort = explode(',', $arrSort['sort']);

            $arrExplodeSort = $this->_createSortArray($arrExplodeSort, $arrTransformRequest);

            $arrSort['sort'] = rtrim(implode(',', $arrExplodeSort), ',');
            return $arrSort;
        }
        return [];
    }

    /**
     * Function create sort array
     *
     * @param Array $arrExplodeSort      array of exploded data
     * @param Array $arrTransformRequest array Transform Request
     *
     * @name   _createSortArray
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $arrExplodeSort
     */
    private function _createSortArray($arrExplodeSort, $arrTransformRequest)
    {
        foreach ($arrExplodeSort as $key => $strExplodeSort) {
            $boolExist = false;

            $strSortSeparator = (false !== strpos($strExplodeSort, static::$DESC_SEPARATOR)) ? static::$DESC_SEPARATOR : '';
            $strParameter = str_replace(static::$DESC_SEPARATOR, '', $strExplodeSort);
            if (in_array($strParameter, array_keys($arrTransformRequest))) {
                $arrExplodeSort[$key] = $strSortSeparator . $arrTransformRequest[$strParameter];
            }
        }
        return $arrExplodeSort;
    }
}
