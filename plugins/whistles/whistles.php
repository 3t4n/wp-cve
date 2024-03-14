<?php
/**
 * Plugin Name: Whistles
 * Plugin URI: http://themehybrid.com/plugins/whistles
 * Description: A plugin for managing tabs, toggles, accordions, and other types of content on a WordPress install.
 * Version: 0.1.1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * A plugin for managing "whistles", which is just an arbitrary name given to content for displaying 
 * in tabs, toggles, accordions, and other types of bells and whistles users want on their sites.  
 * This plugin is a reimagining of how these types of things should by handled by themes.  The plugin 
 * handles the content for portability.  The theme handles the display of the content.  It also gives 
 * near unlimited potential for "whistle" content, only limiting the user by what they can add to the 
 * post editor.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   Whistles
 * @version   0.1.1
 * @since     0.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2013, Justin Tadlock
 * @link      http://themehybrid.com/plugins/whistles
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

final class Whistles_Load {

	/**
	 * Plugin setup.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function setup() {

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( __CLASS__, 'constants' ), 1 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( __CLASS__, 'i18n' ), 2 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( __CLASS__, 'includes' ), 3 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( __CLASS__, 'admin' ), 4 );

		/* Enqueue scripts and styles. */
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		/* Filter current_theme_supports for easier conditionals. */
		add_filter( 'current_theme_supports-whistles', array( __CLASS__, 'current_theme_supports' ), 10, 3 );

		/* Register activation hook. */
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function constants() {

		/* Set constant path to the plugin directory. */
		define( 'WHISTLES_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set the constant path to the plugin directory URI. */
		define( 'WHISTLES_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function includes() {

		require_once( WHISTLES_DIR . 'inc/post-types.php' );
		require_once( WHISTLES_DIR . 'inc/taxonomies.php' );
		require_once( WHISTLES_DIR . 'inc/class-whistles-and-bells.php' );
		require_once( WHISTLES_DIR . 'inc/class-whistles-and-tabs.php' );
		require_once( WHISTLES_DIR . 'inc/class-whistles-and-toggles.php' );
		require_once( WHISTLES_DIR . 'inc/class-whistles-and-accordions.php' );
		require_once( WHISTLES_DIR . 'inc/functions.php' );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function i18n() {

		/* Load the translation of the plugin. */
		load_plugin_textdomain( 'whistles', false, 'whistles/languages' );
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function admin() {

		if ( is_admin() )
			require_once( WHISTLES_DIR . 'admin/admin.php' );
	}

	/**
	 * Loads the stylesheet for the plugin.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function enqueue_scripts() {

		/* Use the .min stylesheet if SCRIPT_DEBUG is turned off. */
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		/* Register the JS file. Load later if needed. */
		wp_register_script( 
			'whistles', 
			WHISTLES_URI . 'js/whistles.min.js', 
			array( 'jquery' ),
			'20130908',
			true
		);

		if ( current_theme_supports( 'whistles', 'styles' ) )
			return;

		/* Enqueue the stylesheet. */
		wp_enqueue_style(
			'whistles',
			trailingslashit( plugin_dir_url( __FILE__ ) ) . "css/whistles$suffix.css",
			null,
			'20130909'
		);
	}

	/**
	 * Filter on 'current_theme_supports-whistles'.  This allows us to check if the theme supports specific 
	 * parts of the whistles plugins like 'scripts' and 'styles'.
	 *
	 * @since  0.1.0
	 * @access public
	 * @param  bool    $supports
	 * @param  array   $args
	 * @param  array   $feature
	 * @return bool
	 */
	public static function current_theme_supports( $supports, $args, $feature ) {

		if ( isset( $args[0] ) ) {

			$check = $args[0];

			if ( in_array( $check, array( 'scripts', 'styles' ) ) ) {

				if ( is_array( $feature[0] ) && isset( $feature[0][ $check ] ) && true === $feature[0][ $check ] )
					$supports = true;
				else
					$supports = false;
			}
		}

		return $supports;
	}

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since  0.1.0
	 * @access public
	 * @return void
	 */
	public static function activation() {

		/* Get the administrator role. */
		$role = get_role( 'administrator' );

		/* If the administrator role exists, add required capabilities for the plugin. */
		if ( !empty( $role ) ) {

			$role->add_cap( 'manage_whistles' );
			$role->add_cap( 'create_whistles' );
			$role->add_cap( 'edit_whistles'   );
		}
	}
}

Whistles_Load::setup();

?>