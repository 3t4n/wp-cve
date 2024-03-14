<?php
/**
 * PeachPay Express Checkout hooks
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

// Express checkout page
add_filter( 'template_include', 'pp_checkout_template_loader', PHP_INT_MAX );
add_filter( 'wp_list_pages_excludes', 'pp_checkout_hide_navigation', PHP_INT_MAX, 1 );

// Express checkout Button
add_action( 'woocommerce_after_add_to_cart_button', 'pp_checkout_product_page_button' );// Product page
add_action( 'woocommerce_proceed_to_checkout', 'pp_checkout_cart_page_button', 30 );// Cart page
add_action( 'woocommerce_before_checkout_form', 'pp_checkout_checkout_page_button', 30 );// Shortcode Checkout page
add_action( 'the_content', 'pp_checkout_blocks_checkout_page_button' );// Blocks Checkout page
add_action( 'woocommerce_widget_shopping_cart_buttons', 'pp_checkout_mini_cart_button' );// Mini cart
add_action( 'wp_footer', 'pp_checkout_floating_button' ); // Floating

add_filter( 'woocommerce_add_to_cart_fragments', 'pp_checkout_floating_button_cart_fragments' );// Floating cart fragments
add_filter( 'woocommerce_add_to_cart_redirect', 'pp_checkout_product_page_fallback_redirect' );

// Shortcodes
add_shortcode( 'peachpay', 'pp_checkout_button_shortcode' );

// Express checkout ajax
add_action( 'wc_ajax_pp-calculate-checkout', 'pp_checkout_wc_ajax_calculate_checkout' );
add_action( 'wc_ajax_pp-validate-checkout', 'pp_checkout_wc_ajax_validate_checkout' );
add_action( 'wc_ajax_pp-change-quantity', 'pp_checkout_wc_ajax_change_quantity' );
add_action( 'wp_ajax_nopriv_peachpay_ajax_login', 'pp_checkout_wp_ajax_login' );
