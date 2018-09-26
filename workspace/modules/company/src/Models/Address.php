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
 * @package  Address
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id:
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\BaseModel;

/**
 * Address Model
 *
 * @name     Address.php
 * @category Model
 * @package  Company
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class Address extends BaseModel
{

    use SoftDeletes;

    const DELETED_AT = 'DeletedAt';

    protected $keyType = 'string';
    protected $tablePrefix = 'Company';
    protected $table = 'Address';
    protected $primaryKey = 'AddressId';
    protected $perPage;
    protected $fillable = [
        'NickName',
        'AddressId',
        'AddressLine1',
        'AddressLine2',
        'City',
        'State',
        'Zip',
        'PrimaryPhone',
        'SecondaryPhone',
        'PrimaryEmail',
        'SecondaryEmail',
        'Fax',
        'Type',
        'CreatedAt',
        'Etag'
    ];
    public $timestamps = false;
    public $addressErrorCodes = [
        'id' => 'CA001',
        'nickname' => 'CA013',
        'address_line1' => 'CA002',
        'address_line2' => 'CA003',
        'city' => 'CA004',
        'state' => 'CA005',
        'zip' => 'CA006',
        'primary_phone' => 'CA007',
        'secondary_phone' => 'CA008',
        'primary_email' => 'CA009',
        'secondary_email' => 'CA010',
        'fax' => 'CA011',
        'address_type' => 'CA012'
    ];
    public $addressType = [
        'primary' => 'primary ',
        'secondary' => 'secondary '
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

        //$this->table = $this->tablePrefix . config('app.db_schema_seperator') . $this->table;
        $this->table = $this->table;
        $this->perPage = config('app.records_per_page');
    }
}
