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
namespace Modules\Company\Traits\AmendmentNote;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Modules\Company\Models\Company;
use Modules\Note\Models\Note;
use Modules\Company\Repositories\AmendmentNoteRepository;
use Modules\Company\Transformers\AmendmentNoteTransformer;
use Modules\Company\Models\AmendmentNote;

/**
 * AddNote trait used to add notes
 *
 * @name     AddAmendmentNote
 * @category Trait
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
trait AddNote
{

    protected $KEYS_TO_UNSET = ['Etag', 'CreatedAt', 'EffectiveStartDate', 'EffectiveEndDate', 'TaxId'];

    /**
     * Add amendment note Method
     *
     * @param Obj $arrNotes Array of note data
     *
     * @name   AddNote
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return json
     */
    public function addNote($arrNotes)
    {
        $amdNoteTrans = new AmendmentNoteTransformer();
        $amdRepo = new AmendmentNoteRepository(new AmendmentNote());

        try {
            $this->checkRecordExistsById(new Company(), $arrNotes['company_id'], 'company'); // check for valid company id

            if ('' == $arrNotes['note_id'] && '' == $arrNotes['note']) {
                return $this->errorBadRequest("Note is required.");
            }

            if (isset($arrNotes['note_id']) && $arrNotes['note_id'] <> '') {
                $this->checkRecordExistsById(new Note(), $arrNotes['note_id'], 'note'); // check for valid note id
            }
            $arrDiff = $this->_processNoteData($arrNotes['old_value'], $arrNotes['new_value']); //proccess data

            if (0 < count($arrDiff)) {
                $arrNotes["field_data"] = json_encode($arrDiff);
                $amendmentNote = $amdRepo->save($amdNoteTrans->transformRequestParameters($arrNotes)); //save amendment note data
                return $this->response->item($amendmentNote, new AmendmentNoteTransformer(), ['key' => 'amendment_note']); //return data
            }
        } catch (QueryException $exception) {
            $this->errorInternal();
        }
    }

    /**
     * Function to get JSON of old values and new values for field
     *
     * @param Array $arrOrigData is used to get old data
     * @param Array $arrNewData  is used to get new data
     *
     * @name   processNoteData
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _processNoteData($arrOrigData, $arrNewData)
    {
        $arrNoteVal = [];
        $arrDif = $this->arrayDiffAssocRecursive($arrOrigData, $arrNewData);
        if (0 < count($arrDif)) {
            $arrDif = $this->_unsetKeys($arrDif);
            foreach ($arrDif as $key => $value) {
                if (is_array($value)) {
                    sort($arrOrigData[$key]);
                    sort($arrNewData[$key]);
                    $arrNoteVal[] = array('field_name' => $key, 'old_value' => implode(', ', $arrOrigData[$key]), 'new_value' => implode(', ', $arrNewData[$key]));
                } else {
                    $arrNoteVal[] = array('field_name' => $key, 'old_value' => $value, 'new_value' => $arrNewData[$key]);
                }
            }
        }
        return $arrNoteVal;
    }

    /**
     * Unset Keys from notes
     *
     * @param Array $arrDifference difference array to add in notes
     *
     * @name   _unsetKeys
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _unsetKeys($arrDifference)
    {
        foreach ($this->KEYS_TO_UNSET as $strKey) {
            if (isset($arrDifference[$strKey])) {
                unset($arrDifference[$strKey]);
            }
        }

        return $arrDifference;
    }

    /**
     * Function to get difference on associate multidimensional array
     *
     * @param Array $array1 The array to compare from
     * @param Array $array2 The array to compare against
     *
     * @name   arrayDiffAssocRecursive
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    public function arrayDiffAssocRecursive($array1, $array2)
    {

        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) && !is_null($value)) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key]) && !is_null($value)) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->arrayDiffAssocRecursive($value, $array2[$key]);
                    if ($new_diff != false) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif ((!isset($array2[$key]) || $array2[$key] != $value) && !is_null($value)) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? [] : $difference;
    }

    /**
     * Common function to get difference between old and new value
     *
     * @param Obj   $objModel is model object to get old and new values
     * @param Array $data     is old replaced fields
     *
     * @name   getDifference
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return Object
     */
    public function getDifference($objModel, array $data)
    {
        $fillAbleProperties = $objModel->getFillable();
        foreach ($data as $key => $value) {
            if (in_array($key, $fillAbleProperties)) {
                $objModel->$key = $value;
            }
        }
        return $objModel;
    }

    /**
     * Add/Update company operation note data using company Id
     *
     * @param String $oldValue       old value array
     * @param Object $newValue       new value array
     * @param Object $objRequestData request Object
     * @param String $strCompanyId   company ID
     * @param String $type           module Type
     *
     * @name   createNote
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function createNote($oldValue, $newValue, $objRequestData, $strCompanyId, $type = 'PROFILE')
    {
        $arrNoteData = [];
        $arrRequestData = $objRequestData;
        if (is_object($objRequestData)) {
            $arrRequestData = $objRequestData->all();
        }
        if (isset($arrRequestData['note'])) {
            $arrRequestData = $arrRequestData['note'];
            $arrNoteData = $this->_setNotesData($arrRequestData);
            $arrNoteData['company_id'] = $strCompanyId;
            $arrNoteData['created_by'] = $arrRequestData['user_id'];
            $arrNoteData['old_value'] = $oldValue;
            $arrNoteData['new_value'] = $newValue;
            $arrNoteData['type'] = $type;
            $this->addNote($arrNoteData);
        }
    }

    /**
     * Function create note array parameters
     *
     * @param Obj $arrRequestData is used to get data sent by client
     *
     * @name   _setNotesData
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    private function _setNotesData($arrRequestData)
    {
        $arrNoteData = [];
        $arrNoteData['note_id'] = null;
        if (isset($arrRequestData['master_note_id'])) {
            $arrNoteData['note_id'] = $arrRequestData['master_note_id'];
        }
        if (isset($arrRequestData['user_name']) && $arrRequestData['user_name'] <> '') {
            $arrNoteData['user_name'] = $arrRequestData['user_name'];
        }
        $arrNoteData['note'] = '';
        if (isset($arrRequestData['other_note']) && $arrRequestData['other_note'] <> '') {
            $arrNoteData['note'] = $arrRequestData['other_note'];
        }
        return $arrNoteData;
    }
}
