<?php
/**
 * Product title.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/loop/title.php
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 */

if ( $product_name ) {
	?>
	<div class="wpsf-product-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a></div>
	<?php
}
