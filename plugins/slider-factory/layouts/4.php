<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_4_width'] ) ) {
	$sf_4_width = $slider['sf_4_width'];
} else {
	$sf_4_width = '100%';
}
if ( isset( $slider['sf_4_height'] ) ) {
	$sf_4_height = $slider['sf_4_height'];
} else {
	$sf_4_height = '100%';
}
if ( isset( $slider['sf_4_auto_play'] ) ) {
	$sf_4_auto_play = $slider['sf_4_auto_play'];
} else {
	$sf_4_auto_play = 'true';
}
if ( isset( $slider['sf_4_sorting'] ) ) {
	$sf_4_sorting = $slider['sf_4_sorting'];
} else {
	$sf_4_sorting = 0;
}

// CSS and JS
wp_enqueue_script( 'jquery' );
wp_enqueue_style( 'sf-4-camera-css' ); // v1.0.0
wp_enqueue_script( 'jquery-effects-core' ); // v1.0.0
wp_enqueue_script( 'sf-4-camera-js' ); // v1.0.0
?>
<script>
jQuery( document ).ready(function() {
	// Avoid `console` errors in browsers that lack a console.
	(function () {
		var method;
		var noop = function () {};
		var methods = [
			'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
			'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
			'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
			'timeline', 'timelineEnd', 'timeStamp', 'trace', 'warn'
		];
		var length = methods.length;
		var console = (window.console = window.console || {});

		while (length--) {
			method = methods[length];

			// Only stub undefined methods.
			if (!console[method]) {
				console[method] = noop;
			}
		}
	}());

	jQuery('.sf-4-<?php echo esc_js( $sf_slider_id ); ?>').camera({
		autoAdvance:<?php echo esc_js( $sf_4_auto_play ); ?>,
		portrait: false,
		height: '70%',
	});
});
</script>

<!-- slider start-->
 <div class="sf-4-<?php echo esc_attr( $sf_slider_id ); ?>">
	<?php
		// slide sorting start
	if ( $sf_4_sorting == 1 ) {
		// Slide ID Ascending (key Ascending)
		ksort( $slider['sf_slide_title'] );
	}
	if ( $sf_4_sorting == 2 ) {
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
			$sf_slide_thumbnail_url = wp_get_attachment_image_src( $attachment_id, 'thumbnail', true ); // attachment medium URL
			$sf_slide_full_url      = wp_get_attachment_image_src( $attachment_id, 'full', true ); // attachment medium URL
			$attachment             = get_post( $attachment_id );
			$sf_slide_descs         = $attachment->post_content; // attachment description
			// print_r($sf_slide_full_url);
			?>
				<div data-src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>">
					<img class="sf-4-slide-image" src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" alt="<?php echo esc_attr( $sf_slide_alt ); ?>">
					
				<?php if ( $sf_slide_title != '' || $sf_slide_descs != '' ) { ?>
					<div class="camera_caption sf-4-slide-content">
						<?php if ( $sf_slide_title != '' ) { ?>
						<p class="sf-4-slide-title"><?php echo esc_html( $sf_slide_title ); ?></p>
						<?php } ?>
						<?php if ( $sf_slide_descs != '' ) { ?>
						<p class="sf-4-slide-desc"><?php echo esc_html( $sf_slide_descs ); ?></p>
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
.sf-4-<?php echo esc_html( $sf_slider_id ); ?> {
	width: <?php echo esc_html( $sf_4_width ); ?>;
	height: <?php echo esc_html( $sf_4_height ); ?>;
}

.sf-4-slide-content {
	
}

.sf-4-slide-image {
	width: 100%;
}

.sf-4-slide-title {
	color: #FFF;
	font-size: 22px;
}
.sf-4-slide-desc {
	color: #FFF;
	font-size: 18px;
}

/********* hide slide content on mobile with media query 26-Jan-2021 *********/
@media(max-width:770px){
	.camera_caption > div {
		padding: 5px 10px!important;
	}
	
	.camera_caption p {
		margin: 0 0 5px;
	}
	
	.sf-4-slide-title {
	color: #FFF;
	font-size: 17px;
	}
	
	.sf-4-slide-desc {
		color: #FFF;
		font-size: 13px;
	}
}
@media(max-width:580px){
	.sf-4-slide-desc {
		display: none;
	}
	.sf-4-slide-title {
		color: #FFF;
		font-size: 12px;
		text-align: center;
	}
}
/********* hide slide content on mobile with media query 26-Jan-2021 *********/


/* hide slide content on mobile */
/* media queries start */
/* Extra small devices (phones, 600px and down) */
/*@media only screen and (max-width: 600px) {
	.sf-4-slide-desc {
		display: none;
	}
	.sf-4-slide-title {
		color: #FFF;
		font-size: 15px;
		text-align: center;
	}
}*/
/* media queries end */
</style>
