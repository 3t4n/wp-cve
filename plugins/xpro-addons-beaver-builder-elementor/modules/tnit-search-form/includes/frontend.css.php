<?php
/**
 * This file should contain frontend styles that
 * will be applied to individual module instances.
 *
 * You have access to three variables in this file:
 *
 * $module An instance of your module class.
 * $id The module's ID.
 * $settings The module's settings.
 */

//Form Height
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-outer-border, .fl-node-$id .tnit-form-search .input-field, .fl-node-$id .tnit-space-increase .btn-submit, .fl-node-$id .tnit-search-box #tnit-trigger-btn, .fl-node-$id .tnit-search-animated-form, .fl-node-$id .tnit-search-animated-form .input-field, .fl-node-$id .tnit-search-animated-form .btn-submit",
		'props'    => array(
			'height' => $settings->form_height . 'px',
		),
	)
);

//Input Field Background Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-search .input-field, .fl-node-$id .tnit-search-animated-form, .fl-node-$id .tnit-search-animated-form .input-field",
		'props'    => array(
			'color'            => $settings->input_text_color,
			'background-color' => $settings->input_bg_color,
		),
	)
);

//Input Field Focus Background Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-search .input-field:focus, .fl-node-$id .tnit-search-animated-form:focus",
		'props'    => array(
			'color'            => $settings->input_text_focus_color,
			'background-color' => $settings->input_bg_focus_color,

		),
	)
);

//Input Field Focus Background Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-search .input-field:focus",
		'props'    => array(
			'color'            => $settings->input_text_focus_color,
			'background-color' => $settings->input_bg_focus_color,

		),
	)
);
//Input Field Focus Background Color Styel 2
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-search-animated-form .input-field:focus",
		'props'    => array(
			'color' => $settings->input_text_focus_color,
		),
	)
);

//Placeholder Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-search .input-field::placeholder, .fl-node-$id .tnit-search-animated-form .input-field::placeholder",
		'props'    => array(
			'color' => $settings->ph_color,
		),
	)
);

//Inpurt Border
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'input_border',
		'selector'     => ".fl-node-$id .tnit-outer-border",
	)
);

//Toggle Button Border
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'tog_btn_border',
		'selector'     => ".fl-node-$id #trigger-tnit-search",
	)
);

//Button Background Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-searchbar-outer .btn-submit, .fl-node-$id .tnit-search-box #tnit-trigger-btn, .fl-node-$id .tnit-search-animated-form .btn-submit",
		'props'    => array(
			'background-color' => $settings->btn_bg_color,

		),
	)
);
//Toogle Button Background Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id #trigger-tnit-search",
		'props'    => array(
			'background-color' => $settings->tog_btn_bg_color,
			'color'            => $settings->tog_btn_icon_color,

		),
	)
);

//Button Background Hover Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-searchbar-outer .btn-submit:hover, .fl-node-$id .tnit-search-box #tnit-trigger-btn:hover, .fl-node-$id .tnit-search-animated-form .btn-submit:hover",
		'props'    => array(
			'background-color' => $settings->btn_bg_hover_color,
		),
	)
);

//Toogle Button Background Hover Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id #trigger-tnit-search:hover",
		'props'    => array(
			'background-color' => $settings->tog_btn_bg_hover_color,
			'color'            => $settings->tog_btn_icon_hover_color,
		),
	)
);

//Button Icon/Text Color Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-search .btn-submit, .fl-node-$id .tnit-search-animated-form .input-field, .fl-node-$id .tnit-search-box #tnit-trigger-btn, .fl-node-$id .tnit-search-animated-form .btn-submit",
		'props'    => array(
			'color' => $settings->btn_icon_color,
		),
	)
);

//Button Icon/Text Color Hover Color
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-search .btn-submit:hover, .fl-node-$id .tnit-search-box #tnit-trigger-btn:hover, .fl-node-$id .tnit-search-animated-form .btn-submit:hover",
		'props'    => array(
			'color' => $settings->btn_icon_hover_color,
		),
	)
);

//Button Icon Font Size
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'icon_size',
		'selector'     => ".fl-node-$id .tnit-searchbar-outer .btn-submit i, .fl-node-$id .tnit-search-box #tnit-trigger-btn i, .fl-node-$id .tnit-search-animated-form .btn-submit i",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

//Toggle Button Icon Size
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'toggle_icon_size',
		'selector'     => ".fl-node-$id #trigger-tnit-search",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);
//Toogle Box Size Rule
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_size',
		'selector'     => ".fl-node-$id #trigger-tnit-search",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_size',
		'selector'     => ".fl-node-$id #trigger-tnit-search",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);

//Input Typography Rule
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'input_typography',
		'selector'     => ".fl-node-$id .tnit-form-search .input-field, .fl-node-$id .tnit-search-animated-form .input-field",
	)
);
//Button Typography Rule
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_typo',
		'selector'     => ".fl-node-$id .tnit-form-search .btn-submit,.fl-node-$id .tnit-search-box #tnit-trigger-btn,.fl-node-$id .tnit-search-animated-form .input-field",
	)
);
