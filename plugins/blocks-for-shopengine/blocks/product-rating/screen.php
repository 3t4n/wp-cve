<?php

defined('ABSPATH') || exit;

use ShopEngine\Utils\Helper;

$is_editor    = $block->is_editor;
$product      = \ShopEngine\Widgets\Products::instance()->get_product(get_post_type());
$rating_count = $product->get_rating_count();


// return if review not available
if(!$is_editor && (!post_type_supports('product', 'comments') || !wc_review_ratings_enabled() || $rating_count <= 0 || !function_exists('woocommerce_template_single_rating'))) {
	return;
}


?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-product-rating">

		<?php if($is_editor) : ?>

            <div class="woocommerce-product-rating">
				<?php shopengine_content_render(wc_get_rating_html(3.5, 10)); ?>
                <a href="#reviews" class="woocommerce-review-link" rel="nofollow">
                    (<?php 
                    // Translators: %s represents the number of customer reviews. Do not translate "span" and "count" in the HTML.
                    printf(wp_kses(_n('%s customer review', '%s customer reviews', 10, 'shopengine-gutenberg-addon'), Helper::get_kses_array()), '<span class="count">' . esc_html(10) . '</span>'); ?>
                    )
                </a>
            </div>

		<?php else :
			woocommerce_template_single_rating();
		endif; ?>

    </div>
</div>

