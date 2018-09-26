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
 * @category Model
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Models;

use App\BaseModel;

/**
 * Address Model
 *
 * @name     CompanyAddress.php
 * @category Model
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class CompanyAddress extends BaseModel
{

    protected $keyType = 'string';
    protected $tablePrefix = 'Company';
    protected $table = 'CompanyAddress';
    protected $primaryKey = 'CompanyAddressId';
    protected $perPage;
    protected $fillable = [
        'AddressId',
        'CompanyId',
        'CompanyAddressId'
    ];
    public $timestamps = false;

    /**
     * Default Constructor
     *
     * @param array $attributes request array
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return $response
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        // $this->table = $this->tablePrefix . config('app.db_schema_seperator') . $this->table;
        $this->table = $this->table;
        $this->perPage = config('app.records_per_page');
    }
}
