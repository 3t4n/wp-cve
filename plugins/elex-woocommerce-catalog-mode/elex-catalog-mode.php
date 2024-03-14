<?php
/*
Plugin Name: ELEX WooCommerce Catalog Mode
Plugin URI: https://elextensions.com/plugin/elex-woocommerce-catalog-mode-plugin-free/
Description:  Hide Add to Cart option. Also, turn your shop into catalog mode.
Version: 1.4.2
WC requires at least: 2.6.0
WC tested up to: 8.6
Author: ELEXtensions
Author URI: https://elextensions.com 
Developer: ELEXtensions
Developer URI: https://elextensions.com
Text Domain: elex-catmode-rolebased-price
*/

// to check wether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// for Required functions
if ( ! function_exists( 'elex_cm_is_woocommerce_active' ) ) {
	require_once( 'elex-includes/elex-functions.php' );
}
// to check woocommerce is active
if ( ! ( elex_cm_is_woocommerce_active() ) ) {
	add_action( 'admin_notices', 'elex_cm_premium_prices_woocommerce_inactive_notice' );
	return;
}

//to check if premium version is active
if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once  ABSPATH . 'wp-admin/includes/plugin.php' ;
}
$rolebased_plugins        = array(
	'elex-woocommerce-catalog-mode/elex-catalog-mode.php' => "Oops! You tried installing the premium version without deactivating the basic version. Kindly deactivate WooCommerce Catalog Mode, Wholesale & Role Based Pricing (BASIC) and then try again. For any issues, kindly contact our <a target='_blank' href='https://elextensions.com/support/'>support</a>.<br>Go back to <a href='" . esc_html( admin_url( 'plugins.php' ) ) . "'>plugins page</a>",
	'elex-catmode-rolebased-price/elex-catmode-rolebased-price.php' => "Oops! You tried installing the Basic version without deactivating the Premium version. Kindly deactivate ELEX WooCommerce Role-based Pricing Plugin & WooCommerce Catalog Mode and then try again. For any issues, kindly contact our <a target='_blank' href='https://elextensions.com/support/'>support</a>.<br>Go back to <a href='" . esc_html( admin_url( 'plugins.php' ) ) . "'>plugins page</a>",
	'catalog-mode-for-woocommerce/class-elex-role-based-catalog-mode-woocommerce.php' => "Oops! You tried installing the woocommerce version without deactivating the Basic version. Kindly deactivate ELEX WooCommerce Role-based Pricing Plugin & WooCommerce Catalog Mode and then try again. For any issues, kindly contact our <a target='_blank' href='https://elextensions.com/support/'>support</a>.<br>Go back to <a href='" . esc_html( admin_url( 'plugins.php' ) ) . "'>plugins page</a>",
);

$current_role_cat_plugin = plugin_basename( __FILE__ );

foreach ( $rolebased_plugins as $role_cat_plugin => $error_msg ) {
	if ( $current_role_cat_plugin === $role_cat_plugin ) {
		continue;
	}

	if ( is_plugin_active( $role_cat_plugin ) ) {
		deactivate_plugins( $current_role_cat_plugin );
		wp_die( wp_kses_post( $error_msg ) );
	}
}




function elex_cm_premium_prices_woocommerce_inactive_notice() {
	?>
<div id="message" class="error">
	<p>
		
	<?php	
	deactivate_plugins( plugin_basename( __FILE__ ) );
	print_r( __( '<b>WooCommerce</b> plugin must be active for <b>WooCommerce Catalog Mode, Wholesale & Role Based Pricing</b> to work. ', 'elex-catmode-rolebased-price' ) ); 
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
	?>
	</p>
</div>
<?php
}

if ( ! defined( 'ELEX_CATALOG_MODE_MAIN_URL_PATH' ) ) {
	define( 'ELEX_CATALOG_MODE_MAIN_URL_PATH', plugin_dir_url( __FILE__ ) );
}



//show message for the first installation
add_action( 'admin_notices', 'elex_cm_plugin_admin_notices' );
function elex_cm_plugin_admin_notices() {
	if ( ! get_option( 'elex_first_installation_msg' ) ) {
		/**
		 * To check plugin is active or not.
		 * 
		 * @since 1.0.0
		 */
		if ( in_array( 'elex-catmode-rolebased-price/elex-catmode-rolebased-price.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			echo "<div class='updated'><strong>ELEX WooCommerce Catalog Mode, Wholesale & Role Based Pricing</strong> is activated. Go to <a href=" . esc_url( admin_url( 'admin.php?page=wc-settings&tab=elex_catalog_mode' ) ) . '>Settings</a> to configure.</div>';
		}
		update_option( 'elex_first_installation_msg', 'true' );
	}
}

if ( ! class_exists( 'Elex_CM_Pricing_Discounts_By_User_Role_WooCommerce' ) ) {
	class Elex_CM_Pricing_Discounts_By_User_Role_WooCommerce {
		
		// initializing the class
		public function __construct() {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) , array( $this, 'elex_cm_pricing_discount_action_links' ) ); //to add settings, doc, etc options to plugins base
			add_action( 'init', array( $this, 'elex_cm_pricing_discount_admin_menu' ) ); //to add pricing discount settings options on woocommerce shop
			add_action( 'admin_menu', array( $this, 'elex_cm_pricing_discount_admin_menu_option' ) ); //to add pricing discount settings menu to main menu of woocommerce
		}
		
		// function to add settings link to plugin view
		public function elex_cm_pricing_discount_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=elex_catalog_mode' ) . '">' . __( 'Settings', 'elex-catmode-rolebased-price' ) . '</a>',
				'<a href="https://elextensions.com/documentation/#elex-woocommerce-catalog-mode" target="_blank">' . __( 'Documentation', 'elex-catmode-rolebased-price' ) . '</a>',
				'<a href="https://elextensions.com/support/" target="_blank">' . __( 'Support', 'elex-catmode-rolebased-price' ) . '</a>',
			);
			return array_merge( $plugin_links, $links );
		}
		
		// function to add menu in woocommerce
		public function elex_cm_pricing_discount_admin_menu() {
			 require_once( 'includes/elex-catalog-mode-admin.php' );
			require_once( 'includes/elex-catalog-mode-settings.php' );
		}
		
		public function elex_cm_pricing_discount_admin_menu_option() {
			global $pricing_discount_settings_page;
			$pricing_discount_settings_page = add_submenu_page( 'woocommerce', __( 'Catalog Mode', 'elex-catmode-rolebased-price' ) , __( 'Catalog Mode', 'elex-catmode-rolebased-price' ) , 'manage_woocommerce', 'admin.php?page=wc-settings&tab=elex_catalog_mode' );
		}
	}

	new Elex_CM_Pricing_Discounts_By_User_Role_WooCommerce();
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	// High performance order tables compatibility.
	add_action(
		'before_woocommerce_init',
		function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		} 
	);
	include __DIR__ . '/review_and_troubleshoot_notify/review-and-troubleshoot-notify-class.php';
	$data = get_plugin_data( __FILE__ );
	$data['name'] = $data['Name']; 
	$data['basename'] = plugin_basename( __FILE__ );
	$data['support_url'] = 'https://wordpress.org/support/plugin/elex-woocommerce-catalog-mode/';
	$data['documentation_url'] = 'https://elextensions.com/knowledge-base/set-up-elex-woocommerce-catalog-mode-wholesale-role-based-pricing/';
	$data['rating_url'] = 'https://elextensions.com/plugin/elex-woocommerce-catalog-mode-plugin-free/#reviews';
	new Elex_Review_Components( $data );
}
