<?php
/**
 * 
 * @package  Accordion and Accordion Slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function aas_slider_shortcode( $atts, $content = null) {
	
	extract(shortcode_atts(array(
							'id'	=> '',
	), $atts, 'aas_slider'));

	// Taking some globals
	global $post;

	// Taking some variables
	$unique 		= wp_aas_get_unique();
	$gallery_id 	= !empty($id) ? $id	: $post->ID;

	$prefix = WP_AAS_META_PREFIX; // Metabox prefix

	$visible_panels		= get_post_meta( $gallery_id, $prefix.'visible_panels', true );
	$visible_panels 	= ! empty( $visible_panels )	? $visible_panels	: '4';
	
	$width				= get_post_meta( $gallery_id, $prefix.'width', true );
	$width				= ! empty( $width )				? $width			: '900';	
	
	$image_size			= get_post_meta( $gallery_id, $prefix.'image_size', true );
	$image_size			= ! empty( $image_size )		? $image_size		: 'large';
	
	$height				= get_post_meta( $gallery_id, $prefix.'height', true );
	$height				= ! empty( $height )			? $height			: '300';
	
	$orientation		= get_post_meta( $gallery_id, $prefix.'orientation', true );
	$orientation 		= ($orientation == 'horizontal')? 'horizontal'		: 'vertical';
	
	$panel_distance		= get_post_meta( $gallery_id, $prefix.'panel_distance', true );
	$panel_distance		= ! empty( $panel_distance )	? $panel_distance	: '0';
	
	$max_openedaccordion_size	= get_post_meta( $gallery_id, $prefix.'max_openedaccordion_size', true );
	$max_openedaccordion_size	= ! empty( $max_openedaccordion_size )	? $max_openedaccordion_size	: '80%';
	
	$open_panel_on 		= get_post_meta( $gallery_id, $prefix.'open_panel_on', true );
	$open_panel_on 		= ($open_panel_on == 'hover')	? 'hover'	: 'click';
	
	$shadow 			= get_post_meta( $gallery_id, $prefix.'shadow', true );
	$shadow 			= ($shadow == 'true')	? 'true'	: 'false';
	
	$autoplay			= get_post_meta( $gallery_id, $prefix.'autoplay', true );
	$autoplay 			= ($autoplay == 'true')	? 'true'	: 'false';

	$mouse_wheel		= get_post_meta( $gallery_id, $prefix.'mouse_wheel', true );
	$mouse_wheel 		= ($mouse_wheel == 'false')	? 'false'	: 'true';

	// Slider configuration
	$slider_conf = compact('visible_panels', 'width', 'height', 'orientation', 'panel_distance', 'max_openedaccordion_size', 'open_panel_on', 'shadow', 'autoplay', 'mouse_wheel' );

	// Enqueue required script
	wp_enqueue_script( 'wpos-accordion-slider-js' );
	wp_enqueue_script( 'wp-aas-public-js' );

	// Getting gallery images
	$images = get_post_meta($gallery_id, $prefix.'gallery_id', true);

	ob_start();

	if( $images ): ?>

		<div class="wpaas-accordion-wrap wpaas-row-clearfix">
			<div id="wpaas-accordion-<?php echo $unique; ?>" class="wpos-tab-slider">
				<div class="as-wposslides">
					<?php foreach( $images as $image ):
						$post_mata_data		= get_post($image);
						$image_lsider		= wp_get_attachment_image_src( $image, $image_size );
						$image_link			= get_post_meta($image, $prefix.'attachment_link',true);
						$image_title		= $post_mata_data->post_title;
						$image_alt_text		= get_post_meta($image,'_wp_attachment_image_alt',true); ?>
						<div class="as-wposslide">
							<?php if(! empty( $image_link )) { ?>
								<a href="<?php echo esc_url($image_link); ?>" >
									<img class="as-wposbg" src="<?php echo esc_url( $image_lsider[0] ); ?>" alt="<?php echo esc_attr( $image_alt_text ); ?>" />
								</a>
							<?php } else { ?>
								<img class="as-wposbg" src="<?php echo esc_url( $image_lsider[0] ); ?>" alt="<?php echo esc_attr( $image_alt_text ); ?>" />	
							<?php }
							if( ! empty( $image_title )) { ?>
								<div class="as-layer as-closed as-black as-padding"><?php echo esc_html( $image_title ); ?></div>
							<?php } ?>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="wpaas-conf wpaas-hide"><?php echo json_encode( $slider_conf ); ?></div><!-- end of-slider-conf -->
			</div><!-- end .msacwl-carousel -->
		</div><!-- end .msacwl-carousel-wrap -->
	<?php endif;

	wp_reset_postdata();
	$content .= ob_get_clean();
	return $content;
}
add_shortcode("aas_slider", "aas_slider_shortcode");