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
 * @package  AmendmentNoteTransformer
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;
use Modules\Company\Models\AmendmentNote;
use Modules\Infrastructure\Services\TransformRequest;

/**
 * Principal Transformer to transform database fields to API
 *
 * @name     AmendmentNoteTransformer
 * @category Transformer
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class AmendmentNoteTransformer extends TransformerAbstract
{

    use TransformRequest;

    /**
     * Function to set transform format
     *
     * @param Model $objAmendmentNote amendmentNote model
     *
     * @name   transform
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function transform(AmendmentNote $objAmendmentNote)
    {

        $arrAmendNote = [
            'id' => $objAmendmentNote->AmendmentNoteId,
            'company_id' => $objAmendmentNote->CompanyId,
            'user_name' => $objAmendmentNote->UserName,
            'note_id' => $objAmendmentNote->NoteId,
            'note' => $objAmendmentNote->Note,
            'field_data' => $objAmendmentNote->FieldData,
            'title' => $objAmendmentNote->Title,
            'type' => $objAmendmentNote->Type,
            'created_by' => $objAmendmentNote->CreatedBy,
            'created_at' => $objAmendmentNote->CreatedAt,
        ];

        return $arrAmendNote;
    }

    /**
     * Function is used to transform user fields to table fields
     *
     * @param Array $arrInput array of input received
     *
     * @name   transformRequestParameters
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function transformRequestParameters($arrInput)
    {
        $arrNoteData = $this->getTransformRequest($arrInput, $this->amendmentNoteTransformRequest());
        return $arrNoteData;
    }

    /**
     * Function is used to declare amendmentNote table fields for transformer request
     *
     * @name   amendmentNoteTransformRequest
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function amendmentNoteTransformRequest()
    {
        return $arrTransformReq = [
            'id' => 'AmendmentNoteId',
            'company_id' => 'CompanyId',
            'user_name' => 'UserName',
            'note_id' => 'NoteId',
            'note' => 'Note',
            'field_data' => 'FieldData',
            'type' => 'Type',
            'created_by' => 'CreatedBy',
            'created_at' => 'CreatedAt',
        ];
    }
}
