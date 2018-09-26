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
 * @category Controller
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Illuminate\Database\Seeder;
use Modules\Note\Models\Note;
use Webpatser\Uuid\Uuid;
use Modules\User\Factory\Azure;
use Faker\Generator;

/**
 * NoteTableSeeder seeder
 *
 * @name     NoteTableSeeder Name
 * @category Seeder
 * @package  Seeder
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class NoteTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @param Obj $faker Request Object
     *
     * @name   addCompany
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        $arrNotes = $this->_getMasterNotes();
        Note::insert($arrNotes);
    }

    /**
     * Function to get array of master notes
     *
     * @name   _getMasterNotes
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _getMasterNotes()
    {
        $arrResponse = [];
        $arrNotes = $this->_getNotes();

        foreach ($arrNotes as $strNoteTitle => $strNoteDesc) {
            $arrResponse[] = [
                'NoteId' => Uuid::generate(4)->string,
                'Title' => $strNoteTitle,
                'Description' => $strNoteDesc,
                'Status' => 1,
                'CreatedAt' => time(),
                'Etag' => time()
            ];
        }

        return $arrResponse;
    }

    /**
     * Function to get notes
     *
     * @name   _getNotes
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _getNotes()
    {
        return $arrNotes = [
            'Updated Company Info' => 'Updated Company Info',
            'Updated Company Address' => 'Updated Company Address',
            'Updated Business Data' => 'Updated Business Data ',
            'Updated Velocity Credit ' => 'Updated Velocity Credit',
            'Updated Velocity Debit' => 'Updated Velocity Debit',
            'Updated Fund Security' => 'Updated Fund Security',
            'Updated Operations' => 'Updated Operations',
            'Updated Underwritings' => 'Updated Underwritings',
            'Updated vericheck' => 'Updated vericheck',
            'Updated Contact' => 'Updated Contact',
            'Updated ODFI' => 'Updated ODFI',
            'Updated Bank Details' => 'Updated Bank Details',
            'Updated  Fees' => 'Updated  Fees',
            'Updated Splits ' => 'Updated Splits'
        ];
    }
}
