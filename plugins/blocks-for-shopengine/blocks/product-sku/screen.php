<?php
defined('ABSPATH') || exit;
$post_type = get_post_type();

$product = \ShopEngine\Widgets\Products::instance()->get_product($post_type);

if(!$product->get_sku()) {

	if($block->is_editor) {
		esc_html_e('This product has no sku', 'shopengine-gutenberg-addon');
	}

	return;
}

?>
<div class="shopengine shopengine-widget">


    <div class="shopengine-sku">

        <?php do_action('woocommerce_product_meta_start'); ?>

        <?php if(wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>

            <span class="sku-wrapper">

                    <?php if(isset($settings['shopengine_product_sku_label_show']['desktop']) && $settings['shopengine_product_sku_label_show']['desktop'] == 'true') : ?>

                        <span class="sku-label"><?php esc_html_e('SKU:', 'shopengine-gutenberg-addon'); ?></span>

                    <?php endif; ?>

                <span class="sku-value">
                <?php
					if(empty( $product->get_sku())) {
						esc_html_e('N/A', 'shopengine-gutenberg-addon');
					} else {
						echo esc_html($product->get_sku());
					}
				?>
                </span>

            </span>

        <?php endif; ?>

    </div>

</div>
