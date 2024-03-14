<?php

namespace UltimateStoreKit\Classes;

if (!defined('ABSPATH'))  exit; // Exit if accessed directly

class Utils {


	public static function get_site_domain() {
		return str_ireplace('www.', '', parse_url(home_url(), PHP_URL_HOST));
	}

	public static function readable_num($size) {
		$l    = substr($size, -1);
		$ret  = substr($size, 0, -1);
		$byte = 1024;

		switch (strtoupper($l)) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}
		return $ret;
	}

	/**
	 * For get wp environment for ultimate store kit
	 * @return [type] [description]
	 */
	public static function get_environment_info() {

		// Figure out cURL version, if installed.
		$curl_version = '';
		if (function_exists('curl_version')) {
			$curl_version = curl_version();
			$curl_version = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
		}


		// WP memory limit.
		$wp_memory_limit = self::readable_num(WP_MEMORY_LIMIT);
		if (function_exists('memory_get_usage')) {
			$wp_memory_limit = max($wp_memory_limit, self::readable_num(@ini_get('memory_limit')));
		}


		return array(
			'home_url'                  => get_option('home'),
			'site_url'                  => get_option('siteurl'),
			'version'                   => BDTUSK_VER,
			'wp_version'                => get_bloginfo('version'),
			'wp_multisite'              => is_multisite(),
			'wp_memory_limit'           => $wp_memory_limit,
			'wp_debug_mode'             => (defined('WP_DEBUG') && WP_DEBUG),
			'wp_cron'                   => !(defined('DISABLE_WP_CRON') && DISABLE_WP_CRON),
			'language'                  => get_locale(),
			'external_object_cache'     => wp_using_ext_object_cache(),
			'php_version'               => phpversion(),
			'php_post_max_size'         => self::readable_num(ini_get('post_max_size')),
			'php_max_execution_time'    => ini_get('max_execution_time'),
			'php_max_input_vars'        => ini_get('max_input_vars'),
			'curl_version'              => $curl_version,
			'suhosin_installed'         => extension_loaded('suhosin'),
			'max_upload_size'           => wp_max_upload_size(),
			'default_timezone'          => date_default_timezone_get(),
			'fsockopen_or_curl_enabled' => (function_exists('fsockopen') || function_exists('curl_init')),
			'soapclient_enabled'        => class_exists('SoapClient'),
			'domdocument_enabled'       => class_exists('DOMDocument'),
			'gzip_enabled'              => is_callable('gzopen'),
			'mbstring_enabled'          => extension_loaded('mbstring'),
		);
	}
}
