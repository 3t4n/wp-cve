<?php
/**
 * Plugin Name: Ultimate Custom Cursor
 * Description: This will enable nice looking custom cursor style on your WordPress site.
 * Plugin URI: https://dynamiclayers.net/ultimate-custom-cursor
 * Author: DynamicLayers
 * Author URI: https://dynamiclayers.net
 * Version: 1.0.3
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The main plugin class
 */
final class DLUCC {

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0.3';

    /**
     * Class construcotr
     */
    private function __construct() {

        $this->define_constants();
        add_action( 'plugins_loaded', [$this, 'init_plugin'] );

    }

    /**
     * Initializes a singleton instance
     *
     * @return \DLUCC
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'DLUCC_VERSION', self::version );
        define( 'DLUCC_FILE', __FILE__ );
        define( 'DLUCC_PATH', __DIR__ );
        define( 'DLUCC_URL', plugins_url( '', DLUCC_FILE ) );
        define( 'DLUCC_ASSETS', DLUCC_URL . '/assets' );
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin() {

        load_plugin_textdomain( 'dlucc' );

        $this->_includes();
        $assets = new DLUCC_ASSETS();
        if ( is_admin() ) {
            $assets->admin();
            $options = new Dlucc_Settings();
            $options->init();
        } else {
            if ( 'enable' === dlucc_get_option( 'custom_cursor', 'enable' ) ) {
                $assets->frontend();
            }

        }

    }

    /**
     * Include Files
     *
     * @return void
     */
    public function _includes() {
        require_once DLUCC_PATH . '/inc/assets.php';
        require_once DLUCC_PATH . '/inc/settings.php';
    }

}

/**
 * Initializes the main plugin
 *
 * @return \DLUCC
 */
function dlucc_init() {
    return DLUCC::init();
}

/**
 * Get Option
 *
 * @return value
 */
if ( ! function_exists( 'dlucc_get_option' ) ) {
    function dlucc_get_option( $option = '', $default = null ) {
        $options = get_option( 'dlucc-opt' );
        return ( isset( $options[$option] ) ) ? $options[$option] : $default;
    }
}

// kick-off the plugin
dlucc_init();