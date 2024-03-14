<?php

// Infobox grid numbers.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hover-card-grid",
		'props'    => array(
			'margin' => ( '' !== $settings->gutter_size ) ? '-' . $settings->gutter_size / 2 . 'px' : '',
		),
	)
);
// Infobox grid numbers - medium.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hover-card-grid",
		'media'    => 'medium',
		'props'    => array(
			'margin' => ( '' !== $settings->gutter_size_medium ) ? '-' . $settings->gutter_size_medium / 2 . 'px' : '',
		),
	)
);
// Infobox grid numbers - responsive.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hover-card-grid",
		'media'    => 'responsive',
		'props'    => array(
			'margin' => ( '' !== $settings->gutter_size_responsive ) ? '-' . $settings->gutter_size_responsive / 2 . 'px' : '',
		),
	)
);
// Infobox grid numbers.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hover-card-grid .tnit-hover-card-grid-item .ee",
		'props'    => array(
			'flex' => 'end',
		),
	)
);
// Infobox grid numbers.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hover-card-grid .tnit-hover-card-grid-item",
		'props'    => array(
			'flex'         => ( '' !== $settings->grid_numbers ) ? 100 / $settings->grid_numbers . '%' : '',
			'-webkit-flex' => ( '' !== $settings->grid_numbers ) ? 100 / $settings->grid_numbers . '%' : '',
			'-moz-flex'    => ( '' !== $settings->grid_numbers ) ? 100 / $settings->grid_numbers . '%' : '',
			'-ms-flex'     => ( '' !== $settings->grid_numbers ) ? 100 / $settings->grid_numbers . '%' : '',
			'max-width'    => ( '' !== $settings->grid_numbers ) ? 100 / $settings->grid_numbers . '%' : '',
			'padding'      => ( '' !== $settings->gutter_size ) ? $settings->gutter_size / 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hover-card-grid .tnit-hover-card-grid-item",
		'media'    => 'medium',
		'props'    => array(
			'flex'         => ( '' !== $settings->grid_numbers_medium ) ? 100 / $settings->grid_numbers_medium . '%' : '50%',
			'-webkit-flex' => ( '' !== $settings->grid_numbers_medium ) ? 100 / $settings->grid_numbers_medium . '%' : '50%',
			'-moz-flex'    => ( '' !== $settings->grid_numbers_medium ) ? 100 / $settings->grid_numbers_medium . '%' : '50%',
			'-ms-flex'     => ( '' !== $settings->grid_numbers_medium ) ? 100 / $settings->grid_numbers_medium . '%' : '50%',
			'max-width'    => ( '' !== $settings->grid_numbers_medium ) ? 100 / $settings->grid_numbers_medium . '%' : '50%',
			'padding'      => ( '' !== $settings->gutter_size_medium ) ? $settings->gutter_size_medium / 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hover-card-grid .tnit-hover-card-grid-item",
		'media'    => 'responsive',
		'props'    => array(
			'flex'         => ( '' !== $settings->grid_numbers_responsive ) ? 100 / $settings->grid_numbers_responsive . '%' : '100%',
			'-webkit-flex' => ( '' !== $settings->grid_numbers_responsive ) ? 100 / $settings->grid_numbers_responsive . '%' : '100%',
			'-moz-flex'    => ( '' !== $settings->grid_numbers_responsive ) ? 100 / $settings->grid_numbers_responsive . '%' : '100%',
			'-ms-flex'     => ( '' !== $settings->grid_numbers_responsive ) ? 100 / $settings->grid_numbers_responsive . '%' : '100%',
			'max-width'    => ( '' !== $settings->grid_numbers_responsive ) ? 100 / $settings->grid_numbers_responsive . '%' : '100%',
			'padding'      => ( '' !== $settings->gutter_size_responsive ) ? $settings->gutter_size_responsive / 2 . 'px' : '',
		),
	)
);
// Overall Align,eny.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hover_card_height',
		'selector'     => ".fl-node-$id .tnit-card-item",
		'prop'         => 'height',
		'unit'         => 'px',

	)
);
// Overall Align,eny.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hover_card_height',
		'selector'     => ".fl-node-$id .tnit-card-item.tnit-card-item_effect12",
		'prop'         => 'width',
		'unit'         => 'px',

	)
);
// Typography Rule For Label.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'label_typography',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-title",
	)
);
// Typography Rule for Content.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'content_typography',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-description",
	)
);
// Typography Rule for Button.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_typography',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-btn_sequare",
	)
);
// Title Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-title",
		'props'    => array(
			'color' => $settings->label_color,
		),
	)
);
// Title Hover Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item:hover .tnit-card-title",
		'props'    => array(
			'color' => $settings->label_hover_color,
		),
	)
);

