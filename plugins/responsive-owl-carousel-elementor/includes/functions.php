<?php

use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;

/**
 * Add custom image sizes for different layouts
 */
function owce_image_sizes() {
	add_image_size( 'owl_elementor_thumbnail', 600, 450 );
	add_image_size( 'owl_elementor_team', 350, 450, true );
	add_image_size( 'owl_elementor_testimonial', 100, 100, true );
}

add_action( 'init', 'owce_image_sizes', 999 );

/**
 * Get carousel layouts
 *
 * @return array
 */
function get_carousel_layouts() {
	$base_layouts = [
		'basic'       => __( 'Basic', 'responsive-owl-carousel-elementor' ),
		'image'       => __( 'Image Only', 'responsive-owl-carousel-elementor' ),
		'testimonial' => __( 'Testimonial', 'responsive-owl-carousel-elementor' ),
		'team'        => __( 'Team', 'responsive-owl-carousel-elementor' ),
	];

	return apply_filters( 'owce_layouts', $base_layouts );
}

/**
 * Generating styles for carousel layouts
 *
 * @param string $layout
 *
 * @return array
 */
function get_carousel_layout_styles( $layout = 'basic' ) {
	$styles = [
		'basic'       => [
			'one' => __( 'One', 'responsive-owl-carousel-elementor' )
		],
		'testimonial' => [
			'one'   => __( 'One', 'responsive-owl-carousel-elementor' ),
			'two'   => __( 'Two', 'responsive-owl-carousel-elementor' ),
			'three' => __( 'Three', 'responsive-owl-carousel-elementor' ),
			'four'  => __( 'Four', 'responsive-owl-carousel-elementor' ),
		],
		'team'        => [
			'one' => __( 'One', 'responsive-owl-carousel-elementor' ),
			'two' => __( 'Two', 'responsive-owl-carousel-elementor' ),
		],
	];

	return $styles[ $layout ];
}

/**
 * Get social icons list
 *
 * @return array
 */
function get_social_icons() {
	$social_icons = [];
	$total        = 4;
	for ( $i = 1; $i <= $total; $i ++ ) {
		$social_icons[ 'item_social_icon_' . $i ] = esc_html__( 'Icon', 'responsive-owl-carousel-elementor' );
	}

	return apply_filters( 'owce_social_icons', $social_icons );
}

/**
 * Get social icons control
 *
 * @param          $widget
 * @param          $settings
 * @param string[] $attrs
 *
 * @return false|string|void
 */
function owce_get_social_icons( $widget, $settings, $attrs = [ 'class' => '' ] ) {
	$social_icons = get_social_icons();
	if ( count( $social_icons ) < 1 ) {
		return;
	}

	$output = '';

	ob_start();
	foreach ( $social_icons as $icon_key => $label ) {

		$link_key = $icon_key . '_link';

		$link_attrs = [
			'class'        => $attrs['class'],
			'data-setting' => $link_key . '_url',
		];

		if ( ! empty( $settings[ $link_key ]['url'] ) ) {
			$link_attrs['href'] = $settings[ $link_key ]['url'];
		}

		if ( ! empty( $settings[ $link_key ]['is_external'] ) ) {
			$link_attrs['target'] = "_blank";
		}

		if ( ! empty( $settings[ $link_key ]['nofollow'] ) ) {
			$link_attrs['rel'] = "nofollow";
		}

		echo owce_get_text_with_tag(
			$widget,
			'a',
			owce_get_rendered_icons( $settings[ $icon_key ] ),
			$link_attrs
		);
	}

	return ob_get_clean();
}

/**
 * Get html tag with passed attributes
 *
 * @param       $widget
 * @param       $html_tag
 * @param       $text
 * @param array $attrs
 *
 * @return string
 */
function owce_get_text_with_tag( $widget, $html_tag, $text, $attrs = [] ) {
	//$widget->add_render_attribute( $key, array_map( 'esc_attr', $attrs ) );
	// following function has a duplication issue
	//	return sprintf( '<%1$s %2$s>%3$s</%1$s>', esc_html( $html_tag ),
	//		$widget->get_render_attribute_string( $key ), $key );

	$html_attrs = '';
	foreach ( $attrs as $key => $value ) {
		$html_attrs .= $key . "=" . $value . " ";
	}

	return sprintf( '<%1$s %2$s>%3$s</%1$s>',
		$html_tag,
		$html_attrs,
		$text
	);
}

