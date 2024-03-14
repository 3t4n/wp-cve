<?php

defined('ABSPATH') || exit;

use ShopEngine\Utils\Helper;

defined('ABSPATH') || exit;

$label     = $settings["shopengine_show_label"]["desktop"];
$post_type = get_post_type();

$product = \ShopEngine\Widgets\Products::instance()->get_product($post_type);

if(!has_term('', 'product_tag', $product->get_id())) {

	if($block->is_editor) {
		echo wp_kses_post('<span class="product-tags-dummy">'. __('This product has no tags', 'shopengine-gutenberg-addon'). '</span>');
	}

	return;
}

?>

<div class="shopengine-tags">

	<?php if($label): ?>

        <span class="product-tags-label">
			<?php echo esc_html( sprintf( _n('TAG:', 'TAGs:', count( $product->get_tag_ids() ), 'shopengine-gutenberg-addon') ) ); ?>
		</span>

	<?php endif;

	shopengine_content_render(wc_get_product_tag_list($product->get_id(), ', ', '<span class="product-tags-links">', '</span>'));

	do_action('woocommerce_product_meta_end'); ?>

</div>
