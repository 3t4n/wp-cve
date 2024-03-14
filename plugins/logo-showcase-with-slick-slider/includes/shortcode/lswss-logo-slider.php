<?php
/**
 * Logo Showcase Slider Shortcode
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function lswss_render_logo_showcase_slider( $atts, $content = '', $logo_post = array() ) {

	// Taking some globals
	global $post;

	// Taking some variables and post settings
	$prefix							= LSWSS_META_PREFIX;
	$post_sett						= lswss_get_post_sett( $atts['id'] );
	$atts							= wp_parse_args( $post_sett['slider'], $atts );

	$atts['unique'] 				= lswss_get_unique();
	$atts['count']					= 0;
	$atts['design'] 				= ! empty( $atts['design'] )			? $atts['design']			: 'design-1';
	$atts['show_title']				= ( $atts['show_title'] == 'false' )	? false						: true;
	$atts['show_desc']				= ( $atts['show_desc'] == 'true' )		? true						: false;
	$atts['slide_to_show'] 			= ! empty( $atts['grid'] )				? $atts['grid']				: 5;
	$atts['arrow']					= ( $atts['arrow'] == 'true' )			? true						: false;
	$atts['dots'] 					= ( $atts['dots'] == 'true' )			? true						: false;
	$atts['autoplay'] 				= ( $atts['autoplay'] == 'true' )		? true						: false;
	$atts['loop'] 					= ( $atts['loop'] == 'true' )			? true						: false;
	$atts['centermode'] 			= ( $atts['centermode'] == 'true' )		? true						: false;
	$atts['center_padding'] 		= ! empty( $atts['center_padding'] )	? $atts['center_padding']	: 0;
	$atts['slides_show'] 			= ! empty( $atts['slides_show'] )		? $atts['slides_show']		: 5;
	$atts['slides_scroll'] 			= ! empty( $atts['slides_scroll'] )		? $atts['slides_scroll']	: 1;
	$atts['speed'] 					= ! empty( $atts['speed'] )				? $atts['speed']			: 800;
	$atts['autoplay_speed'] 		= ! empty( $atts['autoplay_speed'] )	? $atts['autoplay_speed']	: 3000;
	$atts['pause_on_hover'] 		= ( $atts['pause_on_hover'] == 'true' )	? true						: false;
	$atts['min_height'] 			= ! empty( $atts['min_height'] )		? $atts['min_height']		: '';
	$atts['max_height'] 			= ! empty( $atts['max_height'] )		? $atts['max_height']		: 200;
	$atts['slide_to_show_ipad']		= ! empty( $atts['ipad'] )				? $atts['ipad']				: 3;
	$atts['slide_to_show_tablet']	= ! empty( $atts['tablet'] )			? $atts['tablet']			: 2;
	$atts['slide_to_show_mobile']	= ! empty( $atts['mobile'] )			? $atts['mobile']			: 1;
	
	// CSS Class
	$atts['css_class']			= lswss_sanitize_html_classes( $atts['css_class'] );	
	$atts['css_class']			.= ($atts['centermode']) ? ' lswssp-center' : '';
	$atts['css_class']			.= ($atts['show_desc'] || $atts['min_height']) ? '' : ' lswssp-default-height';

	/***** Enqueue Required Scripts Starts *****/
	wp_enqueue_script( 'jquery-slick' );
	wp_enqueue_script( 'lswssp-public-script' );
	lswss_enqueue_script();
	/***** Enqueue Required Scripts Ends *****/

	// WP Query Parameters
	$args = array(
		'post_type'			=> 'attachment',
		'post_status'		=> 'any',
		'orderby'			=> 'post__in',
		'posts_per_page'	=> -1,
		'post__in'			=> $atts['images'],
	);

	// WP Query
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) { ?>		
		
		<style>	
			<?php 
			if( $atts['min_height'] ) { ?>
				#lswssp-logo-carousel-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-logo-img-wrap{min-height:<?php echo esc_attr($atts['min_height']);?>px}
			<?php }

			if( $atts['max_height'] ) { ?>
				#lswssp-logo-carousel-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-logo-img-wrap .lswssp-logo-img{max-height:<?php echo esc_attr($atts['max_height']); ?>px}
			<?php } ?>
		</style>

		<?php include( LSWSS_DIR . '/templates/slider/loop-start.php');

		while ( $query->have_posts() ) : $query->the_post();

			$atts['count']++;
			$atts['logo_title']		= $post->post_title;
			$atts['logo_desc']		= $post->post_content;
			$atts['logo_img_url']	= lswss_get_image( $post->ID, 'full' );
			$atts['logo_link']		= get_post_meta( $post->ID, $prefix.'attachment_link', true );
			$atts['logo_alt_text']	= get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
			$atts['logo_alt_text']	= ! empty( $atts['logo_alt_text'] ) ? $atts['logo_alt_text'] : $post->post_title;

			// CSS Classes
			$atts['wrp_cls'] = "lswssp-post-{$post->ID}";
			
			// Include shortcode html file
			include( LSWSS_DIR . '/templates/slider/design.php' );

		endwhile;
		
		include( LSWSS_DIR . '/templates/slider/loop-end.php');

	} // end of have_post()

	wp_reset_postdata(); // Reset WP Query
}

// Logo Showcase Shortcode
add_action( 'lswss_render_logo_showcase_slider', 'lswss_render_logo_showcase_slider', 10, 3 );