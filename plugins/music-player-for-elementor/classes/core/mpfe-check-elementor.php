<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * Check if Elementor is loaded and it has the correct version
 *
 * The init class that runs the Hello World plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.2.0
 */
final class MPFE_Check_Elementor
{
    /**
     * Plugin Version
     *
     * @since 1.2.0
     * @var string The plugin version.
     */
    const  VERSION = '1.2.0' ;
    /**
     * Minimum Elementor Version
     *
     * @since 1.2.0
     * @var string Minimum Elementor version required to run the plugin.
     */
    const  MINIMUM_ELEMENTOR_VERSION = '2.0.0' ;
    /**
     * Minimum PHP Version
     *
     * @since 1.2.0
     * @var string Minimum PHP version required to run the plugin.
     */
    const  MINIMUM_PHP_VERSION = '7.0' ;
    /**
     * Constructor
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        // Init Plugin
        add_action( 'plugins_loaded', array( $this, 'init' ) );
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
     * @since 1.2.0
     * @access public
     */
    public function init()
    {
        // Check if Elementor installed and activated
        
        if ( !did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
            return;
        }
        
        // Check for required Elementor version
        
        if ( !version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
            return;
        }
        
        // Check for required PHP version
        
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
            return;
        }
        
        // Once we get here, We have passed all validation checks so we can safely include our plugin
        require_once MPFE_DIR_PATH . "/classes/core/load-elementor-widgets.php";
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
        $url = add_query_arg( array(
            'tab'       => 'plugin-information',
            'plugin'    => urlencode( 'elementor' ),
            'TB_iframe' => 'true',
            'width'     => '640',
            'height'    => '500',
        ), self_admin_url( 'plugin-install.php' ) );
        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated. Please install and activate the free version of "%2$s" plugin to proceed.', 'music-player-for-elementor' ),
            '<strong>' . esc_html__( 'Music Player For Elementor', 'music-player-for-elementor' ) . '</strong>',
            '<a href="' . esc_url( $url ) . '" class="thickbox">' . '<strong>' . esc_html__( 'Elementor', 'music-player-for-elementor' ) . '</strong>' . '</a>'
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
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'music-player-for-elementor' ),
            '<strong>' . esc_html__( 'Music Player For Elementor', 'music-player-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'music-player-for-elementor' ) . '</strong>',
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
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'music-player-for-elementor' ),
            '<strong>' . esc_html__( 'Music Player For Elementor', 'music-player-for-elementor' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'music-player-for-elementor' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

}
new MPFE_Check_Elementor();