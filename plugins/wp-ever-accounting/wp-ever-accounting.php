<?php
/**
 * Plugin Name: Ever Accounting
 * Plugin URI: https://wpeveraccounting.com/
 * Description: Manage your business finances right from your WordPress dashboard.
 * Version: 1.1.7
 * Author: everaccounting
 * Author URI: https://wpeveraccounting.com/
 * Requires at least: 4.7.0
 * Tested up to: 6.3
 * Text Domain: wp-ever-accounting
 * Domain Path: /languages/
 * License: GPL2+
 *
 * @package wp-ever-accounting
 */

use EverAccounting\Logger;
use EverAccounting\Options;
use EverAccounting\Utilities;

defined( 'ABSPATH' ) || exit();

/**
 * Class EverAccounting
 *
 * @since 1.0.0
 * @property-read Utilities $utils
 * @property-read Options $options
 * @property-read Logger $logger
 */
final class EverAccounting {

	/**
	 * EverAccounting version.
	 *
	 * @var string
	 */
	public $version = '1.1.7';

	/**
	 * All plugin's classes.
	 *
	 * @since 1.1.2
	 * @var array $classes all plugin's classes.
	 */
	protected $classes = array();

	/**
	 * The single instance of the class.
	 *
	 * @since 1.0.0
	 * @var EverAccounting
	 */
	protected static $instance = null;

	/**
	 * Main EverAccounting Instance.
	 *
	 * Ensures only one instance of EverAccounting is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return EverAccounting - Main instance.
	 * @see eaccounting()
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'wp-ever-accounting' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'wp-ever-accounting' ), '1.0.0' );
	}

	/**
	 * Auto-load in-accessible properties on demand.
	 *
	 * @param mixed $key Key name.
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( 'settings' === $key ) {
			$key = 'options';
		}

		if ( array_key_exists( $key, $this->classes ) ) {
			return $this->classes[ $key ];
		}

		return $this->{$key};
	}

	/**
	 * Function for add classes to $this->classes
	 * for run using eaccounting()
	 *
	 * @param string $class_name Class name.
	 * @param bool   $instance  Instance.
	 *
	 * @since 1.1.2
	 */
	public function set_class( $class_name, $instance = false ) {
		if ( empty( $this->classes[ $class_name ] ) ) {
			$this->classes[ $class_name ] = $instance ? $class_name::instance() : new $class_name();
		}
	}

	/**
	 * EverAccounting constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->define_tables();
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * define all required constants
	 *
	 * since 1.0.0
	 *
	 * @return void
	 */
	public function define_constants() {
		$upload_dir = wp_upload_dir( null, false );

		define( 'EACCOUNTING_VERSION', $this->version );
		define( 'EACCOUNTING_BASENAME', plugin_basename( __FILE__ ) );
		define( 'EACCOUNTING_PLUGIN_FILE', __FILE__ );
		define( 'EACCOUNTING_ABSPATH', dirname( EACCOUNTING_PLUGIN_FILE ) );
		define( 'EACCOUNTING_URL', plugins_url( '', EACCOUNTING_PLUGIN_FILE ) );
		define( 'EACCOUNTING_ASSETS_URL', EACCOUNTING_URL . '/assets' );
		define( 'EACCOUNTING_TEMPLATES_DIR', EACCOUNTING_ABSPATH . '/templates' );
		define( 'EACCOUNTING_LOG_DIR', $upload_dir['basedir'] . '/ea-logs/' );
	}

	/**
	 * Register custom tables within $wpdb object.
	 */
	private function define_tables() {
		global $wpdb;

		// List of tables without prefixes.
		$tables = array(
			'contactmeta' => 'ea_contactmeta',
		);

		foreach ( $tables as $name => $table ) {
			$wpdb->$name    = $wpdb->prefix . $table;
			$wpdb->tables[] = $table;
		}
	}

