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
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Transformers;

use Illuminate\Support\Facades\Config;
use League\Fractal\TransformerAbstract;

/**
 * User transformer class for formatted user object
 *
 * @name     UserTransformer
 * @category Transformer
 * @package  Transformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class UserTransformer extends TransformerAbstract
{

    /**
     * Transformer function returns user object in formatted state
     *
     * @param Obj $objUser is used to transform the real user object
     *
     * @name   transform
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function transform($objUser)
    {
        $formattedUser = [
            "id" => $objUser->objectId, "first_name" => $objUser->givenName,
            "last_name" => $objUser->surname, "email" => isset($objUser->signInNames[0]->value) ? $objUser->signInNames[0]->value : 'NA',
            "phone" => $objUser->mobile, "address" => $objUser->streetAddress,
            "pincode" => $objUser->postalCode, 'status' => $objUser->accountEnabled,
            "etag" => time(), "created_date" => isset($objUser->refreshTokensValidFromDateTime) ? $objUser->refreshTokensValidFromDateTime : 'NA',
        ];
        $formattedUser = array_merge($formattedUser, $this->_checkCompanyExist($objUser));
        return $formattedUser;
    }

    /**
     * Function create active directory user object into the collection
     *
     * @param Object $objUser is active directory user collection object
     *
     * @name   _createUserCollection
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $arrCompanyExt
     */
    private function _checkCompanyExist($objUser)
    {
        $strCompanyExt = Config::get('cloud.company_id_extension');
        $arrCompanyExt = [];
        if (isset($objUser->$strCompanyExt)) {
            $arrCompanyExt['company_id'] = $objUser->$strCompanyExt;
        }
        return $arrCompanyExt;
    }
}
