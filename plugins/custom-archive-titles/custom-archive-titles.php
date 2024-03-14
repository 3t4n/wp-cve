<?php
/*
Plugin Name: Custom Archive Titles
Plugin URI: https://themezee.com/plugins/custom-archive-titles/
Description: A small and simple plugin to adjust the default texts of archive titles in WordPress
Author: ThemeZee
Author URI: https://themezee.com/
Version: 1.1
Text Domain: custom-archive-titles
Domain Path: /languages/
License: GPL v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

ThemeZee Custom Archive Titles
Copyright(C) 2017, ThemeZee.com - support@themezee.com

*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Main ThemeZee_Custom_Archive_Titles Class
 *
 * @package ThemeZee Custom Archive Titles
 */
class ThemeZee_Custom_Archive_Titles {

	/**
	 * Call all Functions to setup the Plugin
	 *
	 * @uses ThemeZee_Custom_Archive_Titles::constants() Setup the constants needed
	 * @uses ThemeZee_Custom_Archive_Titles::includes() Include the required files
	 * @uses ThemeZee_Custom_Archive_Titles::setup_actions() Setup the hooks and actions
	 * @return void
	 */
	static function setup() {

		// Setup Constants.
		self::constants();

		// Setup Translation.
		add_action( 'plugins_loaded', array( __CLASS__, 'translation' ) );

		// Include Files.
		self::includes();

		// Setup Action Hooks.
		self::setup_actions();

	}

	/**
	 * Setup plugin constants
	 *
	 * @return void
	 */
	static function constants() {

		// Define Plugin Name.
		define( 'TZCAT_NAME', 'Custom Archive Titles' );

		// Define Version Number.
		define( 'TZCAT_VERSION', '1.1' );

		// Plugin Folder Path.
		define( 'TZCAT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

		// Plugin Folder URL.
		define( 'TZCAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

		// Plugin Root File.
		define( 'TZCAT_PLUGIN_FILE', __FILE__ );

	}

	/**
	 * Load Translation File
	 *
	 * @return void
	 */
	static function translation() {

		load_plugin_textdomain( 'custom-archive-titles', false, dirname( plugin_basename( TZCAT_PLUGIN_FILE ) ) . '/languages/' );

	}

	/**
	 * Include required files
	 *
	 * @return void
	 */
	static function includes() {

		// Include Settings Classes.
		require_once TZCAT_PLUGIN_DIR . '/includes/class-tzcat-settings.php';
		require_once TZCAT_PLUGIN_DIR . '/includes/class-tzcat-settings-page.php';

	}

	/**
	 * Setup Action Hooks
	 *
	 * @see https://codex.wordpress.org/Function_Reference/add_action WordPress Codex
	 * @return void
	 */
	static function setup_actions() {

		// Change Archive Titles based on user settings.
		add_filter( 'get_the_archive_title', array( __CLASS__, 'custom_archive_titles' ) );

		// Add Settings link to Plugin actions.
		add_filter( 'plugin_action_links_' . plugin_basename( TZCAT_PLUGIN_FILE ), array( __CLASS__, 'plugin_action_links' ) );

	}

	/**
	* Filter the default archive title.
	*
	* @param string $title Archive title.
	* @return string $title
	*/
	static function custom_archive_titles( $title ) {

		// Get Settings.
		$instance = TZCAT_Settings::instance();
		$options = $instance->get_all();

		// Get default settings.
		$default_settings = $instance->default_settings();

		// Change Archive Titles.
		if ( is_category() && $default_settings['category_title'] !== $options['category_title'] ) {

			// Change Category Archive Title.
			$title = sprintf( esc_html( $options['category_title'] ), single_cat_title( '', false ) );

		} elseif ( is_tag() && $default_settings['tag_title'] !== $options['tag_title'] ) {

			// Change Tag Archive Title.
			$title = sprintf( esc_html( $options['tag_title'] ), single_tag_title( '', false ) );

		} elseif ( is_author() && $default_settings['author_title'] !== $options['author_title'] ) {

			// Change Author Archive Title.
			$title = sprintf( esc_html( $options['author_title'] ), '<span class="vcard">' . get_the_author() . '</span>' );

		} elseif ( is_year() && $default_settings['year_title'] !== $options['year_title'] ) {

			// Change Yearly Archive Title.
			$title = sprintf( esc_html( $options['year_title'] ), get_the_date( _x( 'Y', 'yearly archives date format' ) ) );

		} elseif ( is_month() && $default_settings['month_title'] !== $options['month_title'] ) {

			// Change Monthly Archive Title.
			$title = sprintf( esc_html( $options['month_title'] ), get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );

		} elseif ( is_day() && $default_settings['day_title'] !== $options['day_title'] ) {

			// Change Daily Archive Title.
			$title = sprintf( esc_html( $options['day_title'] ), get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) );

		}

		return $title;
	}

	/**
	 * Add Settings link to the plugin actions
	 *
	 * @return array $actions Plugin action links
	 */
	static function plugin_action_links( $actions ) {

		$settings_link = array( 'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=themezee-custom-archive-titles' ), __( 'Settings', 'custom-archive-titles' ) ) );

		return array_merge( $settings_link, $actions );
	}
}

// Run Plugin.
ThemeZee_Custom_Archive_Titles::setup();
