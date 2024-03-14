<?php
/**
 * Advance InfoBox front-end CSS php file
 *
 * @package Advance InfoBox module
 * @since 1.0.25
 */

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infobox-holder .tnit-infoB",
		'props'    => array(
			'border-color' => '#eee',
		),
	)
);

// Image/Icon verticle alignment
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infobox-holder .tnit-img-position",
		'props'    => array(
			'-webkit-align-items' => $settings->imgicon_ver_alignment,
			'-moz-align-items'    => $settings->imgicon_ver_alignment,
			'-ms-align-items'     => $settings->imgicon_ver_alignment,
			'-o-align-items'      => $settings->imgicon_ver_alignment,
			'align-items'         => $settings->imgicon_ver_alignment,
		),
	)
);

// Image/Icon structure
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infobox-holder .tnit-img-position",
		'media'    => 'responsive',
		'enabled'  => 'stack' === $settings->mobile_structure,
		'props'    => array(
			'display'    => 'block',
			'text-align' => 'center !important',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infobox-holder .tnit-img-position .tnit-infobox-imgicon-wrap",
		'media'    => 'responsive',
		'enabled'  => 'stack' === $settings->mobile_structure,
		'props'    => array(
			'margin-left'  => '0px',
			'margin-right' => '0px',
		),
	)
);

// Overall alignment
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'overall_alignment',
		'selector'     => ".fl-node-$id .tnit-infobox-holder, .fl-node-$id .tnit-infobox-holder .tnit-infoBox",
		'prop'         => 'text-align',
	)
);

// Infobox border CSS.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'infoboxes_border',
		'selector'     => ".fl-node-$id .tnit-infobox-holder .tnit-infoBox",
	)
);

// Infobox border hover color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infobox-holder .tnit-infoBox:hover",
		'props'    => array(
			'border-color' => $settings->infoboxes_border_hvr_color,
		),
	)
);

// Background color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infobox-holder .tnit-infoBox",
		'props'    => array(
			'background-color' => $settings->infoboxes_bg_color,
		),
	)
);

// Background hover color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover, .fl-node-$id .tnit-infoBox:before",
		'props'    => array(
			'background-color' => $settings->infoboxes_bg_hvr_color,
		),
	)
);

// Infobox padding
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'infobox_padding',
		'selector'     => ".fl-node-$id .tnit-infobox-holder .tnit-infoBox",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'infobox_padding_top',
			'padding-right'  => 'infobox_padding_right',
			'padding-bottom' => 'infobox_padding_bottom',
			'padding-left'   => 'infobox_padding_left',
		),
	)
);


/**
 * Render rule/properties for Title Prefix
 *
 * @class .tnit-title-prefix
 * @since 1.0.25
 */

// Title Prefix typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_prefix_typography',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-title-prefix",
	)
);

// Title Prefix color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-title-prefix, .fl-node-$id .tnit-infoBox .tnit-title-prefix a",
		'props'    => array(
			'color' => $settings->title_prefix_color,
		),
	)
);

// Title Prefix hover color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover .tnit-title-prefix, .fl-node-$id .tnit-infoBox:hover .tnit-title-prefix a",
		'props'    => array(
			'color' => $settings->title_prefix_hover_color,
		),
	)
);

// Title Prefix margin top
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_prefix_padding',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-title-prefix-wrap",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'title_prefix_padding_top',
			'padding-right'  => 'title_prefix_padding_right',
			'padding-bottom' => 'title_prefix_padding_bottom',
			'padding-left'   => 'title_prefix_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_prefix_margin',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-title-prefix-wrap",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'title_prefix_margin_top',
			'margin-right'  => 'title_prefix_margin_right',
			'margin-bottom' => 'title_prefix_margin_bottom',
			'margin-left'   => 'title_prefix_margin_left',
		),
	)
);

/**
 * Render rule/properties for Title
 *
 * @class .info-title
 * @since 1.0.25
 */

