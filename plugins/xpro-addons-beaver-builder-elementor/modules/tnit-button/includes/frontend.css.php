<?php
/**
 * This file should contain frontend styles that
 * will be applied to individual module instances.
 *
 * You have access to three variables in this file:
 *
 * $module An instance of your module class.
 * $id The module's ID.
 * $settings The module's settings.
 *
 * @package Xpro Addons
 * @sub-package Hover Card Module
 * @since 1.1.3
 */

// Text Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard .btn-text",
		'props'    => array(
			'color' => $settings->btn_text_color,
		),
	)
);
// Text Hover Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard:hover .btn-text",
		'props'    => array(
			'color' => $settings->btn_text_hover_color,
		),
	)
);

// Background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-color",
		'enabled'  => 'none' === $settings->hover_effect_style,
		'props'    => array(
			'background-color' => $settings->btn_bg_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-color",
		'enabled'  => 'effect-1' === $settings->hover_effect_style || 'effect-2' === $settings->hover_effect_style,
		'props'    => array(
			'background-color' => $settings->btn_bg_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-color:before,
					   .fl-node-$id .tnit-btn-bg-color:after",
		'enabled'  => 'effect-3' === $settings->hover_effect_style || 'effect-4' === $settings->hover_effect_style,
		'props'    => array(
			'background-color' => $settings->btn_bg_color,
		),
	)
);
// Background Hover color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-hover-color:hover",
		'enabled'  => 'none' === $settings->hover_effect_style,
		'props'    => array(
			'background-color' => $settings->btn_bg_hover_color,
			'background-image' => 'none',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-hover-color:before,
					   .fl-node-$id .tnit-btn-bg-hover-color:after",
		'enabled'  => 'effect-1' === $settings->hover_effect_style || 'effect-2' === $settings->hover_effect_style,
		'props'    => array(
			'background-color' => $settings->btn_bg_hover_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-hover-color",
		'enabled'  => 'effect-3' === $settings->hover_effect_style || 'effect-4' === $settings->hover_effect_style,
		'props'    => array(
			'background-color' => $settings->btn_bg_hover_color,
		),
	)
);

// Background Gradient color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-gradient",
		'enabled'  => 'none' === $settings->hover_effect_style,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->btn_bg_gradient ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-gradient",
		'enabled'  => 'effect-1' === $settings->hover_effect_style || 'effect-2' === $settings->hover_effect_style,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->btn_bg_gradient ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-gradient:before,
					   .fl-node-$id .tnit-btn-bg-gradient:after",
		'enabled'  => 'effect-3' === $settings->hover_effect_style || 'effect-4' === $settings->hover_effect_style,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->btn_bg_gradient ),
		),
	)
);
// Background Gradient Hover color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-hover-gradient:hover",
		'enabled'  => 'none' === $settings->hover_effect_style,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->btn_hover_bg_gradient ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-hover-gradient:before,
					   .fl-node-$id .tnit-btn-bg-hover-gradient:after",
		'enabled'  => 'effect-1' === $settings->hover_effect_style || 'effect-2' === $settings->hover_effect_style,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->btn_hover_bg_gradient ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bg-hover-gradient",
		'enabled'  => 'effect-3' === $settings->hover_effect_style || 'effect-4' === $settings->hover_effect_style,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->btn_hover_bg_gradient ),
		),
	)
);

// Button Alignment.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'btn_alignment',
		'selector'     => ".fl-node-$id .tnit-bb-button-outer",
		'enabled'      => 'before' === $settings->icon_position || 'after' === $settings->icon_position,
		'prop'         => 'text-align',
	)
);

