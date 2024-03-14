<?php
/**
 * Plugin Name: Delete Comments By Status
 * Plugin URI: http://microsolutionsbd.com/
 * Description: Plugin to delete all comments by selecting the status (pending, spam, trash, approved) 
 * Version: 1.5.3
 * Author: Micro Solutions Bangladesh
 * Author URI: http://microsolutionsbd.com/
 * License: GPL2
 * Text Domain: msbddelcom
 */

define( 'MSBDDELCOM_PATH', plugin_dir_path( __FILE__ ) );

require_once('functions.php');
require_once('admin/main.php');