// Title typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_font_typo',
		'selector'     => ".fl-node-$id .tnit-infoBox .info-title",
	)
);

// Title color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .info-title, .fl-node-$id .tnit-infoBox .info-title a",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);

// Title hover color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover .info-title, .fl-node-$id .tnit-infoBox:hover .info-title a",
		'props'    => array(
			'color' => $settings->title_hover_color,
		),
	)
);

// Title margin top - responsive
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_padding',
		'selector'     => ".fl-node-$id .tnit-infoBox .info-title",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'title_padding_top',
			'padding-right'  => 'title_padding_right',
			'padding-bottom' => 'title_padding_bottom',
			'padding-left'   => 'title_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_margin',
		'selector'     => ".fl-node-$id .tnit-infoBox .info-title",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'title_margin_top',
			'margin-right'  => 'title_margin_right',
			'margin-bottom' => 'title_margin_bottom',
			'margin-left'   => 'title_margin_left',
		),
	)
);

/**
 * Render rule/properties for Title Postfix
 *
 * @class .tnit-title-postfix
 * @since 1.0.25
 */

// Title Postfix typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_postfix_typography',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-title-postfix",
	)
);

// Title Postfix color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-title-postfix, .fl-node-$id .tnit-infoBox .tnit-title-postfix a",
		'props'    => array(
			'color' => $settings->title_postfix_color,
		),
	)
);

// Title Postfix hover color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover .tnit-title-postfix, .fl-node-$id .tnit-infoBox:hover .tnit-title-postfix a",
		'props'    => array(
			'color' => $settings->title_postfix_hover_color,
		),
	)
);

// Title Postfix margin top
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_postfix_padding',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-title-postfix-wrap",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'title_postfix_padding_top',
			'padding-right'  => 'title_postfix_padding_right',
			'padding-bottom' => 'title_postfix_padding_bottom',
			'padding-left'   => 'title_postfix_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_postfix_margin',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-title-postfix-wrap",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'title_postfix_margin_top',
			'margin-right'  => 'title_postfix_margin_right',
			'margin-bottom' => 'title_postfix_margin_bottom',
			'margin-left'   => 'title_postfix_margin_left',
		),
	)
);

/**
 * Render Description CSS rules
 *
 * @class .tnit-infobox-text-wrap
 * @since 1.0.25
 */

// Description typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'infoboxes_text_font_typo',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-text-wrap",
	)
);

// Description color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-text-wrap,.fl-node-$id .tnit-infoBox .tnit-infobox-text-wrap *",
		'props'    => array(
			'color' => $settings->infoboxes_text_color,
		),
	)
);

// Description hover color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover .tnit-infobox-text-wrap,.fl-node-$id .tnit-infoBox:hover .tnit-infobox-text-wrap *",
		'props'    => array(
			'color' => $settings->infoboxes_text_hover_color,
		),
	)
);

// Description margin top - responsive
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'desc_padding',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-text-wrap, .fl-node-$id .tnit-infoBox .tnit-infobox-text-wrap *",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'desc_padding_top',
			'padding-right'  => 'desc_padding_right',
			'padding-bottom' => 'desc_padding_bottom',
			'padding-left'   => 'desc_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'desc_margin',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-text-wrap, .fl-node-$id .tnit-infoBox .tnit-infobox-text-wrap *",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'desc_margin_top',
			'margin-right'  => 'desc_margin_right',
			'margin-bottom' => 'desc_margin_bottom',
			'margin-left'   => 'desc_margin_left',
		),
	)
);


/*--------------------------------------
 * Render rule/properties for Separator
 *-------------------------------------*/

// Separator alignment

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'separator_alignment',
		'selector'     => ".fl-node-$id .tnit-separator-wrapper",
		'prop'         => 'text-align',
	)
);


// Separator styles
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-separator",
		'props'    => array(
			'border-bottom-style' => $settings->separator_style,
			'border-bottom-color' => $settings->separator_color,
			'border-bottom-width' => ( '' !== $settings->separator_thickness ) ? $settings->separator_thickness . 'px' : '',
		),
	)
);

