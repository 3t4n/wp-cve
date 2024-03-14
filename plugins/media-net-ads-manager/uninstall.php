<?php

/**
 * Called when the plugin is uninstalled
 * 
 */

defined( 'WP_UNINSTALL_PLUGIN' ) or die('Cannot call uninstall directly');

require_once __DIR__ . '/vendor/autoload.php';

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

Mnet\MnetAdManagerPlugin::uninstall();