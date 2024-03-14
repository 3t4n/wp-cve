<?php
/**
 * Plugin Name: Accordion and Accordion Slider
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/accordion-accordion-slider/
 * Description: Accordion and Accordion Slider (Horizontal and Vertical) - Responsive and Touch enabled accordion for WordPress Website. Also work with Gutenberg shortcode block. 
 * Author: WP OnlineSupport, Essential Plugin
 * Text Domain: accordion-and-accordion-slider
 * Domain Path: /languages/
 * Version: 1.4
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/accordion-accordion-slider/
 *
 * @package WordPress
 * @author WP OnlineSupport
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! defined( 'WP_AAS_VERSION' ) ) {
	define( 'WP_AAS_VERSION', '1.4' ); // Version of plugin
}

if( ! defined( 'WP_AAS_DIR' ) ) {
    define( 'WP_AAS_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'WP_AAS_URL' ) ) {
    define( 'WP_AAS_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if( ! defined( 'WP_AAS_POST_TYPE' ) ) {
    define( 'WP_AAS_POST_TYPE', 'wpos_aac_slider' ); // Plugin post type
}

if( ! defined( 'WP_AAS_META_PREFIX' ) ) {
    define( 'WP_AAS_META_PREFIX', '_wp_aas_' ); // Plugin metabox prefix
}

if( ! defined( 'WP_AAS_PLUGIN_BUNDLE_LINK' ) ) {
    define('WP_AAS_PLUGIN_BUNDLE_LINK','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Accordion-Slider&utm_campaign=Welcome-Screen'); // Plugin link
}

if( ! defined( 'WP_AAS_PLUGIN_LINK_UNLOCK' ) ) {
    define('WP_AAS_PLUGIN_LINK_UNLOCK','https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=Accordion-Slider&utm_campaign=Features-PRO'); // Plugin link
}

if( ! defined( 'WP_AAS_PLUGIN_LINK_UPGRADE' ) ) {
    define('WP_AAS_PLUGIN_LINK_UPGRADE','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Accordion-Slider&utm_campaign=Upgrade-PRO'); // Plugin Check link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Accordion and Accordion Slider
 * @since 1.0
 */
function wp_aas_load_textdomain() {

    global $wp_version;

    // Set filter for plugin's languages directory
    $wp_aas_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
    $wp_aas_lang_dir = apply_filters( 'wp_aas_languages_directory', $wp_aas_lang_dir );

    // Traditional WordPress plugin locale filter.
    $get_locale = get_locale();

    if ( $wp_version >= 4.7 ) {
        $get_locale = get_user_locale();
    }

    // Traditional WordPress plugin locale filter
    $locale = apply_filters( 'plugin_locale',  $get_locale, 'accordion-and-accordion-slider' );
    $mofile = sprintf( '%1$s-%2$s.mo', 'accordion-and-accordion-slider', $locale );

    // Setup paths to current locale file
    $mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WP_AAS_DIR ) . '/' . $mofile;

    if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
        load_textdomain( 'accordion-and-accordion-slider', $mofile_global );
    } else { // Load the default language files
        load_plugin_textdomain( 'accordion-and-accordion-slider', false, $wp_aas_lang_dir );
    }

}
add_action('plugins_loaded', 'wp_aas_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package stack-slider-a-3d-imageslider
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wp_aas_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package stack-slider-a-3d-imageslider
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wp_aas_uninstall');

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * set default values for the plugin options.
 * 
 * @package stack-slider-a-3d-imageslider
 * @since 1.0.0
 */
function wp_aas_install() {
    
    // Register post type function
    wp_aas_register_post_type();  
   
    // IMP need to flush rules for custom registered post type
    flush_rewrite_rules();

    // Deactivate Pro version
    if( is_plugin_active('accordion-and-accordion-slider-pro/accordion-and-accordion-slider-pro.php') ){
        add_action('update_option_active_plugins', 'wp_aas_deactivate_pro_version');
    }
}

/**
 * Deactivate PRO version when FREE is going to be active
 * 
 * @since 1.0
 */
function wp_aas_deactivate_pro_version() {
    deactivate_plugins('accordion-and-accordion-slider-pro/accordion-and-accordion-slider-pro.php', true);
}

/**
 * Plugin Setup (On Deactivation)
 * 
 * Delete  plugin options.
 * 
 * @package stack-slider-a-3d-imageslider
 * @since 1.0.0
 */
function wp_aas_uninstall() {
    
    // IMP need to flush rules for custom registered post type
    flush_rewrite_rules();
}

/**
 * Display Plugin Notice
 * 
 * @since 1.0
 */
function wp_aas_plugin_admin_notice() {

    global $pagenow;

    // If not plugin screen
    if( 'plugins.php' != $pagenow ) {
        return;
    }

    $dir = plugin_dir_path( __DIR__ ) . 'accordion-and-accordion-slider-pro/accordion-and-accordion-slider-pro.php';

    if( ! file_exists( $dir ) ) {
        return;
    }

    $notice_link        = add_query_arg( array('message' => 'wp-aas-plugin-notice'), admin_url('plugins.php') );
    $notice_transient   = get_transient( 'wp_aas_install_notice' );

    // If free plugin exist
    if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
        echo '<div class="updated notice" style="position:relative;">
            <p>
                <strong>'.sprintf( __('Thank you for activating %s', 'accordion-and-accordion-slider'), 'Accordion and Accordion Slider').'</strong>.<br/>
                '.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'accordion-and-accordion-slider'), '<strong>Accordion and Accordion Slider PRO</strong>' ).'
            </p>
            <a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
        </div>';
    }
}
add_action( 'admin_notices', 'wp_aas_plugin_admin_notice');

// Functions File
require_once( WP_AAS_DIR . '/includes/wp-aas-functions.php' );

// Plugin Post Type File
require_once( WP_AAS_DIR . '/includes/wp-aas-post-types.php' );

// Script File
require_once( WP_AAS_DIR . '/includes/class-wp-aas-script.php' );

// Admin Class File
require_once( WP_AAS_DIR . '/includes/admin/class-wp-aas-admin.php' );

// Shortcode File
require_once( WP_AAS_DIR . '/includes/shortcode/wpos-aas-shortcode.php' );

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl68_load() {

    require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

    $wpos_analytics =  wpos_anylc_init_module( array(
                            'id'            => 68,
                            'file'          => plugin_basename( __FILE__ ),
                            'name'          => 'Accordion and Accordion Slider',
                            'slug'          => 'accordion-and-accordion-slider',
                            'type'          => 'plugin',
                            'menu'          => 'edit.php?post_type=wpos_aac_slider',
                            'text_domain'   => 'accordion-and-accordion-slider',
                        ));

    return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl68_load();
/* Plugin Wpos Analytics Data Ends */