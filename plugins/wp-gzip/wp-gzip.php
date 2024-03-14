<?php
/*
Plugin Name: WP GZip
Plugin URI: http://wordpress.org/plugins/wordpress-gzip
Description: Simple WordPress GZip
Version: 1.0
Author: Lucas Milanez
Author URI: https://br.linkedin.com/in/milanezlucas
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
define( 'WGZ_VERSION', '1.0' );

require_once( plugin_dir_path( __FILE__ ) . '/wp-gzip-admin.php' );
require_once( plugin_dir_path( __FILE__ ) . '/wp-gzip-toolbox.php' );

$wgz = new WGZ_Admin();
