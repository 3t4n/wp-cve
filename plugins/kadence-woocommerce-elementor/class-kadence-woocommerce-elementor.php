<?php
/**
 * Main Elementor class.
 *
 * @package Kadence WooCommerce Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Kadence Woocommerce Elementor Class.
 *
 * @category class
 */
class Kadence_Woocommerce_Elementor {

	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Instance of Elementor Frontend class.
	 *
	 * @var \Elementor\Frontend()
	 */
	public static $elementor_instance;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	/**
	 * Construct function
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
	}
	/**
	 * Run on plugins load stuff
	 */
	public function on_plugins_loaded() {

		if ( ! kadence_wooele_is_woo_active() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_need_woocommerce' ) );
			return;
		}
		if ( ! kadence_wooele_is_ele_active() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_need_elementor' ) );
			return;
		}

		if ( ! defined( 'ELEMENTOR_VERSION' ) || ! is_callable( 'Elementor\Plugin::instance' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_need_elementor' ) );
			return;
		}
		self::$elementor_instance = Elementor\Plugin::instance();

		$this->includes();

		add_action( 'init', array( $this, 'on_init' ) );

		// Get translation set up.
		load_plugin_textdomain( 'kadence-woocommerce-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Add link for plugin page.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugins_page_links' ) );
	}
	/**
	 * Run on itit stuff
	 */
	public function on_init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_styles' ) );

		// Get woocommerce working in the editor.
		add_action( 'admin_action_elementor', array( $this, 'wc_fontend_includes' ), 5 );
	}
	/**
	 *  Include WC fontend.
	 */
	public function wc_fontend_includes() {
		WC()->frontend_includes();
	}
	/**
	 *  Enqueue Styles.
	 */
	public function register_scripts_styles() {
		wp_enqueue_style( 'kadence-woo-ele-templates', KT_WOOELE_URL . 'assets/css/kadence-woocommerce-elementor.css', array(), KT_WOOELE_VERSION, 'all' );
	}
	/**
	 *  Add needed files
	 */
	public function includes() {
		// Admin functions.
		require_once KT_WOOELE_PATH . 'admin/class-kadence-woocommerce-elementor-admin.php';

		// Kadence Woo Elementor Function.
		require_once KT_WOOELE_PATH . 'inc/functions.php';

		// Single Products Elementor.
		require_once KT_WOOELE_PATH . 'inc/class-kadence-single-products-elementor.php';

		// Single Products Elementor.
		require_once KT_WOOELE_PATH . 'inc/class-kadence-woocommerce-elementor-widget-control.php';

		// Load WPML Compatibility if WPML is installed and activated.
		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			require_once KT_WOOELE_PATH . 'inc/compatibility/class-kadence-woocommerce-elementor-wpml-compatibility.php';
		}
	}
	/**
	 * Add a notice about woocommerce being needed.
	 */
	public function admin_notice_need_woocommerce() {
		echo '<div class="notice notice-error is-dismissible">';
		echo '<p>' . esc_html__( 'Kadence Woocommerce Elementor requires WooCommerce to be active to work', 'kadence-woocommerce-elementor' ) . '</p>';
		echo '</div>';
	}
	/**
	 * Add a notice about elementor being needed.
	 */
	public function admin_notice_need_elementor() {
		echo '<div class="notice notice-error is-dismissible">';
		echo '<p>' . esc_html__( 'Kadence Woocommerce Elementor requires Elementor to be active to work', 'kadence-woocommerce-elementor' ) . '</p>';
		echo '</div>';
	}
	/**
	 * Add submenu under Kadence woocommerce elementor
	 *
	 * @param array $links an array of plugin links.
	 * @return array
	 */
	public function plugins_page_links( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=kadence_template_builder' ) . '">' . __( 'Settings', 'kadence-woocommerce-email-designer' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Get option for the plugin settings
	 */
	public static function get_default_single_setting() {
		$value = null;
		// Get all stored values.
		$stored = get_option( 'kt_woo_ele_single_template_default', null );
		// Check if value exists in stored values array.
		if ( ! empty( $stored ) ) {
			$value = $stored;
		}

		return apply_filters( 'kadence_woo_ele_default_single_setting', $value );
	}
}

Kadence_Woocommerce_Elementor::get_instance();
