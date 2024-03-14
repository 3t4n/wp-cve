<?php
/**
 * TNIT Advanced Menu front-end CSS php file
 *
 * @package TNIT Advanced Menu
 * @since 1.1.3
 */

$hambruger_show_on = ( 'medium' === $settings->responsive_breakpoint ) ? $global_settings->medium_breakpoint . 'px' : ( ( 'responsive' === $settings->responsive_breakpoint ) ? $global_settings->responsive_breakpoint . 'px' : '' );


//Menu Styling
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-wrapper",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'  => 'all' !== $settings->responsive_breakpoint,
		'props'    => array(
			'text-align' => $settings->menu_wrapper_align,
		),
	)
);

//Menu Styling
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-container",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'  => 'accordion' !== $settings->menu_layout && 'all' !== $settings->responsive_breakpoint,
		'props'    => array(
			'text-align' => $settings->menu_align,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-vertical-menu .tnit-advance-menu-dropdown-toggle",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'  => 'horizontal' !== $settings->menu_layout,
		'props'    => array(
			'float'       => ( 'right' === $settings->menu_align ) ? 'left' : ( ( 'center' === $settings->menu_align ) ? 'none' : 'right' ),
			'margin-left' => ( 'center' === $settings->menu_align ) ? '10px' : '',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'acc_menu_align',
		'enabled'      => 'accordion' === $settings->menu_layout,
		'selector'     => ".fl-node-$id .tnit-advance-menu-container",
		'prop'         => 'text-align',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-accordion-menu .tnit-advance-menu-dropdown-toggle",
		'enabled'  => 'accordion' === $settings->menu_layout,
		'props'    => array(
			'float'       => ( 'right' === $settings->acc_menu_align ) ? 'left' : ( ( 'center' === $settings->acc_menu_align ) ? 'none' : 'right' ),
			'margin-left' => ( 'center' === $settings->acc_menu_align ) ? '10px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-accordion-menu .tnit-advance-menu-dropdown-toggle",
		'media'    => 'medium',
		'enabled'  => 'accordion' === $settings->menu_layout,
		'props'    => array(
			'float'       => ( 'right' === $settings->acc_menu_align_medium ) ? 'left' : ( ( 'center' === $settings->acc_menu_align_medium ) ? 'none' : 'right' ),
			'margin-left' => ( 'center' === $settings->acc_menu_align_medium ) ? '10px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-accordion-menu .tnit-advance-menu-dropdown-toggle",
		'media'    => 'responsive',
		'enabled'  => 'accordion' === $settings->menu_layout,
		'props'    => array(
			'float'       => ( 'right' === $settings->acc_menu_align_responsive ) ? 'left' : ( ( 'center' === $settings->acc_menu_align_responsive ) ? 'none' : 'right' ),
			'margin-left' => ( 'center' === $settings->acc_menu_align_responsive ) ? '10px' : '',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_wrapper_width',
		'media'        => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'      => 'vertical' === $settings->menu_layout && 'all' !== $settings->responsive_breakpoint,
		'selector'     => ".fl-node-$id .tnit-advance-vertical-menu",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-vertical-menu .tnit-advance-sub-menu",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'  => 'vertical' === $settings->menu_layout && 'all' !== $settings->responsive_breakpoint,
		'props'    => array(
			'right' => ( 'right' === $settings->menu_align ) ? '100%' : '',
			'left'  => ( 'right' === $settings->menu_align ) ? 'auto' : '',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_padding',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'menu_padding_top',
			'padding-right'  => 'menu_padding_right',
			'padding-bottom' => 'menu_padding_bottom',
			'padding-left'   => 'menu_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_margin',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'menu_margin_top',
			'margin-right'  => 'menu_margin_right',
			'margin-bottom' => 'menu_margin_bottom',
			'margin-left'   => 'menu_margin_left',
		),
	)
);

//Menu Colors
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span",
		'props'    => array(
			'color' => $settings->menu_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li:hover > span",
		'props'    => array(
			'color' => $settings->menu_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span",
		'props'    => array(
			'background-color' => $settings->menu_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li:hover > span",
		'props'    => array(
			'background-color' => $settings->menu_hbg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-horizontal-menu-style-2 > ul > li > span:before,
						.fl-node-$id .tnit-advance-horizontal-menu-style-2 > ul > li > span:after,
						.fl-node-$id .tnit-advance-horizontal-menu-style-3 > ul > li > span:before,
						.fl-node-$id .tnit-advance-horizontal-menu-style-3 > ul > li > span:after,
						.fl-node-$id .tnit-advance-horizontal-menu-style-4 > ul > li > span:before,
						.fl-node-$id .tnit-advance-horizontal-menu-style-5 > ul > li > span:before",
		'props'    => array(
			'background-color' => $settings->menu_line_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_icon_image_size',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li .tnit-menu-item-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_icon_image_size',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li .tnit-menu-item-icon > img",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li .tnit-menu-item-icon",
		'props'    => array(
			'color' => $settings->menu_icon_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li:hover .tnit-menu-item-icon",
		'props'    => array(
			'color' => $settings->menu_icon_hvr_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_icon_image_spacing',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li .tnit-menu-item-icon-before",
		'prop'         => 'margin-right',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_icon_image_spacing',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li .tnit-menu-item-icon-after",
		'prop'         => 'margin-left',
		'unit'         => 'px',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_typography',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span",
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'menu_border',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li > span",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list > li:hover > span",
		'props'    => array(
			'border-color' => $settings->menu_border_hcolor,
		),
	)
);

//Sub Menu Outer
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-horizontal-menu .tnit-advance-sub-menu-bg-color,.fl-node-$id .tnit-advance-vertical-menu .tnit-advance-sub-menu-bg-color",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'  => 'accordion' !== $settings->menu_layout && 'all' !== $settings->responsive_breakpoint,
		'props'    => array(
			'background-color' => $settings->submenu_outer_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-horizontal-menu .tnit-advance-sub-menu-bg-gradient,.fl-node-$id .tnit-advance-vertical-menu .tnit-advance-sub-menu-bg-gradient",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'  => 'accordion' !== $settings->menu_layout && 'all' !== $settings->responsive_breakpoint,
		'props'    => array(
			'background-image' => FLBuilderColor::gradient( $settings->submenu_outer_bg_gradient ),
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-horizontal-menu .tnit-advance-sub-menu-bg-photo,.fl-node-$id .tnit-advance-vertical-menu .tnit-advance-sub-menu-bg-photo",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'  => 'accordion' !== $settings->menu_layout && 'all' !== $settings->responsive_breakpoint,
		'props'    => array(
			'background-image'    => $settings->submenu_outer_bg_photo_src,
			'background-position' => str_replace( '-', ' ', $settings->submenu_outer_bg_position ),
			'background-repeat'   => $settings->submenu_outer_bg_repeat,
			'background-size'     => $settings->submenu_outer_bg_size,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'media'        => ( ! empty( $hambruger_show_on ) ) ? 'min-width:' . $hambruger_show_on : '',
		'enabled'      => 'accordion' !== $settings->menu_layout && 'all' !== $settings->responsive_breakpoint,
		'setting_name' => 'submenu_outer_border',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu,.fl-node-$id .tnit-advance-vertical-menu:not(.tnit-hamburger-menu-expand) .tnit-advance-sub-menu",
	)
);

//Sub Menu Styling
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'submenu_padding',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'submenu_padding_top',
			'padding-right'  => 'submenu_padding_right',
			'padding-bottom' => 'submenu_padding_bottom',
			'padding-left'   => 'submenu_padding_left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span",
		'props'    => array(
			'color' => $settings->submenu_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li:hover > span",
		'props'    => array(
			'color' => $settings->submenu_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span",
		'props'    => array(
			'background-color' => $settings->submenu_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li:hover > span",
		'enabled'  => 'accordion' === $settings->menu_layout,
		'props'    => array(
			'background-color' => $settings->submenu_hbg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-1 > li:hover > span,
						.fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-2 > li > span:before,
						.fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-3 > li > span:before,
						.fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-4 > li > span:before,
						.fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu-hover-style-5 > li > span:before",
		'enabled'  => 'accordion' !== $settings->menu_layout,
		'props'    => array(
			'background-color' => $settings->submenu_hbg,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'submenu_icon_image_size',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'submenu_icon_image_size',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon > img",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon",
		'props'    => array(
			'color' => $settings->submenu_icon_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li:hover > span > .tnit-submenu-item-icon",
		'props'    => array(
			'color' => $settings->submenu_icon_hvr_color,
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'submenu_icon_image_spacing',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon-before",
		'prop'         => 'margin-right',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'submenu_icon_image_spacing',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span > .tnit-submenu-item-icon-after",
		'prop'         => 'margin-left',
		'unit'         => 'px',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'submenu_typography',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span",
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'submenu_border',
		'selector'     => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li > span",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id nav:not(.tnit-hamburger-menu-expand) .tnit-advance-menu-list .tnit-advance-sub-menu > li:hover > span",
		'props'    => array(
			'border-color' => $settings->submenu_border_hcolor,
		),
	)
);

//Toggle Button
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-toggle-wrapper",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'max-width:' . $hambruger_show_on : '',
		'props'    => array(
			'display' => 'inline-flex',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-close",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'max-width:' . $hambruger_show_on : '',
		'props'    => array(
			'display' => 'inline-flex',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-wrapper",
		'media'    => ( ! empty( $hambruger_show_on ) ) ? 'max-width:' . $hambruger_show_on : '',
		'props'    => array(
			'text-align' => $settings->hamburger_button_align,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_typo',
		'selector'     => ".fl-node-$id .tnit-advance-menu-toggle-text",
	)
);


FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_icon_size',
		'selector'     => ".fl-node-$id .tnit-advance-menu-toggle-wrapper > i",
		'prop'         => 'font-size',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_icon_space',
		'selector'     => ".fl-node-$id .tnit-advance-menu-toggle-icon-after",
		'prop'         => 'margin-left',
		'unit'         => 'px',
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_icon_space',
		'selector'     => ".fl-node-$id .tnit-advance-menu-toggle-icon-before",
		'prop'         => 'margin-right',
		'unit'         => 'px',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_typo',
		'selector'     => ".fl-node-$id .tnit-advance-menu-toggle-text",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-toggle-wrapper",
		'props'    => array(
			'color' => $settings->hamburger_button_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-toggle-wrapper",
		'props'    => array(
			'background-color' => $settings->hamburger_button_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-toggle-wrapper:hover",
		'props'    => array(
			'color' => $settings->hamburger_button_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-toggle-wrapper:hover",
		'props'    => array(
			'background-color' => $settings->hamburger_button_hbg,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_border',
		'selector'     => ".fl-node-$id .tnit-advance-menu-toggle-wrapper",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-toggle-wrapper:hover",
		'props'    => array(
			'border-color' => $settings->hamburger_button_hborder,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_padding',
		'selector'     => ".fl-node-$id .tnit-advance-menu-toggle-wrapper",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'hamburger_button_padding_top',
			'padding-right'  => 'hamburger_button_padding_right',
			'padding-bottom' => 'hamburger_button_padding_bottom',
			'padding-left'   => 'hamburger_button_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_margin',
		'selector'     => ".fl-node-$id .tnit-advance-menu-toggle-wrapper",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'hamburger_button_margin_top',
			'margin-right'  => 'hamburger_button_margin_right',
			'margin-bottom' => 'hamburger_button_margin_bottom',
			'margin-left'   => 'hamburger_button_margin_left',
		),
	)
);

//Hamburger Menu
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_width',
		'selector'     => ".fl-node-$id [class*=tnit-hamburger-layout-reveal] > nav",
		'prop'         => 'width',
		'unit'         => 'px',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-layout-reveal-right > nav",
		'props'    => array(
			'right' => ( ! empty( $settings->hamburger_width ) ) ? - $settings->hamburger_width . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-layout-reveal-right > nav",
		'media'    => 'medium',
		'props'    => array(
			'right' => ( ! empty( $settings->hamburger_width_medium ) ) ? - $settings->hamburger_width_medium . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-layout-reveal-right > nav",
		'media'    => 'responsive',
		'props'    => array(
			'right' => ( ! empty( $settings->hamburger_width_responsive ) ) ? - $settings->hamburger_width_responsive . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-layout-reveal-left > nav",
		'props'    => array(
			'left' => ( ! empty( $settings->hamburger_width ) ) ? - $settings->hamburger_width . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-layout-reveal-left > nav",
		'media'    => 'medium',
		'props'    => array(
			'left' => ( ! empty( $settings->hamburger_width_medium ) ) ? - $settings->hamburger_width_medium . 'px' : '',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-layout-reveal-left > nav",
		'media'    => 'responsive',
		'props'    => array(
			'left' => ( ! empty( $settings->hamburger_width_responsive ) ) ? - $settings->hamburger_width_responsive . 'px' : '',
		),
	)
);


FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand",
		'props'    => array(
			'background-color' => $settings->hamburger_outer_bg,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_outer_padding',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'hamburger_outer_padding_top',
			'padding-right'  => 'hamburger_outer_padding_right',
			'padding-bottom' => 'hamburger_outer_padding_bottom',
			'padding-left'   => 'hamburger_outer_padding_left',
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_border',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand",
	)
);

//Colors
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li > span",
		'props'    => array(
			'color' => $settings->hamburger_menu_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li:hover > span",
		'props'    => array(
			'color' => $settings->hamburger_menu_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li > span",
		'props'    => array(
			'background-color' => $settings->hamburger_menu_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li:hover > span",
		'props'    => array(
			'background-color' => $settings->hamburger_menu_hbg,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_menu_typography',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li > span",
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_menu_border',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li > span",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li:hover > span",
		'props'    => array(
			'border-color' => $settings->hamburger_menu_hborder,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_menu_padding',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li > span",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'hamburger_menu_padding_top',
			'padding-right'  => 'hamburger_menu_padding_right',
			'padding-bottom' => 'hamburger_menu_padding_bottom',
			'padding-left'   => 'hamburger_menu_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_menu_margin',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand > ul > li > span",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'hamburger_menu_margin_top',
			'margin-right'  => 'hamburger_menu_margin_right',
			'margin-bottom' => 'hamburger_menu_margin_bottom',
			'margin-left'   => 'hamburger_menu_margin_left',
		),
	)
);

//Hamburger Submenu
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span",
		'props'    => array(
			'color' => $settings->hamburger_submenu_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li:hover > span",
		'props'    => array(
			'color' => $settings->hamburger_submenu_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span",
		'props'    => array(
			'background-color' => $settings->hamburger_submenu_bg,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li:hover > span",
		'props'    => array(
			'background-color' => $settings->hamburger_submenu_hbg,
		),
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_submenu_typography',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span",
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_submenu_border',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li:hover > span",
		'props'    => array(
			'border-color' => $settings->hamburger_submenu_hborder,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_submenu_padding',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'hamburger_submenu_padding_top',
			'padding-right'  => 'hamburger_submenu_padding_right',
			'padding-bottom' => 'hamburger_submenu_padding_bottom',
			'padding-left'   => 'hamburger_submenu_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_submenu_margin',
		'selector'     => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-sub-menu > li > span",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'hamburger_submenu_margin_top',
			'margin-right'  => 'hamburger_submenu_margin_right',
			'margin-bottom' => 'hamburger_submenu_margin_bottom',
			'margin-left'   => 'hamburger_submenu_margin_left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand",
		'props'    => array(
			'text-align' => $settings->hamburger_menu_align,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-menu-expand .tnit-advance-menu-dropdown-toggle",
		'props'    => array(
			'float'       => ( 'right' === $settings->hamburger_menu_align ) ? 'left' : ( ( 'center' === $settings->hamburger_menu_align ) ? 'none' : 'right' ),
			'margin-left' => ( 'center' === $settings->hamburger_menu_align ) ? '10px' : '',
		),
	)
);

//Close Button
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-close",
		'props'    => array(
			'font-size'   => ( ! empty( $settings->hamburger_close_icon_size ) ) ? $settings->hamburger_close_icon_size . 'px' : '',
			'width'       => ( ! empty( $settings->hamburger_close_icon_size ) ) ? $settings->hamburger_close_icon_size * 2 . 'px' : '',
			'height'      => ( ! empty( $settings->hamburger_close_icon_size ) ) ? $settings->hamburger_close_icon_size * 2 . 'px' : '',
			'line-height' => ( ! empty( $settings->hamburger_close_icon_size ) ) ? $settings->hamburger_close_icon_size * 2 . 'px' : '',
		),
	)
);

FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_close_icon_size',
		'selector'     => ".fl-node-$id .tnit-advance-menu-close",
		'prop'         => 'line-height',
		'unit'         => 'px',
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-close",
		'props'    => array(
			'color' => $settings->hamburger_close_icon_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-close:hover",
		'props'    => array(
			'color' => $settings->hamburger_close_icon_hcolor,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-close",
		'props'    => array(
			'background-color' => $settings->hamburger_close_btn_bg_color,
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-close:hover",
		'props'    => array(
			'background-color' => $settings->hamburger_close_btn_bg_hcolor,
		),
	)
);

FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_close_btn_border',
		'selector'     => ".fl-node-$id .tnit-advance-menu-close",
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-advance-menu-close:hover",
		'props'    => array(
			'border-color' => $settings->hamburger_close_btn_bcolor,
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'hamburger_button_margin',
		'selector'     => ".fl-node-$id .tnit-advance-menu-close",
		'unit'         => 'px',
		'props'        => array(
			'margin-top'    => 'hamburger_button_margin_top',
			'margin-right'  => 'hamburger_button_margin_right',
			'margin-bottom' => 'hamburger_button_margin_bottom',
			'margin-left'   => 'hamburger_button_margin_left',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id tnit-hamburger-layout-none",
		'media'    => ( 'all' === $settings->responsive_breakpoint ) ? 'default' : '',
		'props'    => array(
			'display' => 'none',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-hamburger-layout-none",
		'media'    => ( 'all' === $settings->responsive_breakpoint ) ? 'default' : $settings->responsive_breakpoint,
		'props'    => array(
			'display' => 'none',
		),
	)
);
