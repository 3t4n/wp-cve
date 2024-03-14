<?php
/**
 * Settings
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */


$count_i = ( ! empty( $param['menu_1']['item_type'] ) ) ? count( $param['menu_1']['item_type'] ) : '0';
if ( $count_i > 0 ) {
	for ( $i = 0; $i < $count_i; $i ++ ) {

		// Icon
		$item_icon_[ $i ] = array(
			'name'   => 'param[menu_1][item_icon][]',
			'class'  => 'icons',
			'type'   => 'select',
			'val'    => isset( $param['menu_1']['item_icon'][ $i ] ) ? $param['menu_1']['item_icon'][ $i ]
				: 'fas fa-hand-point-up',
			'option' => $icons_new,
		);

		// Label for item
		$item_tooltip_[ $i ] = array(
			'name'  => 'param[menu_1][item_tooltip][]',
			'class' => 'item-tooltip',
			'type'  => 'text',
			'val'   => isset( $param['menu_1']['item_tooltip'][ $i ] ) ? $param['menu_1']['item_tooltip'][ $i ] : '',
		);


		// Type of the item
		$item_type_[ $i ] = array(
			'name'   => 'param[menu_1][item_type][]',
			'type'   => 'select',
			'class'  => 'item-type',
			'val'    => isset( $param['menu_1']['item_type'][ $i ] ) ? $param['menu_1']['item_type'][ $i ] : '',
			'option' => array(
				'link'         => esc_attr__( 'Link', 'side-menu' ),
			),
 		);


		// Link URL
		$item_link_[ $i ] = array(
			'name' => 'param[menu_1][item_link][]',
			'type' => 'text',
			'val'  => isset( $param['menu_1']['item_link'][ $i ] ) ? $param['menu_1']['item_link'][ $i ] : '',
		);


		// Open link in a new window
		$new_tab_[ $i ] = array(
			'name'  => 'param[menu_1][new_tab][]',
			'class' => '',
			'type'  => 'checkbox',
			'val'   => isset( $param['menu_1']['new_tab'][ $i ] ) ? $param['menu_1']['new_tab'][ $i ] : 0,
			'func'  => '',
			'sep'   => '',
		);


		$button_id_[ $i ] = array(
			'name' => 'param[menu_1][button_id][]',
			'type' => 'text',
			'val'  => isset( $param['menu_1']['button_id'][ $i ] ) ? $param['menu_1']['button_id'][ $i ] : '',
		);

		$button_class_[ $i ] = array(
			'name' => 'param[menu_1][button_class][]',
			'type' => 'text',
			'val'  => isset( $param['menu_1']['button_class'][ $i ] ) ? $param['menu_1']['button_class'][ $i ] : '',
		);
		$link_rel_[ $i ] = array(
			'name' => 'param[menu_1][link_rel][]',
			'type' => 'text',
			'val'  => isset( $param['menu_1']['link_rel'][ $i ] ) ? $param['menu_1']['link_rel'][ $i ] : '',
		);


		// Font color
		$color_[ $i ] = array(
			'name' => 'param[menu_1][color][]',
			'type' => 'color',
			'val'  => isset( $param['menu_1']['color'][ $i ] ) ? $param['menu_1']['color'][ $i ] : '#ffffff',
		);

		// Icon Сolor
		$iconcolor_[ $i ] = array(
			'name' => 'param[menu_1][iconcolor][]',
			'type' => 'color',
			'val'  => isset( $param['menu_1']['iconcolor'][ $i ] ) ? $param['menu_1']['iconcolor'][ $i ] : '#ffffff',
		);

		// Background
		$bcolor_[ $i ] = array(
			'name' => 'param[menu_1][bcolor][]',
			'type' => 'color',
			'val'  => isset( $param['menu_1']['bcolor'][ $i ] ) ? $param['menu_1']['bcolor'][ $i ] : '#00494f',
		);

		// Background Hover
		$hbcolor_[ $i ] = array(
			'name' => 'param[menu_1][hbcolor][]',
			'type' => 'color',
			'val'  => isset( $param['menu_1']['hbcolor'][ $i ] ) ? $param['menu_1']['hbcolor'][ $i ] : '#80b719',
		);


	}

}


$item_icon_help = array(
	'title' => esc_attr__( 'Set the icon for menu item. If you want use the custom item:', 'side-menu' ),
	'ul'    => array(
		esc_attr__( '1. Check the box on "custom"', 'side-menu' ),
		esc_attr__( '2. Upload the icon in Media Library', 'side-menu' ),
		esc_attr__( '3. Copy the URL to icon', 'side-menu' ),
		esc_attr__( '4. Paste the icon URL to field', 'side-menu' ),
	),
);

$item_tooltip_help = array(
	'text' => esc_attr__( 'Set the text for menu item.', 'side-menu' ),
);

$item_type_help = array(
	'text' => esc_attr__( 'Select the type of menu item. Explanation of some types:', 'side-menu' ),
	'ul'   => array(
		esc_attr__( '<strong>Smooth Scroll</strong> - Smooth scrolling of the page to the specified anchors on the page.',
			'side-menu' ),
		esc_attr__( '<strong>ShiftNav Menu</strong> - open the menu, wich create via the plugin ShiftNav.',
			'side-menu' ),
	),
);

$hold_open_help = array(
	'text' => esc_attr__( 'When the page loads, the menu item will open.', 'side-menu' ),
);

$button_id_help = array(
	'text' => esc_attr__( 'Set the attribute ID for the menu item or left empty.', 'side-menu' ),
);

$button_class_help = array(
	'text' => esc_attr__( 'Set the attribute CLASS for the menu item or left empty.', 'side-menu' ),
);

$close_item_help = array(
	'text' => esc_attr__( 'Set the value in seconds after how long it is necessary to close the item. Leave the field empty or with a value of 0 for not to close the item.', 'side-menu' ),
);

$image_alt_help = array(
	'text' => esc_attr__( 'Set the attribute Alt for custom image.', 'side-menu' ),
);

