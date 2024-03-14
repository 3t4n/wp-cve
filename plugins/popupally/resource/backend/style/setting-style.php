<?php
if (!class_exists('PopupAllyStyleSettings')) {
	class PopupAllyStyleSettings {
		const SETTING_KEY_STYLE = '_popupally_setting_style';
		const SETTING_KEY_NUM_STYLE_SAVED = '_popupally_setting_num_style_saved';

		public static $config_style_settings = array('popup-selector', 'popup-class', 'cookie-name', 'close-trigger');

		private static $style_settings_template_replace = array('name',
			'sign-up-form-method', 'sign-up-form-action', 'signup-form', 'sign-up-form-valid',
			'selected-template');
		private static $style_settings_template_checked_replace = array();

		private static $default_style_settings = null;
		private static $default_popup_style_simple_settings = null;

		public static function do_activation_actions() {
			delete_transient(self::SETTING_KEY_STYLE);
			delete_transient(self::SETTING_KEY_NUM_STYLE_SAVED);

			if (add_option(self::SETTING_KEY_NUM_STYLE_SAVED, 0)) {
				set_transient(self::SETTING_KEY_NUM_STYLE_SAVED, 0, PopupAlly::CACHE_PERIOD);
			}
		}
		public static function do_deactivation_actions() {
			delete_transient(self::SETTING_KEY_STYLE);
			delete_transient(self::SETTING_KEY_NUM_STYLE_SAVED);
		}
		private static function get_default_style_setting() {
			if (self::$default_style_settings === null) {
				self::$default_style_settings = array(1 => self::get_initial_popup_style_setting(1),
					2 => self::get_initial_popup_style_setting(2));
			}
			return self::$default_style_settings;
		}
		public static function get_initial_popup_style_setting($id) {
			$default_style_settings = self::get_default_popup_style_setting($id);
			return $default_style_settings;
		}
		private static function get_default_popup_style_setting($id = false) {
			if (self::$default_popup_style_simple_settings === null) {
				self::$default_popup_style_simple_settings = array('name' => 'Popup {{num}}',
					'signup-form' => '',
					'sign-up-form-method' => 'post',
					'sign-up-form-action' => '',
					'sign-up-form-valid' => 'false',
					'sign-up-form-name-field' => '',
					'sign-up-form-email-field' => '',
					'selected-template' => 'bxsjbi',
					'popup-selector' => '#popup-box-gfcr-{{num}}',
					'popup-class' => 'popupally-opened-gfcr-{{num}}',
					'cookie-name' => 'popupally-cookie-{{num}}',
					'close-trigger' => '.popup-click-close-trigger-{{num}}',
					'is-open' => 'false',
					);
			}
			if (false === $id) {
				return self::$default_popup_style_simple_settings;
			}
			return PopupAllyUtilites::customize_parameter_array(self::$default_popup_style_simple_settings, $id);
		}
		public static function merge_default_style_settings($id, $style) {
			if (isset($style['popup-selector'])) {
				unset($style['popup-selector']);
				unset($style['popup-class']);
				unset($style['close-trigger']);
				unset($style['cookie-name']);
			}
			if (!isset($style['sign-up-form-valid'])) {
				if (isset($style['hidden-form-fields-name']) || isset($style['other-form-fields-name'])) {
					$style['sign-up-form-valid'] = 'true';
				} else {
					$style['sign-up-form-valid'] = 'false';
				}
			}
			$template_obj = PopupAlly::get_template($style['selected-template']);
			if ($template_obj) {
				$style = $template_obj->make_backwards_compatible($style);
				$style = $template_obj->merge_default_values($style);
			}
			$style = wp_parse_args($style, self::get_default_popup_style_setting($id));
			return $style;
		}
		private static $cached_style_settings = null;
		public static function get_style_settings() {
			if (self::$cached_style_settings === null) {
				$style = get_transient(self::SETTING_KEY_STYLE);
				$default_style_settings = self::get_default_style_setting();

				if (!is_array($style)) {
					$style = get_option(self::SETTING_KEY_STYLE, $default_style_settings);

					set_transient(self::SETTING_KEY_STYLE, $style, PopupAlly::CACHE_PERIOD);
				}
				if (!is_array($style)) {
					$style = $default_style_settings;
				}
				foreach ($default_style_settings as $key => $value) {
					if (!isset($style[$key])) {
						$style[$key] = $value;
					}
				}
				// update old setting to new ones
				foreach($style as $id => $setting) {
					if (is_int($id)) {
						$style[$id] = self::merge_default_style_settings($id, $setting);
					}
				}
				self::$cached_style_settings = $style;
			}
			return self::$cached_style_settings;
		}
		private static function generate_signup_html_individual_generated_field($id, $setting, $variable) {
			$generated_field = '';
			$id_prefix = $variable . '-' . $id . '-';
			if (isset($setting[$variable . '-name'])) {
				$prefix = '<input class="sign-up-form-generated-' . $id . '" type="hidden" name="[' . $id . '][';
				foreach($setting[$variable . '-name'] as $field_id => $name) {
					$generated_field .= $prefix . $variable . '-name][' . $field_id . ']" value="' . esc_attr($name) . '" />';
					$generated_field .= $prefix . $variable . '-value][' . $field_id . ']" value="' . esc_attr($setting[$variable . '-value'][$field_id]) . '" id="' . 
							esc_attr($id_prefix . $name) . '" />';
				}
			}
			return $generated_field;
		}
		private static function generate_signup_html_individual_generated_scripts($id, $setting, $variable) {
			$generated_field = '';
			if (isset($setting[$variable])) {
				$prefix = '<input class="sign-up-form-generated-' . $id . '" type="hidden" name="[' . $id . '][';
				foreach($setting[$variable] as $field_id => $name) {
					$generated_field .= $prefix . $variable . '][' . $field_id . ']" value="' . esc_attr($name) . '" />';
				}
			}
			return $generated_field;
		}
		private static function generate_signup_html_generated_field($id, $setting) {
			$generated_field = self::generate_signup_html_individual_generated_field($id, $setting, 'other-form-fields');
			$generated_field .= self::generate_signup_html_individual_generated_field($id, $setting, 'hidden-form-fields');
			$generated_field .= self::generate_signup_html_individual_generated_field($id, $setting, 'checkbox-form-fields');
			$generated_field .= self::generate_signup_html_individual_generated_field($id, $setting, 'dropdown-form-fields');
			return $generated_field;
		}
		private static function generate_signup_html_field_selection($setting, $selected_attr) {
			$selection_code = '<option value=""></option>';
			if (isset($setting['other-form-fields-name'])) {
				$selection_code = '<option value=""></option>';
				foreach($setting['other-form-fields-name'] as $field_id => $name) {
					$esc_name = esc_attr($name);
					$selection_code .= '<option value="' . $esc_name . '" '. ($setting[$selected_attr] == $name ? 'selected="selected"' : '') . '>' . $esc_name . '</option>';
				}
			}
			return $selection_code;
		}
		private static function generate_template_selection_code($templates, $selected) {
			$template_selection_code = '';
			foreach ($templates as $template_uid => $template_obj) {
				if ($template_uid === $selected) {
					$template_selection_code .= '<option value="' . $template_uid . '" selected="selected">' . esc_attr($template_obj->template_name) . '</option>';
				} else {
					$template_selection_code .= '<option value="' . $template_uid . '">' . esc_attr($template_obj->template_name) . '</option>';
				}
			}
			return $template_selection_code;
		}
		public static function generate_individual_style_code($id, $setting, $style_detail_template) {
			$style_detail = $style_detail_template;
			foreach(self::$style_settings_template_replace as $replace) {
				$style_detail = str_replace("{{{$replace}}}", esc_attr($setting[$replace]), $style_detail);
			}
			foreach(self::$style_settings_template_checked_replace as $replace) {
				if ($setting[$replace] === 'true') {
					$style_detail = str_replace("{{{$replace}}}", 'checked="checked"', $style_detail);
				} else {
					$style_detail = str_replace("{{{$replace}}}", '', $style_detail);
				}
			}
			$style_detail = str_replace("{{selected_item_opened}}", $setting['is-open'] === 'true'?'popupally-item-opened':'', $style_detail);
			$style_detail = str_replace("{{selected_item_checked}}", $setting['is-open'] === 'true'?'checked="checked"':'', $style_detail);

			$generated_fields = self::generate_signup_html_generated_field($id, $setting);
			$style_detail = str_replace("{{generated_fields}}", $generated_fields, $style_detail);
			$style_detail = str_replace("{{form-valid-false-hide}}", empty($generated_fields) ? 'style="display:none"' : '', $style_detail);

			$style_detail = str_replace("{{signup_name_field_selection}}", self::generate_signup_html_field_selection($setting, 'sign-up-form-name-field'), $style_detail);
			$style_detail = str_replace("{{signup_email_field_selection}}", self::generate_signup_html_field_selection($setting, 'sign-up-form-email-field'), $style_detail);

			$template_selection_code = '';
			$selected_template_main = $setting['selected-template'];
			$selected_template_obj = PopupAlly::get_template($setting['selected-template']);
			$template_selection_code = self::generate_template_selection_code(PopupAlly::$available_templates, $selected_template_main);

			$template_customization_code = '<div class="template-customization-block template-customization-block-active" id="template-customization-block-' . $id . '-' . $selected_template_obj->uid . '">';
			$template_customization_code .= $selected_template_obj->show_style_settings($id, $setting);
			$template_customization_code .= '</div>';

			$style_detail = str_replace("{{template_selection}}", $template_selection_code, $style_detail);
			$style_detail = str_replace("{{template_customization}}", $template_customization_code, $style_detail);

			$style_detail = str_replace("{{plugin-uri}}", PopupAlly::$PLUGIN_URI, $style_detail);

			$style_detail = str_replace("{{id}}", $id, $style_detail);
			$style_detail = PopupAllySettingShared::replace_all_toggle($style_detail, $setting);
			return $style_detail;
		}
		public static function load_style_template() {
			return file_get_contents(dirname(__FILE__) . '/setting-style-popup-template.php');
		}
		public static function show_style_settings() {
			$style = self::get_style_settings();

			echo '<h3>Style Settings</h3>';

			$style_detail_template = self::load_style_template();
			foreach ($style as $id => $setting) {
				echo self::generate_individual_style_code($id, $setting, $style_detail_template);
			}
			
			echo '<div class="popupally-setting-section"><div class="popupally-setting-section-header">Want more popups? <a class="popupally-trial-link" href="https://popupally.com/upgrading-to-popupally-pro/" target="_blank">Get PopupAlly Pro now!</a></div></div></div>';
		}
		public static function sanitize_style_settings($input) {
			$to_remove = array();	// used to remove any unwanted <input> that got captured by the serialization
			foreach ($input as $id => &$setting) {
				if (is_int($id)) {
					// only sanitize the style settings for the selected template
					$selected_template_obj = PopupAlly::get_template($setting['selected-template']);
					$setting = $selected_template_obj->sanitize_style($setting, $id, true);
					if ($setting['sign-up-form-name-field'] === ':null:') {
						$setting['sign-up-form-name-field'] = '';
					}
					if ($setting['sign-up-form-email-field'] === ':null:') {
						$setting['sign-up-form-email-field'] = '';
					}
				} else {
					$to_remove []= $id;
				}
			}
			foreach ($to_remove as $id) {
				unset($input[$id]);
			}
			self::increment_num_style_saved();
			update_option(self::SETTING_KEY_STYLE, $input);
			set_transient(self::SETTING_KEY_STYLE, $input, PopupAlly::CACHE_PERIOD);
		}

		private static function increment_num_style_saved() {			
			$num_saved = self::get_num_style_saved_settings();
			$num_saved += 1;
			update_option(self::SETTING_KEY_NUM_STYLE_SAVED, $num_saved);
			set_transient(self::SETTING_KEY_NUM_STYLE_SAVED, $num_saved, PopupAlly::CACHE_PERIOD);
		}

		public static function get_num_style_saved_settings() {
			$num = get_transient(self::SETTING_KEY_NUM_STYLE_SAVED);

			if (false === $num) {
				$num = get_option(self::SETTING_KEY_NUM_STYLE_SAVED, 0);

				set_transient(self::SETTING_KEY_NUM_STYLE_SAVED, $num, PopupAlly::CACHE_PERIOD);
			}
			return $num;
		}

		/* used by external plugin to generate default code */
		public static function generate_default_preview_code() {
			$code = array('version' => PopupAlly::VERSION, 'html' => array(), 'css' => array());
			foreach (PopupAlly::$available_templates as $template_uid => $template_obj) {
				$code['html'][$template_uid] = $template_obj->generate_default_customization_html();
				$code['css'][$template_uid] = $template_obj->generate_default_preview_css();
			}
			$code = PopupAllyUtilites::remove_newline($code);
			return $code;
		}
	}
}