<?php

defined('ABSPATH') || exit;

use ShopEngine\Utils\Helper;

function generate_order_item_css($order_items,$random_id) {
	$styles = '';
	$parent_class = '.gutenova-element-'. $random_id;

	foreach($order_items as $key => $item) {
		$order_number = $key + 1;
		if($item['id'] == 'search-box') {
			$styles .= $parent_class . ' .shopengine-advanced-search .search-input-group :is( button )  {order: '. $order_number .';}';
		}
		if($item['id'] == 'search-input') {
			$styles .= $parent_class . ' .shopengine-advanced-search-input  {order: '. $order_number .';}';
		}
		if($item['id'] == 'category-selector') {
			$styles .= $parent_class . ' .shopengine-category-select-wraper  {order: '. $order_number .';}';
		}
	}

	echo '<style>'.wp_kses($styles, Helper::get_kses_array()).'</style>';
}