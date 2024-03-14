<?php

/*
 ===============================
	General > Badge
   =============================== */

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn,.fl-node-$id .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn",
		'enabled'  => 'circle' === $settings->badges_styles->woo_badges_style,
		'props'    => array(
			'width'           => '50px',
			'height'          => '50px',
			'display'         => 'flex',
			'justify-content' => 'center',
			'align-item'      => 'center',
			'border-radius'   => '100%',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-product-grid-badges-innner-wrapper",
		'enabled'  => 'row' === $settings->badges_styles->badges_direction,
		'props'    => array(
			'display'         => 'flex',
			'justify-content' => 'stretch',
			'align-item'      => 'baseline',
			'flex-direction'  => 'row',
		),
	)
);

/*
 ===============================
	Style > General
   =============================== */
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
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-img",
		'props'    => array(
			'object-fit' => $settings->object_fit,
		),
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

/*
 ===============================
	Style > Content
   =============================== */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'enabled'  => 'left' === $settings->alignment,
		'props'    => array(
			'justify-content' => 'flex-start',
			'align-items'     => 'center',
			'text-align'      => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'enabled'  => 'center' === $settings->alignment,
		'props'    => array(
			'justify-content' => 'center',
			'align-items'     => 'center',
			'text-align'      => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'enabled'  => 'right' === $settings->alignment,
		'props'    => array(
			'justify-content' => 'flex-end',
			'align-items'     => 'center',
			'text-align'      => 'right',
		),
	)
);

  /* Content alignment Medium */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'media'    => 'medium',
		'enabled'  => 'left' === $settings->alignment_medium,
		'props'    => array(
			'justify-content' => 'flex-start',
			'align-items'     => 'center',
			'text-align'      => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'media'    => 'medium',
		'enabled'  => 'center' === $settings->alignment_medium,
		'props'    => array(
			'justify-content' => 'center',
			'align-items'     => 'center',
			'text-align'      => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'media'    => 'medium',
		'enabled'  => 'right' === $settings->alignment_medium,
		'props'    => array(
			'justify-content' => 'flex-end',
			'align-items'     => 'center',
			'text-align'      => 'right',
		),
	)
);

  /* Content alignment responsive */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'media'    => 'responsive',
		'enabled'  => 'left' === $settings->alignment_responsive,
		'props'    => array(
			'justify-content' => 'flex-start',
			'align-items'     => 'center',
			'text-align'      => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'media'    => 'responsive',
		'enabled'  => 'center' === $settings->alignment_responsive,
		'props'    => array(
			'justify-content' => 'center',
			'align-items'     => 'center',
			'text-align'      => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-content-sec,.fl-node-$id .xpro-woo-product-grid-star-rating-wrapper",
		'media'    => 'responsive',
		'enabled'  => 'right' === $settings->alignment_responsive,
		'props'    => array(
			'justify-content' => 'flex-end',
			'align-items'     => 'center',
			'text-align'      => 'right',
		),
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

  /* Category */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'category_typography',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro_elementor_category_term_name",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro_elementor_category_term_name",
		'props'    => array(
			'color' => $settings->category_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-category-wrapper::before",
		'props'    => array(
			'background-color' => $settings->category_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro_elementor_category_term_name:hover",
		'props'    => array(
			'color' => $settings->category_hover_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .xpro_elementor_category_term_name:hover .xpro-woo-product-grid-category-wrapper::before",
		'props'    => array(
			'background-color' => $settings->category_hover_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'category_margin',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-category-wrapper",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'category_margin_top',
			'margin-right'  => 'category_margin_right',
			'margin-bottom' => 'category_margin_bottom',
			'margin-left'   => 'category_margin_left',
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

  /* Rating */
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'rating_size',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-star-rating-wrapper .star-rating",
		'units'        => 'px',
		'prop'         => 'font-size',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .star-rating span::before",
		'props'    => array(
			'color' => $settings->rating_front_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-wrapper .star-rating::before",
		'props'    => array(
			'background-color' => $settings->rating_bg_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'excerpt_margin',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-star-rating-wrapper",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'excerpt_margin_top',
			'margin-right'  => 'excerpt_margin_right',
			'margin-bottom' => 'excerpt_margin_bottom',
			'margin-left'   => 'excerpt_margin_left',
		),
	)
);

  /* Price */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'price_typography',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-price-wrapper .price",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-price-wrapper .price",
		'props'    => array(
			'color' => $settings->price_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'space_between_price',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-price-wrapper ins",
		'units'        => 'px',
		'prop'         => 'padding-left',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'sale_typography',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-price-wrapper del .woocommerce-Price-amount",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-price-wrapper del,.fl-node-$id .xpro-woo-product-grid-price-wrapper del .woocommerce-Price-amount",
		'props'    => array(
			'color' => $settings->sale_price_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'price_margin',
		'selector'     => ".fl-node-$id .xpro-product-grid-wrapper .xpro-woo-product-grid-price-wrapper",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'price_margin_top',
			'margin-right'  => 'price_margin_right',
			'margin-bottom' => 'price_margin_bottom',
			'margin-left'   => 'price_margin_left',
		),
	)
);

/*
 ===============================
	Style > Action
   =============================== */
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icons_size',
		'selector'     => ".fl-node-$id .xpro-product-grid-hv-cta-section .xpro-cta-btn i,.fl-node-$id .xpro-product-grid-hv-cta-section .xpro-qv-cart-btn .button::before",
		'units'        => 'px',
		'prop'         => 'font-size',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icons_bg_size',
		'selector'     => ".fl-node-$id .xpro-hv-qv-btn.xpro-cta-btn,.fl-node-$id .xpro-hv-cart-btn.xpro-cta-btn",
		'units'        => 'px',
		'prop'         => 'width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icons_bg_size',
		'selector'     => ".fl-node-$id .xpro-hv-qv-btn.xpro-cta-btn,.fl-node-$id .xpro-hv-cart-btn.xpro-cta-btn",
		'units'        => 'px',
		'prop'         => 'height',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icons_bg_size',
		'selector'     => ".fl-node-$id .xpro-hv-qv-btn.xpro-cta-btn,.fl-node-$id .xpro-hv-cart-btn.xpro-cta-btn",
		'units'        => 'px',
		'prop'         => 'line-height',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icons_space_between',
		'selector'     => ".fl-node-$id .xpro-product-grid-hv-cta-section",
		'units'        => 'px',
		'prop'         => 'grid-gap',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-hv-cta-section .xpro-cta-btn i,.fl-node-$id .xpro-product-grid-hv-cta-section .xpro-qv-cart-btn .button::before",
		'props'    => array(
			'color' => $settings->qv_icons_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-hv-cta-section .xpro-cta-btn:hover i,.fl-node-$id .xpro-product-grid-hv-cta-section .xpro-qv-cart-btn:hover .button::before",
		'props'    => array(
			'color' => $settings->qv_icons_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-hv-cta-section .xpro-cta-btn",
		'props'    => array(
			'background-color' => $settings->qv_icons_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-product-grid-hv-cta-section .xpro-cta-btn:hover",
		'props'    => array(
			'background-color' => $settings->qv_icons_hbackground,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'qv_icons_btns_border',
		'selector'     => ".fl-node-$id .xpro-product-grid-hv-cta-section .xpro-cta-btn",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'qv_icons_btn_margin',
		'selector'     => ".fl-node-$id .xpro-product-grid-hv-cta-section .xpro-cta-btn",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_icons_btn_margin_top',
			'margin-right'  => 'qv_icons_btn_margin_right',
			'margin-bottom' => 'qv_icons_btn_margin_bottom',
			'margin-left'   => 'qv_icons_btn_margin_left',
		),
	)
);

/*
 ===============================
	Style > Popup Content
   =============================== */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-qv-popup-inner",
		'enabled'  => 'color' === $settings->quick_view_styles->qv_main_content_bg_type,
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_main_content_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-qv-popup-inner",
		'enabled'  => 'gradient' === $settings->quick_view_styles->qv_main_content_bg_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->quick_view_styles->qv_main_content_gradient ),
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-qv-popup-overlay",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_overlay_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_main_content_border',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .xpro-qv-popup-inner",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_main_content_padding',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .xpro-qv-popup-inner",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'qv_main_content_padding_top',
			'padding-right'  => 'qv_main_content_padding_right',
			'padding-bottom' => 'qv_main_content_padding_bottom',
			'padding-left'   => 'qv_main_content_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_main_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .xpro-qv-popup-inner",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_main_margin_top',
			'margin-right'  => 'qv_main_margin_right',
			'margin-bottom' => 'qv_main_margin_bottom',
			'margin-left'   => 'qv_main_margin_left',
		),
	)
);

/* SKU */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_meta_typography',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .sku_wrapper",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .sku_wrapper",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_sku_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .sku_wrapper .sku",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_sku_title_color,
		),
	)
);

