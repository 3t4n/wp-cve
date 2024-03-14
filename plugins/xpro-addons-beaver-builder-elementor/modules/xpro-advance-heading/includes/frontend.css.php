<?php
/**
 * This file should contain frontend styles that
 * will be applied to individual module instances.
 *
 * You have access to three variables in this file:
 *
 * $module An instance of your module class.
 * $id The module's ID.
 * $settings The module's settings.
 */

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_general_custom_width',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper-inner",
		'prop'         => 'max-width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_general_alignment',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper",
		'prop'         => 'text-align',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper-inner, .xpro-heading-wrapper .xpro-heading-top",
		'props'    => array(
			'align-items' => $settings->adv_vertical_alignment,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'general_margin',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'general_margin_top',
			'margin-right'  => 'general_margin_right',
			'margin-bottom' => 'general_margin_bottom',
			'margin-left'   => 'general_margin_left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:after",
		'props'    => array(
			'display' => ( 'right' === $settings->adv_general_alignment ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:before",
		'props'    => array(
			'display' => ( 'left' === $settings->adv_general_alignment ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-double",
		'props'    => array(
			'padding-right' => ( 'right' === $settings->adv_general_alignment ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->adv_general_alignment ) ? '0' : '',
		),
	)
);

/*
==========================
	Float Desktop
  ==========================*/
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-wrapper-inner",
		'props'    => array(
			'flex-direction' => ( 'right' === $settings->adv_general_alignment ) ? 'row-reverse' : '',
			'display'        => ( 'center' === $settings->adv_general_alignment ) ? 'inline-block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-shadow-text",
		'props'    => array(
			'transform' => ( 'right' === $settings->adv_general_alignment ) ? 'translateX(-66px)' : ( ( 'left' === $settings->adv_general_alignment ) ? 'translateX(73px)' : '' ),
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon:after",
		'props'    => array(
			'display' => ( 'right' === $settings->adv_general_alignment ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon:before",
		'props'    => array(
			'display' => ( 'left' === $settings->adv_general_alignment ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-double",
		'props'    => array(
			'padding-right' => ( 'right' === $settings->adv_general_alignment ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->adv_general_alignment ) ? '0' : '',
		),
	)
);

/*
 ==========================
	Inside Desktop
   ========================== */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-top",
		'props'    => array(
			'flex-direction' => ( 'right' === $settings->adv_general_alignment ) ? 'row-reverse' : '',
			'display'        => ( 'center' === $settings->adv_general_alignment ) ? 'inline-block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-shadow-text",
		'props'    => array(
			'transform' => ( 'right' === $settings->adv_general_alignment ) ? 'translateX(-66px)' : ( ( 'left' === $settings->adv_general_alignment ) ? 'translateX(73px)' : '' ),
		),
	)
);

// ************************ Separator text / icon ****************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon:after",
		'props'    => array(
			'display' => ( 'right' === $settings->adv_general_alignment ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon:before",
		'props'    => array(
			'display' => ( 'left' === $settings->adv_general_alignment ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-double",
		'props'    => array(
			'padding-right' => ( 'right' === $settings->adv_general_alignment ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->adv_general_alignment ) ? '0' : '',
		),
	)
);

// ************************ Behind Desktop ****************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:after",
		'props'    => array(
			'display' => ( 'right' === $settings->adv_general_alignment ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:before",
		'props'    => array(
			'display' => ( 'left' === $settings->adv_general_alignment ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-double",
		'props'    => array(
			'padding-right' => ( 'right' === $settings->adv_general_alignment ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->adv_general_alignment ) ? '0' : '',
		),
	)
);

// ********************** Top Tablet Medium ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:after",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'right' === $settings->general_alignment_medium ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:before",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'left' === $settings->general_alignment_medium ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:after",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'center' === $settings->general_alignment_medium ) ? 'block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:before",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'center' === $settings->general_alignment_medium ) ? 'block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-double",
		'media'    => 'medium',
		'props'    => array(
			'padding-right' => ( 'right' === $settings->general_alignment_medium ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->general_alignment_medium ) ? '0' : '',
		),
	)
);

// ********************** Float Tablet Medium ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-wrapper-inner",
		'media'    => 'medium',
		'props'    => array(
			'display'        => ( 'right' === $settings->general_alignment_medium ) ? 'inline-flex' : ( ( 'left' === $settings->general_alignment_medium ) ? 'inline-flex' : ( ( 'center' === $settings->general_alignment_medium ) ? 'inline-block' : '' ) ),
			'flex-direction' => ( 'right' === $settings->general_alignment_medium ) ? 'row-reverse' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-shadow-text",
		'media'    => 'medium',
		'props'    => array(
			'transform' => ( 'right' === $settings->general_alignment_medium ) ? 'translateX(-66px)' : ( ( 'left' === $settings->general_alignment_medium ) ? 'translateX(73px)' : ( ( 'center' === $settings->general_alignment_medium ) ? 'translateX(0)' : '' ) ),
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon:after",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'right' === $settings->general_alignment_medium ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon:before",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'left' === $settings->general_alignment_medium ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-double",
		'media'    => 'medium',
		'props'    => array(
			'padding-right' => ( 'right' === $settings->general_alignment_medium ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->general_alignment_medium ) ? '0' : '',
		),
	)
);

// ********************** Inside Tablet Medium ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-top",
		'media'    => 'medium',
		'props'    => array(
			'display'        => ( 'left' === $settings->general_alignment_medium ) ? 'inline-flex' : ( ( 'right' === $settings->general_alignment_medium ) ? 'inline-flex' : ( ( 'center' === $settings->general_alignment_medium ) ? 'inline-block' : '' ) ),
			'flex-direction' => ( 'right' === $settings->general_alignment_medium ) ? 'row-reverse' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-shadow-text",
		'media'    => 'medium',
		'props'    => array(
			'transform' => ( 'right' === $settings->general_alignment_medium ) ? 'translateX(-66px)' : ( ( 'left' === $settings->general_alignment_medium ) ? 'translateX(73px)' : '' ),
		),
	)
);

// ********************** separator text / icon ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon:after",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'right' === $settings->general_alignment_medium ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon:before",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'left' === $settings->general_alignment_medium ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-double",
		'media'    => 'medium',
		'props'    => array(
			'padding-right' => ( 'right' === $settings->general_alignment_medium ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->general_alignment_medium ) ? '0' : '',
		),
	)
);

// ********************** Behind Tablet Medium ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:after",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'right' === $settings->general_alignment_medium ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:before",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'left' === $settings->general_alignment_medium ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:after",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'center' === $settings->general_alignment_medium ) ? 'block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:before",
		'media'    => 'medium',
		'props'    => array(
			'display' => ( 'center' === $settings->general_alignment_medium ) ? 'block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-double",
		'media'    => 'medium',
		'props'    => array(
			'padding-right' => ( 'right' === $settings->general_alignment_medium ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->general_alignment_medium ) ? '0' : '',
		),
	)
);

// ********************** Top Mobile Responsive ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:after",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'right' === $settings->general_alignment_responsive ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:before",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'left' === $settings->general_alignment_responsive ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:after",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'center' === $settings->general_alignment_responsive ) ? 'block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon:before",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'center' === $settings->general_alignment_responsive ) ? 'block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-top .xpro-heading-separator-double",
		'media'    => 'responsive',
		'props'    => array(
			'padding-right' => ( 'right' === $settings->general_alignment_responsive ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->general_alignment_responsive ) ? '0' : '',
		),
	)
);

// ********************** Float Mobile Responsive ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-wrapper-inner",
		'media'    => 'responsive',
		'props'    => array(
			'display'        => ( 'right' === $settings->general_alignment_responsive ) ? 'inline-flex' : ( ( 'left' === $settings->general_alignment_responsive ) ? 'inline-flex' : ( ( 'center' === $settings->general_alignment_responsive ) ? 'inline-block' : '' ) ),
			'flex-direction' => ( 'right' === $settings->general_alignment_responsive ) ? 'row-reverse' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-shadow-text",
		'media'    => 'responsive',
		'props'    => array(
			'transform' => ( 'right' === $settings->general_alignment_responsive ) ? 'translateX(-46px)' : ( ( 'left' === $settings->general_alignment_responsive ) ? 'translateX(46px)' : ( ( 'center' === $settings->general_alignment_responsive ) ? 'translateX(0)' : '' ) ),
		),
	)
);

// ********************** separator text ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon:after",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'right' === $settings->general_alignment_responsive ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon:before",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'left' === $settings->general_alignment_responsive ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-float .xpro-heading-separator-double",
		'media'    => 'responsive',
		'props'    => array(
			'padding-right' => ( 'right' === $settings->general_alignment_responsive ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->general_alignment_responsive ) ? '0' : '',
		),
	)
);

