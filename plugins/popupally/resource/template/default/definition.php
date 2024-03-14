<?php
if (!class_exists('PopupAllyDefaultTemplate')) {
	class PopupAllyDefaultTemplate extends PopupAllyTemplate {
		public function __construct() {
			parent::__construct();
			$this->uid = 'bxsjbi';
			$this->template_name = 'Tried-and-true';
			$this->template_order = 0;

			$this->backend_php = dirname(__FILE__) . '/backend/default-preview.php';

			// 0: front end html, 1: front end embedded, 2: backend preview
			$this->popup_html_template_files = array(
				0 => dirname(__FILE__) . '/frontend/default-popup.php',
				1 => dirname(__FILE__) . '/frontend/default-embedded.php',
				2 => dirname(__FILE__) . '/backend/default-preview-template.php',
				3 => dirname(__FILE__) . '/backend/default-960-preview-template.php',
				4 => dirname(__FILE__) . '/backend/default-640-preview-template.php',
			);
			// 0: front end; 1: backend, 2: front end top margin
			$this->popup_css_template_files = array(
				0 => dirname(__FILE__) . '/frontend/default-popup.css',
				1 => dirname(__FILE__) . '/backend/default-preview-popup.css',
			);

			$this->frontend_css = dirname(__FILE__) . '/frontend/default-popup.css';
			$this->frontend_php = dirname(__FILE__) . '/frontend/default-popup.php';
			$this->frontend_embedded_php = dirname(__FILE__) . '/frontend/default-embedded.php';

			$this->html_mapping = array('image-url',
				'subscribe-button-text', 'sign-up-form-method', 'sign-up-form-action', 'bxsjbi-sign-up-form-name-field',
				'sign-up-form-email-field', 'name-placeholder', 'email-placeholder');
			$this->no_escape_html_mapping = array('headline', 'sales-text', 'privacy-text');
			$this->default_values = array(
				'headline' => "Enter your name and email and get the weekly newsletter... it's FREE!",
				'headline-font' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
				'headline-color' => "#444444",
				'headline-font-size' => "28",
				'headline-line-height' => "30",
				'sales-text' => 'Introduce yourself and your program',
				'sales-text-font' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
				'sales-text-color' => "#444444",
				'sales-text-font-size' => "24",
				'sales-text-line-height' => "28",
				'name-placeholder' => 'Enter your first name here',
				'email-placeholder' => 'Enter a valid email here',
				'input-box-font' => 'Arial, Helvetica, sans-serif',
				'input-box-color' => "#444444",
				'input-box-font-size' => "16",
				'input-box-line-height' => "21",
				'subscribe-button-text' => 'Subscribe',
				'subscribe-button-text-font' => 'Arial, Helvetica, sans-serif',
				'subscribe-button-text-color' => "#ffffff",
				'subscribe-button-text-font-size' => "22",
				'subscribe-button-text-line-height' => "27",
				'privacy-text' => 'Your information will *never* be shared or sold to a 3rd party.',
				'privacy-text-font' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
				'privacy-text-color' => "#444444",
				'privacy-text-font-size' => "14",
				'privacy-text-line-height' => "14",
				'background-color' => '#fefefe',
				'subscribe-button-color' => '#00c98d',
				'bxsjbi-headline-hide-toggle' => 'false',
				'bxsjbi-logo-row-hide-toggle' => 'false',
				'bxsjbi-logo-img-hide-toggle' => 'false',
				'bxsjbi-privacy-hide-toggle' => 'false',
				'image-url' => PopupAlly::$PLUGIN_URI . 'resource/img/pink-tools.png',

				'headline-960-font-size' => '24',
				'headline-960-line-height' => '26',
				'sales-text-960-font-size' => '20',
				'sales-text-960-line-height' => '22',
				'privacy-text-960-font-size' => '10',
				'privacy-text-960-line-height' => '10',
				'input-box-960-font-size' => "12",
				'input-box-960-line-height' => "18",
				'subscribe-button-text-960-font-size' => "18",
				'subscribe-button-text-960-line-height' => "24",

				'headline-640-font-size' => '18',
				'headline-640-line-height' => '20',
				'sales-text-640-font-size' => '12',
				'sales-text-640-line-height' => '14',
				'privacy-text-640-font-size' => '8',
				'privacy-text-640-line-height' => '8',
				'input-box-640-font-size' => "10",
				'input-box-640-line-height' => "14",
				'subscribe-button-text-640-font-size' => "16",
				'subscribe-button-text-640-line-height' => "20",
			);
			$this->style_template_advanced_customization = array(
				'headline' => array(0, '.preview-headline', '#preview-headline'),
				'sales-text' => array(0, '.preview-sales-text', '#preview-sales-text'),
				'privacy-text' => array(0, '.privacy-text', '#privacy-text'),
				'input-box' => array(1, '.preview-input', '.preview-input-desktop'),
				'subscribe-button-text' => array(2, '.subscribe-button', '#subscribe-button'),
				'headline-960' => array(3, '#preview-headline-960'),
				'sales-text-960' => array(3, '#preview-sales-text-960'),
				'privacy-text-960' => array(3, '#privacy-text-960'),
				'input-box-960' => array(4, '.preview-input-960'),
				'subscribe-button-text-960' => array(5, '#subscribe-button-960'),
				'headline-640' => array(3, '#preview-headline-640'),
				'sales-text-640' => array(3, '#preview-sales-text-640'),
				'privacy-text-640' => array(3, '#privacy-text-640'),
				'input-box-640' => array(4, '.preview-input-640'),
				'subscribe-button-text-640' => array(5, '#subscribe-button-640'),
			);
			$this->style_template_checked_replace = array(
				'bxsjbi-headline-hide-toggle',
				'bxsjbi-logo-row-hide-toggle',
				'bxsjbi-logo-img-hide-toggle',
				'bxsjbi-privacy-hide-toggle',
			);
		}

		public function sanitize_style($setting, $id, $is_active = false) {
			$setting = parent::sanitize_style($setting, $id, $is_active);
			return $setting;
		}

		public function prepare_for_code_generation($id, $style, $all_style) {
			$style = parent::prepare_for_code_generation($id, $style, $all_style);

			$style['used-sign-up-form-fields'] = array();
			if (!empty($style['sign-up-form-email-field'])) {
				$style['used-sign-up-form-fields'] []= $style['sign-up-form-email-field'];
			}

			if (!empty($style['sign-up-form-name-field'])) {
				$style['bxsjbi-sign-up-form-name-field'] = $style['sign-up-form-name-field'];
				$style['used-sign-up-form-fields'] []= $style['sign-up-form-name-field'];
			}

			return $style;
		}

		public function show_style_settings($id, $setting) {
			$preview_code = PopupAllyStyleCodeGeneration::generate_popup_html($id, $setting, false, 2, $this);
			$html = $this->get_style_setting_template();

			foreach($this->style_template_advanced_customization as $name => $tuple) {
				$advanced_edit = PopupAllyTemplate::generate_advanced_customization_code($setting, $name, $tuple);

				$html = str_replace("{{{$name}-advanced}}", $advanced_edit, $html);
			}

			for ($i=2;$i<=4;++$i) {
				$html = str_replace("{{preview-code-$i}}", $preview_code[$i], $html);
			}

			// the order is important, as style_template_checked_replace is a subset of default_values
			foreach($this->style_template_checked_replace as $param) {
				$html = str_replace("{{{$param}}}", 'true' === $setting[$param] ? 'checked="checked"' : '', $html);
			}
			foreach($this->default_values as $param  => $default_value) {
				$html = str_replace("{{{$param}}}", PopupAllyUtilites::escape_html_string_literal($setting[$param]), $html);
			}
			return $html;
		}

		public function make_backwards_compatible($style) {
			if (isset($style['text-color'])) {
				$style['headline-color'] = $style['sales-text-color'] = $style['input-box-color'] = $style['privacy-text-color'] = $style['text-color'];
			}
			if (isset($style['display-headline'])) {
				$style['bxsjbi-headline-hide-toggle'] = $style['display-headline'] === 'block' ? 'false' : 'true';
			}
			if (isset($style['display-logo-row'])) {
				$style['bxsjbi-logo-row-hide-toggle'] = $style['display-logo-row'] === 'block' ? 'false' : 'true';
			}
			if (isset($style['display-logo-img'])) {
				$style['bxsjbi-logo-img-hide-toggle'] = $style['display-logo-img'] === 'block' ? 'false' : 'true';
			}
			if (isset($style['display-privacy'])) {
				$style['bxsjbi-privacy-hide-toggle'] = $style['display-privacy'] === 'block' ? 'false' : 'true';
			}
			return $style;
		}
		/* $size_postfix: '' - normal display; '-960' - 960px width; '-640' - 640px width */
		public function generate_position_code($style, $size_postfix) {
			if ($size_postfix === '-960') {
				return 'top:20%;left:50%;margin-left:-240px;';
			} elseif ($size_postfix === '-640') {
				return 'top:20%;left:50%;margin-left:-150px;';
			} else {
				return 'top:20%;left:50%;margin-left:-325px;';
			}
		}
	}
	PopupAlly::add_template(new PopupAllyDefaultTemplate());
}