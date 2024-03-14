<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_1_width'] ) ) {
	$sf_1_width = $slider['sf_1_width'];
} else {
	$sf_1_width = '100%';
}
if ( isset( $slider['sf_1_height'] ) ) {
	$sf_1_height = $slider['sf_1_height'];
} else {
	$sf_1_height = '100%';
}
if ( isset( $slider['sf_1_auto_play'] ) ) {
	$sf_1_auto_play = $slider['sf_1_auto_play'];
} else {
	$sf_1_auto_play = 'true';
}
if ( isset( $slider['sf_1_sorting'] ) ) {
	$sf_1_sorting = $slider['sf_1_sorting'];
} else {
	$sf_1_sorting = 0;
}

// CSS and JS
wp_enqueue_script( 'jquery' );
wp_enqueue_style( 'sf-1-flickity-css' ); // v2.2.1
wp_enqueue_script( 'sf-1-flickity-pkgd-min-js' );
?>
<script>
jQuery( document ).ready(function() {
	var carousel_<?php echo esc_js( $sf_slider_id ); ?> = jQuery('.carousel-main-<?php echo esc_js( $sf_slider_id ); ?>').flickity({
		<?php if ( $sf_1_auto_play == 'true' ) { ?>
		autoPlay: true, // true/false - numbers in milliseconds 
		<?php } ?>
		lazyLoad: 2,
		//adaptiveHeight: true,
	});
	carousel_<?php echo esc_js( $sf_slider_id ); ?>.flickity('resize');
});
</script>

<!-- slider start-->
<div class="carousel-<?php echo esc_attr( $sf_slider_id ); ?> carousel-main-<?php echo esc_attr( $sf_slider_id ); ?>">
	<?php
	// slide sorting start
	if ( $sf_1_sorting == 1 ) {
		// Slide ID Ascending (key Ascending)
		ksort( $slider['sf_slide_title'] );
	}
	if ( $sf_1_sorting == 2 ) {
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
			<div class="carousel-cell-<?php echo esc_attr( $sf_slider_id ); ?>">
				<img class="sf-1-slide-image" data-flickity-lazyload="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" alt="<?php echo esc_attr( $sf_slide_alt ); ?>" width="100%">
				
				<?php if ( $sf_slide_title != '' || $sf_slide_descs != '' ) { ?>
				<div class="sf-1-slide-content">
					<?php
					if ( $sf_slide_title != '' ) {
						?>
						<div class="sf-1-slide-title"><?php echo esc_html( $sf_slide_title ); ?></div><?php } ?>
					<?php
					if ( $sf_slide_descs != '' ) {
						?>
						<div class="sf-1-slide-desc"><?php echo esc_html( $sf_slide_descs ); ?></div><?php } ?>
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
.carousel-<?php echo esc_html( $sf_slider_id ); ?> {
	height: auto;
	width: <?php echo esc_html( $sf_1_width ); ?>;
	height: <?php echo esc_html( $sf_1_height ); ?>;
	margin-bottom: 40px;
}

.carousel-<?php echo esc_html( $sf_slider_id ); ?> .sf-1-slide-image {
	max-width: 100% !important;
	width: <?php echo esc_html( $sf_1_width ); ?>;
	height: <?php echo esc_html( $sf_1_height ); ?>;
	/*object-fit: cover;*/
}

.sf-1-slide-content {
	position: absolute;
	font-size: 18px;
	color: white;
	pointer-events: none !important;
	width: 100%;
	bottom: 8px;
	text-align: center;
}

.sf-1-slide-title {
	padding: 4px 6px;
}

.sf-1-slide-desc {
	padding: 4px 6px;
}

.flickity-page-dots {
	padding: 0px !important;
}

/* Design Preset 1 */
.carousel-cell-<?php echo esc_html( $sf_slider_id ); ?> {
	width: 100%;
	height: auto;
	margin-right: 10px;
}

/* adaptive height css */
.flickity-viewport {
	transition: height 0.2s;
}

/**** media queries start ****/
@media(max-width:650px){
	.sf-1-slide-content {
		position: absolute;
		font-size: 12px;
		color: white;
		pointer-events: none !important;
		width: 100%;
		bottom: 0px !important;
		text-align: center;
	}

	.sf-1-slide-title {
		padding: 0px 6px;
	}

	.sf-1-slide-desc {
		padding: 0px 6px;
	}
}
@media(max-width:450px){
	.sf-1-slide-content {
		position: absolute;
		font-size: 7px;
		color: white;
		pointer-events: none !important;
		width: 100%;
		bottom: 0px !important;
		text-align: center;
	}

	.sf-1-slide-title {
		padding: 0px 3px;
	}

	.sf-1-slide-desc {
		padding: 0px 3px;
	}
}
/**** media queries end ****/

</style>
