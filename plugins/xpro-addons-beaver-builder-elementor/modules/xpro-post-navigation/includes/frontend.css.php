<?php

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'label_typography',
		'selector'     => ".fl-node-$id span.xpro-post-navigation-prev-label,.fl-node-$id span.xpro-post-navigation-next-label",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-prev-label,.fl-node-$id .xpro-post-navigation-next-label",
		'props'    => array(
			'color' => $settings->label_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-link > a:hover .xpro-post-navigation-prev-label, .xpro-post-navigation-link > a:hover .xpro-post-navigation-next-label",
		'props'    => array(
			'color' => $settings->label_hv_color,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id span.xpro-post-navigation-prev-title,.fl-node-$id span.xpro-post-navigation-next-title",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id span.xpro-post-navigation-prev-title,.fl-node-$id span.xpro-post-navigation-next-title",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-link > a:hover .xpro-post-navigation-prev-title,.fl-node-$id .xpro-post-navigation-link > a:hover .xpro-post-navigation-next-title",
		'props'    => array(
			'color' => $settings->title_hv_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-prev > i,.fl-node-$id .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-next > i",
		'props'    => array(
			'color' => $settings->arrow_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-link > a:hover .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-prev > i,.fl-node-$id .xpro-post-navigation-link > a:hover .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-next > i",
		'props'    => array(
			'color' => $settings->arrow_hv_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'arrow_size',
		'selector'     => ".fl-node-$id .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-prev > i,.fl-node-$id .xpro-post-navigation-arrow-wrapper.xpro-post-navigation-arrow-next > i",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-arrow-next",
		'unit'     => 'px',
		'props'    => array(
			'padding-right' => ( $settings->arrow_gap ) ? $settings->arrow_gap . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-arrow-prev",
		'props'    => array(
			'padding-left' => ( $settings->arrow_gap ) ? $settings->arrow_gap . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-separator",
		'props'    => array(
			'background-color' => $settings->separator_color,
			'color'            => $settings->separator_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation .xpro-post-navigation-separator",
		'props'    => array(
			'width' => $settings->separator_size . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation",
		'props'    => array(
			'border-top-width'    => $settings->separator_size . 'px',
			'border-bottom-width' => $settings->separator_size . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-next.xpro-post-navigation-link,.fl-node-$id .xpro-elementor-post-navigation-prev.xpro-elementor-post-navigation-link",
		'props'    => array(
			'width' => ( $settings->separator_size ) ? ( ( 50 % - $settings->separator_size . 'px' ) / 2 ) : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation .xpro-post-navigation-separator",
		'media'    => 'medium',
		'props'    => array(
			'width' => $settings->separator_size . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation",
		'media'    => 'medium',
		'props'    => array(
			'border-top-width'    => $settings->separator_size . 'px',
			'border-bottom-width' => $settings->separator_size . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-next.xpro-post-navigation-link,.fl-node-$id .xpro-elementor-post-navigation-prev.xpro-elementor-post-navigation-link",
		'media'    => 'medium',
		'props'    => array(
			'width' => ( $settings->separator_size ) ? ( ( 50 % - $settings->separator_size . 'px' ) / 2 ) : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation .xpro-post-navigation-separator",
		'media'    => 'responsive',
		'props'    => array(
			'width' => $settings->separator_size . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation",
		'media'    => 'responsive',
		'props'    => array(
			'border-top-width'    => $settings->separator_size . 'px',
			'border-bottom-width' => $settings->separator_size . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-navigation-next.xpro-post-navigation-link,.fl-node-$id .xpro-elementor-post-navigation-prev.xpro-elementor-post-navigation-link",
		'media'    => 'responsive',
		'props'    => array(
			'width' => ( $settings->separator_size ) ? ( ( 50 % - $settings->separator_size . 'px' ) / 2 ) : '',
		),
	)
);
