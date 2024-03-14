<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/*-----------------------------------------------------------------------------------*/
/*	Infobox
/*-----------------------------------------------------------------------------------*/

class WPBakeryShortCode_borderless_wpbakery_infobox extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {
		extract( shortcode_atts( array(
			'title' => '',
			'link' => '',
			'icon_display' => '',
			'custom_image_icon' => '',
			'custom_svg_icon' => '',
			'icon' => '',
			'icon_color' => '',
			'custom_icon_color' => '',
			'shape' => '',
			'color_shape' => '',
			'icon_size' => '',
			'icon_spacing' => '',
			'icon_gap' => '0',
			'height' => 'auto',
			'width' => '100px',
			'style' => 'column',
			'alignment' => 'flex-start',
			'animations' => '',
			'animation_delay' => '',
			'animation_speed' => 'slower animated infinite',
			'title_tag' => 'h3',
			'title_size' => '',
			'title_line_height' => '',
			'title_spacing' => '',
			'title_alignment' => '',
			'title_color' => '',	
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

		// Link
		if($link != '') {
			$link = vc_build_link( $link );
			$link_start = '<a href="'.esc_attr( $link['url'] ).'">';
			$link_finish = '</a>';
		} else {
			$link_start = '';
			$link_finish = '';
		}
		
		$title_color = $title_color ? 'color:'.$title_color.';' : 'color:'.$borderless_primary_color.';'; //Title Color
		$title_size = $title_size ? 'font-size:'.$title_size.';' : ''; //Title Size
		$title_line_height = $title_line_height ? 'line-height:'.$title_line_height.';' : ''; //Title Line Height
		$title_spacing = $title_spacing ? 'margin:'.$title_spacing.';' : ''; //Title Spacing
		$title_alignment = $title_alignment ? 'text-align:'.$title_alignment.';' : ''; //Title Alignment		
		$title_content = ''.$link_start.'<'.$title_tag.' style="'.$title_size.$title_line_height.$title_spacing.$title_alignment.$title_color.'">'.$title.'</'.$title_tag.'>'.$link_finish.'';
		
		// Icon
		if ($icon_display == 'image_icon') {
			
			$default_src = vc_asset_url( 'vc/no_image.png' );
			$img = wp_get_attachment_image_src( $custom_image_icon );
			$src = $img[0];
			$custom_src = $src ? esc_attr( $src ) : $default_src;
			
			$icon_content = '<img src="'.$custom_src.'" >';
			
		} elseif ($icon_display == 'svg_icon') {
			
			$default_src = vc_asset_url( 'vc/no_image.png' );
			$img = wp_get_attachment_image_src( $custom_svg_icon );
			$src = $img[0];
			$custom_src = $src ? esc_attr( $src ) : $default_src;
			
			$icon_content = '<div class="borderless-wpbakery-infobox-svg" style="height:'.$height.';width:'.$width.';"><img class="borderless-svg-img" src="'.$custom_src.'" ></div>';
			
		} else {
			
			$iconClass = isset( $icon ) ? esc_attr( $icon ) : 'fa fa-adjust';
			
			$custom_icon_color = $icon_color ? 'color:'.$custom_icon_color.';' : 'color:'.$borderless_primary_color.';'; //Icon Color
			
			$font_size_reference = $icon_size;
			
			if($icon_size != '') {
				$icon_size = 'font-size:'.$icon_size.';';
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
				
				$shape_render_start = '<div class="borderless-wpbakery-infobox-type '.$shape.'" style="'.$color_shape.''.$icon_spacing.'">';
				$shape_render_finish = '</div>';
				
			} else {
				$shape_render_start = $shape_render_finish = '';
			}

			$icon_content = ''.$shape_render_start.'<span style="'.$custom_icon_color.' '.$icon_size.'" class="borderless-wpbakery-infobox-icon-item '.$iconClass.'"></span>'.$shape_render_finish.'';
		}
		
		// Gap
		
		$icon_gap = 'style="margin:'.$icon_gap.';"';
		
		// Style
		
		$style_alignment = 'style="flex-direction:'.$style.'; align-items:'.$alignment.';"';
		
		//Output
		$output .= '<div '.$el_id.' class="borderless-wpbakery-infobox '.$css_class.'" '.$style_alignment.'>';
		$output .= '<div class="borderless-wpbakery-infobox-icon '.$animations.' '.$animation_delay.' '.$animation_speed.'" '.$icon_gap.'>';
		$output .= $icon_content;
		$output .= '</div>';
		$output .= '<div class="borderless-wpbakery-infobox-content">';
		$output .= $title_content;
		$output .= $content;
		$output .= '</div>';
		$output .= '</div>';
		
		return $output;
	}
}

return array(
	'name' => __( 'Infobox', 'borderless' ),
	'base' => 'borderless_wpbakery_infobox',
	'icon' => plugins_url('../images/infobox.png', __FILE__),
	'show_settings_on_create' => true,
	'category' => __( 'Borderless', 'borderless' ),
	'description' => __( 'Create nice looking infoboxes', 'borderless' ),
	'params' => array(
		
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'borderless' ),
			'param_name' => 'title',
			'description' => __( 'Enter the title here.', 'borderless' ),
		),
		
		array(
			'type' => 'vc_link',
			'heading' => __( 'URL (Link)', 'borderless' ),
			'param_name' => 'link',
			'description' => __( 'Add link to infobox title.', 'borderless' ),
		),
		
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Description', 'borderless' ),
			'param_name' => 'content',
			'description' => __( 'Provide the description for this Infobox.', 'borderless' ),
			'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'borderless' ),
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
				'value' => array( 'rounded','square','round','outline-rounded','outline-square','outline-round',  ),
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
			'heading' => __( 'Spacing', 'borderless' ),
			'param_name' => 'icon_spacing',
			'description' => __( 'Select icon spacing. e.g. 16px.', 'borderless' ),
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'icon_manager' ),
			),
			'group' => 'Icon',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Height', 'borderless' ),
			'param_name' => 'height',
			'description' => __( 'Insert the SVG height.', 'borderless' ),
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'svg_icon' ),
			),
			'group' => 'Icon',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Width', 'borderless' ),
			'param_name' => 'width',
			'description' => __( 'Insert the SVG width.', 'borderless' ),
			'dependency' => array(
				'element' => 'icon_display',
				'value' => array( 'svg_icon' ),
			),
			'group' => 'Icon',
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Gap', 'borderless' ),
			'param_name' => 'icon_gap',
			'description' => __( 'Select icon gap. e.g. 16px.', 'borderless' ),
			'group' => 'Icon',
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Style', 'borderless' ),
			'param_name' => 'style',
			'value' => array(
				__( 'Icon at Top', 'borderless' ) => 'column',
				__( 'Icon at Bottom', 'borderless' ) => 'column-reverse',
				__( 'Icon at Left', 'borderless' ) => 'row',
				__( 'Icon at Right', 'borderless' ) => 'row-reverse',
			),
			'description' => __( 'Select icon position. Icon box style will be changed according to the icon position.', 'borderless' ),
			'group' => 'Icon',
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Alignment', 'borderless' ),
			'param_name' => 'alignment',
			'value' => array(
				__( 'Start', 'borderless' ) => 'flex-start',
				__( 'Center', 'borderless' ) => 'center',
				__( 'End', 'borderless' ) => 'flex-end',
			),
			'description' => __( 'Select icon alignment.', 'borderless' ),
			'group' => 'Icon',
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Animations', 'borderless' ),
			'param_name' => 'animations',
			'value' => array(
				__( 'No Animation', 'borderless' ) => '',
				__( 'Bounce', 'borderless' ) => 'bounce',
				__( 'Flash', 'borderless' ) => 'flash',
				__( 'Pulse', 'borderless' ) => 'pulse',
				__( 'Rubber Band', 'borderless' ) => 'rubberBand',
				__( 'Shake', 'borderless' ) => 'shake',
				__( 'Head Shake', 'borderless' ) => 'headShake',
				__( 'Swing', 'borderless' ) => 'swing',
				__( 'Tada', 'borderless' ) => 'tada',
				__( 'Wobble', 'borderless' ) => 'wobble',
				__( 'Jello', 'borderless' ) => 'jello',
				__( 'Bounce In', 'borderless' ) => 'bounceIn',
				__( 'Bounce In Down', 'borderless' ) => 'bounceInDown',
				__( 'Bounce In Left', 'borderless' ) => 'bounceInLeft',
				__( 'Bounce In Right', 'borderless' ) => 'bounceInRight',
				__( 'Bounce In Up', 'borderless' ) => 'bounceInUp',
				__( 'Bounce Out', 'borderless' ) => 'bounceOut',
				__( 'Bounce Out Down', 'borderless' ) => 'bounceOutDown',
				__( 'Bounce Out Left', 'borderless' ) => 'bounceOutLeft',
				__( 'Bounce Out Right', 'borderless' ) => 'bounceOutRight',
				__( 'Bounce Out Up', 'borderless' ) => 'bounceOutUp',
				__( 'Fade In', 'borderless' ) => 'fadeIn',
				__( 'Fade In Down', 'borderless' ) => 'fadeInDown',
				__( 'Fade In Down Big', 'borderless' ) => 'fadeInDownBig',
				__( 'Fade In Left', 'borderless' ) => 'fadeInLeft',
				__( 'Fade In Left Big', 'borderless' ) => 'fadeInLeftBig',
				__( 'Fade In Right', 'borderless' ) => 'fadeInRight',
				__( 'Fade In Right Big', 'borderless' ) => 'fadeInRightBig',
				__( 'Fade In Up', 'borderless' ) => 'fadeInUp',
				__( 'Fade In Up Big', 'borderless' ) => 'fadeInUpBig',
				__( 'Fade Out', 'borderless' ) => 'fadeOut',
				__( 'Fade Out Down', 'borderless' ) => 'fadeOutDown',
				__( 'Fade Out Down Big', 'borderless' ) => 'fadeOutDownBig',
				__( 'Fade Out Left', 'borderless' ) => 'fadeOutLeft',
				__( 'Fade Out Left Big', 'borderless' ) => 'fadeOutLeftBig',
				__( 'Fade Out Right', 'borderless' ) => 'fadeOutRight',
				__( 'Fade Out Right Big', 'borderless' ) => 'fadeOutRightBig',
				__( 'Fade Out Up', 'borderless' ) => 'fadeOutUp',
				__( 'Fade Out Up Big', 'borderless' ) => 'fadeOutUpBig',
				__( 'Flip In X', 'borderless' ) => 'flipInX',
				__( 'Flip In Y', 'borderless' ) => 'flipInY',
				__( 'Flip Out X', 'borderless' ) => 'flipOutX',
				__( 'Flip Out Y', 'borderless' ) => 'flipOutY',
				__( 'Light Speed In', 'borderless' ) => 'lightSpeedIn',
				__( 'Light Speed Out', 'borderless' ) => 'lightSpeedOut',
				__( 'Rotate In', 'borderless' ) => 'rotateIn',
				__( 'Rotate In Down Left', 'borderless' ) => 'rotateInDownLeft',
				__( 'Rotate In Down Right', 'borderless' ) => 'rotateInDownRight',
				__( 'Rotate In Up Left', 'borderless' ) => 'rotateInUpLeft',
				__( 'Rotate In Up Right', 'borderless' ) => 'rotateInUpRight',
				__( 'Rotate Out', 'borderless' ) => 'rotateOut',
				__( 'Rotate Out Down Left', 'borderless' ) => 'rotateOutDownLeft',
				__( 'Rotate Out Down Right', 'borderless' ) => 'rotateOutDownRight',
				__( 'Rotate Out Up Left', 'borderless' ) => 'rotateOutUpLeft',
				__( 'Rotate Out Up Right', 'borderless' ) => 'rotateOutUpRight',
				__( 'Hinge', 'borderless' ) => 'hinge',
				__( 'Jack In The Box', 'borderless' ) => 'jackInTheBox',
				__( 'Roll In', 'borderless' ) => 'rollIn',
				__( 'Roll Out', 'borderless' ) => 'rollOut',
				__( 'Zoom In', 'borderless' ) => 'zoomIn',
				__( 'Zoom In Down', 'borderless' ) => 'zoomInDown',
				__( 'Zoom In Left', 'borderless' ) => 'zoomInLeft',
				__( 'Zoom In Right', 'borderless' ) => 'zoomInRight',
				__( 'Zoom In Up', 'borderless' ) => 'zoomInUp',
				__( 'Zoom Out', 'borderless' ) => 'zoomOut',
				__( 'Zoom Out Down', 'borderless' ) => 'zoomOutDown',
				__( 'Zoom Out Left', 'borderless' ) => 'zoomOutLeft',
				__( 'Zoom Out Right', 'borderless' ) => 'zoomOutRight',
				__( 'Zoom Out Up', 'borderless' ) => 'zoomOutUp',
				__( 'Slide In Down', 'borderless' ) => 'slideInDown',
				__( 'Slide In Left', 'borderless' ) => 'slideInLeft',
				__( 'Slide In Right', 'borderless' ) => 'slideInRight',
				__( 'Slide In Up', 'borderless' ) => 'slideInUp',
				__( 'Slide Out Down', 'borderless' ) => 'slideOutDown',
				__( 'Slide Out Left', 'borderless' ) => 'slideOutLeft',
				__( 'Slide Out Right', 'borderless' ) => 'slideOutRight',
				__( 'Slide Out Up', 'borderless' ) => 'slideOutUp',
				__( 'Heart Beat', 'borderless' ) => 'heartBeat',
				
			),
			'description' => __( 'Select the type of animation you want on hover.', 'borderless' ),
			'group' => 'Icon',
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Animation Delay', 'borderless' ),
			'param_name' => 'animation_delay',
			'value' => array(
				__( 'No Delay', 'borderless' ) => '',
				__( 'Delay 1 second', 'borderless' ) => 'delay-1s',
				__( 'Delay 2 seconds', 'borderless' ) => 'delay-2s',
				__( 'Delay 3 seconds', 'borderless' ) => 'delay-3s',
				__( 'Delay 4 seconds', 'borderless' ) => 'delay-4s',
				__( 'Delay 5 seconds', 'borderless' ) => 'delay-5s',
				
			),
			'dependency' => array(
				'element' => 'animations',
				'value' => array( 'bounce','flash','pulse','rubberBand','shake','headShake','swing','tada','wobble','jello','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight','bounceOutUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp','fadeOutUpBig','flipInX','flipInY','flipOutX','flipOutY','lightSpeedIn','lightSpeedOut','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','hinge','jackInTheBox','rollIn','rollOut','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','zoomOut','zoomOutDown','zoomOutLeft','zoomOutRight','zoomOutUp','slideInDown','slideInLeft','slideInRight','slideInUp','slideOutDown','slideOutLeft','slideOutRight','slideOutUp','heartBeat' ),
			),
			'description' => __( 'Select delay for animation.', 'borderless' ),
			'group' => 'Icon',
		),
		
		array(
			'type' => 'dropdown',
			'heading' => __( 'Animation Speed', 'borderless' ),
			'param_name' => 'animation_speed',
			'value' => array(
				__( 'Slower - 3s', 'borderless' ) => 'slower animated infinite',
				__( 'Slow - 2s', 'borderless' ) => 'slow animated infinite',
				__( 'Fast - 800ms', 'borderless' ) => 'fast animated infinite',
				__( 'Faster - 500ms', 'borderless' ) => 'faster animated infinite',
				
			),
			'dependency' => array(
				'element' => 'animations',
				'value' => array( 'bounce','flash','pulse','rubberBand','shake','headShake','swing','tada','wobble','jello','bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight','bounceOutUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp','fadeOutUpBig','flipInX','flipInY','flipOutX','flipOutY','lightSpeedIn','lightSpeedOut','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','hinge','jackInTheBox','rollIn','rollOut','zoomIn','zoomInDown','zoomInLeft','zoomInRight','zoomInUp','zoomOut','zoomOutDown','zoomOutLeft','zoomOutRight','zoomOutUp','slideInDown','slideInLeft','slideInRight','slideInUp','slideOutDown','slideOutLeft','slideOutRight','slideOutUp','heartBeat' ),
			),
			'description' => __( 'Select Speed for animation.', 'borderless' ),
			'group' => 'Icon',
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
			'default' => 'h3',
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
	