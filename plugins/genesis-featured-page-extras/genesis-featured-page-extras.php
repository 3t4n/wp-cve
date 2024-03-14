<?php
/**
 * Main plugin file.
 * Extra advanced version of the Genesis Featured Page widget. This plugin is
 *    for the (premium) Genesis Framework 2.0+ only!
 *
 * @package     Genesis Featured Page Extras
 * @author      David Decker
 * @copyright   Copyright (c) 2014, David Decker - DECKERWEB
 * @license     GPL-2.0+
 * @link        http://deckerweb.de/twitter
 *
 * @wordpress-plugin
 * Plugin Name: Genesis Featured Page Extras
 * Plugin URI:  http://genesisthemes.de/en/wp-plugins/genesis-featured-page-extras/
 * Description: Extra advanced version of the Genesis Featured Page widget. This plugin is for the (premium) Genesis Framework 2.0+ only!
 * Version:     1.2.0
 * Author:      David Decker - DECKERWEB
 * Author URI:  http://deckerweb.de/
 * License:     GPL-2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: genesis-featured-page-extras
 * Domain Path: /languages/
 *
 * Copyright (c) 2014 David Decker - DECKERWEB
 *
 *     This file is part of Genesis Featured Page Extras,
 *     a plugin for WordPress.
 *
 *     Genesis Featured Page Extras is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     Genesis Featured Page Extras is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting constants.
 *
 * @since 1.0.0
 */
/** Plugin directory */
define( 'GFPE_PLUGIN_DIR', trailingslashit( dirname( __FILE__ ) ) );

/** Plugin base directory */
define( 'GFPE_PLUGIN_BASEDIR', trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );


/**
 * Set filter for plugin's languages directory.
 *
 * @since  1.0.0
 *
 * @return string Path to plugin's languages directory.
 */
function ddw_gfpe_plugin_lang_dir() {

	return apply_filters( 'gfpe_filter_lang_dir', GFPE_PLUGIN_BASEDIR . 'languages' );

}  // end of function ddw_gfpe_plugin_lang_dir


register_activation_hook( __FILE__, 'ddw_gfpe_activation_check' );
/**
 * Checks for activated Genesis Framework before allowing plugin to activate.
 *
 * @since 1.0.0
 *
 * @uses  load_plugin_textdomain() To load default translations from plugin folder.
 * @uses  ddw_gfpe_plugin_lang_dir() To get filterable path to plugin's languages directory.
 * @uses  get_template_directory() To determine parent theme (Genesis).
 * @uses  deactivate_plugins() In case, deactivate ourself.
 * @uses  wp_die() In case, deactivate ourself, output user message.
 *
 * @param string $gfpe_deactivation_message
 */
function ddw_gfpe_activation_check() {

	/** Load translations to display for the activation message. */
	load_plugin_textdomain( 'genesis-featured-page-extras', FALSE, esc_attr( ddw_gfpe_plugin_lang_dir() ) );

	/** Check for activated Genesis Framework (= template/parent theme) */
	if ( ! function_exists( 'genesis_html5' ) && basename( get_template_directory() ) != 'genesis' ) {

		/** If no Genesis, deactivate ourself */
		deactivate_plugins( plugin_basename( __FILE__ ) );

		/** Message: no Genesis active */
		$gfpe_deactivation_message = sprintf(
			__( 'Sorry, you cannot activate the %1$s plugin unless you have installed the latest version of the %2$sGenesis Framework%3$s.', 'genesis-featured-page-extras' ),
			__( 'Genesis Featured Page Extras', 'genesis-featured-page-extras' ),
			'<a href="http://deckerweb.de/go/genesis/" target="_new"><strong><em>',
			'</em></strong></a>'
		);

		/** Deactivation message */
		wp_die(
			$gfpe_deactivation_message,
			__( 'Plugin', 'genesis-featured-page-extras' ) . ': ' . __( 'Genesis Featured Page Extras', 'genesis-featured-page-extras' ),
			array( 'back_link' => true )
		);

	}  // end-if Genesis check

}  // end of function ddw_gfpe_activation_check


add_action( 'init', 'ddw_gfpe_init', 1 );
/**
 * Load the text domain for translation of the plugin.
 * 
 * @since 1.0.0
 *
 * @uses  is_admin()
 * @uses  get_locale()
 * @uses  load_textdomain()	To load translations first from WP_LANG_DIR sub folder.
 * @uses  load_plugin_textdomain() To additionally load default translations from plugin folder (default).
 * @uses  ddw_gfpe_plugin_lang_dir() To get filterable path to plugin's languages directory.
 *
 * @param string $gfpe_textdomain
 * @param string $locale
 * @param string $gfpe_wp_lang_dir
 */