/**
 * Get image with size
 *
 * @param       $settings
 * @param       $img_size
 * @param       $img_key
 * @param null  $widget
 * @param array $lightbox
 *
 * @return string
 */
function owce_get_img_with_size( $settings, $img_size, $img_key, $widget = null, $lightbox = [] ) {
	$defaults = [
		'show_lightbox'                => false,
		'show_lightbox_title'          => true,
		'show_lightbox_description'    => false,
		'disable_lightbox_editor_mode' => true
	];

	$options = wp_parse_args( $lightbox, $defaults );

	/**
	 * @var string $show_lightbox
	 * @var string $disable_lightbox_editor_mode
	 * @var string $show_lightbox_title
	 * @var string $show_lightbox_description
	 */
	extract( $options );

	if ( $widget && $show_lightbox ) {
		$link = [
			'url' => $settings[ $img_key ]['url'],
			'id'  => $settings[ $img_key ]['id']
		];

		$img_id   = $link['id'];
		$img_link = $link['url'];

		$widget->add_link_attributes( $img_link, $link );
		$widget->add_lightbox_data_attributes( $img_link, $img_id, 'yes', $widget->get_id() );

		// enable/disable click on image to open lightbox in edit mode
		if ( Plugin::$instance->editor->is_edit_mode() ) {
			if ( $disable_lightbox_editor_mode ) {
				$widget->add_render_attribute( $img_link, [ 'class' => 'js-elementor-not-clickable' ] );
			} else {
				$widget->add_render_attribute( $img_link, [ 'class' => 'elementor-clickable' ] );
			}
		}

		// empty title value
		if ( ! $show_lightbox_title ) {
			$widget->add_render_attribute( $img_link, [ 'data-elementor-lightbox-title' => '' ], null, true );
		}

		// empty description value
		if ( ! $show_lightbox_description ) {
			$widget->add_render_attribute( $img_link, [ 'data-elementor-lightbox-description' => '' ], null, true );
		}

		$img_html = "<a " . $widget->get_render_attribute_string( $img_link ) . ">";
		$img_html .= Group_Control_Image_Size::get_attachment_image_html( $settings, $img_size, $img_key );
		$img_html .= "</a>";

		return $img_html;
	}

	return Group_Control_Image_Size::get_attachment_image_html( $settings, $img_size, $img_key );
}

/**
 * Wrapper for generating various kinds of Elementor control
 *
 * @param        $widget
 * @param        $field
 * @param        $label
 * @param        $selector
 * @param array  $options
 * @param string $tab
 */
