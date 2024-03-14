<?php // slider layout 12

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_12_width'] ) ) {
	$sf_12_width = $slider['sf_12_width'];
} else {
	$sf_12_width = '100%';
}
if ( isset( $slider['sf_12_height'] ) ) {
	$sf_12_height = $slider['sf_12_height'];
} else {
	$sf_12_height = 'auto';
}

// CSS and JS
wp_enqueue_style( 'sf-12-twentytwenty-css' );
wp_enqueue_script( 'sf-12-jquery-twentytwenty-js' );
wp_enqueue_script( 'sf-12-jquery-event-move-js' );
wp_enqueue_script( 'sf-12-jquery-images-loaded-js' );
?>
<style>
.sf-12-main-<?php echo esc_html( $sf_slider_id ); ?>{
	width: <?php echo esc_html( $sf_12_width ); ?>; /* width */
	height: auto;
}

.sf-12-main-<?php echo esc_html( $sf_slider_id ); ?> img {
	width : 100%;
	<?php if ( $sf_12_height == '' || $sf_12_height == 'auto' ) { ?>
	height : auto;
	<?php } else { ?>
	height : <?php echo esc_html( $sf_12_height ); ?>;  /* height in px or auto if blank */
	<?php } ?>
}
	
.sf-12-main-<?php echo esc_html( $sf_slider_id ); ?> .twentytwenty-overlay:hover {
	background: rgba(0, 0, 0, 0); 
}
	
.twentytwenty-before-label, .twentytwenty-after-label, .twentytwenty-overlay {
	position: absolute;
	top: 0;
	width: 100%;
	height: 100%;
}
</style>

<div class="sf-12-main-<?php echo esc_attr( $sf_slider_id ); ?>">
	<div class="sf-12-container-<?php echo esc_attr( $sf_slider_id ); ?>" class="twentytwenty-container">
		<?php
		// load sides
		if ( isset( $slider['sf_slide_title'] ) ) {
			foreach ( $slider['sf_slide_title'] as $sf_id_1 => $value ) {
				$attachment_id  = $sf_id_1;
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
					<!-- The before image is first in first loop -->
					<img alt="<?php echo esc_attr( $sf_slide_alt ); ?>" src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" />
					<!-- The after image is last in second loop -->
				<?php
			}//end of for each
		} //end of count
		?>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {	
	jQuery(".sf-12-container-<?php echo esc_js( $sf_slider_id ); ?>").imagesLoaded().done( function() {
		jQuery(".sf-12-container-<?php echo esc_js( $sf_slider_id ); ?>").twentytwenty();
	});
});
</script>