/* Taxonomy */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_tax_typography',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .product_meta .posted_in",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .product_meta .posted_in",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_tax_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .product_meta .posted_in a",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_tax_link_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .product_meta .posted_in a:hover",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_tax_link_hv_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .product_meta .posted_in a",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_tax_link_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-content-sec .sku_wrapper",
		'props'    => array(
			'border-color' => $settings->quick_view_styles->qv_tax_seprator_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_seprator_size',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-content-sec .sku_wrapper",
		'units'        => 'px',
		'prop'         => 'border-width',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .product_meta",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_sku_background,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_meta_border',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .product_meta",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_meta_link_padding',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .product_meta .posted_in a",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'qv_meta_link_padding_top',
			'padding-right'  => 'qv_meta_link_padding_right',
			'padding-bottom' => 'qv_meta_link_padding_bottom',
			'padding-left'   => 'qv_meta_link_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_meta_padding',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .product_meta",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'qv_meta_padding_top',
			'padding-right'  => 'qv_meta_padding_right',
			'padding-bottom' => 'qv_meta_padding_bottom',
			'padding-left'   => 'qv_meta_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_meta_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .product_meta",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_main_margin_top',
			'margin-right'  => 'qv_main_margin_right',
			'margin-bottom' => 'qv_main_margin_bottom',
			'margin-left'   => 'qv_main_margin_left',
		),
	)
);

