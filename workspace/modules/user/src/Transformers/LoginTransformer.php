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
 * @package  LoginTransformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Transformers;

use Illuminate\Support\Facades\Config;
use League\Fractal\TransformerAbstract;

/**
 * Two line description of class
 *
 * @name     LoginTransformer
 * @category Transformer
 * @package  LoginTransformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class LoginTransformer extends TransformerAbstract
{

    /**
     * Login page transformer to transform the login api output
     *
     * @param Obj $singIn is used to get data sent by client
     *
     * @name   transform
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $formattedUrl
     */
    public function transform($singIn)
    {
        if (Config::get('cloud.cloud_env') == 'Azure') {
            $formattedUrl = [
                'id' => $singIn->id,
            ];
            if (isset($singIn->accessToken) && $singIn->accessToken <> '') { // How to use request params to set this flag? Or Is there another alternative?
                $formattedUrl['access_token'] = $singIn->accessToken;
                $formattedUrl['refresh_token'] = $singIn->refreshToken;
                $formattedUrl['expires_in'] = $singIn->expiresIn;
            } else {
                $formattedUrl['redirect_url'] = $singIn->redirectUrl;
            }
        }
        return $formattedUrl;
    }
}
