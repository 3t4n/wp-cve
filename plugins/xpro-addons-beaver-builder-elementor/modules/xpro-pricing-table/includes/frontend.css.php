<?php

// general text background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item",
		'props'    => array(
			'color' => $settings->pricing_color_style,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-inner",
		'props'    => array(
			'text-align' => $settings->pricing_alignment_style,
		),
	)
);

// general text background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-inner",
		'props'    => array(
			'background-color' => $settings->pricing_background_color_style,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-inner",
		'enabled'  => 'gradient' === $settings->pricing_background_type_style,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->pricing_gradient_style ),
		),
	)
);

// Alignment flex direction li.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-features-list li",
		'props'    => array(
			'flex-direction' => ( 'right' === $settings->pricing_alignment_style ) ? 'row-reverse' : '',
		),
	)
);

// Alignment flex direction li span.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item .xpro-pricing-tooltip-toggle",
		'props'    => array(
			'left'  => ( 'right' === $settings->pricing_alignment_style ) ? '-30px' : '',
			'right' => ( 'right' !== $settings->pricing_alignment_style ) ? '-30px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item .xpro-pricing-tooltip",
		'props'    => array(
			'left'       => ( 'right' === $settings->pricing_alignment_style ) ? 'auto' : '',
			'right'      => ( 'right' === $settings->pricing_alignment_style ) ? '25px' : '',
			'text-align' => ( 'right' === $settings->pricing_alignment_style ) ? 'right' : '',

		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-tooltip:after",
		'props'    => array(
			'left'      => ( 'right' === $settings->pricing_alignment_style ) ? '100%' : '',
			'transform' => ( 'right' === $settings->pricing_alignment_style ) ? 'rotate(180deg) translateY(50%)' : '',
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'style_general_border',
		'selector'     => ".fl-node-$id .xpro-pricing-item-inner",
	)
);


// title margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'style_general_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-item-inner",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'style_general_margin_top',
			'margin-right'  => 'style_general_margin_right',
			'margin-bottom' => 'style_general_margin_bottom',
			'margin-left'   => 'style_general_margin_left',
		),
	)
);

// title padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'style_general_padding',
		'selector'     => ".fl-node-$id .xpro-pricing-item-inner",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'style_general_padding_top',
			'padding-right'  => 'style_general_padding_right',
			'padding-bottom' => 'style_general_padding_bottom',
			'padding-left'   => 'style_general_padding_left',
		),
	)
);

/* =======================
	Style > heading
   ======================= */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_heading_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-item-title",
	)
);

// general text background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-title",
		'props'    => array(
			'color' => $settings->pricing_color_heading,
		),
	)
);

// general text background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-title",
		'props'    => array(
			'color' => $settings->pricing_color_heading,
		),
	)
);

// style heading text background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-title",
		'props'    => array(
			'background-color' => $settings->pricing_background_heading,
		),
	)
);

// style heading text background gradient.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-title",
		'enabled'  => 'gradient' === $settings->pricing_background_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->pricing_gradient_heading ),
		),
	)
);

// style heading border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_border_style_heading',
		'selector'     => ".fl-node-$id .xpro-pricing-item-title",
	)
);

// style heading display.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-title",
		'props'    => array(
			'display' => $settings->pricing_display_style_heading,
		),
	)
);

// style heading padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_padding_style_heading',
		'selector'     => ".fl-node-$id .xpro-pricing-item-title",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'pricing_padding_style_heading_top',
			'padding-right'  => 'pricing_padding_style_heading_right',
			'padding-bottom' => 'pricing_padding_style_heading_bottom',
			'padding-left'   => 'pricing_padding_style_heading_left',
		),
	)
);

// style heading Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_margin_style_heading',
		'selector'     => ".fl-node-$id .xpro-pricing-item-title",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_margin_style_heading_top',
			'margin-right'  => 'pricing_margin_style_heading_right',
			'margin-bottom' => 'pricing_margin_style_heading_bottom',
			'margin-left'   => 'pricing_margin_style_heading_left',
		),
	)
);

// style heading icon color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-icon",
		'props'    => array(
			'color' => $settings->icon_color_style_heading,
		),
	)
);

// style heading icon size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_icon_size_style_heading',
		'selector'     => ".fl-node-$id .xpro-pricing-item-icon",
		'prop'         => 'font-size',
	)
);

