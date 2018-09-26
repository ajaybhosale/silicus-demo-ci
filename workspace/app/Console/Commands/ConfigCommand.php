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
 * @category Auth
 * @package  Authentication
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Infrastructure\Services\Setting;

/**
 * Class create configuration into Redis
 *
 * @name     ConfigCommand
 * @category Configuration
 * @package  Configuration
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT:$Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class ConfigCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'config:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create configuration into Redis";

    /**
     * Function create the setting class object
     *
     * @name   handle
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function handle()
    {
        $appSetting = new Setting();
    }
}
