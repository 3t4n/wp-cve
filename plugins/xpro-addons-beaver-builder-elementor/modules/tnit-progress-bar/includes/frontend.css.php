<?php
/**
 * TNIT Progress Bar front-end CSS php file
 *
 * @package TNIT Progress Bar
 * @since 1.1.3
 */

// Items Css.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item",
		'props'    => array(
			'margin-bottom'    => ( '' !== $settings->item_spacing ) ? $settings->item_spacing . 'px' : '',
			'background-color' => $settings->items_bg_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressBox::after",
		'props'    => array(
			'border-top-color' => $settings->items_bg_color,
		),
	)
);
// Items Padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'items_padding',
		'selector'     => ".fl-node-$id .tnit-progressbar-item",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'items_padding_top',
			'padding-right'  => 'items_padding_right',
			'padding-bottom' => 'items_padding_bottom',
			'padding-left'   => 'items_padding_left',
		),
	)
);
// Items border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'items_border',
		'selector'     => ".fl-node-$id .tnit-progressbar-item",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressBox::after",
		'props'    => array(
			'border-top-color' => ( '' !== $settings->items_border ) ? $settings->items_border['color'] : '',

		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item_v1 .tnit-progressbar .tnit-percentCount",
		'media'    => 'default',
		'props'    => array(
			'width'  => ( ! empty( $settings->value_typography['font_size']['length'] ) ) ? $settings->value_typography['font_size']['length'] * 2.5 . 'px' : '',
			'height' => ( ! empty( $settings->value_typography['font_size']['length'] ) ) ? $settings->value_typography['font_size']['length'] * 2.5 . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item_v1 .tnit-progressbar .tnit-percentCount",
		'media'    => 'medium',
		'props'    => array(
			'width'  => ( ! empty( $settings->value_typography_medium['font_size']['length'] ) ) ? $settings->value_typography_medium['font_size']['length'] * 2.5 . 'px' : '',
			'height' => ( ! empty( $settings->value_typography_medium['font_size']['length'] ) ) ? $settings->value_typography_medium['font_size']['length'] * 2.5 . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item_v1 .tnit-progressbar .tnit-percentCount",
		'media'    => 'responsive',
		'props'    => array(
			'width'  => ( ! empty( $settings->value_typography_responsive['font_size']['length'] ) ) ? $settings->value_typography_responsive['font_size']['length'] * 2.5 . 'px' : '',
			'height' => ( ! empty( $settings->value_typography_responsive['font_size']['length'] ) ) ? $settings->value_typography_responsive['font_size']['length'] * 2.5 . 'px' : '',
		),
	)
);

// Height for style 1.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-proggress",
		'enabled'  => 'style-1' === $settings->progressbar_style,
		'props'    => array(
			'height' => $settings->progressbar_thickness . 'px',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-percentCount",
		'enabled'  => 'style-1' === $settings->progressbar_style,
		'props'    => array(
			'margin-top' => 'calc(-50px - ' . $settings->progressbar_thickness . 'px)',

		),
	)
);

// Height for style 2.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-proggress,
						.fl-node-$id .tnit-progressbar-item_v3 .tnit-progressbar .tnit-proggress",
		'enabled'  => 'style-2' === $settings->progressbar_style,
		'props'    => array(
			'height' => $settings->progressbar_thickness . 'px',

		),
	)
);
// Height for style 3.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item_v2 .tnit-progressbar .tnit-proggress, 
					   .fl-node-$id .tnit-progressbar-item_v2 .tnit-progressbar .tnit-percentCount,
					   .fl-node-$id .tnit-progressbar-item_v2 .tnit-progress-title",
		'enabled'  => 'style-3' === $settings->progressbar_style,
		'props'    => array(
			'height' => $settings->progressbar_thickness . 'px',

		),
	)
);
// Height for style 4.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item_v3 .tnit-progressbar .tnit-proggress",
		'enabled'  => 'style-4' === $settings->progressbar_style,
		'props'    => array(
			'height' => $settings->progressbar_thickness . 'px',

		),
	)
);

// Height for style 5.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progress-item-flex .tnit-progressbar .tnit-proggress",
		'enabled'  => 'style-5' === $settings->progressbar_style,
		'props'    => array(
			'height' => ( '' !== $settings->progressbar_thickness ) ? $settings->progressbar_thickness . 'px' : '',

		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progress-item-flex .tnit-progressbar .tnit-percentCount",
		'enabled'  => 'style-5' === $settings->progressbar_style,
		'props'    => array(
			'height' => ( $settings->progressbar_thickness ) ? $settings->progressbar_thickness + 15 . 'px' : '',
			'width'  => ( $settings->progressbar_thickness ) ? $settings->progressbar_thickness + 15 . 'px' : '',
		),
	)
);

