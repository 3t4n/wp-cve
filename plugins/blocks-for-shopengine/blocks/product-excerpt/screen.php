<?php
defined('ABSPATH') || exit;
use ShopEngine\Utils\Helper;

?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-product-excerpt">
        <div class="woocommerce-product-details__short-description">
			<?php

			$post = get_post();
			$pid  = $post->ID;

			if($block->is_editor) {

				$product = ShopEngine\Widgets\Products::instance()->get_product(get_post_type());

				$pid  = $product->get_id();
				$post = get_post($pid);

			}

			$short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

			if(!$short_description && $block->is_editor) {
				esc_html_e('Dummy short description only for editor preview mode if and only if the editor selected product has no short description.', 'shopengine-gutenberg-addon');
			} else {
				echo wp_kses($short_description, Helper::get_kses_array());
			}

			?>
        </div>
    </div>
</div>
