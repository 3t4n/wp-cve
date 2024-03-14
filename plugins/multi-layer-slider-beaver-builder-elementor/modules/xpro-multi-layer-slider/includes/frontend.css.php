<?php

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'slider_height',
        'enabled' 	=> 'vertical' === $settings->slider_orientation,
		'selector'     => ".fl-node-$id .xpro-dynamic-slider,.fl-node-$id .xpro-dynamic-slider-content-area-blank",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);

/*
====================
	Navigation
  ====================*/
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_icon_size',
		'selector'     => ".fl-node-$id .slick-nav-prev, .fl-node-$id .slick-nav-next",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_bg_size',
		'selector'     => ".fl-node-$id .slick-nav-prev, .fl-node-$id .slick-nav-next",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_bg_size',
		'selector'     => ".fl-node-$id .slick-nav-prev, .fl-node-$id .slick-nav-next",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_bg_size',
		'selector'     => ".fl-node-$id .slick-nav-prev, .fl-node-$id .slick-nav-next",
		'prop'         => 'line-height',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_space_between',
		'selector'     => ".fl-node-$id .xpro-dynamic-slider-navigation",
		'prop'         => 'grid-gap',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_offset',
		'selector'     => ".fl-node-$id [class*=xpro-dynamic-slider-navigation-horizontal].xpro-dynamic-slider-navigation-position-default .slick-nav-prev",
		'prop'         => 'left',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_offset',
		'selector'     => ".fl-node-$id [class*=xpro-dynamic-slider-navigation-horizontal].xpro-dynamic-slider-navigation-position-default .slick-nav-next",
		'prop'         => 'right',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_offset',
		'selector'     => ".fl-node-$id [class*=xpro-dynamic-slider-navigation-vertical].xpro-dynamic-slider-navigation-position-default .slick-nav-prev",
		'prop'         => 'top',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_offset',
		'selector'     => ".fl-node-$id [class*=xpro-dynamic-slider-navigation-vertical].xpro-dynamic-slider-navigation-position-default .slick-nav-next",
		'prop'         => 'bottom',
		'unit'         => 'px',
	)
);


/* Normal */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .slick-nav-prev, .fl-node-$id .slick-nav-next",
		'props'    => array(
			'color' => $settings->nav_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .slick-nav-prev, .fl-node-$id .slick-nav-next",
		'props'    => array(
			'background-color' => $settings->nav_bg_color,
		),
	)
);

/* Hover */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .slick-nav-prev:hover, .fl-node-$id .slick-nav-next:hover",
		'props'    => array(
			'color' => $settings->nav_h_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .slick-nav-prev:hover, .fl-node-$id .slick-nav-next:hover",
		'props'    => array(
			'background-color' => $settings->nav_h_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .slick-nav-prev:hover, .fl-node-$id .slick-nav-next:hover",
		'props'    => array(
			'border-color' => $settings->nav_h_border_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_border',
		'selector'     => ".fl-node-$id .slick-nav-prev, .fl-node-$id .slick-nav-next",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'nav_margin',
		'selector'     => ".fl-node-$id .xpro-dynamic-slider-navigation",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'nav_margin_top',
			'margin-right'  => 'nav_margin_right',
			'margin-bottom' => 'nav_margin_bottom',
			'margin-left'   => 'nav_margin_left',
		),
	)
);

/*
==================
	Dots
  ==================*/
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dots_width',
		'selector'     => ".fl-node-$id .xpro-dynamic-slider .slick-dots > li > .slick-dot",
		'prop'         => '--xpro-dynamic-slider-dot-width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dots_height',
		'selector'     => ".fl-node-$id .xpro-dynamic-slider .slick-dots > li > .slick-dot",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dots_space_between',
		'selector'     => ".fl-node-$id .xpro-dynamic-slider .slick-dots > li",
		'prop'         => 'margin-right',
		'unit'         => 'px',
	)
);

/* Normal */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .slick-dots > li > .slick-dot",
		'props'    => array(
			'background-color' => $settings->dots_bg_color,
		),
	)
);

/* active */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .slick-dots > li.slick-active > .slick-dot",
		'props'    => array(
			'background-color' => $settings->dots_active_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .slick-dots > li.slick-active > .slick-dot",
		'props'    => array(
			'border-color' => $settings->dots_active_border_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dots_border',
		'selector'     => ".fl-node-$id .slick-dots > li > .slick-dot",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dots_margin',
		'selector'     => ".fl-node-$id .xpro-dynamic-slider .slick-dots",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'dots_margin_top',
			'margin-right'  => 'dots_margin_right',
			'margin-bottom' => 'dots_margin_bottom',
			'margin-left'   => 'dots_margin_left',
		),
	)
);