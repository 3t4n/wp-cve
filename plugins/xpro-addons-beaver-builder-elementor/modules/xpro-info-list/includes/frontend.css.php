<?php

$list_items_count = count( $settings->list_items );
for ( $i = 0; $i < $list_items_count; $i++ ) {
	$item = $settings->list_items[ $i ];
	// media custom color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xproinfo-repeater-item-$i .xpro-infolist-media-type-custom i",
			'props'    => array(
				'color' => $item->media_color,
			),
		)
	);
	// media custom bg color/border color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xproinfo-repeater-item-$i .xpro-infolist-media-type-custom",
			'props'    => array(
				'background-color' => $item->media_bgcolor,
				'border-color'     => $item->media_border_color,
			),
		)
	);

}

if ( 'center' === $settings->list_align ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-item",
			'props'    => array(
				'flex-direction' => 'column',
				'text-align'     => 'center',
			),
		)
	);
endif;

if ( 'right' === $settings->list_align ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-item",
			'props'    => array(
				'flex-direction' => 'row-reverse',
				'text-align'     => 'right',
			),
		)
	);
endif;

if ( 'left' === $settings->list_align_medium ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-item",
			'media'    => 'medium',
			'props'    => array(
				'flex-direction' => 'row',
				'text-align'     => 'left',
			),
		)
	);
endif;

if ( 'center' === $settings->list_align_medium ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-item",
			'media'    => 'medium',
			'props'    => array(
				'flex-direction' => 'column',
				'text-align'     => 'center',
			),
		)
	);
endif;

if ( 'right' === $settings->list_align_medium ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-item",
			'media'    => 'medium',
			'props'    => array(
				'flex-direction' => 'row-reverse',
				'text-align'     => 'right',
			),
		)
	);
endif;

if ( 'left' === $settings->list_align_responsive ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-item",
			'media'    => 'responsive',
			'props'    => array(
				'flex-direction' => 'row',
				'text-align'     => 'left',
			),
		)
	);
endif;

if ( 'center' === $settings->list_align_responsive ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-item",
			'media'    => 'responsive',
			'props'    => array(
				'flex-direction' => 'column',
				'text-align'     => 'center',
			),
		)
	);
endif;

if ( 'right' === $settings->list_align_responsive ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-item",
			'media'    => 'responsive',
			'props'    => array(
				'flex-direction' => 'row-reverse',
				'text-align'     => 'right',
			),
		)
	);
endif;

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-layout-vertical .xpro-infolist-item",
		'props'    => array(
		    'align-items'  => $settings->vertical_align,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-layout-vertical .xpro-infolist-item",
		'media' => 'medium',
		'props'    => array(
		    'align-items'  => $settings->vertical_align_medium,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-layout-vertical .xpro-infolist-item",
		'media' => 'responsive',
		'props'    => array(
		    'align-items'  => $settings->vertical_align_responsive,
		),
	)
);


// grid system.
if ( $settings->list_item_per_row ) :
	// resp rule.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $settings,
			'setting_name' => 'list_item_per_row',
			'selector'     => ".fl-node-$id .xpro-infolist-layout-horizontal .xpro-infolist-item",
			'prop'         => '--xpro-grid-item',
		)
	);
endif;

// list item space.
if ( $settings->list_item_space ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-layout-horizontal .xpro-infolist-item",
			'props'    => array(
				'width'        => 'calc(100%/var(--xpro-grid-item) - ' . $settings->list_item_space . 'px)',
				'margin-left'  => 'calc(' . $settings->list_item_space . 'px/2)',
				'margin-right' => 'calc(' . $settings->list_item_space . 'px/2)',
			),
		)
	);
	// medium rule.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-layout-horizontal .xpro-infolist-item",
			'media'    => 'medium',
			'props'    => array(
				'width'        => 'calc(100%/var(--xpro-grid-item) - ' . $settings->list_item_space_medium . 'px)',
				'margin-left'  => 'calc(' . $settings->list_item_space_medium . 'px/2)',
				'margin-right' => 'calc(' . $settings->list_item_space_medium . 'px/2)',
			),
		)
	);
	// resp rule.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-layout-horizontal .xpro-infolist-item",
			'media'    => 'responsive',
			'props'    => array(
				'width'        => 'calc(100%/var(--xpro-grid-item) - ' . $settings->list_item_space_responsive . 'px)',
				'margin-left'  => 'calc(' . $settings->list_item_space_responsive . 'px/2)',
				'margin-right' => 'calc(' . $settings->list_item_space_responsive . 'px/2)',
			),
		)
	);
