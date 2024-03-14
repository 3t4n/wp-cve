<?php
/**
 * @package .htaccess Login block
 */
/*
Plugin Name: .htaccess Login block
Plugin URI: http://wp-htaccess.hosting.guru/
Description: Simple and fast security plugin to block login hijackers using .htaccess file.
Version: 0.99
Author: Anton Aleksandrov
Author URI: http://anton.aleksandrov.eu
License: GPLv2 or later
*/

defined('ABSPATH') or die("No script kiddies please!");
define('SLBL_DIR', dirname(__FILE__));

require_once(SLBL_DIR."/class.htaccess_login_block.base.php");
require_once(SLBL_DIR."/class.htaccess_login_block.php");


add_action( 'init', array( 'htaccess_login_block', 'init' ) );

if ( is_admin() ) {
	require_once( SLBL_DIR . '/class.htaccess_login_block.admin.php' );
	add_action( 'init', array( 'htaccess_login_block_admin', 'init' ) );
}


