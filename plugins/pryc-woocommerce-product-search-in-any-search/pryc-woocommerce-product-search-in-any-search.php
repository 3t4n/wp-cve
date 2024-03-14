<?php
/*
 * Plugin Name: PRyC WP/WooCommerce: Product search in any search
 * Plugin URI: http://PRyC.pl
 * Description: Search WooCommerce product at default theme search field or any other...
 * Author: PRyC
 * Author URI: http://PRyC.pl
 * Version: 1.0.10
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

 
 
/* CODE: */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !function_exists("pryc_woocommerce_product_search_in_any_search") ) {
	function pryc_woocommerce_product_search_in_any_search() {
			if ( is_search() && !empty($_GET['s']) && empty($_GET['post_type'])) {
					wp_safe_redirect("/?s=" . urlencode(get_query_var('s')) . "&post_type=product");
					exit();
			}
	}
}
add_action('template_redirect', 'pryc_woocommerce_product_search_in_any_search');

/* END */

