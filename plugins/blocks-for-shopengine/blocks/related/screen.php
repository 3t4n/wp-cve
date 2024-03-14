<?php

defined('ABSPATH') || exit;

$editor_mode = $block->is_editor;

if($editor_mode) {

	$product = ShopEngine\Widgets\Products::instance()->get_product(get_post_type());

	$pid = $product->get_id();

} else {

	$p_obj   = get_post();
	$pid     = $p_obj->ID;
	$product = ShopEngine\Widgets\Products::instance()->get_wc_product($pid);
}

$is_slider_enable = ($settings["shopengine_enable_slider"]["desktop"]) ? true : false;


if(empty($product)) {

	return;
}

$args                     = [
	'posts_per_page' => $settings["shopengine_products_to_show"]["desktop"],
	'columns'        => $is_slider_enable ? (int) $settings["shopengine_slides_per_view"]["desktop"] : (int) $settings["shopengine_column"]["desktop"],
	'orderby'        => $settings["shopengine_order_by"]["desktop"],
	'order'          => $settings["shopengine_order"]["desktop"],
];


$args['related_products'] = array_filter(array_map('wc_get_product', wc_get_related_products($product->get_id(), $args['posts_per_page'], $product->get_upsell_ids())), 'wc_products_array_filter_visible');

// Handle orderby.
$args['related_products'] = wc_products_array_orderby($args['related_products'], $args['orderby'], $args['order']);

$slider_options = [
	'slider_enabled'        => $is_slider_enable,
	'slides_per_view'       => $settings['shopengine_slides_per_view']['desktop'],
	'slider_loop'           => $settings["shopengine_enable_loop"]["desktop"] ? true : false,
	'slider_autoplay'       => $settings["shopengine_enable_autoplay"]["desktop"] ? true : false,
	'slider_autoplay_delay' => $settings["shopengine_slide_speed"]["desktop"],
	'slider_space_between'  => $settings['shopengine_item_column_gap']["desktop"],
];

//passing slider controls to the template file
$encode_slider_options = wp_json_encode($slider_options);
?>

<div class="shopengine shopengine-widget">

    <div class="shopengine-related <?php echo($is_slider_enable ? 'slider-enabled' : 'slider-disabled'); ?>"
         data-controls="<?php echo esc_attr($encode_slider_options); ?>">
		<?php

		if($editor_mode) {
         //some hardcoded html only for editor
            include __DIR__ . '/dummy-related-product.php';


        } else {
			woocommerce_related_products($args);
		}


		if($is_slider_enable && $settings["shopengine_show_dots"]["desktop"]) {
			echo '<div class="swiper-pagination" style="width: 100%;"></div>';
		}

		if($is_slider_enable && $settings["shopengine_show_arrows"]["desktop"]) {
			echo sprintf(
				'<div class="swiper-button-prev"><i class="%1$s"></i></div><div class="swiper-button-next"><i class="%2$s"></i></div>',
				esc_attr($settings["shopengine_left_arrow"]["desktop"]),
				esc_attr($settings["shopengine_right_arrow"]["desktop"])
			);
		}
		?>
    </div>
</div>

