<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_8_width'] ) ) {
	$sf_8_width = $slider['sf_8_width'];
} else {
	$sf_8_width = '400px';
}
if ( isset( $slider['sf_8_height'] ) ) {
	$sf_8_height = $slider['sf_8_height'];
} else {
	$sf_8_height = '400px';
}
if ( isset( $slider['sf_8_responsive'] ) ) {
	$sf_8_responsive = $slider['sf_8_responsive'];
} else {
	$sf_8_responsive = 'true';
}
if ( isset( $slider['sf_8_sorting'] ) ) {
	$sf_8_sorting = $slider['sf_8_sorting'];
} else {
	$sf_8_sorting = 0;
}

// CSS and JS
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'sf-8-infinite-slider-js' );
?>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(function(){
		jQuery('.sf-8-<?php echo esc_js( $sf_slider_id ); ?>').infiniteslide({
			'responsive': <?php echo esc_js( $sf_8_responsive ); ?>
		});
	});

	jQuery('.scroll_item').on('mouseenter',function(){
		jQuery(this).find('.descdiv').css({
			opacity:1
		});
		}).on('mouseleave',function(){
		jQuery(this).find('.descdiv').css({
			opacity:0
		});
	});
});
</script>
<div class="infiniteslide sf-8-<?php echo esc_attr( $sf_slider_id ); ?>">
		<?php
		// slide sorting start
		if ( $sf_8_sorting == 1 ) {
			// Slide ID Ascending (key Ascending)
			ksort( $slider['sf_slide_title'] );
		}
		if ( $sf_8_sorting == 2 ) {
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
				?>
				<div class="scroll_item sf_8_margin-<?php echo esc_attr( $sf_slider_id ); ?>">
					<?php if ( $sf_slide_title || $sf_slide_descs ) { ?>
					<div class="sf-8-content descdiv">
						<?php if ( $sf_slide_title != '' ) { ?>
						<div class="sf-8-title">
							<?php echo esc_html( $sf_slide_title ); ?>
						</div>
						<?php } ?>
						
						<?php if ( $sf_slide_descs != '' ) { ?>
						<div class="sf-8-desc">
							<?php echo esc_html( $sf_slide_descs ); ?>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
					<div>
						<img class="sf_8_img-<?php echo esc_attr( $sf_slider_id ); ?>" src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" alt="<?php echo esc_attr( $sf_slide_alt ); ?>">
					</div>
				</div>
				<?php
			}//end of for each
		} //end of count
		?>
</div>
<style>
.sf_8_margin-<?php echo esc_html( $sf_slider_id ); ?> {
	margin-right: 1%;
}

.infiniteslide .scroll_item {
	position: relative;
}

.descdiv {
	position: absolute;
	opacity:0;
	bottom: 5%;
	color: #fff;
	padding:5%;
	background: rgba(0,0,0,0.5);
	width: 100%;
	transition:all 1s;
	box-sizing: border-box;
}

.infiniteslide .scroll_item .descdiv .sf-8-desc {
	margin-top: 3%;
	margin-bottom: 3%;
}
.sf_8_img-<?php echo esc_html( $sf_slider_id ); ?> {
	width: <?php echo esc_html( $sf_8_width ); ?>;
	height: <?php echo esc_html( $sf_8_height ); ?>;
}
</style>
