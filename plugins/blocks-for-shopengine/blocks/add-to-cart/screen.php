<?php

defined('ABSPATH') || exit;

$product   = \ShopEngine\Widgets\Products::instance()->get_product(get_post_type());
$data_attr = apply_filters('shopengine/add_to_cart_widget/optional_tooltip_data_attr', '');
?>

<div class="shopengine shopengine-widget">
    <div class='shopengine-swatches' <?php echo esc_attr($data_attr) ?>>

		<?php

		$editor_mode = $block->is_editor;

		if($editor_mode) {
			if($product->get_stock_status() != 'instock') {
				esc_html_e('To see the add to cart button , please set stock status as instock for - .', 'shopengine-gutenberg-addon') . '"' . esc_html($product->get_name()) . '"';
			}
		}

		/*
		---------------------------------------------
		Add action for woocommerce quantity button
		--------------------------------------------
	*/

		if(!$product->is_sold_individually()) {

			$stock_quantity = $product->get_stock_quantity();
            
			if($stock_quantity != 1){
			// plus minus button
			$btn_arg = [
				'plus_icon'  => $settings["shopengine_quantity_btn_plus_icon"]["desktop"],
				'minus_icon' => $settings["shopengine_quantity_btn_minus_icon"]["desktop"],
				'position'   => $settings["shopengine_quantity_btn_position"]["desktop"],
			];


			add_action('woocommerce_before_add_to_cart_quantity', function () use ($btn_arg) {

				printf('<div class="quantity-wrap %1$s">', esc_attr($btn_arg['position']));

				if($btn_arg['position'] === 'before') { ?>
                    <div class="shopengine-qty-btn">
                        <button type="button" class="plus <?php echo esc_attr($btn_arg['plus_icon']); ?>"></button>
                        <button type="button" class="minus <?php echo esc_attr($btn_arg['minus_icon']); ?>"></button>
                    </div>
					<?php
				}

				if($btn_arg['position'] === 'both') { ?>
                    <button type="button" class="minus <?php echo esc_attr($btn_arg['minus_icon']); ?>"></button>
					<?php
				}
			});

			add_action('woocommerce_after_add_to_cart_quantity', function () use ($btn_arg) {

				if($btn_arg['position'] === 'after') { ?>
                    <div class="shopengine-qty-btn">
                        <button type="button" class="plus <?php echo esc_attr($btn_arg['plus_icon']); ?>"></button>
                        <button type="button" class="minus <?php echo esc_attr($btn_arg['minus_icon']); ?>"></button>
                    </div>
					<?php
				}

				if($btn_arg['position'] === 'both') { ?>
                    <button type="button" class="plus <?php echo esc_attr($btn_arg['plus_icon']); ?>"></button>
					<?php
				}

				echo '</div>';
			});
			}
		}

		if($editor_mode) {
			WC()->frontend_includes();
			WC()->session = new WC_Session_Handler();
			WC()->session->init();
			WC()->cart = new WC_Cart();
		}
		do_action('woocommerce_' . $product->get_type() . '_add_to_cart');

		?>

    </div>
</div>