// ********************** Inside Mobile Responsive ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-top",
		'media'    => 'responsive',
		'props'    => array(
			'display'        => ( 'left' === $settings->general_alignment_responsive ) ? 'inline-flex' : ( ( 'right' === $settings->general_alignment_responsive ) ? 'inline-flex' : ( ( 'center' === $settings->general_alignment_responsive ) ? 'inline-block' : '' ) ),
			'flex-direction' => ( 'right' === $settings->general_alignment_responsive ) ? 'row-reverse' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-shadow-text",
		'media'    => 'responsive',
		'props'    => array(
			'transform' => ( 'right' === $settings->general_alignment_responsive ) ? 'translateX(-46px)' : ( ( 'left' === $settings->general_alignment_responsive ) ? 'translateX(46px)' : '' ),
		),
	)
);

// ********************** separator text ***************************//
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon:after",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'right' === $settings->general_alignment_responsive ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon:before",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'left' === $settings->general_alignment_responsive ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-inside .xpro-heading-separator-double",
		'media'    => 'responsive',
		'props'    => array(
			'padding-right' => ( 'right' === $settings->general_alignment_responsive ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->general_alignment_responsive ) ? '0' : '',
		),
	)
);

/*
==========================
	Behind Mobile Responsive
  ==========================*/

