<?php
/**
 * Apocalypse Meow Environmental Info.
 *
 * Some basic "about"-type info is needed in a few different places,
 * but didn't quite make sense to shove in any single one. So, here.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use blobfolio\wp\meow\vendor\common;

class about {
	// We don't want the vast majority of what the WP API is selling.
	const API_FIELDS = array(
		'active_installs'=>false,
		'added'=>false,
		'banners'=>false,
		'compatibility'=>false,
		'contributors'=>false,
		'description'=>false,
		'donate_link'=>false,
		'downloaded'=>false,
		'downloadlink'=>true,
		'group'=>false,
		'homepage'=>false,
		'icons'=>false,
		'last_updated'=>false,
		'rating'=>false,
		'ratings'=>false,
		'requires'=>false,
		'reviews'=>false,
		'screenshots'=>false,
		'sections'=>false,
		'short_description'=>false,
		'tags'=>false,
		'tested'=>false,
		'versions'=>false,
	);

	const PARSE_FIELDS = array(
		'Name'=>'Plugin Name',
		'Version'=>'Version',
		'Plugin URI'=>'Plugin URI',
		'Author'=>'Author',
		'Author URI'=>'Author URI',
		'License'=>'License',
		'License URI'=>'License URI',
	);

	protected static $local;
	protected static $remote;
	protected static $timezone;

	/**
	 * Get Remote Info
	 *
	 * Query the WordPress API to see what the latest official release
	 * looks like. We have to do it this way because update checks
	 * don't happen for plugins installed in Must-Use mode.
	 *
	 * @param string $key Key.
	 * @return mixed Values, value, or false.
	 */
	public static function get_remote($key=null) {
		if (\is_null(static::$remote)) {
			// First, try to pull the information from WordPress.
			require_once \ABSPATH . 'wp-admin/includes/plugin.php';
			require_once \ABSPATH . 'wp-admin/includes/plugin-install.php';
			static::$remote = \plugins_api(
				'plugin_information',
				array(
					'slug'=>'apocalypse-meow',
					'fields'=>static::API_FIELDS,
				)
			);
			if (\is_wp_error(static::$remote)) {
				static::$remote = array();
			}
			else {
				// StdClasses are stupid. Sorry. Not sorry.
				static::$remote = (array) static::$remote;
			}
		}

		if (! \is_null($key)) {
			return \array_key_exists($key, static::$remote) ? static::$remote[$key] : false;
		}

		return static::$remote;
	}

	/**
	 * Get Local Info
	 *
	 * Parse the index file for information about the local copy of the
	 * plugin.
	 *
	 * @param string $key Key.
	 * @return mixed Values, value, or false.
	 */
	public static function get_local($key=null) {
		if (\is_null(static::$local)) {
			require_once \ABSPATH . 'wp-admin/includes/plugin.php';
			static::$local = \get_file_data(\MEOW_INDEX, static::PARSE_FIELDS);
		}

		if (! \is_null($key)) {
			return \array_key_exists($key, static::$local) ? static::$local[$key] : false;
		}

		return static::$local;
	}

	/**
	 * Local Time
	 *
	 * WordPress hasn't historically had a sane, single way to pull
	 * timezone information for a blog, so we have to run through a
	 * bunch of crap.
	 *
	 * @return string Timezone.
	 */
	public static function get_timezone() {
		if (\is_null(static::$timezone)) {
			// Try the timezone string.
			if (false === (static::$timezone = \get_option('timezone_string', false))) {

				// Try a GMT offset.
				if (0.0 === ($utc_offset = (float) \get_option('gmt_offset', 0.0))) {
					static::$timezone = 'UTC';
				}
				// Pull proper tz abbreviation from the offset, or default to UTC.
				elseif (false === (static::$timezone = \timezone_name_from_abbr('', ($utc_offset * 3600), 0))) {
					static::$timezone = 'UTC';
				}
			}

			common\ref\sanitize::timezone(static::$timezone);
		}

		return static::$timezone;
	}
}
