<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/*-----------------------------------------------------------------------------------*/
/*	Progress Bar
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_progress_bar extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => '',
			'percentage' => '',
			'height' => '16px',
			'corner' => 'progress-bar-rounded',
			'colors' => '',
			'title_color' => '',
			'icon_color' => '',
			'percentage_color' => '',
			'bar_color' => '',
			'track_color' => '',
			'checkicon' => '',
			'icon' => '',
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
			'borderless-wpbakery-appear-script',
			BORDERLESS__LIB . 'appear.js', array('jquery'), 
			'1.0.0', 
			true 
		);
		wp_enqueue_script(
			'borderless-wpbakery-progressbar-script',
			BORDERLESS__LIB . 'progressbar.js', array('jquery'), 
			'1.1.0', 
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


		// Start Custom Colors		
		$title_color = $title_color ? 'style=color:'.$title_color.'' : 'style=color:'.$borderless_primary_color.'';
		
		$icon_color = $icon_color ? 'style=color:'.$icon_color.'' : 'style=color:'.$borderless_primary_color.'';
		
		$bar_color = $bar_color ? $bar_color : $borderless_primary_color;

		$track_color = $track_color ? $track_color : '#f9f9f9';	
		// End Custom Colors
		
		$height = $height ? 'style="height:'.$height.';"' : '';
		
		if ($checkicon=="custom_icon") { $icon = '<i class="borderless-wpbakery-progress-bar-icon '.$icon.'" '.$icon_color.'></i>'; } else { $icon = ""; }
		
		// Start Output
		
		$output .= '<div '.$el_id.' class="borderless-wpbakery-progress-bar '.$css_class.'">';
		$output .= $icon;
		$output .= '<div class="borderless-wpbakery-progress-bar-inner">';
		$output .= '<span '.$title_color.' class="progress-bar-title">'.$title.'</span>';
		$output .= '<div class="borderless-wpbakery-progress-bar-params '.$corner.'" '.$height.' percentage="'.$percentage.'" bar_color="'.$bar_color.'" track_color="'.$track_color.'" percentage_color="'.$percentage_color.'"></div>';
		$output .= '</div></div>';
		
		return $output;
		
		// End Output
	}
}

return array(
	'name' => __( 'Progress Bar', 'borderless' ),
	'base' => 'borderless_wpbakery_progress_bar',
	'icon' => plugins_url('../images/progress-bar.png', __FILE__),
	'show_settings_on_create' => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Animated progress bar', 'borderless' ),
	'params' => array(
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'borderless' ),
			'param_name' => 'title',
			'description' => __( 'Enter the Progress Bar Field title here.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Progress in %', 'borderless' ),
			'param_name' => 'percentage',
			'description' => __( 'Enter a number between 0 and 100', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Height', 'borderless' ),
			'param_name' => 'height',
			'description' => __( 'Enter a value for height. Ex: 16px.', 'borderless' ),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Corner Style', 'borderless' ),
			'description' => __( 'Select style.', 'borderless' ),
			'param_name' => 'corner',
			'value' => array(
				__( 'Rounded', 'borderless' ) => 'progress-bar-rounded',
				__( 'Square', 'borderless' ) => 'progress-bar-square',
				__( 'Round', 'borderless' ) => 'progress-bar-round',
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Colors', 'borderless' ),
			'param_name' => 'colors',
			'value' => array(
				__( 'Preset Color', 'borderless' ) => '',
				__( 'Custom Color', 'borderless' ) => 'custom',
			),
			'description' => __( 'Choose a color for your progress bar here.', 'borderless' ),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Title Color', 'borderless' ),
			'param_name' => 'title_color',
			'description' => __( 'Select custom color for the title.', 'borderless' ),
			'dependency' => array(
				'element' => 'colors',
				'value' => array( 'custom' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Icon Color', 'borderless' ),
			'param_name' => 'icon_color',
			'description' => __( 'Select custom color for icon.', 'borderless' ),
			'dependency' => array(
				'element' => 'colors',
				'value' => array( 'custom' ),
			),
		),

		array(
			'type' => 'colorpicker',
			'heading' => __( 'Percentage Color', 'borderless' ),
			'param_name' => 'percentage_color',
			'description' => __( 'Select custom color for the percentage.', 'borderless' ),
			'dependency' => array(
				'element' => 'colors',
				'value' => array( 'custom' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Bar Color', 'borderless' ),
			'param_name' => 'bar_color',
			'description' => __( 'Select custom color for the bar.', 'borderless' ),
			'dependency' => array(
				'element' => 'colors',
				'value' => array( 'custom' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Track Color', 'borderless' ),
			'param_name' => 'track_color',
			'description' => __( 'Select custom color for the track.', 'borderless' ),
			'dependency' => array(
				'element' => 'colors',
				'value' => array( 'custom' ),
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon library', 'borderless' ),
			'param_name' => 'checkicon',
			'value' => array(
				__( 'No', 'borderless' ) => 'no_icon',
				__( 'Yes', 'borderless' ) => 'custom_icon',
			),
			'description' => __( 'Should an icon be displayed at the left side of the progress bar.', 'borderless' ),
		),
		
		array(
			'type' => 'iconmanager',
			'heading' => __( 'Icon', 'borderless' ),
			'param_name' => 'icon',
			'description' => __( 'Select icon from library.', 'borderless' ),
			'dependency' => array(
				'element' => 'checkicon',
				'value' => 'custom_icon'
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