function owce_common_controls_section( $widget, $field, $label, $selector, $options = [], $tab = '' ) {
	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$defaults = [
		'hide'                    => true,
		'hide_default'            => '',
		'align'                   => false,
		'tag'                     => true,
		'default_tag'             => 'div',
		'color'                   => true,
		'hover_color'             => false,
		'background'              => false,
		'hover_background'        => false,
		//		'background_type'    => [ 'classic', 'gradient', 'video' ],
		//		'background_exclude' => [],
		'padding'                 => false,
		'margin'                  => true,
		'gap'                     => false,
		'typography'              => true,
		'font_size'               => false,
		'border'                  => false,
		'box_shadow'              => false,
		'border_radius'           => false,
		'icon'                    => false,
		'size'                    => false,
		'image'                   => false,
		'hover_animation'         => false,
		'hover_animation_default' => '',
		'condition'               => ''
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var array  $condition
	 * @var string $hide
	 * @var string $icon
	 * @var string $tag
	 * @var string $default_tag
	 * @var string $color
	 * @var string $hover_color
	 * @var string $background
	 * @var string $hover_background
	 * @var array  $background_exclude
	 * @var array  $background_type
	 * @var string $typography
	 * @var string $gap
	 * @var string $font_size
	 * @var string $align
	 * @var string $margin
	 * @var string $padding
	 * @var string $size
	 * @var string $border
	 * @var string $box_shadow
	 * @var string $border_radius
	 * @var string $box_shadow
	 * @var string $box_shadow
	 * @var string $hover_animation
	 * @var string $hover_animation_default
	 */
	extract( $options );

	$tab_section = $tab == 'tab' ? Controls_Manager::TAB_CONTENT : Controls_Manager::TAB_STYLE;

	$widget->start_controls_section(
		$field_prefix . 'style_' . $field,
		[
			'label'     => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
			'tab'       => $tab_section,
			'condition' => $condition
		]
	);

	if ( $hide ) {
		$hide_options              = $options;
		$hide_options['default']   = $options['hide_default'];
		$hide_options['condition'] = $show_hide_button ?? '';
		$_label                    = owce_key_value_exists( $options, 'hide_label', esc_html__( 'Hide', 'responsive-owl-carousel-elementor' ) );
		owce_switcher_control( $widget, $field . '_hide', $_label, $hide_options );
	}

	if ( $icon ) {
		$_label = owce_key_value_exists( $options, 'icon_label', esc_html__( 'Icon', 'responsive-owl-carousel-elementor' ) );
		owce_icons_control( $widget, $field, $_label, $options );
	}

	if ( $tag ) {
		$_label = owce_key_value_exists( $options, 'tag_label', esc_html__( 'HTML Tag', 'responsive-owl-carousel-elementor' ) );
		$_tags  = [
			'h1'   => 'H1',
			'h2'   => 'H2',
			'h3'   => 'H3',
			'h4'   => 'H4',
			'h5'   => 'H5',
			'h6'   => 'H6',
			'div'  => 'div',
			'span' => 'span',
			'p'    => 'p',
		];
		owce_select_control( $widget, $field . '_tag', $_label, [ 'options' => $_tags, 'default' => $default_tag ] );
	}

	if ( $color ) {
		$_label = owce_key_value_exists( $options, 'color_label', esc_html__( 'Color', 'responsive-owl-carousel-elementor' ) );
		owce_color_control( $widget, $field . '_color', $_label, $selector );
	}

	if ( $background ) {
		$_label = owce_key_value_exists( $options, 'background_label', esc_html__( 'Background', 'responsive-owl-carousel-elementor' ) );
		owce_background_control( $widget, $field . '_background', $_label, [
			'selector'  => $selector,
			'condition' => $condition,
		] );
	}

	if ( $hover_background ) {
		$_label = owce_key_value_exists( $options, 'hover_background_label', esc_html__( 'Hover', 'responsive-owl-carousel-elementor' ) );
		
		// label/description doesn't work for background control.
		// That's why creating a heading control to show a note
		$widget->add_control(
			'_hover_background_note',
			[
				'label'     => esc_html__( $_label, 'responsive-owl-carousel-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		owce_background_control( $widget, $field . '_hover_background', $_label, [
			'hover'     => true,
			'selector'  => $selector,
			'condition' => $condition,
		] );
	}

	if ( $hover_color ) {
		$_label = owce_key_value_exists( $options, 'hover_color_label', esc_html__( 'Hover color', 'responsive-owl-carousel-elementor' ) );
		owce_color_control( $widget, $field . '_hover_color', $_label, $selector, true );
	}

	if ( $typography ) {
		$_label = owce_key_value_exists( $options, 'typography_label', esc_html__( 'Typography', 'responsive-owl-carousel-elementor' ) );
		$widget->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => $field_prefix . $field . '_typography',
				'label'    => esc_html__( $_label, 'responsive-owl-carousel-elementor' ),
				'selector' => '{{WRAPPER}} ' . $selector
			]
		);
	}

	if ( $gap ) {
		$default_gap = null;
		$_label      = owce_key_value_exists( $options, 'gap_label', esc_html__( 'Gap', 'responsive-owl-carousel-elementor' ) );
		owce_slider_control( $widget, $field . '_gap', $_label, [
			'responsive'     => true,
			'property'       => 'no-selector',
			'size_units'     => [ 'px' ],
			'range'          => [ 'px' => [ 'max' => 100 ] ],
			'default'        => [ 'size' => 10 ],
			'tablet_default' => [ 'size' => 10 ],
			'mobile_default' => [ 'size' => 0 ],
		] );
	}

	if ( $font_size ) {
		$_label = owce_key_value_exists( $options, 'font_size_label', esc_html__( 'Size', 'responsive-owl-carousel-elementor' ) );
		owce_slider_control( $widget, $field . '_font_size', $_label, [
			'property' => 'font-size',
			'selector' => $selector
		] );
	}

	if ( $align ) {
		$_label = owce_key_value_exists( $options, 'align_label', esc_html__( 'Align', 'responsive-owl-carousel-elementor' ) );
		owce_choose_control( $widget, $field . '_align', $_label, [ 'selector' => $selector ] );
	}

	if ( $margin ) {
		$_label = owce_key_value_exists( $options, 'margin_label', esc_html__( 'Margin', 'responsive-owl-carousel-elementor' ) );
		owce_dimension_control( $widget, $field . '_margin', $_label, [ 'selector' => $selector ] );
	}

	if ( $padding ) {
		$_label = owce_key_value_exists( $options, 'padding_label', esc_html__( 'Padding', 'responsive-owl-carousel-elementor' ) );
		owce_dimension_control( $widget, $field . '_padding', $_label, [
			'type'     => 'padding',
			'selector' => $selector,
			'default'  => $padding_default ?? []
		] );
	}

	if ( $size ) {
		// width
		$_label = owce_key_value_exists( $options, 'width_label', esc_html__( 'Width', 'responsive-owl-carousel-elementor' ) );
		owce_slider_control( $widget, $field . '_width', $_label, [ 'selector' => $selector ] );

		// height
		$_label = owce_key_value_exists( $options, 'height_label', esc_html__( 'Height', 'responsive-owl-carousel-elementor' ) );
		owce_slider_control( $widget, $field . '_height', $_label, [
			'property'    => 'height',
			'size_units'  => [ 'px' ],
			'selector'    => $selector,
			'description' => 'in px only'
		] );
	}

	if ( $border ) {
		$_label = owce_key_value_exists( $options, 'border_label', esc_html__( 'Border', 'responsive-owl-carousel-elementor' ) );
		$widget->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => $field_prefix . $field . '_border',
				'label'          => esc_html__( $_label, 'responsive-owl-carousel-elementor' ),
				'fields_options' => $border_default ?? '',
				'selector'       => '{{WRAPPER}} ' . $selector,
			]
		);
	}

	if ( $box_shadow ) {
		$_label = owce_key_value_exists( $options, 'box_shadow_label', esc_html__( 'Box Shadow', 'responsive-owl-carousel-elementor' ) );
		$widget->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => $field_prefix . $field . '_box_shadow',
				'label'    => esc_html__( $_label, 'responsive-owl-carousel-elementor' ),
				'selector' => '{{WRAPPER}} ' . $selector,
			]
		);
	}

	if ( $border_radius ) {
		$_label = owce_key_value_exists( $options, 'border_radius_label', esc_html__( 'Border Radius', 'responsive-owl-carousel-elementor' ) );
		owce_dimension_control( $widget, $field . '_border_radius', $_label, [
			'type'     => 'border-radius',
			'selector' => $selector,
			'default'  => $border_radius_default ?? []
		] );
	}

	if ( $hover_animation ) {
		$_label = owce_key_value_exists( $options, 'hover_animation_label', esc_html__( 'Hover Animation', 'responsive-owl-carousel-elementor' ) );
		$widget->add_control(
			$field_prefix . $field . '_hover_animation',
			[
				'label'   => esc_html__( $_label, 'responsive-owl-carousel-elementor' ),
				'type'    => Controls_Manager::HOVER_ANIMATION,
				'default' => $hover_animation_default
			]
		);
	}

	$widget->end_controls_section();
}

