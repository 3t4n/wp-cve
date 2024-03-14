<?php
/**
 * Lord of the Files: Admin Hooks.
 *
 * This covers integrations and behavioral overrides.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

use blobfolio\wp\bm\admin\debug;
use blobfolio\wp\bm\admin\reference;
use blobfolio\wp\bm\admin\settings;
use blobfolio\wp\bm\mime;
use Throwable;

final class admin {
	/**
	 * Deprecated Filters
	 */
	const DEPRECATED_FILTERS = array(
		'blobmimes_get_mime_aliases'=>array(
			'version'=>'1.1.0',
			'replacement'=>'lotf_get_mime_aliases',
		),
		'blobmimes_svg_allowed_attributes'=>array(
			'version'=>'1.1.0',
			'replacement'=>'lotf_svg_allowed_attributes',
		),
		'blobmimes_svg_allowed_domains'=>array(
			'version'=>'1.1.0',
			'replacement'=>'lotf_svg_allowed_domains',
		),
		'blobmimes_svg_allowed_protocols'=>array(
			'version'=>'1.1.0',
			'replacement'=>'lotf_svg_allowed_protocols',
		),
		'blobmimes_svg_allowed_tags'=>array(
			'version'=>'1.1.0',
			'replacement'=>'lotf_svg_allowed_tags',
		),
	);

	/**
	 * Deprecations Warned About
	 *
	 * @var ?array
	 */
	private static $_deprecated;



	// -----------------------------------------------------------------
	// Init
	// -----------------------------------------------------------------

	/**
	 * Register Actions and Filters
	 *
	 * @return void Nothing.
	 */
	public static function init() : void {
		// Set up admin pages.
		debug::bind();
		reference::bind();
		settings::bind();

		// Override upload file validation (general).
		if (settings::get(settings::VALIDATE_TYPES)) {
			\add_filter(
				'wp_check_filetype_and_ext',
				array(static::class, 'check_filetype_and_ext'),
				10,
				4
			);
		}

		// Override upload file validation (SVG).
		if (settings::get(settings::SANITIZE_SVGS)) {
			\add_filter(
				'wp_check_filetype_and_ext',
				array(static::class, 'check_filetype_and_ext_svg'),
				15,
				4
			);
		}

		// Set up translations.
		\add_action('plugins_loaded', array(static::class, 'localize'));

		// Tidying up.
		\add_action('plugins_loaded', array(static::class, 'cleanup'));
	}

	/**
	 * Cleanup
	 *
	 * Remove legacy settings, etc., as the version changes.
	 *
	 * @return void Nothing.
	 */
	public static function cleanup() : void {
		// Already up-to-date?
		if (\LOTF_CLEANUP === ($level = \get_option('lotf_cleanup', ''))) {
			return;
		}

		// Remove the contributor monitoring CRON hook and related
		// options if present. This functionality was removed in 1.1.4,
		// but the hooks, they linger!
		if ($level < \LOTF_CLEANUP) {
			// Remove these options, if present.
			\delete_option('bm_contributor_notice');
			\delete_option('bm_remote_contributors');

			// Unhook the CRON job.
			$next = \wp_next_scheduled('cron_get_remote_contributors');
			if ($next) {
				\wp_unschedule_event($next, 'cron_get_remote_contributors');
			}

		}

		// Update the cleaned version to skip this next time around.
		\update_option('lotf_cleanup', \LOTF_CLEANUP, true);
	}

	/**
	 * Localize
	 *
	 * @return void Nothing.
	 */
	public static function localize() : void {
		if (\LOTF_MUST_USE) {
			\load_muplugin_textdomain(
				'blob-mimes',
				\basename(\LOTF_BASE_PATH) . '/languages'
			);
		}
		else {
			\load_plugin_textdomain(
				'blob-mimes',
				false,
				\basename(\LOTF_BASE_PATH) . '/languages'
			);
		}
	}



	// -----------------------------------------------------------------
	// File Validation
	// -----------------------------------------------------------------

	/**
	 * Override Upload File Validation
	 *
	 * This hooks into wp_check_filetype_and_ext() to improve its
	 * determinations.
	 *
	 * @see wp_check_filetype_and_ext()
	 *
	 * @param array $checked Previous check status.
	 * @param string $file File path.
	 * @param ?string $filename File name.
	 * @param mixed $mimes Mimes.
	 * @return array Checked status.
	 */
	public static function check_filetype_and_ext(
		array $checked,
		string $file,
		?string $filename,
		$mimes = null
	) : array {
		// Do basic extension validation and MIME mapping.
		$wp_filetype = mime::check_real_filetype(
			$file,
			$filename,
			(! $mimes || ! \is_array($mimes)) ? null : $mimes
		);
		$ext = $wp_filetype['ext'] ?? false;
		$type = $wp_filetype['type'] ?? false;
		$proper_filename = false;

		// We can't do any further validation without a file to work
		// with.
		if (! @\file_exists($file)) {
			return \compact('ext', 'type', 'proper_filename');
		}

		// If the type is valid, should we be renaming the file?
		if (false !== $ext && false !== $type) {
			// Filename should be set, but just in case...
			$filename = $filename ? $filename : \basename($file);

			$new_filename = mime::update_filename_extension($filename, $ext);
			if ($new_filename && $filename !== $new_filename) {
				$proper_filename = $new_filename;
			}
		}

		return \compact('ext', 'type', 'proper_filename');
	}

	/**
	 * Sanitize SVG Uploads
	 *
	 * This is triggered after our general content-based fixer, so if
	 * something is claiming to be an SVG here, it should actually be
	 * one.
	 *
	 * @see wp_check_filetype_and_ext()
	 *
	 * @param array $checked Previous check status.
	 * @param string $file File path.
	 * @param string $filename File name.
	 * @param array|bool $mimes Mimes.
	 * @return array Checked status.
	 */
	public static function check_filetype_and_ext_svg(
		array $checked,
		string $file,
		?string $filename,
		$mimes = null
	) : array {
		// Only need to do something if the type is SVG.
		if (
			isset($checked['ext'], $checked['type']) &&
			'image/svg+xml' === $checked['type'] &&
			'svgz' !== $checked['ext']
		) {
			try {
				$contents = @\file_get_contents($file);
				$contents = svg::sanitize($contents);

				// Overwrite the contents if we're good.
				if (\is_string($contents) && $contents) {
					@\file_put_contents($file, $contents);

					// In case it got renamed somewhere along the way.
					if ($checked['proper_filename']) {
						$checked['proper_filename'] = mime::update_filename_extension(
							$checked['proper_filename'],
							'.svg'
						);
					}
				}
				// Otherwise just fail the download.
				else {
					$checked['type'] = $checked['ext'] = false;
				}
			} catch (Throwable $e) {
				\error_log($e->getMessage());
				$checked['type'] = $checked['ext'] = false;
			}
		}

		return $checked;
	}



	// -----------------------------------------------------------------
	// Deprecation
	// -----------------------------------------------------------------

	/**
	 * Apply Filters
	 *
	 * This is a simple wrapper method for applying filter hooks. If it
	 * is discovered that a deprecated filter is being used, an
	 * appropriate notification will be logged, but only once per run.
	 *
	 * @param string $filter Filter.
	 * @param array $args Arguments.
	 * @return mixed Value.
	 */
	public static function filter(string $filter, array $args) {
		// Ignore bad data.
		if (! $filter || empty($args)) {
			return $args[0] ?? null;
		}

		$args[0] = self::_deprecated_filter($filter, $args);
		return \apply_filters($filter, ...$args);
	}

	/**
	 * Apply Deprecated Filter
	 *
	 * The unfortunate thing about deprecating filters is we often have
	 * to double-up on the applications.
	 *
	 * This handles applying deprecated calls only.
	 *
	 * @param string $filter Filter.
	 * @param array $args Arguments.
	 * @return mixed Value.
	 */
	private static function _deprecated_filter(string $filter, array $args) {
		// Most of the time the filter landing here will be a supported
		// one, in which case we should be doing a reverse lookup to
		// call any old ones it might be replacing.
		if (! isset(self::DEPRECATED_FILTERS[$filter])) {
			foreach (self::DEPRECATED_FILTERS as $old=>$v) {
				if ($v['replacement'] === $filter) {
					$filter = $old;
					break;
				}
			}

			// If we didn't find anything, there's nothing for us to do.
			if ($old !== $filter) {
				return $args[0] ?? null;
			}
		}

		// If nothing is bound to the filter, we're done.
		if (! \has_filter($filter)) {
			return $args[0] ?? null;
		}

		// Use the special deprecation method once per run to generate a
		// debug notice.
		if (! isset(self::$_deprecated[$filter])) {
			self::$_deprecated[$filter] = true;
			return \apply_filters_deprecated(
				$filter,
				$args,
				self::DEPRECATED_FILTERS[$filter]['version'] ?? '1.1.0',
				self::DEPRECATED_FILTERS[$filter]['replacement'] ?? false
			);
		}

		return \apply_filters($filter, ...$args);
	}
}
