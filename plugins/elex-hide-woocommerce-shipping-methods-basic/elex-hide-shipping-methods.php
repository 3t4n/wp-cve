<?php
/*
 * Plugin Name: ELEX Hide WooCommerce Shipping Methods (BASIC)
 * Plugin URI: https://elextensions.com/plugin/elex-hide-woocommerce-shipping-methods-plugin-free-version/
 * Description: Hide WooCommerce Shipping Methods based on certain conditions set. Set conditions based on Shipping Class, Order Total Weight, Certain Shipping Methods, etc.
 * Version: 1.4.1
 * Author: ELEXtensions
 * Author URI: https://elextensions.com/
 * Developer: ELEXtensions
 * Developer URI: https://elextensions.com
 * Text Domain: elex-hide-shipping-methods
 * WC requires at least: 2.6
 * WC tested up to: 8.5
 */

if (!defined('ABSPATH')) {
	exit;
}

// for Required functions
if ( ! function_exists( 'elex_hs_basic_is_woocommerce_active' ) ) {
	require_once  'elex-includes/elex-hs-basic-functions.php' ;
}
// to check woocommerce is active
if ( ! ( elex_hs_basic_is_woocommerce_active() ) ) {
	add_action( 'admin_notices', 'woocommerce_activation_notice_in_hs_basic' );
	return;
}
function get_activated_plugins() {
	$activated_plugins = (array) get_option('active_plugins');
	if (is_multisite()) {
		$activated_plugins = array_merge($activated_plugins, get_site_option('active_sitewide_plugins'), array());
	}
	return $activated_plugins;
}

function woocommerce_activation_notice_in_hs_basic() {  ?>
	<div id="message" class="error">
		<p>
			<?php echo( esc_attr_e( 'WooCommerce plugin must be active for ELEX Hide WooCommerce Shipping Methods Basic plugin to work.', 'elex-hide-shipping' ) ); ?>
		</p>
	</div>
	<?php
}
if (defined('ELEX_HIDE_SHIPPING_METHODS_PLUGIN_PATH')) {

	$hs_plugins        = array('elex-hide-shipping-methods/elex-hide-shipping-methods.php' => esc_html__('ELEX Hide WooCommerce Shipping Methods Premium version is already installed and activated. Please deactivate to activate basic or woocommerce version and then try again. For any issues, kindly contact our ', 'elex-hide-shipping'), 
	'woocommerce-shipping-hide-woocommerce-shipping-methods-plugin/elex-hide-shipping-methods.php' => esc_html__('Hide Shipping Methods for WooCommerce is already installed and activated. Please deactivate to activate basic or premium version and then try again. For any issues, kindly contact our ', 'elex-hide-shipping'));
	$current_hs_plugin = plugin_basename(__FILE__);
	foreach ($hs_plugins as $hs_plugins_key => $error_msg) {
		if ($current_hs_plugin === $hs_plugins_key) {
			continue;
		}
		if (array_key_exists($hs_plugins_key, get_activated_plugins())) {
			wp_die(wp_kses_post($error_msg) . "<a target='_blank' href='https://elextensions.com/support/'>" . esc_html__('Support', 'elex-hide-shipping') . '</a>.', '', array('back_link' => 1));
		}
		if (in_array($hs_plugins_key, get_activated_plugins())) {
			wp_die(wp_kses_post($error_msg) . "<a target='_blank' href='https://elextensions.com/support/'>" . esc_html__('Support', 'elex-hide-shipping') . '</a>.', '', array('back_link' => 1));
		}
	}
} else {
	if (!defined('ELEX_HIDE_SHIPPING_METHODS_PLUGIN_PATH')) {
		define('ELEX_HIDE_SHIPPING_METHODS_PLUGIN_PATH', plugin_dir_path(__FILE__));
	}
	if (!defined('ELEX_HIDE_SHIPPING_METHODS_TEMPLATE_PATH')) {
		define('ELEX_HIDE_SHIPPING_METHODS_TEMPLATE_PATH', ELEX_HIDE_SHIPPING_METHODS_PLUGIN_PATH . 'templates');
	}
	if (!defined('ELEX_HIDE_SHIPPING_METHODS_MAIN_URL_PATH')) {
		define('ELEX_HIDE_SHIPPING_METHODS_MAIN_URL_PATH', plugin_dir_url(__FILE__));
	}
	add_action('admin_menu', 'elex_hs_add_menu');
	function elex_hs_add_menu() {
		add_menu_page('ELEX Hide Shipping', 'ELEX Hide Shipping', 'manage_options', 'elex-hide-shipping', 'elex_hs_template_display');
	}
		//include files for admin access
	function elex_hs_template_display() {
		include_once  'includes/elex-hide-shipping-settings.php' ;
	}
	

	add_action('admin_notices', 'elex_hs_plugin_admin_notices');
	function elex_hs_plugin_admin_notices() {
		 /** 
		  * Show message for the first installation
		   * */
		if (!get_option('elex_hs_first_installation_msg')) { 
			if ( in_array('elex-hide-shipping-methods-basic/elex-hide-shipping-methods.php', get_activated_plugins() ) ) {
				$allowed_html = wp_kses_allowed_html('post');
				echo wp_kses("<div class='updated'><strong>ELEX Hide Shipping Methods</strong> is activated. Go to <a href=" . admin_url('admin.php?page=elex-hide-shipping') . '>Settings</a> to configure.</div>', $allowed_html);
			}
			update_option('elex_hs_first_installation_msg', true);
		}
	}

	add_action('init', 'elex_hs_include_file');
	function elex_hs_include_file() {
		include_once 'includes/elex-hs-ajax-functions.php';
	}
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'elex_hs_action_links');
	function elex_hs_action_links( $links) {
		$plugin_links = array('<a href="' . admin_url('admin.php?page=elex-hide-shipping') . '">' . __('Settings', 'elex-hide-shipping-methods') . '</a>', '<a href="https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-hide-woocommerce-shipping-methods-plugin/" target="_blank">' . __('Documentation', 'elex-hide-shipping-methods') . '</a>', '<a href="https://elextensions.com/support/" target="_blank">' . __('Support', 'elex-hide-shipping-methods') . '</a>', '<a href="https://elextensions.com/plugin/hide-woocommerce-shipping-methods/" target="_blank">' . __('Premium Upgrade', 'elex-hide-shipping-methods') . '</a>', );
		return array_merge($plugin_links, $links);
	}
	function elex_hs_load_plugin_textdomain() {
		load_plugin_textdomain('elex-hide-shipping-methods', false, basename(dirname(__FILE__)) . '/lang/');
	}
	add_action('plugins_loaded', 'elex_hs_load_plugin_textdomain');
} // review component
if (!function_exists('get_plugin_data')) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
require_once __DIR__ . '/review_and_troubleshoot_notify/review-and-troubleshoot-notify-class.php';
$data                      = get_plugin_data(__FILE__);
$data['name']              = $data['Name'];
$data['basename']          = plugin_basename(__FILE__);
$data['rating_url']        = 'https://elextensions.com/plugin/elex-hide-woocommerce-shipping-methods-plugin-free-version/#reviews';
$data['documentation_url'] = 'https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-hide-woocommerce-shipping-methods-plugin/';
$data['support_url']       = 'https://wordpress.org/support/plugin/elex-hide-woocommerce-shipping-methods-basic/';
new \Elex_Review_Components($data);
// High performance order tables compatibility.
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} 
);