// Button Alignment -- Icon Positon Outer Left/Right.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox",
		'media'    => 'default',
		'props'    => array(
			'-webkit-justify-content' => ( 'left' === $settings->btn_alignment ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment ) ? 'flex-end' :
									( $settings->btn_alignment ) ),

			'-moz-justify-content'    => ( 'left' === $settings->btn_alignment ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment ) ? 'flex-end' :
									( $settings->btn_alignment ) ),

			'-ms-justify-content'     => ( 'left' === $settings->btn_alignment ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment ) ? 'flex-end' :
									( $settings->btn_alignment ) ),

			'-o-justify-content'      => ( 'left' === $settings->btn_alignment ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment ) ? 'flex-end' :
									( $settings->btn_alignment ) ),

			'justify-content'         => ( 'left' === $settings->btn_alignment ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment ) ? 'flex-end' :
									( $settings->btn_alignment ) ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox",
		'media'    => 'medium',
		'props'    => array(
			'-webkit-justify-content' => ( 'left' === $settings->btn_alignment_medium ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_medium ) ? 'flex-end' :
									( $settings->btn_alignment_medium ) ),

			'-moz-justify-content'    => ( 'left' === $settings->btn_alignment_medium ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_medium ) ? 'flex-end' :
									( $settings->btn_alignment_medium ) ),

			'-ms-justify-content'     => ( 'left' === $settings->btn_alignment_medium ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_medium ) ? 'flex-end' :
									( $settings->btn_alignment_medium ) ),

			'-o-justify-content'      => ( 'left' === $settings->btn_alignment_medium ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_medium ) ? 'flex-end' :
									( $settings->btn_alignment_medium ) ),

			'justify-content'         => ( 'left' === $settings->btn_alignment_medium ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_medium ) ? 'flex-end' :
									( $settings->btn_alignment_medium ) ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox",
		'media'    => 'responsive',
		'props'    => array(
			'-webkit-justify-content' => ( 'left' === $settings->btn_alignment_responsive ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_responsive ) ? 'flex-end' :
									( $settings->btn_alignment_responsive ) ),

			'-moz-justify-content'    => ( 'left' === $settings->btn_alignment_responsive ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_responsive ) ? 'flex-end' :
									( $settings->btn_alignment_responsive ) ),

			'-ms-justify-content'     => ( 'left' === $settings->btn_alignment_responsive ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_responsive ) ? 'flex-end' :
									( $settings->btn_alignment_responsive ) ),

			'-o-justify-content'      => ( 'left' === $settings->btn_alignment_responsive ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_responsive ) ? 'flex-end' :
									( $settings->btn_alignment_responsive ) ),

			'justify-content'         => ( 'left' === $settings->btn_alignment_responsive ) ? 'flex-start' :
									( ( 'right' === $settings->btn_alignment_responsive ) ? 'flex-end' :
									( $settings->btn_alignment_responsive ) ),
		),
	)
);

// Full Width.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard",
		'enabled'  => 'full' === $settings->cta_width,
		'props'    => array(
			'width'      => '100%',
			'text-align' => 'center',
		),
	)
);

// Button custom width.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'enabled'      => 'custom' === $settings->cta_width,
		'setting_name' => 'cta_custom_width',
		'selector'     => ".fl-node-$id .tnit-btn-standard",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);

// Padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'btn_padding',
		'selector'     => ".fl-node-$id .tnit-btn-standard",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'btn_padding_top',
			'padding-right'  => 'btn_padding_right',
			'padding-bottom' => 'btn_padding_bottom',
			'padding-left'   => 'btn_padding_left',
		),
	)
);

// Button border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'border',
		'selector'     => ".fl-node-$id .tnit-btn-standard",
	)
);

