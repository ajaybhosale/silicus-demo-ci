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
 * @category Interface
 * @package  AssignGroupInterface
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\Company\Repositories\Contracts;

use Modules\Infrastructure\Interfaces\BaseInterface;

/**
 * This is an interface for AssignGroup module
 *
 * @name     AssignGroupInterface.php
 * @category Interface
 * @package  AssignGroupInterface
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
interface AssignGroupInterface extends BaseInterface
{

    /**
     * Bulk Delete AroAco table records if User is deleted from Active Directory
     *
     * @param array $strGroupId string of company id
     *
     * @name   bulkDeleteCompanyGroupByGroupId
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return obj
     */
    public function bulkDeleteCompanyGroupByGroupId($strGroupId);

    /**
     * Save a resource using bulk insert
     *
     * @param array $data array of values
     *
     * @name   bulkInsert
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function bulkInsert(array $data);
}
