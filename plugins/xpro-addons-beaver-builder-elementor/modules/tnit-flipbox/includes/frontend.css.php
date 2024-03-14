<?php
/**
 * BB Flip Box front-end CSS php file
 *
 * @package Xpro Addon
 * @sub-package Creative Flip Box Module
 *
 * @since 1.0.22
 */

// Front background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-front",
		'enabled'  => 'color' === $settings->front_bg_type,
		'props'    => array(
			'background-color' => $settings->front_bg_color,
		),
	)
);

// Front background photo.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-front.flip-bgImg",
		'enabled'  => 'photo' === $settings->front_bg_type,
		'props'    => array(
			'background-image' => $settings->front_bg_photo_src,
		),
	)
);

// Front background overlay.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-front.flip-bgImg:before",
		'enabled'  => 'photo' === $settings->front_bg_type,
		'props'    => array(
			'background-color' => $settings->front_bg_overlay,
		),
	)
);

if ( 'custom' === $settings->front_border_type ) {
	// Front border.
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'front_border',
			'selector'     => ".fl-node-$id .flip-box-front",
		)
	);
}

// Front border corners styles.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flipBorderStyle .flip-box-front:before,.fl-node-$id .flipBorderStyle .flip-box-front:after",
		'enabled'  => 'corners' === $settings->front_border_type,
		'props'    => array(
			'border-color' => $settings->front_corners_color,
			'border-width' => ( '' !== $settings->front_corners_thikness ) ? $settings->front_corners_thikness . 'px' : '',
		),
	)
);

// Front padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'front_padding',
		'selector'     => ".fl-node-$id .flip-box-front",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'front_padding_top',
			'padding-right'  => 'front_padding_right',
			'padding-bottom' => 'front_padding_bottom',
			'padding-left'   => 'front_padding_left',
		),
	)
);


/**
 * -----------------------------
 * Front Icon
 * -----------------------------
 */

// Get icon settings.
$icon_settings = $settings->icon_settings;

// Front icon size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $icon_settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .tnit-flipbox .flipbox-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

// Front icon background size.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .flipbox-icon",
		'media'    => 'default',
		'props'    => array(
			'width'  => ( '' !== $icon_settings->icon_bg_size ) ? $icon_settings->icon_bg_size . 'px' : '',
			'height' => ( '' !== $icon_settings->icon_bg_size ) ? $icon_settings->icon_bg_size . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .flipbox-icon",
		'media'    => 'medium',
		'props'    => array(
			'width'  => ( '' !== $icon_settings->icon_bg_size_medium ) ? $icon_settings->icon_bg_size_medium . 'px' : '',
			'height' => ( '' !== $icon_settings->icon_bg_size_medium ) ? $icon_settings->icon_bg_size_medium . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .flipbox-icon",
		'media'    => 'responsive',
		'props'    => array(
			'width'  => ( '' !== $icon_settings->icon_bg_size_responsive ) ? $icon_settings->icon_bg_size_responsive . 'px' : '',
			'height' => ( '' !== $icon_settings->icon_bg_size_responsive ) ? $icon_settings->icon_bg_size_responsive . 'px' : '',
		),
	)
);

// Front icon border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $icon_settings,
		'setting_name' => 'icon_border',
		'selector'     => ".fl-node-$id .tnit-flipbox .flipbox-icon",
	)
);

// Front icon colors.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .flipbox-icon",
		'props'    => array(
			'color'            => $icon_settings->icon_color,
			'background-color' => $icon_settings->icon_bg_color,
		),
	)
);

// Front icon margin top/bottom.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $icon_settings,
		'setting_name' => 'imgicon_margin_top',
		'selector'     => ".fl-node-$id .tnit-flipbox-icon-wrap",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $icon_settings,
		'setting_name' => 'imgicon_margin_bottom',
		'selector'     => ".fl-node-$id .tnit-flipbox-icon-wrap",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);

// Front title typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'front_title_font',
		'selector'     => ".fl-node-$id .flip-box-front .tnit-flipbox-title",
	)
);

// Front title color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-front .tnit-flipbox-title",
		'props'    => array(
			'color' => $settings->front_title_color,
		),
	)
);

// Front title margin top/bottom.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'front_title_margin_top',
		'selector'     => ".fl-node-$id .flip-box-front .tnit-flipbox-title",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'front_title_margin_bottom',
		'selector'     => ".fl-node-$id .flip-box-front .tnit-flipbox-title",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);

// Front description typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'front_description_font',
		'selector'     => ".fl-node-$id .flip-box-front .tnit-flipbox-text",
	)
);

// Front description color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-front .tnit-flipbox-text",
		'props'    => array(
			'color' => $settings->front_description_color,
		),
	)
);

// Front description margin top/bottom.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'front_description_margin_top',
		'selector'     => ".fl-node-$id .flip-box-front .tnit-flipbox-text",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'front_description_margin_bottom',
		'selector'     => ".fl-node-$id .flip-box-front .tnit-flipbox-text",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);

