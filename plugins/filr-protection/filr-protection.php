<?php

/*
Plugin Name:	Filr
Plugin URI:		https://patrickposner.dev/plugins/filr
Description: 	Simple and minimalistic file and document library plugin.
Author: 		Patrick Posner
Version:		1.2.3.7
Text Domain:    filr
Domain Path:    /languages
*
*/
define( 'FILR_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'FILR_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'FILR_VERSION', '1.2.3.7' );
// run plugin.

if ( !function_exists( 'filr_run_plugin' ) ) {
    if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
        require __DIR__ . '/vendor/autoload.php';
    }
    // register cronjob.
    register_activation_hook( __FILE__, 'filr_init_activation' );
    /**
     *
     * Register functions on activation.
     *
     * @return void
     */
    function filr_init_activation()
    {
        if ( !wp_next_scheduled( 'check_file_acess' ) ) {
            wp_schedule_event( time(), 'hourly', 'check_file_acess' );
        }
    }
    
    add_action( 'plugins_loaded', 'filr_run_plugin' );
    /**
     * Run plugin
     *
     * @return void
     */
    function filr_run_plugin()
    {
        // load setup.
        require_once FILR_PATH . '/inc/setup.php';
        // localize.
        $textdomain_dir = plugin_basename( dirname( __FILE__ ) ) . '/languages';
        load_plugin_textdomain( 'filr', false, $textdomain_dir );
        filr\FILR_Admin::get_instance();
        filr\FILR_Meta::get_instance();
        filr\FILR_Shortcode::get_instance();
        filr\FILR_Filesystem::get_instance();
        // Normalize path.
        add_filter(
            'filr_file_directory',
            function ( $current_dir, $filr_dir, $file_id ) {
            if ( function_exists( 'wp_normalize_path' ) ) {
                return wp_normalize_path( $current_dir );
            }
            return $current_dir;
        },
            10,
            3
        );
    }

}
