<?php
/**
 * Lord of the Files: Admin Page (File Settings)
 *
 * Being able to change file security settings through the UI presents
 * a bit of a security risk, so instead we offer a simple configuration
 * wizard capable of generating the applicable code for copy-and-paste
 * ease.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm\admin;

final class settings extends \blobfolio\wp\bm\admin\page {
	/**
	 * Page Slug
	 */
	const SLUG = 'settings';

	/**
	 * Parent Menu
	 */
	const MENU = 'options-general.php';

	/**
	 * Page Title
	 */
	const TITLE = 'File Settings';

	/**
	 * Settings Flags
	 */
	const RENAME_BEFORE_REJECTION = 2;
	const SANITIZE_SVGS = 4;
	const VALIDATE_TYPES = 8;

	/**
	 * Current Settings
	 *
	 * @var ?int
	 */
	protected static $_settings;



	/**
	 * Admin Page: Scripts
	 *
	 * This version can be overloaded by the child safe in the knowledge
	 * that all conditions have been met.
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_scripts() : void {
		\wp_enqueue_style(
			'lotf-settings-css',
			\LOTF_BASE_URL . '/assets/settings.css',
			array(),
			'LOTF1'
		);

		\wp_enqueue_script(
			'lotf-settings-js',
			\LOTF_BASE_URL . '/assets/settings.min.js',
			array(),
			'LOTF1',
			true
		);
	}

	/**
	 * Settings
	 *
	 * @param int $setting Setting.
	 * @return int Setting(s).
	 */
	public static function get(int $setting = 0) : int {
		// We need to load them.
		if (null === static::$_settings) {
			// Start with everything.
			static::$_settings =
				self::RENAME_BEFORE_REJECTION |
				self::SANITIZE_SVGS |
				self::VALIDATE_TYPES;

			if (
				\defined('LOTF_NO_RENAME_BEFORE_REJECTION') &&
				\LOTF_NO_RENAME_BEFORE_REJECTION
			) {
				static::$_settings &= ~self::RENAME_BEFORE_REJECTION;
			}

			if (
				\defined('LOTF_NO_SANITIZE_SVGS') &&
				\LOTF_NO_SANITIZE_SVGS
			) {
				static::$_settings &= ~self::SANITIZE_SVGS;
			}

			if (
				\defined('LOTF_NO_VALIDATE_TYPES') &&
				\LOTF_NO_VALIDATE_TYPES
			) {
				static::$_settings &= ~self::VALIDATE_TYPES;
			}
		}

		// Return one answer.
		if ($setting) {
			return ($setting & static::$_settings);
		}

		return static::$_settings;
	}
}
