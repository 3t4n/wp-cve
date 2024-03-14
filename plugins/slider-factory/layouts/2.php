<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_2_width'] ) ) {
	$sf_2_width = $slider['sf_2_width'];
} else {
	$sf_2_width = '100%';
}
if ( isset( $slider['sf_2_height'] ) ) {
	$sf_2_height = $slider['sf_2_height'];
} else {
	$sf_2_height = '100%';
}
if ( isset( $slider['sf_2_sorting'] ) ) {
	$sf_2_sorting = $slider['sf_2_sorting'];
} else {
	$sf_2_sorting = 0;
}

// CSS and JS
wp_enqueue_script( 'jquery' );
wp_enqueue_style( 'sf-2-photoroller-css' );
wp_enqueue_script( 'sf-2-photoroller-js' );
?>
<script>
jQuery( document ).ready(function() {
	jQuery(".sf-2-<?php echo esc_js( $sf_slider_id ); ?>").photoroller({	});
});
</script>

<!-- slider start-->
<div class="sf-2-<?php echo esc_attr( $sf_slider_id ); ?>">
	<?php
	// slide sorting start
	if ( $sf_2_sorting == 1 ) {
		// Slide ID Ascending (key Ascending)
		ksort( $slider['sf_slide_title'] );
	}
	if ( $sf_2_sorting == 2 ) {
		// Slide ID Descending (key Descending)
		krsort( $slider['sf_slide_title'] );
	}
	// slide sorting end

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
			<div class="sf-2-slide">
				<img class="sf-2-slide-image" src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" alt="<?php echo esc_attr( $sf_slide_alt ); ?>">
				
				<?php if ( $sf_slide_title != '' || $sf_slide_descs != '' ) { ?>
				<div class="sf-2-slide-content">
					<?php if ( $sf_slide_title != '' ) { ?>
					<div class="sf-2-slide-title">
						<?php echo esc_html( $sf_slide_title ); ?>
					</div>
					<?php } ?>
					
					<?php if ( $sf_slide_descs != '' ) { ?>
					<div class="sf-2-slide-desc">
						<?php echo esc_html( $sf_slide_descs ); ?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
			<?php
		}//end of for each
	} //end of count
	?>
</div>
<!-- slider end-->

<style>
.sf-2-<?php echo esc_html( $sf_slider_id ); ?> {
	width: <?php echo esc_html( $sf_2_width ); ?>;
	height: <?php echo esc_html( $sf_2_height ); ?>;
}


<?php if ( strstr( $sf_2_height, 'px' ) ) { ?>
.sf-2-slide-image {
	height: <?php echo esc_html( $sf_2_height ); ?>;
}
<?php } ?>

.sf-2-slide-image {
	width: 100%;
}

.sf-2-slide-content {
	margin: 3px;
	padding: 3px;
}

.sf-2-slide-title {
	padding: 3px;
	margin-top: 4px;
	margin-bottom: 4px;
}
.sf-2-slide-desc {
	padding: 3px;
	margin-top: 4px;
	margin-bottom: 4px;
}
</style>
