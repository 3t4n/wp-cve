<?php

/**
 * Runs on Uninstall of sktbuilder
 *
 * @package   sktbuilder
 * @author    webark.com
 * @link      http://sktbuilder-builder.com/
 * @license   http://sktbuilder-builder.com/licenses/
 * @version   @package_version@
 */
/* if uninstall not called from WordPress exit */
if (!defined('ABSPATH') || !defined('WP_UNINSTALL_PLUGIN')) {
    exit();  // silence is golden
}

// Remove options "sktbuilder"
delete_option( 'sktbuilder_libs' );
delete_option( 'sktbuilder_version' );