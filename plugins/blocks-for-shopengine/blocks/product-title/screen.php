<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">

	<?php

	$p_obj   = get_post();
	$pid     = $p_obj->ID;
	$product = ShopEngine\Widgets\Products::instance()->get_wc_product($pid);

	if($block->is_editor) {

		$product = ShopEngine\Widgets\Products::instance()->get_product(get_post_type());

		$pid = $product->get_id();
	}


	$title_tag = $settings['shopengine_product_title_header_size']['desktop'] ?? 'h2';

	echo sprintf(
		'<div class="shopengine-product-title"><%s class="product-title">%s</%s></div>',
		esc_html($title_tag),
		esc_html(get_the_title($pid)),
		esc_html($title_tag)
	);

	?>
</div>



