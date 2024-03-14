<?php
/**
 * Plugin Name: WooCommerce Check Terms & Conditions
 * Plugin URI:http://suoling.net/woocommerce-check-terms-conditions/
 * Description: Make the Terms & Conditions checkbox checked by default
 * Author: Suifengtec
 * Author URI: http://suoling.net/
 * Version: 1.0
 * Forked:https://gist.github.com/BFTrick/7789974
 */
/**
 * Always check the WC Terms & Conditions checkbox
 *
 * @since 1.0
 *
 * @return bool
 */
function cwp_wc_terms( $terms_is_checked ) {
  return true;
}
add_filter( 'woocommerce_terms_is_checked_default', 'cwp_wc_terms', 10 );