<?php
/*
Plugin Name: UiCore Animate
Plugin URI: https://animate.uicore.co
Description: Animate your design in just a few clicks.
Version: 1.0.4
Author: UiCore
Author URI: https://uicore.co
License: GPL3
Text Domain: uicore-animate
Domain Path: /languages
 * Elementor requires at least: 3.8.0
 * Elementor tested up to: 3.19.4
*/
namespace UiCoreAnimate;

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Base class
 *
 * @class Base The class that holds the entire plugin
 */
final class Base {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.4';

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = array();

    /**
     * Constructor for the Base class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    public function __construct() {

        $this->define_constants();

        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
     * Initializes the Base() class
     *
     * Checks for an existing Base() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Base();
        }

        return $instance;
    }

    /**
     * Magic getter to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }

        return $this->{$prop};
    }

    /**
     * Magic isset to bypass referencing plugin.
     *
     * @param $prop
     *
     * @return mixed
     */
    public function __isset( $prop ) {
        return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
    }

    /**
     * Define the constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'UICORE_ANIMATE_VERSION', $this->version );
        define( 'UICORE_ANIMATE_FILE', __FILE__ );
        define( 'UICORE_ANIMATE_PATH', dirname( UICORE_ANIMATE_FILE ) );
        define( 'UICORE_ANIMATE_INCLUDES', UICORE_ANIMATE_PATH . '/includes' );
        define( 'UICORE_ANIMATE_URL', plugins_url( '', UICORE_ANIMATE_FILE ) );
        define( 'UICORE_ANIMATE_ASSETS', UICORE_ANIMATE_URL . '/assets' );
        define( 'UICORE_ANIMATE_BADGE', '<span title="Powerd by UiCore Animate" style="font-size:11px;font-weight:500;text-transform:uppercase;background:#5dbad8;color:black;padding:2px 5px;border-radius:3px;margin-right:4px;">Animate</span> ' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        if(\class_exists('Elementor\Plugin')){
            $this->includes();
            $this->init_hooks();
        }
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {

        $installed = get_option( 'uianim_installed' );

        if ( ! $installed ) {
            update_option( 'uianim_installed', time() );
        }

        update_option( 'uianim_version', UICORE_ANIMATE_VERSION );
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }

    /**
     * Include the required files
     *
     * @return void
     */
    public function includes() {

        require_once UICORE_ANIMATE_INCLUDES . '/class-helper.php';
        require_once UICORE_ANIMATE_INCLUDES . '/class-settings.php';
        require_once UICORE_ANIMATE_INCLUDES . '/class-assets.php';
        require_once UICORE_ANIMATE_INCLUDES . '/class-elementor.php';
        require_once UICORE_ANIMATE_INCLUDES . '/class-page-transition.php';
        require_once UICORE_ANIMATE_INCLUDES . '/class-rest-api.php';

        if ( $this->is_request( 'admin' ) ) {
            require_once UICORE_ANIMATE_INCLUDES . '/class-admin.php';
        }

        if ( $this->is_request( 'frontend' ) ) {
            require_once UICORE_ANIMATE_INCLUDES . '/class-frontend.php';
        }

    }

    /**
     * Initialize the hooks
     *
     * @return void
     */
    public function init_hooks() {

        add_action( 'init', array( $this, 'init_classes' ) );

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );
    }

    /**
     * Instantiate the required classes
     *
     * @return void
     */
    public function init_classes() {

        new REST_API();
        new Elementor();
        if ( $this->is_request( 'admin' ) ) {
            $this->container['admin'] = new Admin();
        }

        if ( $this->is_request( 'frontend' ) ) {
            $this->container['frontend'] = new Frontend();
        }
        
        $this->container['assets'] = new Assets();
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'uicore-animate', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     *
     * @return bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();

            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

} // Base

$uicore_animate = Base::init();
