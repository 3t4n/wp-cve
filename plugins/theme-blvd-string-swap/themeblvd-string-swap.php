<?php
/*
Plugin Name: Theme Blvd String Swap
Description: This plugin will allow you alter the standard text strings that appear on the frontend of your site when using a Theme Blvd theme.
Version: 1.1.0
Author: Theme Blvd
Author URI: http://themeblvd.com
License: GPL2

    Copyright 2018  Theme Blvd

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

define( 'TB_STRING_SWAP_PLUGIN_VERSION', '1.1.0' );
define( 'TB_STRING_SWAP_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'TB_STRING_SWAP_PLUGIN_URI', plugins_url( '' , __FILE__ ) );

/**
 * Register text domain for localization.
 *
 * @since 1.0.3
 */
function tb_string_swap_textdomain() {

	load_plugin_textdomain( 'theme-blvd-string-swap' );

}
add_action( 'init', 'tb_string_swap_textdomain' );

/**
 * Display admin notice.
 *
 * This warning telling the user they must have a
 * theme with Theme Blvd Framework v2.4+ installed in
 * order to run this plugin.
 *
 * @since 1.0.4
 */
function tb_string_swap_warning() {

	global $current_user;

	// DEBUG: delete_user_meta( $current_user->ID, 'tb-nag-shortcodes-no-framework' );

	if ( ! get_user_meta( $current_user->ID, 'tb-nag-string-swap-no-framework' ) ) {

		echo '<div class="updated">';

		echo '<p><strong>Theme Blvd String Swap:</strong> ' . __( 'You are not using a theme with the Theme Blvd Framework v2.4+, and so this plugin will not do anything.', 'theme-blvd-string-swap' ) . '</p>';

		echo '<p><a href="' . tb_string_swap_disable_url( 'string-swap-no-framework' ) . '">' . __( 'Dismiss this notice', 'theme-blvd-string-swap' ) . '</a> | <a href="http://www.themeblvd.com" target="_blank">' . __( 'Visit ThemeBlvd.com', 'theme-blvd-string-swap' ) . '</a></p>';

		echo '</div>';

	}
}

/**
 * Dismiss an admin notice.
 *
 * @since 1.0.4
 */
function tb_string_swap_disable_nag() {

	global $current_user;

	if ( ! isset( $_GET['nag-ignore'] ) ) {

		return;

	}

	if ( 0 !== strpos( $_GET['nag-ignore'], 'tb-nag-' ) ) { // meta key must start with "tb-nag-"

		return;

	}

	if ( isset( $_GET['security'] ) && wp_verify_nonce( $_GET['security'], 'themeblvd-string-swap-nag' ) ) {

		add_user_meta( $current_user->ID, $_GET['nag-ignore'], 'true', true );

	}
}

/**
 * Get URL to dismiss admin notice.
 *
 * @since 1.0.4
 */
function tb_string_swap_disable_url( $id ) {

	global $pagenow;

	$url = admin_url( $pagenow );

	if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {

		$url .= sprintf( '?%s&nag-ignore=%s', $_SERVER['QUERY_STRING'], 'tb-nag-' . $id );

	} else {

		$url .= sprintf( '?nag-ignore=%s', 'tb-nag-' . $id );

	}

	$url .= sprintf( '&security=%s', wp_create_nonce( 'themeblvd-string-swap-nag' ) );

	return $url;

}

/**
 * Get options.
 *
 * These options will be passed into the theme's
 * options framework when generating the options
 * page.
 *
 * @since 1.0.0
 */
