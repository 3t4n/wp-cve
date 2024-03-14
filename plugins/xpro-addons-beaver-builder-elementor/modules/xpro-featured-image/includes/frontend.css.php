<?php

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'featured_alignment',
		'selector'     => ".fl-node-$id .xpro-featured-image",
		'prop'         => 'text-align',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'featured_width',
		'selector'     => ".fl-node-$id .xpro-featured-image > img",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'featured_max_width',
		'selector'     => ".fl-node-$id .xpro-featured-image > img",
		'prop'         => 'max-width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'featured_height',
		'selector'     => ".fl-node-$id .xpro-featured-image > img",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-featured-image > img",
		'props'    => array(
			'opacity' => $settings->featured_image_opacity,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'featured_image_border',
		'selector'     => ".fl-node-$id .xpro-featured-image > img",
	)
);
