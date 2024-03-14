<?php
/** 
 * Plugin Name: Allow only 1 product in Cart
 * Description: This Plugin gives you the functionality when the user clicks on Add to Cart button it’ll clear their previous cart data entirely and add new cart data, only allow one product purchase at a time.
 * Author: MohammedYasar Khalifa
 * Author URI: https://myasark.wordpress.com/
 * Version: 1.2
 * License: GPL2 or later
 * Text Domain: allow-only-1-product-in-cart-for-woocommerce
 * Domain Path: /languages
 */
defined('ABSPATH') || exit;
class Allow_Only_1_Product_Cart_MYK {
    function __construct() {
     add_action( 'woocommerce_before_calculate_totals', array($this,'wcaopc_keep_only_last_cart_item'), 30, 1 );
    }
  function wcaopc_keep_only_last_cart_item( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;
    $cart_items = $cart->get_cart();
    if( count($cart_items) > 1 ){
        $cart_item_keys = array_keys( $cart_items );
        $cart->remove_cart_item( reset($cart_item_keys) );
     }
   }
}
$plugin = new Allow_Only_1_Product_Cart_MYK();