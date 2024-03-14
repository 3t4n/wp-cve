<?php

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'alignment',
		'selector'     => ".fl-node-$id .xpro-simple-heading-wrapper",
		'prop'         => 'text-align',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .xpro-heading-title",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-title",
		'enabled'  => 'color' === $settings->title_color_type,
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-title",
		'enabled'  => 'gradient' === $settings->title_color_type,
		'props'    => array(
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'transparent',
			'background-image'        => FLBuilderColor::gradient( $settings->title_gradient ),
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_stroke_width',
		'enabled'      => 'stroke' === $settings->title_stroke_txt_type,
		'selector'     => ".fl-node-$id .xpro-heading-title",
		'unit'         => 'px',
		'prop'         => '-webkit-text-stroke-width',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-title",
		'enabled'  => 'stroke' === $settings->title_stroke_txt_type,
		'props'    => array(
			'-webkit-text-stroke-color' => $settings->title_stroke_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-title",
		'props'    => array(
			'mix-blend-mode' => $settings->title_blend_mode_type,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'center_title_typography',
		'selector'     => ".fl-node-$id .xpro-title-focus",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-title-focus",
		'enabled'  => 'color' === $settings->center_title_color_type,
		'props'    => array(
			'color' => $settings->center_title_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-title-focus",
		'enabled'  => 'gradient' === $settings->center_title_color_type,
		'props'    => array(
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'transparent',
			'background-image'        => FLBuilderColor::gradient( $settings->center_title_gradient ),
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-title-focus",
		'enabled'  => 'color' === $settings->center_title_bg_type,
		'props'    => array(
			'background-color' => $settings->center_title_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-title-focus",
		'enabled'  => 'gradient' === $settings->center_title_bg_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->center_title_bg_gradient ),
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'center_title_stroke_width',
		'selector'     => ".fl-node-$id .xpro-title-focus",
		'enabled'      => 'stroke' === $settings->center_title_stroke_txt_type,
		'unit'         => 'px',
		'prop'         => '-webkit-text-stroke-width',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-title-focus",
		'enabled'  => 'stroke' === $settings->center_title_stroke_txt_type,
		'props'    => array(
			'-webkit-text-stroke-color' => $settings->center_title_stroke_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-title-focus",
		'props'    => array(
			'mix-blend-mode' => $settings->center_title_blend_mode_type,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'center_title_border',
		'selector'     => ".fl-node-$id .xpro-title-focus",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'center_title_padding',
		'selector'     => ".fl-node-$id .xpro-title-focus",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'center_title_padding_top',
			'padding-right'  => 'center_title_padding_right',
			'padding-bottom' => 'center_title_padding_bottom',
			'padding-left'   => 'center_title_padding_left',
		),
	)
);
