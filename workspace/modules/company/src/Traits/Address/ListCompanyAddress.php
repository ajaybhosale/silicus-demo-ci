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
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Traits\Address;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use JohannesSchobel\DingoQueryMapper\Parser\DingoQueryMapper;
use Modules\Company\Models\Company;
use Modules\Company\Transformers\AddressTransformer;
use Modules\Infrastructure\Services\TransformRequest;

/**
 * ListCompanyAddress Trait to get all company contacts with search, sort and pagination
 *
 * @name     ListCompanyAddress
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ListCompanyAddress
{

    use TransformRequest;

    /**
     * Function listCompanyAddress to get Company Address list with search, sort and pagination
     *
     * @param Object $request      Request Object
     * @param String $strCompanyId Company Id
     *
     * @name   listCompanyAddress
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function listCompanyAddress(Request $request, $strCompanyId)
    {
        try {
            $this->checkRecordExistsById(new Company(), $strCompanyId, 'company');  // check for valid company id

            $addressTransformer = new AddressTransformer();

            // transform request parameter for search and sort
            $request = $this->transformSearchOrSortRequest($request, $addressTransformer->addressTransformRequest());

            $arrobjAddress = $this->_addressRepository->getAllAddressesByCompanyId($strCompanyId);
            $queryMapper = new DingoQueryMapper($request);
            $address = $queryMapper->createFromCollection($arrobjAddress)->paginate();

            return $this->response->paginator($address, $addressTransformer, ['key' => 'address']);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }
}
