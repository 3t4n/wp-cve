<?php
/**
 * Plugin Name:          Kadence WooCommerce Elementor
 * Plugin URI:           https://www.kadencewp.com/product/kadence-woocommerce-elementor/
 * Description:          Elementor for Woocommerce. This plugin adds product templates to create custom product pages as well as allows for full Elementor page builder editing inside the product itself.
 * Version:              1.0.8
 * Author:               Kadence WP
 * Author URI:           https://kadencewp.com/
 * License:              GPLv2 or later
 * Text Domain:          kadence-woocommerce-elementor
 *
 * @package Kadence WooCommerce Elementor.
 */

define( 'KT_WOOELE_PATH', realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
define( 'KT_WOOELE_URL', plugin_dir_url( __FILE__ ) );
define( 'KT_WOOELE_VERSION', '1.0.8' );

require_once KT_WOOELE_PATH . 'class-kadence-woocommerce-elementor.php';
require_once KT_WOOELE_PATH . 'class-kadence-woocommerce-elementor-plugin-check.php';

/**
 * Check for woocommerce.
 */
function kadence_wooele_is_woo_active() {
	return Kadence_Woocommerce_Elementor_Plugin_Check::active_check_woo();
}
/**
 * Check for elementor.
 */
function kadence_wooele_is_ele_active() {
	return Kadence_Woocommerce_Elementor_Plugin_Check::active_check_ele();
}
