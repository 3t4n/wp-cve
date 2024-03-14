<?php

if(!class_exists('Web_Fonts_Provider')) {
	class Web_Fonts_Provider {
		public static function get_provider_key() {
			return sanitize_title_with_dashes(get_class($this));
		}
		
		public static function get_provider_name() {
			return sprintf(__('Please provide a name for this provider in the class %s.'), get_class($this));
		}
		
		public static function settings_page() {
			printf(__('Please implement the method settings_page in the class %s to display the content for this page.'), get_class($this));
		}
		
		public static function settings_page_registered($settings_page_key) {
			
		}
	}
}
