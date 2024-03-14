<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/*-----------------------------------------------------------------------------------*/
/*	List Group
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_list_group extends WPBakeryShortCodesContainer {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			//Static
			'mode' => 'borderless-wpbakery-list-group-item-text',
			'appearance' => '',
			'direction' => 'borderless-direction-vertical',
			'alignment' => 'borderless-align-left',
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
		$output .= '<div '.$el_id.' class="borderless-wpbakery-list-group '.$css_class.' '.$direction.' '.$appearance.' '.$alignment.' '.$mode.'">'.wpb_js_remove_wpautop($content).'</div>';
		
		return $output;
	}
}

class WPBakeryShortCode_borderless_wpbakery_list_group_item extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => '',
			'link' => '',
			'icon' => '',
			'colors' => '',
			'icon_color' => '',
			'title_color' => '',
			//Static
			'el_id' => '',
			'el_class' => '',
			'css' => '',
			'css_animation' => ''
		), $atts ) );
		$output = '';
		
		
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
		
		
		// URL Builder
		$link = vc_build_link( $link );
		
		
		// Set custom values
		$icon_color = $icon_color ? 'style=color:'.$icon_color.'' : '';
		$title_color = $title_color ? 'style=color:'.$title_color.'' : '';
		
		// End Custom Colors
		
		// Start Icon
		
		$icon = $icon ? '<i class="'.$icon.'" '.$icon_color.' aria-hidden="true"></i>' : '';
		
		//End Icon
		
		// Start Link		
		if($link['url'] != ''){
			$tag = 'a';
			$href = 'href="'.esc_attr( $link['url'] ).'"';
		} else {
			$tag = 'span';
			$href = '';
		}
		// End Link
		
		$output .= '<'.$tag.' '.$href.' '.$el_id.' class="borderless-wpbakery-list-group-item '.$css_class.'" '.$title_color.'>'.$icon.$title.'</'.$tag.'>';
		
		
		return $output;
	}
}

vc_map( array(
	'name' => __( 'List Group', 'borderless' ),
	'base' => 'borderless_wpbakery_list_group',
	'icon' => plugins_url('../images/list-group.png', __FILE__),
	"as_parent" => array('only' => 'borderless_wpbakery_list_group_item'),
	"content_element" => true,
	"show_settings_on_create" => false,
	"is_container" => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Show a flexible and powerful list', 'borderless' ),
	'params' => array(
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'List Mode', 'borderless' ),
			'param_name' => 'mode',
			'value' => array(
				__( 'Text', 'borderless' ) => 'borderless-wpbakery-list-group-item-text',
				__( 'Link', 'borderless' ) => 'borderless-wpbakery-list-group-item-link',
			),
			'description' => __( 'Choose a mode for list group.', 'borderless' ),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Appearance', 'borderless' ),
			'param_name' => 'appearance',
			'value' => array(
				__( 'No Borders and Separator Lines', 'borderless' ) => '',
				__( 'Separator Lines', 'borderless' ) => 'borderless-separator-lines',
				__( 'Borders and Separator Lines', 'borderless' ) => 'borderless-borders-separator-lines',
				__( 'Borders With Rounded Corners and Separator Lines', 'borderless' ) => 'borderless-borders-rounded-corners-separator-lines',
			),
			'description' => __( 'Choose a appearance for list group.', 'borderless' ),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Direction', 'borderless' ),
			'param_name' => 'direction',
			'value' => array(
				__( 'Vertical', 'borderless' ) => 'borderless-direction-vertical',
				__( 'Horizontal', 'borderless' ) => 'borderless-direction-horizontal',
			),
			'description' => __( 'Choose the direction for list group.', 'borderless' ),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Alignment', 'borderless' ),
			'param_name' => 'alignment',
			'value' => array(
				__( 'Left', 'borderless' ) => 'borderless-align-left',
				__( 'Right', 'borderless' ) => 'borderless-align-right',
				__( 'Center', 'borderless' ) => 'borderless-align-center',
			),
			'description' => __( 'Choose the alignment for list group.', 'borderless' ),
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
		
		vc_map( array(
			"name" => __("List Item", 'borderless'),
			'description' => __( 'Display List Group Item', 'borderless' ),
			"base" => "borderless_wpbakery_list_group_item",
			'icon' => plugins_url('../images/list-item.png', __FILE__),
			"content_element" => true,
			"as_child" => array('only' => 'borderless_wpbakery_list_group'), 
			"params" => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Title', 'borderless' ),
					'param_name' => 'title',
				),
				
				array(
					'type' => 'vc_link',
					'heading' => __( 'URL (Link)', 'borderless' ),
					'param_name' => 'link',
					'description' => __( 'Add link to List Item.', 'borderless' ),
				),
				
				array(
					'type' => 'iconmanager',
					'heading' => __( 'Icon', 'borderless' ),
					'param_name' => 'icon',
					'description' => __( 'Select icon from library.', 'borderless' ),
				),
				
				array(
					'type' => 'dropdown',
					'heading' => __( 'Colors', 'borderless' ),
					'param_name' => 'colors',
					'value' => array(
						__( 'Preset Colors', 'borderless' ) => '',
						__( 'Custom Colors', 'borderless' ) => 'custom',
					),
					'description' => __( 'Choose a color for icons and titles.', 'borderless' ),
				),
				
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Icon Color', 'borderless' ),
					'param_name' => 'icon_color',
					'description' => __( 'Select custom color for icons.', 'borderless' ),
					'dependency' => array(
						'element' => 'colors',
						'value' => array( 'custom' ),
					),
				),
				
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Title Color', 'borderless' ),
					'param_name' => 'title_color',
					'description' => __( 'Select custom color for titles.', 'borderless' ),
					'dependency' => array(
						'element' => 'colors',
						'value' => array( 'custom' ),
					),
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
					)
					) );