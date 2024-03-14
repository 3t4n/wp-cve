<?php
defined('ABSPATH') || exit;
/**
 * Product Title Default layouts
 * @since 1.0
 * @author quomodosoft.com
 * shop_ready_is_elementor_mode()
 */

$tag = $settings['title_tag'];
$id = get_the_id();

if (shop_ready_is_elementor_mode()) {

	if ($settings['wready_product_id'] != '') {
		$id = $settings['wready_product_id'];
	}

}

global $product;
$product_instance = is_null($product) ? wc_get_product($id) : $product;

if (!is_object($product_instance)) {
	return;
}


/*Title Tag*/
if (!empty($settings['title_tag'])) {
	$title_tag = $settings['title_tag'];
} else {
	$title_tag = 'h3';
}

/*Title*/
if ($product_instance && method_exists($product_instance, 'get_name')) {
	$title = '<' . $title_tag . ' class="area__title">' . $product_instance->get_name() . '</' . $title_tag . '>';
} else {
	$title = '';
}

echo wp_kses_post($title);