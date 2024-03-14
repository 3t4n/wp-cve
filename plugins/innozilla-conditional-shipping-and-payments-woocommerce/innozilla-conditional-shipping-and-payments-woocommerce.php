<?php
/*
Plugin Name: Innozilla Conditional Shipping and Payments for WooCommerce
Plugin URI: https://innozilla.com/wordpress-plugins/woocommerce-conditional-shipping-and-payments
Description: WooCommerce Extension that can restrict your shipping and payment options using conditional logic.
Author: Innozilla
Author URI: https://innozilla.com/
Text Domain: innozilla-conditional-shipping-and-payments-woocommerce
Version: 1.0.1
*/

define( 'ICSAPW_VERSION', '1.0.0' );

define( 'ICSAPW_REQUIRED_WP_VERSION', '3.0.0' );

define( 'ICSAPW_PLUGIN', __FILE__ );

define( 'ICSAPW_PLUGIN_BASENAME', plugin_basename( ICSAPW_PLUGIN ) );

define( 'ICSAPW_PLUGIN_NAME', trim( dirname( ICSAPW_PLUGIN_BASENAME ), '/' ) );

define( 'ICSAPW_PLUGIN_DIR', untrailingslashit( dirname( ICSAPW_PLUGIN ) ) );

define( 'ICSAPW_PLUGIN_URL', untrailingslashit( plugins_url( '', ICSAPW_PLUGIN ) ) );

if ( ! class_exists( 'ICSAPW_Setup' ) ) {
	require_once dirname( __FILE__ ) . '/classes/Setup.php';
	$ICSAPW_setup = new ICSAPW_Setup();
	$ICSAPW_setup->init();
}

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WC_Conditional_Shipping_Payments' ) ) :

class WC_Conditional_Shipping_Payments {

	/* Plugin version */
	const VERSION = '1.0.0';

	/* Required WC version */
	const REQ_WC_VERSION = '2.6.0';

	/* Text domain */
	const TEXT_DOMAIN = 'innozilla-conditional-shipping-and-payments-woocommerce-pro';


	/**
	 * @var WC_Conditional_Shipping_Payments - the single instance of the class.
	 *
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main WC_Conditional_Shipping_Payments Instance.
	 *
	 * Ensures only one instance of WC_Conditional_Shipping_Payments is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @static
	 * @see ICSAPW_WC_()
	 *
	 * @return WC_Conditional_Shipping_Payments - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Admin functions and filters.
	 *
	 * @var WC_CSP_Admin
	 */
	public $admin;

	/**
	 * Loaded restrictions.
	 *
	 * @var WC_CSP_Restrictions
	 */
	public $restrictions;

	/**
	 * Loaded conditions.
	 *
	 * @var WC_CSP_Conditions
	 */
	public $conditions;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
		add_action( 'plugins_loaded', array( $this, 'initialize_plugin' ) );
		add_action( 'admin_init', array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
	}

	/**
     * Load Upgrade to PRO
     * Version 1.0.1 Update
     */
    public function plugin_action_links( $links ) {
        $plugin_links = array(
            '<a href="https://innozilla.com/wordpress-plugins/woocommerce-conditional-shipping-and-payments/#pro" style="font-weight:bold; color: #48a05b;">' . __( 'Upgrade to PRO', 'woocommerce-shipping-per-product' ) . '</a>'
        );
        return array_merge( $plugin_links, $links );
    }

	/**
	 * Plugin version getter.
	 *
	 * @since  1.5.9
	 *
	 * @param  boolean  $base
	 * @param  string   $version
	 * @return string
	 */
	public function plugin_version( $base = false, $version = '' ) {

		$version = $version ? $version : self::VERSION;

		if ( $base ) {
			$version_parts = explode( '-', $version );
			$version       = sizeof( $version_parts ) > 1 ? $version_parts[ 0 ] : $version;
		}

		return $version;
	}

	/**
	 * Plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
	}

	/**
	 * Plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Indicates whether the plugin has been fully initialized.
	 *
	 * @since  1.7.6
	 *
	 * @return boolean
	 */
	public function plugin_initialized() {
		return class_exists( 'WC_CSP_Autoloader' );
	}

