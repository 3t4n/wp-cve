<?php

/**
 * Single Product Rating
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$id = get_the_id();

if (shop_ready_is_elementor_mode()) {
	
	if ($settings['wready_product_id'] != '') {
		$id = $settings['wready_product_id'];
	}
}

global $product;

$product = is_null($product) ? wc_get_product($id) : $product;

if (!is_object($product)) {
	return;
}
if (!method_exists($product, 'get_rating_count')) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();
if (shop_ready_is_elementor_mode()) {
	$rating_count = 6;
	$average = 4;
}
?>
<div class="woocommerce-product-rating">
    <?php echo wp_kses_post(wc_get_rating_html($average, $rating_count)); // WPCS: XSS ok. 
		?>
    <a href="#<?php echo esc_attr($settings['review_form_section']); ?>" class="woocommerce-review-link"
        rel="nofollow">(<?php printf(_n('%s customer review', '%s customer reviews', esc_html($review_count), 'shopready-elementor-addon'), '<span class="count">' . esc_html($review_count) . '</span>'); ?>)</a>
</div>