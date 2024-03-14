<?php
/**
 * Reamaze Admin Settings.
 *
 * @author      Reamaze
 * @category    Admin
 * @package     Reamaze/Admin
 * @version     2.3.2
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (!class_exists('Reamaze_Admin_Settings')) :

/**
 * Reamaze_Admin_Settings
 */
class Reamaze_Admin_Settings {
	private static $settings = array();
	private static $errors   = array();
	private static $messages = array();

	/**
	 * Get settings pages
	 */
	public static function get_settings_pages() {
		if (empty(self::$settings)) {
			$settings = array();

			include_once('settings/reamaze-settings-page.php');

			$settings[] = include('settings/reamaze-settings-account.php');
			$settings[] = include('settings/reamaze-settings-widget.php');
			$settings[] = include('settings/reamaze-settings-personal.php');

			self::$settings = $settings;
		}

		return self::$settings;
	}

	/**
	 * Save settings
	 */
	public static function save() {
		global $current_tab;

		if (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'], 'reamaze-settings')) {
			die(__('Something went wrong. Please try again.', 'reamaze'));
		}

		// Trigger actions
		do_action('reamaze_settings_save_' . $current_tab);
//		do_action('reamaze_update_options_' . $current_tab);
//		do_action('reamaze_update_options');

		self::add_message(__('Settings saved.', 'reamaze'));

//		do_action('reamaze_settings_saved');
	}

	/**
	 * Add a message
	 * @param string $text
	 */
	public static function add_message($text) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error
	 * @param string $text
	 */
	public static function add_error($text) {
		self::$errors[] = $text;
	}

	/**
	 * Output messages + errors
	 * @return string
	 */
	public static function show_messages() {
		if (sizeof(self::$errors) > 0) {
			foreach (self::$errors as $error) {
				echo '<div id="message" class="error fade"><p><strong>' . esc_html($error) . '</strong></p></div>';
			}
		} elseif (sizeof(self::$messages) > 0) {
			foreach (self::$messages as $message) {
				echo '<div id="message" class="updated fade"><p><strong>' . esc_html($message) . '</strong></p></div>';
			}
		}
	}

	/**
	 * Settings page.
	 *
	 * Displays Reamaze Settings Page
	 */
	public static function output() {
		global $current_tab;

		// Include settings pages
		self::get_settings_pages();

		// Get current tab/section
		$current_tab     = empty($_GET['tab']) ? 'account' : sanitize_title($_GET['tab']);

		// Save settings if data has been posted
		if (! empty($_POST)) {
			self::save();
		}

		// Add any posted messages
		if (! empty($_GET['reamaze_error'])) {
			self::add_error(stripslashes(sanitize_textarea_field($_GET['reamaze_error'])));
		}

		if (! empty($_GET['reamaze_message'])) {
			self::add_message(stripslashes(sanitize_textarea_field($_GET['reamaze_message'])));
		}

		self::show_messages();

		// Get tabs for the settings page
		$tabs = apply_filters('reamaze_settings_tabs_array', array());

		include 'views/admin-settings.php';
	}
}

endif;
