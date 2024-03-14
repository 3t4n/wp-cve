<?php

// Photo Alignment.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'photo_alignment',
		'selector'     => ".fl-node-$id .tnit-module-image-icon",
		'enabled'      => 'photo' === $settings->image_type,
		'prop'         => 'text-align',
	)
);
// Image Effects.
$filter  = '';
$filter .= ( '' !== $settings->photo_blur ) ? 'blur(' . $settings->photo_blur . 'px)' : '';
$filter .= ( '' !== $settings->photo_brightness ) ? ' brightness(' . $settings->photo_brightness . '%)' : '';
$filter .= ( '' !== $settings->photo_contrast ) ? ' contrast(' . $settings->photo_contrast . '%)' : '';
$filter .= ( '' !== $settings->photo_grayscale ) ? ' grayscale(' . $settings->photo_grayscale . '%)' : '';
$filter .= ( '' !== $settings->photo_hue_rotate ) ? ' hue-rotate(' . $settings->photo_hue_rotate . 'deg)' : '';
$filter .= ( '' !== $settings->photo_invert ) ? ' invert(' . $settings->photo_invert . '%)' : '';
$filter .= ( '' !== $settings->photo_opacity ) ? ' opacity(' . $settings->photo_opacity . '%)' : '';
$filter .= ( '' !== $settings->photo_saturate ) ? ' saturate(' . $settings->photo_saturate . '%)' : '';
$filter .= ( '' !== $settings->photo_sepia ) ? ' sepia(' . $settings->photo_sepia . '%)' : '';

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-image-item img",
		'props'    => array(
			'filter' => $filter,
		),
	)
);
// Image Effects on Hover.
$filter_hover  = '';
$filter_hover .= ( '' !== $settings->photo_blur_hover ) ? 'blur(' . $settings->photo_blur_hover . 'px)' : '';
$filter_hover .= ( '' !== $settings->photo_brightness_hover ) ? ' brightness(' . $settings->photo_brightness_hover . '%)' : '';
$filter_hover .= ( '' !== $settings->photo_contrast_hover ) ? ' contrast(' . $settings->photo_contrast_hover . '%)' : '';
$filter_hover .= ( '' !== $settings->photo_grayscale_hover ) ? ' grayscale(' . $settings->photo_grayscale_hover . '%)' : '';
$filter_hover .= ( '' !== $settings->photo_hue_rotate_hover ) ? ' hue-rotate(' . $settings->photo_hue_rotate_hover . 'deg)' : '';
$filter_hover .= ( '' !== $settings->photo_invert_hover ) ? ' invert(' . $settings->photo_invert_hover . '%)' : '';
$filter_hover .= ( '' !== $settings->photo_opacity_hover ) ? ' opacity(' . $settings->photo_opacity_hover . '%)' : '';
$filter_hover .= ( '' !== $settings->photo_saturate_hover ) ? ' saturate(' . $settings->photo_saturate_hover . '%)' : '';
$filter_hover .= ( '' !== $settings->photo_sepia_hover ) ? ' sepia(' . $settings->photo_sepia_hover . '%)' : '';

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-image-item:hover img",
		'props'    => array(
			'filter' => $filter_hover,
		),
	)
);
// Overlay Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-image-item_effect2:before, 
						.fl-node-$id .tnit-image-item_effect1:after, 
						.fl-node-$id .tnit-image-item_effect3:after,
						.fl-node-$id .tnit-image-cricle_effect:before",
		'enabled'  => 'color' === $settings->overlay_color_type,
		'props'    => array(
			'background-color' => $settings->overlay_color,
		),
	)
);
// Gradient.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-image-item_effect2:before, 
						.fl-node-$id .tnit-image-item_effect1:after,
						.fl-node-$id .tnit-image-item_effect3:after,
						.fl-node-$id .tnit-image-cricle_effect:before",
		'enabled'  => 'gradient' === $settings->overlay_color_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->overlay_gradient ),
		),
	)
);
// Photo Width.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_size',
		'selector'     => ".fl-node-$id .tnit-image-item,
						.fl-node-$id .tnit-image-item_effect2:before",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);
// Photo Height.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_size',
		'selector'     => ".fl-node-$id .tnit-image-item,
						.fl-node-$id .tnit-image-item_effect2:before",
		'enabled'      => 'simple' !== $settings->image_style,
		'prop'         => 'height',
		'unit'         => 'px',
	)
);
// Border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'photo_border',
		'selector'     => ".fl-node-$id .photo-custom-style",
	)
);
// Border Hover.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'photo_border_hover',
		'selector'     => ".fl-node-$id .photo-custom-style:hover",
	)
);
// Background Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .photo-custom-style",
		'enabled'  => 'custom' === $settings->image_style,
		'props'    => array(
			'background-color' => $settings->img_bg_color,
		),
	)
);

// Content Padding Rule.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'photo_padding',
		'selector'     => ".fl-node-$id .photo-custom-style",
		'enabled'      => 'custom' === $settings->image_style,
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'photo_padding_top',
			'padding-right'  => 'photo_padding_right',
			'padding-bottom' => 'photo_padding_bottom',
			'padding-left'   => 'photo_padding_left',
		),
	)
);

// Icon Font Size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .tnit-photo-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);
// Icon Font Size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_alignment',
		'selector'     => ".fl-node-$id .tnit-photo-icon-wrapper",
		'prop'         => 'text-align',

	)
);
// Icon Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-photo-icon",
		'props'    => array(
			'color' => $settings->icon_color,
		),
	)
);
// Icon Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-photo-icon:hover",
		'props'    => array(
			'color' => $settings->icon_hover_color,
		),
	)
);
// Icon Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-photo-icon-circle,
		 				.fl-node-$id .tnit-photo-icon-square, 
		 				.fl-node-$id .tnit-photo-icon-custom",
		'props'    => array(
			'background-color' => $settings->icon_bg_color,
		),
	)
);
// Icon Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-photo-icon-circle:hover,
						.fl-node-$id .tnit-photo-icon-square:hover, 
						.fl-node-$id .tnit-photo-icon-custom:hover",
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
		'selector'     => ".fl-node-$id .tnit-photo-icon-circle,
							.fl-node-$id .tnit-photo-icon-square,
							.fl-node-$id .tnit-photo-icon-custom",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);
// Icon background size (height).
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_bg_size',
		'selector'     => ".fl-node-$id .tnit-photo-icon-circle,
							.fl-node-$id .tnit-photo-icon-square,
							.fl-node-$id .tnit-photo-icon-custom",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);
// Icon border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_border_style',
		'selector'     => ".fl-node-$id .tnit-photo-icon-custom",
	)
);
// Icon border hover.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_border_hover',
		'selector'     => ".fl-node-$id .tnit-photo-icon-custom:hover",
	)
);
