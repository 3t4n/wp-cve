<?php
/**
 * Plugin Name: Gosign - Readmore Toggle Text Block
 * Plugin URI: https://www.gosign.de/
 * Description: Text Block with read more toggle button
 * Author: Gosign.de
 * Author URI: https://www.gosign.de/wordpress-agentur/
 * Version: 2.0.1
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package GLSB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GosignReadMoreToggleTextBlock Class
 */
class GosignReadMoreToggleTextBlock {

    /**
     * The single class instance.
     *
     * @var $_instance
     */
    private static $_instance = null;

    /**
     * Path to the plugin directory
     *
     * @var $plugin_path
     */
    public $plugin_path;

    /**
     * URL to the plugin directory
     *
     * @var $plugin_url
     */
    public $plugin_url;

    /**
     * Plugin name
     *
     * @var $plugin_name
     */
    public $plugin_name;

    /**
     * Plugin version
     *
     * @var $plugin_version
     */
    public $plugin_version;

    /**
     * Plugin slug
     *
     * @var $plugin_slug
     */
    public $plugin_slug;

    /**
     * Plugin name sanitized
     *
     * @var $plugin_name_sanitized
     */
    public $plugin_name_sanitized;

    /**
     * GhostKit constructor.
     */
    public function __construct() {
        /* We do nothing here! */
    }

    /**
     * Main Instance
     * Ensures only one instance of this class exists in memory at any one time.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            self::$_instance->init_options();
            self::$_instance->init_hooks();
        }
        return self::$_instance;
    }

    /**
     * Init options
     */
    public function init_options() {
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );

        // load textdomain.
        load_plugin_textdomain( 'gosign-readmore-toggle-text-block', false, basename( dirname( __FILE__ ) ) . '/languages' );

    }


    /**
     * Init hooks
     */
    public function init_hooks() {
        add_action( 'admin_init', array( $this, 'admin_init' ) );

        // include blocks.
        // work only if Gutenberg available.
        if ( function_exists( 'register_block_type' ) ) {
            add_action( 'init', array( $this, 'register_scripts' ) );

            // we need to enqueue the main script earlier to let 3rd-party plugins add custom styles support.
            add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ), 9 );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_assets' ) );
        }
    }

    /**
     * Register scripts.
     */
    public function register_scripts() {

    }

    /**
     * Enqueue editor assets
     */
    public function enqueue_block_editor_assets() {
        $css_deps = array();
        $js_deps = array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-edit-post', 'wp-compose', 'underscore', 'wp-components', 'jquery' );

        // Styles.
        wp_enqueue_style(
            'gosign-readMore-block-admin-css',
            plugins_url( 'assets/admin/css/style.min.css', __FILE__ ),
            $css_deps,
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/admin/css/style.min.css' )
        );

        // Scripts.
        wp_enqueue_script(
            'gosign-readMore-block-admin-js', // Handle.
            plugins_url( 'blocks/index.min.js', __FILE__ ), // Block.build.js: We register the block here. Built with Webpack.
            $js_deps, // Dependencies, defined above.
            filemtime( plugin_dir_path( __FILE__ ) . 'blocks/index.min.js' )
        );
    }

    /**
     * Enqueue editor frontend assets
     */
    public function enqueue_block_assets() {
        $css_deps = array( );
        $js_deps = array( 'jquery' );

        // Styles.
        wp_enqueue_style(
            'gosign-readMore-block-frontend-css',
            plugins_url( 'blocks/style.min.css', __FILE__ ),
            $css_deps,
            filemtime( plugin_dir_path( __FILE__ ) . 'blocks/style.min.css' )
        );
        // Scripts.
        wp_enqueue_script(
            'gosign-readMore-block-backend-js',
            plugins_url( 'assets/js/script.min.js', __FILE__ ),
            $js_deps
        );
    }

    /**
     * Init variables
     */
    public function admin_init() {
        // get current plugin data.
        $data = get_plugin_data( __FILE__ );
        $this->plugin_name = $data['Name'];
        $this->plugin_version = $data['Version'];
        $this->plugin_slug = plugin_basename( __FILE__, '.php' );
        $this->plugin_name_sanitized = basename( __FILE__, '.php' );
    }
}

/**
 * Function works with the GosignReadMoreToggleTextBlock class instance
 *
 * @return object GosignReadMoreToggleTextBlock
 */
function gosignreadmoreblock() {
    return GosignReadMoreToggleTextBlock::instance();
}
add_action( 'plugins_loaded', 'gosignreadmoreblock' );