/* Title */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_title_typography',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .product_title",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .product_title",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_title_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_title_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .product_title",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_title_margin_top',
			'margin-right'  => 'qv_title_margin_right',
			'margin-bottom' => 'qv_title_margin_bottom',
			'margin-left'   => 'qv_title_margin_left',
		),
	)
);

/* Description */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_description_typography',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .woocommerce-product-details__short-description",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .woocommerce-product-details__short-description",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_desc_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_desc_padding',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .woocommerce-product-details__short-description",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_desc_padding_top',
			'margin-right'  => 'qv_desc_padding_right',
			'margin-bottom' => 'qv_desc_padding_bottom',
			'margin-left'   => 'qv_desc_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_desc_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .woocommerce-product-details__short-description",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_desc_margin_top',
			'margin-right'  => 'qv_desc_margin_right',
			'margin-bottom' => 'qv_desc_margin_bottom',
			'margin-left'   => 'qv_desc_margin_left',
		),
	)
);

/* Rating */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .woocommerce-review-link",
		'props'    => array(
			'color' => $settings->quick_view_styles->rating_front_color,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_rating_txt_typography',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .woocommerce-review-link",
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_rating_size',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .star-rating",
		'units'        => 'px',
		'prop'         => 'font-size',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .star-rating span::before",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_rating_front_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .star-rating::before",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_rating_bg_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_rating_txt_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .woocommerce-review-link",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_rating_txt_margin_top',
			'margin-right'  => 'qv_rating_txt_margin_right',
			'margin-bottom' => 'qv_rating_txt_margin_bottom',
			'margin-left'   => 'qv_rating_txt_margin_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_rating_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .woocommerce-product-rating",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_rating_margin_top',
			'margin-right'  => 'qv_rating_margin_right',
			'margin-bottom' => 'qv_rating_margin_bottom',
			'margin-left'   => 'qv_rating_margin_left',
		),
	)
);

/* Price */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_price_typography',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .price",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .price",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_price_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_space_between_price',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .price ins",
		'units'        => 'px',
		'prop'         => 'padding-left',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_sale_typography',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .price del .woocommerce-Price-amount",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .price del .woocommerce-Price-amount",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_sale_price_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_price_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .price",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_price_margin_top',
			'margin-right'  => 'qv_price_margin_right',
			'margin-bottom' => 'qv_price_margin_bottom',
			'margin-left'   => 'qv_price_margin_left',
		),
	)
);