// ********************** separator text / icon ***************************//

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:after",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'right' === $settings->general_alignment_responsive ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:before",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'left' === $settings->general_alignment_responsive ) ? 'none' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:after,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:after",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'center' === $settings->general_alignment_responsive ) ? 'block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text:before,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon:before",
		'media'    => 'responsive',
		'props'    => array(
			'display' => ( 'center' === $settings->general_alignment_responsive ) ? 'block' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-text,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-icon,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-icon-position-behind .xpro-heading-separator-double",
		'media'    => 'responsive',
		'props'    => array(
			'padding-right' => ( 'right' === $settings->general_alignment_responsive ) ? '0' : '',
			'padding-left'  => ( 'left' === $settings->general_alignment_responsive ) ? '0' : '',
		),
	)
);

/*
==========================
	Style > Title
  ==========================*/

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_title_typography',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
		'props'    => array(
			'color' => $settings->adv_title_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
		'enabled'  => 'gradient' === $settings->adv_title_color_type,
		'props'    => array(
			'background-image'        => FLBuilderColor::gradient( $settings->adv_title_gradient ),
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'ff000000',
			'background-color'        => 'ff000000',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
		'enabled'  => 'photo' === $settings->adv_title_color_type,
		'props'    => array(
			'background-image'        => $settings->title_image_masking_src,
			'background-position'     => $settings->adv_title_image_position,
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'ff000000',
			'background-color'        => 'ff000000',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
		'props'    => array(
			'background-color' => $settings->adv_title_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
		'enabled'  => 'gradient' === $settings->adv_title_background_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->adv_title_background_gradient ),
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_title_border',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_title_margin',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'title_margin_top',
			'margin-right'  => 'title_margin_right',
			'margin-bottom' => 'title_margin_bottom',
			'margin-left'   => 'title_margin_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_title_padding',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-title",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'title_padding_top',
			'padding-right'  => 'title_padding_right',
			'padding-bottom' => 'title_padding_bottom',
			'padding-left'   => 'title_padding_left',
		),
	)
);

/* ================================
	Style > Center Title
   ================================ */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_center_title_typography',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-title-focus",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-title-focus",
		'props'    => array(
			'color' => $settings->adv_center_title_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-title-focus",
		'enabled'  => 'gradient' === $settings->adv_center_title_color_type,
		'props'    => array(
			'background-image'        => FLBuilderColor::gradient( $settings->adv_center_title_gradient ),
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'ff000000',
			'background-color'        => 'ff000000',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-title-focus",
		'enabled'  => 'photo' === $settings->adv_center_title_color_type,
		'props'    => array(
			'background-image'        => $settings->center_title_image_masking_src,
			'background-position'     => $settings->adv_center_title_image_position,
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'ff000000',
			'background-color'        => 'ff000000',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-title-focus",
		'props'    => array(
			'background-color' => $settings->adv_center_title_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-title-focus",
		'enabled'  => 'gradient' === $settings->adv_center_title_background_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->adv_center_title_background_gradient ),
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_center_title_border',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-title-focus",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_center_title_padding',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-title-focus",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'adv_center_title_padding_top',
			'padding-right'  => 'adv_center_title_padding_right',
			'padding-bottom' => 'adv_center_title_padding_bottom',
			'padding-left'   => 'adv_center_title_padding_left',
		),
	)
);

