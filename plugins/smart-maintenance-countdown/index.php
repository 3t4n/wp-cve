<?php
/*
Plugin Name: Smart Maintenance & Countdown
Plugin URI: http://wordpress.org/plugins/
Description: This is a very easy and simple plugins to maintain the under maintenance and Coming soon countdown period for wordpress.
Version: 1.2
Author: GM Nazmul Hossain
Author URI: http://gmnazmul.com
License: GPL2
*/
define( 'SM_PLUGIN_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'SM_TEMPLATES_DIR',     SM_PLUGIN_DIR . trailingslashit( 'templates' ) );

$settings_data = get_option('SmartMaintenance_settings');
if($settings_data==true){
    define( 'TEMPLATE_PATH',     SM_TEMPLATES_DIR . trailingslashit( $settings_data['template'] ) );
    define( 'TEMPLATE_URL',     plugins_url('templates', __FILE__). '/'.$settings_data['template'] .'/' );
}

include_once(SM_PLUGIN_DIR.'includes/admin.php');
include_once(SM_PLUGIN_DIR.'includes/view.php');


?>