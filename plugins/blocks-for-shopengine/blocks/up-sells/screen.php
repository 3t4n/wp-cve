<?php defined('ABSPATH') || exit;

$post_type = get_post_type();

$product = \ShopEngine\Widgets\Products::instance()->get_product($post_type);

$editor_mode = $block->is_editor;
$upsell_ids = [];

if(!empty($product)) {
	$upsell_ids = $product->get_upsell_ids();
	$upsells    = array_filter(array_map('wc_get_product', $upsell_ids), 'wc_products_array_filter_visible');
}


$is_slider_enable = $settings['shopengine_up_sells_product_enable_slider']['desktop'] ? true : false;

// slider controls for the template file
$slider_options = [
	'slider_enabled'        => $is_slider_enable,
	'slidesPerView'        => $settings['shopengine_up_sells_product_slider_perview']['desktop'],
	'slider_loop'           => $settings['shopengine_up_sells_product_slider_loop']['desktop'] ? true : false,
	'slider_autoplay'       => $settings['shopengine_up_sells_product_slider_autoplay']['desktop'] ? true : false,
	'slider_autoplay_delay' => $settings['shopengine_up_sells_product_slider_autoplay_delay']['desktop'],
	'slider_space_between'  => $settings['shopengine_up_sells_product_column_gap']['desktop'],
];


//passing slider controls to the template file
$encode_slider_options = wp_json_encode($slider_options);

$limit   = $settings['shopengine_up_sells_product_to_show']['desktop'];
$orderby = $settings['shopengine_up_sells_product_orderby']['desktop'];
$order   = $settings['shopengine_up_sells_product_order']['desktop'];
$columns = $is_slider_enable ? $settings['shopengine_up_sells_product_slider_perview']['desktop'] : 4;

?>

<div class="shopengine shopengine-widget">
	<?php if( ! empty($upsell_ids) || $editor_mode ): ?>
    <div class="shopengine-up-sells <?php echo( (bool) $is_slider_enable ? esc_attr('slider-enabled') : esc_attr('slider-disabled')); ?>"
         data-controls="<?php echo esc_attr($encode_slider_options); ?>">
		<?php

		if( $editor_mode || !is_product() ) {
         //some hardcoded html only for editor
         include __DIR__ . '/dummy-up-sell.php';
		} else {
			woocommerce_upsell_display($limit, $columns, $orderby, $order);
		}

		if($is_slider_enable && $settings['shopengine_up_sells_product_slider_show_dots']['desktop']) {
			echo '<div class="swiper-pagination" style="width: 100%;"></div>';
		}

		if($is_slider_enable && $settings['shopengine_up_sells_product_slider_show_arrows']['desktop']) {
			echo sprintf(
				'<div class="swiper-button-prev"><i class="%1$s"></i></div><div class="swiper-button-next"><i class="%2$s"></i></div>',
				esc_attr($settings['shopengine_up_sells_product_slider_left_arrow_icon']['desktop']),
				esc_attr($settings['shopengine_up_sells_product_slider_right_arrow_icon']['desktop'])
			);
		}
		?>
    </div>
	<?php endif; ?>
</div>
