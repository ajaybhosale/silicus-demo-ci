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
 * @category Transformer
 * @package  AddressTransformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Transformers;

use Modules\Infrastructure\Services\TransformRequest;
use Modules\Company\Models\Address;
use League\Fractal\TransformerAbstract;

/**
 * Address Transformer to transform DB fields to API and vice versa
 *
 * @name     AddressTransformer.php
 * @category Transformer
 * @package  Company_Address
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class AddressTransformer extends TransformerAbstract
{

    use TransformRequest;

    /**
     * Function to set transform format
     *
     * @param Model $objAddress Address model
     *
     * @name   addressTransform
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function transform(Address $objAddress)
    {
        return $arrAddress = [
            'id' => $objAddress->AddressId,
            'nickname' => $objAddress->NickName,
            'address_line1' => $objAddress->AddressLine1,
            'address_line2' => $objAddress->AddressLine2,
            'city' => $objAddress->City,
            'state' => $objAddress->State,
            'zip' => $objAddress->Zip,
            'primary_phone' => $objAddress->PrimaryPhone,
            'secondary_phone' => $objAddress->SecondaryPhone,
            'primary_email' => $objAddress->PrimaryEmail,
            'secondary_email' => $objAddress->SecondaryEmail,
            'fax' => $objAddress->Fax,
            'address_type' => $objAddress->Type,
            'created_at' => $objAddress->CreatedAt,
            'etag' => $objAddress->Etag
        ];
    }

    /**
     * Function is used to transform user fields to table fields
     *
     * @param Array $arrInputs array of input received
     *
     * @name   transformRequestParameters
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function transformRequestParameters($arrInputs)
    {
        $objAddressData = [];
        $objAddressData = $this->getTransformRequest($arrInputs, $this->addressTransformRequest());
        return $objAddressData;
    }

    /**
     * Function is used to declare address table fields for transformer request
     *
     * @name   addressTransformRequest
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function addressTransformRequest()
    {
        return $arrTransformReq = [
            'id' => 'AddressId',
            'nickname' => 'NickName',
            'address_line1' => 'AddressLine1',
            'address_line2' => 'AddressLine2',
            'city' => 'City',
            'state' => 'State',
            'zip' => 'Zip',
            'primary_phone' => 'PrimaryPhone',
            'secondary_phone' => 'SecondaryPhone',
            'primary_email' => 'PrimaryEmail',
            'secondary_email' => 'SecondaryEmail',
            'fax' => 'Fax',
            'address_type' => 'Type',
            'etag' => 'Etag',
            'created_at' => 'CreatedAt',
        ];
    }
}
