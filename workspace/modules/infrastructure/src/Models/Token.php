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
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Infrastructure\Models;

use App\BaseModel;

/**
 * Model class for table Aco
 *
 * @name     Token
 * @category Model
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class Token extends BaseModel
{

    protected $keyType = 'string';
    protected $tablePrefix = 'dbo';
    protected $table = 'Token';
    protected $primaryKey = 'TokenId';
    protected $fillable = [
        'Token',
        'Key',
        'Value',
        'Type'
    ];
    public $timestamps = false;

    /**
     * Default Constructor
     *
     * @param Array $attributes Attributes array
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->table = $this->tablePrefix . config('app.db_schema_seperator') . $this->table;
    }
}
