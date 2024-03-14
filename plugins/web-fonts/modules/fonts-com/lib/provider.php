<?php

if(!class_exists('Fonts_Com_Provider')) {
	
	class Fonts_Com_Provider extends Web_Fonts_Provider {
		public static function get_provider_key() {
			return 'fonts-com';
		}
		
		public static function get_provider_name() {
			return __('Fonts.com');
		}
		
		public static function settings_page() {
			Fonts_Com_Plugin::display_settings_page();
		}
		
		public static function settings_page_registered($settings_page_key) {
			Fonts_Com_Plugin::$admin_page_hooks[] = $settings_page_key;
			
			add_action("load-{$settings_page_key}", array('Fonts_Com_Plugin', 'detect_submissions'));
		}
	}
}