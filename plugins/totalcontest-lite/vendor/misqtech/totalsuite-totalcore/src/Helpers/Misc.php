<?php

namespace TotalContestVendors\TotalCore\Helpers;


use Closure;

/**
 * Class Misc
 * @package TotalContestVendors\TotalCore\Helpers
 */
class Misc {
	public static function purgePluginsCache() {
		if ( function_exists( 'w3tc_pgcache_flush' ) ):
			w3tc_pgcache_flush();
		elseif ( function_exists( 'wp_cache_clear_cache' ) ):
			wp_cache_clear_cache();
		elseif ( function_exists( 'rocket_clean_domain' ) ):
			rocket_clean_domain();
		elseif ( function_exists( 'hyper_cache_invalidate' ) ):
			hyper_cache_invalidate();
		elseif ( function_exists( 'wp_fast_cache_bulk_delete_all' ) ):
			wp_fast_cache_bulk_delete_all();
		elseif ( has_action( 'cachify_flush_cache' ) ):
			do_action( 'cachify_flush_cache' );
		endif;
	}


	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset
	 *
	 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
	 * @link https://www.skyverge.com/blog/down-the-rabbit-hole-wordpress-and-timezones/
	 *
	 * @return string valid PHP timezone string
	 */
	public static function timeZoneString() {
		// If site timezone string exists, return it.
		if ( $timezone = get_option( 'timezone_string' ) ):
			return $timezone;
		endif;

		// Get UTC offset, if it isn't set then return UTC.
		if ( 0 === ( $utc_offset = (int) get_option( 'gmt_offset', 0 ) ) ):
			return 'UTC';
		endif;

		// Adjust UTC offset from hours to seconds.
		$utc_offset *= 3600;

		// Attempt to guess the timezone string from the UTC offset.
		if ( $timezone = timezone_name_from_abbr( '', $utc_offset ) ):
			return $timezone;
		endif;

		// Last try, guess timezone string manually.
		foreach ( timezone_abbreviations_list() as $abbr ):
			foreach ( $abbr as $city ):
				if ( (bool) date( 'I' ) === (bool) $city['dst'] && $city['timezone_id'] && (int) $city['offset'] === $utc_offset ):
					return $city['timezone_id'];
				endif;
			endforeach;
		endforeach;

		// Fallback to UTC.
		return 'UTC';
	}

	/**
	 * Is doing AJAX.
	 *
	 * @return bool
	 */
	public static function isDoingAjax() {
		return defined( 'DOING_AJAX' ) && DOING_AJAX;
	}

	/**
	 * Is REST API request.
	 *
	 * @return bool
	 */
	public static function isRestRequest() {
		return defined( 'REST_REQUEST' ) && REST_REQUEST;
	}

	/**
	 * Is development mode active.
	 *
	 * @return bool
	 */
	public static function isDevelopmentMode() {
		return WP_DEBUG;
	}

