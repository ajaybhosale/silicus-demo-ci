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
 * @category Transformer
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Services;

use Illuminate\Support\Facades\Config;
use Modules\Infrastructure\Traits\TransformSearchData;
use Modules\Infrastructure\Traits\TransformSortData;

/**
 * Common GetTransformRequest method to get input request from user and convert input to transform format
 *
 * @name     GetTransformRequest
 * @category Transformer
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait TransformRequest
{

    use TransformSortData;
    use TransformSearchData;

    /**
     * Function get input request from user and convert input to transform format
     *
     * @param Array $input               request Array
     * @param Array $arrTransformRequest Transform array mapping
     *
     * @name   getTransformRequest
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function getTransformRequest($input, $arrTransformRequest)
    {
        $transformData = [];
        foreach ($input as $key => $value) {
            if (isset($arrTransformRequest[$key])) {
                $transformData[$arrTransformRequest[$key]] = $value;
            }
        }
        $transformData["Etag"] = time();

        $transformData = array_merge($transformData, $this->_checkRequestId($input));
        return $transformData;
    }

    /**
     * Function get input request from user and convert input to transform format
     *
     * @param Array $input request Array
     *
     * @name   _checkRequestId
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _checkRequestId($input)
    {
        $transformData = [];
        if (!isset($input['id']) || '' == $input['id']) {
            $transformData['CreatedAt'] = time();
        }
        return $transformData;
    }

    /**
     * Function get search or sort input request from user and convert it to DB format
     *
     * @param Object $request             Request object
     * @param Array  $arrTransformRequest Transform array mapping
     *
     * @name   transformSearchOrSortRequest
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return object
     */
    public function transformSearchOrSortRequest($request, $arrTransformRequest)
    {
        $arrTransformData = $arrPagination = $arrSort = $arrSearch = [];
        $inputRequest = $request->all();
        $arrPagination = array_only($inputRequest, ['page', 'limit']);
        $arrSort = array_only($inputRequest, ['sort']);
        $arrSearch = array_diff($inputRequest, $arrPagination, $arrSort);
        // transform Sort data
        $arrSort = $this->transformSortRequestData($arrSort, $arrTransformRequest);
        // transform search data
        $arrSearchRequest = $this->transformSearchRequestData($arrSearch, $arrTransformRequest);
        $arrTransformSearch = isset($arrSearchRequest['merge']) ? $arrSearchRequest['merge'] : [];
        $arrTransformData = array_merge($arrTransformData, $arrPagination, $arrSort, $arrTransformSearch);
        $strQueryString = $this->_transformQueryString($arrTransformData); // transform query string
        $request->server->set('QUERY_STRING', $strQueryString);
        $request->merge($arrTransformData); // Merge transform request data
        $this->_excludeDingoQueryParameter(); //exclude parameters from dingo query mapper
        return $request;
    }

    /**
     * Function transforms query parameter string
     *
     * @param Array $arrTransformData query string array
     *
     * @name   _transformQueryString
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return String
     */
    private function _transformQueryString($arrTransformData)
    {
        $strQueryString = '';
        foreach ($arrTransformData as $key => $value) {
            $strQueryString .= $key . '=' . $value . '&';
        }
        $strQueryString = rtrim($strQueryString, '&');
        return $strQueryString;
    }

    /**
     * Function transforms query parameter string
     *
     * @name   _excludeDingoQueryParameter
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    private function _excludeDingoQueryParameter()
    {
        $arrExceptKeys = [];
        $arrExcludeParameter = Config::get('dingoquerymapper.excludedParameters');
        Config::set('dingoquerymapper.excludedParameters', array_merge($arrExcludeParameter, $arrExceptKeys));
    }
}
