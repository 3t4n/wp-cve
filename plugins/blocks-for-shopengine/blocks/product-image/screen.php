<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-product-image shopengine-gallery-slider">
        <a href="#" class="shopengine-product-image-toggle position-<?php echo esc_attr($settings['shopengine_lightbox_icon_position']['desktop']); ?>">
            <i aria-hidden="true"
               class="<?php echo $settings["shopengine_image_lightbox_icon"]["desktop"] ? esc_attr($settings["shopengine_image_lightbox_icon"]["desktop"]) : "fas fa-expand-alt"; ?>"></i>
        </a>
		<?php
		$product = \ShopEngine\Widgets\Products::instance()->get_product(get_post_type());

		if($block->is_editor || !is_product()) {
			wc()->frontend_includes();
		}

		/*
			-----------------------------------------------
			Changed flash sale text to percentage off text
			-----------------------------------------------
		*/

		if($settings["shopengine_show_badge"]["desktop"]) {

			$regular_price = $product->get_regular_price();
			$current_price = $product->get_price();

			if(!empty($regular_price) && !empty($current_price)) {

				$flash_slae_position = 'position-' . $settings['shopengine_flash_sale_position']['desktop'];
				$s_p                 = ($regular_price - $current_price) / $regular_price * 100;
				$sale_price          = \Automattic\WooCommerce\Utilities\NumberUtil::round($s_p);

				echo '<span class="onsale ' . esc_attr($flash_slae_position) . '">' . esc_html($sale_price) . esc_html__('% OFF', 'shopengine-gutenberg-addon') . '</span>';
			}

		} // flash sale end
		?>

		<?php wc_get_template('single-product/product-image.php'); ?>
    </div>
</div>

