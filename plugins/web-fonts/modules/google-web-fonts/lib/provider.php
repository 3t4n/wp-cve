<?php

if(!class_exists('Google_Web_Fonts_Provider')) {
	
	class Google_Web_Fonts_Provider extends Web_Fonts_Provider {
		public static function get_provider_key() {
			return 'google-web-fonts';
		}
		
		public static function get_provider_name() {
			return __('Google Web Fonts');
		}
		
		public static function settings_page() {
			Google_Web_Fonts_Plugin::display_settings_page();
		}
		
		public static function settings_page_registered($settings_page_key) {
			Google_Web_Fonts_Plugin::$admin_page_hooks[] = $settings_page_key;
			
			add_action("load-{$settings_page_key}", array('Google_Web_Fonts_Plugin', 'detect_submissions'));
		}
	}
}