endif;

// list item space bottom.
if ( $settings->list_item_space_bottom ) :
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .xpro-infolist-layout-vertical .xpro-infolist-item:not(:nth-last-child(1)), .fl-node-$id .xpro-infolist-layout-horizontal .xpro-infolist-item, .fl-node-$id .xpro-infolist-media-type-icon::before",
			'props'    => array(
				'margin-bottom' => $settings->list_item_space_bottom . 'px',
			),
		)
	);
endif;

// list item bg.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-item",
		'props'    => array(
			'background-color' => $settings->list_item_bg,
		),
	)
);

// list item border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'list_item_border',
		'selector'     => ".fl-node-$id .xpro-infolist-item",
	)
);

// list item padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'list_item_padding',
		'selector'     => ".fl-node-$id .xpro-infolist-item",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'list_item_padding_top',
			'padding-right'  => 'list_item_padding_right',
			'padding-bottom' => 'list_item_padding_bottom',
			'padding-left'   => 'list_item_padding_left',
		),
	)
);

// media item color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media",
		'props'    => array(
			'color'            => $settings->media_item_color,
			'background-color' => $settings->media_item_bg,
		),
	)
);

// media item border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'media_item_border',
		'selector'     => ".fl-node-$id .xpro-infolist-media, .fl-node-$id .xpro-infolist-media-type-custom ",
	)
);

// media margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'media_margin',
		'selector'     => ".fl-node-$id .xpro-infolist-media",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'media_margin_top',
			'margin-right'  => 'media_margin_right',
			'margin-bottom' => 'media_margin_bottom',
			'margin-left'   => 'media_margin_left',
		),
	)
);

// media padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'media_padding',
		'selector'     => ".fl-node-$id .xpro-infolist-media",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'media_padding_top',
			'padding-right'  => 'media_padding_right',
			'padding-bottom' => 'media_padding_bottom',
			'padding-left'   => 'media_padding_left',
		),
	)
);

// media icon size.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-icon",
		'props'    => array(
			'font-size'  => $settings->media_icon_size . 'px',
			'min-height' => $settings->media_icon_size . 'px',
			'min-width'  => $settings->media_icon_size . 'px',
		),
	)
);
// medium screen.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-icon",
		'media'    => 'medium',
		'props'    => array(
			'font-size'  => $settings->media_icon_size_medium . 'px',
			'min-height' => $settings->media_icon_size_medium . 'px',
			'min-width'  => $settings->media_icon_size_medium . 'px',
		),
	)
);
// resp screen.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-icon",
		'media'    => 'responsive',
		'props'    => array(
			'font-size'  => $settings->media_icon_size_responsive . 'px',
			'min-height' => $settings->media_icon_size_responsive . 'px',
			'min-width'  => $settings->media_icon_size_responsive . 'px',
		),
	)
);

// media icon bg size.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-icon",
		'props'    => array(
			'height' => $settings->media_icon_bgsize . 'px',
			'width'  => $settings->media_icon_bgsize . 'px',
		),
	)
);
// medium screen.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-icon",
		'media'    => 'medium',
		'props'    => array(
			'height' => $settings->media_icon_bgsize_medium . 'px',
			'width'  => $settings->media_icon_bgsize_medium . 'px',
		),
	)
);
// resp screen.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-icon",
		'media'    => 'responsive',
		'props'    => array(
			'height' => $settings->media_icon_bgsize_responsive . 'px',
			'width'  => $settings->media_icon_bgsize_responsive . 'px',
		),
	)
);

// seprator style.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'media_item_border',
		'selector'     => ".fl-node-$id .xpro-infolist-media-type-icon::before",
	)
);

