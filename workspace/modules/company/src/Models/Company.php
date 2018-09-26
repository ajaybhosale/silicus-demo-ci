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
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;

/**
 * Company Model
 *
 * @name     Company.php
 * @category Model
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class Company extends BaseModel
{

    use SoftDeletes;

    const DELETED_AT = 'DeletedAt';

    //protected $keyType = 'string';
    protected $tablePrefix = 'Company';
    protected $table = 'Company';
    protected $primaryKey = 'CompanyId';
    protected $perPage;
    protected $fillable = [
        'CompanyId',
        'Type',
        'Name',
        'DoingBusinessAs',
        'TaxId',
        'CreatedAt',
        'Etag',
        'DeletedAt'
    ];
    protected $dates = ['DeletedAt'];
    public $timestamps = false;
    public $companyErrCodes = [
        'parent_id' => 'CO002',
        'type' => 'CO003',
        'odfi_id' => 'CO004',
        'name' => 'CO005',
        'doing_business_as' => 'CO006',
        'tax_id' => 'CO007',
        'website_url' => 'CO008',
        'pin_number' => 'CO009',
        'uuid' => 'CO010',
        'gateway_id' => '',
        'can_issue_credit' => 'CO011',
        'can_issue_credit_approved' => 'CO012',
        'can_issue_credit_active' => 'CO013',
        'has_bankstatements' => 'CO014',
        'has_driver_license' => 'CO015',
        'has_business_license' => 'CO016',
        'has_other' => 'CO017',
        'other' => 'CO018',
        'reason_for_deactivation' => 'CO019',
        'status' => 'CO020',
        'created_by' => 'CO021',
        'created_at' => ''
    ];
    public $companyType = [
        'merchant' => 'MERCHANT',
        'iso' => 'ISO'
    ];

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

        $this->perPage = $this->records_per_page; //config('app.records_per_page');
    }
}
