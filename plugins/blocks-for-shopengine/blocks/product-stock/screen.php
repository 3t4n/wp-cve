<?php

defined('ABSPATH') || exit;

$post_type = get_post_type();
$product   = ShopEngine\Widgets\Products::instance()->get_product($post_type);

$icon         = '';
$stock_status = $product->get_stock_status();
$availability = $product->get_availability();

if($stock_status == 'instock') :

	$icon = isset($settings['shopengine_pstock_in_stock_icon']['desktop']) ? $settings['shopengine_pstock_in_stock_icon']['desktop'] : '';

elseif($stock_status == 'outofstock') :

	$icon = isset($settings['shopengine_pstock_out_of_stock_icon']['desktop']) ? $settings['shopengine_pstock_out_of_stock_icon']['desktop'] : '';

elseif($stock_status == 'onbackorder') :

	$icon = isset($settings['shopengine_pstock_available_on_backorder_icon']['desktop']) ? $settings['shopengine_pstock_available_on_backorder_icon']['desktop'] : '';

endif;
?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-product-stock">

		<?php if($block->is_editor) :

			$icons = [
				'in_stock_icon'               => isset($settings['shopengine_pstock_in_stock_icon']['desktop']) ? $settings['shopengine_pstock_in_stock_icon']['desktop'] : '',
				'out_of_stock_icon'           => isset($settings['shopengine_pstock_out_of_stock_icon']['desktop']) ? $settings['shopengine_pstock_out_of_stock_icon']['desktop'] : '',
				'available_on_backorder_icon' => isset($settings['shopengine_pstock_available_on_backorder_icon']['desktop']) ? $settings['shopengine_pstock_available_on_backorder_icon']['desktop'] : '',
			];

			$stock_type = $settings['shopengine_pstock_stock_type']['desktop'];
			$stock_type = $stock_type ? $stock_type : 'in_stock'; // Validate Stock Type.

			$stock_class = str_replace('_', '-', $stock_type);
			$stock_text  = isset($settings[$stock_type . '_text']) ? $settings[$stock_type . '_text'] : ucwords(str_replace('_', ' ', $stock_type));

			$stock_icon = isset($icons[$stock_type . '_icon']) ? $icons[$stock_type . '_icon'] : '';

			echo '<p class="' . esc_attr($stock_class) . '">  <i class="' . esc_attr($stock_icon) . '"></i> ' . esc_html($stock_text) . '</p>';

		else : ?>

            <p class="<?php echo esc_attr($availability['class']); ?>">

				<?php
				if(!empty($icon)) :
					?>
                    <i class="<?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
				<?php
				endif;

				if($product->is_on_backorder()) {
					$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__('On backorder', 'shopengine-gutenberg-addon');
				} elseif($product->is_in_stock()) {
					$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__('In Stock', 'shopengine-gutenberg-addon');
				} else {
					$stock_html = $availability['availability'] ? $availability['availability'] : esc_html__('Out of stock', 'shopengine-gutenberg-addon');
				}
				shopengine_content_render(apply_filters('woocommerce_stock_html', $stock_html, wp_kses_post($availability['availability']), $product));
				?>

            </p>

		<?php endif; ?>

    </div>
</div>
