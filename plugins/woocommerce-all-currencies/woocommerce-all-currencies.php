<?php
/*
Plugin Name: All Currencies for WooCommerce
Plugin URI: https://wpwham.com/products/all-currencies-for-woocommerce/
Description: Add all countries currencies and cryptocurrencies to WooCommerce.
Version: 2.4.2
Author: WP Wham
Author URI: https://wpwham.com
Text Domain: woocommerce-all-currencies
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
) {
	return;
}

if ( 'woocommerce-all-currencies.php' === basename( __FILE__ ) ) {
	// Check if Pro is active, if so then return
	$plugin = 'woocommerce-all-currencies-pro/woocommerce-all-currencies-pro.php';
	if (
		in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
		( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

if ( ! class_exists( 'Alg_WC_All_Currencies' ) ) :

/**
 * Main Alg_WC_All_Currencies Class
 *
 * @version 2.4.2
 * @class   Alg_WC_All_Currencies
 */
final class Alg_WC_All_Currencies {
	
	public $core         = null;
	public $settings     = null;
	public $value_symbol = null;
	public $value_name   = null;
	
	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 2.1.0
	 */
	public $version = '2.4.2';

	/**
	 * @var Alg_WC_All_Currencies The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_All_Currencies Instance
	 *
	 * Ensures only one instance of Alg_WC_All_Currencies is loaded or can be loaded.
	 *
	 * @static
	 * @return Alg_WC_All_Currencies - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Alg_WC_All_Currencies Constructor.
	 *
	 * @version 2.3.7
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'woocommerce-all-currencies', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages',                     array( $this, 'add_woocommerce_currencies_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
			// Settings
			require_once( 'includes/settings/settings-functions.php' );
			require_once( 'includes/settings/class-wc-currencies-settings-section.php' );
			$this->settings = array();
			$this->settings['general']       = require_once( 'includes/settings/class-wc-currencies-settings-general.php' );
			$this->settings['list']          = require_once( 'includes/settings/class-wc-currencies-settings-list.php' );
			$this->settings['list-crypto']   = require_once( 'includes/settings/class-wc-currencies-settings-list-crypto.php' );
			$this->settings['custom']        = require_once( 'includes/settings/class-wc-currencies-settings-custom.php' );
			add_action( 'woocommerce_system_status_report', array( $this, 'add_settings_to_status_report' ) );
			if ( get_option( 'alg_wc_all_currencies_version', '' ) !== $this->version ) {
				add_action( 'admin_init', array( $this, 'version_updated' ) );
			}
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 2.2.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_all_currencies' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'woocommerce-all-currencies.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="https://wpwham.com/products/all-currencies-for-woocommerce/?utm_source=plugins_page&utm_campaign=free&utm_medium=all_currencies">' . __( 'Unlock All', 'woocommerce-all-currencies' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * add settings to WC status report
	 *
	 * @version 2.3.7
	 * @since   2.3.7
	 * @author  WP Wham
	 */
	public static function add_settings_to_status_report() {
		#region add_settings_to_status_report
		$protected_settings = array( 'wpwham_all_currencies_license' );
		$settings_general   = Alg_WC_All_Currencies_Settings_General::get_settings();
		$settings_country   = Alg_WC_All_Currencies_Settings_List::get_settings();
		$settings_crypto    = Alg_WC_All_Currencies_Settings_List_Crypto::get_settings();
		$settings_custom    = Alg_WC_All_Currencies_Settings_Custom_Currencies::get_settings();
		$settings = array_merge(
			$settings_general, $settings_country, $settings_crypto, $settings_custom
		);
		?>
		<table class="wc_status_table widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="All Currencies Settings"><h2><?php esc_html_e( 'All Currencies Settings', 'woocommerce-all-currencies' ); ?></h2></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $settings as $setting ): ?>
				<?php 
				if ( in_array( $setting['type'], array( 'title', 'sectionend' ) ) ) { 
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
					<td data-export-label="<?php echo esc_attr( $title ); ?>"><?php esc_html_e( $title, 'woocommerce-all-currencies' ); ?>:</td>
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
	 * @version 2.2.0
	 */
	function includes() {
		// Currencies array
		require_once( 'includes/currencies.php' );
		// Core
		$this->core = require_once( 'includes/class-wc-currencies-core.php' );
	}

	/**
	 * version_updated.
	 *
	 * @version 2.2.1
	 * @since   2.2.0
	 */
	function version_updated() {
		// Deprecated options
		if ( version_compare( get_option( 'alg_wc_all_currencies_version', '' ), '2.2.0', '<' ) ) {
			$currencies_symbols = get_option( 'alg_wc_all_currencies_symbols', array() );
			foreach ( array_merge( alg_wcac_get_country_currencies_names(), alg_wcac_get_crypto_currencies_names() ) as $code => $name ) {
				if ( false !== ( $symbol = get_option( 'woocommerce_currencies_pro_currency_' . $code, false ) ) ) {
					$currencies_symbols[ $code ] = $symbol;
					delete_option( 'woocommerce_currencies_pro_currency_' . $code );
				}
			}
			update_option( 'alg_wc_all_currencies_symbols', $currencies_symbols );
		}
		// Finish
		update_option( 'alg_wc_all_currencies_version', $this->version );
		add_action( 'admin_notices', array( $this, 'plugin_updated_notice' ) );
	}

	/**
	 * plugin_updated_notice.
	 *
	 * @version 2.2.0
	 * @since   2.1.0
	 */
	function plugin_updated_notice() {
		if ( get_option( 'alg_wc_all_currencies_version', '' ) === $this->version ) {
			$class   = 'notice notice-success is-dismissible';
			$message = sprintf( __( '<strong>All Currencies for WooCommerce</strong> plugin successfully updated to version %s.', 'woocommerce-all-currencies' ), $this->version );
			echo sprintf( '<div class="%s"><p>%s</p></div>', $class, $message );
		}
	}

	/**
	 * Add Woocommerce Currencies settings tab to WooCommerce settings.
	 *
	 * @version 2.2.0
	 */
	function add_woocommerce_currencies_settings_tab( $settings ) {
		$settings[] = require_once( 'includes/settings/class-wc-settings-currencies.php' );
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

if ( ! function_exists( 'alg_wc_all_currencies' ) ) {
	/**
	 * Returns the main instance of Alg_WC_All_Currencies to prevent the need to use globals.
	 *
	 * @version 2.1.0
	 * @return  Alg_WC_All_Currencies
	 */
	function alg_wc_all_currencies() {
		return Alg_WC_All_Currencies::instance();
	}
}

alg_wc_all_currencies();