// Descrpition Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-description",
		'props'    => array(
			'color' => $settings->des_color,
		),
	)
);
// Descrpition Hover Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-description:hover",
		'props'    => array(
			'color' => $settings->des_hover_color,
		),
	)
);
// Border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'card_box_border',
		'selector'     => ".fl-node-$id .tnit-card-item",
	)
);
// Hover Effects radius.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item_effect12 .tnit-card-caption::before",
		'media'    => 'default',
		'props'    => array(
			'border-top-left-radius'     => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border['radius']['top_left'] ) ? $settings->card_box_border['radius']['top_left'] . 'px' : '',
			'border-top-right-radius'    => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border['radius']['top_right'] ) ? $settings->card_box_border['radius']['top_right'] . 'px' : '',
			'border-bottom-left-radius'  => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border['radius']['bottom_left'] ) ? $settings->card_box_border['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border['radius']['bottom_right'] ) ? $settings->card_box_border['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item_effect12 .tnit-card-caption::before",
		'media'    => 'medium',
		'props'    => array(
			'border-top-left-radius'     => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border_medium['radius']['top_left'] ) ? $settings->card_box_border_medium['radius']['top_left'] . 'px' : '',
			'border-top-right-radius'    => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border_medium['radius']['top_right'] ) ? $settings->card_box_border_medium['radius']['top_right'] . 'px' : '',
			'border-bottom-left-radius'  => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border_medium['radius']['bottom_left'] ) ? $settings->card_box_border_medium['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border_medium['radius']['bottom_right'] ) ? $settings->card_box_border_medium['radius']['bottom_right'] . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item_effect12 .tnit-card-caption::before",
		'media'    => 'responsive',
		'props'    => array(
			'border-top-left-radius'     => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border_responsive['radius']['top_left'] ) ? $settings->card_box_border_responsive['radius']['top_left'] . 'px' : '',
			'border-top-right-radius'    => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border_responsive['radius']['top_right'] ) ? $settings->card_box_border_responsive['radius']['top_right'] . 'px' : '',
			'border-bottom-left-radius'  => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border_responsive['radius']['bottom_left'] ) ? $settings->card_box_border_responsive['radius']['bottom_left'] . 'px' : '',
			'border-bottom-right-radius' => ( '' !== $settings->card_box_border && '' !== $settings->card_box_border_responsive['radius']['bottom_right'] ) ? $settings->card_box_border_responsive['radius']['bottom_right'] . 'px' : '',
		),
	)
);

// Padding Rule.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hover_card_box_padding',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-caption",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'hover_card_box_padding_top',
			'padding-right'  => 'hover_card_box_padding_right',
			'padding-bottom' => 'hover_card_box_padding_bottom',
			'padding-left'   => 'hover_card_box_padding_left',
		),
	)
);
// Overall Aligment.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'outer_overall_alignment',
		'selector'     => ".fl-node-$id .tnit-card-item",
		'prop'         => 'text-align',

	)
);
// Separator Alignment.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-linebar",
		'media'    => 'default',
		'enabled'  => '' !== $settings->outer_overall_alignment,
		'props'    => array(
			'margin-left'  => ( 'left' === $settings->outer_overall_alignment ) ? '0px' : '',
			'margin-right' => ( 'right' === $settings->outer_overall_alignment ) ? '0px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-linebar",
		'media'    => 'medium',
		'enabled'  => '' !== $settings->outer_overall_alignment_medium,
		'props'    => array(
			'margin-left'  => ( 'left' === $settings->outer_overall_alignment_medium ) ? '0px' : 'auto',
			'margin-right' => ( 'right' === $settings->outer_overall_alignment_medium ) ? '0px' : 'auto',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-linebar",
		'media'    => 'responsive',
		'enabled'  => '' !== $settings->outer_overall_alignment_responsive,
		'props'    => array(
			'margin-left'  => ( 'left' === $settings->outer_overall_alignment_responsive ) ? '0px' : 'auto',
			'margin-right' => ( 'right' === $settings->outer_overall_alignment_responsive ) ? '0px' : 'auto',
		),
	)
);

