<?php
/*
Plugin Name: Coupon By Roles For WooCommerce
Description: This plugin allows admin to set coupons by user roles.
Version: 0.6
Plugin URI: https://zetamatic.com
Author: zetamatic
Author URI: https://zetamatic.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

define('CBRWC_FILE', __FILE__);
define('CBRWC_PATH', plugin_dir_path(__FILE__));
define('CBRWC_BASE', plugin_basename(__FILE__));

/**
 * Plugin Localization
 */
add_action('plugins_loaded', 'coupon_by_roles_for_woocommerce_domain');

function coupon_by_roles_for_woocommerce_domain() {
	load_plugin_textdomain('coupon_by_roles_wc', false, basename( dirname( __FILE__ ) ) . '/lang' );
}

require_once dirname( __FILE__ ) . '/inc/coupon-by-roles-woocommerce.php';

new Coupon_By_Roles_WC();
