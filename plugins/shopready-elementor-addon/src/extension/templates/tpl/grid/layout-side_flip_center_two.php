<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<div <?php wc_product_class('wooready_product_layout_3 wooready_product_layout_center_two', $product); ?>>

	<?php

	do_action('shop_ready_grid_thumbnail');
	do_action('woo_ready_grid_loop_ontent_before');
	do_action('shop_ready_grid_loop_ontent');
	do_action('woo_ready_grid_loop_ontent_after');

	?>

</div>