// Separator width
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'separator_width',
		'selector'     => ".fl-node-$id .tnit-separator",
		'prop'         => 'width',
	)
);

// Separator margin-top
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'separator_margin_top',
		'selector'     => ".fl-node-$id .tnit-separator",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);

// Separator margin-bottom
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'separator_margin_bottom',
		'selector'     => ".fl-node-$id .tnit-separator",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);


/**
 * =================================================
 * Render CTA Button CSS rules
 *
 * @class .tnit-infobox-button-wrap .tnit-btn-arrow
 * @since 1.0.25
 * =================================================
 */

// CTA Link Icon Colors
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap > a",
		'enabled'  => 'icon' === $settings->link_type,
		'props'    => array(
			'color' => $settings->cta_color,
		),
	)
);

// CTA Link Icon Colors
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow",
		'enabled'  => 'icon' === $settings->link_type,
		'props'    => array(
			'color'     => $settings->cta_color,
			'font-size' => ( '' !== $settings->cta_icon_size ) ? $settings->cta_icon_size . 'px' : '',
		),
	)
);

// CTA Link Icon Hover Colors
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover .tnit-infobox-button-wrap .tnit-btn-arrow",
		'enabled'  => 'icon' === $settings->link_type,
		'props'    => array(
			'color' => $settings->cta_hvr_color,
		),
	)
);

// CTA Button typography
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'infoboxes_cta_text_font_typo',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow",
	)
);

if ( 'button' === $settings->link_type ) {
	// CTA Button border
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'cta_border',
			'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow",
		)
	);
}

// CTA Button Colors
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow",
		'enabled'  => 'button' === $settings->link_type,
		'props'    => array(
			'color'            => $settings->cta_color,
			'background-color' => $settings->cta_bg_color,
		),
	)
);

// CTA Button Hover Colors
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow:hover",
		'enabled'  => 'button' === $settings->link_type,
		'props'    => array(
			'color'            => $settings->cta_hvr_color,
			'background-color' => $settings->cta_bg_hvr_color,
			'border-color'     => $settings->cta_border_hvr_color,
		),
	)
);

// CTA Button width
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow",
		'enabled'  => 'auto' === $settings->cta_width && 'button' === $settings->link_type,
		'props'    => array(
			'width' => 'auto',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow",
		'enabled'  => 'full' === $settings->cta_width && 'button' === $settings->link_type,
		'props'    => array(
			'width' => '100%',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow",
		'enabled'  => 'custom' === $settings->cta_width && 'button' === $settings->link_type,
		'props'    => array(
			'width' => ( '' !== $settings->cta_custom_width ) ? $settings->cta_custom_width . 'px' : '',
		),
	)
);

if ( 'button' === $settings->link_type ) {
	// Button padding
	FLBuilderCSS::dimension_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'cta_padding',
			'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap .tnit-btn-arrow",
			'unit'         => 'px',
			'props'        => array(
				'padding-top'    => 'cta_padding_top',
				'padding-right'  => 'cta_padding_right',
				'padding-bottom' => 'cta_padding_bottom',
				'padding-left'   => 'cta_padding_left',
			),
		)
	);
}

// CTA Button margin
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-infobox-button-wrap",
		'props'    => array(
			'margin-top'    => ( '' !== $settings->infoboxes_cta_text_margin_top ) ? $settings->infoboxes_cta_text_margin_top . 'px' : '',
			'margin-bottom' => ( '' !== $settings->infoboxes_cta_text_margin_bottom ) ? $settings->infoboxes_cta_text_margin_bottom . 'px' : '',
		),
	)
);
// Button Alignment
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'cta_alignment',
		'selector'     => ".fl-node-$id .tnit-infobox-button-wrap",
		'prop'         => 'text-align',
	)
);

