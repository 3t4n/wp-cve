<?php
/**
 * Main Settings param
 *
 * @package     Wow_Plugin
 * @copyright   Copyright (c) 2018, Dmytro Lobov
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */


// Position of the menu
$menu = array(
	'id'     => 'position',
	'name'   => 'param[menu]',
	'type'   => 'select',
	'val'    => isset( $param['menu'] ) ? $param['menu'] : 'left',
	'option' => array(
		'left'  => esc_attr__( 'Left', 'side-menu' ),
		'right' => esc_attr__( 'Right', 'side-menu' ),
	),
);

// Menu position help
$menu_help = array(
	'text' => esc_attr__( 'Specify menu position on screen.', 'side-menu' ),
);



// Menu item height
$height = array(
	'name'   => 'param[height]',
	'id'     => 'height',
	'type'   => 'number',
	'val'    => isset( $param['height'] ) ? $param['height'] : '40',
	'option' => array(
		'min'         => '0',
		'step'        => '1',
		'placeholder' => '40',
	),
);

$height_help = array(
	'text' => esc_attr__( 'The height of the menu items in (px).', 'side-menu' ),
);

// Space between items
$gap = array(
	'name'   => 'param[gap]',
	'id'     => 'gap',
	'type'   => 'number',
	'val'    => isset( $param['gap'] ) ? $param['gap'] : '2',
	'option' => array(
		'step'        => '1',
		'placeholder' => '2',
	),
);

$gap_help = array(
	'text' => esc_attr__( 'The height of the menu items in (px).', 'side-menu' ),
);

// Font size 
$fontsize = array(
	'name'   => 'param[fontsize]',
	'id'     => 'fontsize',
	'type'   => 'number',
	'val'    => isset( $param['fontsize'] ) ? $param['fontsize'] : '24',
	'option' => array(
		'step'        => '1',
		'placeholder' => '24',
	),
);

// Font size helper
$fontsize_help = array(
	'text' => esc_attr__( 'Set the font size for label content in px', 'side-menu' ),
);


// Icon size
$iconsize = array(
	'name'   => 'param[iconsize]',
	'id'     => 'iconsize',
	'type'   => 'number',
	'val'    => isset( $param['iconsize'] ) ? $param['iconsize'] : '24',
	'option' => array(
		'min'         => '0',
		'step'        => '1',
		'placeholder' => '24',
	),
);

$iconsize_help = array(
	'text' => esc_attr__( 'Set the size for icon in px', 'side-menu' ),
);


// Custom image width
$iwidth = array(
	'name'   => 'param[iwidth]',
	'id'     => 'iwidth',
	'type'   => 'number',
	'val'    => isset( $param['iwidth'] ) ? $param['iwidth'] : '24',
	'option' => array(
		'min'         => '0',
		'step'        => '1',
		'placeholder' => '0',
	),
	'func'   => '',
	'sep'    => '',
);

$iwidth_help = array(
	'text' => esc_attr__( 'Set the size for Custom icon in px', 'side-menu' ),
);

// Border width (px)
$bwidth = array(
	'name'   => 'param[bwidth]',
	'id'     => 'bwidth',
	'type'   => 'number',
	'val'    => isset( $param['bwidth'] ) ? $param['bwidth'] : '0',
	'option' => array(
		'min'         => '0',
		'step'        => '1',
		'placeholder' => '0',
	),
);

$bwidth_help = array(
	'text' => esc_attr__( 'Set the border width for menu items in px', 'side-menu' ),
);


// Border color
$bcolor = array(
	'name' => 'param[bcolor]',
	'id'   => 'bcolor',
	'type' => 'color',
	'val'  => isset( $param['bcolor'] ) ? $param['bcolor'] : 'rgba(0,0,0,0.75)',
	'sep'  => '',
);

$bcolor_help = array(
	'text' => esc_attr__( 'Set the border color', 'side-menu' ),
);

// Z-index
$zindex = array(
	'name'   => 'param[zindex]',
	'type'   => 'number',
	'val'    => isset( $param['zindex'] ) ? round( $param['zindex'] ) : '9',
	'option' => array(
		'min'         => '0',
		'step'        => '1',
		'placeholder' => '9',
	),
);

// Z-index helper
$zindex_help = array(
	'text' => esc_attr__( 'The z-index property specifies the stack order of an element. An element with greater stack order is always in front of an element with a lower stack order.',
		'side-menu' ),
);