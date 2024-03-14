<?php

/*
Plugin Name:       Media.net Ads Manager
Description: 			 The media.net ads manager provides an ability to place your ads.
Version:      		 2.10.13
Requires PHP:      5.4
Requires at least: 4.8
Author:            Media.net
Author URI:        http://media.net
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

defined('ABSPATH') or die('Do not execute directly');

require_once __DIR__ . '/vendor/autoload.php';

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

use Mnet\MnetAdManagerPlugin;

register_activation_hook(__FILE__, array('Mnet\MnetAdManagerPlugin', 'activate'));
register_deactivation_hook(__FILE__, array('Mnet\MnetAdManagerPlugin', 'deactivate'));


add_action('upgrader_process_complete', array('Mnet\MnetAdManagerPlugin', 'onPluginUpgrade'), 10, 2);

/** This will be called every time the plugin is loaded
 * so we'll maintain an option to check our plugin version 
 * and if we detect new version then we'll update our options
 * */
add_action('plugins_loaded', array('Mnet\MnetAdManagerPlugin', 'update'));

ob_start();

MnetAdManagerPlugin::run();
