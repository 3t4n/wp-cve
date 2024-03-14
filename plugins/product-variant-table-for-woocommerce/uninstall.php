<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$options_array = array(
    'pvtfw_variant_table_place',
    'pvtfw_variant_table_columns',
    'pvtfw_variant_table_show_available_options_btn',
    'pvtfw_variant_table_available_options_btn_text',
    'pvtfw_variant_table_cart_btn_text',
    'pvtfw_variant_table_qty_layout',
    'pvtfw_variant_table_sub_total',
    'pvtfw_variant_table_scroll_to_top',
    'pvtfw_variant_table_cart_notice',
    'pvtfw_variant_table_show_table_header',
    'pvtfw_variant_table_full_table',
    'pvtfw_variant_table_scrollable_x',
    'pvtfw_variant_table_min_width',
    'pvtfw_variant_table_tab',
);

foreach ($options_array as $key => $option) {
	delete_option($option);
}
 