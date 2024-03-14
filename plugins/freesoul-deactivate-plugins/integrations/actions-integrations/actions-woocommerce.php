<?php
/**
 * WooCommmece actions to be added to the Actions settings page.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$actions = array(
    'main_file'    => 'woocommerce/woocommerce.php',
    'is_active'    => defined( 'WC_PLUGIN_FILE' ),
    'ajax_actions' => array(
        'add_to_cart'             => array( 'description' => esc_html__( 'add to cart', 'freesoul-deactivate-plugins' ) ),
        'remove_from_cart'        => array( 'description' => esc_html__( 'remove from cart', 'freesoul-deactivate-plugins' ) ),
        'apply_coupon'            => array( 'description' => esc_html__( 'apply coupon', 'freesoul-deactivate-plugins' ) ),
        'remove_coupon'           => array( 'description' => esc_html__( 'remove coupon', 'freesoul-deactivate-plugins' ) ),
        'get_wc_coupon_message'   => array( 'description' => esc_html__( 'coupon message', 'freesoul-deactivate-plugins' ) ),
        'checkout'                => array( 'description' => esc_html__( 'checkout ajax refresh', 'freesoul-deactivate-plugins' ) ),
        'get_cart_totals'         => array( 'description' => esc_html__( 'get cart totals', 'freesoul-deactivate-plugins' ) ),
        'get_customer_location'   => array( 'description' => esc_html__( 'get customer location', 'freesoul-deactivate-plugins' ) ),
        'get_refreshed_fragments' => array( 'description' => esc_html__( 'get refreshed fragments', 'freesoul-deactivate-plugins' ) ),
        'get_variation'           => array( 'description' => esc_html__( 'get variation', 'freesoul-deactivate-plugins' ) ),
        'update_order_review'     => array( 'description' => esc_html__( 'update order review', 'freesoul-deactivate-plugins' ) ),
        'update_shipping_method'  => array( 'description' => esc_html__( 'update shipping method', 'freesoul-deactivate-plugins' ) ),
    ),
);