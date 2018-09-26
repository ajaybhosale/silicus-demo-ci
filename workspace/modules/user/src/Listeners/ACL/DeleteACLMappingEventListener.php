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
 * @category Event
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT: $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
namespace Modules\User\Listeners\ACL;

use Modules\User\Events\ACL\DeleteACLMappingEvent;
use Modules\ACL\Repositories\ARORepository;
use Modules\ACL\Repositories\AROACORepository;

/**
 * Listener Class for ACL Mapping
 *
 * @name     DeleteACLMappingEventListener
 * @category Listener
 * @package  Infrastructure
 * @author   VCI <info@vericheck.net>
 * @license  Copyright 2018 VeriCheck | All Rights Reserved
 * @version  GIT $Id$
 * @link     https://www.vericheck.com/docs/{link to Phpdoc}
 */
class DeleteACLMappingEventListener
{

    private $_aroRepository;
    private $_aroAcoRepository;

    /**
     * Default constructor function
     *
     * @param Object $aroRepository    of aroRepository class
     * @param Object $aroAcoRepository of aroAcoRepository class
     *
     * @name   __construct
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return mixed
     */
    public function __construct(ARORepository $aroRepository, AROACORepository $aroAcoRepository)
    {
        $this->_aroRepository = $aroRepository;
        $this->_aroAcoRepository = $aroAcoRepository;
    }

    /**
     * Handle Event in Same class
     *
     * @param Object $event Event object
     *
     * @name   handle
     * @access public
     * @author VCI <info@vericheck.net>
     *
     * @return void
     */
    public function handle(DeleteACLMappingEvent $event)
    {

        $strUserId = $event->id;
        $strModelColoumn = 'ModelId';
        $arrAroChild = $this->_aroRepository->findAroById($strModelColoumn, $strUserId);

        if (!empty($arrAroChild)) {
            $strAroColoumn = 'AroId';
            $arrBulkDeleteUser = [];
            foreach ($arrAroChild as $arrUser) {
                $arrBulkDeleteUser[] = $arrUser["AroId"];
            }
            $aroAco = $this->_aroAcoRepository->bulkDeleteAroAcoById($strAroColoumn, $arrBulkDeleteUser);
        }
        $responseDelete = $this->_aroRepository->deleteById($strModelColoumn, $strUserId);
    }
}
