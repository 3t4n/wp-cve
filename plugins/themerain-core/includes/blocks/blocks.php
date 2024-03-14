<?php

/**
 * Register ThemeRain Slider block
 */
function themerain_block_slider() {
	wp_register_style( 'trc-blocks', TRC_ASSETS_URL . '/css/blocks.css' );
	wp_register_script( 'trc-blocks', TRC_ASSETS_URL . '/js/blocks.js', array( 'wp-blocks', 'wp-block-editor', 'wp-components' ) );

	register_block_type( 'themerain/slider', array(
		'editor_style'    => 'trc-blocks',
		'editor_script'   => 'trc-blocks',
		'render_callback' => 'trc_block_slider_callback'
	) );
}
add_action( 'init', 'themerain_block_slider' );

/**
 * Render callback for ThemeRain Slider block
 */
function trc_block_slider_callback( $atts ) {
	$atts = wp_parse_args( $atts, [
		'images'   => '',
		'align'    => '',
		'columns'  => 2,
		'space'    => 20,
		'autoplay' => false,
		'loop'     => false,
		'center'   => true,
		'ratio'    => '16/9'
	] );

	$class = 'themerain-block-slider';
	if ( $atts['align'] ) {
		$class .= ' align' . esc_attr( $atts['align'] );
	}
	if ( isset( $atts['className'] ) ) { 
		$class .= ' ' . esc_attr( $atts['className'] ); 
	}

	$slides = '';

	if ( $atts['images'] ) {
		foreach ( $atts['images'] as $image ) {
			$slides .= '<div class="swiper-slide">';
				$slides .= '<div style="--aspect-ratio: ' . esc_attr( $atts['ratio'] ) . ';">';
					$slides .= wp_get_attachment_image( $image['id'], 'full' );
				$slides .= '</div>';
			$slides .= '</div>';
		}
	}

	$data  = ' data-columns="' . esc_attr( $atts['columns'] ) . '"';
	$data .= ' data-space="' . esc_attr( $atts['space'] ) . '"';
	$data .= ' data-autoplay="' . esc_attr( $atts['autoplay'] ) . '"';
	$data .= ' data-loop="' . esc_attr( $atts['loop'] ) . '"';
	$data .= ' data-center="' . esc_attr( $atts['center'] ) . '"';

	$otput = '<div class="' . esc_attr( $class ) . '">';
		$otput .= '<div class="swiper-container"' . $data . '>';
			$otput .= '<div class="swiper-wrapper">';
				$otput .= $slides;
			$otput .= '</div>';
		$otput .= '</div>';
	$otput .= '</div>';

	return $otput;
}