	/**
	 * Include all required files
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function includes() {
		require_once EACCOUNTING_ABSPATH . '/vendor/autoload.php';

		// Abstract classes.
		require_once EACCOUNTING_ABSPATH . '/includes/abstracts/abstract-registry.php';
		require_once EACCOUNTING_ABSPATH . '/includes/abstracts/abstract-assets.php';
		require_once EACCOUNTING_ABSPATH . '/includes/abstracts/abstract-background-process.php';

		// Core classes.
		require_once EACCOUNTING_ABSPATH . '/includes/class-install.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-utilities.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-options.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-ajax.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-assets.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-rewrites.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-controller.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-compatibility.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-chart.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-collection.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-datetime.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-inflector.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-logger.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-money.php';
		require_once EACCOUNTING_ABSPATH . '/includes/class-repositories.php';
		require_once EACCOUNTING_ABSPATH . '/includes/core-functions.php';

		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			require_once EACCOUNTING_ABSPATH . '/includes/admin/class-admin.php';
		}

		$this->classes['options'] = new Options();
	}

	/**
	 * When WP has loaded all plugins, trigger the `eaccounting_loaded` hook.
	 *
	 * This ensures `eaccounting_loaded` is called only after all other plugins
	 * are loaded, to avoid issues caused by plugin directory naming changing
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function on_plugins_loaded() {
		do_action( 'eaccounting_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function init_hooks() {
		register_activation_hook( EACCOUNTING_PLUGIN_FILE, array( 'EverAccounting\Install', 'install' ) );
		register_shutdown_function( array( $this, 'log_errors' ) );

		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ), - 1 );
		add_action( 'init', array( $this, 'localization_setup' ) );
		add_action( 'switch_blog', array( $this, 'wpdb_table_fix' ), 0 );
		add_action( 'init', array( $this, 'init_classes' ), 0 );
	}

	/**
	 * Ensures fatal errors are logged so they can be picked up in the status report.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function log_errors() {
		$error = error_get_last();
		if ( $error && in_array(
			$error['type'],
			array(
				E_ERROR,
				E_PARSE,
				E_COMPILE_ERROR,
				E_USER_ERROR,
				E_RECOVERABLE_ERROR,
			),
			true
		) ) {
			$this->logger->log_critical(
			/* translators: 1: error message 2: file name and path 3: line number */
				sprintf( __( '%1$s in %2$s on line %3$s', 'wp-ever-accounting' ), $error['message'], $error['file'], $error['line'] ) . PHP_EOL,
				array(
					'source' => 'fatal-errors',
				)
			);
		}
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function localization_setup() {
		$locale = ( get_locale() !== '' ) ? get_locale() : 'en_US';
		load_textdomain( 'wp-ever-accounting', WP_LANG_DIR . '/plugins/wp-ever-accounting-' . $locale . '.mo' );
		load_plugin_textdomain( 'wp-ever-accounting', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Set table names inside WPDB object.
	 *
	 * @since 1.1.0
	 * @return void
	 */
	public function wpdb_table_fix() {
		$this->define_tables();
	}

	/**
	 * Initialize plugin classes.
	 */
	public function init_classes() {
		$this->classes['utils']  = new Utilities();
		$this->classes['logger'] = new Logger();
	}

	/**
	 * Return plugin version.
	 *
	 * @since 1.2.0
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Plugin URL getter.
	 *
	 * @param string $path Path to append to the URL.
	 *
	 * @since 1.2.0
	 *
	 * @return string
	 */
	public function plugin_url( $path = '' ) {
		$url = untrailingslashit( plugins_url( '/', EACCOUNTING_PLUGIN_FILE ) );
		if ( $path && is_string( $path ) ) {
			$url  = trailingslashit( $url );
			$url .= ltrim( $path, '/' );
		}

		return $url;
	}

	/**
	 * Plugin path getter.
	 *
	 * @param string $path Path to append to the plugin path.
	 *
	 * @since 1.2.0
	 * @return string
	 */
	public function plugin_path( $path = '' ) {
		$plugin_path = untrailingslashit( plugin_dir_path( EACCOUNTING_PLUGIN_FILE ) );
		if ( $path && is_string( $path ) ) {
			$plugin_path  = trailingslashit( $plugin_path );
			$plugin_path .= ltrim( $path, '/' );
		}

		return $plugin_path;
	}

	/**
	 * Get the template path.
	 *
	 * @since 1.2.0
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'eaccounting_template_path', 'eaccounting/' );
	}

	/**
	 * Get Ajax URL.
	 *
	 * @since 1.0.2
	 * @return string
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}
}

/**
 * Returns the main instance of Plugin.
 *
 * @since  1.0.0
 * @return EverAccounting
 */
function eaccounting() {
	return EverAccounting::instance();
}

eaccounting();
