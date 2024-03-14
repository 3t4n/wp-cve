<?php

if (!defined('WPINC')) {
    exit;
}


$post_columns = array(
    'id' => 'Product Id[id]',
    'title' => 'Product Title[title]',
    'description' => 'Product Description[description]',
    'item_group_id' => 'Item Group Id[item_group_id]',
    'link' => 'Product URL[link]',
    'product_type' => 'Product Categories[product_type] ',
    'google_product_category' => 'Google Product Category[google_product_category]',
    'image_link' => 'Main Image[image_link]',
    'condition' => 'Condition[condition]',
    'availability' => 'Condition[condition]',
    'price' => 'Price[price]',
    'mpn' => 'MPN[mpn]',
    'brand' => 'Brand[brand]',
    'sell_on_google_quantity' => 'Sell on Google quantity[sell_on_google_quantity]',
    'min_handling_time' => 'Min handling time[min_handling_time]',
    'max_handling_time' => 'Max handling time[max_handling_time]',
    'return_address_label' => 'Return address label[return_address_label]',
    'return_policy_label' => 'Return policy label[return_policy_label]',
    'google_funded_promotion_eligibility' => 'Google funded promotion eligibility[google_funded_promotion_eligibility]',
);

return apply_filters('wt_pf_glpi_product_post_columns', $post_columns);
