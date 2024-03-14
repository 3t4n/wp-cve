<?php
/**
 * Plugin Name: Visual Hook Guide for Kadence
 * Plugin URI: https://profiles.wordpress.org/srikat/
 * Description: Find Kadence action hooks easily and copy them by a single click at their actual locations in your Kadence theme.
 * Version: 1.0.1
 * Author: Sridhar Katakam
 * Author URI: https://profiles.wordpress.org/srikat/
 * Text Domain: visual-hook-guide-kadence
 * License: GPL v3
 * Requires at least: 6.2.2
 * Requires PHP: 7.4
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

register_activation_hook( __FILE__, 'kvhg_activation_check' );
/**
 * Check if Kadence is the parent theme.
 */
function kvhg_activation_check() {
	$theme_info = wp_get_theme();

	$kadence_flavors = [
		'kadence',
	];

	if ( ! in_array( $theme_info->Template, $kadence_flavors, true ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate ourself.

		$message = sprintf(
			/* translators: %s: URL to Kadence Framework. */
			__( 'Sorry, you can\'t activate this plugin unless you have installed <a href="%s">Kadence</a>.', 'visual-hook-guide-kadence' ),
			esc_url( 'https://www.kadencewp.com/kadence-theme/' )
		);

		wp_die( $message );
	}
}

add_action( 'admin_bar_menu', 'kvhg_admin_bar_links', 100 );
/**
 * Add admin bar items.
 */
function kvhg_admin_bar_links() {
	global $wp_admin_bar;

	if ( is_admin() || false === function_exists( 'Kadence\kadence' ) ) {
		return;
	}

	$wp_admin_bar->add_menu(
		[
			'id'       => 'kvhg',
			'title'    => __( 'Kadence Hooks', 'visual-hook-guide-kadence' ),
			'href'     => '',
			'position' => 0,
		]
	);

	$wp_admin_bar->add_menu(
		[
			'id'       => 'kvhg_action',
			'parent'   => 'kvhg',
			'title'    => __( 'Action Hooks', 'visual-hook-guide-kadence' ),
			'href'     => esc_url( add_query_arg( 'kvhg_hooks', 'show' ) ),
			'position' => 10,
		]
	);

	$wp_admin_bar->add_menu(
		[
			'id'       => 'kvhg_clear',
			'parent'   => 'kvhg',
			'title'    => __( 'Clear', 'visual-hook-guide-kadence' ),
			'href'     => esc_url(
				remove_query_arg(
					[
						'kvhg_hooks',
					]
				)
			),
			'position' => 10,
		]
	);

}

add_action( 'wp_enqueue_scripts', 'kvhg_hooks_script_and_styles' );
/**
 * Load assets.
 */
function kvhg_hooks_script_and_styles() {
	$kvhg_plugin_css_url = plugins_url( 'style.css', __FILE__ );
	$kvhg_plugin_js_url  = plugins_url( 'main.js', __FILE__ );

	if ( 'show' === filter_input( INPUT_GET, 'kvhg_hooks', FILTER_SANITIZE_STRING ) ) {
		wp_enqueue_style( 'kvhg-styles', $kvhg_plugin_css_url, null, '1.0.0', false );
		wp_enqueue_script( 'kvhg-scripts', $kvhg_plugin_js_url, null, '1.0.1', true );
	}
}

add_action( 'all', 'kvhg_print_hooks_on_page' );
/**
 * Print the hooks.
 */
function kvhg_print_hooks_on_page() {
	// BAIL without hooking into anything if on the admin page or if not displaying anything.
	if ( is_admin() || ! ( 'show' === filter_input( INPUT_GET, 'kvhg_hooks', FILTER_SANITIZE_STRING ) ) ) {
		return;
	}

	global $wp_actions;
	$filter = current_filter();

	if ( 'kadence_' === substr( $filter, 0, 8 ) ) {
		if ( isset( $wp_actions[ $filter ] ) ) {
			printf( '<div id="%1$s" class="kadence-hook"><input type="text" title="%1$s" readonly value="%1$s" /></div>', $filter );
		}
	}
}
