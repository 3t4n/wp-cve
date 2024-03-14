<?php
/**
 * Main plugin file. This plugin is adding a printer-ready stylesheet file for
 *    the Genesis Framework and its currently active child theme.
 *
 * @package     Genesis Printstyle Plus
 * @author      David Decker
 * @copyright   Copyright (c) 2011-2014, David Decker - DECKERWEB
 * @license     GPL-2.0+
 * @link        http://deckerweb.de/twitter
 *
 * @wordpress-plugin
 * Plugin Name: Genesis Printstyle Plus
 * Plugin URI:  http://genesisthemes.de/en/wp-plugins/genesis-printstyle-plus/
 * Description: This plugin is adding a printer-ready stylesheet file for the Genesis Framework and its currently active child theme.
 * Version:     1.9.3
 * Author:      David Decker - DECKERWEB
 * Author URI:  http://deckerweb.de/
 * License:     GPL-2.0+
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 * Text Domain: genesis-printstyle-plus
 * Domain Path: /languages/
 *
 * Copyright (c) 2011-2014 David Decker - DECKERWEB
 *
 *     This file is part of Genesis Printstyle Plus,
 *     a plugin for WordPress.
 *
 *     Genesis Printstyle Plus is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     Genesis Printstyle Plus is distributed in the hope that
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
 * @since 1.9.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


/**
 * Setting constants.
 *
 * @since 1.2.0
 */
/** Set plugin version */
define( 'GPSP_VERSION', ddw_gpsp_plugin_get_data( 'Version' ) );

/** Plugin directory */
define( 'GPSP_PLUGIN_DIR', trailingslashit( dirname( __FILE__ ) ) );

/** Plugin base directory */
define( 'GPSP_PLUGIN_BASEDIR', trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );


register_activation_hook( __FILE__, 'ddw_gpsp_activation_check' );
/**
 * Checks for activated Genesis Framework and its minimum version before allowing plugin to activate.
 *
 * @since 1.2.0
 *
 * @uses  load_plugin_textdomain()
 * @uses  get_template_directory()
 * @uses  deactivate_plugins()
 * @uses  wp_die()
 */
function ddw_gpsp_activation_check() {

	/** Load translations to display for the activation message. */
	load_plugin_textdomain( 'genesis-printstyle-plus', FALSE, GPSP_PLUGIN_BASEDIR . 'languages' );

	/** Check for activated Genesis Framework (= template/parent theme) */
	if ( basename( get_template_directory() ) != 'genesis' ) {

		/** If no Genesis, deactivate ourself */
		deactivate_plugins( plugin_basename( __FILE__ ) );

		/** Message: no Genesis active */
		$gpsp_genesis_deactivation_message = sprintf(
			__( 'Sorry, you cannot activate the %1$s plugin unless you have installed the latest version of the %2$sGenesis Framework%3$s.', 'genesis-printstyle-plus' ),
			__( 'Genesis Printstyle Plus', 'genesis-printstyle-plus' ),
			'<a href="http://deckerweb.de/go/genesis/" target="_new"><strong><em>',
			'</em></strong></a>'
		);

		/** Deactivation message */
		wp_die(
			$gpsp_genesis_deactivation_message,
			__( 'Plugin', 'genesis-printstyle-plus' ) . ': ' . __( 'Genesis Printstyle Plus', 'genesis-printstyle-plus' ),
			array( 'back_link' => true )
		);

	}  // end-if Genesis check

}  // end of function ddw_gpsp_activation_check


add_action( 'init', 'ddw_gpsp_init' );
/**
 * Load the text domain for translation of the plugin.
 * Load admin helper functions - only within 'wp-admin'.
 * 
 * @since 1.1.0
 *
 * @uses  is_admin()
 * @uses  load_textdomain()	To load translations first from WP_LANG_DIR sub folder.
 * @uses  load_plugin_textdomain() To additionally load default translations from plugin folder (default).
 */
function ddw_gpsp_init() {

	/** If 'wp-admin' include admin helper functions */
	if ( is_admin() ) {

		/** Set unique textdomain string */
		$gpsp_textdomain = 'genesis-printstyle-plus';

		/** The 'plugin_locale' filter is also used by default in load_plugin_textdomain() */
		$locale = apply_filters( 'plugin_locale', get_locale(), $gpsp_textdomain );

		/** Set filter for WordPress languages directory */
		$gpsp_wp_lang_dir = apply_filters(
			'gpsp_filter_wp_lang_dir',
			trailingslashit( WP_LANG_DIR ) . 'genesis-printstyle-plus/' . $gpsp_textdomain . '-' . $locale . '.mo'
		);

		/** Translations: First, look in WordPress' "languages" folder = custom & update-secure! */
		load_textdomain( $gpsp_textdomain, $gpsp_wp_lang_dir );

		/** Translations: Secondly, look in plugin's "languages" folder = default */
		load_plugin_textdomain( $gpsp_textdomain, FALSE, GPSP_PLUGIN_BASEDIR . 'languages' );


		/** Load admin extras */
		require_once( GPSP_PLUGIN_DIR . 'includes/gpsp-admin-extras.php' );

	}  // end-if is_admin() check

	/** Otherwise, include our frontend hook */
	else {

		add_action( 'wp_enqueue_scripts', 'ddw_gpsp_add_hook' );

	}  // end if/else is_admin() check

}  // end of function ddw_gpsp_init


