<?php

/**
 * FileUploader class to set application meta data.
 *
 * @name       index.php
 * @category   ToolKit
 * @package    FileUploader
 * @author     Vivek Kale <vivek.kale@silicus.com>
 * @license    Silicus http://www.silicus.com/
 * @version    GIT: $Id: c4755e1be0447eb1558ee325aceb00816a13c727 $
 * @link       None
 * @filesource
 */
use App\Http\Controllers\UploadHandler;

error_reporting(E_ALL | E_STRICT);
$rootPath      = str_replace("public", "", $_SERVER['DOCUMENT_ROOT']);
require $rootPath . 'config/uploader.php';
require $rootPath . 'modules/Uploader/src/Controllers/UploadHandler.php';
$uploadHandler = new UploadHandler();


