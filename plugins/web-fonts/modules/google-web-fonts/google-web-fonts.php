<?php
/*
 Plugin Name: Web Fonts - Google Web Fonts
 Plugin URI: http://www.google.com/webfonts
 Description: A plugin for the Web Fonts plugin produced by Fonts.com and Monotype Imaging. This built in plugin adds Google Web Fonts support to the Web Fonts plugin.
 Version: 1.1.6
 Author: Nick Ohrn of Plugin-Developer.com
 Author URI: http://plugin-developer.com/
 */

if(!class_exists('Google_Web_Fonts_Plugin')) {
	class Google_Web_Fonts_Plugin {

		/// KEYS

		//// VERSION
		const VERSION = '1.1.6';

		//// KEYS
		const SETTINGS_KEY = '_google_web_fonts_settings';

		const FONT_DATA_KEY = '_google_web_fonts_fonts';
		const SELECTOR_DATA_KEY = '_google_web_fonts_selectors';
		const SORTABLE_FONT_TRANSIENT_BASE = '_google_web_fonts_fonts_';
		const SORTABLE_FONT_TRANSIENT_TIMEOUT = 600;

		/// DATA STORAGE
		public static $admin_page_hooks = array();
		private static $default_settings = array();
		private static $variants_map = array();

		public static function init() {
			self::add_actions();
			self::add_filters();
			self::initialize_defaults();

			if(function_exists('register_web_fonts_provider')) {
				register_web_fonts_provider('Google_Web_Fonts_Provider');
			}
		}

		private static function add_actions() {
			if(is_admin()) {
				add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_administrative_resources'));
				add_action('web_fonts_manage_stylesheet_fonts', array(__CLASS__, 'handle_stylesheet_fonts'));
				add_action('web_fonts_manage_stylesheet_selectors', array(__CLASS__, 'handle_stylesheet_selectors'));
			} else {
				add_action('wp_head', array(__CLASS__, 'enqueue_frontend_resources'), 1);
				add_action('wp_head', array(__CLASS__, 'display_styles'), 11);
			}

			add_action('wp_ajax_web_fonts_google_web_fonts_clear_key', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_google_web_fonts_set_key', array(__CLASS__, 'ajax_container'));

			add_action('wp_ajax_web_fonts_google_web_fonts_get_fonts', array(__CLASS__, 'ajax_container'));
			add_action('wp_ajax_web_fonts_google_web_fonts_set_font_status', array(__CLASS__, 'ajax_container'));
		}

		private static function add_filters() {
			add_filter('web_fonts_manage_stylesheet_fonts_and_selectors', array(__CLASS__, 'add_stylesheet_fonts_and_selectors'));
		}

		private static function initialize_defaults() {
			self::$variants_map['100'] = __('Ultra-Light');
			self::$variants_map['100italic'] = __('Ultra-Light Italic');
			self::$variants_map['200'] = __('Light');
			self::$variants_map['200italic'] = __('Light Italic');
			self::$variants_map['300'] = __('Book');
			self::$variants_map['300italic'] = __('Book Italic');
			self::$variants_map['400'] = __('Normal');
			self::$variants_map['400italic'] = __('Normal Italic');
			self::$variants_map['500'] = __('Normal');
			self::$variants_map['500italic'] = __('Normal Italic');
			self::$variants_map['600'] = __('Semi-Bold');
			self::$variants_map['600italic'] = __('Semi-Bold Italic');
			self::$variants_map['700'] = __('Bold');
			self::$variants_map['700italic'] = __('Bold Italic');
			self::$variants_map['800'] = __('Extra-Bold');
			self::$variants_map['800italic'] = __('Extra-Bold Italic');
			self::$variants_map['900'] = __('Ultra-Bold');
			self::$variants_map['900italic'] = __('Ultra-Bold Italic');
			self::$variants_map['bold'] = __('Bold');
			self::$variants_map['bolditalic'] = __('Bold Italic');
			self::$variants_map['italic'] = __('Normal');
			self::$variants_map['regular'] = __('Normal');

		}

		/// AJAX CALLBACKS

		public static function ajax_container() {
			$data = self::trim_r(stripslashes_deep($_REQUEST));
			$action = str_replace('web_fonts_google_web_fonts_', '', $data['action']);
			$method_name = "ajax_{$action}";

			if(isset($data['nonce']) && wp_verify_nonce($data['nonce'], 'google-web-fonts-action') && method_exists(__CLASS__, $method_name)) {
				$results = self::$method_name($data);
			} else {
				$results = self::get_response(array(), __('Something went wrong. Please refresh the page and try again.'), true);
			}

			header('Content-Type: application/json');
			echo json_encode($results);
			exit;
		}

		public static function ajax_clear_key($data) {
			self::set_settings(array());

			return self::get_response(array(), __('The authentication key was successfully cleared.'));
		}

		public static function ajax_set_key($data) {
			$fonts = self::get_fonts($data['key'], '', 'alpha', 0, 1, true);

			if(is_wp_error($fonts)) {
				$results = self::get_response(array(), __('Your API Key could not be validated. Please enter a valid key.'), true);
			} else {
				self::set_settings(array('api-key' => $data['key']));

				$results = self::get_response(array('key' => $data['key']), __('Your API Key has been validated and saved.'), false);
			}

			return $results;
		}

		public static function ajax_get_fonts($data) {
			$page = isset($data['page_number']) && is_numeric($data['page_number']) && $data['page_number'] >= 1 ? (intval($data['page_number']) - 1) : 0;
			$page_limit = 12;

			$search_keyword = $data['search_keyword'];
			$search_sort = $data['search_sort'];
			$settings = self::get_settings();

			$font_search_response = self::get_fonts($settings['api-key'], $search_keyword, $search_sort, $page, $page_limit);

			if(is_wp_error($font_search_response)) {
				$results = self::get_response(null, __('There was an issue retrieving the appropriate fonts. Please try again.'), true);
			} else {
				$results = self::get_response($font_search_response);
			}

			return $results;
		}

		public static function ajax_set_font_status($data) {
			$enabled = ($data['enabled'] == 1);
			$font_data = $data['font_data'];
			$font_key = sanitize_title_with_dashes($font_data['family_name']);

			$enabled_fonts = self::get_font_data();

			if($enabled) {
				$enabled_fonts[$font_key] = $font_data;
			} else {
				unset($enabled_fonts[$font_key]);
			}

			self::set_font_data($enabled_fonts);

			$results = self::get_response(array('enabled' => $enabled, 'font_data' => $font_data, 'enabled_fonts' => $enabled_fonts), sprintf(__('The selected font was %s.'), ($enabled ? __('enabled') : __('disabled'))));

			return $results;
		}

		/// CALLBACKS

		public static function add_stylesheet_fonts_and_selectors($data) {
			$enabled_fonts = self::get_font_data();
			$enabled_selectors = self::get_selector_data();

			$font_selector_map = array();

			foreach($enabled_selectors as $enabled_selector_id => $enabled_selector) {
				$data['selectors'][] = $prepared_selector = web_fonts_prepare_selector_item('google-web-fonts', $enabled_selector_id, $enabled_selector['tag'], $enabled_selector['fallback'], 'google-web-fonts-' . $enabled_selector['font-id']);

				if(!empty($enabled_selector['font-id']) && isset($enabled_fonts[$enabled_selector['font-id']])) {
					if(!is_array($font_selector_map[$enabled_selector['font-id']])) {
						$font_selector_map[$enabled_selector['font-id']] = array();
					}
					$font_selector_map[$enabled_selector['font-id']][] = $prepared_selector;
				}
			}

			foreach($enabled_fonts as $enabled_font_id => $enabled_font) {
				$data['fonts'][] = web_fonts_prepare_font_item('google-web-fonts', $enabled_font_id, $enabled_font['family_name'], $enabled_font['family'], 'Quick Brown Fox Jumped Over The Lazy Dog', $font_selector_map[$enabled_font_id], array('fontFamily' => $enabled_font['family'], 'fontStyle' => $enabled_font['style'], 'fontWeight' => $enabled_font['weight']));
			}

			return $data;
		}

		public static function detect_submissions() {
			$data = stripslashes_deep($_REQUEST);
		}

		public static function enqueue_administrative_resources($hook) {
			// This is a kludy hack and I hate it, but oh well - have to do it
			if('toplevel_page_' . Web_Fonts::SETTINGS_PAGE_SLUG === $hook) {
				self::enqueue_frontend_resources();
			}

			if(!in_array($hook, self::$admin_page_hooks)) { return; }

			wp_enqueue_script('google-web-fonts-backend', plugins_url('resources/backend/google-web-fonts.js', __FILE__), array('jquery', 'jquery-form', 'thickbox'), self::VERSION);
			wp_enqueue_style('google-web-fonts-backend', plugins_url('resources/backend/google-web-fonts.css', __FILE__), array('thickbox'), self::VERSION);

			$strings = array(
				'request_in_progress_message' => __('There is already a request in progress. Please wait until the request has completed before trying another action.'),
				'assign_fonts_title' => __('Assign Fonts'),
				'enabled_fonts_title' => __('Enabled Fonts'),
			);

			wp_localize_script('google-web-fonts-backend', 'Google_Web_Fonts_Config', $strings);
		}

		public static function enqueue_frontend_resources() {
			$enabled_fonts = self::get_font_data();

			if(!empty($enabled_fonts)) {
				$url_parts = array();
				foreach($enabled_fonts as $enabled_font) {
					$url_parts[] = $enabled_font['url_param'];
				}

				$url_parts = array_filter($url_parts);
				$url = add_query_arg(array('family' => implode('|', $url_parts)), 'http://fonts.googleapis.com/css');
				wp_enqueue_style('google-web-fonts', $url, array(), self::VERSION);
			}
		}

		public static function handle_stylesheet_fonts($fonts) {
			$selectors_to_save = array();

			foreach($fonts as $font) {
				if('google-web-fonts' == $font->provider && is_array($font->selectors)) {
					foreach($font->selectors as $selector) {
						$selectors_to_save['google-web-fonts-' . $selector->tag] = array(
							'font-id' => $font->id,
							'fallback' => $selector->fallback,
							'tag' => $selector->tag,
						);
					}
				}
			}

			self::set_selector_data($selectors_to_save);

			return $fonts;
		}

		public static function handle_stylesheet_selectors($selectors) {
			$selectors_to_save = array();

			foreach($selectors as $selector) {
				$font = $selector->font;

				if(is_object($font) && 0 === strpos($font->id, 'google-web-fonts-')) {
					$selectors_to_save['google-web-fonts-' . $selector->tag] = array(
						'font-id' => str_replace('google-web-fonts-', '', $font->id),
						'fallback' => $selector->fallback,
						'tag' => $selector->tag,
					);
				}
			}

			self::set_selector_data($selectors_to_save);

			return $selectors;
		}

		/// DISPLAY CALLBACKS

		public static function display_styles() {
			$font_data = self::get_font_data();
			$selector_data = self::get_selector_data();

			if(!empty($selector_data)) {
				echo "\n<!-- Google Web Fonts Style Declarations -->\n";
				echo '<style type="text/css">';
				foreach($selector_data as $selector) {
					if(isset($font_data[$selector['font-id']])) {
						$font = $font_data[$selector['font-id']];

						printf('%s{font-family: "%s"%s; font-style: %s; font-weight: %d}', $selector['tag'], esc_attr($font['family']), empty($selector['fallback']) ? '' : ',' . esc_attr($selector['fallback']), $font['style'], $font['weight']);
					}
				}
				echo '</style>';
				echo "\n<!-- End Google Web Fonts Style Declarations -->\n";
			}
		}

		public static function display_settings_page() {
			$data = stripslashes_deep($_REQUEST);

			$settings = self::get_settings();
			$is_setup = self::is_setup();

			$base_url = add_query_arg(array('page' => $data['page']), admin_url('admin.php'));
			$valid_tabs = $is_setup ? array('setup', 'fonts') : array('setup');
			$current_tab = in_array($data['tab'], $valid_tabs) ? $data['tab'] : ($is_setup ? 'fonts' : 'setup');

			include('views/backend/settings/_inc/nav.php');

			// Make this dynamic
			switch($current_tab) {
				case 'setup':
					include('views/backend/settings/setup.php');
					break;
				case 'fonts':
					include('views/backend/settings/fonts.php');
					break;
			}
		}

		/// FONTS

		private static function get_font_data() {
			$font_data = wp_cache_get(self::FONT_DATA_KEY);

			if(!is_array($selector_data)) {
				$font_data = get_option(self::FONT_DATA_KEY, array());
				wp_cache_set(self::FONT_DATA_KEY, $font_data, null, time() + CACHE_PERIOD);
			}

			return $font_data;
		}

		private static function set_font_data($font_data) {
			if(is_array($font_data)) {
				update_option(self::FONT_DATA_KEY, $font_data);
				wp_cache_set(self::FONT_DATA_KEY, $font_data, null, time() + CACHE_PERIOD);
			}
		}

		/// SELECTORS

		private static function get_selector_data() {
			$selector_data = wp_cache_get(self::SELECTOR_DATA_KEY);

			if(!is_array($selector_data)) {
				$selector_data = get_option(self::SELECTOR_DATA_KEY, array());
				wp_cache_set(self::SELECTOR_DATA_KEY, $selector_data, null, time() + CACHE_PERIOD);
			}

			return $selector_data;
		}

		private static function set_selector_data($selector_data) {
			if(is_array($selector_data)) {
				update_option(self::SELECTOR_DATA_KEY, $selector_data);
				wp_cache_set(self::SELECTOR_DATA_KEY, $selector_data, null, time() + CACHE_PERIOD);
			}
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

		/// UTILITY

		private static function get_response($data = array(), $message = null, $error = false) {
			return array_merge(array('error' => (bool)$error, 'message' => $message), (array)$data);
		}

		private static function is_setup() {
			$settings = self::get_settings();

			return !empty($settings['api-key']);
		}

		private static function trim_r($data) {
			if(is_array($data)) {
				$trimmed = array();
				foreach($data as $key => $value) {
					$trimmed[$key] = self::trim_r($value);
				}
				return $trimmed;
			} else {
				return trim($data);
			}
		}

		/// API

		private static function SORTABLE_FONT_TRANSIENT_BASE($sort) {
			return self::SORTABLE_FONT_TRANSIENT_BASE . $sort;
		}

		private static function get_api($api_key = null) {
			require_once('lib/wp-google-web-fonts-api.php');

			return new WP_Google_Web_Fonts_API($api_key);
		}

		private static function get_fonts($api_key, $search_keyword = '', $search_sort = 'alpha', $page = 0, $page_limit = 12, $fresh = false) {
			if(!self::is_valid_sort($search_sort)) {
				$search_sort = 'alpha';
			}

			$key = self::SORTABLE_FONT_TRANSIENT_BASE($search_sort);

			if(!$fresh) {
				$fonts = get_transient($key);
			}

			if(!is_array($fonts)) {
				$api = self::get_api($api_key);
				$fonts = $api->get_fonts($search_sort);

				if(is_array($fonts)) {
					$fonts = self::separate_fonts($fonts);
					set_transient($key, $fonts, self::SORTABLE_FONT_TRANSIENT_TIMEOUT);
				}
			}

			if(is_array($fonts)) {

				if(!empty($search_keyword)) {
					$filtered_fonts = array();

					foreach($fonts as $font) {
						if(false !== strpos($font->family_name, $search_keyword)) {
							$filtered_fonts[] = $font;
						}
					}

					$fonts = $filtered_fonts;
				}

				foreach($fonts as $font) {
					$font->is_enabled = self::is_font_enabled($font);
				}

				// This has to stay here before we array_slice
				$number_fonts = count($fonts);

				$enabled_fonts = self::get_font_data();
				$fonts = array_slice($fonts, ($page * $page_limit), $page_limit);
				$pagination_links = paginate_links(array(
					'base' => admin_url('admin-ajax.php') . '%_%', // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
					'format' => '?page_number=%#%', // ?page=%#% : %#% is replaced by the page number
					'total' => ceil($number_fonts / $page_limit),
					'current' => ($page + 1),
					'show_all' => false,
					'prev_next' => true,
					'prev_text' => __('&laquo; Previous'),
					'next_text' => __('Next &raquo;'),
					'end_size' => 2,
					'mid_size' => 3,
					'type' => 'plain',
					'add_args' => array(), // array of query args to add
					'add_fragment' => ''
				));

				return compact('enabled_fonts', 'fonts', 'number_fonts', 'pagination_links');
			} else {
				return $fonts;
			}
		}

		private static function is_valid_sort($sort) {
			return in_array($sort, array('alpha', 'date', 'popularity', 'style', 'trending'));
		}

		private static function separate_fonts($fonts) {
			$separated_fonts = array();

			foreach($fonts as $font) {
				$variants = $font->variants;
				unset($font->variants);

				foreach($variants as $variant) {
					$separated_fonts[] = self::add_variant_properties($font, $variant);
				}
			}

			return $separated_fonts;
		}

		private static function add_variant_properties($font, $variant) {
			if('bold' === $variant || 'bolditalic' === $variant) {
				$weight = 700;
			} else if('regular' === $variant || 'italic' === $variant) {
				$weight = 400;
			} else {
				$weight = preg_replace('/[^\d]/', '', $variant);
			}

			$a_font = clone $font;
			$a_font->family_name = sprintf('%s %s', $a_font->family, isset(self::$variants_map[$variant]) ? self::$variants_map[$variant] : __('Normal'));
			$a_font->id = sanitize_title_with_dashes($a_font->family_name);
			$a_font->style = strpos($variant, 'italic') === false ? 'normal' : 'italic';
			$a_font->style_string = ('italic' === $a_font->style) ? __('Italic') : __('Normal');
			$a_font->url_param = sprintf('%s:%s', str_replace(' ', '+', $a_font->family), $variant);
			$a_font->variant = $variant;
			$a_font->weight = $weight;
			$a_font->weight_string = self::get_weight_string($a_font->weight);

			return $a_font;
		}

		private static function is_font_enabled($font) {
			$enabled_fonts = self::get_font_data();

			return isset($enabled_fonts[$font->id]);
		}

		private static function get_weight_string($weight) {
			return isset(self::$variants_map[$weight]) ? self::$variants_map[$weight] : __('Normal');
		}
	}

	require_once('lib/provider.php');
	Google_Web_Fonts_Plugin::init();
}