<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_6_width'] ) ) {
	$sf_6_width = $slider['sf_6_width'];
} else {
	$sf_6_width = '100%';
}
if ( isset( $slider['sf_6_height'] ) ) {
	$sf_6_height = $slider['sf_6_height'];
} else {
	$sf_6_height = '100%';
}
if ( isset( $slider['sf_6_auto_play'] ) ) {
	$sf_6_auto_play = $slider['sf_6_auto_play'];
} else {
	$sf_6_auto_play = 'true';
}
if ( isset( $slider['sf_6_sorting'] ) ) {
	$sf_6_sorting = $slider['sf_6_sorting'];
} else {
	$sf_6_sorting = 0;
}

// CSS and JS
wp_enqueue_script( 'jquery' );
wp_enqueue_style( 'sf-6-wipeslider-css' ); // v2.2.1
wp_enqueue_script( 'sf-6-wipeslider-js' );
?>
<script>
jQuery(window).on('load', function(){
	jQuery('.sf-6-<?php echo esc_js( $sf_slider_id ); ?>').wipeSlider({
		auto : <?php echo esc_js( $sf_6_auto_play ); ?>,
	});
});
</script>

<div class="slidesWrap sf-6-<?php echo esc_attr( $sf_slider_id ); ?>">
	<ul class="slides ulslide">
	<?php
	// slide sorting start
	if ( $sf_6_sorting == 1 ) {
		// Slide ID Ascending (key Ascending)
		ksort( $slider['sf_slide_title'] );
	}
	if ( $sf_6_sorting == 2 ) {
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
			<li class="slide">
				<img src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" alt="<?php echo esc_attr( $sf_slide_alt ); ?>">
				
				<?php if ( $sf_slide_title != '' || $sf_slide_descs != '' ) { ?>
				<div class="m_innerBox">
					<?php if ( $sf_slide_title != '' ) { ?>
					<div class="sf-6-slide-title">
						<?php echo esc_html( $sf_slide_title ); ?>
					</div>
					<?php } ?>
					
					<?php if ( $sf_slide_descs != '' ) { ?>
					<div class="sf-6-slide-desc">
						<?php echo esc_html( $sf_slide_descs ); ?>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</li>
			<?php
		}//end of for each
	} //end of count
	?>
	</ul>
</div>
<style>

.slidesWrap .slide{
	width: <?php echo esc_html( $sf_6_width ); ?>;
}
.slidesWrap .slide img{
	width: <?php echo esc_html( $sf_6_width ); ?>;
	height: <?php echo esc_html( $sf_6_height ); ?>;
}
.sf-6-<?php echo esc_html( $sf_slider_id ); ?> {
	width: <?php echo esc_html( $sf_6_width ); ?>;
	height: <?php echo esc_html( $sf_6_height ); ?>;
}

/* FIX 15-JAN-2021 */
.slide .m_innerBox{
	position: absolute;
	bottom: 10px;
	left: 10px;
	right: 10px;
	padding: 10px;
}

/* media queries start */
@media only screen and (max-width: 600px) {
	.slide .m_innerBox{
		position: absolute;
		bottom: 5px !important;
		left: 5px  !important;
		right: 5px  !important;
		padding: 5px  !important;
		font-size: 12px  !important;
	}
}
@media only screen and (max-width: 500px) {
	.slide .m_innerBox{
		position: absolute;
		bottom: 3px !important;
		left: 3px  !important;
		right: 3px  !important;
		padding: 3px  !important;
		font-size: 9px  !important;
	}
}
/* media queries end */
</style>
