<?php
/**
 * Main plugin file.
 * Finally, use widgets to maintain & customize your Archive Page Template in
 *   Genesis Framework and Child Themes to create archive or sitemap listings.
 *
 * @package   Genesis Widgetized Archive
 * @author    David Decker
 * @copyright Copyright (c) 2012-2013, David Decker - DECKERWEB
 * @link      http://deckerweb.de/twitter
 *
 * Plugin Name: Genesis Widgetized Archive
 * Plugin URI: http://genesisthemes.de/en/wp-plugins/genesis-widgetized-archive/
 * Description: Finally, use widgets to maintain & customize your Archive Page Template in Genesis Framework and Child Themes to create archive or sitemap listings.
 * Version: 1.2.1
 * Author: David Decker - DECKERWEB
 * Author URI: http://deckerweb.de/
 * License: GPL-2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: genesis-widgetized-archive
 * Domain Path: /languages/
 *
 * Copyright (c) 2012-2013 David Decker - DECKERWEB
 *
 *     This file is part of Genesis Widgetized Archive,
 *     a plugin for WordPress.
 *
 *     Genesis Widgetized Archive is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     Genesis Widgetized Archive is distributed in the hope that
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
 * @since 1.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting constants
 *
 * @since 1.0.0
 */
/** Set plugin version */
define( 'GWAT_VERSION', ddw_gwat_plugin_get_data( 'Version' ) );

/** Plugin directory */
define( 'GWAT_PLUGIN_DIR', trailingslashit( dirname( __FILE__ ) ) );

/** Plugin base directory */
define( 'GWAT_PLUGIN_BASEDIR', trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );

/** Set constant/ filter for plugin's languages directory */
define(
	'GWAT_LANG_DIR',
	apply_filters( 'gwat_filter_lang_dir', GWAT_PLUGIN_BASEDIR . 'languages/' )
);

/** Dev scripts & styles on Debug, minified on production */
define(
	'GWAT_SCRIPT_SUFFIX',
	( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? '' : '.min'
);


register_activation_hook( __FILE__, 'ddw_gwat_activation_check' );
/**
 * Checks for activated Genesis Framework before allowing plugin to activate
 *
 * @since 1.0.0
 *
 * @uses  load_plugin_textdomain()
 * @uses  deactivate_plugins()
 * @uses  wp_die()
 */
function ddw_gwat_activation_check() {

	/** Load translations to display for the activation message. */
	load_plugin_textdomain( 'genesis-widgetized-archive', FALSE, GWAT_LANG_DIR );

	/** Check for activated Genesis Framework (= template/parent theme) */
	if ( basename( get_template_directory() ) != 'genesis' ) {

		/** If no Genesis, deactivate ourself */
		deactivate_plugins( plugin_basename( __FILE__ ) );

		/** Message: no Genesis active */
		$gwat_deactivation_message = sprintf(
			__( 'Sorry, you cannot activate the %1$s plugin unless you have installed the latest version of the %2$sGenesis Framework%3$s', 'genesis-widgetized-archive' ),
			__( 'Genesis Widgetized Archive', 'genesis-widgetized-archive' ),
			'<a href="http://deckerweb.de/go/genesis/" target="_new"><strong><em>',
			'</em></strong></a>'
		);

		/** Deactivation message */
		wp_die(
			$gwat_deactivation_message,
			__( 'Plugin', 'genesis-widgetized-archive' ) . ': ' . __( 'Genesis Widgetized Archive', 'genesis-widgetized-archive' ),
			array( 'back_link' => true )
		);

	}  // end-if Genesis check

}  // end of function ddw_gwat_activation_check


add_action( 'init', 'ddw_gwat_init', 1 );
/**
 * Plugin init:
 *    - Load the text domain for translation of the plugin.
 *    - Register the additional widget areas.
 * 
 * @since 1.0.0
 *
 * @uses  load_textdomain()	To load translations first from WP_LANG_DIR sub folder.
 * @uses  load_plugin_textdomain() To additionally load default translations from plugin folder (default).
 * @uses  get_template_directory()
 */
function ddw_gwat_init() {

	/** Set unique textdomain string */
	$gwat_textdomain = 'genesis-widgetized-archive';

	/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
	$locale = apply_filters( 'plugin_locale', get_locale(), $gwat_textdomain );

	/** Set filter for WordPress languages directory */
	$gwat_wp_lang_dir = apply_filters(
		'gwat_filter_wp_lang_dir',
		trailingslashit( WP_LANG_DIR ) . 'genesis-widgetized-archive/' . $gwat_textdomain . '-' . $locale . '.mo'
	);

	/** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
	load_textdomain( $gwat_textdomain, $gwat_wp_lang_dir );

	/** Translations: Secondly, look in plugin's "languages" folder = default */
	load_plugin_textdomain( $gwat_textdomain, FALSE, GWAT_LANG_DIR );


	/** Check for activated Genesis Framework (= template/parent theme) */
	if ( basename( get_template_directory() ) == 'genesis' ) {

		/** Register additional widget areas */
		require_once( GWAT_PLUGIN_DIR . 'includes/gwat-widget-areas.php' );

	}  // end if Genesis check

}  // end of function ddw_gwat_init


add_action( 'init', 'ddw_gwat_setup' );
/**
 * Additional plugin setup:
 *    - Define helper constants.
 *    - Load required files for admin and frontend.
 *
 * @since 1.0.0
 *
 * @uses  is_admin()
 * @uses  current_user_can()
 */
function ddw_gwat_setup() {

	/** Define constants and set defaults for removing all or certain sections */
	if ( ! defined( 'GWAT_NO_SECOND_WIDGET_AREA' ) ) {
		define( 'GWAT_NO_SECOND_WIDGET_AREA', FALSE );
	}

	if ( ! defined( 'GWAT_NO_THIRD_WIDGET_AREA' ) ) {
		define( 'GWAT_NO_THIRD_WIDGET_AREA', FALSE );
	}

	if ( ! defined( 'GWAT_NO_WIDGETS_SHORTCODE' ) ) {
		define( 'GWAT_NO_WIDGETS_SHORTCODE', FALSE );
	}

	/** Load the admin and frontend functions only when needed */
	if ( is_admin() ) {

		require_once( GWAT_PLUGIN_DIR . 'includes/gwat-admin.php' );

	} else {

		require_once( GWAT_PLUGIN_DIR . 'includes/gwat-frontend.php' );

	}  // end if is_admin() check

	/** Add "Widgets Page" link to plugin page */
	if ( is_admin() && current_user_can( 'edit_theme_options' ) ) {

		add_filter(
			'plugin_action_links_' . plugin_basename( __FILE__ ),
			'ddw_gwat_widgets_page_link'
		);

	}  // end if

}  // end of function ddw_gwat_setup


/**
 * Returns current plugin's header data in a flexible way.
 *
 * @since  1.0.0
 *
 * @uses   is_admin()
 * @uses   get_plugins()
 * @uses   plugin_basename()
 *
 * @param  string 	$gwat_plugin_value
 *
 * @return string Plugin data.
 */
function ddw_gwat_plugin_get_data( $gwat_plugin_value ) {

	/** Bail early if we are not in wp-admin */
	if ( ! is_admin() ) {
		return;
	}

	/** Include WordPress plugin data */
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}

	$gwat_plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$gwat_plugin_file = basename( ( __FILE__ ) );

	return $gwat_plugin_folder[ $gwat_plugin_file ][ $gwat_plugin_value ];

}  // end of function ddw_gwat_plugin_get_data