<?php
/**
 * Single Product Image
 * @since 1.0
 */
defined('ABSPATH') || exit;
wp_enqueue_script('wc-single-product');
wp_enqueue_style('wc-single-product');
$id = get_the_id();

if ($settings['wready_product_id'] != '') {
	$id = $settings['wready_product_id'];
}
if (shop_ready_is_elementor_mode()) {
	wp_enqueue_style('flex-slider-css', SHOP_READY_PUBLIC_ROOT_CSS . 'plugins/flexslider.css');
	?>

<?php

}

global $product;

$product = is_null($product) ? wc_get_product($id) : $product;

if (!is_object($product)) {
	return;
}

if (!method_exists($product, 'get_id')) {
	return;
}

if ($settings['show_flash'] == 'yes') {
	wc_get_template('loop/sale-flash.php');
}

wc_get_template('single-product/product-image.php');

// On render widget from Editor - trigger the init manually.
if (shop_ready_is_elementor_mode()) {
	wp_enqueue_script('flex-slider-js', SHOP_READY_PUBLIC_ROOT_JS . '/plugins/flexslider.js');
	?>
<script>
(function() {

    jQuery('.woocommerce-product-gallery').each(function() {
        jQuery(this).wc_product_gallery();
    });

})();
</script>

<?php
}

?>