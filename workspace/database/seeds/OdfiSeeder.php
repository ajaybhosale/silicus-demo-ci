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
 * @package  Bank
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
use Illuminate\Database\Seeder;
use Modules\Bank\Models\Odfi;
use Modules\Bank\Models\OdfiFedWindow;
use Webpatser\Uuid\Uuid;
use Modules\User\Factory\Azure;

/**
 * OdfiSeeder seeder
 *
 * @name     OdfiSeeder Name
 * @category Seeder
 * @package  Seeder
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class OdfiSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $odfiDetailsCBOC = Odfi::create([
                'RoutingNumber' => '031201360',
                'Abbreviation' => 'CBOC',
                'Code' => 'CBOC',
                'SftpSendHost' => 'test',
                'SftpSendUsername' => 'test',
                'SftpSendKey' => 'testkey',
                'SftpSendPort' => 123,
                'SftpReceiveHost' => 'test',
                'SftpReceiveUsername' => 'test',
                'SftpReceiveKey' => 'testkey',
                'SftpReceivePort' => 456,
                'Status' => 1,
                'Etag' => time()
        ]);

        for ($i = 8; $i <= 20; $i += 4) {
            $odfiFedWindowDetails = OdfiFedWindow::create([
                    'OdfiId' => $odfiDetailsCBOC['OdfiId'],
                    'FedStartTiming' => date($i . ':00:00'),
                    'FedEndTiming' => date($i . ':00:00'),
                    'Type' => 1,
                    'Status' => 1,
                    'Etag' => time()
            ]);

            $odfiFedWindowDetails = OdfiFedWindow::create([
                    'OdfiId' => $odfiDetailsCBOC['OdfiId'],
                    'FedStartTiming' => date($i . ':00:00'),
                    'FedEndTiming' => date($i . ':00:00'),
                    'Type' => 2,
                    'Status' => 1,
                    'Etag' => time()
            ]);
        }

        $odfiDetailsCO = Odfi::create([
                'RoutingNumber' => '031201360',
                'Abbreviation' => 'CO',
                'Code' => 'CO',
                'SftpSendHost' => 'test',
                'SftpSendUsername' => 'test',
                'SftpSendKey' => 'testkey',
                'SftpSendPort' => 123,
                'SftpReceiveHost' => 'test',
                'SftpReceiveUsername' => 'test',
                'SftpReceiveKey' => 'testkey',
                'SftpReceivePort' => 456,
                'Status' => 1,
                'Etag' => time()
        ]);

        for ($i = 8; $i <= 20; $i += 4) {
            $odfiFedWindowDetails = OdfiFedWindow::create([
                    'OdfiId' => $odfiDetailsCO['OdfiId'],
                    'FedStartTiming' => date($i . ':00:00'),
                    'FedEndTiming' => date($i . ':00:00'),
                    'Type' => 1,
                    'Status' => 1,
                    'Etag' => time()
            ]);

            $odfiFedWindowDetails = OdfiFedWindow::create([
                    'OdfiId' => $odfiDetailsCO['OdfiId'],
                    'FedStartTiming' => date($i . ':00:00'),
                    'FedEndTiming' => date($i . ':00:00'),
                    'Type' => 2,
                    'Status' => 1,
                    'Etag' => time()
            ]);
        }
    }
}