// Spacing between title and bar.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item .tnit-progress-title",
		'enabled'  => 'style-3' !== $settings->progressbar_style,
		'props'    => array(
			'margin-bottom' => ( '' !== $settings->title_spacing ) ? $settings->title_spacing . 'px' : '',
		),
	)
);
// Spacing title for style 5.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item.tnit-progress-item-flex .tnit-progress-title",
		'props'    => array(
			'margin-right'  => ( '' !== $settings->title_spacing ) ? $settings->title_spacing . 'px' : '',
			'margin-bottom' => '0',
		),
	)
);


// Border Settings.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'border',
		'selector'     => ".fl-node-$id .tnit-progressbar",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar,.fl-node-$id .tnit-progressbar .tnit-proggress",
		'props'    => array(
			'border-top-left-radius'     => ( '' !== $settings->border && '' !== $settings->border['radius']['top_left'] ) ? $settings->border['radius']['top_left'] . 'px' : '',
			'border-top-right-radius'    => ( '' !== $settings->border && '' !== $settings->border['radius']['top_right'] ) ? $settings->border['radius']['top_right'] . 'px' : '',
			'border-bottom-left-radius'  => ( '' !== $settings->border && '' !== $settings->border['radius']['bottom_left'] ) ? $settings->border['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->border && '' !== $settings->border['radius']['bottom_right'] ) ? $settings->border['radius']['bottom_right'] . 'px' : '',
		),
	)
);

// Title Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .tnit-progressbar-item .tnit-progress-title",
	)
);

// Title Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item .tnit-progress-title",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);

// Value Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'value_typography',
		'selector'     => ".fl-node-$id .tnit-progressbar-item .tnit-percentCount",
	)
);

// Value Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressBox .tnit-percentCount,
						.fl-node-$id .tnit-progressbar-item_v2 .tnit-progressbar .tnit-percentCount,
						.fl-node-$id .tnit-progressbar-item_v1 .tnit-progressbar .tnit-percentCount,
						.fl-node-$id .tnit-progress-style_v2 .tnit-percentCount,
						.fl-node-$id .tnit-progressbar-item_v3 .tnit-percentCount",
		'props'    => array(
			'color' => $settings->value_color,
		),
	)
);
// Value Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item_v2 .tnit-progressbar .tnit-percentCount,
						.fl-node-$id .tnit-progress-item-flex .tnit-progressbar .tnit-percentCount,
						.fl-node-$id .tnit-progress-style_v2 .tnit-percentCount",
		'props'    => array(
			'background-color' => $settings->value_bgcolor,
		),
	)
);
// Value Color Top Border Tip.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progress-style_v2 .tnit-percentCount:before",
		'props'    => array(
			'border-bottom-color' => $settings->value_bgcolor,
		),
	)
);
// Value Color Top Border Tip for style 3.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item_v2 .tnit-progressbar .tnit-percentCount:after",
		'props'    => array(
			'border-left-color' => $settings->value_bgcolor,
		),
	)
);
// Description Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'descp_typography',
		'selector'     => ".fl-node-$id .tnit-progressbar-item .tnit-text",
	)
);

// Title Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item .tnit-text",
		'props'    => array(
			'color' => $settings->desc_color,
		),
	)
);


// Progress Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item .tnit-proggress",
		'enabled'  => 'color' === $settings->progress_color_type,
		'props'    => array(
			'background-color' => $settings->progress_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item .tnit-proggress",
		'enabled'  => 'gradient' === $settings->progress_color_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->progress_gradient ),
		),
	)
);
// Progress Bar Base color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-progressbar-item .tnit-progressbar",
		'props'    => array(
			'background-color' => $settings->progressbar_base_color,
		),
	)
);

// Loop Start.
$progress_items_count = count( $settings->progressbar_items );
for ( $i = 0; $i < $progress_items_count; $i++ ) {
	$progressbar_item = $settings->progressbar_items[ $i ];

	$progress_gradient_JSON = json_decode( json_encode( $progressbar_item->progress_gradient ), true );

	// Progress Bar Background-Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-progressbar-item-$i .tnit-progressbar",
			'props'    => array(
				'background-color' => $progressbar_item->progressbar_base_color,
			),
		)
	);
	// Progress Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-progressbar-item-$i .tnit-proggress",
			'enabled'  => 'color' === $settings->progress_color_type,
			'props'    => array(
				'background-color' => $progressbar_item->progress_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-progressbar-item-$i .tnit-proggress",
			'enabled'  => 'gradient' === $settings->progress_color_type && ( ! empty( $progress_gradient_JSON['colors'][0] ) || ! empty( $progress_gradient_JSON['colors'][1] ) ),
			'props'    => array(
				'background-image' => $module->tnit_form_gradient( $progressbar_item->progress_gradient ),
			),
		)
	);

}
