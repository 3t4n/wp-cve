<?php
/**
 * Plugin Name:       Library Bookshelves
 * Plugin URI:        https://wordpress.org/plugins/library-bookshelves/
 * Description:       Create bookshelves that link to your library catalog. Use shortcodes and to display book covers in carousels.
 * Version:           5.6
 * Requires at least: 4.6
 * Requires PHP:      5.3
 * Text Domain:       library-bookshelves
 * Domain Path:       /languages
 * Author:            Jon Lorang
 * Author URI:        https://koan-design.com
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Library_Bookshelves {
	private static $instance;
	public $settings;
	public $option_name;
	public $options;
	public $version;
	public $token;
	public $file;
	public $dir;
	public $db_version;

	public function __construct( $file = '', $version = '5.6' ) {
		$this->version = $version;
		$this->db_version = 3; // as of v5.0
		$this->token = 'library_bookshelves';
		$this->option_name = 'library_bookshelves';
		$this->options = get_option( 'library_bookshelves' );
		$this->file = $file;
		$this->dir = dirname( $this->file );

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load required files
		require_once dirname( __FILE__ ) . '/functions.php';
		require_once dirname( __FILE__ ) . '/class-bookshelves-post-type.php';
		require_once dirname( __FILE__ ) . '/class-bookshelves-settings.php';
		require_once dirname( __FILE__ ) . '/class-bookshelves-shortcode.php';
		require_once dirname( __FILE__ ) . '/bookshelves-taxonomy.php';

		// Instantiate settings
		if ( ! $this->settings ) {
			$this->settings = Bookshelves_Settings::instance( $this );
		}

		// Register (de)activation hooks
		register_activation_hook( $this->file, array( $this, 'activate' ) );
		register_deactivation_hook( $this->file, array( $this, 'deactivate' ) );

		// Upgrade routine
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		// Add cron schedule intervals
		add_filter( 'cron_schedules', 'lbs_add_cron_intervals' );
	}

	public function enqueue_styles() {
		wp_register_style( $this->token . '-frontend', plugin_dir_url( __FILE__ ) . 'css/bookshelves.css', $this->version );
		wp_enqueue_style( $this->token . '-frontend' );

		$custom_css = lbs_get_css_opts();
		wp_add_inline_style( $this->token . '-frontend', $custom_css );

		wp_register_style( $this->token . '-slick', plugin_dir_url( __FILE__ ) . 'slick/slick.css', $this->version );
		wp_enqueue_style( $this->token . '-slick' );

		wp_register_style( $this->token . '-slick-theme', plugin_dir_url( __FILE__ ) . 'slick/slick-theme.css', array(), $this->version );
		wp_enqueue_style( $this->token . '-slick-theme' );
	}

	public function enqueue_scripts() {
		wp_register_script( $this->token . '-slick', plugin_dir_url( __FILE__ ) . 'slick/slick.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->token . '-slick' );

		// Divi theme fix
		$template = get_option( "template" );
		if( $template  == "Divi" ) {
			wp_enqueue_script( $this->token . '-divifix', plugin_dir_url( __FILE__ ) . 'js/divifix.js', array( 'jquery'), $this->version, true );
		}
	}

	public function admin_enqueue_scripts( $hook = '' ) {
		wp_register_script( $this->token . '-admin', plugin_dir_url( __FILE__ ) . 'js/bookshelves.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->token . '-admin' );

		wp_register_script( $this->token . '-slick', plugin_dir_url( __FILE__ ) . 'slick/slick.min.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->token . '-slick' );

		// Enable Media Library
		wp_enqueue_media();
	}

	public function admin_enqueue_styles( $hook = '' ) {
		wp_register_style( $this->token . '-admin', plugin_dir_url( __FILE__ ) . 'css/bookshelves.css', $this->version );
		wp_enqueue_style( $this->token . '-admin' );

		$custom_css = lbs_get_css_opts();
		wp_add_inline_style( $this->token . '-admin', $custom_css );

		wp_register_style( $this->token . '-slick', plugin_dir_url( __FILE__ ) . 'slick/slick.css', $this->version );
		wp_enqueue_style( $this->token . '-slick' );

		wp_register_style( $this->token . '-slick-theme', plugin_dir_url( __FILE__ ) . 'slick/slick-theme.css', array(), $this->version );
		wp_enqueue_style( $this->token . '-slick-theme' );
	}

	public function activate() {
		$this->_log_version_number();
	}

	public function admin_init() {
		if ( false === $this->options || ! isset( $this->options['db_version'] ) || $this->options['db_version'] < $this->db_version ) {
			if ( ! is_array( $this->options ) ) {
				$this->options = array();
			}
			$current_db_version = isset( $this->options['db_version'] ) ? $this->options['db_version'] : 0;
			$this->upgrade( $current_db_version );
			$this->options['db_version'] = $this->db_version;
			update_option( $this->option_name, $this->options );
		}
	}

	public function upgrade( $current_db_version ) {
		// Upgrade database options from earlier versions
		if ( $current_db_version < 3 ) {
			// Nothing to do here now.
		}
	}

	private function _log_version_number() {
		update_option( $this->token . '_version', $this->version );
	}

	public function deactivate() {
		$this->settings->unregister_settings();
		flush_rewrite_rules();
	}

	public static function instance() {
		null === self::$instance && self::$instance = new self();
		return self::$instance;
	}
}

$library_bookshelves = Library_Bookshelves::instance( __FILE__, '5.6' );
