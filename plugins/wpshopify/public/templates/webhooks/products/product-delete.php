<?php

$DB_Products = ShopWP\Factories\DB\Products_Factory::build();
$DB_Posts = ShopWP\Factories\DB\Posts_Factory::build();

$product_id = $data->product_listing->product_id;

$found_post = \get_posts([
    'post_type' => 'wps_products',
    'meta_query' => [
        [
            'key' => 'product_id',
            'value' => $product_id,
            'compare' => '=',
        ]
    ]
]);

if (!empty($found_post)) {
    $delete_result = \wp_delete_post($found_post[0]->ID, true);
}