/*
 ===============================
	Style > Popup Buttons
   =============================== */
   /* Close Button */
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_close_icon_size',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-cross i",
		'units'        => 'px',
		'prop'         => 'width',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-cross i",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_close_icon_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-cross i:hover",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_close_icon_hv_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-cross",
		'enabled'  => 'color' === $settings->quick_view_styles->qv_close_icon_bg_type,
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_close_icon_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-cross",
		'enabled'  => 'gradient' === $settings->quick_view_styles->qv_close_icon_bg_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->quick_view_styles->qv_close_icon_gradient ),
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_close_icon_border',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-cross",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_close_icon_padding',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-cross",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'qv_close_icon_padding_top',
			'padding-right'  => 'qv_close_icon_padding_right',
			'padding-bottom' => 'qv_close_icon_padding_bottom',
			'padding-left'   => 'qv_close_icon_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_close_icon_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .xpro-woo-qv-cross",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_close_icon_margin_top',
			'margin-right'  => 'qv_close_icon_margin_right',
			'margin-bottom' => 'qv_close_icon_margin_bottom',
			'margin-left'   => 'qv_close_icon_margin_left',
		),
	)
);

/* Quantity Buttons */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-minus,.fl-node-$id .xpro-qv-main-wrapper .xpro-plus",
		'props'    => array(
			'color'            => $settings->quick_view_styles->qv_quantity_btn_color,
			'background-color' => $settings->quick_view_styles->qv_quantity_btn_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .xpro-minus:hover,.fl-node-$id .xpro-qv-main-wrapper .xpro-plus:hover",
		'props'    => array(
			'color'            => $settings->quick_view_styles->qv_quantity_btn_hcolor,
			'background-color' => $settings->quick_view_styles->qv_quantity_btn_bg_hcolor,
			'border-color'     => $settings->quick_view_styles->qv_quantity_btn_border_hcolor,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_quantity_btn_border',
		'selector'     => ".fl-node-$id xpro-qv-main-wrapper .xpro-minus,.fl-node-$id .xpro-qv-main-wrapper .xpro-plus",
	)
);

/* Quantity Buttons input */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-popup-wrapper input[type='number']",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_quantity_btn_input_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-qv-content-sec .quantity,.fl-node-$id .xpro-qv-popup-wrapper input[type='number']",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_quantity_btn_input_bg_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_quantity_btn_input_border',
		'selector'     => ".fl-node-$id .xpro-woo-qv-content-sec .quantity",
	)
);

/* Button */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_button_typography',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_button_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_button_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button:hover,.fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button:focus",
		'props'    => array(
			'color' => $settings->quick_view_styles->qv_button_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button:hover,.fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button:focus",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->qv_button_hbg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button:hover,.fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button:focus",
		'props'    => array(
			'border-color' => $settings->quick_view_styles->qv_button_hborder,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_button_border',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_button_item_padding',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'qv_button_item_padding_top',
			'padding-right'  => 'qv_button_item_padding_right',
			'padding-bottom' => 'qv_button_item_padding_bottom',
			'padding-left'   => 'qv_button_item_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'qv_button_margin',
		'selector'     => ".fl-node-$id .xpro-qv-main-wrapper .single_add_to_cart_button",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'qv_button_margin_top',
			'margin-right'  => 'qv_button_margin_right',
			'margin-bottom' => 'qv_button_margin_bottom',
			'margin-left'   => 'qv_button_margin_left',
		),
	)
);

/*
 ===============================
	Style > Popup Variations
   =============================== */
   /* Label */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_label_typography',
		'selector'     => ".fl-node-$id .variations label, {{WRAPPER}} .variations select",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .variations td label,.fl-node-$id .variations select",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_label_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_inline_label_space_between',
		'selector'     => ".fl-node-$id .variations td.value .xpro_swatches",
		'units'        => 'px',
		'prop'         => 'grid-gap',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_inline_label_space_between',
		'selector'     => ".fl-node-$id .variations td.value .xpro_swatches .swatch",
		'units'        => 'px',
		'prop'         => 'margin-right',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .variations tr",
		'row'      => $settings->quick_view_styles->variation_label_display_style,
		'props'    => array(
			'flex-direction' => 'row',
			'display'        => 'flex',
			'align-items'    => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .variations tr",
		'column'   => $settings->quick_view_styles->variation_label_display_style,
		'props'    => array(
			'flex-direction' => 'column;',
			'display'        => 'flex',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_inline_label_width',
		'selector'     => ".fl-node-$id .variations th.label",
		'units'        => 'px',
		'prop'         => 'width',
	)
);