/**
 * Get elementor background control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_background_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'hover'              => false,
		'background_type'    => [ 'classic' ],
		'background_exclude' => [ 'image' ],
		'selector'           => '',
		'condition'          => '',
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $description
	 * @var array  $background_exclude
	 * @var array  $background_type
	 * @var string $selector
	 * @var array  $classes
	 * @var array  $hover
	 * @var array  $condition
	 */
	extract( $options );

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$selector = '{{WRAPPER}} ' . $selector;

	if ( $hover ) {
		$selector = $selector . ':hover, ' . $selector . ':focus';
	}

	$widget->add_group_control(
		Group_Control_Background::get_type(),
		[
			'name'            => $field_prefix . $field,
			'label'           => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
			'description'     => esc_html__( 'My description', 'responsive-owl-carousel-elementor' ),
			'exclude'         => $background_exclude,
			'types'           => $background_type,
			'selector'        => $selector,
			'content_classes' => 'myclassss',
			'condition'       => $condition,
		]
	);
}

/**
 * Get elementor text control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_text_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'type'        => 'text',
		'input_type'  => 'text',
		'description' => '',
		'show_label'  => true,
		'label_block' => true,
		'default'     => '',
		'placeholder' => '',
		'classes'     => '',
		'selectors'   => '',
		'condition'   => ''
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $show_label
	 * @var string $label_block
	 * @var string $placeholder
	 * @var string $classes
	 * @var array  $selectors
	 * @var array  $condition
	 * @var string $type
	 * @var string $input_type
	 */
	extract( $options );

	$args = [
		'label'       => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
		'type'        => Controls_Manager::TEXT,
		'default'     => esc_html__( $default, 'responsive-owl-carousel-elementor' ),
		'show_label'  => $show_label,
		'label_block' => $label_block,
		'placeholder' => esc_html__( $placeholder, 'responsive-owl-carousel-elementor' ),
		'classes'     => $classes,
		'selectors'   => $selectors,
		'condition'   => $condition
	];

	if ( $type == 'text' ) {
		$args['input_type'] = $input_type;
	}

	if ( $type == 'textarea' ) {
		$args['type'] = Controls_Manager::TEXTAREA;
	}

	if ( $type == 'wysiwyg' ) {
		$args['type'] = Controls_Manager::WYSIWYG;
	}

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		$args
	);
}