/**
 * Returns current plugin's header data in a flexible way.
 *
 * @since  1.6.0
 *
 * @uses   is_admin()
 * @uses   get_plugins()
 * @uses   plugin_basename()
 *
 * @param  $gpsp_plugin_value
 *
 * @return string String with plugin data.
 */
function ddw_gpsp_plugin_get_data( $gpsp_plugin_value ) {

	/** Bail early if we are not in wp-admin */
	if ( ! is_admin() ) {

		return;

	}  // end if

	/** Include WordPress plugin data */
	if ( ! function_exists( 'get_plugins' ) ) {

		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	}  // end if

	$gpsp_plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$gpsp_plugin_file = basename( ( __FILE__ ) );

	return $gpsp_plugin_folder[ $gpsp_plugin_file ][ $gpsp_plugin_value ];

}  // end of function ddw_gpsp_plugin_get_data


/**
 * Include our own hook within 'wp_enqueue_scripts'
 *    to better add or remove plugin & custom stylesheets
 * 
 * @since 1.5.0
 */
function ddw_gpsp_add_hook() {

	/** Action hook: 'gpsp_load_styles' - allows for enqueueing additional custom print styles */
	do_action( 'gpsp_load_styles' );

}  // end of function ddw_gpsp_add_hook


add_action( 'wp_enqueue_scripts', 'ddw_gpsp_printstyle_logic' );
/**
 * Set our own action hook for hooking styles in
 * 
 * @since 1.5.0
 *
 * @uses  get_stylesheet_directory()
 */
function ddw_gpsp_printstyle_logic() {

	/**
	 * At first, look in child theme for custom print stylesheet: 'gpsp-print.css'
	 * If it exists, enqueue it!
	 */
	if ( is_readable( get_stylesheet_directory() . '/gpsp-print.css' ) ) {

		add_action( 'gpsp_load_styles', 'gpsp_custom_printstyle' );

	}

	/** If no custom/user stylesheet exists, enqueue our default plugin's print styles */
	else {

		add_action( 'gpsp_load_styles', 'ddw_gpsp_printstyle', 5 );

		/** If existing in child theme folder, add additional user styles */
		if ( is_readable( get_stylesheet_directory() . '/print-additions.css' ) ) {

			add_action( 'gpsp_load_styles', 'gpsp_printstyle_additions' );

		}  // end-if check for print-additions.css

	} // end if/else stylesheet file checks

}  // end of function ddw_gpsp_printstyle_logic


/**
 * Helper function for returning string for minifying scripts/ stylesheets.
 *
 * @since  1.9.0
 *
 * @return string String for minifying scripts/ stylesheets.
 */
function ddw_gpsp_script_suffix() {
	
	return ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? '' : '.min';

}  // end of function ddw_gpsp_script_suffix


/**
 * Helper function for returning string for versioning scripts/ stylesheets.
 *
 * @since  1.9.0
 *
 * @return string Version string for versioning scripts/ stylesheets.
 */
function ddw_gpsp_script_version() {

	return ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? time() : filemtime( plugin_dir_path( __FILE__ ) );

}  // end of function ddw_gpsp_script_version


/**
 * Conditionally enqueue custom/user print styles.
 * 
 * @since 1.5.0
 *
 * @uses  wp_register_style()
 * @uses  wp_enqueue_style()
 */
function gpsp_custom_printstyle() {

	/** Register custom/user stylesheet */
	wp_register_style(
		'genesis-printstyle-plus-custom',
		get_stylesheet_directory_uri() . '/gpsp-print.css',
		false,
		defined( 'CHILD_THEME_VERSION' ) ? CHILD_THEME_VERSION : ddw_gpsp_script_version(),
		'print'
	);

	/** Enqueue custom/user stylesheet */
	wp_enqueue_style( 'genesis-printstyle-plus-custom' );

}  // end of function gpsp_custom_printstyle


/**
 * Enqueue the default plugin's print stylesheet.
 * 
 * @since 1.0.0
 *
 * @uses  wp_enqueue_style()
 * @uses  wp_enqueue_style()
 */
function ddw_gpsp_printstyle() {

	/** Check for Genesis HTML5 */
	$gpsp_genesis_html = current_theme_supports( 'html5' ) ? '-html5' : '';

	/** Register default print stylesheet */
	wp_register_style(
		'genesis-printstyle-plus',
		plugins_url( 'css/print' . $gpsp_genesis_html . ddw_gpsp_script_suffix() . '.css', __FILE__ ),
		false,
		ddw_gpsp_script_version(),
		'print'
	);

	/** Enqueue default print stylesheet */
	wp_enqueue_style( 'genesis-printstyle-plus' );

}  // end of function ddw_gpsp_printstyle


/**
 * Conditionally enqueue additional print style additions.
 * 
 * @since 1.5.0
 *
 * @uses  wp_register_style()
 * @uses  wp_enqueue_style()
 */
function gpsp_printstyle_additions() {

	/** Register additions stylesheet */
	wp_register_style(
		'genesis-printstyle-plus-additions',
		get_stylesheet_directory_uri() . '/print-additions.css',
		false,
		defined( 'CHILD_THEME_VERSION' ) ? CHILD_THEME_VERSION : ddw_gpsp_script_version(),
		'print'
	);

	/** Enqueue additions stylesheet */
	wp_enqueue_style( 'genesis-printstyle-plus-additions' );

}  // end of function gpsp_printstyle_additions