/* Description */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_description_typography',
		'selector'     => ".fl-node-$id .woocommerce-variation-description",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .woocommerce-variation-description p",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_description_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_description_margin',
		'selector'     => ".fl-node-$id .woocommerce-variation-description",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'variation_description_margin_top',
			'margin-right'  => 'variation_description_margin_right',
			'margin-bottom' => 'variation_description_margin_bottom',
			'margin-left'   => 'variation_description_margin_left',
		),
	)
);

/* Price */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_price_typography',
		'selector'     => ".fl-node-$id :is(.price, .price del, .price ins )",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id :is(.price, .price del, .price ins )",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_price_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .price ins .amount",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_sale_price_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-badge",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_price_discount_badge_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-badge",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->variation_price_discount_badge_bg_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_price_discount_badge_font_size',
		'selector'     => ".fl-node-$id .xpro-badge",
		'units'        => 'px',
		'prop'         => 'font-size',
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_price_margin',
		'selector'     => ".fl-node-$id  .woocommerce-variation-price",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'variation_price_margin_top',
			'margin-right'  => 'variation_price_margin_right',
			'margin-bottom' => 'variation_price_margin_bottom',
			'margin-left'   => 'variation_price_margin_left',
		),
	)
);

/* Dropdown */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .variations select",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_dropdown_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_dropdown_border',
		'selector'     => ".fl-node-$id .variations select",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_item_margin',
		'selector'     => ".fl-node-$id .variations tr",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'variation_item_margin_top',
			'margin-right'  => 'variation_item_margin_right',
			'margin-bottom' => 'variation_item_margin_bottom',
			'margin-left'   => 'variation_item_margin_left',
		),
	)
);

/*
 ===============================
	Style > Popup Swatches
   =============================== */
   /* Color */
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_color_width',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch.swatch_color",
		'units'        => 'px',
		'prop'         => 'width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_color_height',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch.swatch_color",
		'units'        => 'px',
		'prop'         => 'height',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_color_border_radius',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch_color",
		'units'        => 'px',
		'prop'         => 'border-radius',
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_color_label_border',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch_color",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro_swatches .swatch_color.selected",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_swatch_color_selected_label_border,
		),
	)
);

   /* Image */
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_image_width',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch.swatch_image",
		'units'        => 'px',
		'prop'         => 'width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_image_height',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch.swatch_image",
		'units'        => 'px',
		'prop'         => 'height',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_image_border_radius',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch_image",
		'units'        => 'px',
		'prop'         => 'border-radius',
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_image_label_border',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch_image",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id  .xpro_swatches .swatch_image.selected",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_swatch_image_selected_label_border,
		),
	)
);

   /* Label */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_label_typography',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch_label",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro_swatches .swatch_label",
		'props'    => array(
			'color' => $settings->quick_view_styles->variation_swatch_label_text_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro_swatches .swatch_label",
		'props'    => array(
			'background-color' => $settings->quick_view_styles->variation_swatch_label_background_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro_swatches .swatch_label.selected",
		'props'    => array(
			'border-color' => $settings->quick_view_styles->variation_swatch_label_selected_label_border,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_label_label_border',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch_label",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->quick_view_styles,
		'setting_name' => 'variation_swatch_label_padding',
		'selector'     => ".fl-node-$id .xpro_swatches .swatch_label",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'variation_swatch_label_padding_top',
			'padding-right'  => 'variation_swatch_label_padding_right',
			'padding-bottom' => 'variation_swatch_label_padding_bottom',
			'padding-left'   => 'variation_swatch_label_padding_left',
		),
	)
);

/*
 ===============================
	Style > Badge
   =============================== */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'badges_typography',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-badges-btn",
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'badges_bg_size',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn,.fl-node-$id .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn",
		'units'        => 'px',
		'prop'         => 'width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'badges_bg_size',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn,.fl-node-$id .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn",
		'units'        => 'px',
		'prop'         => 'height',
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'badges_btn_border',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-badges-btn",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'badges_btn_padding',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-product-grid-badges-wrapper",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'badges_btn_padding_top',
			'padding-right'  => 'badges_btn_padding_right',
			'padding-bottom' => 'badges_btn_padding_bottom',
			'padding-left'   => 'badges_btn_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'button_margin',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-badges-btn",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'button_margin_top',
			'margin-right'  => 'button_margin_right',
			'margin-bottom' => 'button_margin_bottom',
			'margin-left'   => 'button_margin_left',
		),
	)
);

