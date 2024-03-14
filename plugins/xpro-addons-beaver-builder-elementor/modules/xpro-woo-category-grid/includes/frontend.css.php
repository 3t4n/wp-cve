<?php

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_height',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-img",
		'units'        => 'px',
		'prop'         => 'height',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_height',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-img",
		'units'        => 'px',
		'prop'         => 'min-height',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-item",
		'enabled'  => 'color' === $settings->item_bg_type,
		'props'    => array(
			'background-color' => $settings->item_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-item",
		'enabled'  => 'gradient' === $settings->item_bg_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->item_bg_gradient ),
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'item_border',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-item",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'item_padding',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-item",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'item_padding_top',
			'padding-right'  => 'item_padding_right',
			'padding-bottom' => 'item_padding_bottom',
			'padding-left'   => 'item_padding_left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-img-section::after",
		'props'    => array(
			'color' => $settings->overlay_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-img:hover .xpro-woo-product-img-section::after",
		'props'    => array(
			'color' => $settings->overlay_hcolor,
		),
	)
);

/* ===============================
	Style > Content
   =============================== */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-img:hover .xpro-woo-product-img-section::after,.fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-item:hover .xpro-woo-product-img-section::after",
		'enabled'  => 'flex-start' === $settings->horizontal_alignment,
		'props'    => array(
			'align-items' => 'flex-start',
			'text-align'  => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'enabled'  => 'center' === $settings->horizontal_alignment,
		'props'    => array(
			'align-items' => 'center',
			'text-align'  => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'enabled'  => 'flex-end' === $settings->horizontal_alignment,
		'props'    => array(
			'align-items' => 'flex-end',
			'text-align'  => 'right',
		),
	)
);

/* Medium */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'media'    => 'medium',
		'enabled'  => 'flex-start' === $settings->horizontal_alignment_medium,
		'props'    => array(
			'align-items' => 'flex-start',
			'text-align'  => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'media'    => 'medium',
		'enabled'  => 'center' === $settings->horizontal_alignment_medium,
		'props'    => array(
			'align-items' => 'center',
			'text-align'  => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'media'    => 'medium',
		'enabled'  => 'flex-end' === $settings->horizontal_alignment_medium,
		'props'    => array(
			'align-items' => 'flex-end',
			'text-align'  => 'right',
		),
	)
);

/* Responsive */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'media'    => 'responsive',
		'enabled'  => 'flex-start' === $settings->horizontal_alignment_responsive,
		'props'    => array(
			'align-items' => 'flex-start',
			'text-align'  => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'media'    => 'responsive',
		'enabled'  => 'center' === $settings->horizontal_alignment_responsive,
		'props'    => array(
			'align-items' => 'center',
			'text-align'  => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'media'    => 'responsive',
		'enabled'  => 'flex-end' === $settings->horizontal_alignment_responsive,
		'props'    => array(
			'align-items' => 'flex-end',
			'text-align'  => 'right',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'vertical_alignment',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-inner-content-sec",
		'prop'         => 'justify-content',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'content_height',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec",
		'units'        => 'px',
		'prop'         => 'height',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec",
		'enabled'  => 'color' === $settings->content_bg_type,
		'props'    => array(
			'background-color' => $settings->content_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec",
		'enabled'  => 'gradient' === $settings->content_bg_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->content_gradient ),
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-cat-layout-4 .xpro-woo-product-grid-content-sec::before,.fl-node-$id .xpro-woo-product-cat-layout-6 .xpro-woo-product-grid-content-sec::before",
		'props'    => array(
			'border-top-color'    => $settings->content_border_bg,
			'border-bottom-color' => $settings->content_border_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-cat-layout-4 .xpro-woo-product-grid-content-sec::after,.fl-node-$id .xpro-woo-product-cat-layout-6 .xpro-woo-product-grid-content-sec::after",
		'props'    => array(
			'border-right-color' => $settings->content_border_bg,
			'border-left-color'  => $settings->content_border_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-cat-layout-7 .xpro-woo-product-grid-content-sec::before",
		'props'    => array(
			'background-color' => $settings->content_border_bg,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'content_border',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'content_padding',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'content_padding_top',
			'padding-right'  => 'content_padding_right',
			'padding-bottom' => 'content_padding_bottom',
			'padding-left'   => 'content_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'content_margin',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-content-sec",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'content_margin_top',
			'margin-right'  => 'content_margin_right',
			'margin-bottom' => 'content_margin_bottom',
			'margin-left'   => 'content_margin_left',
		),
	)
);

/* Title */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-title",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-title",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-title:hover",
		'props'    => array(
			'color' => $settings->title_hover_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_margin',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-title",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'title_margin_top',
			'margin-right'  => 'title_margin_right',
			'margin-bottom' => 'title_margin_bottom',
			'margin-left'   => 'title_margin_left',
		),
	)
);

/* Description */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'description_typography',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt",
		'props'    => array(
			'color' => $settings->excerpt_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'excerpt_margin',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-excerpt",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'excerpt_margin_top',
			'margin-right'  => 'excerpt_margin_right',
			'margin-bottom' => 'excerpt_margin_bottom',
			'margin-left'   => 'excerpt_margin_left',
		),
	)
);

/* ===============================
	Style > Button
   =============================== */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_typography',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn",
		'props'    => array(
			'color' => $settings->button_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn",
		'props'    => array(
			'background-color' => $settings->button_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:hover,.fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:focus",
		'props'    => array(
			'color' => $settings->button_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:hover,.fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:focus",
		'props'    => array(
			'background-color' => $settings->button_hbg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:hover,.fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn:focus",
		'props'    => array(
			'border-color' => $settings->button_hborder_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_border',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_item_padding',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'button_item_padding_top',
			'padding-right'  => 'button_item_padding_right',
			'padding-bottom' => 'button_item_padding_bottom',
			'padding-left'   => 'button_item_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_margin',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-shop-btn .xpro-woo-cart-btn",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'button_margin_top',
			'margin-right'  => 'button_margin_right',
			'margin-bottom' => 'button_margin_bottom',
			'margin-left'   => 'button_margin_left',
		),
	)
);
