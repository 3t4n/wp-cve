<?php
/**
 * Xpro front-end CSS php file
 *
 * @package Xpro Addons
 * @sub-package Social Icons Module
 *
 * @since 1.1.3
 */

// Background Size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'size',
		'selector'     => ".fl-node-$id .tnit-social-icon-circle .tnit-social-icon-icon,
							.fl-node-$id .tnit-social-icon-square .tnit-social-icon-icon",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'size',
		'selector'     => ".fl-node-$id .tnit-social-icon-circle .tnit-social-icon-icon,
							.fl-node-$id .tnit-social-icon-square .tnit-social-icon-icon",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_bg_size',
		'selector'     => ".fl-node-$id .tnit-social-icon-custom .tnit-social-icon-icon",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_bg_size',
		'selector'     => ".fl-node-$id .tnit-social-icon-custom .tnit-social-icon-icon",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);
// size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'size',
		'selector'     => ".fl-node-$id .tnit-social-icon-simple .tnit-social-icon-icon,
							.fl-node-$id .tnit-social-icon-custom .tnit-social-icon-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-circle .tnit-social-icon-icon,
						.fl-node-$id .tnit-social-icon-square .tnit-social-icon-icon",
		'media'    => 'default',
		'props'    => array(
			'font-size'        => ( '' !== $settings->size ) ? $settings->size / 2 . 'px' : '',
			'background-color' => $settings->icon_bg_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-circle .tnit-social-icon-icon,
						.fl-node-$id .tnit-social-icon-square .tnit-social-icon-icon",
		'media'    => 'medium',
		'props'    => array(
			'font-size' => ( '' !== $settings->size_medium ) ? $settings->size_medium / 2 . 'px' : '',
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-circle .tnit-social-icon-icon,
						.fl-node-$id .tnit-social-icon-square .tnit-social-icon-icon",
		'media'    => 'responsive',
		'props'    => array(
			'font-size' => ( '' !== $settings->size_responsive ) ? $settings->size_responsive / 2 . 'px' : '',
		),
	)
);
// Spacing.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'spacing',
		'selector'     => ".fl-node-$id .tnit-social-icon-horizontal .tnit-social-icon-link-wrap",
		'prop'         => 'margin-right',
		'unit'         => 'px',
	)
);
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'spacing',
		'selector'     => ".fl-node-$id .tnit-social-icon-vertical .tnit-social-icon-link-wrap",
		'prop'         => 'margin-bottom',
		'unit'         => 'px',
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-horizontal",
		'media'    => 'default',
		'props'    => array(
			'justify-content' => ( empty( $settings->align ) ) ? '' :
								( ( 'left' === $settings->align ) ? 'flex-start' :
								( ( 'right' === $settings->align ) ? 'flex-end' : $settings->align ) ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-horizontal",
		'media'    => 'medium',
		'props'    => array(
			'justify-content' => ( empty( $settings->align_medium ) ) ? '' :
								( ( 'left' === $settings->align_medium ) ? 'flex-start' :
								( ( 'right' === $settings->align_medium ) ? 'flex-end' : $settings->align_medium ) ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-horizontal",
		'media'    => 'responsive',
		'props'    => array(
			'justify-content' => ( empty( $settings->align_responsive ) ) ? '' :
								( ( 'left' === $settings->align_responsive ) ? 'flex-start' :
								( ( 'right' === $settings->align_responsive ) ? 'flex-end' : $settings->align_responsive ) ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-vertical",
		'media'    => 'default',
		'props'    => array(
			'align-items' => ( empty( $settings->align ) ) ? '' :
								( ( 'left' === $settings->align ) ? 'flex-start' :
								( ( 'right' === $settings->align ) ? 'flex-end' : $settings->align ) ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-vertical",
		'media'    => 'medium',
		'props'    => array(
			'align-items' => ( empty( $settings->align_medium ) ) ? '' :
								( ( 'left' === $settings->align_medium ) ? 'flex-start' :
								( ( 'right' === $settings->align_medium ) ? 'flex-end' : $settings->align_medium ) ),
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-vertical",
		'media'    => 'responsive',
		'props'    => array(
			'align-items' => ( empty( $settings->align_responsive ) ) ? '' :
								( ( 'left' === $settings->align_responsive ) ? 'flex-start' :
								( ( 'right' === $settings->align_responsive ) ? 'flex-end' : $settings->align_responsive ) ),
		),
	)
);
// Border.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_border',
		'selector'     => ".fl-node-$id .tnit-social-icon-custom .tnit-social-icon-icon,
							.fl-node-$id .tnit-social-icon-custom .tnit-social-icon-image .tnit-photo",
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-custom .tnit-social-icon-icon:hover,
						.fl-node-$id .tnit-social-icon-custom .tnit-social-icon-image:hover .tnit-photo",
		'props'    => array(
			'border-color' => $settings->icon_border_hvr_color,
		),
	)
);
// Colors.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-icon",
		'props'    => array(
			'color' => $settings->icon_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-icon:hover",
		'props'    => array(
			'color' => $settings->icon_hvr_color,
		),
	)
);
// Background Colors.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-circle .tnit-social-icon-icon,
						.fl-node-$id .tnit-social-icon-square .tnit-social-icon-icon,
						.fl-node-$id .tnit-social-icon-custom .tnit-social-icon-icon",
		'props'    => array(
			'background-color' => $settings->icon_bg_color,
		),
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-circle .tnit-social-icon-icon:hover,
						.fl-node-$id .tnit-social-icon-square .tnit-social-icon-icon:hover,
						.fl-node-$id .tnit-social-icon-custom .tnit-social-icon-icon:hover",
		'props'    => array(
			'background-color' => $settings->icon_bg_hvr_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'size',
		'selector'     => ".fl-node-$id .tnit-social-icon-simple .tnit-social-icon-image",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-social-icon-circle .tnit-social-icon-image,
						.fl-node-$id .tnit-social-icon-square .tnit-social-icon-image",
		'props'    => array(
			'width'  => ( '' !== $settings->size ) ? $settings->size . 'px' : '',
			'height' => ( '' !== $settings->size ) ? $settings->size . 'px' : '',
		),
	)
);

$social_icons_count = count( $settings->social_icons );
for ( $i = 0; $i < $social_icons_count; $i++ ) {

	$social_icon = $settings->social_icons[ $i ];

	// Colors.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-social-icon-$i .tnit-social-icon-icon",
			'props'    => array(
				'color' => $social_icon->icon_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-social-icon-$i .tnit-social-icon-icon:hover",
			'props'    => array(
				'color' => $social_icon->icon_hvr_color,
			),
		)
	);
	// Background Colors.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-social-icon-$i .tnit-social-icon-circle .tnit-social-icon-icon,
							.fl-node-$id .tnit-social-icon-$i .tnit-social-icon-square .tnit-social-icon-icon,
							.fl-node-$id .tnit-social-icon-$i .tnit-social-icon-custom .tnit-social-icon-icon",
			'props'    => array(
				'background-color' => $social_icon->bg_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-social-icon-$i .tnit-social-icon-circle .tnit-social-icon-icon:hover,
							.fl-node-$id .tnit-social-icon-$i .tnit-social-icon-square .tnit-social-icon-icon:hover,
							.fl-node-$id .tnit-social-icon-$i .tnit-social-icon-custom .tnit-social-icon-icon:hover",
			'props'    => array(
				'background-color' => $social_icon->bg_hvr_color,
			),
		)
	);
	// Background Colors.
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-social-icon-$i .tnit-social-icon-custom .tnit-social-icon-icon",
			'props'    => array(
				'border-color' => $social_icon->border_color,
			),
		)
	);
	FLBuilderCSS::rule(
		array(
			'selector' => ".fl-node-$id .tnit-social-icon-$i .tnit-social-icon-custom .tnit-social-icon-icon:hover",
			'props'    => array(
				'border-color' => $social_icon->border_hvr_color,
			),
		)
	);

}
