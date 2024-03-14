<?php

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'alignment',
		'selector'     => ".fl-node-$id .xpro-testimonial-wrapper",
		'prop'         => 'text-align',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-wrapper",
		'media'    => 'default',
		'props'    => array(
			'grid-template-columns' => 'repeat(' . $settings->columns . ', auto)',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-wrapper",
		'media'    => 'medium',
		'props'    => array(
			'grid-template-columns' => 'repeat(' . $settings->columns_medium . ', auto)',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-wrapper",
		'media'    => 'responsive',
		'props'    => array(
			'grid-template-columns' => 'repeat(' . $settings->columns_responsive . ', auto)',
		),
	)
);


// Styling Tab.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'space_between',
		'unit'         => 'px',
		'selector'     => ".fl-node-$id .xpro-testimonial-wrapper",
		'prop'         => 'grid-column-gap',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'space_between',
		'unit'         => 'px',
		'selector'     => ".fl-node-$id .xpro-testimonial-wrapper",
		'prop'         => 'grid-row-gap',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-layout-1 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-2 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-3 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-7 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-9 .xpro-testimonial-item, .fl-node-$id .xpro-testimonial-layout-10 .xpro-testimonial-item",
		'enabled'  => 'color' === $settings->background_type,
		'props'    => array(
			'background-color' => $settings->bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,.fl-node-$id .xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,.fl-node-$id .xpro-testimonial-layout-6 .xpro-testimonial-content,.fl-node-$id .xpro-testimonial-layout-8 .xpro-testimonial-content",
		'enabled'  => 'color' === $settings->background_type,
		'props'    => array(
			'background-color' => $settings->bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-layout-1 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-2 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-3 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-7 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-9 .xpro-testimonial-item, .fl-node-$id .xpro-testimonial-layout-10 .xpro-testimonial-item",
		'enabled'  => 'gradient' === $settings->background_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->bg_gradient ),
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,.fl-node-$id .xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,.fl-node-$id .xpro-testimonial-layout-6 .xpro-testimonial-content,.fl-node-$id .xpro-testimonial-layout-8 .xpro-testimonial-content",
		'enabled'  => 'gradient' === $settings->background_type,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->bg_gradient ),
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'border',
		'selector'     => ".fl-node-$id .xpro-testimonial-layout-1 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-2 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-3 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-7 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-9 .xpro-testimonial-item, .fl-node-$id .xpro-testimonial-layout-10 .xpro-testimonial-item",
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'border',
		'selector'     => ".fl-node-$id .xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,.fl-node-$id .xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,.fl-node-$id .xpro-testimonial-layout-6 .xpro-testimonial-content,.fl-node-$id .xpro-testimonial-layout-8 .xpro-testimonial-content",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'padding',
		'selector'     => ".fl-node-$id .xpro-testimonial-layout-1 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-2 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-3 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-7 .xpro-testimonial-item,.fl-node-$id .xpro-testimonial-layout-9 .xpro-testimonial-item, .fl-node-$id .xpro-testimonial-layout-10 .xpro-testimonial-item",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'padding_top',
			'padding-right'  => 'padding_right',
			'padding-bottom' => 'padding_bottom',
			'padding-left'   => 'padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'padding',
		'selector'     => ".fl-node-$id .xpro-testimonial-layout-4 .xpro-testimonial-inner-wrapper,.fl-node-$id .xpro-testimonial-layout-5 .xpro-testimonial-inner-wrapper,.fl-node-$id .xpro-testimonial-layout-6 .xpro-testimonial-content,.fl-node-$id .xpro-testimonial-layout-8 .xpro-testimonial-content",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'padding_top',
			'padding-right'  => 'padding_right',
			'padding-bottom' => 'padding_bottom',
			'padding-left'   => 'padding_left',
		),
	)
);

// Image Styling.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_width',
		'unit'         => 'px',
		'selector'     => ".fl-node-$id .xpro-testimonial-image > img",
		'prop'         => 'width',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_height',
		'unit'         => 'px',
		'selector'     => ".fl-node-$id .xpro-testimonial-image > img",
		'prop'         => 'height',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-image > img",
		'props'    => array(
			'object-fit' => $settings->object_fit,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-image > img",
		'props'    => array(
			'box-shadow' => FLBuilderColor::shadow( $settings->image_shadow ),
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_border',
		'selector'     => ".fl-node-$id .xpro-testimonial-image > img",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'image_margin',
		'selector'     => ".fl-node-$id .xpro-testimonial-image",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'image_margin_top',
			'margin-right'  => 'image_margin_right',
			'margin-bottom' => 'image_margin_bottom',
			'margin-left'   => 'image_margin_left',
		),
	)
);

