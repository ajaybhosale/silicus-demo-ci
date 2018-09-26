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
 * @category Category
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Traits\AmendmentNote;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Modules\Company\Models\Company;

/**
 * ShowAmendmentNotes trait used to show amendment notes
 *
 * @name     ShowAmendmentNotes
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait ShowAmendmentNotes
{

    /**
     * Function to get amendment note
     *
     * @param Obj $strCompanyId Company Id
     *
     * @name   showAmendmentNotes
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function showAmendmentNotes($strCompanyId)
    {
        try {
            //check for valid company id
            $this->checkRecordExistsById(new Company(), $strCompanyId, 'company');
            $objAmendmentNotes = $this->_amendmentNoteRepo->getNotes($strCompanyId);

            $objAmendmentNotes = collect($objAmendmentNotes);
            $sorted = $objAmendmentNotes->sortByDesc('CreatedAt');

            return $this->response->collection($sorted, $this->_amendmentNoteTran, ['key' => 'amendment_note']);
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }
}