/**
 * Get elementor image control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_image_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'description' => '',
		'classes'     => '',
		'condition'   => '',
		'default'     => [
			'url' => Utils::get_placeholder_image_src()
		],
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $description
	 * @var string $classes
	 * @var array  $condition
	 */
	extract( $options );

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		[
			'label'       => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
			'description' => esc_html__( $description, 'responsive-owl-carousel-elementor' ),
			'type'        => Controls_Manager::MEDIA,
			'classes'     => $classes,
			'condition'   => $condition,
			'default'     => $default
		]
	);
}

/**
 * Get elementor dimension control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_dimension_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'type'               => 'margin',
		'responsive'         => true,
		'description'        => '',
		'size_units'         => [ 'px', '%', 'em' ],
		'default'            => [
			'top'      => '',
			'right'    => '',
			'bottom'   => '',
			'left'     => '',
			'isLinked' => true,
		],
		'allowed_dimensions' => 'all',
		'classes'            => '',
		'selector'           => '',
		'condition'          => '',
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $description
	 * @var array  $size_units
	 * @var array  $allowed_dimensions
	 * @var string $selector
	 * @var string $classes
	 * @var array  $condition
	 * @var string $responsive
	 * @var string $type
	 */
	extract( $options );

	$args = [
		'label'              => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
		'description'        => esc_html__( $description, 'responsive-owl-carousel-elementor' ),
		'type'               => Controls_Manager::DIMENSIONS,
		'size_units'         => $size_units,
		'selectors'          => [
			'{{WRAPPER}} ' . $selector => $type . ': {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'allowed_dimensions' => $allowed_dimensions,
		'default'            => $default,
		'classes'            => $classes,
		'condition'          => $condition,
	];

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';
	if ( $responsive ) {
		$widget->add_responsive_control(
			$field_prefix . $field,
			$args
		);
	} else {
		$widget->add_control(
			$field_prefix . $field,
			$args
		);
	}
}

