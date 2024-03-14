<?php
if (!class_exists('PopupAllyStyleCodeGeneration')) {
	class PopupAllyStyleCodeGeneration {
		// 0 - normal popup, 1 - embedded, 2 - preview
		public static function generate_popup_html($id, $setting, $all_style, $mode = 0, $template_obj = null) {
			if (null === $template_obj) {
				$template_obj = PopupAlly::get_template($setting['selected-template']);
				if (!$template_obj) {
					return '';
				}
			}

			return PopupAllyUtilites::remove_newline($template_obj->generate_popup_html($id, $setting, $all_style, $mode));
		}

		// 0 - normal popup, 1 - preview
		public static function generate_popup_css($id, $setting, $all_style, $mode = 0, $template_obj = null) {
			if (null === $template_obj) {
				$template_obj = PopupAlly::get_template($setting['selected-template']);
				if (!$template_obj) {
					return '';
				}
			}
			return PopupAllyUtilites::remove_css_newline($template_obj->generate_popup_css($id, $setting, $all_style, $mode));
		}
	}
}