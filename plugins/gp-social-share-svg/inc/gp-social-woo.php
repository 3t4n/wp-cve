<?php

// Exit if directlye accessed
defined( 'ABSPATH' ) or die( 'Cannot access pages directly.' );

function gp_social_wc_global_hooks() {
    $hooks = array (
        'woocommerce_before_main_content' => 'woocommerce_before_main_content',
        'woocommerce_after_main_content' => 'woocommerce_after_main_content',
        'woocommerce_sidebar' => 'woocommerce_sidebar',
        'woocommerce_breadcrumb' => 'woocommerce_breadcrumb',
    );
    return $hooks;
}
function gp_social_wc_shops_hooks() {
    $hooks = array (
        'woocommerce_archive_description' => 'woocommerce_archive_description',
        'woocommerce_before_shop_loop' => 'woocommerce_before_shop_loop',
        'woocommerce_after_shop_loop' => 'woocommerce_after_shop_loop',
        'woocommerce_before_shop_loop_item_title' => 'woocommerce_before_shop_loop_item_title',
        'woocommerce_after_shop_loop_item_title' => 'woocommerce_after_shop_loop_item_title',
    );
    return $hooks;
}
function gp_social_wc_single_hooks() {
    $hooks = array (
        'woocommerce_before_single_product' => 'woocommerce_before_single_product',
        'woocommerce_before_single_product_summary' => 'woocommerce_before_single_product_summary',
        'woocommerce_after_single_product_summary' => 'woocommerce_after_single_product_summary',
        'woocommerce_single_product_summary' => 'woocommerce_single_product_summary',
        'woocommerce_simple_add_to_cart' => 'woocommerce_simple_add_to_cart',
        'woocommerce_before_add_to_cart_form' => 'woocommerce_before_add_to_cart_form',
        'woocommerce_after_add_to_cart_form' => 'woocommerce_after_add_to_cart_form',
        'woocommerce_before_add_to_cart_button' => 'woocommerce_before_add_to_cart_button',
        'woocommerce_after_add_to_cart_button' => 'woocommerce_after_add_to_cart_button',
        'woocommerce_before_add_to_cart_quantity' => 'woocommerce_before_add_to_cart_quantity',
        'woocommerce_after_add_to_cart_quantity' => 'woocommerce_after_add_to_cart_quantity',
        'woocommerce_product_meta_start' => 'woocommerce_product_meta_start',
        'woocommerce_product_meta_end' => 'woocommerce_product_meta_end',
        'woocommerce_after_single_product' => 'woocommerce_after_single_product',
        'woocommerce_share' => 'woocommerce_share',
    );
    return $hooks;
}

function gp_social_woo_hooks( $meta_boxes ) {
    $meta_boxes[] = array(
        
        'title'     => 'WooCommerce Settings',
        'settings_pages' => 'gp_social_settings',

        'tabs'      => array(
            'woocommerce'    => array(
                'label' => 'WooCommerce',
                'icon'  => 'dashicons-cart',
            ),
        ),

        'tab_style' => 'default',
        'tab_wrapper' => true,

        'fields'    => array(
            array(
                'name' => 'WooCommerce Global Hooks',
                'id'   => 'gp_woo_global_hook',
                'type' => 'select',
                'tab'   => 'woocommerce',
                'options' => gp_social_wc_global_hooks(),
                'multiple'        => false,
                'placeholder'     => 'Select the hook location',
                'select_all_none' => false,
            ),// gp_woo_global_hook
            array(
                'name' => 'WooCommerce Product Hooks',
                'id'   => 'gp_woo_single_hook',
                'type' => 'select',
                'tab'   => 'woocommerce',
                'options' => gp_social_wc_single_hooks(),
                'multiple'        => false,
                'placeholder'     => 'Select the hook location',
                'select_all_none' => false,
            ),// gp_woo_single_hook
            array(
                'name' => 'WooCommerce Shop Hooks',
                'id'   => 'gp_woo_shop_hook',
                'type' => 'select',
                'tab'   => 'woocommerce',
                'options' => gp_social_wc_shops_hooks(),
                'multiple'        => false,
                'placeholder'     => 'Select the hook location',
                'select_all_none' => false,
            ),// gp_woo_shop_hook
        ),
    );
    return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'gp_social_woo_hooks' );