function ddw_gfpe_init() {

	/** Load translations, plus include admin specific functions */
	if ( is_admin() ) {

		/** Set unique textdomain string */
		$gfpe_textdomain = 'genesis-featured-page-extras';

		/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
		$locale = apply_filters( 'plugin_locale', get_locale(), $gfpe_textdomain );

		/** Set filter for WordPress languages directory */
		$gfpe_wp_lang_dir = apply_filters(
			'gfpe_filter_wp_lang_dir',
			trailingslashit( WP_LANG_DIR ) . 'genesis-featured-page-extras/' . $gfpe_textdomain . '-' . $locale . '.mo'
		);

		/** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
		load_textdomain( $gfpe_textdomain, $gfpe_wp_lang_dir );

		/** Translations: Secondly, look in plugin's "languages" folder = default */
		load_plugin_textdomain( $gfpe_textdomain, FALSE, esc_attr( ddw_gfpe_plugin_lang_dir() ) );


		/** Include admin helper functions */
		require_once( GFPE_PLUGIN_DIR . 'includes/gfpe-admin-extras.php' );

	}  // end-if

	/** Add "Widgets Page" link to plugin page */
	if ( is_admin() && current_user_can( 'edit_theme_options' ) ) {

		add_filter(
			'plugin_action_links_' . plugin_basename( __FILE__ ),
			'ddw_gfpe_widgets_page_link'
		);

	}  // end if

}  // end of function ddw_gfpe_init


/**
 * Helper function for setting shortcode support in (Text) Widgets.
 *
 * USAGE: add_filter( 'gfpe_filter_widget_shortcodes', '__return_false' );
 *
 * @since 1.0.0
 */
function ddw_gfpe_widget_shortcodes() {

	return (bool) apply_filters( 'gfpe_filter_widget_shortcodes', '__return_true' );

}  // end of function ddw_gfpe_widget_shortcodes


add_action( 'widgets_init', 'ddw_gfpe_register_widget' );
/**
 * Register our Widget class, include plugin file.
 *
 * @since 1.0.0
 *
 * @uses  register_widget()
 */
function ddw_gfpe_register_widget() {

	/** Load widget code part */
	require_once( GFPE_PLUGIN_DIR . 'includes/gfpe-widget-featured-page-extras.php' );

	/** Register the widget - only if Genesis active */
	if ( defined( 'PARENT_THEME_VERSION' ) ) {

		return register_widget( 'DDW_Genesis_Featured_Page_Extras' );

	}  // end if

	/** Add shortcode support to widgets */
	if ( ddw_gfpe_widget_shortcodes() && ! is_admin() ) {

		add_filter( 'widget_text', 'do_shortcode' );

	}  // end if

}  // end of function ddw_gfpe_register_widget


/**
 * Returns current plugin's header data in a flexible way.
 *
 * @since  1.0.0
 *
 * @uses   is_admin()
 * @uses   get_plugins()
 * @uses   plugin_basename()
 *
 * @param  $gfpe_plugin_value
 *
 * @return string Plugin data.
 */
function ddw_gfpe_plugin_get_data( $gfpe_plugin_value ) {

	/** Bail early if we are not in wp-admin */
	if ( ! is_admin() ) {
		return;
	}

	/** Include WordPress plugin data */
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	$gfpe_plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$gfpe_plugin_file = basename( ( __FILE__ ) );

	return $gfpe_plugin_folder[ $gfpe_plugin_file ][ $gfpe_plugin_value ];

}  // end of function ddw_gfpe_plugin_get_data


/**
 * Helper function for returning string for minifying scripts/ stylesheets.
 *
 * @since  1.0.0
 *
 * @return string String for minifying scripts/ stylesheets.
 */
function ddw_gfpe_script_suffix() {
	
	return ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? '' : '.min';

}  // end of function ddw_gfpe_script_suffix


/**
 * Helper function for returning string for versioning scripts/ stylesheets.
 *
 * @since  1.0.0
 *
 * @return string Version string for versioning scripts/ stylesheets.
 */
function ddw_gfpe_script_version() {

	return ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? time() : filemtime( plugin_dir_path( __FILE__ ) );

}  // end of function ddw_gfpe_script_version