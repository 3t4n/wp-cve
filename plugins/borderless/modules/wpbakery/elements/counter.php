<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/*-----------------------------------------------------------------------------------*/
/*	Counter
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_counter extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => '',
			'value' => '999',
			'value_speed' => '2000',
			'value_interval' => '1',
			'checkicon' => '',
			'icon' => '',
			'title_tag' => 'h3',
			'title_size' => '1.3rem',
			'title_line_height' => '1em',
			'title_color' => '',
			'counter_size' => '4rem',
			'counter_line_height' => '1em',
			'counter_color' => '#818b92',
			'icon_size' => '4rem',
			'icon_line_height' => '2em',
			'icon_color' => '',
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
		wp_enqueue_script(
			'borderless-appear-script',
			BORDERLESS__LIB . 'appear.js', array('jquery'), 
			'1.0.0', 
			true 
		);
		wp_enqueue_script(
			'borderless-countto-script',
			BORDERLESS__LIB . 'countto.js', array('jquery'), 
			'1.2.0', 
			true 
		);
		wp_enqueue_script(
			'borderless-wpbakery-script',
			BORDERLESS__SCRIPTS . 'borderless-wpbakery.min.js', array('jquery'), 
			BORDERLESS__VERSION, 
			true 
		);


		// Retrieve data from the database.
		$options = get_option( 'borderless' );


		// Set default values
		$borderless_primary_color = isset( $options['primary_color'] ) ? $options['primary_color'] : '#3379fc'; //Primary Color
		$borderless_secondary_color = isset( $options['secondary_color'] ) ? $options['secondary_color'] : '#3379fc'; //Secondary Color
		$borderless_text_color = isset( $options['text_color'] ) ? $options['text_color'] : ''; //Text Color
		$borderless_accent_color = isset( $options['accent_color'] ) ? $options['accent_color'] : '#3379fc'; //Accent Color		
	

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


		// Set custom values
		$title_color = $title_color ?: $borderless_primary_color; //Title Color
		$icon_color = $icon_color ?: $borderless_primary_color; //Icon Color 
		
		
		// Output
		$output .= '<div '.$el_id.' class="borderless-wpbakery-counter text-center '.$css_class.'">';
		if($checkicon == 'icon') {
			$output .= '<div class="borderless-wpbakery-counter-icon"><i style="font-size:'.$icon_size.'; line-height:'.$icon_line_height.'; color:'.$icon_color.';" class="'.$icon.'"></i></div>';
		} 
		$output .= '<div class="borderless-wpbakery-counter-paramns" style="font-size:'.$counter_size.'; line-height:'.$counter_line_height.'; color:'.$counter_color.';" value="'.$value.'" value-speed="'.$value_speed.'" value-interval="'.$value_interval.'"></div>';
		if($title != ''){ 
			$output .= '<'.$title_tag.' style="font-size:'.$title_size.'; line-height:'.$title_line_height.'; color:'.$title_color.';" class="borderless-wpbakery-counter-title">'.$title.'</'.$title_tag.'>'; 
		}
		$output .= '</div>';
		
		return $output;
	}
}

return array(
	'name' => __( 'Counter', 'borderless' ),
	'base' => 'borderless_wpbakery_counter',
	'icon' => plugins_url('../images/counter.png', __FILE__),
	'show_settings_on_create' => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Your milestones and achievements', 'borderless' ),
	'params' => array(
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'borderless' ),
			'param_name' => 'title',
			'description' => __( 'Enter the title here.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Counter Value', 'borderless' ),
			'param_name' => 'value',
			'description' => __( 'Enter number for counter without any special character.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Counter Value Speed', 'borderless' ),
			'param_name' => 'value_speed',
			'description' => __( 'Enter number for counter without any special character.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Counter Value Interval', 'borderless' ),
			'param_name' => 'value_interval',
			'description' => __( 'Enter number for counter without any special character.', 'borderless' ),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon library', 'borderless' ),
			'param_name' => 'checkicon',
			'value' => array(
				__( 'No', 'borderless' ) => '',
				__( 'Yes', 'borderless' ) => 'icon',
			),
			'description' => __( 'Enable Icon Library.', 'borderless' ),
		),
		
		array(
			'type' => 'iconmanager',
			'heading' => __( 'Icon', 'borderless' ),
			'param_name' => 'icon',
			'description' => __( 'Select icon from library.', 'borderless' ),
			'dependency' => array(
				'element' => 'checkicon',
				'value' => array( 'icon' ),
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Title Tag', 'borderless' ),
			'param_name' => 'title_tag',
			'group' => 'Typography',
			'value' => array(
				__( 'H1', 'borderless' ) => 'h1',
				__( 'H2', 'borderless' ) => 'h2',
				__( 'H3', 'borderless' ) => 'h3',
				__( 'H4', 'borderless' ) => 'h4',
				__( 'H5', 'borderless' ) => 'h5',
				__( 'H6', 'borderless' ) => 'h6',
				__( 'p', 'borderless' ) => 'p',
				__( 'div', 'borderless' ) => 'div',
			),
			'description' => __( 'Select title tag.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Title Font Size', 'borderless' ),
			'param_name' => 'title_size',
			'description' => __( 'Enter font size.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Title Line Height', 'borderless' ),
			'param_name' => 'title_line_height',
			'description' => __( 'Enter line height.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Title Color', 'borderless' ),
			'param_name' => 'title_color',
			'description' => __( 'Select custom color for the title.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Counter Size', 'borderless' ),
			'param_name' => 'counter_size',
			'description' => __( 'Enter font size.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Counter Line Height', 'borderless' ),
			'param_name' => 'counter_line_height',
			'description' => __( 'Enter line height.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Counter Color', 'borderless' ),
			'param_name' => 'counter_color',
			'description' => __( 'Select custom color for the number.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Icon Size', 'borderless' ),
			'param_name' => 'icon_size',
			'description' => __( 'Enter font size.', 'borderless' ),
			'group' => 'Typography',
			'dependency' => array(
				'element' => 'checkicon',
				'value' => array( 'icon' ),
			),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Icon Line Height', 'borderless' ),
			'param_name' => 'icon_line_height',
			'description' => __( 'Enter line height.', 'borderless' ),
			'group' => 'Typography',
			'dependency' => array(
				'element' => 'checkicon',
				'value' => array( 'icon' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Icon Color', 'borderless' ),
			'param_name' => 'icon_color',
			'description' => __( 'Select custom color for the icon.', 'borderless' ),
			'group' => 'Typography',
			'dependency' => array(
				'element' => 'checkicon',
				'value' => array( 'icon' ),
			),
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
