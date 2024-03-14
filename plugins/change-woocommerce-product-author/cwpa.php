<?php
/*
* Plugin Name: Change WooCommerce Authorship - Migrate WC Product Author Ownership
* Plugin URI: https://www.fahimm.com/how-to-change-woocommerce-product-ownership/
* Description: Easily you can Change WooCommerce Product Author Ownership. You can transfer WooCommerce product author. WooCommerce product author transfer with this plugin.
* Version: 1.0.7
* Author: Fahim Murshed
* Author URI: https://fahimm.com
* License: GNU/GPL V2 or Later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

	function function_add_author_cwpa() {
		add_post_type_support( 'product', 'author' );
	}
	add_action('init', 'function_add_author_cwpa', 999 );