<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/*-----------------------------------------------------------------------------------*/
/*	Icon Group
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_icon_group extends WPBakeryShortCodesContainer {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'direction' => 'row',
			'justify_content' => 'flex-start',
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
		

		// Output
		$output .= '<div '.$el_id.' class="borderless-wpbakery-icon-group '.$css_class.'" style="justify-content:'.$justify_content.'; flex-direction:'.$direction.'">';
		$output .= wpb_js_remove_wpautop($content);
		$output .= '</div>';
		
		return $output;
	}
}


vc_map( array(
	'name' => __( 'Icon Group', 'borderless' ),
	'base' => 'borderless_wpbakery_icon_group',
	'icon' => plugins_url('../images/icon-group.png', __FILE__),
	"as_parent" => array('only' => 'borderless_wpbakery_icon'),
	"content_element" => true,
	"show_settings_on_create" => false,
	"is_container" => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Add and manage multiple icons', 'borderless' ),
	'params' => array(
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Direction', 'borderless' ),
			'param_name' => 'direction',
			'value' => array(
				__( 'Row', 'borderless' ) => 'row',
				__( 'Row Reverse', 'borderless' ) => 'row-reverse',
				__( 'Column', 'borderless' ) => 'column',
				__( 'Column Reverse', 'borderless' ) => 'column-reverse',
			),
			'description' => __( 'Select the direction icons list.', 'borderless' ),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Justify Content', 'borderless' ),
			'param_name' => 'justify_content',
			'value' => array(
				__( 'Left', 'borderless' ) => 'flex-start',
				__( 'Right', 'borderless' ) => 'flex-end',
				__( 'Center', 'borderless' ) => 'center',
				__( 'Space Around', 'borderless' ) => 'space-around',
				__( 'Space Between', 'borderless' ) => 'space-between',
			),
			'description' => __( 'Select icons alignment.', 'borderless' ),
		),
		
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
	"js_view" => 'VcColumnView'
	) );