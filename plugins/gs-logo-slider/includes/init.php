<?php
namespace GSLOGO;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('plugins_loaded', function() {
    
    /**
     * Compatibility check with Pro plugin
     */
    if ( is_pro_compatible() ) {
        /**
         * Activation redirects
         */
        register_activation_hook( GSL_PLUGIN_FILE, 'GSLOGO\on_activation' );

        /**
         * Init Appsero
         */
        gs_appsero_init();

        /**
         * Load Main Plugin
         */
        require_once GSL_PLUGIN_DIR . 'includes/plugin.php';
    }
    
    /**
     * Remove Reviews Metadata on plugin Deactivation.
     */
    register_deactivation_hook( GSL_PLUGIN_FILE, 'GSLOGO\on_deactivation' );
    
    /**
     * Plugins action links
     */
    add_filter( 'plugin_action_links_' . plugin_basename( GSL_PLUGIN_FILE ), 'GSLOGO\add_pro_link' );
    
    /**
     * Plugins Load Text Domain
     */
    add_action( 'init', 'GSLOGO\gs_load_textdomain' );

}, -20 );