/* Sale */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn",
		'props'    => array(
			'color' => $settings->badges_styles->sale_btn_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn",
		'props'    => array(
			'background-color' => $settings->badges_styles->sale_btn_background,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'sale_btn_margin',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-sale-flash-btn",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'sale_btn_margin_top',
			'margin-right'  => 'sale_btn_margin_right',
			'margin-bottom' => 'sale_btn_margin_bottom',
			'margin-left'   => 'sale_btn_margin_left',
		),
	)
);

/* Featured */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn",
		'props'    => array(
			'color' => $settings->badges_styles->featured_btn_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn",
		'props'    => array(
			'background-color' => $settings->badges_styles->featured_btn_background,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'featured_btn_margin',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-featured-flash-btn",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'featured_btn_margin_top',
			'margin-right'  => 'featured_btn_margin_right',
			'margin-bottom' => 'featured_btn_margin_bottom',
			'margin-left'   => 'featured_btn_margin_left',
		),
	)
);

/* Out of Stock */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn",
		'props'    => array(
			'color' => $settings->badges_styles->out_stock_btn_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn",
		'props'    => array(
			'background-color' => $settings->badges_styles->out_stock_background,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->badges_styles,
		'setting_name' => 'out_stock_margin',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-item .xpro-woo-out-of-stock-btn",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'out_stock_margin_top',
			'margin-right'  => 'out_stock_margin_right',
			'margin-bottom' => 'out_stock_margin_bottom',
			'margin-left'   => 'out_stock_margin_left',
		),
	)
);

/*
 ===============================
	Style > Button
   =============================== */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_typography',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button",
		'props'    => array(
			'color' => $settings->button_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button",
		'props'    => array(
			'background-color' => $settings->button_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button:hover,.fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button:focus",
		'props'    => array(
			'color' => $settings->button_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button:hover,.fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button:focus",
		'props'    => array(
			'background-color' => $settings->button_hbg,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_border',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_item_padding',
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button",
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
		'selector'     => ".fl-node-$id .xpro-woo-product-grid-add-to-cart-btn .button",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'button_margin_top',
			'margin-right'  => 'button_margin_right',
			'margin-bottom' => 'button_margin_bottom',
			'margin-left'   => 'button_margin_left',
		),
	)
);

/*
 ===============================
	Style > Pagination
   =============================== */
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->pagination_styles,
		'setting_name' => 'pagination_alignment',
		'selector'     => ".fl-node-$id .xpro-elementor-post-pagination",
		'prop'         => 'justify-content',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->pagination_styles,
		'setting_name' => 'pagination_typography',
		'selector'     => ".fl-node-$id .xpro-elementor-post-pagination .page-numbers",
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->pagination_styles,
		'setting_name' => 'pagination_space_between',
		'selector'     => ".fl-node-$id .xpro-elementor-post-pagination",
		'unit'         => 'px',
		'prop'         => 'grid-gap',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-elementor-post-pagination .page-numbers",
		'props'    => array(
			'color'            => $settings->pagination_styles->pagination_color,
			'background-color' => $settings->pagination_styles->pagination_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-elementor-post-pagination .page-numbers:hover",
		'props'    => array(
			'color'            => $settings->pagination_styles->pagination_hover_color,
			'background-color' => $settings->pagination_styles->pagination_bg_hover_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-elementor-post-pagination .page-numbers.current",
		'props'    => array(
			'color'            => $settings->pagination_styles->pagination_active_color,
			'background-color' => $settings->pagination_styles->pagination_bg_arctive_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->pagination_styles,
		'setting_name' => 'pagination_border',
		'selector'     => ".fl-node-$id .xpro-elementor-post-pagination .page-numbers",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->pagination_styles,
		'setting_name' => 'pagination_padding',
		'selector'     => ".fl-node-$id .xpro-elementor-post-pagination .page-numbers",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'pagination_padding_top',
			'padding-right'  => 'pagination_padding_right',
			'padding-bottom' => 'pagination_padding_bottom',
			'padding-left'   => 'pagination_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->pagination_styles,
		'setting_name' => 'pagination_margin',
		'selector'     => ".fl-node-$id .xpro-elementor-post-pagination",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'pagination_margin_top',
			'margin-right'  => 'pagination_margin_right',
			'margin-bottom' => 'pagination_margin_bottom',
			'margin-left'   => 'pagination_margin_left',
		),
	)
);