// Title margin-bottom.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_margin_bottom',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-title",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);
// Descrption margin-bottom.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'des_margin_bottom',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-description",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);
// Icon Size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);
// Icon colors.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-icon",
		'props'    => array(
			'color' => $settings->icon_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-icon:hover",
		'props'    => array(
			'color' => $settings->icon_hover_color,
		),
	)
);
// Icon margins.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_margin_top',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-icon",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_margin_bottom',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-icon",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);

// Button Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-btn",
		'props'    => array(
			'color' => $settings->button_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-btn:hover",
		'props'    => array(
			'color' => $settings->button_hover_color,
		),
	)
);
// Button BG-Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-btn_sequare",
		'props'    => array(
			'background-color' => $settings->button_bg_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-card-item .tnit-card-btn_sequare:hover::before,
                    .fl-node-$id .tnit-card-item .tnit-card-btn_sequare:hover::after",
		'props'    => array(
			'background-color' => $settings->button_bg_hvr_color,
		),
	)
);
// Button Border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_border',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-btn_sequare",
	)
);
// Button Border hover.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_hvr_border',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-btn_sequare:hover",
	)
);
// Button margins.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_margin_top',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-btn",
		'prop'         => 'margin-top',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_margin_bottom',
		'selector'     => ".fl-node-$id .tnit-card-item .tnit-card-btn",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);


