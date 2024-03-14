<?php
/*
Plugin Name: WP Safe Mode Bootstrap File
Version: 1.0
Description: This file was generated automatically by the WP Safe Mode Plugin in order to enable loading Safe Mode properly.
Author: WP Safe Mode
Author URI: http://wp.lan/wp-admin/admin.php?page=wp-safe-mode
*/
if( file_exists(WP_PLUGIN_DIR.'/wp-safe-mode/wp-safe-mode-loader.php') ) include_once(WP_PLUGIN_DIR.'/wp-safe-mode/wp-safe-mode-loader.php');

function wp_safe_mode_loader_location(){ return array( '__DIR__' => dirname(__FILE__), '__FILE__' => __FILE__ ); }