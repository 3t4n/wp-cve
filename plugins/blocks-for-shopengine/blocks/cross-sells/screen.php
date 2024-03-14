<?php
defined('ABSPATH') || exit;

$post_type = get_post_type();

$product = \ShopEngine\Widgets\Products::instance()->get_product(get_post_type());


$is_slider_enable = ($settings['shopengine_cross_sells_product_enable_slider']['desktop'] == true) ? true : false;

// slider controls for the template file
$slider_options = [
	'slider_enabled'        => $is_slider_enable,
	'slidesPerView'        => $settings['shopengine_cross_sells_product_slider_perview']['desktop'],
	'slider_loop'           => ($settings['shopengine_cross_sells_product_slider_loop']['desktop'] == true) ? true : false,
	'slider_autoplay'       => ($settings['shopengine_cross_sells_product_slider_autoplay']['desktop'] == true) ? true : false,
	'slider_autoplay_delay' => $settings['shopengine_cross_sells_product_slider_autoplay_delay']['desktop'],
	'slider_space_between'  => $settings['shopengine_cross_sells_product_column_gap']['desktop'],
];

//passing slider controls to the template file
$encode_slider_options = wp_json_encode($slider_options);

\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();

\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_part_filter_by_match('woocommerce/content-product.php', 'templates/content-product.php');

$editor_mode = $block->is_editor;

if($editor_mode) {

	$args = [
		'type'  => ['simple'],
		'limit' => $settings['shopengine_cross_sells_product_to_show']['desktop'],
	];

	$parent_product_array = wc_get_products($args);

	foreach($parent_product_array as $prod) {

		$crosssell_products[] = $prod->get_id();
	}

	add_filter('woocommerce_cart_crosssell_ids', function ($cross_sell_ids) use ($crosssell_products) {

		return $crosssell_products;
	});

	wc()->frontend_includes();

	\Wpmet\Gutenova\Helper::add_product_in_cart_if_no_cart_found();
}

$cross_sells = null;

if(WC()->cart) {
	$cross_sells = array_filter(array_map('wc_get_product', WC()->cart->get_cross_sells()), 'wc_products_array_filter_visible');
}

if(empty($cross_sells)) {
	return;
}


$limit   = $settings['shopengine_cross_sells_product_to_show']['desktop'];
$orderby = $settings['shopengine_cross_sells_product_orderby']['desktop'];
$order   = $settings['shopengine_cross_sells_product_order']['desktop'];
$columns = $is_slider_enable ? $settings['shopengine_cross_sells_product_slider_perview']['desktop'] : $settings['shopengine_cross_sells_product_columns']['desktop'];
?>
<div class="shopengine shopengine-widget">
    <div class="shopengine-cross-sells <?php echo($is_slider_enable ? 'slider-enabled' : 'slider-disabled'); ?>"
         data-controls="<?php echo esc_attr($encode_slider_options); ?>">
		<?php

		woocommerce_cross_sell_display($limit, $columns, $orderby, $order);

		if($is_slider_enable && $settings['shopengine_cross_sells_product_slider_show_dots']['desktop']) {
			echo '<div class="swiper-pagination" style="width: 100%;"></div>';
		}

		if($is_slider_enable && $settings['shopengine_cross_sells_product_slider_show_arrows']['desktop']) {
			echo sprintf(
				'<div class="swiper-button-prev"><i class="%1$s"></i></div><div class="swiper-button-next"><i class="%2$s"></i></div>',
				esc_attr($settings['shopengine_cross_sells_product_slider_left_arrow_icon']['desktop']),
				esc_attr($settings['shopengine_cross_sells_product_slider_right_arrow_icon']['desktop'])
			);
		}
		?>
    </div>
</div>
