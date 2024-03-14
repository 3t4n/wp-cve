<?php

// Prevent ourselves from being run directly.
	defined('ABSPATH') or die("No script kiddies please!");

	if (!function_exists('json_last_error_msg')) {

		function json_last_error_msg() {
			static $ERRORS = array(
				JSON_ERROR_NONE => 'No error',
				JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
				JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
				JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
				JSON_ERROR_SYNTAX => 'Syntax error',
				JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
			);

			$error = json_last_error();
			return isset($ERRORS[$error]) ? $ERRORS[$error] : 'Unknown error';
		}

	}

	class TBCNews_Plugin_Utils {// Although maybe namespace would suffice

		static $np_version = NULL;

		static function np_version() {
			if (self::$np_version) {
				return(self::$np_version);
			}
			if (self::$np_version = get_option('tbcnews_plugin_version')) {
				return(self::$np_version);
			}
			return(self::np_version_hard());
		}

		static function np_version_hard() {
			if (!function_exists('get_plugin_data')) {
				include( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_data = get_plugin_data(plugin_dir_path(__FILE__) . 'tbcnews-plugin.php');
			self::$np_version = $plugin_data['Version'];
			update_option('tbcnews_plugin_version', $plugin_data['Version']);
			update_option('tbcnews_plugin_version_taken', filemtime(plugin_dir_path(__FILE__) . 'tbcnews-plugin.php'));
			return(self::$np_version);
		}

	}

?>
