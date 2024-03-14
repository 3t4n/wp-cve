<?php
/**
 * Clone Elements Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Elements for clone Menu 1
$menu_1_item_icon        = array(
	'name'   => 'param[menu_1][item_icon][]',
	'class'  => 'icons',
	'type'   => 'select',
	'val'    => 'fas fa-hand-point-up',
	'option' => $icons_new,
);

$menu_1_item_tooltip     = array(
	'name'  => 'param[menu_1][item_tooltip][]',
	'class' => 'item-tooltip',
	'type'  => 'text',
	'val'   => '',
);

$menu_1_item_type = array(
	'name'   => 'param[menu_1][item_type][]',
	'type'   => 'select',
	'val'    => 'link',
	'class'  => 'item-type',
	'option' => array(
		'link'         => esc_attr__( 'Link', 'side-menu' ),
	),
);

$menu_1_item_link = array(
	'name' => 'param[menu_1][item_link][]',
	'type' => 'text',
	'val'  => '',
);

$menu_1_new_tab = array(
	'name'  => 'param[menu_1][new_tab][]',
	'class' => '',
	'type'  => 'checkbox',
	'val'   => '',
);

$menu_1_button_id = array(
	'name' => 'param[menu_1][button_id][]',
	'type' => 'text',
	'val'  => '',
);

$menu_1_button_id_help = array(
	'text' => esc_attr__( 'Set ID for element.', 'side-menu' ),
);

$menu_1_button_class = array(
	'name' => 'param[menu_1][button_class][]',
	'type' => 'text',
	'val'  => '',
);

$menu_1_button_class_help = array(
	'title' => esc_attr__( 'Set Class for element.', 'side-menu' ),
	'ul'    => array(
		__( 'You may enter several classes separated by a space.', 'side-menu' ),
	)
);

$menu_1_link_rel = array(
	'name' => 'param[menu_1][link_rel][]',
	'type' => 'text',
	'val'  => '',
);

// Font color
$menu_1_color = array(
	'name' => 'param[menu_1][color][]',
	'type' => 'color',
	'val'  => '#ffffff',
);

// Icon Сolor
$menu_1_iconcolor = array(
	'name' => 'param[menu_1][iconcolor][]',
	'type' => 'color',
	'val'  => '#ffffff',
);

// Background
$menu_1_bcolor = array(
	'name' => 'param[menu_1][bcolor][]',
	'type' => 'color',
	'val'  => '#128be0',
);

// Background Hover
$menu_1_hbcolor = array(
	'name' => 'param[menu_1][hbcolor][]',
	'type' => 'color',
	'val'  => '#128be0',
);

$menu_1_item_icon_help = array(
	'title' => esc_attr__( 'Set the icon for menu item. If you want use the custom item:', 'side-menu' ),
	'ul'    => array(
		esc_attr__( '1. Check the box on "custom"', 'side-menu' ),
		esc_attr__( '2. Upload the icon in Media Library', 'side-menu' ),
		esc_attr__( '3. Copy the URL to icon', 'side-menu' ),
		esc_attr__( '4. Paste the icon URL to field', 'side-menu' ),
	),
);

$menu_1_item_tooltip_help = array(
	'text' => esc_attr__( 'Set the text for menu item.', 'side-menu' ),
);

$menu_1_item_type_help = array(
	'text' => esc_attr__( 'Select the type of menu item. Explanation of some types:', 'side-menu' ),
	'ul'   => array(
		esc_attr__( '<strong>Smooth Scroll</strong> - Smooth scrolling of the page to the specified anchors on the page.', 'side-menu' ),
		esc_attr__( '<strong>ShiftNav Menu</strong> - open the menu, wich create via the plugin ShiftNav.', 'side-menu' ),
	),
);

$menu_1_hold_open_help = array(
	'text' => esc_attr__( 'When the page loads, the menu item will open.', 'side-menu' ),
);

$menu_1_close_item_help = array(
	'text' => esc_attr__( 'Set the value in seconds after how long it is necessary to close the item. Leave the field empty or with a value of 0 for not to close the item.', 'side-menu' ),
);