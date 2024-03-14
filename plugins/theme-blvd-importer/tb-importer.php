<?php
/*
Plugin Name: Theme Blvd Importer
Description: A free plugin that integrates some helpful import/export functionality into Theme Blvd themes.
Version: 1.0.4
Author: Theme Blvd
Author URI: http://themeblvd.com
License: GPL2

    Copyright 2015  Theme Blvd

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

define( 'TB_IMPORTER_PLUGIN_VERSION', '1.0.4' );
define( 'TB_IMPORTER_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'TB_IMPORTER_PLUGIN_URI', plugins_url( '' , __FILE__ ) );

/**
 * Run Importer
 *
 * @since 1.0.0
 */
function themeblvd_importer_init() {

	// Plugin isn't used outside of admin
	if ( ! is_admin() ) {
		return;
	}

	// global $_themeblvd_foo;

	// Check to make sure Theme Blvd Framework 2.5.2+ is running
	if ( ! defined( 'TB_FRAMEWORK_VERSION' ) || version_compare( TB_FRAMEWORK_VERSION, '2.5.2', '<' ) ) {
		add_action( 'admin_notices', 'themeblvd_importer_warning' );
		add_action( 'admin_init', 'themeblvd_importer_disable_nag' );
		return;
	}

	// Include files
	include_once( TB_IMPORTER_PLUGIN_DIR . '/inc/class-tb-export.php' );
	include_once( TB_IMPORTER_PLUGIN_DIR . '/inc/class-tb-import.php' );
	include_once( TB_IMPORTER_PLUGIN_DIR . '/inc/class-tb-export-options.php' );
	include_once( TB_IMPORTER_PLUGIN_DIR . '/inc/class-tb-import-options.php' );

	// Run the theme demo importer.
	if ( defined( 'WP_LOAD_IMPORTERS' ) ) {
		$importer = Theme_Blvd_Import::get_instance();
	}

}
add_action( 'after_setup_theme', 'themeblvd_importer_init', 5 );

/**
 * Register text domain for localization.
 *
 * @since 1.0.0
 */
function themeblvd_importer_textdomain() {
	load_plugin_textdomain('theme-blvd-importer');
}
add_action( 'init', 'themeblvd_importer_textdomain' );

/**
 * Display warning telling the user they must have a
 * theme with Theme Blvd framework v2.5.2+ installed in
 * order to run this plugin.
 *
 * @since 1.0.0
 */
function themeblvd_importer_warning() {

	global $current_user;

	// DEBUG: delete_user_meta( $current_user->ID, 'tb-nag-importer-no-framework' );

	if( ! get_user_meta( $current_user->ID, 'tb-nag-importer-no-framework' ) ) {
		echo '<div class="updated">';
		echo '<p><strong>Theme Blvd Importer: </strong>'.__( 'You are not using a theme with the Theme Blvd Framework v2.5.2+, and so this plugin will not do anything.', 'theme-blvd-importer' ).'</p>';
		echo '<p><a href="'.esc_url(themeblvd_importer_disable_url('importer-no-framework')).'">'.__('Dismiss this notice', 'theme-blvd-importer').'</a> | <a href="http://www.themeblvd.com" target="_blank">'.__('Visit ThemeBlvd.com', 'theme-blvd-importer').'</a></p>';
		echo '</div>';
	}
}

/**
 * Dismiss an admin notice.
 *
 * @since 1.0.0
 */
function themeblvd_importer_disable_nag() {

	global $current_user;

	if ( ! isset($_GET['nag-ignore']) ) {
		return;
	}

	if ( strpos($_GET['nag-ignore'], 'tb-nag-') !== 0 ) { // meta key must start with "tb-nag-"
		return;
	}

	if ( isset($_GET['security']) && wp_verify_nonce( $_GET['security'], 'themeblvd-importer-nag' ) ) {
		add_user_meta( $current_user->ID, $_GET['nag-ignore'], 'true', true );
	}
}

/**
 * Disable admin notice URL.
 *
 * @since 1.0.0
 */
function themeblvd_importer_disable_url( $id ) {

	global $pagenow;

	if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
		$pagenow .= '?'.$_SERVER['QUERY_STRING'];
	}

	$url = add_query_arg(
		array(
			'nag-ignore' 	=> 'tb-nag-'.$id,
			'security' 		=> wp_create_nonce('themeblvd-importer-nag')
		),
		admin_url($pagenow)
	);

	return $url;
}
