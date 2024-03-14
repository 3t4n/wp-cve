<?php
/*
Plugin Name: Post Status Notifier Lite
Plugin URI: http://www.ifeelweb.de/wp-plugins/post-status-notifier/
Description: Lets you create individual notification rules to be informed about all post status transitions of your blog. Features custom email texts with many placeholders and custom post types.
Author: Timo Reith
Version: 1.11.1
Author URI: http://www.ifeelweb.de
Text Domain: psn
Requires PHP: 7.4
Requires at least: 3.3
Tested up to: 6.2.3
*/
require_once dirname(__FILE__) . '/includes/functions.php';
include_once dirname(__FILE__) . '/custom.php';

if (!class_exists('IfwPsn_Wp_Plugin_Loader')) {
    require_once IFW_PSN_LIB_ROOT . '/IfwPsn/Wp/Plugin/Loader.php';
}

IfwPsn_Wp_Plugin_Loader::load(__FILE__);