/**
 * Get elementor switcher control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_switcher_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'responsive'     => false,
		'description'    => '',
		'label_on'       => 'Yes',
		'label_off'      => 'No',
		'return_value'   => 'yes',
		'default'        => 'yes',
		'tablet_default' => 'yes',
		'mobile_default' => 'yes',
		'condition'      => ''
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $description
	 * @var string $label_on
	 * @var string $label_off
	 * @var string $return_value
	 * @var string $tablet_default
	 * @var string $mobile_default
	 * @var array  $condition
	 * @var string $responsive
	 */
	extract( $options );

	$args = [
		'label'        => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
		'description'  => esc_html__( $description, 'responsive-owl-carousel-elementor' ),
		'type'         => Controls_Manager::SWITCHER,
		'label_on'     => esc_html__( $label_on, 'responsive-owl-carousel-elementor' ),
		'label_off'    => esc_html__( $label_off, 'responsive-owl-carousel-elementor' ),
		'return_value' => $return_value,
		'default'      => $default,
		'condition'    => $condition
	];

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	if ( $responsive ) {
		$args['tablet_default'] = $tablet_default;
		$args['mobile_default'] = $mobile_default;
		
		$widget->add_responsive_control(
			$field_prefix . $field,
			$args
		);
	} else {
		$widget->add_control(
			$field_prefix . $field,
			$args
		);
	}
}

/**
 * Get elementor number control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_number_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'responsive'     => false,
		'description'    => '',
		'min'            => 1,
		'max'            => null,
		'step'           => 1,
		'default'        => null,
		'tablet_default' => null,
		'mobile_default' => null,
		'condition'      => ''
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $description
	 * @var number $min
	 * @var number $max
	 * @var number $step
	 * @var number $tablet_default
	 * @var number $mobile_default
	 * @var string $responsive
	 * @var array  $condition
	 */
	extract( $options );

	$args = [
		'label'       => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
		'description' => esc_html__( $description, 'responsive-owl-carousel-elementor' ),
		'type'        => Controls_Manager::NUMBER,
		'min'         => $min,
		'max'         => $max,
		'step'        => $step,
		'default'     => $default,
		'condition'   => $condition,
	];

	if ( $responsive ) {
		$args['tablet_default'] = $tablet_default;
		$args['mobile_default'] = $mobile_default;
		
		$widget->add_responsive_control(
			$widget::FIELD_PREFIX . $field,
			$args
		);
	} else {
		$widget->add_control(
			$widget::FIELD_PREFIX . $field,
			$args
		);
	}
}

/**
 * Get elementor color control
 *
 * @param      $widget
 * @param      $field
 * @param      $label
 * @param      $selector
 * @param bool $hover
 */
function owce_color_control( $widget, $field, $label, $selector, $hover = false ) {
	if ( $hover ) {
		$selector = $selector . ':hover';
	}

	$widget->add_control(
		$widget::FIELD_PREFIX . $field,
		[
			'label'     => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} ' . $selector => strpos( $selector, 'svg' ) ? 'fill: {{VALUE}}' : 'color: {{VALUE}}',
			]
		]
	);
}

