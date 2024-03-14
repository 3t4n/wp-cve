<?php

defined('ABSPATH') || exit;

$product = \ShopEngine\Widgets\Products::instance()->get_product(get_post_type());

$heading = apply_filters('woocommerce_product_additional_information_heading', esc_html__('Additional information', 'shopengine-gutenberg-addon'));

if($block->is_editor) {
	wc()->frontend_includes();
}
?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-additional-information">


		<?php if($heading) : ?>
            <h2><?php echo esc_html($heading); ?></h2>
		<?php endif; ?>

		<?php do_action('woocommerce_product_additional_information', $product); ?>

    </div>
</div>




