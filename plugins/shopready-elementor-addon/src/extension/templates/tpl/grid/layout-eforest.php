<?php


if (!defined('ABSPATH')) {
	exit;
}
use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

$meta_hover_content = WReady_Helper::get_global_setting('shop_ready_pro_archive_eforest_meta_hover_content', 'no');
$meta_cls = 'wooready_product_layout_eforest wooready_product_layout_eforest wooready-slider-product-layout';

if ($meta_hover_content == 'yes') {
	$meta_cls = 'shop-ready-eforest-grid-style2 wooready_product_layout_eforest wooready_product_layout_eforest wooready-slider-product-layout';
}

?>


<div <?php wc_product_class($meta_cls, $product); ?>>

	<?php

	do_action('shop_ready_grid_thumbnail');
	do_action('woo_ready_grid_loop_content_before');
	do_action('shop_ready_grid_loop_content');
	do_action('woo_ready_grid_loop_content_after');

	?>

</div>