/* ================================
	Style > SubTitle
   ================================ */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_subtitle_typography',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
		'props'    => array(
			'color' => $settings->adv_subtitle_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
		'enabled'  => 'gradient' === $settings->adv_subtitle_color_type,
		'props'    => array(
			'background-image'        => FLBuilderColor::gradient( $settings->adv_subtitle_gradient ),
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'ff000000',
			'background-color'        => 'ff000000',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
		'enabled'  => 'photo' === $settings->adv_subtitle_color_type,
		'props'    => array(
			'background-image'        => $settings->subtitle_image_masking_src,
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'ff000000',
			'background-color'        => 'ff000000',
			'background-position'     => 'center center',

		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
		'props'    => array(
			'background-color' => $settings->adv_subtitle_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
		'enabled'  => 'gradient' === $settings->adv_subtitle_background_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->adv_title_background_gradient ),
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_subtitle_border',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_subtitle_margin',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'subtitle_margin_top',
			'margin-right'  => 'subtitle_margin_right',
			'margin-bottom' => 'subtitle_margin_bottom',
			'margin-left'   => 'subtitle_margin_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_subtitle_padding',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-subtitle",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'subtitle_padding_top',
			'padding-right'  => 'subtitle_padding_right',
			'padding-bottom' => 'subtitle_padding_bottom',
			'padding-left'   => 'subtitle_padding_left',
		),
	)
);

/* ================================
	Style > Description
   ================================ */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_description_typography',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-description",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-description",
		'props'    => array(
			'color' => $settings->adv_description_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'adv_description_margin',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-heading-description",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'description_margin_top',
			'margin-right'  => 'description_margin_right',
			'margin-bottom' => 'description_margin_bottom',
			'margin-left'   => 'description_margin_left',
		),
	)
);
/* ================================
	Style > Separator
   ================================ */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_text_typography',
		'selector'     => ".fl-node-$id .xpro-heading-separator-text",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-separator-text",
		'props'    => array(
			'color' => $settings->adv_separator_styles->adv_separator_text_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-separator-simple::before,.fl-node-$id .xpro-heading-separator-double:before,.fl-node-$id .xpro-heading-separator-double:after,.fl-node-$id .xpro-heading-separator-text::before,.fl-node-$id .xpro-heading-separator-text::after,.fl-node-$id .xpro-heading-separator-icon::before,.fl-node-$id .xpro-heading-separator-icon::after",
		'props'    => array(
			'border-color' => $settings->adv_separator_styles->adv_separator_after_before_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_width',
		'selector'     => ".fl-node-$id .xpro-heading-separator-simple::before,.fl-node-$id .xpro-heading-separator-double:before,.fl-node-$id .xpro-heading-separator-double:after,.fl-node-$id .xpro-heading-separator-text::before,.fl-node-$id .xpro-heading-separator-text::after,.fl-node-$id .xpro-heading-separator-icon::before,.fl-node-$id .xpro-heading-separator-icon::after",
		'unit'         => 'px',
		'prop'         => 'width',
	)
);

