<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/*-----------------------------------------------------------------------------------*/
/*	Icon
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_icon extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'icon' => '',
			'icon_color' => '',
			'custom_icon_color' => '',
			'shape' => '',
			'color_shape' => '',
			'icon_size' => '32px',
			'spacing' => '',
			'icon_alignment' => 'left',
			'link' => '',
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
		$link = vc_build_link( $link );
		$color = ($icon_color == 'custom') ? 'color:'.$custom_icon_color.';' : 'color:'.$borderless_primary_color.';'; //Icon Color
		$font_size_reference = $icon_size;
		$icon_size = $icon_size ? 'font-size:'.$icon_size.';' : ' font-size:4rem;'; //Font Size
		$icon_alignment = $icon_alignment ? 'text-align:'.$icon_alignment.';' : ''; //Icon Alignment
		
		if($shape != '') {

			if($shape == 'rounded' || $shape == 'square' || $shape == 'round') {
			  $color_shape = $color_shape ? 'background-color:'.$color_shape.';' : 'background-color:'.$borderless_primary_color.';'; //Background Color
			} else {
			  $color_shape = $color_shape ? 'border-color:'.$color_shape.';' : 'border-color:'.$borderless_primary_color.';'; //Border Color
			}
  
		} else {
			$color_shape = $default_color_shape = '';
		}
  
		if($spacing != '') {
			$spacing = 'height:'.$spacing.'; width:'.$spacing.';';
		} else {
			$spacing = 'height:calc('.$font_size_reference.' + 2em); width:calc('.$font_size_reference.' + 2em);';
		}
		
		
		// Output
		$output .= '<div '.$el_id.' class="borderless-wpbakery-icon background-shape '.$css_class.'" style="'.$icon_alignment.'">';
		if($link['url'] != ''){$output .= '<a href="'.esc_attr( $link['url'] ).'">';}
		$output .= '<div style="'.$color_shape.''.$spacing.'" class="single-icon '.$shape.'">';
		$output .= '<i class="'.$icon.'" style="'.$color.$icon_size.'" aria-hidden="true"></i>';
		$output .= '</div>';
		if($link['url'] != ''){$output .= '</a>';}
		$output .= '</div>';
		
		return $output;
	}
}

return array(
	'name' => __( 'Icon', 'borderless' ),
	'base' => 'borderless_wpbakery_icon',
	'icon' => plugins_url('../images/icon.png', __FILE__),
	'show_settings_on_create' => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Choose an icon from libraries', 'borderless' ),
	'params' => array(
		array(
			'type' => 'iconmanager',
			'heading' => __( 'Icon', 'borderless' ),
			'param_name' => 'icon',
			'description' => __( 'Select icon from library.', 'borderless' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon color', 'borderless' ),
			'param_name' => 'icon_color',
			'value' => array(
				__( 'Preset Color', 'borderless' ) => '',
				__( 'Custom Color', 'borderless' ) => 'custom',
			),
			'description' => __( 'Select icon color.', 'borderless' ),
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Icon Color', 'borderless' ),
			'param_name' => 'custom_icon_color',
			'description' => __( 'Select custom icon color.', 'borderless' ),
			'dependency' => array(
				'element' => 'icon_color',
				'value' => array( 'custom' ),
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Shape', 'borderless' ),
			'description' => __( 'Select icon shape.', 'borderless' ),
			'param_name' => 'shape',
			'value' => array(
				__( 'None', 'borderless' ) => '',
				__( 'Rounded', 'borderless' ) => 'rounded',
				__( 'Square', 'borderless' ) => 'square',
				__( 'Round', 'borderless' ) => 'round',
				__( 'Outline Rounded', 'borderless' ) => 'outline-rounded',
				__( 'Outline Square', 'borderless' ) => 'outline-square',
				__( 'Outline Round', 'borderless' ) => 'outline-round',
			),
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Color Shape', 'borderless' ),
			'param_name' => 'color_shape',
			'description' => __( 'Select custom shape background color.', 'borderless' ),
			'dependency' => array(
				'element' => 'shape',
				'value' => array( 'rounded','square','round','outline-rounded','outline-square','outline-round',  ),
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Size', 'borderless' ),
			'param_name' => 'icon_size',
			'description' => __( 'Icon size. Default value is 32px.', 'borderless' ),
			'value' => '32px',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Spacing', 'borderless' ),
			'param_name' => 'spacing',
			'description' => __( 'Select icon spacing.', 'borderless' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Gap', 'borderless' ),
			'param_name' => 'gap',
			'description' => __( 'Select icon gap.', 'borderless' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Alignment', 'borderless' ),
			'param_name' => 'icon_alignment',
			'value' => array(
				__( 'Left', 'borderless' ) => 'left',
				__( 'Right', 'borderless' ) => 'right',
				__( 'Center', 'borderless' ) => 'center',
			),
			'description' => __( 'Select icon alignment.', 'borderless' ),
		),
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'borderless' ),
			'param_name' => 'link',
			'description' => __( 'Add link to icon.', 'borderless' ),
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
	