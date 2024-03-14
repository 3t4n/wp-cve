<?php
/*
Plugin Name: Product categories search admin for woocommerce
Description: Simply add a search field for product categories in new/edit product page in admin.
Version: 1.2
Author: Andreas Sofantzis
Author URI: https://83pixel.com
Text Domain: pcswc
Domain Path: /languages
License: GPL v2 or later
*/

add_action( 'init', function() {
    load_plugin_textdomain( 'pcswc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
  } );

add_action('admin_enqueue_scripts', function() {
    
    wp_enqueue_script( 'pcswc-admin', plugin_dir_url( __FILE__ ) . '/assets/pcswc-admin.js', array('jquery'), filemtime(plugin_dir_path( __FILE__ ) . 'assets/pcswc-admin.js'), true);

    wp_localize_script( 'pcswc-admin', 'main_vars', array(
        'search' => __('Search', 'pcswc'),
    ));

}, 100);