// style heading icon Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_icon_margin_style_heading',
		'selector'     => ".fl-node-$id .xpro-pricing-item-icon,.fl-node-$id .xpro-pricing-media",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_icon_margin_style_heading_top',
			'margin-right'  => 'pricing_icon_margin_style_heading_right',
			'margin-bottom' => 'pricing_icon_margin_style_heading_bottom',
			'margin-left'   => 'pricing_icon_margin_style_heading_left',
		),
	)
);

// icon background size/width.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'style_media_image_background_size',
		'selector'     => ".fl-node-$id .xpro-pricing-media > img",
		'prop'         => 'width',
	)
);

// icon background size/height.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'style_media_image_background_size',
		'selector'     => ".fl-node-$id .xpro-pricing-media > img",
		'prop'         => 'height',
	)
);

// price display.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-price-tag",
		'props'    => array(
			'display' => $settings->pricing_display_price,
		),
	)
);

// price color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-price-tag",
		'props'    => array(
			'color' => $settings->pricing_price_color,
		),
	)
);

// price Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_prices_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-price-tag",
	)
);

// price Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_price_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-price-box",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_price_margin_top',
			'margin-right'  => 'pricing_price_margin_right',
			'margin-bottom' => 'pricing_price_margin_bottom',
			'margin-left'   => 'pricing_price_margin_left',
		),
	)
);

// price currency color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-currency",
		'props'    => array(
			'color' => $settings->pricing_price_currency_color,
		),
	)
);

// price currency Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_price_currency_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-currency",
	)
);

// price currency Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_price_currency_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-currency",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_price_currency_margin_top',
			'margin-right'  => 'pricing_price_currency_margin_right',
			'margin-bottom' => 'pricing_price_currency_margin_bottom',
			'margin-left'   => 'pricing_price_currency_margin_left',
		),
	)
);

// price Period color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-price-period",
		'props'    => array(
			'color' => $settings->pricing_price_period_color,
		),
	)
);

// price Period Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_price_period_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-price-period",
	)
);

// price Period Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_price_period_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-price-period",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_price_period_margin_top',
			'margin-right'  => 'pricing_price_period_margin_right',
			'margin-bottom' => 'pricing_price_period_margin_bottom',
			'margin-left'   => 'pricing_price_period_margin_left',
		),
	)
);

// features Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-features",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_feature_margin_top',
			'margin-right'  => 'pricing_feature_margin_right',
			'margin-bottom' => 'pricing_feature_margin_bottom',
			'margin-left'   => 'pricing_feature_margin_left',
		),
	)
);

// feature title color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-features-title",
		'props'    => array(
			'color' => $settings->pricing_feature_title_color,
		),
	)
);

// feature title Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_title_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-features-title",
	)
);

// feature title Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_title_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-features-title",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_feature_title_margin_top',
			'margin-right'  => 'pricing_feature_title_margin_right',
			'margin-bottom' => 'pricing_feature_title_margin_bottom',
			'margin-left'   => 'pricing_feature_title_margin_left',
		),
	)
);

// feature icon size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_list_icon_size',
		'selector'     => ".fl-node-$id .xpro-pricing-feature-icon i",
		'prop'         => 'font-size',
	)
);

// icon background size/width.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_list_icon_size',
		'selector'     => ".fl-node-$id .xpro-pricing-feature-icon i",
		'unit'         => 'px',
		'prop'         => 'width',
	)
);

// feature icon margin right.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_icon_space',
		'selector'     => ".fl-node-$id .xpro-pricing-feature-icon i",
		'unit'         => 'px',
		'prop'         => 'margin-right',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-features-list li",
		'props'    => array(
			'text-align' => $settings->pricing_feature_content_align,
		),
	)
);

// Feature Alignment flex direction li.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-features-list li",
		'props'    => array(
			'flex-direction' => ( 'right' === $settings->pricing_feature_content_align ) ? 'row-reverse' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-features-list li",
		'props'    => array(
			'display' => ( 'center' === $settings->pricing_feature_content_align ) ? 'block' : '',
		),
	)
);