/**
 * Get elementor slider control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_slider_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'responsive'     => false,
		'description'    => '',
		'property'       => 'width',
		'size_units'     => [ 'px', '%' ],
		'range'          => [
			'px' => [
				'min'  => 0,
				'max'  => 1920,
				'step' => 1,
			],
			'%'  => [
				'min' => 0,
				'max' => 100,
			],
		],
		'default'        => [
			'unit' => 'px',
			'size' => '',
		],
		'classes'        => '',
		'selector'       => '',
		'tablet_default' => '',
		'mobile_default' => ''
	];
	
	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $description
	 * @var array  $size_units
	 * @var array  $range
	 * @var string $property
	 * @var string $tablet_default
	 * @var string $mobile_default
	 * @var string $selector
	 * @var string $classes
	 * @var string $responsive
	 */
	extract( $options );

	$args = [
		'label'       => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
		'description' => esc_html__( $description, 'responsive-owl-carousel-elementor' ),
		'type'        => Controls_Manager::SLIDER,
		'size_units'  => $size_units,
		'range'       => $range,
		'default'     => $default,
		'classes'     => $classes
	];

	if ( $property == 'width' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => 'width: {{SIZE}}{{UNIT}};' ];
	}

	if ( $property == 'height' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => 'height: {{SIZE}}{{UNIT}};' ];
	}

	if ( $property == 'font-size' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => 'font-size: {{SIZE}}{{UNIT}};' ];
	}

	if ( $property == 'border-radius' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => 'border-radius: {{SIZE}}{{UNIT}};' ];
	}

	if ( $tablet_default ) {
		$args['tablet_default'] = $options['tablet_default'];
	}

	if ( $mobile_default ) {
		$args['mobile_default'] = $options['mobile_default'];
	}

	if ( $property == 'no-selector' ) {
		unset( $args['selectors'] );
	}

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';
	if ( $responsive ) {
		$widget->add_responsive_control(
			$field_prefix . $field,
			$args
		);
	} else {
		$widget->add_control(
			$field_prefix . $field,
			$args
		);
	}
}

/**
 * Get elementor choose control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_choose_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'description' => '',
		'options'     => [
			'left'   => [
				'title' => esc_html__( 'Left', 'responsive-owl-carousel-elementor' ),
				'icon'  => 'fa fa-align-left',
			],
			'center' => [
				'title' => esc_html__( 'Center', 'responsive-owl-carousel-elementor' ),
				'icon'  => 'fa fa-align-center',
			],
			'right'  => [
				'title' => esc_html__( 'Right', 'responsive-owl-carousel-elementor' ),
				'icon'  => 'fa fa-align-right',
			],
		],
		'default'     => '',
		'toggle'      => true,
		'classes'     => '',
		'selector'    => ''
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $description
	 * @var string $classes
	 * @var array  $selector
	 */
	extract( $options );

	$args = [
		'label'       => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
		'description' => esc_html__( $description, 'responsive-owl-carousel-elementor' ),
		'type'        => Controls_Manager::CHOOSE,
		'options'     => $options,
		'default'     => $default,
		'classes'     => $classes,
		'selectors'   => [
			'{{WRAPPER}} ' . $selector => 'text-align: {{VALUE}}'
		]
	];

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		$args
	);
}

/**
 * Get elementor select control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $_options
 */
function owce_select_control( $widget, $field, $label, $_options = [] ) {
	$defaults = [
		'description' => '',
		'options'     => [],
		'default'     => '',
		'classes'     => '',
		'selector'    => '',
		'condition'   => ''
	];

	$_options = wp_parse_args( $_options, $defaults );

	/**
	 * @var string $default
	 * @var string $description
	 * @var string $classes
	 * @var string $selector
	 * @var array  $options
	 * @var array  $condition
	 */
	extract( $_options );

	$args = [
		'label'       => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
		'description' => esc_html__( $description, 'responsive-owl-carousel-elementor' ),
		'type'        => Controls_Manager::SELECT,
		'options'     => $options,
		'default'     => $default,
		'classes'     => $classes,
		'condition'   => $condition
	
	];

	if ( $selector == 'no-refresh' ) {
		$args['selectors'] = [ '{{WRAPPER}} ' . $selector => '' ];
	}

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		$args
	);
}

/**
 * Get elementor url control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_url_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'description' => '',
		'show_label'  => true,
		'label_block' => true,
		'default'     => [
			'url'         => '#',
			'is_external' => true,
			'nofollow'    => true,
		],
		'url_options' => [
			'is_external',
			'nofollow',
		],
		'placeholder' => 'https://your-link.com',
		'classes'     => '',
		'selector'    => '',
		'condition'   => ''
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $placeholder
	 * @var string $show_label
	 * @var string $label_block
	 * @var array  $url_options
	 * @var string $classes
	 * @var string $selector
	 * @var array  $condition
	 */
	extract( $options );

	$args = [
		'label'       => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
		'type'        => Controls_Manager::URL,
		'placeholder' => esc_html__( $placeholder, 'responsive-owl-carousel-elementor' ),
		'show_label'  => $show_label,
		'label_block' => $label_block,
		'options'     => $url_options,
		'default'     => $default,
		'classes'     => $classes,
		// selectors is just to prevent refreshing page while typing the URL
		'selectors'   => [
			'{{WRAPPER}} ' . $selector => '',
		],
		'condition'   => $condition
	];

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		$args
	);
}

