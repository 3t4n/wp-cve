<?php
/*
* Plugin Name: 404 Image Redirection (Replace Broken Images)
* Description: This plugin will help to replace broken images in posts and pages with a default image.
* Version: 1.3
* Author: wp-buy
* Text Domain: broken-image-domain
* Domain Path: /languages
* Author URI: https://profiles.wordpress.org/wp-buy/#content-plugins
* License: GPL2
 */
if ( ! defined( 'ABSPATH' ) )
    exit;

if ( ! function_exists( 'broken_img_n_free_load_textdomain' ) ) {
    function broken_img_n_free_load_textdomain(){
        load_plugin_textdomain('broken-image-domain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    add_action('init', 'broken_img_n_free_load_textdomain');
}
define( 'broken_image_val_update_htaccess', 123456789101112 );
define( 'broken_image_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'broken_image_PLUGIN_URL', plugin_dir_url(__FILE__) );
require_once( broken_image_PLUGIN_DIR . '/admin/setCode.php' );
require_once( broken_image_PLUGIN_DIR . '/admin/setting.php' );
require_once( broken_image_PLUGIN_DIR . '/admin/change.php' );



if (!function_exists('BIR_install')) {
    function BIR_install()
    {
        BIR_create_table('BIR_replace_an_image');
    }
}
register_activation_hook( __FILE__, 'BIR_install' );

if (!function_exists('BIR_create_table')) {
    function BIR_create_table($create_table_name)
    {

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . $create_table_name;

        $sql = "CREATE TABLE IF NOT EXISTS  $table_name (
		`id` int(11) NOT NULL AUTO_INCREMENT,
        `old` varchar(256) NOT NULL,
        `new` int(11) NOT NULL,
        `date` datetime NOT NULL DEFAULT current_timestamp(),
        UNIQUE KEY id (id)
	) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}