	/**
	 * Define constants if not present.
	 *
	 * @since  1.7.6
	 *
	 * @return boolean
	 */
	protected function maybe_define_constant( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Fire in the hole!
	 *
	 * @return void
	 */
	public function initialize_plugin() {

		$this->define_constants();

		// WC version check.
		if ( ! function_exists( 'WC' ) || version_compare( WC()->version, self::REQ_WC_VERSION ) < 0 ) {
			require_once( ICSAPW_ABSPATH . 'includes/admin/ICSAPW-class-wc-csp-admin-notices.php' );
			$notice = sprintf( __( 'Innozilla Conditional Shipping and Payments for WooCommerce requires at least WooCommerce <strong>%s</strong>.', 'woocommerce-conditional-shipping-and-payments' ), self::REQ_WC_VERSION );
			WC_CSP_Admin_Notices::add_notice( $notice, 'error' );
			return false;
		}

		$this->includes();

	}

 	/**
	 * Define constants.
	 *
	 * @return void
	 */
	public function define_constants() {
		$this->maybe_define_constant( 'WC_CSP_VERSION', self::VERSION );
		$this->maybe_define_constant( 'ICSAPW_ABSPATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
	}


	/**
	 * Includes.
	 *
	 * @since 1.4.0
	 */
	public function includes() {

		// Class autoloader.
		require_once( ICSAPW_ABSPATH . 'includes/ICSAPW-class-wc-csp-autoloader.php' );

		// Helpers.
		require_once( ICSAPW_ABSPATH . 'includes/ICSAPW-class-wc-csp-helpers.php' );

		// Global functions.
		require_once( ICSAPW_ABSPATH . 'includes/ICSAPW-wc-csp-functions.php' );

		// Compatibility.
		require_once( ICSAPW_ABSPATH . 'includes/compatibility/ICSAPW-class-wc-csp-compatibility.php' );

		// Abstract restriction class extended by the included restriction classes.
		require_once( ICSAPW_ABSPATH . 'includes/abstracts/ICSAPW-class-wc-csp-abstract-restriction.php' );

		// Restriction type interfaces implemented by the included restriction classes.
		require_once( ICSAPW_ABSPATH . 'includes/types/ICSAPW-class-wc-csp-checkout-restriction.php' );
		require_once( ICSAPW_ABSPATH . 'includes/types/ICSAPW-class-wc-csp-cart-restriction.php' );
		require_once( ICSAPW_ABSPATH . 'includes/types/ICSAPW-class-wc-csp-update-cart-restriction.php' );
		require_once( ICSAPW_ABSPATH . 'includes/types/ICSAPW-class-wc-csp-add-to-cart-restriction.php' );

		// Abstract condition classes extended by the included condition classes.
		require_once( ICSAPW_ABSPATH . 'includes/abstracts/ICSAPW-class-wc-csp-abstract-condition.php' );
		require_once( ICSAPW_ABSPATH . 'includes/abstracts/ICSAPW-class-wc-csp-abstract-package-condition.php' );

		// Admin functions and meta-boxes.
		if ( is_admin() ) {
			$this->admin_includes();
		}

		// Load declared restrictions.
		$this->restrictions = new WC_CSP_Restrictions();

		// Load restriction conditions.
		$this->conditions = new WC_CSP_Conditions();
	}

	/**
	 * Loads the Admin & AJAX filters / hooks.
	 *
	 * @return void
	 */
	public function admin_includes() {
		require_once( ICSAPW_ABSPATH . 'includes/admin/ICSAPW-class-wc-csp-admin.php' );
		$this->admin = new WC_CSP_Admin();
	}

	/**
	 * Load textdomain.
	 *
	 * @return void
	 */
	public function init_textdomain() {
		load_plugin_textdomain( 'woocommerce-conditional-shipping-and-payments', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Store extension version.
	 *
	 * @return void
	 */
	public function activate() {

		$version = get_option( 'wc_csp_version', false );

		if ( $version === false ) {

			add_option( 'wc_csp_version', self::VERSION );

			// Clear cached shipping rates.
			WC_CSP_Core_Compatibility::clear_cached_shipping_rates();

			// Add dismissible welcome notice.
			WC_CSP_Admin_Notices::add_maintenance_notice( 'welcome' );

		} elseif ( version_compare( $version, self::VERSION, '<' ) ) {

			update_option( 'wc_csp_version', self::VERSION );

			// Clear cached shipping rates.
			WC_CSP_Core_Compatibility::clear_cached_shipping_rates();
		}
	}

	/**
	 * Deactivate extension.
	 *
	 * @return void
	 */
	public function deactivate() {
		// Clear cached shipping rates.
		//WC_CSP_Core_Compatibility::clear_cached_shipping_rates();
	}
}

endif; // end class_exists check

/**
 * Returns the main instance of WC_Conditional_Shipping_Payments to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Innozilla Conditional Shipping and Payments for WooCommerce
 */
function ICSAPW_WC_() {
	return WC_Conditional_Shipping_Payments::instance();
}

// Launch the whole plugin.
$GLOBALS[ 'woocommerce_conditional_shipping_and_payments' ] = ICSAPW_WC_();