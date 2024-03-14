<?php
if (!class_exists('PopupAllyCleanTemplate')) {
	class PopupAllyCleanTemplate extends PopupAllyTemplate {
		public function __construct() {
			parent::__construct();
			$this->uid = 'plsbvs';
			$this->template_name = 'Express yourself';
			$this->template_order = 1;

			$this->backend_php = dirname(__FILE__) . '/backend/clean-preview.php';

			// 0: front end html, 1: front end embedded, 2: backend preview
			$this->popup_html_template_files = array(
				0 => dirname(__FILE__) . '/frontend/clean-popup.php',
				1 => dirname(__FILE__) . '/frontend/clean-embedded.php',
				2 => dirname(__FILE__) . '/backend/clean-preview-template.php',
				3 => dirname(__FILE__) . '/backend/clean-960-preview-template.php',
				4 => dirname(__FILE__) . '/backend/clean-640-preview-template.php',
			);
			// 0: front end; 1: backend, 2: front end top margin
			$this->popup_css_template_files = array(
				0 => dirname(__FILE__) . '/frontend/clean-popup.css',
				1 => dirname(__FILE__) . '/backend/clean-preview-popup.css',
			);

			$this->html_mapping = array('plsbvs-name-placeholder', 'plsbvs-email-placeholder', 'plsbvs-subscribe-button-text', 'sign-up-form-method', 'sign-up-form-action', 'plsbvs-sign-up-form-name-field',
				'sign-up-form-email-field');
			$this->no_escape_html_mapping = array('plsbvs-headline');
			$this->default_values = array(
				'plsbvs-background-color' => '#d3d3d3',
				'plsbvs-image-url' => '',
				'plsbvs-background-image' => 'none',
				'plsbvs-width' => '940',
				'plsbvs-height' => '60',
				'plsbvs-headline' => 'Get free weekly updates:',
				'plsbvs-headline-font' => '"Palatino Linotype", "Book Antiqua", Palatino, serif',
				'plsbvs-headline-color' => "#111111",
				'plsbvs-headline-font-size' => "20",
				'plsbvs-headline-line-height' => "24",
				'plsbvs-headline-top' => '15',
				'plsbvs-headline-left' => '60',
				'plsbvs-name-placeholder' => 'Name',
				'plsbvs-name-field-top' => '15',
				'plsbvs-name-field-left' => '90',
				'plsbvs-name-field-font' => 'Arial, Helvetica, sans-serif',
				'plsbvs-name-field-color' => "#444444",
				'plsbvs-name-field-font-size' => "16",
				'plsbvs-name-field-line-height' => "16",
				'plsbvs-email-placeholder' => 'Email',
				'plsbvs-email-field-top' => '15',
				'plsbvs-email-field-left' => '100',
				'plsbvs-email-field-font' => 'Arial, Helvetica, sans-serif',
				'plsbvs-email-field-color' => "#444444",
				'plsbvs-email-field-font-size' => "16",
				'plsbvs-email-field-line-height' => "16",
				'plsbvs-subscribe-button-text' => 'Sign up!',
				'plsbvs-subscribe-button-color' => '#00c98d',
				'plsbvs-subscribe-button-text-font' => 'Arial, Helvetica, sans-serif',
				'plsbvs-subscribe-button-text-color' => "#ffffff",
				'plsbvs-subscribe-button-text-font-size' => "16",
				'plsbvs-subscribe-button-text-line-height' => "16",
				'plsbvs-subscribe-button-top' => '15',
				'plsbvs-subscribe-button-left' => '110',

				'plsbvs-width-960' => '600',
				'plsbvs-height-960' => '60',
				'plsbvs-headline-960-top' => '15',
				'plsbvs-headline-960-left' => '10',
				'plsbvs-headline-960-font-size' => '16',
				'plsbvs-headline-960-line-height' => '20',
				'plsbvs-name-field-960-top' => '15',
				'plsbvs-name-field-960-left' => '15',
				'plsbvs-name-field-960-font-size' => '14',
				'plsbvs-name-field-960-line-height' => '14',
				'plsbvs-email-field-960-top' => '15',
				'plsbvs-email-field-960-left' => '20',
				'plsbvs-email-field-960-font-size' => '14',
				'plsbvs-email-field-960-line-height' => '14',
				'plsbvs-subscribe-button-960-top' => '15',
				'plsbvs-subscribe-button-960-left' => '25',
				'plsbvs-subscribe-button-text-960-font-size' => '16',
				'plsbvs-subscribe-button-text-960-line-height' => '16',

				'plsbvs-width-640' => '300',
				'plsbvs-height-640' => '50',
				'plsbvs-headline-640-top' => '5',
				'plsbvs-headline-640-left' => '10',
				'plsbvs-headline-640-font-size' => '14',
				'plsbvs-headline-640-line-height' => '16',
				'plsbvs-name-field-640-top' => '8',
				'plsbvs-name-field-640-left' => '10',
				'plsbvs-name-field-640-font-size' => '10',
				'plsbvs-name-field-640-line-height' => '10',
				'plsbvs-email-field-640-top' => '8',
				'plsbvs-email-field-640-left' => '15',
				'plsbvs-email-field-640-font-size' => '10',
				'plsbvs-email-field-640-line-height' => '10',
				'plsbvs-subscribe-button-640-top' => '8',
				'plsbvs-subscribe-button-640-left' => '20',
				'plsbvs-subscribe-button-text-640-font-size' => '10',
				'plsbvs-subscribe-button-text-640-line-height' => '10',
			);
			$this->style_template_advanced_customization = array(
				'plsbvs-headline' => array(0, '.plsbvs-preview-headline', '#plsbvs-preview-headline'),
				'plsbvs-name-field' => array(1, '.plsbvs-preview-name', '#plsbvs-preview-name'),
				'plsbvs-email-field' => array(1, '.plsbvs-preview-email', '#plsbvs-preview-email'),
				'plsbvs-subscribe-button-text' => array(2, '.plsbvs-subscribe-button', '#plsbvs-subscribe-button'),
				'plsbvs-headline-960' => array(3, '.plsbvs-preview-headline-960'),
				'plsbvs-name-field-960' => array(4, '.plsbvs-preview-name-960'),
				'plsbvs-email-field-960' => array(4, '.plsbvs-preview-email-960'),
				'plsbvs-subscribe-button-text-960' => array(5, '.plsbvs-subscribe-button-960'),
				'plsbvs-headline-640' => array(3, '.plsbvs-preview-headline-640'),
				'plsbvs-name-field-640' => array(4, '.plsbvs-preview-name-640'),
				'plsbvs-email-field-640' => array(4, '.plsbvs-preview-email-640'),
				'plsbvs-subscribe-button-text-640' => array(5, '.plsbvs-subscribe-button-640'),
			);
			$this->style_template_checked_replace = array(
			);
		}

		public function sanitize_style($setting, $id, $is_active = false) {
			$setting = parent::sanitize_style($setting, $id, $is_active = false);

			return $setting;
		}

		public function prepare_for_code_generation($id, $style, $all_style) {
			$style = parent::prepare_for_code_generation($id, $style, $all_style);
			$style['plsbvs-background-image'] = PopupAllyTemplate::image_url_code_generation($style, 'plsbvs-image-url');

			$style['used-sign-up-form-fields'] = array();
			if (!empty($style['sign-up-form-email-field'])) {
				$style['used-sign-up-form-fields'] []= $style['sign-up-form-email-field'];
			}

			if (!empty($style['sign-up-form-name-field'])) {
				$style['plsbvs-sign-up-form-name-field'] = $style['sign-up-form-name-field'];
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
		/* $size_postfix: '' - normal display; '-960' - 960px width; '-640' - 640px width */
		public function generate_position_code($style, $size_postfix) {
			return 'top:50%;left:50%;margin-top:-' . (intval($style['plsbvs-height' . $size_postfix]) / 2) .
					'px;margin-left:-' . (intval($style['plsbvs-width' . $size_postfix]) / 2) . 'px;';
		}
		public function make_backwards_compatible($style) {
			if (isset($style['plsbvs-text-color'])) {
				$style['plsbvs-headline-color'] = $style['plsbvs-text-color'];
			}
			return $style;
		}
	}
	PopupAlly::add_template(new PopupAllyCleanTemplate());
}
