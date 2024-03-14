<?php // slider layout 11

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_11_width'] ) ) {
	$sf_11_width = $slider['sf_11_width'];
} else {
	$sf_11_width = '100%';
}
if ( isset( $slider['sf_11_height'] ) ) {
	$sf_11_height = $slider['sf_11_height'];
} else {
	$sf_11_height = '750px';
}
if ( isset( $slider['sf_11_sorting'] ) ) {
	$sf_11_sorting = $slider['sf_11_sorting'];
} else {
	$sf_11_sorting = 0;
}

// CSS and JS
wp_enqueue_style( 'sf-11-product-slider-style-css' );
wp_enqueue_script( 'sf-11-product-slider-mordenizer-js' );
wp_enqueue_script( 'sf-11-product-slider-js' );
?>
<div class="sf-11-<?php echo esc_attr( $sf_slider_id ); ?>">
	<section id="ps-container" class="ps-container">
	
		<div class="ps-header">
			<h1></h1>
		</div>
		
		<!-- /ps-header -->
		<div class="ps-contentwrapper">
			<?php
			// slide sorting start
			if ( $sf_11_sorting == 1 ) {
				// Slide ID Ascending (key Ascending)
				ksort( $slider['sf_slide_title'] );
			}
			if ( $sf_11_sorting == 2 ) {
				// Slide ID Descending (key Descending)
				krsort( $slider['sf_slide_title'] );
			}
			// slide sorting end

			// load sides
			if ( isset( $slider['sf_slide_title'] ) ) {
				foreach ( $slider['sf_slide_title'] as $sf_id => $value ) {
					$attachment_id  = $sf_id;
					$sf_slide_title = get_the_title( $attachment_id );
					$sf_slide_alt   = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
					// wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false )
					// thumb, thumbnail, medium, large, post-thumbnail
					$sf_slide_thumbnail_url = wp_get_attachment_image_src( $attachment_id, 'large', true ); // attachment medium URL
					$sf_slide_full_url      = wp_get_attachment_image_src( $attachment_id, 'full', true ); // attachment medium URL
					$attachment             = get_post( $attachment_id );
					$sf_slide_descs         = $attachment->post_content; // attachment description
					// print_r($sf_slide_full_url);
					?>
				<div class="ps-content">
					<h2><?php echo esc_html( $sf_slide_title ); ?></h2>
					<p><?php echo esc_html( $sf_slide_descs ); ?></p>
				</div>
					<?php
				}//end of for each
			} //end of count
			?>
		</div>
		<!-- /ps-contentwrapper -->
		
		<!-- loop again here for images -->
		<div class="ps-slidewrapper">
			<div class="ps-slides">
				<?php
				// load sides
				if ( isset( $slider['sf_slide_title'] ) ) {
					foreach ( $slider['sf_slide_title'] as $sf_id_2 => $value ) {
						$attachment_id  = $sf_id_2;
						$sf_slide_title = get_the_title( $attachment_id );
						$sf_slide_alt   = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
						// wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false )
						// thumb, thumbnail, medium, large, post-thumbnail
						$sf_slide_thumbnail_url = wp_get_attachment_image_src( $attachment_id, 'large', true ); // attachment medium URL
						$sf_slide_full_url      = wp_get_attachment_image_src( $attachment_id, 'full', true ); // attachment medium URL
						$attachment             = get_post( $attachment_id );
						$sf_slide_descs         = $attachment->post_content; // attachment description
						// print_r($sf_slide_full_url);
						?>
				<div style="background-image:url(<?php echo esc_url( $sf_slide_full_url[0] ); ?>);"></div>
						<?php
					}//end of for each
				} //end of count
				?>
			</div>

			<nav>
				<a href="#" class="ps-prev"></a>
				<a href="#" class="ps-next"></a>
			</nav>
		</div>
		<!-- /ps-slidewrapper -->

	</section>
	<!-- /ps-container -->
</div>
<style>
.sf-11-<?php echo esc_html( $sf_slider_id ); ?>{
	width:<?php echo esc_html( $sf_11_width ); ?>;
	height:<?php echo esc_html( $sf_11_height ); ?>;
}
</style>
<script type="text/javascript">
jQuery(function () {
	Slider.init();
});
</script>
