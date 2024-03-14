<?php
/*
* Shop Page
*/

// Add to Cart Button Text
add_filter( 'woocommerce_product_add_to_cart_text', 'wpbforwpbakery_custom_add_cart_button_shop_page', 99, 2 );
function wpbforwpbakery_custom_add_cart_button_shop_page( $label ) {
   return wpbforwpbakery_get_option( 'shop_add_to_cart_txt', 'wpbforwpbakery_rename_label_tabs', __('Add To Cart', 'wpbforwpbakery' ), true );
}

/*
* Product Details Page
*/

// Add to Cart Button Text
add_filter( 'woocommerce_product_single_add_to_cart_text', 'wpbforwpbakery_custom_add_cart_button_single_product' );
function wpbforwpbakery_custom_add_cart_button_single_product( $label ) {
   return wpbforwpbakery_get_option( 'add_to_cart_txt', 'wpbforwpbakery_rename_label_tabs', __('Add To Cart', 'wpbforwpbakery' ), true);
}