// Feature Alignment flex direction li span.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-features-list li .xpro-pricing-tooltip-toggle",
		'props'    => array(
			'left'  => ( 'right' === $settings->pricing_feature_content_align ) ? '-30px' : '',
			'right' => ( 'right' !== $settings->pricing_feature_content_align ) ? '-30px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-features-list li .xpro-pricing-tooltip",
		'props'    => array(
			'left'       => ( 'right' === $settings->pricing_feature_content_align ) ? 'auto' : '',
			'right'      => ( 'right' === $settings->pricing_feature_content_align ) ? '25px' : '',
			'text-align' => ( 'right' === $settings->pricing_feature_content_align ) ? 'right' : '',

		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-features-list li .xpro-pricing-tooltip:after",
		'props'    => array(
			'left'      => ( 'right' === $settings->pricing_feature_content_align ) ? '100%' : '',
			'transform' => ( 'right' === $settings->pricing_feature_content_align ) ? 'rotate(180deg) translateY(50%)' : '',
		),
	)
);


if ( $settings->pricing_feature_icon_position ) :
	//icon position
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-pricing-feature-icon i",
			'props'    => array(
				'position' => 'relative',
				'top'      => $settings->pricing_feature_icon_position . 'px',
			),
		)
	);
endif;

// featureicon margin right.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_icon_space_between',
		'selector'     => ".fl-node-$id .xpro-pricing-features-list li",
		'unit'         => 'px',
		'prop'         => 'margin-bottom',
	)
);

// feature  li active color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id li.active",
		'props'    => array(
			'color' => $settings->pricing_feature_list_active_color,
		),
	)
);

// feature  li active icon color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id li.active .xpro-pricing-feature-icon i",
		'props'    => array(
			'color' => $settings->pricing_feature_list_active_icon_color,
		),
	)
);

// feature  li inactive icon color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id li.inactive .xpro-pricing-feature-icon i",
		'props'    => array(
			'color' => $settings->pricing_feature_list_inactive_icon_color,
		),
	)
);

// feature  li inactive color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id li.inactive",
		'props'    => array(
			'color' => $settings->pricing_feature_list_inactive_color,
		),
	)
);

// feature tooltip icon color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-tooltip-toggle",
		'props'    => array(
			'color' => $settings->pricing_feature_tooltip_icon_color,
		),
	)
);

// feature tooltip background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-tooltip-toggle",
		'props'    => array(
			'background-color' => $settings->pricing_feature_tooltip_icon_background_color,
		),
	)
);

// feature tooltip content Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_tooltip_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-tooltip",
	)
);

// feature tooltip width Typography.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_tooltip_width',
		'selector'     => ".fl-node-$id .xpro-pricing-tooltip",
		'prop'         => 'width',
	)
);

// feature tooltip icon color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-tooltip",
		'props'    => array(
			'color' => $settings->pricing_feature_tooltip_content_color,
		),
	)
);

// feature tooltip background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-tooltip",
		'props'    => array(
			'background-color' => $settings->pricing_feature_tooltip_content_background,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-tooltip:after",
		'props'    => array(
			'border-right-color' => $settings->pricing_feature_tooltip_content_background,
		),
	)
);

// feature tooltip content padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_tooltip_content_padding',
		'selector'     => ".fl-node-$id .xpro-pricing-tooltip",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'pricing_feature_tooltip_content_padding_top',
			'padding-right'  => 'pricing_feature_tooltip_content_padding_right',
			'padding-bottom' => 'pricing_feature_tooltip_content_padding_bottom',
			'padding-left'   => 'pricing_feature_tooltip_content_padding_left',
		),
	)
);

// description color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-text",
		'props'    => array(
			'color' => $settings->pricing_description_color,
		),
	)
);

// description Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_description_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-item-text",
	)
);

// description icon width.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_description_width',
		'selector'     => ".fl-node-$id .xpro-pricing-item-text",
		'prop'         => 'max-width',
	)
);

// feature icon Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_feature_icon_title_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-feature-title",
	)
);

// description icon Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_description_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-item-text",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_description_margin_top',
			'margin-right'  => 'pricing_description_margin_right',
			'margin-bottom' => 'pricing_description_margin_bottom',
			'margin-left'   => 'pricing_description_margin_left',
		),
	)
);

// separator color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-separator",
		'props'    => array(
			'background-color' => $settings->pricing_separator_color,
		),
	)
);

// separator width.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_separator_width',
		'selector'     => ".fl-node-$id .xpro-pricing-item-separator",
		'prop'         => 'width',
	)
);

// separator height.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_separator_height',
		'selector'     => ".fl-node-$id .xpro-pricing-item-separator",
		'prop'         => 'height',
	)
);

