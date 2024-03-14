<?php

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'align',
		'selector'     => ".fl-node-$id .xpro-post-content",
		'prop'         => 'text-align',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'content_typography',
		'selector'     => ".fl-node-$id .xpro-post-content",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-content",
		'props'    => array(
			'color' => $settings->content_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id a",
		'props'    => array(
			'color' => $settings->link_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id a:hover",
		'props'    => array(
			'fill' => $settings->link_hv_color,
		),
	)
);
