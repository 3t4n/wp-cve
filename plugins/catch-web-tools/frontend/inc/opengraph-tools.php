<?php
/**
 * @package Frontend
 * @sub-package Opengraph tools
 */

/**
 * Get the opengraph setting and format output
 * @return [string] [opengraph information]
 */
function catchwebtools_opengraph_display(){
	$og_settings = catchwebtools_get_options( 'catchwebtools_opengraph' );
	$output      = '';

	if( isset( $og_settings['status'] ) &&  $og_settings['status'] ){

		unset( $og_settings['status'] );

		if ( is_home() || is_front_page() ) {
			foreach( $og_settings as $property=>$content ) {
				if ( 'og:default_image' == $property ) {
					$image = isset( $og_settings['og:default_image'] ) ? $og_settings['og:default_image'] : '';

					if ( '' != $image ) {
						$output	.= '<meta property="og:image" content="'. esc_attr( $image ).'"/>'. PHP_EOL;
					}
				} elseif ( '' != $content ) {
					if ( 'custom' == $property ) {
						$output	.= $content . PHP_EOL ;
					} else {
						$output	.= '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '"/>'. PHP_EOL;
					}
				}
			}
		} elseif ( is_category() || is_archive() ) {
			$output	.= '<meta property="og:title" content="'. esc_attr( single_term_title( "", false ) ) .'"/>'. PHP_EOL;
		} elseif ( (is_single() || is_page()) && !is_page('blog') ) {
			//Title
			$get_title   = get_post_meta( get_the_ID(), 'catchwebtools_opengraph_title', true );
			$final_title = ! empty( $get_title ) ? $get_title : the_title_attribute( 'echo=0' ) ;

			// Add title to $output as it is never empty.
			$output	.= '<meta property="og:title" content="' . $final_title . '"/>'. PHP_EOL;

			// URL
			$get_url   = get_post_meta( get_the_ID(), 'catchwebtools_opengraph_url', true );
			$final_url = ! empty( $get_url ) ? $get_url : get_permalink() ;

			// Add url to $output as it is never empty.
			$output	.= '<meta property="og:url" content="'. esc_attr( $final_url ).'"/>'. PHP_EOL;

			//Image
			$get_image = get_post_meta( get_the_ID(), 'catchwebtools_opengraph_image',true );

			if( empty( $get_image ) && '' == $get_image ){
				$get_image = isset( $og_settings['og:default_image'] ) ? $og_settings['og:default_image'] : '';
			}

			if ( !empty( $get_image ) ) {
				$output	.= '<meta property="og:image" content="'. esc_attr( $get_image ).'"/>'. PHP_EOL;
			}

			// Description
			$get_description   = get_post_meta(get_the_ID(),'catchwebtools_opengraph_description',true);
			$final_description = ! empty( $get_description ) ? $get_description :  '' ;

			// Add $final_description to $output as it is never empty.
			$output	.= '<meta property="og:description" content="'. esc_attr( $final_description ).'"/>'. PHP_EOL;

			// Type
			$get_type   = get_post_meta(get_the_ID(),'catchwebtools_opengraph_type',true);

			if ( ! empty( $get_type ) ) {
				$output	.= '<meta property="og:type" content="'. esc_attr( $get_type ).'"/>'. PHP_EOL;
			}

			// Custom
			$get_custom = get_post_meta(get_the_ID(),'catchwebtools_opengraph_custom',true);

			if ( !empty( $get_custom ) ) {
				$output	.= $get_custom;
			}
		}

	}
	return $output;
}

/**
 * Get Open Graph Html Content
 * @return [string] [html attribute for open graph]
 */
function catchwebtools_add_opengraph_namespace() {
	$og_settings = catchwebtools_get_options( 'catchwebtools_opengraph' );
	if( isset( $og_settings['status'] ) && $og_settings['status'] ){
		echo ' prefix="og: http://ogp.me/ns#"' ;
	}
}
add_filter( 'language_attributes', 'catchwebtools_add_opengraph_namespace' );