	/**
	 * Get debug information.
	 *
	 * @return array
	 */
	public static function getDebugInfo() {

		// System information
		$details = [
			'PHP'       => [
				'Version'              => PHP_VERSION,
				'OS'                   => PHP_OS,
				'Extensions'           => implode( ', ', get_loaded_extensions() ),
				'Memory Limit'         => size_format( wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) ) ),
				'Post Max Size'        => size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ),
				'Upload max file size' => size_format( wp_convert_hr_to_bytes( ini_get( 'upload_max_filesize' ) ) ),
				'Time Limit'           => ini_get( 'max_execution_time' ),
				'Max Input Vars'       => number_format( ini_get( 'max_input_vars' ) ),
				'Display Errors'       => ini_get( 'display_errors' ) ? ini_get( 'display_errors' ) : 'OFF',
			],
			'Database'  => [
				'Version' => \TotalContestVendors\TotalCore\Application::get( 'database' )->get_var( 'SELECT VERSION()' ),
				'Tables'  => array_map(
					function ( $item ) {
						unset( $item['Row_format'], $item['Version'], $item['Data_free'], $item['Max_data_length'], $item['Check_time'], $item['Create_time'], $item['Update_time'], $item['Checksum'], $item['Create_options'], $item['Comment'] );

						return [
							'Name'      => $item['Name'],
							'Rows'      => $item['Rows'],
							'Data'      => size_format( $item['Data_length'] ),
							'Index'     => size_format( $item['Index_length'] ),
							'Engine'    => $item['Engine'],
							'Collation' => $item['Collation'],
						];
					},
					\TotalContestVendors\TotalCore\Application::get( 'database' )->get_results( 'SHOW TABLE STATUS', ARRAY_A )
				),
			],
			'Server'    => [
				'Software' => $_SERVER['SERVER_SOFTWARE'],
				'Port'     => $_SERVER['SERVER_PORT'],
				'Protocol' => $_SERVER['SERVER_PROTOCOL'],
			],
			'Sessions'  => [
				'Enabled'          => isset( $_SESSION ) ? 'ON' : 'OFF',
				'Name'             => ini_get( 'session.name' ),
				'Path'             => ini_get( 'session.save_path' ),
				'Use Cookies'      => ini_get( 'session.use_cookies' ) ? 'ON' : 'OFF',
				'Use Only Cookies' => ini_get( 'session.use_only_cookies' ) ? 'ON' : 'OFF',
				'Cookie Path'      => ini_get( 'session.cookie_path' ),
			],
			'Cookies'   => [
				'Domain' => ( COOKIE_DOMAIN ? COOKIE_DOMAIN : 'N/A' ),
				'Path'   => SITECOOKIEPATH,
			],
			'WordPress' => [
				'Version'                 => $GLOBALS['wp_version'],
				'Locale'                  => get_locale(),
				'MU'                      => is_multisite() ? 'ON' : 'OFF',
				'Home'                    => get_option( 'home' ),
				'Memory Limit'            => size_format( wp_convert_hr_to_bytes( WP_MEMORY_LIMIT ) ),
				'Max Memory Limit'        => size_format( wp_convert_hr_to_bytes( WP_MAX_MEMORY_LIMIT ) ),
				'Short Initialization'    => SHORTINIT ? 'ON' : 'OFF',
				'Debug Mode'              => WP_DEBUG ? 'ON' : 'OFF',
				'Debug Script'            => SCRIPT_DEBUG ? 'ON' : 'OFF',
				'Debug Log'               => WP_DEBUG_LOG ? 'ON' : 'OFF',
				'Cache'                   => WP_CACHE ? 'ON' : 'OFF',
				'Force SSL'               => FORCE_SSL_ADMIN ? 'ON' : 'OFF',
				'Cron'                    => ! defined( 'DISABLE_WP_CRON' ) ? 'ON' : 'OFF',
				'Revisions'               => WP_POST_REVISIONS ? ( WP_POST_REVISIONS ? 'ON' : 'OFF' ) : 'OFF',
				'Compress Stylesheets'    => defined( 'COMPRESS_CSS' ) && COMPRESS_CSS ? 'ON' : 'OFF',
				'Compress Scripts'        => defined( 'COMPRESS_SCRIPTS' ) && COMPRESS_SCRIPTS ? 'ON' : 'OFF',
				'Concatenate Scripts'     => defined( 'CONCATENATE_SCRIPTS ' ) && CONCATENATE_SCRIPTS ? 'ON' : 'OFF',
				'Enforce Gzip'            => defined( 'ENFORCE_GZIP ' ) && ENFORCE_GZIP ? 'ON' : 'OFF',
				'Directory Permissions'   => defined( 'FS_CHMOD_DIR' ) ? FS_CHMOD_DIR : 'OFF',
				'File Permissions'        => defined( 'FS_CHMOD_FILE' ) ? FS_CHMOD_DIR : 'OFF',
				'Filesystem Method'       => defined( 'FS_METHOD' ) ? FS_METHOD : get_filesystem_method(),
				'Proxy'                   => defined( 'WP_PROXY_HOST' ) ? 'ON' : 'OFF',
				'Block External Requests' => defined( 'WP_HTTP_BLOCK_EXTERNAL' ) && WP_HTTP_BLOCK_EXTERNAL ? 'ON' : 'OFF',
				'Save Queries'            => defined( 'SAVEQUERIES' ) ? ( SAVEQUERIES ? 'ON' : 'OFF' ) : 'OFF',
			],
			'Plugins'   => [],
		];

		// Plugins
		$plugins       = get_plugins();
		$activePlugins = get_option( 'active_plugins', [] );

		foreach ( $plugins as $path => $plugin ):
			// If the plugin isn't active, don't show it.
			if ( ! in_array( $path, $activePlugins ) ):
				continue;
			endif;

			$details['Plugins'][ $plugin['Name'] ] = $plugin['Version'];
		endforeach;


		// Multi-site Plugins
		if ( is_multisite() ) :
			$networkPlugins = wp_get_active_network_plugins();
			foreach ( $networkPlugins as $networkPluginFile ) :
				$networkPlugin = get_plugin_data( $networkPluginFile );

				$details['Network Active Plugins'][ $networkPlugin['Name'] ] = $networkPlugin['Version'];
			endforeach;
		endif;

		return $details;
	}

	/**
	 * Get site languages.
	 *
	 * @return array
	 */
	public static function getSiteLanguages() {
		// Available languages
		$languages = [];
		// Polylang
		if ( function_exists( 'pll_languages_list' ) ):
			$currentLocale = get_locale();
			$rawLanguages  = pll_languages_list( [ 'fields' => [] ] );
			foreach ( $rawLanguages as $language ):
				if ( $language->locale == $currentLocale ):
					continue;
				endif;

				$languages[] = [
					'code'      => $language->locale,
					'direction' => $language->is_rtl ? 'rtl' : 'ltr',
					'name'      => $language->name,
				];
			endforeach;
		endif;
		// WPML
		if ( function_exists( 'icl_get_languages' ) && ! empty( $GLOBALS['sitepress'] ) ):
			$currentLocale = get_locale();
			$rawLanguages  = icl_get_languages();
			foreach ( $rawLanguages as $language ):
				if ( $language['default_locale'] == $currentLocale ):
					continue;
				endif;

				$languages[] = [
					'code'      => $language['default_locale'],
					'direction' => $GLOBALS['sitepress']->is_rtl( $language['code'] ) ? 'rtl' : 'ltr',
					'name'      => $language['native_name'],
				];
			endforeach;
		endif;

		return $languages;
	}

	/**
	 * Get JSON from options table.
	 *
	 * @param $option
	 *
	 * @return array
	 */
	public static function getJsonOption( $option ) {
		return (array) json_decode( (string) get_option( $option, '' ), true );
	}

	/**
	 * Generates a UUID.
	 *
	 * @return string
	 */
	public static function generateUid() {
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
			mt_rand( 0, 0xffff ),
			mt_rand( 0, 0x0fff ) | 0x4000,
			mt_rand( 0, 0x3fff ) | 0x8000,
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	/**
	 * Return the value (with closure support).
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public static function value( $value ) {
		return $value instanceof Closure ? $value() : $value;
	}
}
