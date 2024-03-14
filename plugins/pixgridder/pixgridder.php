<?php 
/*
Plugin Name: PixGridder
Plugin URI: http://www.pixedelic.com/plugins/pixgridder
Description: A simple page composer that splits your pages/posts into grid with columns and rows
Version: 2.0.6
Author: Manuel asia | Pixedelic.com
Author URI: http://www.pixedelic.com
License: GPL2
Pro version URI: http://codecanyon.net/item/pixgridder-pro-page-grid-composer-for-wordpress/5251972
*/

define( 'PIXGRIDDER_PATH', plugin_dir_path( __FILE__ ) );
define( 'PIXGRIDDER_URL', plugin_dir_url( __FILE__ ) );

require_once( PIXGRIDDER_PATH . 'lib/functions.php' );

register_activation_hook( __FILE__, array( 'PixGridder', 'activate' ) );
register_uninstall_hook( __FILE__, array( 'PixGridder', 'uninstall' ) );

PixGridder::get_instance();