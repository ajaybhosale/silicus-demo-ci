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
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace App\Http\Controllers;

use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Routing\Helpers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller as BaseController;
use Modules\Infrastructure\Services\ValidationRegex;
use stdClass;

//use Illuminate\Support\Facades\Config;

/**
 * Controller class defines for all api call
 *
 * @name     Controller
 * @category Controller
 * @package  App
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id: da75e564ffb4f7fd67bad71778009e8c02067ce9 $
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class Controller extends BaseController
{

    use Helpers;
    use ValidationRegex;

    const SUCCESS_CODE = 200;

    public $cmsUrl = null;

    /**
     * Site URL
     *
     * @var string $siteUrl
     */
    public $url = '';

    /**
     * Site theme name
     *
     * @var string $theme
     */
    public $theme = '';

    /**
     * Constructer of controller class
     *
     * @name   __construct
     * @access public
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function __construct()
    {
        $this->url = config('app.url');
        $this->theme = config('app.theme');
    }

    /**
     * This will loda JS and CSS file dynamically
     *
     * @name   loadJsCSS
     * @access public
     * @author Ajay Bhosale <ajay.bhosale@silicus.com>
     *
     * @param array $js  description
     * @param array $css description
     *
     * @return void
     */
    public function loadJsCSS($js = null, $css = null)
    {
        if (is_array($js)) {
            view()->share('jsFiles', $js);
        }

        if (is_array($css)) {
            view()->share('cssFiles', $css);
        }
    }

    /**
     * Validate each request for handling error request
     *
     * @param Obj    $request          is used to get data sent by client
     * @param array  $arrRules         is rules array passed to handle api request
     * @param array  $arrMessages      is display the default error message
     * @param array  $arrAttributes    is attributes are different according to fields
     * @param array  $arrAttributeCode is code associated with each fields
     * @param String $strExceptionMsg  is display the exception message if occurred during error handling
     *
     * @name   validateRequest
     * @access protected
     * @author VCI <info@vericheck.net>
     *
     * @return boolean
     */
    protected function validateRequest(Request $request, array $arrRules, array $arrMessages = [], $arrAttributes = [], $arrAttributeCode = [], $strExceptionMsg = 'Validation Failed')
    {
        $objValidator = Validator::make($request->all(), $arrRules, $arrMessages, $arrAttributes);
        if ($objValidator->fails()) {
            $arrReformErrors = $this->reformErrors($objValidator, $arrAttributeCode);
            throw new ResourceException($strExceptionMsg, $arrReformErrors);
        }
        return true;
    }

    /**
     * This method will reform error response.
     *
     * @param Obj   $objValidator is used to get error object
     * @param array $arrCodes     is code associated with each filed
     *
     * @name   _reformErrors
     * @access protected
     * @author VCI <info@vericheck.net>
     *
     * @return Obj Reform error object
     */
    protected function reformErrors($objValidator, $arrCodes)
    {
        $arrReformErrors = [];
        $arrCodeErrors = $objValidator->errors()->getMessages();
        $objErrors = $objValidator->failed();
        foreach ($objErrors as $strErrorKey => $arrRules) {
            $strErrCodeKey = $strErrorKey;
            $arrErrorKey = explode('.', $strErrorKey);
            if (is_array($arrErrorKey) && !empty($arrErrorKey) && isset($arrErrorKey[1]) && is_numeric($arrErrorKey[1])) {
                $arrErrorKey[1] = '*';
                $strErrCodeKey = implode('.', $arrErrorKey);
            }

            $intIterator = 0;
            foreach ($arrRules as $strKeyRule => $strKeyValue) {
                $intCode = (!empty($arrCodes) && isset($arrCodes[$strErrCodeKey])) ? $arrCodes[$strErrCodeKey] : "";
                $objError = new stdClass();
                $objError->code = $intCode;
                $objError->type = $strKeyRule;
                $objError->message = $arrCodeErrors[$strErrorKey][$intIterator];
                $objError->more_info = $this->cmsUrl . $intCode;
                $arrReformErrors[] = $objError;
                $intIterator++;
            }
        }
        return $arrReformErrors;
    }

    /**
     * Set application guzzlehttp call global to graph api's
     *
     * @param string $method      method type of https request
     * @param string $graphUrl    graph api url
     * @param string $token       user access token
     * @param string $contentType content-type for the request
     * @param string $body        content-type for the request
     *
     * @name   makeGraphApiCall
     * @access protected
     * @author VCI <info@vericheck.net>
     *
     * @return $objResponse
     */
    protected function makeGraphApiCall($method = 'GET', $graphUrl = '', $token = '', $contentType = 'application/json', $body = '')
    {
        $objResponse = [];
        $objGuzzleClient = new Client(['base_uri' => $graphUrl, 'verify' => false]);
        try {
            if (isset($method) && ($method == 'GET')) {
                $objResponse = $objGuzzleClient->request($method, $graphUrl, ['headers' => ['Authorization' => 'Bearer ' . $token, 'Content-Type' => $contentType]]);
            } else {
                $objResponse = $objGuzzleClient->request($method, $graphUrl, ['headers' => ['Authorization' => 'Bearer ' . $token, 'Content-Type' => 'application/json'], 'body' => $body]);
            }
            $objResponse = $objResponse->getBody()->getContents();
        } catch (\Exception $exception) {
            $objResult = json_decode($exception->getResponse()->getBody()->getContents(), true);
            if (isset($objResult['odata.error']['message'])) {
                $objResponse['data']['error']['status_code'] = $exception->getCode();
                $objResponse['data']['error']['message'] = $objResult['odata.error']['message']['value'];
                return json_encode($objResponse);
            } else {
                $objResponse = $objResult;
            }
        }
        return $objResponse;
    }
}
