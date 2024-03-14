<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_3_width'] ) ) {
	$sf_3_width = $slider['sf_3_width'];
} else {
	$sf_3_width = '100%';
}
if ( isset( $slider['sf_3_height'] ) ) {
	$sf_3_height = $slider['sf_3_height'];
} else {
	$sf_3_height = '500';
}
if ( isset( $slider['sf_3_auto_play'] ) ) {
	$sf_3_auto_play = $slider['sf_3_auto_play'];
} else {
	$sf_3_auto_play = 'true';
}
if ( isset( $slider['sf_3_sorting'] ) ) {
	$sf_3_sorting = $slider['sf_3_sorting'];
} else {
	$sf_3_sorting = 0;
}

// CSS and JS
wp_enqueue_script( 'jquery' );
wp_enqueue_style( 'fontawesome-css' );
wp_enqueue_script( 'sf-3-accordion-carousel-blue-slider-js' );
?>
<script>
jQuery( document ).ready(function() {
	jQuery('.first-sample-<?php echo esc_js( $sf_slider_id ); ?> .slider').blue_slider({
		auto_play: <?php echo esc_js( $sf_3_auto_play ); ?>,						// to auto play slides (default: false)
		prev_arrow: '.first-sample-<?php echo esc_js( $sf_slider_id ); ?> .prev-slide',		// class name of prev arrow button element
		next_arrow: '.first-sample-<?php echo esc_js( $sf_slider_id ); ?> .next-slide',		// class name of next arrow button element
	});
});
</script>

<!-- slider start-->
<div class="sf-3-<?php echo esc_attr( $sf_slider_id ); ?>">
	<div class="first-sample-<?php echo esc_attr( $sf_slider_id ); ?>">
		<div class="slider">
			<?php
			// slide sorting start
			if ( $sf_3_sorting == 1 ) {
				// Slide ID Ascending (key Ascending)
				ksort( $slider['sf_slide_title'] );
			}
			if ( $sf_3_sorting == 2 ) {
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
					<div class="item">
						<img class="sf-3-slide-image" src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" alt="<?php echo esc_attr( $sf_slide_alt ); ?>">
					</div>
					<?php
				}//end of for each
			} //end of count
			?>
		</div>
		<i class="prev-slide "><i class="fa fa-arrow-circle-left"></i></i>
		<i class="next-slide "><i class="fa fa-arrow-circle-right"></i></i>
	</div>
</div>
<!-- slider end-->

<style>
.sf-3-<?php echo esc_html( $sf_slider_id ); ?> {
	width: <?php echo esc_html( $sf_3_width ); ?>;
	height: <?php echo esc_html( $sf_3_height ); ?>px;
}

.sf-3-slide-content {
}

.sf-3-slide-title {
}

.sf-3-slide-desc {
}


/* main CSS start */
.first-sample-<?php echo esc_html( $sf_slider_id ); ?> {
	width: 100%;				/* width of slider */
	height: 100%;				/* height of slider */
	border: 5px solid #1c1c1c;	/* border color/thickness of slider */
	border-radius: 3px;
	position: relative;
	background-color: #1c1c1c	/* background color of slider */
}

.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .slider {
	height: 100%
}

.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .slider .slide-wrapper {
	overflow: hidden;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none
}

.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .slider .slide-tracker .my-fr-current .item::before {
	background-color: rgba(0, 0, 0, 0);
}

.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .slider .item {
	width: 100%;
	height: 100%;
	overflow: hidden;
	position: relative
}

.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .slider .item::before {
	content: '';
	position: absolute;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	background-color: rgba(0, 0, 0, 0.8);
	transition: background-color 1s
}

.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .slider .item img {
	width: 100%;
	height: 100%;
	object-fit: cover;				/* image fit or Zoom 100% */
	object-position: center center
}

.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .prev-slide,
.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .next-slide {
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	font-size:50px;					/* button size in px */
	cursor: pointer;
	color: #888;					/* button color */
	transition: color .3s
}

.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .prev-slide:hover,
.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .next-slide:hover {
	color: #ccc 					/* button hover color */
}

/* position of previous button from slider container (default -70 px) */
.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .prev-slide {
	left: 0px
}

/* position of next button from slider container (default -70 px) */
.first-sample-<?php echo esc_html( $sf_slider_id ); ?> .next-slide {
	right: 0px
}
/* main CSS end */

/* media query CSS start */
@media only screen and (max-width: 600px) {
	.sf-3-<?php echo esc_html( $sf_slider_id ); ?> {
		height: <?php echo ( esc_html( $sf_3_height ) / 2 ); ?>px;
	}
}
/* media query CSS end */
</style>
