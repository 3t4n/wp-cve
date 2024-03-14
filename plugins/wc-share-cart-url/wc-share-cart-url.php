<?php

/**
 * Plugin Name: WC Share Cart URL
 * Description: Share Woocommerce Cart URL to the Customer.
 * Version: 1.1.1
 * Author: Krzysztof Piątkowski
 * Text Domain: wc-share-cart-url
 * Domain Path: /languages
 * License: GPLv2
 * Requires PHP: 5.6
 * Network: true
 */

if (!defined('ABSPATH')) {
    die("No direct access!");
}

if (!class_exists('WC_Share_Cart_Url')) {

	class WC_Share_Cart_Url
	{
		private static $session_cart_keys = array(
			'cart', 'cart_totals', 'applied_coupons', 'coupon_discount_totals', 'coupon_discount_tax_totals'
		);

		public static function init()
		{
			add_action('woocommerce_before_cart', array(__CLASS__, 'before_cart'));
			add_action('woocommerce_load_cart_from_session', array(__CLASS__, 'set_session_cart'), 1);
			add_filter('woocommerce_update_cart_action_cart_updated', array(__CLASS__, 'update_cart'), 10, 1);
			add_filter('woocommerce_cart_item_price', array(__CLASS__,'cart_item_price'), 10, 3 ); 
			add_action('woocommerce_before_calculate_totals', array(__CLASS__, 'before_calculate_totals'), 10, 1);
		}

		public static function get_session_cart()
		{
			$cart_session = array();

			foreach(self::$session_cart_keys as $key) {
				$cart_session[$key] = WC()->session->get( $key );
			}

			return serialize($cart_session);
		}

		public static function set_session_cart()
		{
			if( isset($_REQUEST['share']) ) {

				$hash = sanitize_file_name($_REQUEST['share']);
				$file = get_temp_dir() . $hash;

				if (file_exists($file)) {

					$cart = unserialize( file_get_contents($file) );

					foreach(self::$session_cart_keys as $key) {
						WC()->session->set( $key, $cart[$key] );
					}
				}
			}
		}

		public static function before_cart()
		{
			if (current_user_can('manage_woocommerce')) {

				if (isset($_POST['wc_cart_share'])) {

					$session_cart = self::get_session_cart();
					$hash = wp_hash( $session_cart );

					file_put_contents( get_temp_dir() . $hash, $session_cart);

					echo '<h2>Share link:</h2>';
					echo '<pre>';

					echo esc_html( wc_get_cart_url() ) . '?share=';
					echo esc_html( $hash );

					echo '</pre>';

				} else {
				?>
				<form method="post">
					<button type="submit" name="wc_cart_share"><?php _e('Share this cart', 'wc-share-cart-url'); ?></button>
				</form>
				<?php
				}
			}
		}

		public static function update_cart($cart_updated)
		{
			if(isset($_REQUEST['cart']) && current_user_can('manage_woocommerce')) {
				$cart = WC()->cart->get_cart();
				foreach($_REQUEST['cart'] as $key => $data) {
					if(isset($data['custom_price']) && isset($cart[$key])) {
						$cart[$key]['wcscu_price'] = $data['custom_price'];
					}
				}
				WC()->cart->set_cart_contents($cart);
			}

			return $cart_updated;
		}

		public static function cart_item_price($wc, $cart_item, $cart_item_key)
		{
			if(current_user_can('manage_woocommerce')) {
				$_product = wc_get_product($cart_item['product_id']);

				$wc .= '<input type="number" size="4" class="input-text text" min="0" step="0.01" ';
				$wc .= 'name="cart[' . $cart_item_key .'][custom_price]" ';
				$wc .= 'value="' . $cart_item['data']->get_price() . '" />';
			}
			return $wc;
		}

	    public static function before_calculate_totals($cart)
	    {
	        /*if (defined('DOING_AJAX') && DOING_AJAX) {
	            return false;
	        }*/

	        $cart = WC()->cart->get_cart();

	        foreach ($cart as $key => $value) {
	            if (isset($value['wcscu_price'])) {
	                $value['data']->set_price(($value['wcscu_price']));
	            }
	        }
	    }

	}

	WC_Share_Cart_Url::init();

}