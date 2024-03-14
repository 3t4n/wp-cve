<?php
if (!class_exists('PopupAllyTemplate')) {
	class PopupAllyTemplate {
		const POPUP_BOX_SHADOW_CSS = '-webkit-box-shadow: 0 10px 25px rgba(0,0,0,0.5);-moz-box-shadow: 0 10px 25px rgba(0,0,0,0.5);box-shadow: 0 10px 25px rgba(0,0,0,0.5);';

		public $uid = null;
		public $template_name = null;
		public $template_order = 0;

		public $frontend_css = null;

		// 0 - normal popup, 1 - embedded, 2 - preview
		protected $popup_html_template_files = null;
		private $popup_html_template = array();

		protected $popup_css_template_files = null;
		private $popup_css_template = array();
		
		public $backend_php = null;
		public $frontend_php = null;
		public $frontend_embedded_php = null;

		private $style_setting_template = null;

		protected $style_template_advanced_customization = array();

		// 0: text, 1: input, 2: submit, 3: responsive text, 4: responsive input, 5: responsive submit
		private static $style_advanced_customization_php = array(
			0 => 'setting-style-text-customization.php',
			1 => 'setting-style-input-customization.php',
			2 => 'setting-style-submit-customization.php',
			3 => 'setting-style-text-responsive-customization.php',
			4 => 'setting-style-input-responsive-customization.php',
			5 => 'setting-style-submit-responsive-customization.php',
			);
		private static $style_advanced_customization_template = array();

		public $html_mapping = null;
		public $no_escape_html_mapping = null;
		public $default_values = null;
	
		public static function initialize(){
			$base_path = dirname(__FILE__) . '/template/';
			foreach(self::$style_advanced_customization_php as $id=>$file) {
				self::$style_advanced_customization_php[$id] = $base_path . $file;
				self::$style_advanced_customization_template[$id] = null;
			}
		}

		protected function __construct() {
		}

		public function is_fluid_template() {
			return false;
		}
		public function sanitize_style($input, $id, $is_active = false) {
			if ($is_active) {
				$input['popup-selector'] = '#popup-box-gfcr-' . $id;
				$input['popup-class'] = 'popupally-opened-gfcr-' . $id;
				$input['cookie-name'] = 'popupally-cookie-' . $id;
				$input['close-trigger'] = '.popup-click-close-trigger-' . $id;
			}
			return $input;
		}

		public function prepare_for_code_generation($id, $style, $all_style) {
			return $style;
		}
		public function generate_preview_popup_html($id, $setting) {
			$generated_templates = array();
			for ($i=2;$i<=4;++$i) {
				$template = $this->get_popup_html_template($i);
				$generated_templates[$i] = $this->generate_popup_html_from_template($id, $setting, $template, true);
			}
			return $generated_templates;
		}
		protected function generate_popup_html_from_template($id, $setting, $template, $is_preview = false) {
			$template = str_replace('{{num}}', $id, $template);

			foreach ($this->html_mapping as $replace) {
				if (isset($setting[$replace])) {
					$template = str_replace('{{' . $replace . '}}', esc_attr($setting[$replace]), $template);
				} else {
					$template = str_replace('{{' . $replace . '}}', '', $template);
				}
			}
			foreach ($this->no_escape_html_mapping as $replace) {
				if (isset($setting[$replace])) {
					if ($is_preview && !PopupAllySettingShared::has_matching_tags($setting[$replace])) {
						$template = str_replace('{{' . $replace . '}}', 'Mismatch tag in HTML string. Please fix.', $template);
					} else {
						$template = str_replace('{{' . $replace . '}}', $setting[$replace], $template);
					}
				} else {
					$template = str_replace('{{' . $replace . '}}', '', $template);
				}
			}
			// generate hidden fields
			$hidden_fields = '';

			if (isset($setting['hidden-form-fields-name'])) {
				foreach ($setting['hidden-form-fields-name'] as $field_id => $name) {
					$hidden_fields .= '<input type="hidden" name="' . $name . '" value="' . esc_attr($setting['hidden-form-fields-value'][$field_id]) . '"/>';
				}
			}
			if (isset($setting['other-form-fields-name'])) {
				foreach ($setting['other-form-fields-name'] as $field_id => $name) {
					if (isset($setting['used-sign-up-form-fields']) && is_array($setting['used-sign-up-form-fields'])) {
						if (in_array($name, $setting['used-sign-up-form-fields'])) {
							continue;
						}
					}
					$hidden_fields .= '<input type="hidden" name="' . $name . '" value="' . esc_attr($setting['other-form-fields-value'][$field_id]) . '"/>';
				}
			}
			$template = str_replace('{{hidden-fields}}', $hidden_fields, $template);
			return $template;
		}
		public function generate_popup_html($id, $setting, $all_style, $mode = 0) {
			$setting = $this->prepare_for_code_generation($id, $setting, $all_style);
			if (2 == $mode) {
				return $this->generate_preview_popup_html($id, $setting);
			}
			$template = $this->get_popup_html_template($mode);
			return $this->generate_popup_html_from_template($id, $setting, $template);
		}
		/* mode: 0 - normal css, 1 - preview css */
		public function generate_popup_css($id, $setting, $all_style, $mode) {
			$setting = $this->prepare_for_code_generation($id, $setting, $all_style);
			$template = $this->get_popup_css_template($mode);
			$template = str_replace('{{num}}', $id, $template);

			$show_popup_box_shadow = true;
			foreach ($this->default_values as $replace => $default_value) {
				$value = $setting[$replace];
				if ('-color' === substr($replace, -6)) { // ends with -color
					if ('' === $setting[$replace]) {
						$value = 'transparent';
						if ('background-color' === substr($replace, -16)) { // ends with -background-color
							$show_popup_box_shadow = false;
						}
					} else {
						if ('#' !== $setting[$replace][0]) {
							$value = '#' + $value;
						}
						$value = $value . '000000';
						$value = substr($value, 0, 7);
					}
				} elseif ('-hide-toggle' === substr($replace, -12)) { // ends with -hide-toggle
					$value = $value === 'true' ? 'none' : 'block';
						
				}
				$template = str_replace('{{' . $replace . '}}', $value, $template);
			}

			if ($mode === 0) {
				if ($show_popup_box_shadow) {
					$template = str_replace('{{POPUP_BOX_SHADOW_CSS}}', self::POPUP_BOX_SHADOW_CSS, $template);
				} else {
					$template = str_replace('{{POPUP_BOX_SHADOW_CSS}}', '', $template);
				}
				$uri = parse_url(PopupAlly::$PLUGIN_URI, PHP_URL_PATH);
				$template = str_replace('{{plugin_uri}}', $uri, $template);

				$template = str_replace('{{position-code}}', $this->generate_position_code($setting, ''), $template);
				$template = str_replace('{{position-960-code}}', $this->generate_position_code($setting, '-960'), $template);
				$template = str_replace('{{position-640-code}}', $this->generate_position_code($setting, '-640'), $template);
			}
			return $template;
		}

		protected function get_style_setting_template() {
			if (null === $this->style_setting_template) {
				$this->style_setting_template = file_get_contents($this->backend_php);
			}
			return $this->style_setting_template;
		}

		private static function get_style_advanced_customization_template($template_id) {
			if (null === self::$style_advanced_customization_template[$template_id]) {
				self::$style_advanced_customization_template[$template_id] = file_get_contents(self::$style_advanced_customization_php[$template_id]);
			}
			return self::$style_advanced_customization_template[$template_id];
		}
		// 0: text, 1: input, 2: submit, 3: responsive text, 4: responsive input, 5: responsive submit
		protected static function generate_advanced_customization_code($setting, $name, $template_definition) {
			$template_id = $template_definition[0];
			$target = $target_specific = $template_definition[1];
			if (count($template_definition) > 2) {
				$target_specific = $template_definition[2];
			}
			$advanced_edit = self::get_style_advanced_customization_template($template_id);

			if ($template_id < 3) {
				$advanced_edit = PopupAllySettingShared::customize_advanced_edit_option($setting, $name, $advanced_edit);
			}
			$advanced_edit = str_replace("{{element_name}}", $name, $advanced_edit);
			$advanced_edit = str_replace("{{preview_element_name}}", $target, $advanced_edit);
			$advanced_edit = str_replace("{{preview_element_name_specific}}", $target_specific, $advanced_edit);
			return $advanced_edit;
		}

		public function show_style_settings($id, $setting) {
			return '';
		}

		public function get_popup_html_template($template_id = 0) {
			if (!isset($this->popup_html_template[$template_id])) {
				$this->popup_html_template[$template_id] = file_get_contents($this->popup_html_template_files[$template_id]);
			}
			return $this->popup_html_template[$template_id];
		}

		public function get_popup_css_template($template_id) {
			if (!isset($this->popup_css_template[$template_id])) {
				if ($this->popup_css_template_files[$template_id]) {
					$this->popup_css_template[$template_id] = file_get_contents($this->popup_css_template_files[$template_id]);
				} else {
					$this->popup_css_template[$template_id] = '';
				}
			}
			return $this->popup_css_template[$template_id];
		}

		public function make_backwards_compatible($style) {
			return $style;
		}
		public function merge_default_values($style) {
			$style = wp_parse_args($style, $this->default_values);
			return $style;
		}
		public function generate_position_code($style, $mode) {
			return '';
		}
		public function generate_default_customization_html() {
			$style_html = '<div class="template-customization-block" id="template-customization-block---id---' . $this->uid . '">' .
						$this->show_style_settings('--id--', $this->default_values) . '</div>';
			$style_html = str_replace('{{id}}', '--id--', $style_html);
			$style_html = PopupAllySettingShared::replace_all_toggle($style_html, $this->default_values);
			return $style_html;
		}
		public function generate_default_preview_css() {
			$setting = $this->merge_default_values($this->default_values);
			return PopupAllyStyleCodeGeneration::generate_popup_css('--id--', $setting, false, 1, $this);
		}
		// <editor-fold defaultstate="collapsed" desc="Utility functions">
		public static function image_url_code_generation(&$style, $source_param) {
			if (isset($style[$source_param]) && $style[$source_param]) {
				return 'url(' . $style[$source_param] . ')';
			}
			return 'none';
		}
		// </editor-fold>
	}
	PopupAllyTemplate::initialize();
}