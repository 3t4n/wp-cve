<?php

/**
 * The template for displaying product content within loops
 * @since 1.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
	return;
}

$grid_style = get_option('wooready_products_archive_shop_grid_style', 'wc');

$path 		= dirname(__FILE__) . '/grid/layout-' . $grid_style . '.php';

if (file_exists($path)) {
	include($path);
} else {
	include('grid/layout.php');
}