// separator width
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_width',
		'selector'     => ".fl-node-$id .xpro-heading-separator-simple,.fl-node-$id .xpro-heading-separator-double,.fl-node-$id .xpro-heading-separator-text,.fl-node-$id .xpro-heading-separator-icon",
		'props'        => array(
			'padding-right' => ( $settings->adv_separator_styles->adv_separator_width ) ? $settings->adv_separator_styles->adv_separator_width . 'px' : '',
			'padding-left'  => ( $settings->adv_separator_styles->adv_separator_width ) ? $settings->adv_separator_styles->adv_separator_width . 'px' : '',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_height',
		'selector'     => ".fl-node-$id .xpro-heading-separator-simple::before,.fl-node-$id .xpro-heading-separator-double:before,.fl-node-$id .xpro-heading-separator-double:after,.fl-node-$id .xpro-heading-separator-text::before,.fl-node-$id .xpro-heading-separator-text::after,.fl-node-$id .xpro-heading-separator-icon::before,.fl-node-$id .xpro-heading-separator-icon::after",
		'unit'         => 'px',
		'prop'         => 'border-top-width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_border_radius',
		'selector'     => ".fl-node-$id .xpro-heading-separator-simple::before,.fl-node-$id .xpro-heading-separator-double:before,.fl-node-$id .xpro-heading-separator-double:after,.fl-node-$id .xpro-heading-separator-text::before,.fl-node-$id .xpro-heading-separator-text::after,.fl-node-$id .xpro-heading-separator-icon::before,.fl-node-$id .xpro-heading-separator-icon::after",
		'prop'         => 'border-radius',
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_margin',
		'selector'     => ".fl-node-$id [class*=xpro-heading-separator]",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'separator_margin_top',
			'margin-right'  => 'separator_margin_right',
			'margin-bottom' => 'separator_margin_bottom',
			'margin-left'   => 'separator_margin_left',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_icon_size',
		'selector'     => ".fl-node-$id .xpro-heading-separator-icon > i",
		'prop'         => 'font-size',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_icon_background_size',
		'selector'     => ".fl-node-$id .xpro-heading-separator-icon > i",
		'prop'         => 'width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_icon_background_size',
		'selector'     => ".fl-node-$id .xpro-heading-separator-icon > i",
		'prop'         => 'height',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-separator-icon > i",
		'props'    => array(
			'color' => $settings->adv_separator_styles->adv_separator_icon_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-separator-icon > i",
		'props'    => array(
			'background-color' => $settings->adv_separator_styles->adv_separator_icon_background_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->adv_separator_styles,
		'setting_name' => 'adv_separator_icon_border',
		'selector'     => ".fl-node-$id .xpro-heading-separator-icon > i",
	)
);

//FLBuilderCSS::rule(
//	array(
//		'selector' => ".fl-node-$id [class*=xpro-heading-separator-shape] > svg",
//		'props'    => array(
//			'fill' => $settings->adv_separator_styles->adv_separator_shape_color,
//		),
//	)
//);
//
//FLBuilderCSS::responsive_rule(
//	array(
//		'settings'     => $settings->adv_separator_styles,
//		'setting_name' => 'adv_separator_shape_background_size',
//		'selector'     => ".fl-node-$id [class*=xpro-heading-separator-shape] > svg ",
//		'prop'         => 'width',
//	)
//);
//
//FLBuilderCSS::dimension_field_rule(
//	array(
//		'settings'     => $settings->adv_separator_styles,
//		'setting_name' => 'adv_separator_shape_margin',
//		'selector'     => ".fl-node-$id [class*=xpro-heading-separator-shape] > svg",
//		'unit'         => 'px',
//		'props'        => array(
//			'margin-top'    => 'separator_shape_margin_top',
//			'margin-right'  => 'separator_shape_margin_right',
//			'margin-bottom' => 'separator_shape_margin_bottom',
//			'margin-left'   => 'separator_shape_margin_left',
//		),
//	)
//);

/* ================================
	Style > Shadow
   ================================ */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->shadow_styles,
		'setting_name' => 'adv_shadow_typography',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
	)
);

FLBuilderCSS::rule(
	array(
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'setting_name' => 'adv_shadow_outline_text',
		'enabled'      => 'enable' === $settings->shadow_styles->adv_shadow_outline_type,
		'props'        => array(
			'-webkit-text-fill-color' => '00000000',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->shadow_styles,
		'setting_name' => 'adv_shadow_outline_width',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'enabled'      => 'enable' === $settings->shadow_styles->adv_shadow_outline_type,
		'prop'         => '-webkit-text-stroke-width',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'enabled'  => 'enable' === $settings->shadow_styles->adv_shadow_outline_type,
		'props'    => array(
			'-webkit-text-stroke-color' => $settings->shadow_styles->adv_shadow_outline_text,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'props'    => array(
			'color' => $settings->shadow_styles->adv_shadow_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'enabled'  => 'gradient' === $settings->shadow_styles->adv_shadow_color_type,
		'props'    => array(
			'background-image'        => FLBuilderColor::gradient( $settings->shadow_styles->adv_shadow_gradient ),
			'-webkit-background-clip' => 'text',
			'-webkit-text-fill-color' => 'ff000000',
			'background-color'        => 'ff000000',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'props'    => array(
			'background-color' => $settings->shadow_styles->adv_shadow_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'enabled'  => 'gradient' === $settings->shadow_styles->adv_shadow_background_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->shadow_styles->adv_shadow_background_gradient ),
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->shadow_styles,
		'setting_name' => 'adv_shadow_vertical_offset',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'prop'         => '--xpro-shadow-translate-y',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->shadow_styles,
		'setting_name' => 'adv_shadow_horizontal_offset',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'prop'         => '--xpro-shadow-translate-x',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->shadow_styles,
		'setting_name' => 'adv_shadow_rotate',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'prop'         => '--xpro-shadow-rotate',
		'unit'         => 'deg',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'props'    => array(
			'transform-origin' => $settings->shadow_styles->adv_shadow_origin,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->shadow_styles,
		'setting_name' => 'adv_shadow_border',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->shadow_styles,
		'setting_name' => 'adv_shadow_padding',
		'selector'     => ".fl-node-$id .xpro-heading-wrapper .xpro-shadow-text",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'shadow_padding_top',
			'padding-right'  => 'shadow_padding_right',
			'padding-bottom' => 'shadow_padding_bottom',
			'padding-left'   => 'shadow_padding_left',
		),
	)
);
