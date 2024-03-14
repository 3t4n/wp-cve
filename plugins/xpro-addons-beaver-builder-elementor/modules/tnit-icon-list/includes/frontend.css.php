<?php
/**
 * Xpro front-end CSS php file
 *
 * @package Xpro Addons
 * @sub-package Creative Icon List Module
 *
 * @since 1.1.3
 */

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'list_item_space',
		'selector'     => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_title_space',
		'selector'     => ".fl-node-$id .tnit-icon-list .tnit-image-icon-wrap",
		'prop'         => 'margin-right',
		'unit'         => 'px',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => ".fl-node-$id .tnit-icon-list .tnit-icon-list-title",
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-title,
						.fl-node-$id .tnit-icon-list .tnit-icon-list-title a",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-title:hover,
						.fl-node-$id .tnit-icon-list .tnit-icon-list-title:hover a",
		'props'    => array(
			'color' => $settings->title_hvr_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .tnit-icon-list .tnit-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon",
		'props'    => array(
			'color' => $settings->icon_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon:hover",
		'props'    => array(
			'color' => $settings->icon_hvr_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-circle,
						.fl-node-$id .tnit-icon-list .tnit-icon-square",
		'media'    => 'default',
		'props'    => array(
			'width'            => ( '' !== $settings->icon_size ) ? $settings->icon_size * 2 . 'px' : '',
			'height'           => ( '' !== $settings->icon_size ) ? $settings->icon_size * 2 . 'px' : '',
			'background-color' => $settings->icon_bg_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-circle,
						.fl-node-$id .tnit-icon-list .tnit-icon-square",
		'media'    => 'medium',
		'props'    => array(
			'width'  => ( '' !== $settings->icon_size_medium ) ? $settings->icon_size_medium * 2 . 'px' : '',
			'height' => ( '' !== $settings->icon_size_medium ) ? $settings->icon_size_medium * 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-circle,
						.fl-node-$id .tnit-icon-list .tnit-icon-square",
		'media'    => 'responsive',
		'props'    => array(
			'width'  => ( '' !== $settings->icon_size_responsive ) ? $settings->icon_size_responsive * 2 . 'px' : '',
			'height' => ( '' !== $settings->icon_size_responsive ) ? $settings->icon_size_responsive * 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-circle:hover,
						.fl-node-$id .tnit-icon-list .tnit-icon-square:hover",
		'props'    => array(
			'background-color' => $settings->icon_bg_hvr_color,
		),
	)
);
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_border',
		'selector'     => ".fl-node-$id .tnit-icon-list .tnit-icon-custom",
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_bg_size',
		'selector'     => ".fl-node-$id .tnit-icon-list .tnit-icon-custom",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_bg_size',
		'selector'     => ".fl-node-$id .tnit-icon-list .tnit-icon-custom",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-custom",
		'props'    => array(
			'background-color' => $settings->icon_bg_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-custom:hover",
		'props'    => array(
			'background-color' => $settings->icon_bg_hvr_color,
			'border-color'     => $settings->icon_border_hvr_color,
		),
	)
);

$icon_list_count = count( $settings->list_items );
for ( $i = 0; $i < $icon_list_count; $i++ ) {

	$list_item = $settings->list_items[ $i ];

	/* Title Colors */
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-list-title",
			'props'    => array(
				'color' => $list_item->title_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-list-title:hover",
			'props'    => array(
				'color' => $list_item->title_hvr_color,
			),
		)
	);

	/* Icon/Image Colors */
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon",
			'props'    => array(
				'color' => $list_item->icon_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon:hover",
			'props'    => array(
				'color' => $list_item->icon_hvr_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-circle,
							.fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-square,
							.fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-custom",
			'props'    => array(
				'background-color' => $list_item->icon_bg_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-circle:hover,
							.fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-square:hover,
							.fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-custom:hover",
			'props'    => array(
				'background-color' => $list_item->icon_bg_hvr_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-custom",
			'props'    => array(
				'border-color' => $list_item->icon_border_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-icon-list .tnit-icon-list-item-$i .tnit-icon-custom:hover",
			'props'    => array(
				'border-color' => $list_item->icon_border_hvr_color,
			),
		)
	);

}
