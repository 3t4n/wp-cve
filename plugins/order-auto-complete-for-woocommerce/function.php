<?php

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {


    // To change add to cart text on single product page
    add_filter('woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text');
    function woocommerce_custom_single_add_to_cart_text()
    {
        $singleshop = get_option('woodecor_options1');
        if (!empty($singleshop)) {
            return esc_html_e($singleshop, 'wtt-woo-auto-complete');
        } else {
            return __('Add To cart', 'wtt-woo-auto-complete');
        }
    }


    // To change add to cart text on product archives(Collection) page

    add_filter('woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text');
    function woocommerce_custom_product_add_to_cart_text()
    {
        $archiveshop = get_option('woodecor_options1');
        $outofstockbtn = get_option('woodecor_options2');
        global $product;
        if ($product && !$product->is_in_stock()) {
            return esc_html_e($outofstockbtn, 'wtt-woo-auto-complete');
        } else {


            return __($archiveshopbtn = !empty($archiveshop) ? $archiveshop : 'Add to cart', 'wtt-woo-auto-complete');
        }
    }
}
