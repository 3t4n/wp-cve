<?php

class IncreaseProductVariationLimit {

	/**
	 * @var object The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $errors
	 */
	public static $errors = array();

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ), 8 );
	}

	public function init() {

		if ( defined( 'IPVL_VERSION' ) ) {
			$this->version = IPVL_VERSION;
		} else {
			$this->version = '1.0';
		}

		$this->plugin_name = sanitize_title( IPVL_NAME );

		$this->load_dependencies();
	}

	private static function load_dependencies() {
		require_once IPVL_PLUGIN_PATH . '/includes/admin.php';
	}

	/**
	 * Check if we can activate plugin
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function check() {

		$passed = true;

		/* translators: 1: Plugin name */
		$inactive_text = '<strong>' . sprintf( __( '%s is inactive.', 'increase-product-variation-limit-for-woocommerce' ), IPVL_NAME ) . '</strong>';

		if ( version_compare( phpversion(), IPVL_MIN_PHP_VER, '<=' ) ) {
			/* translators: 1: inactive text, 2: plugin name */
			self::$errors[] = sprintf( __( '%1$s The plugin requires PHP version %2$s or newer.', 'increase-product-variation-limit-for-woocommerce' ), $inactive_text, IPVL_MIN_PHP_VER );
			$passed         = false;
		} elseif ( ! self::is_wp_version_ok() ) {
			/* translators: 1: inactive text, 2: plugin name */
			self::$errors[] = sprintf( __( '%1$s The plugin requires WordPress version %2$s or newer.', 'increase-product-variation-limit-for-woocommerce' ), $inactive_text, IPVL_MIN_WP_VER );
			$passed         = false;
		}

		return $passed;
	}

	/**
	 * Check WP version
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	protected static function is_wp_version_ok() {
		global $wp_version;
		if ( ! IPVL_MIN_WP_VER ) {
			return true;
		}
		return version_compare( $wp_version, IPVL_MIN_WP_VER, '>=' );
	}

	/**
	 * Admin notices
	 *
	 * @since 1.0.0
	 */
	public static function admin_notices() {
		if ( empty( self::$errors ) ) {
			return;
		};
		echo '<div class="notice notice-error"><p>';
		echo implode( '<br>', self::$errors ); // WPCS XSS ok.
		echo '</p></div>';
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Class Instance
	 *
	 * @static
	 * @return object instance
	 *
	 * @since  1.0.0
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

}

/**
 * Instance of plugin
 *
 * @return object
 * @since  1.0.0
 */
if ( ! function_exists( 'increase_product_variation_limit' ) ) {

	function increase_product_variation_limit() {
		return IncreaseProductVariationLimit::instance();
	}
}
