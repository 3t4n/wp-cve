<?php
/**
 * Add to cart button.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/loop/add_to_cart.php
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 */

if ( $add_to_cart_button ) {
	?>
	<div class="wpsf-cart-button"><?php echo do_shortcode( '[add_to_cart id="' . get_the_ID() . '" show_price="false"]' ); ?></div>
	<?php
}