/**
 * Icon CSS rules
 *
 * @class .tnit-infoBox .tnit-icon
 */

// Icon color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-icon",
		'props'    => array(
			'color' => $settings->icon_color,
		),
	)
);

// Icon hover color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover .tnit-icon",
		'props'    => array(
			'color' => $settings->icon_hover_color,
		),
	)
);

// Icon background color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-icon",
		'enabled'  => 'simple' !== $settings->icon_bg_style,
		'props'    => array(
			'background-color' => $settings->icon_bg_color,
		),
	)
);

// Icon hover background color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover .tnit-icon",
		'enabled'  => 'simple' !== $settings->icon_bg_style,
		'props'    => array(
			'background-color' => $settings->icon_bg_hover_color,
		),
	)
);

if ( 'custom' === $settings->icon_bg_style ) {
	// Icon border
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'icon_border',
			'selector'     => ".fl-node-$id .tnit-infoBox .tnit-icon",
		)
	);

	// Icon background size (width) - Responsive
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'icon_bg_size',
			'selector'     => ".fl-node-$id .tnit-infoBox .tnit-icon",
			'prop'         => 'width',
			'unit'         => 'px',
		)
	);

	// Icon background size (height) - Responsive
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'icon_bg_size',
			'selector'     => ".fl-node-$id .tnit-infoBox .tnit-icon",
			'prop'         => 'height',
			'unit'         => 'px',
		)
	);
}

// Icon hover border color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox:hover .tnit-icon",
		'enabled'  => 'custom' === $settings->icon_bg_style,
		'props'    => array(
			'border-color' => $settings->icon_border_hover_color,
		),
	)
);

// Icon size
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

// Icon background size - dynamic
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-icon",
		'enabled'  => ( 'circle' === $settings->icon_bg_style || 'square' === $settings->icon_bg_style ) && '' !== $settings->icon_size,
		'media'    => 'default',
		'props'    => array(
			'width'  => ( '' !== $settings->icon_size ) ? ( $settings->icon_size * 2 ) . 'px' : '',
			'height' => ( '' !== $settings->icon_size ) ? ( $settings->icon_size * 2 ) . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-icon",
		'enabled'  => ( 'circle' === $settings->icon_bg_style || 'square' === $settings->icon_bg_style ) && '' !== $settings->icon_size_medium,
		'media'    => 'medium',
		'props'    => array(
			'width'  => ( '' !== $settings->icon_size_medium ) ? ( $settings->icon_size_medium * 2 ) . 'px' : '',
			'height' => ( '' !== $settings->icon_size_medium ) ? ( $settings->icon_size_medium * 2 ) . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-infoBox .tnit-icon",
		'enabled'  => ( 'circle' === $settings->icon_bg_style || 'square' === $settings->icon_bg_style ) && '' !== $settings->icon_size_responsive,
		'media'    => 'responsive',
		'props'    => array(
			'width'  => ( '' !== $settings->icon_size_responsive ) ? ( $settings->icon_size_responsive * 2 ) . 'px' : '',
			'height' => ( '' !== $settings->icon_size_responsive ) ? ( $settings->icon_size_responsive * 2 ) . 'px' : '',
		),
	)
);


/**
 * Image CSS rules
 *
 * @class .tnit-infoBox .tnit-infobox-imgicon-wrap .tnit-info-thumb .tnit-photo-img
 */

// Image border
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'img_border',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-imgicon-wrap .tnit-info-thumb .tnit-photo-img",
	)
);

// Image size
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'photo_size',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-imgicon-wrap .tnit-info-thumb .tnit-photo-img",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);
// Image-Icon margin
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'imgicon_margin',
		'selector'     => ".fl-node-$id .tnit-infoBox .tnit-infobox-imgicon-wrap",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'imgicon_margin_top',
			'margin-right'  => 'imgicon_margin_right',
			'margin-bottom' => 'imgicon_margin_bottom',
			'margin-left'   => 'imgicon_margin_left',
		),
	)
);
