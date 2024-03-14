<?php
/**
 * PHP prior to 5.5, or WooCommerce not found / old release
 *
 * @package Fish and Ships
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;


if ( !function_exists('woocommerce_fish_n_ships_no_wc') ) {
	function woocommerce_fish_n_ships_no_wc() {
		
		echo '<div class="error">';

		if (version_compare( phpversion(), '5.5', '<') ) {
			echo '<p><strong>WC Fish and Ships Plugin</strong>: PHP 5.5 or newer required. Currently installed: ' . phpversion() . '</p>';
		}

		if (!function_exists('WC')) {
			echo '<p><strong>WC Fish and Ships Plugin</strong>: WooCommerce plugin not detected. It needs WooCommerce 2.6.0 or newer.</p>';
		} else {
			echo '<p><strong>WC Fish and Ships Plugin</strong>: WooCommerce 2.6.0 or newer required. Currently installed: ' .  WC()->version . '</p>';
		}
		echo '</div>';
	}
	add_action('admin_notices', 'woocommerce_fish_n_ships_no_wc');
}