// seprator width, height, color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-icon::before",
		'props'    => array(
			'height' => $settings->media_icon_separator_width . 'px',
			'width'  => $settings->media_icon_separator_height . 'px',
			'color'  => $settings->media_icon_separator_color,
		),
	)
);

// media img size -width, height.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-image img",
		'props'    => array(
			'width'      => $settings->media_image_size . 'px',
			'height'     => $settings->image_height . 'px',
			'object-fit' => $settings->object_fit,
		),
	)
);
// medium screen.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-image img",
		'media'    => 'medium',
		'props'    => array(
			'width'      => $settings->media_image_size_medium . 'px',
			'height'     => $settings->image_height_medium . 'px',
			'object-fit' => $settings->object_fit,
		),
	)
);
// small screen.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-image img",
		'media'    => 'responsive',
		'props'    => array(
			'width'      => $settings->media_image_size_responsive . 'px',
			'height'     => $settings->image_height_responsive . 'px',
			'object-fit' => $settings->object_fit,
		),
	)
);

// media_custom_bg_size.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-custom",
		'props'    => array(
			'width'  => $settings->media_custom_bg_size . 'px',
			'height' => $settings->media_custom_bg_size . 'px',
		),
	)
);
// medium screen.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-custom",
		'media'    => 'medium',
		'props'    => array(
			'width'  => $settings->media_custom_bg_size_medium . 'px',
			'height' => $settings->media_custom_bg_size_medium . 'px',
		),
	)
);
// small screen.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-media-type-custom",
		'media'    => 'responsive',
		'props'    => array(
			'width'  => $settings->media_custom_bg_size_responsive . 'px',
			'height' => $settings->media_custom_bg_size_responsive . 'px',
		),
	)
);

// media custom padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'media_custom_padding',
		'selector'     => ".fl-node-$id .xpro-infolist-media-type-custom",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'media_custom_padding_top',
			'padding-right'  => 'media_custom_padding_right',
			'padding-bottom' => 'media_custom_padding_bottom',
			'padding-left'   => 'media_custom_padding_left',
		),
	)
);

// title color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-title",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);

// title hv color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-item:hover .xpro-infolist-title",
		'props'    => array(
			'color' => $settings->title_hover_color,
		),
	)
);

// title margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_margin',
		'selector'     => ".fl-node-$id .xpro-infolist-title",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'title_margin_top',
			'margin-right'  => 'title_margin_right',
			'margin-bottom' => 'title_margin_bottom',
			'margin-left'   => 'title_margin_left',
		),
	)
);

// title padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_padding',
		'selector'     => ".fl-node-$id .xpro-infolist-title",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'title_padding_top',
			'padding-right'  => 'title_padding_right',
			'padding-bottom' => 'title_padding_bottom',
			'padding-left'   => 'title_padding_left',
		),
	)
);

// desc color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-desc",
		'props'    => array(
			'color' => $settings->desc_color,
		),
	)
);

// desc hv color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-infolist-item:hover .xpro-infolist-desc",
		'props'    => array(
			'color' => $settings->desc_hv_color,
		),
	)
);

// desc margin.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'desc_margin',
		'selector'     => ".fl-node-$id .xpro-infolist-desc",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'desc_margin_top',
			'margin-right'  => 'desc_margin_right',
			'margin-bottom' => 'desc_margin_bottom',
			'margin-left'   => 'desc_margin_left',
		),
	)
);

// desc padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'desc_padding',
		'selector'     => ".fl-node-$id .xpro-infolist-desc",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'desc_padding_top',
			'padding-right'  => 'desc_padding_right',
			'padding-bottom' => 'desc_padding_bottom',
			'padding-left'   => 'desc_padding_left',
		),
	)
);

// title typo.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .xpro-infolist-title",
	)
);

// desc typo.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'description_typography',
		'selector'     => ".fl-node-$id .xpro-infolist-desc",
	)
);

// content typo.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'count_typography',
		'selector'     => ".fl-node-$id .xpro-infolist-custom",
	)
);
