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
 * @package  CorporateStructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Seeder;
use Modules\CorporateStructure\Models\CorporateStructure;

/**
 * Master Corporate Structure table seeder
 *
 * @name     CorporateStructureTableSeeder
 * @category Seeder
 * @package  Seeder
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CorporateStructureTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrCorporateStructures = $this->_generateCorporateStructureData();
        CorporateStructure::insert($arrCorporateStructures);
    }

    /**
     * Function to generate sec code data array
     *
     * @name   _generateCorporateStructureData
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _generateCorporateStructureData()
    {
        $arrResponse = [];
        $arrCorporateStructure = $this->_getCorporateStructures();
        foreach ($arrCorporateStructure as $strName => $strDescription) {
            $arrResponse[] = [
                'CorporateStructureId' => Uuid::generate(4)->string,
                'Name' => $strName,
                'Description' => $strDescription,
                'Status' => 1,
                'CreatedAt' => time(),
                'Etag' => time()
            ];
        }
        return $arrResponse;
    }

    /**
     * Function to get sec codes
     *
     * @name   _getCorporateStructures
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _getCorporateStructures()
    {
        return $arrCorporateStructure = [
            'Share Holders' => 'Share Holders',
            'Board of Directors' => 'Board of Directors',
            'Chairman' => 'Chairman',
            'CEO' => 'Chief Executive Officer',
            'COO' => 'Chief Operating Officer',
            'CFO' => 'Chief Financial Officer',
            'CTO' => 'Chief Technical Officer',
        ];
    }
}
