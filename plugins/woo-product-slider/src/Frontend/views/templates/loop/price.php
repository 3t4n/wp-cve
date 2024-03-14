<?php
/**
 * Price.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/loop/price.php
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 */

$price_html = $product->get_price_html();
if ( $product_price && class_exists( 'WooCommerce' ) && $price_html ) {
	?>
	<div class="wpsf-product-price"> <?php echo wp_kses_post( $price_html ); ?></div>
	<?php
}
