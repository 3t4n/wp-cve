<?php
/**
 * Carousel
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/carousel.php
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend
 */

?>
<div id="wps-slider-section" class="wps-slider-section wps-slider-section-<?php echo esc_attr( $post_id ); ?>">
	<?php
	require self::wps_locate_template( 'slider-title.php' );
	require self::wps_locate_template( 'preloader.php' );
	?>
	<div id="sp-woo-product-slider-<?php echo esc_attr( $post_id ); ?>" class="wps-product-section sp-wps-<?php echo esc_attr( $template_class ); ?>" <?php echo wp_kses_post( $slider_data . ' ' . $the_rtl ); ?> data-preloader="<?php echo esc_attr( $preloader ); ?>">
	<?php
	if ( 'slider' === $layout_preset ) {
		?>
		<div class="swiper-wrapper">
		<?php
	}

	if ( $shortcode_query->have_posts() ) {
		while ( $shortcode_query->have_posts() ) :
			$shortcode_query->the_post();
			global $product;
			if ( 'custom' === $template_style ) {
				require self::wps_locate_template( 'custom/custom.php' );
			} else {
				require self::wps_locate_template( 'theme/theme.php' );
			}

		endwhile;
		if ( 'grid' === $layout_preset ) {
			require self::wps_locate_template( 'pagination.php' );
		}
		if ( 'slider' === $layout_preset ) {
			?>
			</div> <!-- end swiper-wrapper tag  -->
			<?php
		}
		?>
		<?php if ( 'slider' === $layout_preset && 'true' === $navigation ) : ?>
		<!-- next / prev arrows -->
			<div class="wpsp-nav swiper-button-next"><i class="fa fa-angle-right"></i></div>
			<div class="wpsp-nav swiper-button-prev"><i class="fa fa-angle-left"></i></div>
			<!-- !next / prev arrows -->
				<?php
			endif;
		if ( 'slider' === $layout_preset && 'true' === $pagination ) :
			?>
			<!-- pagination dots -->
			<div class="wpsp-pagination-dot swiper-pagination"></div>
			<!-- !pagination dots -->
			<?php
			endif;
	} else {
		?>
		<h2 class="sp-not-found-any-product-f"><?php echo esc_html__( 'No products found', 'woo-product-slider' ); ?></h2>
			<?php
	}
	wp_reset_postdata();
	?>
	</div>
</div>
