<?php
/*
 Plugin Name: Web Fonts
 Plugin URI: http://webfonts.fonts.com
 Description: The canonical Web Fonts plugin for WordPress. This plugin includes support for Fonts.com and Google web fonts and supports a variety of web font providers via an easy to implement plugin architecture.
 Version: 1.1.6
 Author: Nick Ohrn of Plugin-Developer.com
 Author URI: http://plugin-developer.com/
 */

if(!class_exists('Web_Fonts')) {
	class Web_Fonts {
		/// CONSTANTS

		//// VERSION
		const VERSION = '1.1.6';

		//// KEYS
		const SETTINGS_KEY = '_web_fonts_settings';
		const SAVED_TIMESTAMP_KEY = '_web_fonts_stylesheet_saved_timestamp';

		//// SLUGS
		const SETTINGS_PAGE_SLUG = 'web-fonts';

		//// CACHE
		const CACHE_PERIOD = 86400; // 24 HOURS

		/// DATA STORAGE
		private static $admin_page_hooks = array();
		private static $registered_providers = array();

		public static function init() {
			self::add_actions();
			self::add_filters();
		}

		private static function add_actions() {
			if(is_admin()) {
				add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_administrative_resources'));
				add_action('admin_menu', array(__CLASS__, 'add_administrative_interface_items'));
			}
		}

		private static function add_filters() {

		}

		/// CALLBACKS

		public static function add_administrative_interface_items() {
			if(!empty(self::$registered_providers)) {
				self::$admin_page_hooks[] = $settings = add_menu_page(__('Web Fonts'), __('Web Fonts'), 'manage_options', self::SETTINGS_PAGE_SLUG, array(__CLASS__, 'display_stylesheet_page'), plugins_url('resources/backend/img/web-fonts-16.png', __FILE__));
				self::$admin_page_hooks[] = $stylesheet = add_submenu_page(self::SETTINGS_PAGE_SLUG, __('Manage Web Font Stylesheet'), __('Manage Stylesheet'), 'manage_options', 'web-fonts', array(__CLASS__, 'display_stylesheet_page'));

				add_action("load-{$stylesheet}", array(__CLASS__, 'process_manage_stylesheet'));

				foreach(self::$registered_providers as $provider_class_name) {
					$provider_name = call_user_func(array($provider_class_name, 'get_provider_name'));

					self::$admin_page_hooks[] = $menu_slug = add_submenu_page(self::SETTINGS_PAGE_SLUG, sprintf(__('Web Fonts - %s'), $provider_name), $provider_name, 'manage_options', self::get_page_slug_for_provider($provider_class_name), array(__CLASS__, 'display_settings_page'));

					call_user_func(array($provider_class_name, 'settings_page_registered'), $menu_slug);
				}
			}
		}

		public static function enqueue_administrative_resources($hook) {
			if(!in_array($hook, self::$admin_page_hooks)) { return; }

			wp_enqueue_script('knockout', plugins_url('resources/backend/knockout.js', __FILE__), array(), '2.0.0');
			wp_enqueue_script('web-fonts-backend', plugins_url('resources/backend/web-fonts.js', __FILE__), array('jquery', 'knockout'), self::VERSION);
			wp_enqueue_style('web-fonts-backend', plugins_url('resources/backend/web-fonts.css', __FILE__), array(), self::VERSION);
		}

		public static function process_manage_stylesheet() {
			$data = stripslashes_deep($_REQUEST);

			if(isset($data['web-fonts-manage-stylesheet-nonce']) && wp_verify_nonce($data['web-fonts-manage-stylesheet-nonce'], 'web-fonts-manage-stylesheet')) {
				$stylesheet_data = json_decode($data['web-fonts-stylesheet-data']);

				do_action("web_fonts_manage_stylesheet_{$stylesheet_data->visible_tab}s", $stylesheet_data->{"{$stylesheet_data->visible_tab}s"});

				add_settings_error('general', 'stylesheet_updated', __('Stylesheet updated!'), 'updated');
				set_transient('settings_errors', get_settings_errors(), 30);

				self::set_stylesheet_timestamp(current_time('timestamp'));

				wp_redirect(add_query_arg(array('page' => 'web-fonts', 'settings-updated' => 'true'), admin_url('admin.php')));
				exit;
			}
		}

		/// DISPLAY CALLBACKS

		public static function display_stylesheet_page() {
			include('views/backend/settings/overview.php');
		}

		public static function display_settings_page() {
			$data = stripslashes_deep($_GET);

			$provider_key = str_replace('web-fonts-', '', $data['page']);
			$provider_class_name = self::$registered_providers[$provider_key];

			include('views/backend/settings/settings.php');
		}

		/// SETTINGS

		private static function get_settings() {
			$settings = wp_cache_get(self::SETTINGS_KEY);

			if(!is_array($settings)) {
				$settings = wp_parse_args(get_option(self::SETTINGS_KEY, self::$default_settings), self::$default_settings);
				wp_cache_set(self::SETTINGS_KEY, $settings, null, time() + CACHE_PERIOD);
			}

			return $settings;
		}

		private static function set_settings($settings) {
			if(is_array($settings)) {
				$settings = wp_parse_args($settings, self::$default_settings);
				update_option(self::SETTINGS_KEY, $settings);
				wp_cache_set(self::SETTINGS_KEY, $settings, null, time() + CACHE_PERIOD);
			}
		}

		private static function get_stylesheet_timestamp() {
			$timestamp = wp_cache_get(self::SAVED_TIMESTAMP_KEY);

			if(!is_array($timestamp)) {
				$timestamp = get_option(self::SAVED_TIMESTAMP_KEY, time());
				wp_cache_set(self::SAVED_TIMESTAMP_KEY, $timestamp, null, time() + CACHE_PERIOD);
			}

			return $timestamp;
		}

		private static function set_stylesheet_timestamp($timestamp) {
			if(!empty($timestamp)) {
				update_option(self::SAVED_TIMESTAMP_KEY, $timestamp);
				wp_cache_set(self::SAVED_TIMESTAMP_KEY, $timestamp, null, time() + CACHE_PERIOD);
			}
		}

		/// UTILITY

		private static function get_page_slug_for_provider($provider_class_name) {
			return 'web-fonts-' . call_user_func(array($provider_class_name, 'get_provider_key'));
		}

		/// TEMPLATE TAGS

		public static function get_last_saved_stylesheet_timestamp() {
			return self::get_stylesheet_timestamp();
		}

		public static function register_web_fonts_provider($provider_class_name) {
			if(class_exists($provider_class_name) && is_subclass_of($provider_class_name, 'Web_Fonts_Provider') && !in_array($provider_class_name, self::$registered_providers)) {
				$provider_key = call_user_func(array($provider_class_name, 'get_provider_key'));

				self::$registered_providers[$provider_key] = $provider_class_name;
			}

			return self::$registered_providers;
		}
	}

	require_once('lib/provider.php');
	require_once('lib/template-tags.php');

	// Include by default the Fonts.com provider plugin
	require_once('modules/fonts-com/fonts-com.php');

	// Include by default the Google Web Fonts provider plugin
	require_once('modules/google-web-fonts/google-web-fonts.php');

	Web_Fonts::init();
}
