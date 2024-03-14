<?php
/*
 *	Plugin Name: BricksLabs Bricks Navigator
 *	Plugin URI: https://brickslabs.com/bricks-navigator/
 *	Author: Sridhar Katakam
 *	Author URI: https://brickslabs.com
 *	Description: Adds quick links in the WordPress admin bar for users of Bricks theme.
 *	Text Domain: brickslabs-bricks-navigator
 *	Version: 1.0.3
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'BRICKSLABS_BRICKS_NAVIGATOR_VERSION' ) ) {
	define( 'BRICKSLABS_BRICKS_NAVIGATOR_VERSION', '1.0.3' );
}

if ( ! defined( 'BRICKSLABS_BRICKS_NAVIGATOR_BASE' ) ) {
	define( 'BRICKSLABS_BRICKS_NAVIGATOR_BASE', plugin_basename( __FILE__ ) );
}

//======================================================================
// FUNCTIONS
//======================================================================
/**
 * Function to check whether the plugin should do its thing for the logged-in user.
 *
 * @return bool true if the active theme is Bricks or a child theme of Bricks AND if `bricks_is_builder` function exists AND if current user can use builder OR false otherwise.
 * @link https://wordpress.stackexchange.com/a/190298/14380
 * @link https://www.isitwp.com/display-theme-information-with-get_theme_data/
 */
function brickslabs_bricks_navigator_user_can_use_bricks_builder(): bool {
	$parent_theme = wp_get_theme( get_template() );

	return ( 'Bricks' === $parent_theme->get( 'Name' ) && function_exists( 'bricks_is_builder' ) && \Bricks\Capabilities::current_user_can_use_builder() );
}

//======================================================================
// ASSETS
//======================================================================

add_action( 'admin_enqueue_scripts', 'brickslabs_bricks_navigator_css' );
add_action( 'wp_enqueue_scripts', 'brickslabs_bricks_navigator_css' );
/**
 * Load custom CSS both in the WP admin and on front end.
 */
function brickslabs_bricks_navigator_css(): void {
	if ( ! is_admin_bar_showing() || ! brickslabs_bricks_navigator_user_can_use_bricks_builder() ) {
		return;
	}

	if ( is_admin_bar_showing() ) {
		wp_enqueue_style( 'brickslabs_bricks_navigator_style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', [], BRICKSLABS_BRICKS_NAVIGATOR_VERSION );
	}
}

//======================================================================
// BRICKS
//======================================================================

add_action( 'admin_bar_menu', 'brickslabs_bricks_navigator_bricks', 999 );
/**
 * Add the "Bricks" link in admin bar main menu.
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
 */
function brickslabs_bricks_navigator_bricks(WP_Admin_Bar $wp_admin_bar ): void {
	if ( ! is_admin_bar_showing() || ! brickslabs_bricks_navigator_user_can_use_bricks_builder() ) {
		return;
	}

	$is_bricks_enabled = $options['bricks'] ?? false;

	$iconhtml = sprintf( '<img src="%s" style="width: 16px; height: 16px; padding-right: 6px;" />', plugin_dir_url( __FILE__ ) . 'assets/images/bricks-logo.png' );

	$wp_admin_bar->add_node(
		[
			'id'    => 'bn-bricks',
			'title' => $iconhtml . __( 'Bricks', 'bricks-navigator' ),
			'href'  => admin_url( 'themes.php?page=bricks' ),
		]
	);

	// Site-specific Bricks links
	require_once 'inc/bricks.php';

	// Community
	if ( ! get_option( 'brickslabs_bricks_navigator_hide_community_menu' ) ) {
		require_once 'inc/community.php';
	}

	// 3rd party plugins
	if ( ! get_option( 'brickslabs_bricks_navigator_hide_thirdparty_plugins' ) ) {
		require_once 'inc/thirdpartyplugins.php';
	}
}

// Settings page
require_once 'inc/settings.php';

// If brickslabs_bricks_navigator_show_in_editor option is checked, show the admin bar in Bricks editor.
if ( get_option( 'brickslabs_bricks_navigator_show_in_editor' ) ) {
	require_once 'inc/show-admin-bar-in-editor.php';
}

// Add settings link in the plugin list page
add_filter( 'plugin_action_links_' . BRICKSLABS_BRICKS_NAVIGATOR_BASE, function( $links ) {
	$url = esc_url(
		add_query_arg(
			'page',
			'brickslabs-bricks-navigator',
			get_admin_url() . 'admin.php'
		)
	);

	// Create the link
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';

	// Adds the link to the beginning of the array
	array_unshift(
		$links,
		$settings_link
	);

	return $links;
} );