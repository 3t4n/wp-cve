<?php

/**
 * Called on plugin uninstall
 */
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

try {

    /* Call config files */
    require(dirname(__FILE__) . '/config/config.php');
    require_once(RKMW_CLASSES_DIR . 'ObjController.php');

    /* Delete the record from database */
    RKMW_Classes_ObjController::getClass('RKMW_Classes_Helpers_Tools');
    delete_option(RKMW_OPTION);

} catch (Exception $e) {
}
