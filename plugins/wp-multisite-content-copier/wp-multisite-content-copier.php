<?php
/*
Plugin Name: WordPress Multisite Content Copier/Updater
Description: Copy/Update posts and pages from one site (blog) to the other sites (blogs) in your WordPress Multisite Network.
Version:     1.5.0
Author:      Obtain Infotech
Author URI:  http://www.obtaininfotech.com/
License:     GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

if ( ! function_exists( 'WMCC_deactivate_pro_version' ) ) {
    register_activation_hook( __FILE__, 'WMCC_deactivate_pro_version' );
    function WMCC_deactivate_pro_version() {
        
        deactivate_plugins( '/wp-multisite-content-copier-pro/wp-multisite-content-copier-pro.php' );
    }
}

if ( ! function_exists( 'WMCC_plugin_activation' ) ) {
    register_activation_hook( __FILE__, 'WMCC_plugin_activation' );
    function WMCC_plugin_activation() {
        
        global $wpdb;
        
        $table_name = $wpdb->base_prefix . 'wmcc_relationships';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `source_item_id` bigint(20) UNSIGNED NOT NULL,
            `source_blog_id` tinyint(4) UNSIGNED NOT NULL,
            `destination_item_id` bigint(20) UNSIGNED NOT NULL,
            `destination_blog_id` tinyint(4) UNSIGNED NOT NULL,
            `relationship_id` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            `type` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            `type_name` varchar(200) COLLATE utf8mb4_unicode_520_ci NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

if ( ! function_exists( 'WMCC_admin_include_css_and_js' ) ) {
    add_action( 'admin_enqueue_scripts', 'WMCC_admin_include_css_and_js' );
    function WMCC_admin_include_css_and_js() {
        
        wp_register_style( 'wmcc-style', plugin_dir_url( __FILE__ ) . 'assets/css/wmcc-style.css', false, '1.0.0' );
        wp_enqueue_style( 'wmcc-style' );
        
        wp_register_script( 'wmcc-script', plugin_dir_url( __FILE__ ) . 'assets/js/wmcc-script.js', array( 'jquery' ) );
        wp_localize_script( 'wmcc-script', 'wmcc_ajaxurl', array( 'ajaxurl' => admin_url( '/admin-ajax.php' ) ) );
        wp_enqueue_script( 'wmcc-script' );
    }
}

require  plugin_dir_path( __FILE__ ) . 'include/wmcc-network.php';

require  plugin_dir_path( __FILE__ ) . 'include/wmcc-functions.php';

require  plugin_dir_path( __FILE__ ) . 'include/content-copier.php';