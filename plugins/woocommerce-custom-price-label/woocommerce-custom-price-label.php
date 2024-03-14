<?php
/*
Plugin Name: Custom Price Labels for WooCommerce
Plugin URI: https://wpwham.com/products/custom-price-labels-for-woocommerce/
Description: Create any custom price label for any WooCommerce product.
Version: 2.5.12
Author: WP Wham
Author URI: https://wpwham.com
Text Domain: woocommerce-custom-price-label
Domain Path: /langs
WC requires at least: 3.0
WC tested up to: 7.8
Copyright: © 2018-2023 WP Wham. All rights reserved.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) return;

if ( 'woocommerce-custom-price-label.php' === basename( __FILE__ ) ) {
	// Check if Pro is active, if so then return
	$plugin = 'woocommerce-custom-price-label-pro/woocommerce-custom-price-label-pro.php';
	if (
		in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
		( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
	) return;
}

if ( ! class_exists( 'Woocommerce_Custom_Price_Label' ) ) :

/**
 * Main Woocommerce_Custom_Price_Label Class
 *
 * @class   Woocommerce_Custom_Price_Label
 * @version 2.5.12
 */
final class Woocommerce_Custom_Price_Label {

	public $settings = null;
	
	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 2.1.1
	 */
	public $version = '2.5.12';

	/**
	 * @var Woocommerce_Custom_Price_Label The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Woocommerce_Custom_Price_Label Instance
	 *
	 * Ensures only one instance of Woocommerce_Custom_Price_Label is loaded or can be loaded.
	 *
	 * @version 2.2.0
	 * @static
	 * @return  Woocommerce_Custom_Price_Label - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Woocommerce_Custom_Price_Label Constructor.
	 *
	 * @version 2.5.7
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'woocommerce-custom-price-label', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
			// Settings
			require_once( 'includes/admin/class-wc-custom-price-label-settings-section.php' );
			$this->settings = array();
			$this->settings['general'] = require_once( 'includes/admin/class-wc-custom-price-label-settings-general.php' );
			$this->settings['global']  = require_once( 'includes/admin/class-wc-custom-price-label-settings-global.php' );
			$this->settings['local']   = require_once( 'includes/admin/class-wc-custom-price-label-settings-local.php' );
			add_action( 'woocommerce_system_status_report', array( $this, 'add_settings_to_status_report' ) );
			if ( get_option( 'alg_wc_custom_price_label_version', '' ) !== $this->version ) {
				add_action( 'admin_init', array( $this, 'version_updated' ) );
			}
			// Per product settings
			require_once( 'includes/admin/class-wc-custom-price-label-settings-per-product.php' );
			// Bulk editor tool
			require_once( 'includes/admin/class-wc-custom-price-label-bulk-editor-tool.php' );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 2.5.11
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=custom_price_label' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'woocommerce-custom-price-label.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="' . esc_url( 'https://wpwham.com/products/custom-price-labels-for-woocommerce/?utm_source=plugins_page&utm_campaign=free&utm_medium=custom_price_label' ) . '">' .
				__( 'Unlock all', 'woocommerce-custom-price-label' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * add settings to WC status report
	 *
	 * @version 2.5.7
	 * @since   2.5.7
	 * @author  WP Wham
	 */
	public static function add_settings_to_status_report() {
		#region add_settings_to_status_report
		$protected_settings = array( 'wpwham_custom_price_label_license' );
		$settings_general   = WC_Custom_Price_Label_Settings_General::get_section_settings();
		$settings_global    = WC_Custom_Price_Label_Settings_Global::get_section_settings();
		$settings_local     = WC_Custom_Price_Label_Settings_Local::get_section_settings();
		$settings = array_merge(
			$settings_general, $settings_global, $settings_local
		);
		?>
		<table class="wc_status_table widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Custom Price Labels Settings"><h2><?php esc_html_e( 'Custom Price Labels Settings', 'woocommerce-custom-price-label' ); ?></h2></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $settings as $setting ): ?>
				<?php 
				if ( in_array( $setting['type'], array( 'title', 'sectionend', 'alg_wc_custom_price_labels_dashboard' ) ) ) { 
					continue;
				}
				if ( isset( $setting['title'] ) ) {
					$title = $setting['title'];
				} elseif ( isset( $setting['desc'] ) ) {
					$title = $setting['desc'];
				} else {
					$title = $setting['id'];
				}
				$value = get_option( $setting['id'] ); 
				if ( in_array( $setting['id'], $protected_settings ) ) {
					$value = $value > '' ? '(set)' : 'not set';
				}
				?>
				<tr>
					<td data-export-label="<?php echo esc_attr( $title ); ?>"><?php esc_html_e( $title, 'woocommerce-custom-price-label' ); ?>:</td>
					<td class="help">&nbsp;</td>
					<td><?php echo is_array( $value ) ? print_r( $value, true ) : $value; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		#endregion add_settings_to_status_report
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 2.4.3
	 */
	function includes() {
		// Functions
		require_once( 'includes/wc-custom-price-label-functions.php' );
		// Core
		require_once( 'includes/class-wc-custom-price-label.php' );
	}

	/**
	 * version_updated.
	 *
	 * @version 2.4.3
	 * @since   2.4.3
	 */
	function version_updated() {
		foreach ( $this->settings as $section ) {
			foreach ( $section->get_settings() as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}
		update_option( 'alg_wc_custom_price_label_version', $this->version );
	}

	/**
	 * Add Woocommerce settings tab to WooCommerce settings.
	 *
	 * @version 2.4.3
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'includes/admin/class-wc-settings-custom-price-label.php' );
		return $settings;
	}

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
}

endif;

if ( ! function_exists( 'WCCPL' ) ) {
	/**
	 * Returns the main instance of Woocommerce_Custom_Price_Label to prevent the need to use globals.
	 *
	 * @version 2.1.0
	 * @return  Woocommerce_Custom_Price_Label
	 */
	function WCCPL() {
		return Woocommerce_Custom_Price_Label::instance();
	}
}

WCCPL();