// Back background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-back",
		'enabled'  => 'color' === $settings->back_bg_type,
		'props'    => array(
			'background-color' => $settings->back_bg_color,
		),
	)
);

// Back background photo.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-back.flip-bgImg",
		'enabled'  => 'photo' === $settings->back_bg_type,
		'props'    => array(
			'background-image' => $settings->back_bg_photo_src,
		),
	)
);

// Back background overlay.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-back.flip-bgImg:before",
		'enabled'  => 'photo' === $settings->back_bg_type,
		'props'    => array(
			'background-color' => $settings->back_bg_overlay,
		),
	)
);

// Back border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'back_border',
		'selector'     => ".fl-node-$id .flip-box-back",
	)
);

// Back padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'back_padding',
		'selector'     => ".fl-node-$id .flip-box-back",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'back_padding_top',
			'padding-right'  => 'back_padding_right',
			'padding-bottom' => 'back_padding_bottom',
			'padding-left'   => 'back_padding_left',
		),
	)
);

// Back title typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'back_title_font',
		'selector'     => ".fl-node-$id .flip-box-back .tnit-flipbox-title",
	)
);

// Back title color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-back .tnit-flipbox-title,.fl-node-$id .flip-box-back .tnit-flipbox-title a",
		'props'    => array(
			'color' => $settings->back_title_color,
		),
	)
);

// Back title margin top/bottom.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'back_title_margin_top',
		'selector'     => ".fl-node-$id .flip-box-back .tnit-flipbox-title",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'back_title_margin_bottom',
		'selector'     => ".fl-node-$id .flip-box-back .tnit-flipbox-title",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);

// Back description typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'back_description_font',
		'selector'     => ".fl-node-$id .flip-box-back .tnit-flipbox-text",
	)
);

// Back description color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .flip-box-back .tnit-flipbox-text,.fl-node-$id .flip-box-back .tnit-flipbox-text p",
		'props'    => array(
			'color' => $settings->back_description_color,
		),
	)
);

// Back description margin top/bottom.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'back_description_margin_top',
		'selector'     => ".fl-node-$id .flip-box-back .tnit-flipbox-text",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'back_description_margin_bottom',
		'selector'     => ".fl-node-$id .flip-box-back .tnit-flipbox-text",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);

// Get button settings.
$button_settings = $settings->button_settings;

// Back button typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $button_settings,
		'setting_name' => 'button_font',
		'selector'     => ".fl-node-$id .tnit-flipbox .tnit-btn-text",
	)
);

// Back button width.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .tnit-btn-text",
		'enabled'  => 'auto' === $button_settings->button_width,
		'props'    => array(
			'width' => 'auto',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .tnit-btn-text",
		'enabled'  => 'full' === $button_settings->button_width,
		'props'    => array(
			'width' => '100%',
		),
	)
);

// Back button custom width.
if ( 'custom' === $button_settings->button_width ) {
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $button_settings,
			'setting_name' => 'button_custom_width',
			'selector'     => ".fl-node-$id .tnit-flipbox .tnit-btn-text",
			'prop'         => 'width',
			'unit'         => 'px',
		)
	);
}

// Back button border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $button_settings,
		'setting_name' => 'button_border',
		'selector'     => ".fl-node-$id .tnit-flipbox .tnit-btn-text",
	)
);

// Back button padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $button_settings,
		'setting_name' => 'button_padding',
		'selector'     => ".fl-node-$id .tnit-flipbox .tnit-btn-text",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'button_padding_top',
			'padding-right'  => 'button_padding_right',
			'padding-bottom' => 'button_padding_bottom',
			'padding-left'   => 'button_padding_left',
		),
	)
);

// Back button colors.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .tnit-btn-text",
		'props'    => array(
			'color'            => $button_settings->button_color,
			'background-color' => $button_settings->button_bg_color,
		),
	)
);

// Back button hover colors.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .tnit-btn-text:hover",
		'props'    => array(
			'color'            => $button_settings->button_hvr_color,
			'background-color' => $button_settings->button_bg_hvr_color,
			'border-color'     => $button_settings->button_border_hvr_color,
		),
	)
);

// Back button icon size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $button_settings,
		'setting_name' => 'button_icon_size',
		'selector'     => ".fl-node-$id .tnit-flipbox .tnit-btn-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

// Back button icon color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .tnit-btn-icon",
		'props'    => array(
			'color' => $button_settings->button_color,
		),
	)
);

// Back button icon hover color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-flipbox .tnit-btn-icon:hover",
		'props'    => array(
			'color' => $button_settings->button_hvr_color,
		),
	)
);

// Back button margin top/bottom.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $button_settings,
		'setting_name' => 'button_margin_top',
		'selector'     => ".fl-node-$id .tnit-flipbox .tnit-flipbox-button-wrap",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $button_settings,
		'setting_name' => 'button_margin_bottom',
		'selector'     => ".fl-node-$id .tnit-flipbox .tnit-flipbox-button-wrap",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);