// Loop Start.
$hcard_item_count = count( $settings->hcard_form_items );
for ( $i = 0; $i < $hcard_item_count; $i++ ) {
	$hcard_form_item = $settings->hcard_form_items[ $i ];

	// Overall Aligment.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'overall_alignment',
			'selector'     => ".fl-node-$id .tnit-card-item.tnit-card-$i",
			'prop'         => 'text-align',

		)
	);
	// Separator Alignment.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i .tnit-card-linebar",
			'media'    => 'default',
			'enabled'  => '' !== $hcard_form_item->overall_alignment,
			'props'    => array(
				'margin-left'  => ( 'left' === $hcard_form_item->overall_alignment ) ? '0px' : 'auto',
				'margin-right' => ( 'right' === $hcard_form_item->overall_alignment ) ? '0px' : 'auto',
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i .tnit-card-linebar",
			'media'    => 'medium',
			'enabled'  => '' !== $hcard_form_item->overall_alignment_medium,
			'props'    => array(
				'margin-left'  => ( 'left' === $hcard_form_item->overall_alignment_medium ) ? '0px' : 'auto',
				'margin-right' => ( 'right' === $hcard_form_item->overall_alignment_medium ) ? '0px' : 'auto',
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i .tnit-card-linebar",
			'media'    => 'responsive',
			'enabled'  => '' !== $hcard_form_item->overall_alignment_responsive,
			'props'    => array(
				'margin-left'  => ( 'left' === $hcard_form_item->overall_alignment_responsive ) ? '0px' : 'auto',
				'margin-right' => ( 'right' === $hcard_form_item->overall_alignment_responsive ) ? '0px' : 'auto',
			),
		)
	);

	// Background Overlay Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect1 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect2 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect3 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect4 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect4 .tnit-card-caption:after,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect5 .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect6 .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect8:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect12 .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect11:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect7 .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect10:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect9:before",

			'enabled'  => 'color' === $hcard_form_item->bg_overlay_color_type,
			'props'    => array(
				'background-color' => $hcard_form_item->bg_overlay_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect5:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect6:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect8:hover:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect12:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect11:hover:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect10:hover:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect9:hover:before",

			'enabled'  => 'color' === $hcard_form_item->bg_overlay_color_type,
			'props'    => array(
				'background-color' => 'rgba(0,0,0,0)',
			),
		)
	);

	// Background Overlay Gradient.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect1 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect2 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect3 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect4 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect4 .tnit-card-caption:after,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect5 .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect6 .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect8:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect12 .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect11:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect7 .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect10:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect9:before",
			'enabled'  => 'gradient' === $hcard_form_item->bg_overlay_color_type,
			'props'    => array(
				'background' => $module->tnit_form_gradient( $hcard_form_item->bg_overlay_gradient ),
			),
		)
	);

	// Hover Overlay Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect1:hover .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect2:hover .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect3:hover .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect4:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect5 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect5 .tnit-card-caption:after,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect6 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect8:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect12 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect11:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect10 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect9 .tnit-card-caption:before",
			'enabled'  => 'color' === $hcard_form_item->overlay_color_type,
			'props'    => array(
				'background-color' => $hcard_form_item->overlay_color,
				'background-image' => 0,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect5:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect6:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect8:hover:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect12:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect11:hover:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect10:hover:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect9:hover:before",
			'enabled'  => 'color' === $hcard_form_item->overlay_color_type,
			'props'    => array(
				'background-image' => 0,
			),
		)
	);
	// Hover OverlayGradient.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect1:hover .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect2:hover .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect3:hover .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect4:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect5 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect5 .tnit-card-caption:after,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect6 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect8:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect12 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect11:hover .tnit-card-caption,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect10 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect10 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect9 .tnit-card-caption:before",
			'enabled'  => 'gradient' === $hcard_form_item->overlay_color_type,
			'props'    => array(
				'background' => $module->tnit_form_gradient( $hcard_form_item->overlay_gradient ),
			),
		)
	);

	// Seprator Border Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect11 .tnit-card-caption:before,
                        .fl-node-$id .tnit-card-$i.tnit-card-item_effect11 .tnit-card-caption:after,
                        .fl-node-$id .tnit-card-$i.tnit-card-linebar:before",
			'props'    => array(
				'border-color' => $hcard_form_item->border_color,
			),
		)
	);
	// Border Color for Style 11.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i .tnit-card-linebar:before,
                        .fl-node-$id .tnit-card-$i .tnit-card-title-effect_v1:before",
			'props'    => array(
				'background-color' => $hcard_form_item->border_color,
			),
		)
	);
	// Separator styles.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-$i .tnit-card-linebar, .fl-node-$id .tnit-card-$i .tnit-card-title-effect_v1:before",
			'props'    => array(
				'height' => ( '' !== $hcard_form_item->separator_height ) ? $hcard_form_item->separator_height . 'px' : '',
			),
		)
	);

	// Button padding.
	FLBuilderCSS::dimension_field_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_margin',
			'selector'     => ".fl-node-$id .tnit-card-$i .tnit-card-linebar, .fl-node-$id .tnit-card-$i.tnit-card-item_effect9 .tnit-card-title.tnit-card-title-effect_v1:before",
			'unit'         => 'px',
			'props'        => array(
				'margin-top'    => 'separator_margin_top',
				'margin-right'  => 'separator_margin_right',
				'margin-bottom' => 'separator_margin_bottom',
				'margin-left'   => 'separator_margin_left',
			),
		)
	);
	// Icon margin-top.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'icon_margin_top',
			'selector'     => ".fl-node-$id .tnit-card-$i .tnit-card-icon",
			'prop'         => 'margin-top',
			'unit'         => 'px',
		)
	);

	// Icon margin-bottom.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'icon_margin_bottom',
			'selector'     => ".fl-node-$id .tnit-card-$i .tnit-card-icon",
			'prop'         => 'margin-bottom',
			'unit'         => 'px',
		)
	);

	// Title Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-title",
			'props'    => array(
				'color' => $hcard_form_item->hover_card_title_color,
			),
		)
	);
	// Title Hover Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i:hover .tnit-card-title",
			'props'    => array(
				'color' => $hcard_form_item->hover_card_title_color_h,
			),
		)
	);
	// Descrpition Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-description",
			'props'    => array(
				'color' => $hcard_form_item->hover_card_description_color,
			),
		)
	);
	// Descrpition Hover Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-description:hover",
			'props'    => array(
				'color' => $hcard_form_item->hover_card_description_color_h,
			),
		)
	);

	// Icon Tab Setting Start.
	// Icon Font Size.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'card_icon_size',
			'selector'     => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-icon",
			'prop'         => 'font-size',
			'unit'         => 'px',
		)
	);
	// Icon Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-icon",
			'props'    => array(
				'color' => $hcard_form_item->card_icon_color,
			),
		)
	);
	// Icon Hover Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i:hover .tnit-card-icon",
			'props'    => array(
				'color' => $hcard_form_item->card_icon_hover_color,
			),
		)
	);
	// CTA Button Colors.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn",
			'enabled'  => 'button' === $hcard_form_item->link_type,
			'props'    => array(
				'color'            => $hcard_form_item->cta_color,
				'background-color' => $hcard_form_item->cta_bg_color,
			),
		)
	);
	// Button Background Hover Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn_sequare:hover:before, .tnit-card-item.tnit-card-$i .tnit-card-btn_sequare:hover:after",
			'enabled'  => 'button' === $hcard_form_item->link_type,
			'props'    => array(
				'background-color' => $hcard_form_item->cta_bg_hvr_color,
			),
		)
	);
	// Button Hover Color.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn_sequare:hover",
			'enabled'  => 'button' === $hcard_form_item->link_type,
			'props'    => array(
				'color' => $hcard_form_item->cta_hvr_color,
			),
		)
	);
	// CTA Button border.
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'cta_border',
			'selector'     => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn_sequare",
		)
	);
	// CTA Button border hover.
	FLBuilderCSS::border_field_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'button_hvr_border',
			'selector'     => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn_sequare:hover",
		)
	);

	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn_sequare",
			'enabled'  => 'full' === $hcard_form_item->cta_width && 'button' === $hcard_form_item->link_type,
			'props'    => array(
				'width'           => '100%',
				'justify-content' => 'center',
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn_sequare",
			'enabled'  => 'custom' === $hcard_form_item->cta_width && 'button' === $hcard_form_item->link_type,
			'props'    => array(
				'width'           => ( '' !== $hcard_form_item->cta_custom_width ) ? $hcard_form_item->cta_custom_width . 'px' : '',
				'justify-content' => 'center',
			),
		)
	);
	if ( 'button' === $hcard_form_item->link_type ) {
		// Button padding.
		FLBuilderCSS::dimension_field_rule(
			array(
				'settings'     => $hcard_form_item,
				'setting_name' => 'cta_padding',
				'selector'     => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn_sequare",
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
	// CTA Link Icon Colors.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn.tnit-card-btn_arrow",
			'enabled'  => 'icon' === $hcard_form_item->link_type,
			'props'    => array(
				'color'     => $hcard_form_item->cta_color,
				'font-size' => ( '' !== $hcard_form_item->cta_icon_size ) ? $hcard_form_item->cta_icon_size . 'px' : '',
			),
		)
	);
	// CTA Link Icon Colors.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-card-item.tnit-card-$i .tnit-card-btn.tnit-card-btn_arrow:hover",
			'enabled'  => 'icon' === $hcard_form_item->link_type,
			'props'    => array(
				'color' => $hcard_form_item->cta_hvr_color,
			),
		)
	);

	// Separator CSS for style 11.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_width',
			'selector'     => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect10 .tnit-card-linebar",
			'prop'         => 'width',
		)
	);
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_thickness',
			'selector'     => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect10 .tnit-card-linebar",
			'prop'         => 'height',
			'unit'         => 'px',
		)
	);
	// Separator CSS for style 12.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_thickness',
			'selector'     => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect9 .tnit-card-title-effect_v1:before",
			'prop'         => 'width',
			'unit'         => 'px',

		)
	);
	// Separator CSS for style 12.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_height',
			'selector'     => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect9 .tnit-card-title-effect_v1:before",
			'prop'         => 'height',
			'unit'         => 'px',

		)
	);

	// Bottom-Left separator effect.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_thickness',
			'selector'     => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect11 .tnit-card-caption:before",
			'prop'         => 'border-left-width',
			'unit'         => 'px',
		)
	);
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_thickness',
			'selector'     => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect11 .tnit-card-caption:before",
			'prop'         => 'border-bottom-width',
			'unit'         => 'px',
		)
	);
	// Top-Right separator effect.
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_thickness',
			'selector'     => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect11 .tnit-card-caption::after",
			'prop'         => 'border-top-width',
			'unit'         => 'px',
		)
	);
	FLBuilderCSS::responsive_rule(
		array(
			'settings'     => $hcard_form_item,
			'setting_name' => 'separator_thickness',
			'selector'     => ".fl-node-$id .tnit-card-$i.tnit-card-item_effect11 .tnit-card-caption::after",
			'prop'         => 'border-right-width',
			'unit'         => 'px',
		)
	);
}
