<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/*-----------------------------------------------------------------------------------*/
/*	SVG
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_svg extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'svg' => '',
			'alignment' => 'left',
			'height' => 'auto',
			'width' => '100%',
			//Static
			'el_id' => '',
			'el_class' => '',
			'css' => '',
			'css_animation' => ''
		), $atts ) );
		$output = '';

		// Assets.
		wp_enqueue_style(
			'borderless-wpbakery-style',
			BORDERLESS__STYLES . 'wpbakery.min.css', 
			false, 
			BORDERLESS__VERSION
		);


		// Retrieve data from the database.
		$options = get_option( 'borderless' );
		
		
		// Set default values
		$borderless_primary_color = isset( $options['primary_color'] ) ? $options['primary_color'] : '#3379fc'; //Primary Color
		$borderless_secondary_color = isset( $options['secondary_color'] ) ? $options['secondary_color'] : '#3379fc'; //Secondary Color
		$borderless_text_color = isset( $options['text_color'] ) ? $options['text_color'] : ''; //Text Color
		$borderless_accent_color = isset( $options['accent_color'] ) ? $options['accent_color'] : '#3379fc'; //Accent Color
		
		
		$svg_img = wp_get_attachment_image_src( $svg );
		$svg_src = $svg_img[0];
		
		// Default Extra Class, CSS and CSS animation
		$css = isset( $atts['css'] ) ? $atts['css'] : '';
		$el_id = isset( $atts['el_id'] ) ? 'id="' . esc_attr( $el_id ) . '"' : '';
		$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$css_animation_style = ' wpb_animate_when_almost_visible wpb_' . $css_animation;
		}
		$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
		

		// Output
		$output .= '<div '.$el_id.' class="borderless-wpbakery-svg '.$css_class.' '. $alignment.'">
		<div class="borderless-wpbakery-svg-inner" style="height:'.$height.';width:'.$width.';"><img class="borderless-svg-img" src="'.$svg_src.'"/></div>
		</div>';
		
		return $output;
	}
}

return array(
	'name' => __( 'SVG', 'borderless' ),
	'base' => 'borderless_wpbakery_svg',
	'icon' => plugins_url('../images/svg.png', __FILE__),
	'show_settings_on_create' => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Animated or static SVG for your content', 'borderless' ),
	'params' => array(
		array(
			'type' => 'attach_image',
			'heading' => __( 'SVG', 'borderless' ),
			'param_name' => 'svg',
			'description' => __( 'Select a SVG Icon from media library.', 'borderless' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Height', 'borderless' ),
			'param_name' => 'height',
			'description' => __( 'Insert the SVG height.', 'borderless' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Width', 'borderless' ),
			'param_name' => 'width',
			'description' => __( 'Insert the SVG width.', 'borderless' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Alignment', 'borderless' ),
			'param_name' => 'alignment',
			'value' => array(
				__( 'Left', 'borderless' ) => 'borderless-wpbakery-svg-align-left',
				__( 'Right', 'borderless' ) => 'borderless-wpbakery-svg-align-right',
				__( 'Center', 'borderless' ) => 'borderless-wpbakery-svg-align-center',
			),
			'description' => __( 'Select icon alignment.', 'borderless' ),
		),
		
		// Animation
		vc_map_add_css_animation(),
		
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'borderless' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'borderless' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
			),
			
			array(
				'type' => 'textfield',
				'heading' => __( 'Extra class name', 'borderless' ),
				'param_name' => 'el_class',
				'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'borderless' ),
			),
			
			array(
				'type' => 'css_editor',
				'heading' => __( 'CSS box', 'borderless' ),
				'param_name' => 'css',
				'group' => __( 'Design Options', 'borderless' ),
			),
		),
	);
	