// Border radius - Effect Top-Bottom.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect2:before",
		'media'    => 'default',
		'props'    => array(
			'border-top-left-radius'  => ( '' !== $settings->border && '' !== $settings->border['radius']['top_left'] ) ? $settings->border['radius']['top_left'] . 'px' : '',
			'border-top-right-radius' => ( '' !== $settings->border && '' !== $settings->border['radius']['top_right'] ) ? $settings->border['radius']['top_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect2:before",
		'media'    => 'medium',
		'props'    => array(
			'border-top-left-radius'  => ( '' !== $settings->border && '' !== $settings->border_medium['radius']['top_left'] ) ? $settings->border_medium['radius']['top_left'] . 'px' : '',
			'border-top-right-radius' => ( '' !== $settings->border && '' !== $settings->border_medium['radius']['top_right'] ) ? $settings->border_medium['radius']['top_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect2:before",
		'media'    => 'responsive',
		'props'    => array(
			'border-top-left-radius'  => ( '' !== $settings->border && '' !== $settings->border_responsive['radius']['top_left'] ) ? $settings->border_responsive['radius']['top_left'] . 'px' : '',
			'border-top-right-radius' => ( '' !== $settings->border && '' !== $settings->border_responsive['radius']['top_right'] ) ? $settings->border_responsive['radius']['top_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect2:after",
		'media'    => 'default',
		'props'    => array(
			'border-bottom-left-radius'  => ( '' !== $settings->border && '' !== $settings->border['radius']['bottom_left'] ) ? $settings->border['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->border && '' !== $settings->border['radius']['bottom_right'] ) ? $settings->border['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect2:after",
		'media'    => 'medium',
		'props'    => array(
			'border-bottom-left-radius'  => ( '' !== $settings->border && '' !== $settings->border_medium['radius']['bottom_left'] ) ? $settings->border_medium['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->border && '' !== $settings->border_medium['radius']['bottom_right'] ) ? $settings->border_medium['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect2:after",
		'media'    => 'responsive',
		'props'    => array(
			'border-bottom-left-radius'  => ( '' !== $settings->border && '' !== $settings->border_responsive['radius']['bottom_left'] ) ? $settings->border_responsive['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->border && '' !== $settings->border_responsive['radius']['bottom_right'] ) ? $settings->border_responsive['radius']['bottom_right'] . 'px' : '',
		),
	)
);

// Border radius - Effect Left-Right.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect1:before",
		'media'    => 'default',
		'props'    => array(
			'border-top-left-radius'    => ( '' !== $settings->border && '' !== $settings->border['radius']['bottom_left'] ) ? $settings->border['radius']['bottom_left'] . 'px' : '',
			'border-bottom-left-radius' => ( '' !== $settings->border && '' !== $settings->border['radius']['bottom_right'] ) ? $settings->border['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect1:before",
		'media'    => 'medium',
		'props'    => array(
			'border-top-left-radius'    => ( '' !== $settings->border && '' !== $settings->border_medium['radius']['bottom_left'] ) ? $settings->border_medium['radius']['bottom_left'] . 'px' : '',
			'border-bottom-left-radius' => ( '' !== $settings->border && '' !== $settings->border_medium['radius']['bottom_right'] ) ? $settings->border_medium['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect1:before",
		'media'    => 'responsive',
		'props'    => array(
			'border-top-left-radius'    => ( '' !== $settings->border && '' !== $settings->border_responsive['radius']['bottom_left'] ) ? $settings->border_responsive['radius']['bottom_left'] . 'px' : '',
			'border-bottom-left-radius' => ( '' !== $settings->border && '' !== $settings->border_responsive['radius']['bottom_right'] ) ? $settings->border_responsive['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect1:after",
		'media'    => 'default',
		'props'    => array(
			'border-top-right-radius'    => ( '' !== $settings->border && '' !== $settings->border['radius']['bottom_left'] ) ? $settings->border['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->border && '' !== $settings->border['radius']['bottom_right'] ) ? $settings->border['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect1:after",
		'media'    => 'medium',
		'props'    => array(
			'border-top-right-radius'    => ( '' !== $settings->border && '' !== $settings->border_medium['radius']['bottom_left'] ) ? $settings->border_medium['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->border && '' !== $settings->border_medium['radius']['bottom_right'] ) ? $settings->border_medium['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard-IconBox .tnit-btn-effect1:after",
		'media'    => 'responsive',
		'props'    => array(
			'border-top-right-radius'    => ( '' !== $settings->border && '' !== $settings->border_responsive['radius']['bottom_left'] ) ? $settings->border_responsive['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->border && '' !== $settings->border_responsive['radius']['bottom_right'] ) ? $settings->border_responsive['radius']['bottom_right'] . 'px' : '',
		),
	)
);

// Border Hover Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard:hover",
		'props'    => array(
			'border-color' => $settings->boder_hover_color,
		),
	)
);

// Icon Font Size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .tnit-btn-bb-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);
// Icon Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-bb-icon i",
		'props'    => array(
			'color' => $settings->icon_color,
		),
	)
);
// Icon Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard:hover .tnit-btn-bb-icon i",
		'props'    => array(
			'color' => $settings->icon_hover_color,
		),
	)
);
// Icon Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-button-icon-circle,
		 				.fl-node-$id .tnit-button-icon-square, 
		 				.fl-node-$id .tnit-button-icon-custom",
		'props'    => array(
			'background-color' => $settings->icon_bg_color,
		),
	)
);
// Icon Hover Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard:hover .tnit-button-icon-circle,
						.fl-node-$id .tnit-btn-standard:hover .tnit-button-icon-square, 
						.fl-node-$id .tnit-btn-standard:hover .tnit-button-icon-custom",
		'props'    => array(
			'background-color' => $settings->icon_bg_hover_color,
		),
	)
);
// Icon background size (width).
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_bg_size',
		'selector'     => ".fl-node-$id .tnit-button-icon-circle,
							.fl-node-$id .tnit-button-icon-square,
							.fl-node-$id .tnit-button-icon-custom",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);
// Icon background size (height).
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_bg_size',
		'selector'     => ".fl-node-$id .tnit-button-icon-circle,
							.fl-node-$id .tnit-button-icon-square,
							.fl-node-$id .tnit-button-icon-custom",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);
// IconBox left.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard .tnit-btn-bb-iconLeft",
		'enabled'  => 'simple' !== $settings->icon_style,
		'media'    => 'default',
		'props'    => array(
			'left' => ( '' !== $settings->icon_bg_size ) ? '-' . $settings->icon_bg_size / 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard .tnit-btn-bb-iconLeft",
		'enabled'  => 'simple' !== $settings->icon_style,
		'media'    => 'medium',
		'props'    => array(
			'left' => ( '' !== $settings->icon_bg_size_medium ) ? '-' . $settings->icon_bg_size_medium / 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard .tnit-btn-bb-iconLeft",
		'enabled'  => 'simple' !== $settings->icon_style,
		'media'    => 'responsive',
		'props'    => array(
			'left' => ( '' !== $settings->icon_bg_size_responsive ) ? '-' . $settings->icon_bg_size_responsive / 2 . 'px' : '',
		),
	)
);
// IconBox right.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard .tnit-btn-bb-iconRight",
		'enabled'  => 'simple' !== $settings->icon_style,
		'media'    => 'default',
		'props'    => array(
			'right' => ( '' !== $settings->icon_bg_size ) ? '-' . $settings->icon_bg_size / 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard .tnit-btn-bb-iconRight",
		'enabled'  => 'simple' !== $settings->icon_style,
		'media'    => 'medium',
		'props'    => array(
			'right' => ( '' !== $settings->icon_bg_size_medium ) ? '-' . $settings->icon_bg_size_medium / 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard .tnit-btn-bb-iconRight",
		'enabled'  => 'simple' !== $settings->icon_style,
		'media'    => 'responsive',
		'props'    => array(
			'right' => ( '' !== $settings->icon_bg_size_responsive ) ? '-' . $settings->icon_bg_size_responsive / 2 . 'px' : '',
		),
	)
);

// Icon border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_border_style',
		'selector'     => ".fl-node-$id .tnit-button-icon-custom",
		'enabled'      => 'custom' === $settings->icon_style,
	)
);
// Icon Border Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard .tnit-button-icon-simple,
						.fl-node-$id .tnit-btn-standard .tnit-button-icon-circle,
						.fl-node-$id .tnit-btn-standard .tnit-button-icon-square",
		'props'    => array(
			'border-color' => $settings->icon_border_color,
		),
	)
);

// Icon Border Hover Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-btn-standard:hover .tnit-btn-bb-icon",
		'props'    => array(
			'border-color' => $settings->icon_border_hover_color,
		),
	)
);

// Typography Rule.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'my_typography',
		'selector'     => ".fl-node-$id .tnit-btn-standard",
	)
);
