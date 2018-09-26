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
 * @package  CompanyTransformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Modules\Company\Models\Company;
use Modules\Company\Traits\Company\CompanyRelationships;
use Modules\Infrastructure\Services\TransformRequest;

/**
 * Two line description of class
 *
 * @name     CompanyTransformer.php
 * @category Transformer
 * @package  CompanyTransformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CompanyTransformer extends TransformerAbstract
{

    use CompanyRelationships;
    use TransformRequest;

    /**
     * Function to set transform format
     *
     * @param Model $objCompany Company model
     *
     * @name   transform
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function transform(Company $objCompany)
    {
        $arrCompanyData = ['id' => $objCompany->CompanyId,
            'type' => $objCompany->Type, 'odfi_id' => $objCompany->OdfiId,
            'name' => $objCompany->Name, 'doing_business_as' => $objCompany->DoingBusinessAs,
            'tax_id' => $objCompany->TaxId, 'website_url' => $objCompany->WebsiteUrl,
            'created_at' => $objCompany->CreatedAt, 'etag' => $objCompany->Etag,
        ];

        return $arrCompanyData;
    }

    /**
     * Function is used to transform user fields to table fields
     *
     * @param Obj $arrInputs input
     *
     * @name   transformRequestParameters
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function transformRequestParameters($arrInputs)
    {
        $arrCompanyData = [];
        $arrCompanyData = $this->getTransformRequest($arrInputs, $this->transformRequest());
        return $arrCompanyData;
    }

    /**
     * Function is used to declare company table fields for transformer request
     *
     * @name   _transformRequest
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function transformRequest()
    {
        return $arrTransformReq = ['id' => 'CompanyId',
            'type' => 'Type',
            'name' => 'Name', 'doing_business_as' => 'DoingBusinessAs',
            'tax_id' => 'TaxId',
            'created_at' => 'CreatedAt', 'etag' => 'Etag',
            'deleted_at' => 'DeletedAt'];
    }
}
