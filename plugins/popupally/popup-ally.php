<?php
/*
 Plugin Name: PopupAlly
 Plugin URI: https://popupally.com/
 Description: Want to increase your subscriber base? Exit-intent popups allow you to capture lost visitors and have been shown to increase conversion by over 300%. PopupAlly allows you to create advanced popup signup forms in under 5 minutes, even if you don't know code. PopupAlly's visual editor allows you to customize the look-and-feel of your popups with an instant preview, saving you lots of time.
 Version: 2.1.1
 Author: AccessAlly
 Author URI: https://accessally.com/
 */

if (!class_exists('PopupAlly')) {
	class PopupAlly {
		/// CONSTANTS
		const VERSION = '2.1.1';

		const SETTING_KEY_ALL = '_popupally_setting';
		const SETTING_KEY_CODE = '_popupally_setting_code';

		const SETTING_KEY_OPTIN_SUBMIT = '_popupally_optin_submit';

		const HELP_URL = 'https://kb.accessally.com/tutorials/whats-the-difference-between-popupally-pro-and-the-free-version/';

		const SCRIPT_FOLDER = 'popupally-scripts';

		// CACHE
		const CACHE_PERIOD = 86400;

		const TEMPLATE_DIRECTORY = 'template';
		private static $template_folders = array('default', 'clean');

		public static $PLUGIN_URI = '';
		public static $available_templates = array();

		// used for parameter parsing
		private static $config_display_settings = array('cookie-duration', 'priority');
		private static $local_cached_to_show_results = false;

		public static function init() {
			self::$PLUGIN_URI = plugin_dir_url(__FILE__);
			self::add_actions();
			self::add_filters();
			self::load_templates();

			register_activation_hook(__FILE__, array(__CLASS__, 'do_activation_actions'));
			register_deactivation_hook(__FILE__, array(__CLASS__, 'do_deactivation_actions'));
		}
		public static function upgrade_database() {
			/* must be called first because the database version will be updated by the other initialize_defaults calls */
			if (!PopupAllySettingShared::is_database_up_to_date()) {
				// refresh the code stored in the database
				self::get_popup_code(true);
				$code = self::generate_popup_code(true);
				self::generate_script_files($code);

				PopupAllySettingShared::update_database_version();
			}
		}

		public static function do_activation_actions() {
			delete_transient(self::SETTING_KEY_ALL);
			delete_option(self::SETTING_KEY_ALL);
			delete_transient(self::SETTING_KEY_CODE);
			delete_option(self::SETTING_KEY_CODE);

			PopupAllyDisplaySettings::do_activation_actions();
			PopupAllyStyleSettings::do_activation_actions();
			PopupAllyAdvancedSettings::do_activation_actions();
		}

		public static function do_deactivation_actions() {
			delete_transient(self::SETTING_KEY_ALL);
			delete_option(self::SETTING_KEY_ALL);
			delete_transient(self::SETTING_KEY_CODE);
			delete_option(self::SETTING_KEY_CODE);

			PopupAllyDisplaySettings::do_deactivation_actions();
			PopupAllyStyleSettings::do_deactivation_actions();
			PopupAllyAdvancedSettings::do_deactivation_actions();
		}

		private static function add_actions() {
			add_action('plugins_loaded', array(__CLASS__, 'upgrade_database'));

			if (is_admin()) {
				add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_administrative_resources'));

				add_action('add_meta_boxes', array(__CLASS__, 'add_meta_box'));

				// add setting menu
				add_action('admin_menu', array(__CLASS__, 'add_menu_pages'));
				add_action('admin_init', array(__CLASS__, 'register_settings'));
				add_action('wp_ajax_popupally_free_optin_submit', array(__CLASS__, 'optin_submit_callback'));
			} else {
				add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_resources'));
				add_action('wp_footer', array(__CLASS__, 'add_popup_html'));
			}
		}

		private static function add_filters() {
			add_filter('the_content', array(__CLASS__, 'add_form_to_content'));
		}

		public static function register_settings() {
			register_setting(self::SETTING_KEY_ALL, self::SETTING_KEY_ALL, array(__CLASS__, 'sanitize_settings'));
		}

		public static function enqueue_resources() {
			wp_enqueue_script('jquery');

			$display = PopupAllyDisplaySettings::get_display_settings();
			$style = PopupAllyStyleSettings::get_style_settings();

			$to_show = self::get_popup_to_show();
			$num_saved = PopupAllyStyleSettings::get_num_style_saved_settings();

			$base_script_url = PopupAllyUtilites::get_script_folder_url();
			$script_prefix = get_current_blog_id() . '-';
			if (!empty($to_show)) {
				$popup_param = self::generate_popup_parameters($display, $style, $to_show);

				wp_enqueue_script('popupally-action-script', self::$PLUGIN_URI . 'resource/frontend/popup.min.js', array('jquery'), self::VERSION);
				wp_localize_script( 'popupally-action-script', 'popupally_action_object',
					array('popup_param' => $popup_param));

				wp_enqueue_style('popupally-style', $base_script_url . '/' . $script_prefix . 'popupally-style.css', false, self::VERSION . '.' . $num_saved);
			}
			$ids = self::get_popup_thank_you_to_show();
			if (!empty($ids)){
				foreach($ids as $id) {
					wp_enqueue_script('popupally-thank-you-script-' . $id, $base_script_url . '/' . $script_prefix . 'popupally-thank-you-' . $id . '.js', false, self::VERSION . '.' . $num_saved);
				}
			}
		}

		public static function enqueue_administrative_resources($hook) {
			if (strpos($hook, self::SETTING_KEY_ALL) !== false) {
				wp_enqueue_media();

				wp_enqueue_style('popupally-backend', self::$PLUGIN_URI . 'resource/backend/popupally.css', false, self::VERSION);

				wp_enqueue_script('popupally-backend-default-code', self::$PLUGIN_URI . 'resource/backend/js/popup-default-code.js', false, self::VERSION);
				wp_enqueue_script('popupally-backend', self::$PLUGIN_URI . 'resource/backend/js/popupally.min.js', array('jquery', 'popupally-backend-default-code'), self::VERSION);
				wp_enqueue_script('popupally-backend-color-picker', self::$PLUGIN_URI . 'resource/backend/jscolor/jscolor.js', array('jquery'), self::VERSION);

				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] !== 'off'){
					$admin_url = preg_replace("/^http:/i", "https:", admin_url('admin-ajax.php'));
				}else{
					$admin_url = preg_replace("/^https:/i", "http:", admin_url('admin-ajax.php'));
				}
				wp_localize_script( 'popupally-backend', 'popupally_data_object',
					array('ajax_url' => $admin_url, 'plugin_url' => self::$PLUGIN_URI, 'nonce' => wp_create_nonce('popupally-free')));
			}
		}

		// <editor-fold defaultstate="collapsed" desc="Templates">
		public static function add_template($template) {
			self::$available_templates[$template->uid] = $template;
		}

		public static function get_template($template_uid) {
			if (isset(self::$available_templates[$template_uid])) {
				return self::$available_templates[$template_uid];
			}
			return self::$available_templates['bxsjbi'];
		}
		private static function load_templates() {
			$root = dirname(__FILE__) . '/resource/' .self::TEMPLATE_DIRECTORY;
			foreach (self::$template_folders as $folder) {
				$file = $root . '/' . $folder . '/definition.php';
				include_once($file);
			}
		}
		// </editor-fold>


		// <editor-fold defaultstate="collapsed" desc="Embedded sign up forms">
		public static function add_form_to_content($content) {
			if (!is_singular()) {
				return $content;
			}
			$to_show = self::get_popup_to_show();
			if (!empty($to_show)) {
				$display = PopupAllyDisplaySettings::get_display_settings();
				$code = self::get_popup_code();
				foreach($to_show as $id => $popup_types) {
					if (in_array('embedded', $popup_types)) {
						if ('post-start' === $display[$id]['embedded-location']) {
							$content = do_shortcode($code[$id]['embedded_html']) . $content;
						} elseif ('post-end' === $display[$id]['embedded-location']) {
							$content = $content . do_shortcode($code[$id]['embedded_html']);
						}
					}
				}
			}
			return $content;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Page meta box">
		public static function add_meta_box($post_type) {
			$post_types = array('post', 'page');     //limit meta box to certain post types
			if ( in_array( $post_type, $post_types )) {
				add_meta_box(
					'popupally-display-settings',
					 'PopupAlly Display Settings',
					array( __CLASS__, 'show_post_display_meta_box_content' ),
					$post_type,
					'side',
					'high'
				);
			}
		}

		public static function show_post_display_meta_box_content($post) {
			$to_show = self::get_popup_to_show($post->ID);

			include (dirname(__FILE__) . '/resource/backend/post-display.php');
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Settings">
		public static function add_menu_pages() {
			// Add the top-level admin menu
			$capability = 'manage_options';
			$plugin_page = add_menu_page('PopupAlly', 'PopupAlly', $capability, self::SETTING_KEY_ALL, array(__CLASS__, 'show_popupally_settings'), self::$PLUGIN_URI . 'resource/backend/img/popupally-icon.png');

			add_action('admin_head-'.$plugin_page, array(__CLASS__, 'add_preview_popup_scripts'));
		}

		public static function show_popupally_settings() {
			if (!current_user_can('manage_options')) {
				wp_die('You do not have sufficient permissions to access this page.');
			}
			self::check_php_version(self::SETTING_KEY_ALL);
			$setting = self::get_selected_settings();

			$submitted = self::get_optin_submit_settings();
			$show_opt_in = empty($submitted['submit']);

			$admin_name = $admin_email = '';
			if ($show_opt_in) {
				$current_user = wp_get_current_user();
				$admin_name = $current_user->user_firstname;
				$admin_email = $current_user->user_email;
			}
			include (dirname(__FILE__) . '/resource/backend/setting-all.php');
		}

		public static function sanitize_settings($input) {
			if (!isset($input[PopupAllyDisplaySettings::SETTING_KEY_DISPLAY])) {
				return $input;
			}
			$display = PopupAllySettingShared::convert_setting_string_to_array($input[PopupAllyDisplaySettings::SETTING_KEY_DISPLAY]);
			$style = PopupAllySettingShared::convert_setting_string_to_array($input[PopupAllyStyleSettings::SETTING_KEY_STYLE]);
			$advanced = PopupAllySettingShared::convert_setting_string_to_array($input[PopupAllyAdvancedSettings::SETTING_KEY_ADVANCED]);
			if (false === $display || false === $style || false === $advanced) {
				add_settings_error(self::SETTING_KEY_ALL, 'settings_updated', 'Setting update failed due to missing settings.', 'error');
				return $input['selected'];
			}
			$display_length = self::get_setting_array_length($display);
			$style_length = self::get_setting_array_length($style);
			if ($display_length !== $style_length){
				add_settings_error(self::SETTING_KEY_ALL, 'settings_updated', 'Setting update failed due to mismatching settings.', 'error');
				return $input['selected'];
			}

			PopupAllyDisplaySettings::sanitize_display_settings($display);
			PopupAllyStyleSettings::sanitize_style_settings($style);
			$advanced = PopupAllyAdvancedSettings::sanitize_advanced_settings($advanced);

			// refresh the code stored in the database
			self::get_popup_code(true);
			$code = self::generate_popup_code(true);

			self::generate_script_files($code);

			PopupAllyUtilites::clear_wp_cache();
			set_transient(self::SETTING_KEY_ALL, $input['selected'], self::CACHE_PERIOD);
			add_settings_error(self::SETTING_KEY_ALL, 'settings_updated', 'Settings saved!', 'updated');
			return $input['selected'];
		}
		private static function get_setting_array_length($input){
			$length = 0;
			foreach ($input as $id => &$setting) {
				if (is_int($id)) {
					++$length;
				}
			}
			return $length;
		}
		
		private static function generate_individual_popup_code($id, $style, $all_style, $full_generation = false) {
			$result = array();

			$advanced_settings = PopupAllyAdvancedSettings::get_advanced_settings();
			if (isset($advanced_settings['use-important']) && $advanced_settings['use-important'] === 'true') {
				$use_important = ' !important';
			} else {
				$use_important = '';
			}
			$result['html'] =PopupAllyStyleCodeGeneration::generate_popup_html($id, $style, $all_style, 0);
			$result['embedded_html'] = PopupAllyStyleCodeGeneration::generate_popup_html($id, $style, $all_style, 1);

			if ($full_generation) {
				$result['css'] = str_replace('{{use-important}}', $use_important, PopupAllyStyleCodeGeneration::generate_popup_css($id, $style, $all_style, 0));
				$result['thank_you'] = self::generate_thank_you_js($style);
			}
			return $result;
		}

		public static function generate_popup_code($full_generation = false) {
			$style = PopupAllyStyleSettings::get_style_settings();

			$code = array('version' => self::VERSION);
			foreach($style as $id => $style_settings) {
				$code[$id] = self::generate_individual_popup_code($id, $style_settings, $style, $full_generation);
			}
			return $code;
		}

		public static function generate_script_files($code) {
			foreach($code as $id => $content) {
				if (is_int($id)) {
					$code[$id]['html'] = do_shortcode($content['html']);
					$code[$id]['embedded_html'] = do_shortcode($content['embedded_html']);
				}
			}
			if (!function_exists('request_filesystem_credentials')) {
				require_once (ABSPATH . '/wp-admin/includes/file.php');
			}
			if (false === ($creds = request_filesystem_credentials('admin.php', '', false, false, null))) {
				add_settings_error(self::SETTING_KEY_ALL, 'script-file', 'File permission error: cannot write styling file. Please make sure you have write permission to the WordPress install (or add FTP information to wp-config.php).', 'error');
				return true;
			}
			if (!WP_Filesystem($creds)) {
				add_settings_error(self::SETTING_KEY_ALL, 'script-file-init', 'File permission error: file writing initialization failed. Please make sure you have write permission to the WordPress install (or add FTP information to wp-config.php).', 'error');
				return true;
			}
			global $wp_filesystem;
			$target_dir = PopupAllyUtilites::get_script_folder_dir();

			if(!$wp_filesystem->is_dir($target_dir)) {
				$wp_filesystem->mkdir($target_dir);
			}
			$css = '';
			$prefix = get_current_blog_id() . '-';

			foreach($code as $id => $values){
				if (is_array($values)) {	// do not process the 'version' key
					$css .= $values['css'];

					$thank_you = 'var exdate = new Date();exdate.setFullYear(exdate.getFullYear() + 10);' . $values['thank_you'];
					$wp_filesystem->put_contents($target_dir . $prefix . 'popupally-thank-you-' . $id . '.js', $thank_you, FS_CHMOD_FILE);
				}
			}
			$wp_filesystem->put_contents($target_dir . $prefix . 'popupally-style.css', $css, FS_CHMOD_FILE);
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Front end">
		public static function add_preview_popup_scripts() {
			$style_settings = PopupAllyStyleSettings::get_style_settings();
			foreach($style_settings as $id => $style) {
				echo '<style id="popupally-preview-css-' . $id . '" type="text/css">';
				$template_uid = $style['selected-template'];
				$template_obj = self::get_template($template_uid);
				if ($template_obj) {
					echo PopupAllyStyleCodeGeneration::generate_popup_css($id, $style, $style_settings, 1, $template_obj);
				}
				echo '</style>';
			}
		}
		private static function generate_popup_parameters($display, $style, $to_show) {
			$params = array();
			foreach ($display as $id => $display_settings) {
				$to_show_settings = false;
				if (isset($to_show[$id])) {
					$to_show_settings = $to_show[$id];
				}
				$params[$id] = self::generate_individual_popup_parameters($id, $display_settings, $style[$id], $to_show_settings);
			}
			return $params;
		}
		private static function generate_individual_popup_parameters($id, $display, $style, $popup_types = false) {
			$param = array('id' => $id);
			if (false !== $popup_types) {
				foreach($popup_types as $type) {
					switch($type) {
						case 'timed':
							$param['timed-popup-delay'] = $display['timed-popup-delay'];
							break;
						case 'exit-intent':
							$param['enable-exit-intent-popup'] = $display['enable-exit-intent-popup'];
							break;
					}
				}
			}
			$param = PopupAllyUtilites::extract_array_values($display, self::$config_display_settings, $param);
			$param = PopupAllyUtilites::extract_array_values($style, PopupAllyStyleSettings::$config_style_settings, $param);
			return $param;
		}
		public static function add_popup_html() {
			$to_show = self::get_popup_to_show();
			if (!empty($to_show)) {
				$display = PopupAllyDisplaySettings::get_display_settings();
				$code = self::get_popup_code();
				foreach($to_show as $id => $popup_types) {
					if (in_array('embedded', $popup_types)) {
						if ('page-end' === $display[$id]['embedded-location']) {
							echo do_shortcode($code[$id]['embedded_html']);
						}
					}
					echo do_shortcode($code[$id]['html']);
				}
			}
		}

		private static function generate_thank_you_js($style) {
			return 'document.cookie = "' . $style['cookie-name'] . '=disable; path=/; expires="+ exdate.toGMTString();';
		}

		public static function get_popup_thank_you_to_show($post_id = false) {
			if ($post_id === false) {
				global $wp_query;
				if (isset($wp_query) && isset($wp_query->post)) {
					$post_id = $wp_query->post->ID;
				} else {
					return array();
				}
			}
			$cookies = array();
			$display = PopupAllyDisplaySettings::get_display_settings();
			foreach ($display as $id => $settings) {
				if ((isset($settings['timed']) && 'true' === $settings['timed']) ||
						(isset($settings['enable-exit-intent-popup']) && 'true' === $settings['enable-exit-intent-popup'])) {
					if (isset($settings['thank-you'][$post_id])) {
						$cookies []= $id;
					}
				}
			}
			return $cookies;
		}

		private static function get_post_categories($post_id) {
			$post_categories = wp_get_post_categories($post_id);
			$categories = array();
			foreach($post_categories as $c){
				$cat = get_category($c);
				$categories[$cat->cat_ID] = $cat->slug;
			}
			return $categories;
		}
		private static function check_post_selection($current_page_attribute, $selection) {
			$post_id = $current_page_attribute['post_id'];
			$is_front_page = $current_page_attribute['is_front_page'];
			$is_blog_index = $current_page_attribute['is_blog_index'];
			$is_404 = $current_page_attribute['is_404'];
			$category_id = $current_page_attribute['category_id'];
			$post_type = $current_page_attribute['post_type'];
			$categories = $current_page_attribute['categories'];

			if ('page' === $post_type && isset($selection['all-pages'])) {
				return true;
			}
			if ('post' === $post_type && isset($selection['all-posts'])) {
				return true;
			}
			if (false !== $post_id) {
				if (isset($selection['all-'.$post_type])) {
					return true;
				}
				if (isset($selection[$post_id])) {
					return true;
				}
			} elseif ($is_front_page && isset($selection['front-page'])) {
				return true;
			} elseif ($category_id >= 0 && isset($selection['category-' . $category_id])) {
				return true;
			}
			return false;
		}
		private static function generate_current_page_attribute($post_id, $retrieve_default) {
			$result = array('is_front_page' => false, 'is_blog_index' => false, 'is_404' => false, 'category_id' => -1, 'categories' => array(), 'post_type' => '', 'post_id' => $post_id);
			if (is_front_page()) {
				$result['is_front_page'] = true;
				$result['post_type'] = 'page';
			} elseif ($post_id === false) {
				global $wp_query;
				if (!isset($wp_query)) {
					if ($retrieve_default) {
						self::$local_cached_to_show_results = array();
					}
					return false;
				}
				if ($wp_query->is_posts_page) {
					$result['is_blog_index'] = true;
					$result['post_type'] = 'page';
				} elseif ($wp_query->is_category) {
					$result['category_id'] = $wp_query->queried_object_id;
					$result['post_type'] = 'page';
				} elseif ($wp_query->is_404) {
					$result['is_404'] = true;
					$result['post_type'] = 'page';
				} elseif (isset($wp_query->post)) {
					$result['post_id'] = $post_id = $wp_query->post->ID;
					$result['post_type'] = $wp_query->post->post_type;
					$result['categories'] = self::get_post_categories($post_id);
				} else {
					if ($retrieve_default) {
						self::$local_cached_to_show_results = array();
					}
					return false;
				}
			} else {
				$post = get_post($post_id);
				if (null === $post) {
					if ($retrieve_default) {
						self::$local_cached_to_show_results = array();
					}
					return false;
				}
				$result['post_type'] = $post->post_type;
				$result['categories'] = self::get_post_categories($post_id);
			}
			return $result;
		}
		public static function get_popup_to_show($post_id = false) {
			$retrieve_default = false === $post_id;
			if ($retrieve_default && false !== self::$local_cached_to_show_results) {
				return self::$local_cached_to_show_results;
			}

			$current_page_attribute = self::generate_current_page_attribute($post_id, $retrieve_default);
			if (!$current_page_attribute) {
				return array();
			}
			$post_id = $current_page_attribute['post_id'];

			$result = array();
			$display = PopupAllyDisplaySettings::get_display_settings();
			foreach ($display as $id => $settings) {
				if (false !== $post_id && isset($settings['thank-you'][$post_id])) {
					continue;
				}
				$to_show = false;
				if (isset($settings['show-all']) && 'true' === $settings['show-all']) {	// exclude path
					$to_show = !self::check_post_selection($current_page_attribute, $settings['exclude']);
				} else {	// include path
					$to_show = self::check_post_selection($current_page_attribute, $settings['include']);
				}
				if (!$to_show) {
					continue;
				}
				$row = array();
				if ('true' === $settings['timed'] && $settings['timed-popup-delay'] >= 0) {
					$row []= 'timed';
				}
				if ('true' === $settings['enable-exit-intent-popup']) {
					$row []= 'exit-intent';
				}
				if ('true' === $settings['enable-embedded']) {
					$row []= 'embedded';
				}
				if (!empty($row)) {
					$result[$id] = $row;
				}
			}
			if ($retrieve_default) {
				self::$local_cached_to_show_results = $result;
			}
			return $result;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Utlities">
		public static function get_selected_settings() {
			$selected = get_transient(self::SETTING_KEY_ALL);

			if (!is_array($selected)) {
				$selected = get_option(self::SETTING_KEY_ALL, array('selected-tab' => 'display'));

				set_transient(self::SETTING_KEY_ALL, $selected, self::CACHE_PERIOD);
			}
			if (!is_array($selected) || empty($selected['selected-tab'])) {
				$selected = array('selected-tab' => 'display');
			}
			return $selected;
		}
		private static function get_optin_submit_settings() {
			$submitted = get_transient(self::SETTING_KEY_OPTIN_SUBMIT);

			if (!is_array($submitted)) {
				$submitted = get_option(self::SETTING_KEY_OPTIN_SUBMIT, array('submit' => ''));

				set_transient(self::SETTING_KEY_OPTIN_SUBMIT, $submitted, self::CACHE_PERIOD);
			}
			if (!is_array($submitted) || !isset($submitted['submit'])) {
				$submitted = array('submit' => '');
			}
			return $submitted;
		}
		public static function optin_submit_callback() {
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'popupally-free')) {
				http_response_code(403);
				die();
			}
			$submitted = self::get_optin_submit_settings();
			$submitted['submit'] = time();

			set_transient(self::SETTING_KEY_OPTIN_SUBMIT, $submitted, self::CACHE_PERIOD);
			if (!add_option(self::SETTING_KEY_OPTIN_SUBMIT, $submitted, '', 'no')) {
				update_option(self::SETTING_KEY_OPTIN_SUBMIT, $submitted);
			}
			die();
		}

		public static function get_popup_code($force = false) {
			return PopupAllyUtilites::get_cached_code(self::SETTING_KEY_CODE, array(__CLASS__, 'generate_popup_code'), $force);
		}

		private static function check_php_version($setting = false) {
			if (!defined('PHP_VERSION_ID')) {
				$version = explode('.', PHP_VERSION);
				define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
			}
			if (PHP_VERSION_ID < 50300) {
				$message = 'The server is currently running PHP Version ' . PHP_VERSION . '. PopupAlly needs at least PHP 5.3 to function properly. Please ask your host to upgrade.';
				if (false !== $setting) {
					add_settings_error($setting, 'php_version_error', $message, 'error');
				} else {
					return $message;
				}
			}
			return false;
		}
		// </editor-fold>
	}
	require_once('resource/popup-ally-template.php');
	require_once('resource/backend/setting-shared.php');
	require_once('resource/backend/display/setting-display.php');
	require_once('resource/backend/style/setting-style.php');
	require_once('resource/backend/style/setting-style-code-generation.php');
	require_once('resource/backend/advanced-settings/setting-advanced.php');
	require_once('resource/popupally-utilities.php');
	PopupAlly::init();
}
