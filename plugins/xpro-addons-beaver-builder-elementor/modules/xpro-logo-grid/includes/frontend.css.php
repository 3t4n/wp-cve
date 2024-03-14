<?php

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-logo-grid-figure",
		'props'    => array(
			'background-color' => $settings->grid_gb,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'grid_height',
		'selector'     => ".fl-node-$id .xpro-logo-grid-item",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'grid_border',
		'selector'     => ".fl-node-$id .xpro-logo-grid-item",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'grid_padding',
		'selector'     => ".fl-node-$id .xpro-logo-grid-figure",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'grid_padding_top',
			'padding-right'  => 'grid_padding_right',
			'padding-bottom' => 'grid_padding_bottom',
			'padding-left'   => 'grid_padding_left',
		),
	)
);
