<?php
/**
 * Corona plugin.
 *
 * The main plugin handler class is responsible for initializing Corona. The
 * class registers and all the components required to run the plugin.
 *
 * @package Corona
 */
namespace CoderExpert\Corona;
defined( 'ABSPATH' ) or exit;
/**
 * Other Class Import List
 */
use CoderExpert\Corona\Loader;

final class Plugin {
    /**
     * Plugin Version
     * @var string
     */
    protected static $version;
    /**
     * Plugin Name
     * @var string
     */
    protected static $plugin_name;
    /**
     * Plugin Instance
     * @var Plugin
     */
    private static $_instance = null;
    /**
     * Get a single instance of Plugin
     * @return Plugin
     */
    public static function get_instance(){
        if( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	/**
	 * Plugin constructor.
	 * Initializing Corona plugin.
     * 
	 * @access private
	 */
    private function __construct() {
		if ( defined( 'CE_CORONA_VERSION' ) ) {
			self::$version = CE_CORONA_VERSION;
		} else {
			self::$version = '0.0.1';
		}
        self::$plugin_name = 'ce-corona';
        register_activation_hook( CE_CORONA_FILE, [ __CLASS__, 'activate' ] );
        Loader::add_action( 'plugins_loaded', $this, 'text_domain' );
        Elementor::instance();
        Admin::init();
        Shortcode::init();
        self::run();
        do_action( 'ce_corona_init' );
    }
    public static function activate( $network_wide ){
        flush_rewrite_rules();
        if ( is_multisite() && $network_wide ) {
			return;
        }
        set_transient( 'corona_activation_redirect', true, MINUTE_IN_SECONDS );
    }
    public function text_domain(){
        load_plugin_textdomain( 'ce-corona', false, dirname( plugin_basename( CE_CORONA_FILE ) ) . '/languages' ); 
    }
    /**
     * Run all actions and hooks
     *
     * @return void
     */
    public static function run(){
        /**
         * Loader will run actions and filters function for the plugin.
         */
        Loader::run();
    }
    /**
     * Get Plugin Name or You can say Text Domain
     * @return string
     */
    public static function get_name(){
        return self::$plugin_name;
    }
    /**
     * Get Plugin Version
     * @return String
     */
    public static function get_version(){
        return self::$version;
    }
}