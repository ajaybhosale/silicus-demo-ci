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
 * @package  Sec
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Seeder;
use Modules\Sec\Models\Sec;

/**
 * Master Sec table seeder
 *
 * @name     SecCodeTableSeeder
 * @category Seeder
 * @package  Seeder
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class SecCodeTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrSecCodes = $this->_generateSecCodeData();
        Sec::insert($arrSecCodes);
    }

    /**
     * Function to generate sec code data array
     *
     * @name   _generateSecCodeData
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _generateSecCodeData()
    {
        $arrResponse = [];
        $arrSecCode = $this->_getSecCodes();
        foreach ($arrSecCode as $strCode => $strDescription) {
            $arrResponse[] = [
                'SecId' => Uuid::generate(4)->string,
                'Code' => $strCode,
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
     * @name   _getSecCodes
     * @access private
     * @author VCI <info@vericheck.net>
     *
     * @return array
     */
    private function _getSecCodes()
    {
        return $arrSecCode = [
            'ARC' => 'Accounts Receivable Entry',
            'BOC' => 'Back Office Conversion',
            'CCD' => 'Cash Concentration and Disbursement',
            'CIE' => 'Customer Initiated Entry',
            'CTX' => 'Corporate Trade Exchange',
            'XCK' => 'Destroyed Check Entries',
            'POP' => 'Point-of-Purchase Entry',
            'POS' => 'Point-of-Sale Entry',
            'PPD' => 'Prearranged Payment and Deposit',
            'RCK' => 'Re-presented Check Entry',
            'TEL' => 'Telephone-Initiated Entry',
            'WEB' => 'Internet-Initiated Entry'
        ];
    }
}