function tb_string_swap_get_options() {

	/*
	 * Retrieve current local text strings.
	 *
	 * This will also be modified later to tell the
	 * user they need to update their theme.
	 */
	$locals = themeblvd_get_all_locals();

	$options[] = array(
		'name' => __( 'Standard Text Strings', 'theme-blvd-string-swap' ),
		'desc' => __( 'Here you can find most of the text strings that you will typically find on the frontend of your site when using a Theme Blvd theme. Simply enter in a new value for each one that you want to change.<br><br>Note: This is a general plugin aimed at working with all Theme Blvd themes, however it\'s impossible to guarantee that this will effect every theme in the exact same way.', 'theme-blvd-string-swap' ),
		'type' => 'section_start',
	);

	foreach ( $locals as $id => $string ) {

		$options[] = array(
			'desc' => '<strong>' . __( 'Internal ID', 'theme-blvd-string-swap' ) . ':</strong> ' . $id . '<br><strong>' . __( 'Original String', 'theme-blvd-string-swap' ) . ':</strong> ' . $string,
			'id'   => $id,
			'std'  => $string,
			'type' => 'textarea',
		);

	}

	$options[] = array(
		'type' => 'section_end',
	);

	$options[] = array(
		'name' => __( 'Blog Meta', 'theme-blvd-string-swap' ),
		'desc' => null,
		'type' => 'section_start',
	);

	$options[] = array(
		'desc'  => __( 'Designate how you\'d like the meta info to display in your blog. This typically will show below the title of blog posts in most theme designs.<br><br>You can use the following macros:<br><strong>%date%</strong> - Date post was published.<br><strong>%author%</strong> - Author that wrote the post.<br><strong>%2$categories%</strong> - Categories post belongs to.<br><br><em>Note: Save this option as blank to allow the theme to show its normal meta info.</em>', 'theme-blvd-string-swap' ),
		'id'    => 'blog_meta',
		// translators: 1. post date, 2. post author
		'std'   => __( 'Posted on %1$date% by %author% in %2$categories%', 'theme-blvd-string-swap' ),
		'type'  => 'textarea',
	);

	if ( version_compare( TB_FRAMEWORK_VERSION, '2.5.0', '>=' ) ) { // Having this ommitted in older themes is for the "closer" bug!
		$options[] = array(
			'type' => 'section_end',
		);
	}

	return $options;

}

/**
 * Build options page.
 *
 * This function utilizes Theme_Blvd_Options_Page
 * of the theme framework to build an options page,
 * pulling the options from tb_string_swap_get_options().
 *
 * @since 1.0.3
 */
function tb_string_swap_admin() {

	if ( class_exists( 'Theme_Blvd_Options_Page' ) ) {

		global $_tb_string_swap_admin;

		// Check to make sure Theme Blvd Framework 2.4+ is running
		if ( ! defined( 'TB_FRAMEWORK_VERSION' ) || version_compare( TB_FRAMEWORK_VERSION, '2.4.0', '<' ) ) {
			add_action( 'admin_notices', 'tb_string_swap_warning' );
			add_action( 'admin_init', 'tb_string_swap_disable_nag' );
			return;
		}

		$options = tb_string_swap_get_options();

		$args = array(
			'parent'        => 'themes.php', // only used prior to framework 2.5.2
			'page_title'    => __( 'Theme Text Strings', 'theme-blvd-string-swap' ),
			'menu_title'    => __( 'Theme Text Strings', 'theme-blvd-string-swap' ),
			'cap'           => apply_filters( 'tb_string_swap_cap', 'edit_theme_options' ),
		);

		$_tb_string_swap_admin = new Theme_Blvd_Options_Page( 'tb_string_swap', $options, $args );

	}

}
add_action( 'after_setup_theme', 'tb_string_swap_admin' );

/**
 * Apply frontend text strings.
 *
 * This function merges any user-inputted text
 * string modifications with text strings pulled
 * from the framework.
 *
 * @since 1.0.0
 */
function tb_string_swap_apply_changes( $locals ) {

	if ( ! is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

		$new_locals = get_option( 'tb_string_swap' );

		foreach ( $locals as $id => $string ) {

			if ( isset( $new_locals[ $id ] ) ) {

				$locals[ $id ] = $new_locals[ $id ];

			}
		}
	}

	return $locals;

}
add_filter( 'themeblvd_frontend_locals', 'tb_string_swap_apply_changes', 999 );

/**
 * Display custom blog meta.
 *
 * @since 1.0.0
 */
function tb_string_swap_blog_meta() {

	$new_locals = get_option( 'tb_string_swap' );

	$meta = $new_locals['blog_meta'];

	$author_string = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" rel="author">' . get_the_author() . '</a>';

	$meta = str_replace( '%date%', get_the_time( get_option( 'date_format' ) ), $meta );

	$meta = str_replace( '%author%', $author_string, $meta );

	$meta = str_replace( '%categories%', get_the_category_list( ', ' ), $meta );

	echo '<div class="entry-meta">' . $meta . '</div><!-- .entry-meta -->';

}

/**
 * Add blog meta.
 *
 * If the user has typed in custom structure for
 * the entry meta that displays below post titles,
 * we'll hook it in here.
 *
 * @since 1.0.0
 */
function tb_string_swap_add_actions() {

	$new_locals = get_option( 'tb_string_swap' );

	if ( ! empty( $new_locals['blog_meta'] ) ) {

		remove_all_actions( 'themeblvd_blog_meta' );

		add_action( 'themeblvd_blog_meta', 'tb_string_swap_blog_meta' );

	}
}
add_action( 'after_setup_theme', 'tb_string_swap_add_actions', 999 );
