<?php

// meta color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-meta-cls li",
		'props'    => array(
			'color' => $settings->meta_color,
		),
	)
);

// meta link color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-meta-cls li a",
		'props'    => array(
			'color' => $settings->meta_link_color,
		),
	)
);

if ( 'yes' === $settings->display_border ) :
	// border color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-post-meta-cls > li::before",
			'props'    => array(
				'color' => $settings->border_color,
			),
		)
	);
endif;

// space btw.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-meta-cls li",
		'props'    => array(
			'padding-left'  => $settings->space_btw . $settings->space_btw_unit,
			'padding-right' => $settings->space_btw . $settings->space_btw_unit,
		),
	)
);


// typo.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'meta_typography',
		'selector'     => ".fl-node-$id .xpro-post-meta-cls li",
	)
);

// typo link.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'meta_typography_link',
		'selector'     => ".fl-node-$id .xpro-post-meta-cls li a",
	)
);
