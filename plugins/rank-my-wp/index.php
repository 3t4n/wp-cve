<?php
/*
 * Copyright (c) 2021, Squirrly.
 * The copyrights to the software code in this file are licensed under the (revised) BSD open source license.

 * Plugin Name: Rank My WP
 * Plugin URI: https://wordpress.org/plugins/rank-my-wp/
 * Description: A complex tool to help you Rank on Google with the best Keywords and to Monitor your progress
 * Author: Squirrly
 * Author URI: https://rankmywp.com
 * Version: 1.1.0
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: rank-my-wp
 * Domain Path: /languages
 */

if (!defined('RKMW_VERSION')) {
    /* SET THE CURRENT VERSION ABOVE AND BELOW */
    define('RKMW_VERSION', '1.1.0');

    // Call config files
    try {
        require_once(dirname(__FILE__) . '/config/config.php');

        /* important to check the PHP version */
        // inport main classes
        require_once(RKMW_CLASSES_DIR . 'ObjController.php');

        // Load helpers
        RKMW_Classes_ObjController::getClass('RKMW_Classes_Helpers_Tools');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_Helpers_Sanitize');
        // Load the Front and Block controller
        RKMW_Classes_ObjController::getClass('RKMW_Classes_FrontController');
        RKMW_Classes_ObjController::getClass('RKMW_Classes_BlockController');

        if (RKMW_Classes_Helpers_Tools::isBackedAdmin()) {
            require_once(dirname(__FILE__) . '/debug/index.php');

            if (RKMW_Classes_Helpers_Tools::isPluginInstalled('squirrly-seo/squirrly.php')) {
                add_filter('rkmw_menu_url', function ($url) {
                    return str_replace('rkmw_', 'sq_', $url);
                });
                return;
            }

            RKMW_Classes_ObjController::getClass('RKMW_Classes_FrontController')->runAdmin();


            // Hook activate, deactivate and upgrades
            register_activation_hook(__FILE__, array(RKMW_Classes_ObjController::getClass('RKMW_Classes_Helpers_Tools'), 'rkmw_activate'));
            register_deactivation_hook(__FILE__, array(RKMW_Classes_ObjController::getClass('RKMW_Classes_Helpers_Tools'), 'rkmw_deactivate'));
            add_action('upgrader_process_complete', array(RKMW_Classes_ObjController::getClass('RKMW_Classes_Helpers_Tools'), 'rkmw_upgrade'), 10, 2);
        }

    } catch (Exception $e) {
    }
}
