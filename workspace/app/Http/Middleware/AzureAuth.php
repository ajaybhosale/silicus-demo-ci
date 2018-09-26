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
 * @category OAuth2
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Helpers;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Config;
use Modules\Infrastructure\Services\TokenService;
use function version;

/**
 * Authentication middleware checks user identity
 *
 * @name     AzureAuth
 * @category OAuth
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class AzureAuth
{

    use Helpers;
    use TokenService;

    /**
     * Handle method filters and validate access token before each request and
     * give access to user api's
     *
     * @param Obj     $request is used to get data sent by client
     * @param Closure $next    forward the user request
     *
     * @name   handle
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function handle($request, Closure $next)
    {
        $client = new Client();
        $apiConfig = Config::get('cloud.graph_api.profile');
        $accessToken = $this->bearerToken($request);
        if ($accessToken) {
            try {
                $profile = $client->request($apiConfig['method'], $apiConfig['url'], ['headers' => ['Authorization' => 'Bearer ' . $accessToken, 'Content-Type' => 'application/json']]);
                if ($accessToken !== null && $profile->getStatusCode() === 200) {
                    $response = $next($request);
                    return $response;
                } else {
                    $this->errorUnauthorized('Invalid Access Token');
                }
            } catch (ClientException $exception) {
                $this->errorUnauthorized('Invalid Access Token');
            }
        } else {
            $this->errorUnauthorized('Invalid Access Token');
        }
    }
}
