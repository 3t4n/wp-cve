<?php
/**
 * Logo Showcase Grid Shortcode
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function lswss_render_logo_showcase_grid( $atts, $content = '', $logo_post = array() ) {

	// Taking some globals
	global $post;

	// Taking some variables and post settings
	$prefix							= LSWSS_META_PREFIX;
	$post_sett						= lswss_get_post_sett( $atts['id'] );
	$atts							= wp_parse_args( $post_sett['grid'], $atts );
	
	$atts['unique'] 				= lswss_get_unique();
	$atts['count']					= 0;
	$atts['design'] 				= ! empty( $atts['design'] )			? $atts['design']			: 'design-1';
	$atts['show_title']				= ( $atts['show_title'] == 'false' )	? false						: true;
	$atts['show_desc']				= ( $atts['show_desc'] == 'true' )		? true						: false;
	$atts['logo_grid'] 				= ! empty( $atts['grid'] )				? $atts['grid']				: 5;
	$atts['min_height'] 			= ! empty( $atts['min_height'] )		? $atts['min_height']		: '';
	$atts['max_height'] 			= ! empty( $atts['max_height'] )		? $atts['max_height']		: 200;
	$atts['slide_to_show_ipad']		= ! empty( $atts['ipad'] )				? $atts['ipad']				: 3;
	$atts['slide_to_show_tablet']	= ! empty( $atts['tablet'] )			? $atts['tablet']			: 2;
	$atts['slide_to_show_mobile']	= ! empty( $atts['mobile'] )			? $atts['mobile']			: 1;

	// CSS Class
	$atts['css_class']	= lswss_sanitize_html_classes( $atts['css_class'] );
	$atts['css_class']	.= ( $atts['show_desc'] || $atts['min_height'] ) ? '' : ' lswssp-default-height';
	
	$atts['res_width_ipad']   	= 100/$atts['slide_to_show_ipad'];
	$atts['res_width_tablet']   = 100/$atts['slide_to_show_tablet'];
	$atts['res_width_mobile']   = 100/$atts['slide_to_show_mobile'];
	
	// WP Query Parameters
	$args = array(
		'post_type'			=> 'attachment',
		'post_status'		=> 'any',
		'orderby'			=> 'post__in',
		'posts_per_page'	=> -1,
		'post__in'			=> $atts['images'],
	);

	// WP Query
	$query 					= new WP_Query( $args );
	$atts['max_num_pages']	= $query->max_num_pages;

	if ( $query->have_posts() ) { ?>

		<style>				
			@media only screen and (min-width:641px) and (max-width: 768px) {
				#lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-columns{width:<?php echo esc_attr( $atts['res_width_ipad'] ); ?>%; clear:none;}
				#lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-columns:nth-child(<?php echo esc_attr( $atts['slide_to_show_ipad'] ); ?>n+1){clear:both;}
			}
			@media only screen and (min-width:481px) and (max-width: 640px) {
				#lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-columns{width:<?php echo esc_attr( $atts['res_width_tablet'] ); ?>%; clear:none;}
				#lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-columns:nth-child(<?php echo esc_attr( $atts['slide_to_show_tablet'] ); ?>n+1){clear:both;}
			}
			@media only screen and (max-width:480px) {
				#lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-columns{width:<?php echo esc_attr( $atts['res_width_mobile'] ); ?>%; clear:none;}
				#lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-columns:nth-child(<?php echo esc_attr( $atts['slide_to_show_mobile'] ); ?>n+1){clear:both;}
			}
			
			<?php 
			if( $atts['min_height'] ) { ?>
				#lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-logo-img-wrap{min-height:<?php echo esc_attr($atts['min_height']);?>px}
			<?php }

			if( $atts['max_height'] ) { ?>
				#lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?> .lswssp-logo-img-wrap .lswssp-logo-img{max-height:<?php echo esc_attr($atts['max_height']); ?>px}
			<?php } ?>
		</style>

		<?php

		// Logo Grid Loop Start
		include( LSWSS_DIR . '/templates/grid/loop-start.php');

		while ( $query->have_posts() ) : $query->the_post();

			$atts['count']++;
			$atts['logo_title']		= $post->post_title;
			$atts['logo_desc']		= $post->post_content;
			$atts['logo_img_url']	= lswss_get_image( $post->ID, 'full');
			$atts['logo_link']		= get_post_meta( $post->ID, $prefix.'attachment_link', true );
			$atts['logo_alt_text']	= get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
			$atts['logo_alt_text']	= ! empty( $atts['logo_alt_text'] ) ? $atts['logo_alt_text'] : $post->post_title;

			// CSS Classes
			$atts['wrp_cls'] = "lswssp-columns lswssp-col-{$atts['logo_grid']} lswssp-post-{$post->ID}";		
			$atts['wrp_cls'] .= ( $atts['count'] % $atts['logo_grid']  == 1 )	? ' lswssp-first'	: '';			
		
			// Include shortcode html file
			include( LSWSS_DIR . '/templates/grid/design.php' );

		endwhile;

		include( LSWSS_DIR . '/templates/grid/loop-end.php');

	} // end of have_post()

	wp_reset_postdata(); // Reset WP Query
}

// Logo Showcase Shortcode
add_action( 'lswss_render_logo_showcase_grid', 'lswss_render_logo_showcase_grid', 10, 3 );