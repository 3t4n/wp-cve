<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://nitin247.com/
 * @since             1.0.0
 * @package           Wc_Product_Author
 *
 * @wordpress-plugin
 * Plugin Name:       Product Author for WooCommerce
 * Plugin URI:        https://nitin247.com/plugin/wc-product-author/
 * Description:       Product Author for WooCommerce enables author functionality for Woocommerce Products, Author can be assigned to Woocommerce Product using this plugin.
 * Version:           1.0.5
 * Author:            Nitin Prakash
 * Author URI:        https://nitin247.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-product-author
 * Domain Path:       /languages
 * Requires PHP:      7.4
 * Requires at least: 6.0
 * Tested up to: 6.4
 * WC requires at least: 8.0
 * WC tested up to: 8.6
 */

// Exit if accessed directly

if ( ! defined( 'WP_FS__ENABLE_GARBAGE_COLLECTOR' ) ) {
    define( 'WP_FS__ENABLE_GARBAGE_COLLECTOR', true );
}

if ( ! function_exists( 'wcpa_fs' ) ) {
	// Create a helper function for easy SDK access.
	function wcpa_fs() {
		global $wcpa_fs;

		if ( ! isset( $wcpa_fs ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/freemius/start.php';

			$wcpa_fs = fs_dynamic_init(
				array(
					'id'             => '4032',
					'slug'           => 'wc-product-author',
					'type'           => 'plugin',
					'public_key'     => 'pk_e7499d637a3698cec8a6594707be7',
					'is_premium'     => false,
					'has_addons'     => false,
					'has_paid_plans' => false,
					'menu'           => array(
						'first-path' => 'plugins.php',
						'account'    => false,
					),
				)
			);
		}

		return $wcpa_fs;
	}

	// Init Freemius.
	wcpa_fs();
	// Signal that SDK was initiated.
	do_action( 'wcpa_fs_loaded' );
}

defined( 'WCPA_FS_PLUGIN_VERSION' ) or define( 'WCPA_FS_PLUGIN_VERSION', '1.0.5' );
defined( 'WCPA_FS_TEXT_DOMAIN' ) or define( 'WCPA_FS_TEXT_DOMAIN', 'wc-product-author' );
defined( 'WCPA_FS_SETTINGS_FIELD' ) or define( 'WCPA_FS_SETTINGS_FIELD', 'wcpa_fs_settings' );

class Product_Author_WooCommerce {

	/**
	 * Default instance for class
	 *
	 * @var object instance
	 */
	private static $instance;

	/**
	 * Default Ssettings
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Get Instance
	 *
	 * @since 1.0.5
	 *
	 * @return object initialized object of class.
	 *
	 * Initialize class instance
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
				self::$instance = new self();
		}
		return self::$instance;

	}

	/**
	 * Function Construct
	 *
	 * @since 1.0.5
	 *
	 * @return void.
	 *
	 * Initialize instructor for class
	 */
	public function __construct() {

		load_plugin_textdomain( WCPA_FS_TEXT_DOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );

		if ( $this->run_check() ) {

			$this->default_settings();

			add_action( 'admin_menu', array( $this, 'add_author_menu_page' ), 100 );
			add_action( 'admin_init', array( $this, 'register_author_settings' ) );

			add_action( 'before_woocommerce_init', array( $this, 'declare_compatibility' ) );
			add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

			if ( 'yes' === $this->get_settings( 'enable_author_front' ) ) {
				add_action( 'woocommerce_single_product_summary', array( $this, 'show_author' ), 10, 3 );
			}

			// Add author support for product post type
			$this->post_type_support();
		}

	}

	/**
	 * Function Declare Compatibility
	 *
	 * @since 1.0.5
	 *
	 * @return void.
	 *
	 * Declare compatibilities with WooCommerce
	 */
	public function declare_compatibility() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'analytics', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'new_navigation', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}

	/**
	 * Function Run Check
	 *
	 * @since 1.0.5
	 *
	 * @return bool.
	 *
	 * Check dependencies before running plugin
	 */
	public function run_check() {
		if ( ! $this->is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action( 'admin_notices', array( $this, 'wc_not_active_notice' ) );
			return false;
		}

		return true;
	}

	/**
	 * Function Add Author Menu Page
	 *
	 * @since 1.0.5
	 *
	 * @return void.
	 *
	 * Create admin menu page
	 */
	public function add_author_menu_page() {
		add_submenu_page(
			'woocommerce',
			__( 'Product Author', WCPA_FS_TEXT_DOMAIN ),
			__( 'Product Author', WCPA_FS_TEXT_DOMAIN ),
			'manage_woocommerce',
			'product-authors',
			array( $this, 'author_admin_page_callback' )
		);
	}

	/**
	 * Function Register Author Settings
	 *
	 * @since 1.0.5
	 *
	 * @return void.
	 *
	 * Register Admin Page settings
	 */
	public function register_author_settings() {

		add_settings_section(
			'wcpa_settings_section',
			__( 'Product Author Settings', WCPA_FS_TEXT_DOMAIN ),
			array( $this, 'settings_section_callback' ),
			'wcpa_settings_page'
		);

		add_settings_field(
			'enable_author_front',
			'Enable Author in Front',
			array( $this, 'enable_author_front_callback' ),
			'wcpa_settings_page',
			'wcpa_settings_section'
		);

		register_setting( 'wcpa_settings_page', WCPA_FS_SETTINGS_FIELD . '[enable_author_feature]', 'sanitize_text_field' );
	}

	/**
	 * Function Settings Section Callback
	 *
	 * @since 1.0.5
	 *
	 * @return void.
	 *
	 * Callback function for settings section
	 */
	public function settings_section_callback() {
		echo esc_html__( 'Customize the settings for Product Author', WCPA_FS_TEXT_DOMAIN );
	}

	/**
	 * Function Enable Author Front Callback
	 *
	 * @since 1.0.5
	 *
	 * @return void.
	 *
	 * Callback for author front callback
	 */
	public function enable_author_front_callback() {
		$enable_author_feature = esc_html( $this->get_settings( 'enable_author_front' ) );

		echo 'Yes <input type="radio" name="' . esc_attr( WCPA_FS_SETTINGS_FIELD ) . '[enable_author_front]" value="yes" ' . checked( 'yes', $enable_author_feature, false ) . ' />';
		echo ' No <input type="radio" name="' . esc_attr( WCPA_FS_SETTINGS_FIELD ) . '[enable_author_front]" value="no" ' . checked( 'no', $enable_author_feature, false ) . ' />';
	}

	/**
	 * Function Author Admin Page Callback
	 *
	 * @since 1.0.5
	 *
	 * @return void.
	 *
	 * Callback for author admin page callback
	 */
	public function author_admin_page_callback() {
		if ( isset( $_POST['author_form_submitted'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			// Handle saving author information if form is submitted.
			$posted_data = $_POST; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			$this->save_setting( $posted_data[ WCPA_FS_SETTINGS_FIELD ] );
		}

		// Display the admin page content with the form.
		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'Product Author', WCPA_FS_TEXT_DOMAIN ) . '</h1>';
		echo '<form method="post">';
		wp_nonce_field( 'wcpa_settings_page', WCPA_FS_SETTINGS_FIELD );
		settings_fields( 'wcpa_settings_group' );
		do_settings_sections( 'wcpa_settings_page' );
		submit_button( esc_html__( 'Save Changes', WCPA_FS_TEXT_DOMAIN ) );
		echo '<input type="hidden" name="author_form_submitted" value="1" />';
		echo '</form>';

		echo '</div>';
	}

	/**
	 * Function Save Setting
	 *
	 * @since 1.0.5
	 *
	 * @param array $posted_array
	 *
	 * @return void.
	 *
	 * Save Admin backend settings
	 */
	private function save_setting( $posted_data = array() ) {

		$default_settings = $this->get_settings( '', true );
		$posted_data      = empty( $posted_data ) ? array() : $this->sanitize_clean( $posted_data );
		$settings         = wp_parse_args( $posted_data, $default_settings );

		update_option( WCPA_FS_SETTINGS_FIELD, ( $settings ) );
	}

	/**
	 * Function Sanitize Clean
	 *
	 * @since 1.0.5
	 *
	 * @param array $posted_data
	 *
	 * @return array $data sanitized array of input data.
	 *
	 * Sanitize function to clean inputs loop
	 */
	private function sanitize_clean( $posted_data ) {
		$data = array();
		if ( is_array( $posted_data ) ) {
			foreach ( $posted_data as $post_key => $post_data ) {
				$data[ $post_key ] = sanitize_text_field( $post_data );
			}
			return $data;
		} else {
			return sanitize_text_field( $data );
		}
	}

	/**
	 * Function Is Plugin Active
	 *
	 * @since 1.0.5
	 *
	 * @param string $plugin_slug
	 *
	 * @return bool.
	 *
	 * Check if plugin is active
	 */
	public function is_plugin_active( $plugin_slug ) {
		if ( ! in_array( $plugin_slug, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Function WC Not Active Notice
	 *
	 * @since 1.0.5
	 * @return void.
	 *
	 * Admin notice if WooCommerce not Active
	 */
	public function wc_not_active_notice() {
		echo '<div class="notice notice-error">';
		echo  '<p>' . esc_html__( 'Product Author for WooCommerce requires active WooCommerce Installation!', WCPA_FS_TEXT_DOMAIN ) . '</p>';
		echo '</div>';
	}

	/**
	 * Function Post Type Support
	 *
	 * @since 1.0.5
	 * @return void.
	 *
	 * Add support for product post type
	 */
	public function post_type_support() {
		add_post_type_support( 'product', 'author' );
	}

	/**
	 * Function Action Links
	 *
	 * @since 1.0.5
	 * @param array $links
	 * @return array $links.
	 *
	 * Declare compatibilities with WooCommerce
	 */
	public function action_links( $links ) {
		$links = array_merge(
			array(
				'<a target="blank" href="' . esc_url(
					admin_url( 'admin.php?page=product-authors' )
				) . '">' . __( 'Settings', WCPA_FS_TEXT_DOMAIN ) . '</a>',
				'<a target="blank" href="' . esc_url( 'https://nitin247.com/support/' ) . '">' . __( 'Plugin Support', WCPA_FS_TEXT_DOMAIN ) . '</a>',
			),
			$links
		);

		return $links;
	}

	/**
	 * Function Show Author
	 *
	 * @since 1.0.5
	 *
	 * @param string $show_label
	 * @param bool $author_label
	 * @param bool $show_permalink
	 *
	 * @return bool.
	 *
	 * Declare compatibilities with WooCommerce
	 */
	public function show_author( $show_label = '', $author_label = true, $show_permalink = true ) {
		$author_name     = get_the_author();
		$author_template = '<div class="wcpa_container">';

		if ( true === $author_label ) {
			$author_template .= '<span class="wcpa_label">' . __( 'Author : ', WCPA_FS_TEXT_DOMAIN ) . '</span>';
		}
		if ( true === $show_permalink ) {
			$author_name = get_the_author_link();
		}

		$author_template .= '<span class="wcpa_name">' . $author_name . '</span>';
		$author_template .= '</div>';

		// Show Post Author
		echo wp_kses_post( $author_template );
	}

	/**
	 * Function Default Settings
	 *
	 * @since 1.0.5
	 * @return void.
	 *
	 * Add support for product post type
	 */
	public function default_settings() {
		$this->settings = array( 'enable_author_front' => 'yes' );
	}

	/**
	 * Function Get Settings
	 *
	 * @since 1.0.5
	 *
	 * @param string $setting_name
	 * @param bool $skip_merge
	 *
	 * @return array $settings.
	 *
	 * Get settings based on parameters
	 */
	public function get_settings( $setting_name = '', $skip_merge = false ) {

		$option = get_option( WCPA_FS_SETTINGS_FIELD, array() );

		if ( true === $skip_merge ) {
			$this->settings = $option;
		} else {
			$this->settings = wp_parse_args( $option, $this->settings );
		}

		if ( ! empty( $setting_name ) && isset( $this->settings[ $setting_name ] ) ) {
			return $this->settings[ $setting_name ];
		}

		return $this->settings;
	}

	/**
	 * Function String To Bool
	 *
	 * @since 1.0.5
	 * @return bool $param.
	 *
	 * Convert string to bool
	 */
	public function string_to_bool( $param ) {
		return filter_var( $param, FILTER_VALIDATE_BOOLEAN );
	}

}

// Initialize the plugin.
function wcpa_fs_init() {
	Product_Author_WooCommerce::get_instance();
}

add_action( 'plugins_loaded', 'wcpa_fs_init' );

if ( ! function_exists( 'wcpa_fs_string_to_bool' ) ) {
	/**
	 * Function WPCA String To Bool
	 *
	 * @since 1.0.5
	 *
	 * @param mixed $param
	 * @return bool.
	 *
	 * Convert string to bool
	 */
	function wcpa_fs_string_to_bool( $param ) {
		return Product_Author_WooCommerce::get_instance()->string_to_bool( $param );
	}
}

