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

use Illuminate\Database\QueryException;
use Modules\Infrastructure\Models\Token;

/**
 * Manage Token traits
 *
 * @name     ManageToken
 * @category Trait
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ManageToken
{

    /**
     * Get token value
     *
     * @param Obj $token Token key
     *
     * @name   getToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function getToken($token)
    {
        try {
            $objToken = new Token();
            $data = $objToken->where('TokenId', $token)->first();
            return (is_null($data)) ? '' : $data->Value;
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }

    /**
     * Set token value
     *
     * @param Obj $data data to encrypt
     * @param Obj $type data type
     *
     * @name   setToken
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return mixed
     */
    public function setToken($data, $type = null)
    {
        try {
            $objToken = new Token;

            $objToken->Key = md5($data);
            $objToken->Value = $data;
            $objToken->Type = $type;

            $objToken->save();

            return $objToken->TokenId;
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }
}