/**
 * Get elementor icons control
 *
 * @param       $widget
 * @param       $field
 * @param       $label
 * @param array $options
 */
function owce_icons_control( $widget, $field, $label, $options = [] ) {
	$defaults = [
		'default'   => [ 'library' => 'solid', 'value' => 'fas fa-star' ],
		'classes'   => '',
		'condition' => ''
	];

	$options = wp_parse_args( $options, $defaults );

	/**
	 * @var string $default
	 * @var string $classes
	 */
	extract( $options );

	$field_prefix = owce_get_class_constant( $widget, 'FIELD_PREFIX' ) ? $widget::FIELD_PREFIX : '';

	$widget->add_control(
		$field_prefix . $field,
		[
			'label'   => esc_html__( $label, 'responsive-owl-carousel-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => $default,
			'classes' => $classes
		]
	);
}

/**
 * Get custom social icons control
 *
 * @param       $widget
 * @param       $fields
 * @param array $options
 */
function owce_social_icons_control( $widget, $fields, $options = [] ) {
	$default_icons = [
		'fa-facebook-f',
		'fa-twitter',
		'fa-instagram',
		'fa-linkedin-in'
	];

	$icons_options                       = $options;
	$icons_options['default']['library'] = 'fa-brands';

	$index = 0;

	$existing_classes = $options['classes'] . ' ';

	foreach ( $fields as $key => $label ) {
		$icons_options['default']['value'] = 'fab ' . $default_icons[ $index ];
		owce_icons_control( $widget, $key, $label, $icons_options );

		$options['classes'] = $existing_classes . $key . '_link_url';
		owce_url_control( $widget, $key . '_link', $label . ' Link', $options );

		$index++;
	}
}

/**
 * Get rendered icon for icons control
 *
 * @param     $key
 * @param int $length
 *
 * @return false|string
 */
function owce_get_rendered_icons( $key, $length = 1 ) {
	ob_start();
	for ( $i = 1; $i <= $length; $i++ ) {
		Icons_Manager::render_icon( $key, [ 'aria-hidden' => 'true' ] );
	}

	return ob_get_clean();
}

/**
 * Check if key exists and return it
 *
 * @param $arr
 * @param $key
 * @param $default
 *
 * @return mixed
 */
function owce_key_value_exists( $arr, $key, $default ) {
	return ( ! empty( $arr[ $key ] ) && trim( $arr[ $key ] ) ) ? $arr[ $key ] : $default;
}

/**
 * Check if constant exists and return it
 *
 * @param $class
 * @param $name
 *
 * @return bool
 */
function owce_get_class_constant( $class, $name ) {
	if ( is_string( $class ) ) {
		return defined( "$class::$name" );
	} else if ( is_object( $class ) ) {
		return defined( get_class( $class ) . "::$name" );
	}

	return false;
}

/**
 * Generate item link HTML
 *
 * @param $widget
 * @param $item
 * @param $index
 * @param $class
 * @return string[]
 */
function owce_get_item_link( $widget, $item, $index = null, $class = '' ) {
	$link_start = '';
	$link_end   = '';
	if ( ! empty( $item['item_url']['url'] ) ) {
		$widget->add_link_attributes( 'item_url' . $index, $item['item_url'] );
		$link_attrs = $widget->get_render_attribute_string( 'item_url' . $index );

		if ( ! empty( $link_attrs ) ) {
			$link_start = '<a class="owl-link ' . esc_attr( $class ) . '" ' . wp_kses_post( $link_attrs ) . '>';
			$link_end   = '</a>';
		}
	}

	return array(
		$link_start,
		$link_end,
	);
}