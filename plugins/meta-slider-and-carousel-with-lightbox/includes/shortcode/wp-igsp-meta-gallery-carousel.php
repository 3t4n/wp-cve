<?php
/**
 * 'meta_gallery_carousel' Shortcode
 * 
 * @package Meta slider and carousel with lightbox
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function msacwl_gallery_carousel( $atts, $content ) {

	// Taking some globals
	global $post;

	// SiteOrigin Page Builder Gutenberg Block Tweak - Do not Display Preview
	if( isset( $_POST['action'] ) && ( $_POST['action'] == 'so_panels_layout_block_preview' || $_POST['action'] == 'so_panels_builder_content_json' ) ) {
		return '[meta_gallery_carousel]';
	}

	// Divi Frontend Builder - Do not Display Preview
	if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_POST['is_fb_preview'] ) && isset( $_POST['shortcode'] ) ) {
		return '<div class="wp-igsp-builder-shrt-prev">
					<div class="wp-igsp-builder-shrt-title"><span>'.esc_html__('Gallery Carousel - Shortcode', 'meta-slider-and-carousel-with-lightbox').'</span></div>
					meta_gallery_carousel
				</div>';
	}

	// Fusion Builder Live Editor - Do not Display Preview
	if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) || ( isset( $_POST['action'] ) && $_POST['action'] == 'get_shortcode_render' )) ) {
		return '<div class="wp-igsp-builder-shrt-prev">
					<div class="wp-igsp-builder-shrt-title"><span>'.esc_html__('Gallery Carousel - Shortcode', 'meta-slider-and-carousel-with-lightbox').'</span></div>
					meta_gallery_carousel
				</div>';
	}

	extract( shortcode_atts(array(
		'id'				=> '',
		'slide_to_show'		=> 2,
		'slide_to_scroll'	=> 1,
		'autoplay'			=> 'true',
		'autoplay_speed'	=> 3000,
		'speed'				=> 300,
		'arrows'			=> 'true',
		'dots'				=> 'true',
		'show_title'		=> 'true',
		'show_caption'		=> 'true',
		'slider_height'		=> '',
		'lazyload'			=> '',
		'extra_class'		=> '',
		'className'			=> '',
		'align'				=> '',
	), $atts, 'meta_gallery_carousel') );

	// Taking some variables
	$unique 			= wp_igsp_get_unique();
	$slide_to_show		= wp_igsp_clean_number( $slide_to_show, 2 );
	$slide_to_scroll	= wp_igsp_clean_number( $slide_to_scroll, 1 );
	$autoplay_speed		= wp_igsp_clean_number( $autoplay_speed, 3000 );
	$speed				= wp_igsp_clean_number( $speed, 300 );
	$slider_height		= wp_igsp_clean_number( $slider_height, '' );
	$slider_height		= ( ! empty( $slider_height ) )	? "style='height:{$slider_height}px;'" : '';
	$gallery_id			= ! empty( $id )				? $id		: $post->ID;
	$show_caption		= ( $show_caption == 'true' )	? true		: false;
	$show_title			= ( $show_title == 'true' )		? true		: false;
	$arrows				= ( $arrows == 'false' )		? 'false'	: 'true';
	$dots				= ( $dots == 'false' )			? 'false'	: 'true';
	$autoplay			= ( $autoplay == 'false' )		? 'false'	: 'true';
	$lazyload			= ( $lazyload == 'ondemand' || $lazyload == 'progressive' ) ? $lazyload : ''; // ondemand or progressive
	$align				= ! empty( $align )				? "align{$align}"			: '';
	$extra_class		= $extra_class .' '. $align .' '. $className;
	$extra_class		= wp_igsp_get_sanitize_html_classes( $extra_class );

	// Getting gallery post status and type
	$gallery_post = get_post( $gallery_id );

	// Return if post is not exist or status is not publish
	if( empty( $gallery_post ) || ($gallery_post && ( $gallery_post->post_status != 'publish' )) ) {
		return $content;
	}

	// Enqueue required script
	wp_enqueue_script( 'wpos-magnific-script' );
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wp-igsp-public-js' );

	// carousel configuration
	$slider_conf = compact('slide_to_show', 'slide_to_scroll', 'autoplay', 'autoplay_speed', 'speed', 'arrows','dots', 'lazyload');

	// Getting gallery images
	$images	= get_post_meta( $gallery_id, '_vdw_gallery_id', true );
	$count	= 1;
	ob_start();

	if( $images ): ?>
		<div class="msacwl-carousel-wrap msacwl-row-clearfix <?php echo esc_attr( $extra_class ); ?>" data-conf="<?php echo htmlspecialchars( json_encode( $slider_conf ) ); ?>">
			<div id="msacwl-carousel-<?php echo esc_attr( $unique ); ?>" class="msacwl-carousel msacwl-common-slider msacwl-slider-popup">
				<?php foreach( $images as $image ):

					// Taking some variables
					$post_meta_data			= get_post( $image );
					$image_alt_text			= get_post_meta( $image, '_wp_attachment_image_alt', true );
					$gallery_img_src		= wp_igsp_get_image_src( $image, 'full' );
					$gallery_slider_img_src	= $gallery_img_src;

					if ( $lazyload ) {
						$gallery_slider_img_src = WP_IGSP_URL.'assets/images/spacer.gif';
					}
				?>
				<div class="msacwl-carousel-slide msacwl-slide" data-item-index="<?php echo esc_attr( $count ); ?>" <?php echo $slider_height; ?>>

					<a class="msacwl-img-link" href="javascript:void(0);" data-mfp-src="<?php echo esc_url( $gallery_img_src ); ?>">
						<img class="msacwl-img" src="<?php echo esc_url( $gallery_slider_img_src ); ?>" <?php if( $lazyload ) { ?>data-lazy="<?php echo esc_url( $gallery_img_src ); ?>"<?php } ?> data-title="<?php echo esc_attr( $post_meta_data->post_title ); ?>" alt="<?php echo esc_attr( $image_alt_text ); ?>" />
					</a>

					<?php if( $show_title || $show_caption ) { ?>
						<div class="msacwl-gallery-caption">
							<?php if( $show_title ) { ?>
								<span class="image_title"><?php echo wp_kses_post( $post_meta_data->post_title ); ?></span>
							<?php } if( $show_caption ) { ?>
								<span><?php echo wp_kses_post( $post_meta_data->post_excerpt ); ?></span>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
				<?php $count++; // Increment loop count
				endforeach; ?>
			</div>
		</div>
	<?php endif;

	$content .= ob_get_clean();
	return $content;
}

// 'meta_gallery_carousel' Shortcode
add_shortcode( 'meta_gallery_carousel', 'msacwl_gallery_carousel' );