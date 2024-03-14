<?php
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'enabled'  => 'left' === $settings->alignment,
		'props'    => array(
			'align-items' => 'flex-start',
			'text-align'  => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'enabled'  => 'center' === $settings->alignment,
		'props'    => array(
			'align-items' => 'center',
			'text-align'  => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'enabled'  => 'right' === $settings->alignment,
		'props'    => array(
			'align-items' => 'flex-end',
			'text-align'  => 'right',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'enabled'  => 'left' === $settings->alignment,
		'props'    => array(
			'justify-content' => 'flex-start',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'enabled'  => 'center' === $settings->alignment,
		'props'    => array(
			'justify-content' => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'enabled'  => 'right' === $settings->alignment,
		'props'    => array(
			'justify-content' => 'flex-end',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'enabled'  => 'left' === $settings->alignment,
		'props'    => array(
			'flex-direction' => 'row',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'enabled'  => 'center' === $settings->alignment,
		'props'    => array(
			'flex-direction' => 'row',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'enabled'  => 'right' === $settings->alignment,
		'props'    => array(
			'flex-direction' => 'row-reverse',
		),
	)
);

// Alignment Medium
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'media'    => 'medium',
		'enabled'  => 'left' === $settings->alignment_medium,
		'props'    => array(
			'align-items' => 'flex-start',
			'text-align'  => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'media'    => 'medium',
		'enabled'  => 'center' === $settings->alignment_medium,
		'props'    => array(
			'align-items' => 'center',
			'text-align'  => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'media'    => 'medium',
		'enabled'  => 'right' === $settings->alignment_medium,
		'props'    => array(
			'align-items' => 'flex-end',
			'text-align'  => 'right',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'media'    => 'medium',
		'enabled'  => 'left' === $settings->alignment_medium,
		'props'    => array(
			'justify-content' => 'flex-start',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'media'    => 'medium',
		'enabled'  => 'center' === $settings->alignment_medium,
		'props'    => array(
			'justify-content' => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'media'    => 'medium',
		'enabled'  => 'right' === $settings->alignment_medium,
		'props'    => array(
			'justify-content' => 'flex-end',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'media'    => 'medium',
		'enabled'  => 'left' === $settings->alignment_medium,
		'props'    => array(
			'flex-direction' => 'row',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'media'    => 'medium',
		'enabled'  => 'center' === $settings->alignment_medium,
		'props'    => array(
			'flex-direction' => 'row',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'media'    => 'medium',
		'enabled'  => 'right' === $settings->alignment_medium,
		'props'    => array(
			'flex-direction' => 'row-reverse',
		),
	)
);

// Alignment Responsive
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'media'    => 'responsive',
		'enabled'  => 'left' === $settings->alignment_responsive,
		'props'    => array(
			'align-items' => 'flex-start',
			'text-align'  => 'left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'media'    => 'responsive',
		'enabled'  => 'center' === $settings->alignment_responsive,
		'props'    => array(
			'align-items' => 'center',
			'text-align'  => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'media'    => 'responsive',
		'enabled'  => 'right' === $settings->alignment_responsive,
		'props'    => array(
			'align-items' => 'flex-end',
			'text-align'  => 'right',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'media'    => 'responsive',
		'enabled'  => 'left' === $settings->alignment_responsive,
		'props'    => array(
			'justify-content' => 'flex-start',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'media'    => 'responsive',
		'enabled'  => 'center' === $settings->alignment_responsive,
		'props'    => array(
			'justify-content' => 'center',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-meta-list",
		'media'    => 'responsive',
		'enabled'  => 'right' === $settings->alignment_responsive,
		'props'    => array(
			'justify-content' => 'flex-end',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'media'    => 'responsive',
		'enabled'  => 'left' === $settings->alignment_responsive,
		'props'    => array(
			'flex-direction' => 'row',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'media'    => 'responsive',
		'enabled'  => 'center' === $settings->alignment_responsive,
		'props'    => array(
			'flex-direction' => 'row',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author,.fl-node-$id .xpro-post-grid-meta-list > li",
		'media'    => 'responsive',
		'enabled'  => 'right' === $settings->alignment_responsive,
		'props'    => array(
			'flex-direction' => 'row-reverse',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'item_height',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-item",
		'units'        => 'px',
		'prop'         => 'height',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_height',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-image",
		'units'        => 'px',
		'prop'         => 'height',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-item",
		'enabled'  => 'color' === $settings->item_bg_type,
		'props'    => array(
			'background-color' => $settings->item_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-item",
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
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-item",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'item_padding',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-item",
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
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-item .xpro-post-grid-image::after",
		'props'    => array(
			'color' => $settings->overlay_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-item:hover .xpro-post-grid-image::after",
		'props'    => array(
			'color' => $settings->overlay_hcolor,
		),
	)
);

/*
 ==========================================
	Style > Content
   ========================================== */


FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'enabled'  => 'color' === $settings->content_styles->content_bg_type,
		'props'    => array(
			'background-color' => $settings->content_styles->content_background,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-content",
		'enabled'  => 'gradient' === $settings->content_styles->content_bg_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->content_styles->content_gradient ),
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->content_styles,
		'setting_name' => 'content_border',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-content",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->content_styles,
		'setting_name' => 'content_padding',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-content",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'content_padding_top',
			'padding-right'  => 'content_padding_right',
			'padding-bottom' => 'content_padding_bottom',
			'padding-left'   => 'content_padding_left',
		),
	)
);

/* Title */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->content_styles,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-title",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-title",
		'props'    => array(
			'color' => $settings->content_styles->title_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-title:hover",
		'props'    => array(
			'color' => $settings->content_styles->title_hover_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->content_styles,
		'setting_name' => 'title_margin',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-title",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'title_margin_top',
			'margin-right'  => 'title_margin_right',
			'margin-bottom' => 'title_margin_bottom',
			'margin-left'   => 'title_margin_left',
		),
	)
);

/* Content */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->content_styles,
		'setting_name' => 'description_typography',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-excerpt",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-excerpt",
		'props'    => array(
			'color' => $settings->content_styles->excerpt_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->content_styles,
		'setting_name' => 'excerpt_margin',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-excerpt",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'excerpt_margin_top',
			'margin-right'  => 'excerpt_margin_right',
			'margin-bottom' => 'excerpt_margin_bottom',
			'margin-left'   => 'excerpt_margin_left',
		),
	)
);

/*
 ==========================================
	Style > Meta
   ========================================== */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->meta_styles,
		'setting_name' => 'meta_typography',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li",
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->meta_styles,
		'setting_name' => 'meta_space_between',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list",
		'units'        => 'px',
		'prop'         => 'grid-gap',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li > i",
		'props'    => array(
			'color' => $settings->meta_styles->meta_icon_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li,.fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li a",
		'props'    => array(
			'color' => $settings->meta_styles->meta_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li",
		'props'    => array(
			'background-color' => $settings->meta_styles->meta_bg_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->meta_styles,
		'setting_name' => 'meta_border',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->meta_styles,
		'setting_name' => 'meta_padding',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'meta_padding_top',
			'padding-right'  => 'meta_padding_right',
			'padding-bottom' => 'meta_padding_bottom',
			'padding-left'   => 'meta_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->meta_styles,
		'setting_name' => 'meta_margin',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-meta-list > li",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'meta_margin_top',
			'margin-right'  => 'meta_margin_right',
			'margin-bottom' => 'meta_margin_bottom',
			'margin-left'   => 'meta_margin_left',
		),
	)
);

// Wrapper
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->meta_styles,
		'setting_name' => 'meta_wrapper_border',
		'selector'     => ".fl-node-$id .xpro-post-grid-layout-7 .xpro-post-grid-meta-list",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->meta_styles,
		'setting_name' => 'meta_wrapper_padding',
		'selector'     => ".fl-node-$id .xpro-post-grid-layout-7 .xpro-post-grid-meta-list",
		'units'        => 'px',
		'props'        => array(
			'padding-top'    => 'meta_wrapper_padding_top',
			'padding-right'  => 'meta_wrapper_padding_right',
			'padding-bottom' => 'meta_wrapper_padding_bottom',
			'padding-left'   => 'meta_wrapper_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->meta_styles,
		'setting_name' => 'meta_wrapper_margin',
		'selector'     => ".fl-node-$id .xpro-post-grid-layout-7 .xpro-post-grid-meta-list",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'meta_wrapper_margin_top',
			'margin-right'  => 'meta_wrapper_margin_right',
			'margin-bottom' => 'meta_wrapper_margin_bottom',
			'margin-left'   => 'meta_wrapper_margin_left',
		),
	)
);

/*
 ==========================================
	Style > Author
   ========================================== */
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-author img",
		'props'    => array(
			'width'  => $settings->author_styles->avatar_size . 'px',
			'height' => $settings->author_styles->avatar_size . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-layout-4 .xpro-post-grid-content",
		'props'    => array(
			'margin-top' => 'calc(' . $settings->author_styles->avatar_size . 'px / 2 )',
		),
	)
);

// Medium
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-author img",
		'media'    => 'medium',
		'props'    => array(
			'width'  => $settings->author_styles->avatar_size_medium . 'px',
			'height' => $settings->author_styles->avatar_size_medium . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-layout-4 .xpro-post-grid-content",
		'media'    => 'medium',
		'props'    => array(
			'margin-top' => 'calc(' . $settings->author_styles->avatar_size_medium . 'px / 2 )',
		),
	)
);

// Responsive
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-author img",
		'media'    => 'responsive',
		'props'    => array(
			'width'  => $settings->author_styles->avatar_size_responsive . 'px',
			'height' => $settings->author_styles->avatar_size_responsive . 'px',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-layout-4 .xpro-post-grid-content",
		'media'    => 'responsive',
		'props'    => array(
			'margin-top' => 'calc(' . $settings->author_styles->avatar_size_responsive . 'px / 2 )',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings->author_styles,
		'setting_name' => 'author_space_between',
		'selector'     => ".fl-node-$id .xpro-post-grid-author",
		'units'        => 'px',
		'prop'         => 'grid-gap',
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings->author_styles,
		'setting_name' => 'author_border',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-author img",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->author_styles,
		'setting_name' => 'author_wrapper_margin',
		'selector'     => ".fl-node-$id .xpro-post-grid-wrapper .xpro-post-grid-author img",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'author_wrapper_margin_top',
			'margin-right'  => 'author_wrapper_margin_right',
			'margin-bottom' => 'author_wrapper_margin_bottom',
			'margin-left'   => 'author_wrapper_margin_left',
		),
	)
);

/* Title */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->author_styles,
		'setting_name' => 'author_title_typography',
		'selector'     => ".fl-node-$id .xpro-post-grid-author-title",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author-title",
		'props'    => array(
			'color' => $settings->author_styles->author_title_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->author_styles,
		'setting_name' => 'author_title_margin',
		'selector'     => ".fl-node-$id .xpro-post-grid-author-title",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'author_title_margin_top',
			'margin-right'  => 'author_title_margin_right',
			'margin-bottom' => 'author_title_margin_bottom',
			'margin-left'   => 'author_title_margin_left',
		),
	)
);

/* Name */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings->author_styles,
		'setting_name' => 'author_name_typography',
		'selector'     => ".fl-node-$id .xpro-post-grid-author-name",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-post-grid-author-name",
		'props'    => array(
			'color' => $settings->author_styles->author_name_color,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings->author_styles,
		'setting_name' => 'author_name_margin',
		'selector'     => ".fl-node-$id .xpro-post-grid-author-name",
		'units'        => 'px',
		'props'        => array(
			'margin-top'    => 'excerpt_margin_top',
			'margin-right'  => 'excerpt_margin_right',
			'margin-bottom' => 'excerpt_margin_bottom',
			'margin-left'   => 'excerpt_margin_left',
		),
	)
);

/*
 ==========================================
	Style > Pagination
   ========================================== */
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
		'settings'     =>$settings->pagination_styles,
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
