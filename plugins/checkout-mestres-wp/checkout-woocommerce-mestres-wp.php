<?php
/**
* Plugin Name: Checkout Mestres WP
* Plugin URI: http://www.mestresdowp.com.br/
* Description: Transform the Shopping Experience with Ease and Efficiency on your WooCommerce Site
* Version: 7.3.4
* Author: Mestres do WP
* Author URI: http://www.mestresdowp.com.br
* License: GPLv3 or later
* License URI: https://www.gnu.org/licenses/gpl-3.0.html
* Text Domain: checkout-mestres-wp
* Domain Path: /languages
 */
 /*
Copyright 2021  Mestres do WP  (email : contato@mestresdowp.com.br)
*/
include("env.php");
include("backend.php");
include("frontend.php");
add_action('plugins_loaded', 'cwmp_load_textdomain');
function cwmp_load_textdomain() {
	$wpr_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wpr_lang_dir = apply_filters( 'wpr_languages_directory', $wpr_lang_dir );
	$locale = apply_filters( 'plugin_locale', get_locale(), 'checkout-mestres-wp' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'checkout-mestres-wp', $locale );
	$mofile_local  = $wpr_lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/checkout-mestres-wp/' . $mofile;
	if ( file_exists( $mofile_global ) ) {
		load_textdomain( 'checkout-mestres-wp', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) {
		load_textdomain( 'checkout-mestres-wp', $mofile_local );
	} else {
		load_plugin_textdomain( 'checkout-mestres-wp', false, $wpr_lang_dir );
	}
}