// separator Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_separator_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-item-separator",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_separator_margin_top',
			'margin-right'  => 'pricing_separator_margin_right',
			'margin-bottom' => 'pricing_separator_margin_bottom',
			'margin-left'   => 'pricing_separator_margin_left',
		),
	)
);

// style heading display.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-btn",
		'props'    => array(
			'display' => $settings->pricing_display_button,
		),
	)
);

// Button color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-btn",
		'props'    => array(
			'color' => $settings->pricing_button_color,
		),
	)
);

// Button color hover.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-btn:hover",
		'props'    => array(
			'color' => $settings->pricing_button_color_hover,
		),
	)
);

// Button background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-btn",
		'props'    => array(
			'background-color' => $settings->pricing_button_background_color,

		),
	)
);

// Button background color hover.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-btn:hover",
		'props'    => array(
			'background-color' => $settings->pricing_button_background_color_hover,
		),
	)
);

// Button gradient color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-btn",
		'enabled'  => 'gradient' === $settings->pricing_background_style_type_button, // Optional.
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->pricing_button_background_gradient ),
		),
	)
);

// Button gradient color hover.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-btn:hover",
		'enabled'  => 'gradient' === $settings->pricing_background_style_type_hover_button, // Optional.
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->pricing_button_background_gradient_hover ),
		),
	)
);

// Button color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-btn:hover",
		'props'    => array(
			'border-color' => $settings->pricing_button_border_hover,
		),
	)
);

// Button border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_button_border',
		'selector'     => ".fl-node-$id .xpro-pricing-item-btn",
	)
);

// description Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_button_typography',
		'selector'     => ".fl-node-$id .xpro-pricing-item-btn",
	)
);

// style heading padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_button_padding',
		'selector'     => ".fl-node-$id .xpro-pricing-item-btn",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'pricing_button_padding_top',
			'padding-right'  => 'pricing_button_padding_right',
			'padding-bottom' => 'pricing_button_padding_bottom',
			'padding-left'   => 'pricing_button_padding_left',
		),
	)
);

// button Margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_button_margin',
		'selector'     => ".fl-node-$id .xpro-pricing-item-btn",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'pricing_button_margin_top',
			'margin-right'  => 'pricing_button_margin_right',
			'margin-bottom' => 'pricing_button_margin_bottom',
			'margin-left'   => 'pricing_button_margin_left',
		),
	)
);

// icon background size/width.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'style_badge_background_size',
		'selector'     => ".fl-node-$id .xpro-price-item-badge",
		'prop'         => 'width',
	)
);

// Badge Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_badge_typography',
		'selector'     => ".fl-node-$id .xpro-price-item-badge",
	)
);

// style heading display.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-pricing-item-inner",
		'props'    => array(
			'overflow' => $settings->pricing_position_overflow,
		),
	)
);

// badge offset top.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_badge_offset_top',
		'selector'     => ".fl-node-$id .xpro-price-item-badge",
		'prop'         => '--xpro-badge-translate-y',
		'unit'         => 'px',
	)
);

// badge offset top.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_badge_offset_left',
		'selector'     => ".fl-node-$id .xpro-price-item-badge",
		'prop'         => '--xpro-badge-translate-x',
		'unit'         => 'px',
	)
);

// badge rotate.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_badge_rotate',
		'selector'     => ".fl-node-$id .xpro-price-item-badge",
		'prop'         => '--xpro-badge-rotate',
		'unit'         => 'deg',
	)
);

// badge transform origin.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-price-item-badge",
		'props'    => array(
			'transform-origin' => $settings->pricing_badge_origin,
		),
	)
);

// style heading padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_badge_padding',
		'selector'     => ".fl-node-$id .xpro-price-item-badge",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'pricing_badge_padding_top',
			'padding-right'  => 'pricing_badge_padding_right',
			'padding-bottom' => 'pricing_badge_padding_bottom',
			'padding-left'   => 'pricing_badge_padding_left',
		),
	)
);

// Button color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-price-item-badge",
		'props'    => array(
			'color' => $settings->pricing_badge_text_color,
		),
	)
);

// Button background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-price-item-badge",
		'props'    => array(
			'background-color' => $settings->pricing_badge_background_color,
		),
	)
);

// Badge border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'pricing_badge_border',
		'selector'     => ".fl-node-$id .xpro-price-item-badge",
	)
);
