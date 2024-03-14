<?php
/**
 * Plugin Name: Menu Item Duplicator
 * Description: Enable duplication of menu items (also duplicates sub-elements)
 * Version: 1.0.2
 * Author: Mathieu Hays
 * Author URI: https://mathieuhays.co.uk
 * License: GPL2
 * Text Domain: menu-item-duplicator
 * Domain Path: /languages
 */

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'MENU_ITEM_DUPLICATOR_VERSION', '1.0.2' );

if ( ! is_admin() ) {
	return;
}

function menu_item_duplicator_init() {
	load_plugin_textdomain(
		'menu-item-duplicator',
		false,
		basename( dirname( __FILE__ ) ) . '/languages'
	);
}
add_action( 'admin_init', 'menu_item_duplicator_init' );


function menu_item_duplicator_enqueue_scripts() {
	global $pagenow;

	// only load on the Appearance > Menus screen
	if ( 'nav-menus.php' !== $pagenow ) {
		return;
	}

	$script_path = 'dist/menu-item-duplicator.min.js';

	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$script_path = 'src/menu-item-duplicator.js';
	}

	wp_enqueue_script(
		'menu-item-duplicator',
		plugins_url( $script_path, __FILE__ ),
		[ 'jquery' ],
		MENU_ITEM_DUPLICATOR_VERSION,
		true
	);

	wp_localize_script( 'menu-item-duplicator', 'menuItemDuplicator', [
		'labels' => [
			'duplicate' => __( 'Duplicate', 'menu-item-duplicator' ),
		],
	] );
}
add_action( 'admin_enqueue_scripts', 'menu_item_duplicator_enqueue_scripts' );
