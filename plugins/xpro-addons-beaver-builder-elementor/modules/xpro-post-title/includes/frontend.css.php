<?php

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_padding',
		'selector'     => ".fl-node-$id .xpro-post-title-text",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'title_padding_top',
			'padding-right'  => 'title_padding_right',
			'padding-bottom' => 'title_padding_bottom',
			'padding-left'   => 'title_padding_left',
		),
	)
);

// title margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_margin',
		'selector'     => ".fl-node-$id .xpro-post-title-wrapper",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'title_margin_top',
			'margin-right'  => 'title_margin_right',
			'margin-bottom' => 'title_margin_bottom',
			'margin-left'   => 'title_margin_left',
		),
	)
);

// alignment.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'post_align',
		'selector'     => ".fl-node-$id .fl-module-content.fl-node-content",
		'prop'         => 'text-align',
	)
);
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .xpro-post-title-text",
	)
);

// title color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-title-text",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);
