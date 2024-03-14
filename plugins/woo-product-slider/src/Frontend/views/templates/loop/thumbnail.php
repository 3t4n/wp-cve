<?php
/**
 * Product thumbnail.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/loop/thumbnail.php
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 */

?>
<a href="<?php echo esc_url( get_the_permalink() ); ?>" class="wps-product-image">
	<?php
	if ( $product_image ) {
		$product_thumb          = $image_sizes;
		$wps_product_image_size = apply_filters( 'sp_wps_product_image_size', $product_thumb );

		if ( has_post_thumbnail( $shortcode_query->post->ID ) ) {
			echo get_the_post_thumbnail( $shortcode_query->post->ID, $wps_product_image_size, array( 'class' => 'wpsf-product-img' ) );
		} elseif ( $product->get_image_id() ) {
			echo wp_get_attachment_image( $product->get_image_id(), $wps_product_image_size, false, array( 'class' => 'wpsf-product-img' ) );
		} else {
			?>
			<img id="place_holder_thm" src="<?php echo esc_attr( wc_placeholder_img_src() ); ?>" alt="Placeholder" />
			<?php
		}
	}
	?>
</a>
