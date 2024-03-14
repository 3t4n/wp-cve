<?php

defined( 'ABSPATH' ) || exit;

$args = [
    'posts_per_page' => 4,
    'columns'        => 4,
    'orderby'        => $settings['orderby'],
    'order'          => $settings['order'],
];

$id = get_the_id();

if (shop_ready_is_elementor_mode()) {
  
    if ($settings['wready_product_id'] != '') {
        $id = $settings['wready_product_id'];
    }
}

global $product;

$product = is_null($product) ? wc_get_product($id) : $product;

if (!is_object($product)) {
    return;
}
if (!empty($settings['posts_per_page'])) {
    $args['posts_per_page'] = $settings['posts_per_page'];
}

if (!empty($settings['columns'])) {
    $args['columns'] = $settings['columns'];
}

if (!method_exists($product, 'get_upsell_ids')) {
    return;
}

$args['related_products'] = array_filter(array_map('wc_get_product', wc_get_related_products($product->get_id(), $args['posts_per_page'], $product->get_upsell_ids())), 'wc_products_array_filter_visible');

$args['related_products'] = wc_products_array_orderby($args['related_products'], $args['orderby'], $args['order']);

wc_get_template('single-product/related.php', $args);
