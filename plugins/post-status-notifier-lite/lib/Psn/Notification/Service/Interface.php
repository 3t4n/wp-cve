<?php
/**
 * Notification service interface
 *
 * @author      Timo Reith <timo@ifeelweb.de>
 * @version     $Id: Interface.php 852284 2014-02-05 22:42:24Z worschtebrot $
 * @copyright   Copyright (c) ifeelweb.de
 * @package     Psn_Notification
 */
interface Psn_Notification_Service_Interface
{
    public function execute(Psn_Model_Rule $rule, $post);
}
