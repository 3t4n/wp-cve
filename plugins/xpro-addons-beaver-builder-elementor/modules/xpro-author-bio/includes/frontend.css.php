<?php

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'author_width',
		'selector'     => ".fl-node-$id .xpro-author-box-avatar > img",
		'prop'         => 'width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'author_Height',
		'selector'     => ".fl-node-$id .xpro-author-box-avatar > img",
		'prop'         => 'height',
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'author_border',
		'selector'     => ".fl-node-$id .xpro-author-box-avatar > img",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'author_margin',
		'selector'     => ".fl-node-$id .xpro-author-box-avatar > img",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'author_margin_top',
			'margin-right'  => 'author_margin_right',
			'margin-bottom' => 'author_margin_bottom',
			'margin-left'   => 'author_margin_left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-author-box-name",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .xpro-author-box-name",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_margin',
		'selector'     => ".fl-node-$id .xpro-author-box-name",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'title_margin_top',
			'margin-right'  => 'title_margin_right',
			'margin-bottom' => 'title_margin_bottom',
			'margin-left'   => 'title_margin_left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-author-box-bio",
		'props'    => array(
			'color' => $settings->Bio_color,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'Bio_typography',
		'selector'     => ".fl-node-$id .xpro-author-box-bio",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'Bio_margin',
		'selector'     => ".fl-node-$id .xpro-author-box-bio",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'Bio_margin_top',
			'margin-right'  => 'Bio_margin_right',
			'margin-bottom' => 'Bio_margin_bottom',
			'margin-left'   => 'Bio_margin_left',
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_typography',
		'selector'     => ".fl-node-$id .xpro-author-box-button",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-author-box-button",
		'props'    => array(
			'color' => $settings->button_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-author-box-button",
		'props'    => array(
			'background-color' => $settings->button_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-author-box-button:hover, .xpro-author-box-button:focus",
		'props'    => array(
			'color' => $settings->button_hv_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-author-box-button:hover, .xpro-author-box-button:focus",
		'props'    => array(
			'background-color' => $settings->button_bg_hv_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-author-box-button:hover, .xpro-author-box-button:focus",
		'props'    => array(
			'border-color' => $settings->button_border_hv_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_border',
		'selector'     => ".fl-node-$id .xpro-author-box-button",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_padding',
		'selector'     => ".fl-node-$id .xpro-author-box-button",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'button_padding_top',
			'padding-right'  => 'button_padding_right',
			'padding-bottom' => 'button_padding_bottom',
			'padding-left'   => 'button_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => ' button_margin',
		'selector'     => ".fl-node-$id .xpro-author-box-button",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'button_margin_top',
			'margin-right'  => 'button_margin_right',
			'margin-bottom' => 'button_margin_bottom',
			'margin-left'   => 'button_margin_left',
		),
	)
);
