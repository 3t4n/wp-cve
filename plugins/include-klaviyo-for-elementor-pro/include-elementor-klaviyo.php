<?php
/**
 * Plugin Name:       Include Klaviyo for Elementor pro
 * Description:       Klaviyo's list API integration for Elementor pro form
 * Version:           2.1
 * Author:            Thong Nguyen
 * Author URI:        https://nguyenminhthong.net/aboutme
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


/**
 * Activate the plugin.
 */
register_deactivation_hook( __FILE__, 'tho_ele_klav_active' );

function tho_ele_klav_active(){

}


/**
 * Deactivation hook.
 */

register_deactivation_hook( __FILE__, 'tho_ele_klav_deactive' );

function tho_ele_klav_deactive() {
    
}

/**
 * Uninstall hook.
 */
register_uninstall_hook(__FILE__, 'tho_ele_klav_uninstall');

function tho_ele_klav_uninstall(){

}



/**
 * Main Elementor Test Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Tho_Elementor_Extension {

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '2.1';

    /**
     * Minimum Elementor Version
     *
     * @since 1.0.0
     *
     * @var string Minimum Elementor version required to run the plugin.
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '5.4';

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Elementor_Tho_Extension The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return Elementor_Tho_Extension An instance of the class.
     */
    public static function instance() {

        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;

    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {

        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );

    }

    /**
     * Load Textdomain
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function i18n() {

        load_plugin_textdomain( 'tho-elementor-extension' );

    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        if( ! class_exists('\ElementorPro\Modules\Forms\Classes\Action_Base')){
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_elementor_pro' ] );
            return;
        }

        // Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
        add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_missing_main_plugin() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'tho-elementor-extension' ),
            '<strong>' . esc_html__( 'Include Klaviyo for Elementor pro', 'tho-elementor-extension' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'tho-elementor-extension' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    public function admin_notice_missing_elementor_pro() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" need "%2$s" to be installed and activated.', 'tho-elementor-extension' ),
            '<strong>' . esc_html__( 'Include Klaviyo for Elementor pro', 'tho-elementor-extension' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor Pro', 'tho-elementor-extension' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'tho-elementor-extension' ),
            '<strong>' . esc_html__( 'Include Klaviyo for Elementor pro', 'elementor-tho-extension' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'tho-elementor-extension' ) . '</strong>',
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
     *
     * @access public
     */
    public function admin_notice_minimum_php_version() {

        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'tho-elementor-extension' ),
            '<strong>' . esc_html__( 'Include Klaviyo for Elementor pro', 'tho-lementor-extension' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'tho-elementor-extension' ) . '</strong>',
             self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_widgets() {

        // Include Widget files
        require_once( __DIR__ . '/include-klaviyo.php' );

        $klaviyo_action = new Tho_klaviyo_Form_Action();

        \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $klaviyo_action->get_name(), $klaviyo_action );

        // Register widget
        //\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Tho_Widget() );

    }

    /**
     * Init Controls
     *
     * Include controls files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init_controls() {

        // Include Control files
       /* require_once( __DIR__ . '/controls/test-control.php' );

        // Register control
        \Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );*/

    }

}

Tho_Elementor_Extension::instance();
