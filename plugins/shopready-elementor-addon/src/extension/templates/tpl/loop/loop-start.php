<?php

/**
 * Product Loop Start
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$grid_style = get_option('wooready_products_archive_shop_grid_style', 'wc');



?>

<div class="<?php echo esc_attr($grid_style . '-sr-customize') ?> products display:grid width:100% flex-wrap:wrap woo-ready-products shop-ready-product-grid-ajax-loader grid-template-columns-<?php echo esc_attr(wc_get_loop_prop('columns')); ?>">