<?php

defined('ABSPATH') || exit;

$p_obj   = get_post();
$pid     = $p_obj->ID;
$product = ShopEngine\Widgets\Products::instance()->get_wc_product($pid);

if($block->is_editor) {

	$product = ShopEngine\Widgets\Products::instance()->get_a_simple_product();

	$pid = $product->get_id();
}

if(!has_term('', 'product_cat', $pid)) {

	return;
}

?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-cats shopengine-flex-align">
        <!-- Condition true kora hoyeche -->
		<!-- <?php if(isset($settings['shopengine_product_cats_label_show']['desktop']) && $settings['shopengine_product_cats_label_show']['desktop'] == true) : ?> -->

            <span class="product-cats-label">
				<?php esc_html_e('Category: ', 'shopengine-gutenberg-addon') . esc_html(count($product->get_category_ids())); ?>
			</span>

		<?php endif;
		shopengine_content_render(wc_get_product_category_list($product->get_id(), ', ', '<span class="product-cats-links">', '</span>')); ?>
    </div>
</div>
