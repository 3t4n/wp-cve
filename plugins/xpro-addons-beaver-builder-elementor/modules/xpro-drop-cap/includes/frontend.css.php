<?php
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dropcap_typography',
		'selector'     => ".fl-node-$id .xpro-dropcap-wrapper > p",
		'unit'         => 'px',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-dropcap-wrapper > p",
		'props'    => array(
			'color' => $settings->dropcap_color,
		),
	)
);

/* Style > DropCap */
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dropcap_letter_typography',
		'selector'     => ".fl-node-$id .xpro-dropcap-wrapper > p:first-of-type::first-letter",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-dropcap-wrapper > p:first-of-type::first-letter",
		'props'    => array(
			'color' => $settings->dropcap_letter_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .xpro-dropcap-wrapper > p:first-of-type::first-letter",
		'props'    => array(
			'background-color' => $settings->dropcap_letter_bg_color,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dropcap_letter_border',
		'selector'     => ".fl-node-$id .xpro-dropcap-wrapper > p:first-of-type::first-letter",
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dropcap_letter_padding',
		'selector'     => ".fl-node-$id .xpro-dropcap-wrapper > p:first-of-type::first-letter",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'dropcap_letter_padding_top',
			'padding-right'  => 'dropcap_letter_padding_right',
			'padding-bottom' => 'dropcap_letter_padding_bottom',
			'padding-left'   => 'dropcap_letter_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'grid_padding',
		'selector'     => ".fl-node-$id .xpro-dropcap-wrapper > p:first-of-type::first-letter",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'dropcap_letter_margin_top',
			'margin-right'  => 'dropcap_letter_margin_right',
			'margin-bottom' => 'dropcap_letter_margin_bottom',
			'margin-left'   => 'dropcap_letter_margin_left',
		),
	)
);
