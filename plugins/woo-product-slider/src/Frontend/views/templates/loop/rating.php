<?php
/**
 * Average rating.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/loop/rating.php
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 */

if ( class_exists( 'WooCommerce' ) && $product_rating ) {
	$average = $product->get_average_rating();
	if ( $average > 0 ) {
		?>
		<div class="star-rating" title="<?php echo esc_html__( 'Rated', 'woo-product-slider' ) . ' ' . esc_attr( $average ) . '' . esc_html__( ' out of 5', 'woo-product-slider' ); ?>">
			<span style="width:<?php echo esc_attr( ( ( $average / 5 ) * 100 ) ); ?>%"><strong itemprop="ratingValue" class="rating"><?php echo esc_attr( $average ); ?></strong>
			<?php echo esc_html__( 'out of 5', 'woo-product-slider' ); ?></span>
		</div>
		<?php
	}
}
