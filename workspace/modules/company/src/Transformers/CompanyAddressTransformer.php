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
 * @package  CompanyAddressTransformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Transformers;

use Modules\Company\Models\CompanyAddress;
use League\Fractal\TransformerAbstract;

/**
 * Two line description of class
 *
 * @name     CompanyAddressTransformer.php
 * @category Transformer
 * @package  CompanyAddressTransformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CompanyAddressTransformer extends TransformerAbstract
{

    /**
     * Function to set transform format
     *
     * @param Model $objCompanyAddress CompanyAddress model
     *
     * @name   transform
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function transform(CompanyAddress $objCompanyAddress)
    {
        return $arrAddressData = [
            'id' => $objCompanyAddress->CompanyAddressId,
            'company_id' => $objCompanyAddress->CompanyId,
            'address_id' => $objCompanyAddress->AddressId
        ];
    }
}
