<?php

/**
 * @package better_el_addons 
 * @version 1.3.9
 */
/**
 * Plugin Name: Better Elementor Addons
 * Description: Simple Elementor Addons for making Beautiful Website.
 * Plugin URI:  https://wordpress.org/plugins/better-elementor-addons/
 * Version:     1.3.9
 * Author:      ElementCamp
 * Author URI:  https://addons.elementcamp.net/
 * Text Domain: better-el-addons
 * Domain Path: /lang
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
// Ensure the free version is deactivated if premium is running

// Plugin version
define( 'BEA_VERSION', '1.3.9' );
// Plugin Root File
define( 'BEA_PLUGIN_FILE', __FILE__ );
// Plugin Folder Path
define( 'BEA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BEA_PLUGIN_SLUG', dirname( plugin_basename( __FILE__ ) ) );
// Plugin Folder URL
define( 'BEA_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
    
    /**
     * Main Elementor Hello World Class
     *
     * The init class that runs the Hello World plugin.
     * Intended To make sure that the plugin's minimum requirements are met.
     *
     * You should only modify the constants to match your plugin's needs.
     *
     * Any custom code should go inside Plugin Class in the plugin.php file.
     * @since 1.0.0
     */
    final class Better_Elementor_Elements
    {
        /**
         * Plugin Version
         *
         * @since 1.0.1
         * @var string The plugin version.
         */
        const  VERSION = '1.3.6' ;
        /**
         * Minimum Elementor Version
         *
         * @since 1.0.0
         * @var string Minimum Elementor version required to run the plugin.
         */
        const  MINIMUM_ELEMENTOR_VERSION = '3.0.0' ;
        /**
         * Minimum PHP Version
         *
         * @since 1.0.0
         * @var string Minimum PHP version required to run the plugin.
         */
        const  MINIMUM_PHP_VERSION = '5.6' ;
        /**
         * Constructor
         *
         * @since 1.0.0
         * @access public
         */
        public function __construct()
        {
            // Load translation
            add_action( 'init', array( $this, 'i18n' ) );
            // Init Plugin
            add_action( 'plugins_loaded', array( $this, 'init' ) );
        }
        
        /**
         * Load Textdomain
         *
         * Load plugin localization files.
         * Fired by `init` action hook.
         *
         * @since 1.0.0
         * @access public
         */
        public function i18n()
        {
            load_plugin_textdomain( 'better-el-addons', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
        }
        
        /**
         * Initialize the plugin
         *
         * Validates that Elementor is already loaded.
         * Checks for basic plugin requirements, if one check fail don't continue,
         * if all check have passed include the plugin class.
         *
         * Fired by `plugins_loaded` action hook.
         *
         * @since 1.0.0
         * @access public
         */
        public function init()
        {
            // Check for required PHP version
            
            if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
                return;
            }
            
            // Once we get here, We have passed all validation checks so we can safely include our plugin
            require_once 'plugin.php';
        }
        
        /**
         * Admin notice
         *
         * Warning when the site doesn't have Elementor installed or activated.
         *
         * @since 1.0.0
         * @access public
         */
        public function admin_notice_missing_main_plugin()
        {
            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
            $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor */
                esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'better-el-addons' ),
                '<strong>' . esc_html__( 'Better Elementor Elements', 'better-el-addons' ) . '</strong>',
                '<strong>' . esc_html__( 'Elementor', 'better-el-addons' ) . '</strong>'
            );
            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }
        
        /**
         * Admin notice
         *
         * Warning when the site doesn't have a minimum required Elementor version.
         *
         * @since 1.0.0
         * @access public
         */
        public function admin_notice_minimum_elementor_version()
        {
            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
            $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
                esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'better-el-addons' ),
                '<strong>' . esc_html__( 'Better Elementor Elements', 'better-el-addons' ) . '</strong>',
                '<strong>' . esc_html__( 'Elementor', 'better-el-addons' ) . '</strong>',
                self::MINIMUM_ELEMENTOR_VERSION
            );
            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }
        
        /**
         * Admin notice
         *
         * Warning when the site doesn't have a minimum required PHP version.
         *
         * @since 1.0.0
         * @access public
         */
        public function admin_notice_minimum_php_version()
        {
            if ( isset( $_GET['activate'] ) ) {
                unset( $_GET['activate'] );
            }
            $message = sprintf(
                /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
                esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'better-el-addons' ),
                '<strong>' . esc_html__( 'Elementor Hello World', 'better-el-addons' ) . '</strong>',
                '<strong>' . esc_html__( 'PHP', 'better-el-addons' ) . '</strong>',
                self::MINIMUM_PHP_VERSION
            );
            printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
        }
    
    }
    // Instantiate Better_Elementor_Elements.
    new Better_Elementor_Elements();
    function create_custom_categories( $elements_manager )
    {
        $elements_manager->add_category( 'better-category', [
            'title' => __( 'Better Elements.', 'better-el-addons' ),
            'icon'  => 'fa fa-plug',
        ] );
    }
    
    add_action( 'elementor/elements/categories_registered', 'create_custom_categories' );
    //include elementor addon
    include 'inc/elementor-addon.php';