// Author.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-title",
		'props'    => array(
			'color' => $settings->author_color,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'author_typography',
		'selector'     => ".fl-node-$id .xpro-testimonial-title",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'author_margin',
		'selector'     => ".fl-node-$id .xpro-testimonial-title",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'author_margin_top',
			'margin-right'  => 'author_margin_right',
			'margin-bottom' => 'author_margin_bottom',
			'margin-left'   => 'author_margin_left',
		),
	)
);

// Designation.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-designation",
		'props'    => array(
			'color' => $settings->designation_color,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'designation_typography',
		'selector'     => ".fl-node-$id .xpro-testimonial-designation",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'designation_margin',
		'selector'     => ".fl-node-$id .xpro-testimonial-designation",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'designation_margin_top',
			'margin-right'  => 'designation_margin_right',
			'margin-bottom' => 'designation_margin_bottom',
			'margin-left'   => 'designation_margin_left',
		),
	)
);

// Description.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-description",
		'props'    => array(
			'color' => $settings->description_color,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'description_typography',
		'selector'     => ".fl-node-$id .xpro-testimonial-description",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'description_margin',
		'selector'     => ".fl-node-$id .xpro-testimonial-description",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'description_margin_top',
			'margin-right'  => 'description_margin_right',
			'margin-bottom' => 'description_margin_bottom',
			'margin-left'   => 'description_margin_left',
		),
	)
);

// Rating.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'rating_size',
		'unit'         => 'px',
		'enabled'      => 'star' === $settings->rating_style,
		'selector'     => ".fl-node-$id .xpro-testimonial-rating",
		'prop'         => 'font-size',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-rating,.fl-node-$id .xpro-rating-layout-star > i",
		'props'    => array(
			'color' => $settings->rating_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-rating-layout-star > .xpro-rating-filled",
		'enabled'  => 'star' === $settings->rating_style,
		'props'    => array(
			'color' => $settings->rating_filled,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-rating-layout-num",
		'props'    => array(
			'background-color' => $settings->rating_filled,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'rating_typography',
		'enabled'      => 'num' === $settings->rating_style,
		'selector'     => ".fl-node-$id .xpro-rating-layout-num",
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'rating_border',
		'enabled'      => 'num' === $settings->rating_style,
		'selector'     => ".fl-node-$id .xpro-rating-layout-num",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'rating_padding',
		'enabled'      => 'num' === $settings->rating_style,
		'selector'     => ".fl-node-$id .xpro-rating-layout-num",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'rating_padding_top',
			'padding-right'  => 'rating_padding_right',
			'padding-bottom' => 'rating_padding_bottom',
			'padding-left'   => 'rating_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'rating_margin',
		'selector'     => ".fl-node-$id .xpro-testimonial-rating",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'rating_margin_top',
			'margin-right'  => 'rating_margin_right',
			'margin-bottom' => 'rating_margin_bottom',
			'margin-left'   => 'rating_margin_left',
		),
	)
);

// Quote.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'rating_size',
		'unit'         => 'px',
		'enabled'      => 'star' === $settings->rating_style,
		'selector'     => ".fl-node-$id .xpro-testimonial-quote",
		'prop'         => 'font-size',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-testimonial-quote",
		'props'    => array(
			'color' => $settings->quote_color,
		),
	)
);

// Code To Add Font Size for Quote
FLBuilderCSS::rule(
    array(
        'selector' => ".fl-node-$id .xpro-testimonial-quote",
        'props'    => array(
            'font-size'  => $settings->quote_size . 'px',
        ),
    )
);


FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'quote_margin',
		'selector'     => ".fl-node-$id .xpro-testimonial-quote",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'quote_margin_top',
			'margin-right'  => 'quote_margin_right',
			'margin-bottom' => 'quote_margin_bottom',
			'margin-left'   => 'quote_margin_left',
		),
	)
);
