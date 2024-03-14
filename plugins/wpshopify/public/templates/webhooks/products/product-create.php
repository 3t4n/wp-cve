<?php

$CPT_Model = ShopWP\Factories\CPT_Model_Factory::build();
$DB_Products = ShopWP\Factories\DB\Products_Factory::build();

if (!empty($data)) {
    $new_post_id = $CPT_Model->insert_or_update_product_post($data->product_listing, $data->product_listing->product_id);
}