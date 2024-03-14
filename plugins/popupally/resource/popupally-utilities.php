<?php
if (!class_exists('PopupAllyUtilites')) {
	class PopupAllyUtilites {
		public static function customize_parameter_array($source, $num) {
			$result = array();
			foreach ($source as $tag => $value) {
				$result[$tag] = str_replace('{{num}}', $num, $value);
			}
			return $result;
		}

		public static function remove_newline($str) {
			if (is_array($str)) {
				foreach($str as $key => $value) {
					$str[$key] = self::remove_newline($value);
				}
				return $str;
			}
			$str = preg_replace("/>[\r|\n|\s]*</", '><', $str);
			return $str;
		}
		public static function remove_css_newline($str) {
			if (is_array($str)) {
				foreach($str as $key => $value) {
					$str[$key] = self::remove_newline($value);
				}
				return $str;
			}
			$str = str_replace("\r", '', $str);
			$str = str_replace("\n", '', $str);
			return $str;
		}

		public static function get_cached_code($option_name, $generator_function, $force = false) {
			if ($force) {
				$code = call_user_func($generator_function);
				update_option($option_name, $code);
				set_transient($option_name, $code, PopupAlly::CACHE_PERIOD);
				return $code;
			}
			$code = get_transient($option_name);

			if (!is_array($code) || !isset($code['version']) || $code['version'] !== PopupAlly::VERSION) {
				$code = get_option($option_name, 0);
				if (!is_array($code) || !isset($code['version']) || $code['version'] !== PopupAlly::VERSION) {
					$code = call_user_func($generator_function);
					update_option($option_name, $code);
				}
				set_transient($option_name, $code, PopupAlly::CACHE_PERIOD);
			}
			return $code;
		}
		public static function escape_html_string_literal($str) {
			$str = str_replace('&', '&amp;', $str);
			$str = str_replace('<', '&lt;', $str);
			$str = str_replace('>', '&gt;', $str);
			$str = str_replace("'", '&apos;', $str);
			$str = str_replace('"', '&quot;', $str);
			return $str;
		}
		public static function extract_array_values($source, $tags, $target) {
			foreach ($tags as $tag) {
				if (isset($source[$tag])) {
					$target[$tag] = $source[$tag];
				} else {
					$target[$tag] = '';
				}
			}
			return $target;
		}
		public static function generate_random_string($len) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randstring = '';
			for ($i = 0; $i < $len; $i++) {
				$randstring .= $characters[rand(0, strlen($characters) - 1)];
			}
			return $randstring;
		}
		public static function clear_wp_cache() {
			// clear WPEngine cache
			if (class_exists('WpeCommon')) {
				if (method_exists('WpeCommon', 'purge_memcached')) { 
					WpeCommon::purge_memcached();
				}
				if (method_exists('WpeCommon', 'clear_maxcdn_cache')) { 
					WpeCommon::clear_maxcdn_cache();
				}
				if (method_exists('WpeCommon', 'purge_varnish_cache')) { 
					WpeCommon::purge_varnish_cache();
				}
			}
			// clear W3 Total Cache cache
			if ( function_exists( 'w3tc_pgcache_flush' ) ) {
				w3tc_pgcache_flush(); 
			}
			// clear WP Super Cache
			if ( function_exists( 'wp_cache_clean_cache' ) ) {
				global $file_prefix;
				wp_cache_clean_cache($file_prefix);
			}
		}
		public static function get_script_folder_dir() {
			global $wp_filesystem;
			$dir = trailingslashit($wp_filesystem->wp_content_dir());
			$dir = trailingslashit($dir . PopupAlly::SCRIPT_FOLDER);
			return $dir;
		}
		public static function get_script_folder_url() {
			$url = content_url(PopupAlly::SCRIPT_FOLDER);
			return $url;
		}
	}
}