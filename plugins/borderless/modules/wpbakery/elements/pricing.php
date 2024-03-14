<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/*-----------------------------------------------------------------------------------*/
/*	Pricing
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_pricing extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => '',
			'price' => '',
			'currency' => '',
			'plan' => '',
			'feature_1' => '',
			'feature_2' => '',
			'feature_3' => '',
			'feature_4' => '',
			'feature_5' => '',
			'feature_6' => '',
			'feature_7' => '',
			'feature_8' => '',
			'feature_9' => '',
			'feature_10' => '',
			'feature_11' => '',
			'feature_12' => '',
			'feature_13' => '',
			'feature_14' => '',
			'feature_15' => '',
			'features_color' => '',
			'features_icon' => 'features-arrow',
			'features_icon_color' => '',
			'features_spacing' => '',
			'features_line_list' => '',
			'features_line_list_color' => '',
			'icon_display' => '',
			'custom_image_icon' => '',
			'custom_svg_icon' => '',
			'icon' => '',
			'icon_color' => '',
			'custom_icon_color' => '',
			'shape' => '',
			'color_shape' => '',
			'icon_size' => '8rem',
			'icon_spacing' => '',
			'icon_alignment' => 'left',
			'button_title' => 'Link Button',
			'button_link' => '#',
			'button_text_color' => '#ffffff',
			'button_background_color' => '',
			'button_shape' => 'rounded',
			'button_size' => 'btn-lg',
			'button_extra_size' => '',
			'button_alignment' => 'text-left',
			'area_1' => 'icon',
			'margin_area_1' => '0',
			'padding_area_1' => '0',
			'background_area_1' => 'transparent',
			'area_2' => 'title',
			'margin_area_2' => '0',
			'padding_area_2' => '0',
			'background_area_2' => '',
			'area_3' => 'sub_heading',
			'margin_area_3' => '0',
			'padding_area_3' => '0',
			'background_area_3' => 'transparent',
			'area_4' => 'price',
			'margin_area_4' => '0',
			'padding_area_4' => '0',
			'background_area_4' => '',
			'area_5' => 'featured_list',
			'margin_area_5' => '0',
			'padding_area_5' => '0',
			'background_area_5' => '',
			'area_6' => 'pricing_button',
			'margin_area_6' => '0',
			'padding_area_6' => '0',
			'background_area_6' => '',
			'title_tag' => 'h3',
			'title_size' => '',
			'title_line_height' => '',
			'title_spacing' => '',
			'title_alignment' => '',
			'title_color' => '',
			'price_size' => '',
			'price_line_height' => '',
			'price_spacing' => 'auto',
			'price_alignment' => '',
			'price_color' => '',
			'currency_size' => '',
			'currency_spacing' => '',
			'shadow_pricing_table' => '',
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
		
		
		// Start Default Extra Class, CSS and CSS animation
		
		$css = isset( $atts['css'] ) ? $atts['css'] : '';
		$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
		
		if ( '' !== $css_animation ) {
			wp_enqueue_script( 'waypoints' );
			$css_animation_style = ' wpb_animate_when_almost_visible wpb_' . $css_animation;
		}
		
		$class_to_filter = vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
		
		// End Default Extra Class, CSS and CSS animation
		
		// Icon
		if ($icon_display == 'image_icon') {
			
			if($icon_alignment != '') {
				$icon_alignment = 'text-align:'.$icon_alignment.';';
			}
			
			$default_src = vc_asset_url( 'vc/no_image.png' );
			$img = wp_get_attachment_image_src( $custom_image_icon );
			$src = $img[0];
			$custom_src = $src ? esc_attr( $src ) : $default_src;
			
			$icon_content = '<div style="'.$icon_alignment.'"><img src="'.$custom_src.'" ></div>';
			
		} elseif ($icon_display == 'svg_icon') {
			
			if($icon_alignment != '') {
				$icon_alignment = 'text-align:'.$icon_alignment.';';
			}
			
			$default_src = vc_asset_url( 'vc/no_image.png' );
			$img = wp_get_attachment_image_src( $custom_svg_icon );
			$src = $img[0];
			$custom_src = $src ? esc_attr( $src ) : $default_src;
			
			$icon_content = '<div class="elvn" style="'.$icon_alignment.'"><img class="borderless-svg-img" src="'.$custom_src.'" ></div>';
			
		} else {
			
			$iconClass = isset( $icon ) ? esc_attr( $icon ) : 'fa fa-adjust';
			
			$custom_icon_color = $icon_color ? 'color:'.$custom_icon_color.';' : 'color:'.$borderless_primary_color.';'; //Icon Color
			
			$font_size_reference = $icon_size;
			
			if($icon_size != '') {
				$icon_size = 'font-size:'.$icon_size.';';
			}
			
			if($icon_alignment != '') {
				$icon_alignment = 'text-align:'.$icon_alignment.';display: block;';
			}
			
			if($shape != '') {
				
				if($shape == 'rounded' || $shape == 'square' || $shape == 'round') {
					$color_shape = $color_shape ? 'background-color:'.$color_shape.';' : 'background-color:'.$borderless_primary_color.';'; //Background Color Shape
				} else {
					$color_shape = $color_shape ? 'border-color:'.$color_shape.';' : 'border-color:'.$borderless_primary_color.';'; //Border Color Shape
				}
				
				if($icon_spacing != '') {
					$icon_spacing = 'height:'.$icon_spacing.'; width:'.$icon_spacing.';';
				} else {
					$icon_spacing = 'height:calc('.$font_size_reference.' + 2em); width:calc('.$font_size_reference.' + 2em);';
				}
				
				$shape_render_start = '<div style="'.$icon_alignment.'"><div class="icon-pricing '.$shape.'" style="'.$color_shape.''.$icon_spacing.'">';
				$shape_render_finish = '</div></div>';
				
			} else {
				$shape_render_start = '';
				$shape_render_finish = '';
			}
			
			$icon_content = ''.$shape_render_start.'<span style="'.$custom_icon_color.' '.$icon_size.' '.$icon_alignment.'" class="vc_icon_element-icon '.$iconClass.'"></span>'.$shape_render_finish.'';
		}
		
		
		
		// Title
		$title_content = '<'.$title_tag.' style="font-size:'.$title_size.';line-height:'.$title_line_height.';margin:'.$title_spacing.';text-align:'.$title_alignment.';color:'.$title_color.';">'.$title.'</'.$title_tag.'>';
		
		//Price
		$price_content = '<p class="price" style="line-height:'.$price_line_height.';margin:'.$price_spacing.';text-align:'.$price_alignment.';color:'.$price_color.';">
		<span class="pricing-currency" style="font-size:'.$currency_size.';margin:'.$currency_spacing.';">'.$currency.'</span><span class="pricing-value" style="font-size:'.$price_size.';">'.$price.'</span>
		<span class="pricing-plan">'.$plan.'</span>
		</p>';
		
		//Sub Heading
		
		$sub_heading_content = $content;
		
		//Featured List
		if($features_icon == 'features-arrow') {
			$features_icon = 'fa-chevron-right';
		} else if($features_icon == 'features-check') {
			$features_icon = 'fa-check';
		} else if($features_icon == 'features-more') {
			$features_icon = 'fa-plus';
		} else {
			$features_icon = 'fa-star';
		}
		
		$featured_list = '';
		
		if($features_icon_color != '') {
			$features_icon_color = 'style="color:'.$features_icon_color.'"';
		} else {
			$features_icon_color = '';
		}
		
		if($features_line_list == 'line_list' ) {
			$features_line_list = 'line-list';
			if($features_line_list_color != '') {
				$features_line_list_color = 'border-bottom-color:'.$features_line_list_color.';';
			} else {
				$features_line_list_color = '';
			}
		} else {
			$features_line_list = '';
			$features_line_list_color = '';
		}
		
		$features_counter = 1;
		
		while( $features_counter <= 15 ){
			if(${'feature_' . $features_counter} != ''){
				$featured_list = $featured_list .'<li class="'.$features_line_list.'" style="color:'.$features_color.';padding:'.$features_spacing.';'.$features_line_list_color.'"><i '.$features_icon_color.' class="fa '.$features_icon.'"></i>'.${'feature_' . $features_counter}.'</li>';
			}
			$features_counter++;
		}
		
		
		$featured_list_content = '<div><ul class="featured-list">'.$featured_list.'</ul></div>';
		
		
		if($shadow_pricing_table != '') {
			$shadow_pricing_table = 'shadow-pricing';
		}
		
		//Button
		
		$link = vc_build_link( $button_link );
		
		if( $button_shape == 'rounded' || $button_shape == 'square' || $button_shape == 'round' ){
			$button_background_color = $button_background_color ? 'background-color:'.$button_background_color.';' : 'background-color:'.$borderless_primary_color.';'; //Background Color
		} else {
			$button_background_color = $button_background_color ? 'border-width: 2px;border-style: solid;border-color:'.$button_background_color.';' : 'border-width: 2px;border-style: solid;border-color:'.$borderless_primary_color.';'; //Border Color
		}
		
		$pricing_button_content = '<div class="'.$button_alignment.'"><a style="'.$button_background_color.' color:'.$button_text_color.';padding:'.$button_extra_size.';" href="'.esc_attr( $link['url'] ).'" class="'.$button_shape.' pricing-button btn '.$button_size.'" role="button">'.$button_title.'</a></div>';
		
		//Layout
		
		$layout = 1;
		
		$output .= '<div class="borderless-wpbakery-pricing '.$css_class.' '.$shadow_pricing_table.'">';
		
		while( $layout <= 6 ){
			if(${'area_' . $layout} != '' && ${'area_' . $layout} != 'disable'){
				
				$data_content = ${'area_' . $layout} . '_content';
				
				$output .= '<div class="'.${'area_' . $layout}.'" style="margin:'.${'margin_area_' . $layout}.';padding:'.${'padding_area_' . $layout}.';background-color:'.${'background_area_' . $layout}.';">'.${$data_content}.'</div>';
				
			}
			$layout++;
		}
		
		$output .= '</div>';
		
		return $output;
	}
}

return array(
	'name' => __( 'Pricing', 'borderless' ),
	'base' => 'borderless_wpbakery_pricing',
	'icon' => plugins_url('../images/pricing.png', __FILE__),
	'show_settings_on_create' => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Create nice looking pricing tables', 'borderless' ),
	'params' => array(
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'borderless' ),
			'param_name' => 'title',
			'description' => __( 'Enter the title here.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Price', 'borderless' ),
			'param_name' => 'price',
			'description' => __( 'Enter the price for this package. e.g. 999.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Currency', 'borderless' ),
			'param_name' => 'currency',
			'description' => __( 'Enter the price unit for this package. e.g. $.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Plan', 'borderless' ),
			'param_name' => 'plan',
			'description' => __( 'Enter the plan for this package. e.g. per month.', 'borderless' ),
		),
		
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Sub Heading', 'borderless' ),
			'param_name' => 'content',
			'description' => __( 'Enter short description.', 'borderless' ),
			'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'borderless' ),
		),
		
		/*
		* Features Tab
		*/
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 1', 'borderless' ),
			'param_name' => 'feature_1',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 2', 'borderless' ),
			'param_name' => 'feature_2',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 3', 'borderless' ),
			'param_name' => 'feature_3',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 4', 'borderless' ),
			'param_name' => 'feature_4',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 5', 'borderless' ),
			'param_name' => 'feature_5',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 6', 'borderless' ),
			'param_name' => 'feature_6',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 7', 'borderless' ),
			'param_name' => 'feature_7',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 8', 'borderless' ),
			'param_name' => 'feature_8',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 9', 'borderless' ),
			'param_name' => 'feature_9',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 10', 'borderless' ),
			'param_name' => 'feature_10',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 11', 'borderless' ),
			'param_name' => 'feature_11',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 12', 'borderless' ),
			'param_name' => 'feature_12',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 13', 'borderless' ),
			'param_name' => 'feature_13',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 14', 'borderless' ),
			'param_name' => 'feature_14',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Feature 15', 'borderless' ),
			'param_name' => 'feature_15',
			'description' => __( 'Enter feature.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Features Color', 'borderless' ),
			'param_name' => 'features_color',
			'description' => __( 'Select custom color.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Features Icon', 'borderless' ),
			'param_name' => 'features_icon',
			'group' => 'Features',
			'value' => array(
				__( 'Arrow', 'borderless' ) => 'features-arrow',
				__( 'Check', 'borderless' ) => 'features-check',
				__( 'More', 'borderless' ) => 'features-more',
				__( 'Star', 'borderless' ) => 'features-star',
			),
			'description' => __( 'Select icon for featured list.', 'borderless' ),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Features Icon Color', 'borderless' ),
			'param_name' => 'features_icon_color',
			'description' => __( 'Select custom icon color.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Features Spacing', 'borderless' ),
			'param_name' => 'features_spacing',
			'description' => __( 'Select features spacing. e.g. 16px.', 'borderless' ),
			'group' => 'Features',
		),
		
		array(
			'type' => 'checkbox',
			'heading' => __('Features Line List', 'borderless'),
			'param_name' => 'features_line_list',
			'group' => 'Features',
			'value' => array(
				__( 'Enable Features Line List.', 'borderless' ) => 'line_list',
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Features Line List Color', 'borderless' ),
			'param_name' => 'features_line_list_color',
			'description' => __( 'Select custom line color.', 'borderless' ),
			'group' => 'Features',
			'dependency' => array(
				'element' => 'features_line_list',
				'value' => array( 'line_list' ),
			),
		),
		
		array(
			'type' => 'checkbox',
			'heading' => __('Shadow Pricing Table', 'borderless'),
			'param_name' => 'shadow_pricing_table',
			'value' => array(
				__( 'Apply shadow on the Pricing table.', 'borderless' ) => 'shadow_pricing',
			),
		),
		
		/*
		* Icon Tab
		*/
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Icon to display', 'borderless' ),
			'param_name' => 'icon_display',
			'value' => array(
				__( 'Icon Manager', 'borderless' ) => 'icon_manager',
				__( 'Image Icon', 'borderless' ) => 'image_icon',
				__( 'SVG Icon', 'borderless' ) => 'svg_icon',
			),
			'description' => __( 'Enable Icon Library.', 'borderless' ),
			'group' => 'Icon',
		),
		
		array(
			'type' => 'attach_image',
			'heading' => __( 'Upload Image Icon', 'borderless' ),
			'param_name' => 'custom_image_icon',
			'description' => __( 'Upload the custom image icon.', 'borderless' ),
			'group' => 'Icon',
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'image_icon' ),
			),
		),
		
		array(
			'type' => 'attach_image',
			'heading' => __( 'Upload SVG Icon', 'borderless' ),
			'param_name' => 'custom_svg_icon',
			'description' => __( 'Upload the custom svg icon.', 'borderless' ),
			'group' => 'Icon',
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'svg_icon' ),
			),
		),
		
		array(
			'type' => 'iconmanager',
			'heading' => __( 'Icon', 'borderless' ),
			'param_name' => 'icon',
			'description' => __( 'Select icon from library.', 'borderless' ),
			'group' => 'Icon',
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'icon_manager' ),
			),
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
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'icon_manager' ),
			),
			'group' => 'Icon',
		),
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Custom Icon Color', 'borderless' ),
			'param_name' => 'custom_icon_color',
			'description' => __( 'Select custom icon color.', 'borderless' ),
			'group' => 'Icon',
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
			'group' => 'Icon',
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'icon_manager' ),
			),
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
			'group' => 'Icon',
			'dependency' => array(
				'element' => 'shape',
				'value' => array( 'rounded','boxed','rounded-less','rounded-outline','boxed-outline','rounded-less-outline'  ),
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Size', 'borderless' ),
			'param_name' => 'icon_size',
			'description' => __( 'Icon size. Default value is 16px.', 'borderless' ),
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'icon_manager' ),
			),
			'group' => 'Icon',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Icon Spacing', 'borderless' ),
			'param_name' => 'icon_spacing',
			'description' => __( 'Select icon spacing. e.g. 16px.', 'borderless' ),
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'icon_manager' ),
			),
			'group' => 'Icon',
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
			'group' => 'Icon',
		),
		
		/*
		* Button Tab
		*/
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'borderless' ),
			'param_name' => 'button_title',
			'description' => __( 'Enter the title here.', 'borderless' ),
			'group' => 'Button',			
		),
		
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'borderless' ),
			'param_name' => 'button_link',
			'description' => __( 'Add link to button.', 'borderless' ),
			'group' => 'Button',
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Text Color', 'borderless' ),
			'param_name' => 'button_text_color',
			'description' => __( 'Select button text color.', 'borderless' ),
			//CURIOSIDADE'param_holder_class' => 'vc_colored-dropdown vc_btn3-colored-dropdown',
			'value' => '#ffffff',
			'group' => 'Button',
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Color', 'borderless' ),
			'param_name' => 'button_background_color',
			'description' => __( 'Select button background color.', 'borderless' ),
			'group' => 'Button',
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Shape', 'borderless' ),
			'description' => __( 'Select button shape.', 'borderless' ),
			'param_name' => 'button_shape',
			'group' => 'Button',
			'value' => array(
				__( 'Rounded', 'borderless' ) => 'rounded',
				__( 'Square', 'borderless' ) => 'square',
				__( 'Round', 'borderless' ) => 'round',
				__( 'Outline Rounded', 'borderless' ) => 'outline-rounded',
				__( 'Outline Square', 'borderless' ) => 'outline-square',
				__( 'Outline Round', 'borderless' ) => 'outline-round',
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Size', 'borderless' ),
			'param_name' => 'button_size',
			'value' => array(
				__( 'Extra Small', 'borderless' ) => 'btn-xs',
				__( 'Small', 'borderless' ) => 'btn-sm',
				__( 'Medium', 'borderless' ) => 'btn-md',
				__( 'Large', 'borderless' ) => 'btn-lg',
				__( 'Block', 'borderless' ) => 'btn-block',
			),
			'description' => __( 'Select button display size.', 'borderless' ),
			'group' => 'Button',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra Size', 'borderless' ),
			'param_name' => 'button_extra_size',
			'description' => __( 'Enter extra size.', 'borderless' ),
			'group' => 'Button',			
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Alignment', 'borderless' ),
			'param_name' => 'button_alignment',
			'value' => array(
				__( 'Left', 'borderless' ) => 'text-left',
				__( 'Right', 'borderless' ) => 'text-right',
				__( 'Center', 'borderless' ) => 'text-center',
			),
			'description' => __( 'Select button alignment.', 'borderless' ),
			'group' => 'Button',
		),
		
		/*
		* Layout Tab
		*/
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'First Area', 'borderless' ),
			'param_name' => 'area_1',
			'group' => 'Layout',
			'value' => array(
				__( 'Icon', 'borderless' ) => 'icon',
				__( 'Title', 'borderless' ) => 'title',
				__( 'Sub Heading', 'borderless' ) => 'sub_heading',
				__( 'Price and Plan', 'borderless' ) => 'price',
				__( 'Featured List', 'borderless' ) => 'featured_list',
				__( 'Button', 'borderless' ) => 'pricing_button',
				__( 'Disable', 'borderless' ) => 'disable',
			),
			'default' => 'icon_image',
			'description' => __( 'Choose the element.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Margin First Area', 'borderless' ),
			'param_name' => 'margin_area_1',
			'description' => __( 'Enter margin. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_1',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Padding First Area', 'borderless' ),
			'param_name' => 'padding_area_1',
			'description' => __( 'Enter padding. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_1',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background First Area', 'borderless' ),
			'param_name' => 'background_area_1',
			'description' => __( 'Select custom background color for first area.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_1',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Second Area', 'borderless' ),
			'param_name' => 'area_2',
			'group' => 'Layout',
			'value' => array(
				__( 'Icon', 'borderless' ) => 'icon',
				__( 'Title', 'borderless' ) => 'title',
				__( 'Sub Heading', 'borderless' ) => 'sub_heading',
				__( 'Price and Plan', 'borderless' ) => 'price',
				__( 'Featured List', 'borderless' ) => 'featured_list',
				__( 'Button', 'borderless' ) => 'pricing_button',
				__( 'Disable', 'borderless' ) => 'disable',
			),
			'default' => 'title',
			'description' => __( 'Choose the element.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Margin Second Area', 'borderless' ),
			'param_name' => 'margin_area_2',
			'description' => __( 'Enter margin. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_2',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Padding Second Area', 'borderless' ),
			'param_name' => 'padding_area_2',
			'description' => __( 'Enter padding. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_2',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Second Area', 'borderless' ),
			'param_name' => 'background_area_2',
			'description' => __( 'Select custom background color for second area.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_2',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Third Area', 'borderless' ),
			'param_name' => 'area_3',
			'group' => 'Layout',
			'value' => array(
				__( 'Icon', 'borderless' ) => 'icon',
				__( 'Title', 'borderless' ) => 'title',
				__( 'Sub Heading', 'borderless' ) => 'sub_heading',
				__( 'Price and Plan', 'borderless' ) => 'price',
				__( 'Featured List', 'borderless' ) => 'featured_list',
				__( 'Button', 'borderless' ) => 'pricing_button',
				__( 'Disable', 'borderless' ) => 'disable',
			),
			'default' => 'value',
			'description' => __( 'Choose the element.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Margin Third Area', 'borderless' ),
			'param_name' => 'margin_area_3',
			'description' => __( 'Enter margin. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_3',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Padding Third Area', 'borderless' ),
			'param_name' => 'padding_area_3',
			'description' => __( 'Enter padding. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_3',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Third Area', 'borderless' ),
			'param_name' => 'background_area_3',
			'description' => __( 'Select custom background color for third area.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_3',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Fourth Area', 'borderless' ),
			'param_name' => 'area_4',
			'group' => 'Layout',
			'value' => array(
				__( 'Icon', 'borderless' ) => 'icon',
				__( 'Title', 'borderless' ) => 'title',
				__( 'Sub Heading', 'borderless' ) => 'sub_heading',
				__( 'Price and Plan', 'borderless' ) => 'price',
				__( 'Featured List', 'borderless' ) => 'featured_list',
				__( 'Button', 'borderless' ) => 'pricing_button',
				__( 'Disable', 'borderless' ) => 'disable',
			),
			'default' => 'featured_list',
			'description' => __( 'Choose the element.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Margin Fourth Area', 'borderless' ),
			'param_name' => 'margin_area_4',
			'description' => __( 'Enter margin. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_4',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Padding Fourth Area', 'borderless' ),
			'param_name' => 'padding_area_4',
			'description' => __( 'Enter padding. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_4',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Fourth Area', 'borderless' ),
			'param_name' => 'background_area_4',
			'description' => __( 'Select custom background color for fourth area.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_4',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Fifth Area', 'borderless' ),
			'param_name' => 'area_5',
			'group' => 'Layout',
			'value' => array(
				__( 'Icon', 'borderless' ) => 'icon',
				__( 'Title', 'borderless' ) => 'title',
				__( 'Sub Heading', 'borderless' ) => 'sub_heading',
				__( 'Price and Plan', 'borderless' ) => 'price',
				__( 'Featured List', 'borderless' ) => 'featured_list',
				__( 'Button', 'borderless' ) => 'pricing_button',
				__( 'Disable', 'borderless' ) => 'disable',
			),
			'default' => 'btn',
			'description' => __( 'Choose the element.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Margin Fifth Area', 'borderless' ),
			'param_name' => 'margin_area_5',
			'description' => __( 'Enter margin. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_5',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Padding Fifth Area', 'borderless' ),
			'param_name' => 'padding_area_5',
			'description' => __( 'Enter padding. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_5',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Fifth Area', 'borderless' ),
			'param_name' => 'background_area_5',
			'description' => __( 'Select custom background color for fifth area.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_5',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Sixth Area', 'borderless' ),
			'param_name' => 'area_6',
			'group' => 'Layout',
			'value' => array(
				__( 'Icon', 'borderless' ) => 'icon',
				__( 'Title', 'borderless' ) => 'title',
				__( 'Sub Heading', 'borderless' ) => 'sub_heading',
				__( 'Price and Plan', 'borderless' ) => 'price',
				__( 'Featured List', 'borderless' ) => 'featured_list',
				__( 'Button', 'borderless' ) => 'pricing_button',
				__( 'Disable', 'borderless' ) => 'disable',
			),
			'default' => 'btn',
			'description' => __( 'Choose the element.', 'borderless' ),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Margin Sixth Area', 'borderless' ),
			'param_name' => 'margin_area_6',
			'description' => __( 'Enter margin. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_6',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Padding Sixth Area', 'borderless' ),
			'param_name' => 'padding_area_6',
			'description' => __( 'Enter padding. e.g. 16px.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_6',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Background Sixth Area', 'borderless' ),
			'param_name' => 'background_area_6',
			'description' => __( 'Select custom background color for sixth area.', 'borderless' ),
			'group' => 'Layout',
			'dependency' => array(
				'element' => 'area_6',
				'value_not_equal_to' => array( '' ),
			),
		),
		
		/*
		* Typography Tab
		*/
		
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
			'type' => 'textfield',
			'heading' => __( 'Title Spacing', 'borderless' ),
			'param_name' => 'title_spacing',
			'description' => __( 'Select title spacing. e.g. 16px.', 'borderless' ),
			'group' => 'Typography',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Title Alignment', 'borderless' ),
			'param_name' => 'title_alignment',
			'value' => array(
				__( 'Left', 'borderless' ) => 'left',
				__( 'Right', 'borderless' ) => 'right',
				__( 'Center', 'borderless' ) => 'center',
			),
			'description' => __( 'Select title alignment.', 'borderless' ),
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
			'heading' => __( 'Price Font Size', 'borderless' ),
			'param_name' => 'price_size',
			'description' => __( 'Enter font size.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Price Line Height', 'borderless' ),
			'param_name' => 'price_line_height',
			'description' => __( 'Enter line height.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Price Spacing', 'borderless' ),
			'param_name' => 'price_spacing',
			'description' => __( 'Select title spacing. e.g. 16px.', 'borderless' ),
			'group' => 'Typography',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Price Alignment', 'borderless' ),
			'param_name' => 'price_alignment',
			'value' => array(
				__( 'Left', 'borderless' ) => 'left',
				__( 'Right', 'borderless' ) => 'right',
				__( 'Center', 'borderless' ) => 'center',
			),
			'description' => __( 'Select price alignment.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'colorpicker',
			'heading' => __( 'Price Color', 'borderless' ),
			'param_name' => 'price_color',
			'description' => __( 'Select custom color for the price.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Currency Font Size', 'borderless' ),
			'param_name' => 'currency_size',
			'description' => __( 'Enter font size.', 'borderless' ),
			'group' => 'Typography',
		),
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Currency Spacing', 'borderless' ),
			'param_name' => 'currency_spacing',
			'description' => __( 'Select spacing. e.g. 16px.', 'borderless' ),
			'group' => 'Typography',
		),
		
		// Animation
		vc_map_add_css_animation(),
		
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
