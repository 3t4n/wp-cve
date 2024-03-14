<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_5_width'] ) ) {
	$sf_5_width = $slider['sf_5_width'];
} else {
	$sf_5_width = '500px';
}
if ( isset( $slider['sf_5_height'] ) ) {
	$sf_5_height = $slider['sf_5_height'];
} else {
	$sf_5_height = '400px';
}
if ( isset( $slider['sf_5_auto_play'] ) ) {
	$sf_5_auto_play = $slider['sf_5_auto_play'];
} else {
	$sf_5_auto_play = 'false';
}
if ( isset( $slider['sf_5_sorting'] ) ) {
	$sf_5_sorting = $slider['sf_5_sorting'];
} else {
	$sf_5_sorting = 0;
}

// CSS and JS
wp_enqueue_style( 'sf-5-cover-flow-flipster-slider-css' ); // v2.2.1
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'sf-5-cover-flow-flipster-slider-js' );
?>
<div class="slider-container-<?php echo esc_attr( $sf_slider_id ); ?>">
	<ul class="flip-items">
	<?php
	// slide sorting start
	if ( $sf_5_sorting == 1 ) {
		// Slide ID Ascending (key Ascending)
		ksort( $slider['sf_slide_title'] );
	}
	if ( $sf_5_sorting == 2 ) {
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
			<li >
				<img src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" alt="<?php echo esc_attr( $sf_slide_alt ); ?>">
			</li>
			<?php
		}//end of for each
	} //end of count
	?>
	</ul>
</div>
<script>
jQuery( document ).ready(function() {
	jQuery(".slider-container-<?php echo esc_js( $sf_slider_id ); ?>").flipster({
		<?php if ( $sf_5_auto_play == 'true' ) { ?>
		autoplay: 2000,		// true/false - numbers in milliseconds 
		<?php } ?>
	});
});
</script>
<style>
.slider-container-<?php echo esc_html( $sf_slider_id ); ?> img {
	width : <?php echo esc_html( $sf_5_width ); ?>;
	height : <?php echo esc_html( $sf_5_height ); ?>;
}

/********* FIX FOR VERTICAL SCROLL BAR 19-JAN-2021 *********/
.flipster {
	display: block;
	/* overflow-x: hidden; */
	/* overflow-x: inherit;
	overflow-y: visible; */
	overflow: hidden !important;
	position: relative;
}

/********* FIX FOR small screen media query 25-JAN-2021 *********/
@media only screen and (max-width: 768px) {
	.slider-container-<?php echo esc_html( $sf_slider_id ); ?> img {
		width : 300px;
	}
}
@media only screen and (max-width: 540px) {
	.slider-container-<?php echo esc_html( $sf_slider_id ); ?> img {
		width : 170px;
	}
}
</style>
