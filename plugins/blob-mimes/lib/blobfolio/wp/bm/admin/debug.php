<?php
/**
 * Lord of the Files: Admin Page (Debug File Validation)
 *
 * This allows administrators to (temporarily) upload any arbitrary file
 * to discover what type of content WordPress, FileInfo, and Lord of the
 * Files thinks it is, and whether or not WordPress would allow it in
 * the Media Library.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm\admin;

use blobfolio\wp\bm\mime;
use blobfolio\wp\bm\mime\aliases;



final class debug extends \blobfolio\wp\bm\admin\page {
	/**
	 * Page Slug
	 */
	const SLUG = 'debug';

	/**
	 * Page Title
	 */
	const TITLE = 'Debug File Validation';

	/**
	 * Debug Flags
	 */
	const FILE_ALLOWED = 1;
	const FILE_RENAMED = 2;
	const LOTF_MATCH = 4;
	const LOTF_OK = 8;
	const MAGIC_MATCH = 16;
	const MAGIC_OK = 32;
	const NAIVE_OK = 64;

	/**
	 * Test Results
	 *
	 * @var ?array
	 */
	protected static $_results;

	/**
	 * Upload MIMEs
	 *
	 * @var ?array
	 */
	protected static $_wp;



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
			'lotf-debug-css',
			\LOTF_BASE_URL . '/assets/debug.css',
			array(),
			'LOTF1'
		);

		\wp_enqueue_script(
			'lotf-debug-js',
			\LOTF_BASE_URL . '/assets/debug.min.js',
			array(),
			'LOTF1',
			true
		);
	}

	/**
	 * Admin Page: POST
	 *
	 * @return void Nothing.
	 */
	protected static function _admin_page_post() : void {
		if (
			! isset($_FILES['file']['tmp_name']) ||
			! \is_file($_FILES['file']['tmp_name']) ||
			(null === static::$_results = static::validate(
				$_FILES['file']['tmp_name'],
				$_FILES['file']['name']
			))
		) {
			static::_add_error(
				\__('The file could not be uploaded to the server.', 'blob-mimes')
			);
			return;
		}
	}

	/**
	 * Results
	 *
	 * @return ?array Results.
	 */
	public static function results() : ?array {
		return static::$_results ?? null;
	}

	/**
	 * System Details
	 *
	 * @return array Details.
	 */
	public static function system() : array {
		$out = array(
			'os'=>\php_uname('a'),
			'php'=>\phpversion(),
			'php_ext'=>\get_loaded_extensions(),
			'wp'=>\get_bloginfo('version'),
			'locale'=>\get_locale(),
			'plugins'=>array(),
			'theme'=>'',
		);

		// Extensions need sorting.
		\sort($out['php_ext']);

		// Pull plugin and theme info.
		require_once \ABSPATH . 'wp-admin/includes/plugin.php';
		$plugins_all = \get_plugins();
		$plugins_active = \get_option('active_plugins');
		$themes_all = \wp_get_themes();
		$theme_active = \basename(\get_stylesheet_directory());

		// Only include active plugins.
		if (! empty($plugins_active) && \is_array($plugins_active)) {
			foreach ($plugins_all as $k=>$v) {
				if (\in_array($k, $plugins_active, true)) {
					$out['plugins'][] = "{$v['TextDomain']} [{$v['Version']}]";
				}
			}
			\sort($out['plugins']);
		}

		// Only include the active theme.
		foreach ($themes_all as $k=>$v) {
			if ($k === $theme_active) {
				$out['theme'] = "{$theme_active} [{$v['Version']}]";
				break;
			}
		}

		return $out;
	}

	/**
	 * Test File Validation
	 *
	 * @param string $file File.
	 * @param string $filename File name.
	 * @return ?array Results.
	 */
	public static function validate(string $file, string $filename = '') : ?array {
		if (! $file || ! @\is_file($file)) {
			return null;
		}

		// Get a file name going.
		if (! $filename) {
			$filename = \basename($file);
		}

		$out = array(
			'naive_name'=>$filename,
			'naive_ext'=>false,
			'naive_type'=>false,
			'magic_type'=>false,
			'best_type'=>false,
			'name'=>$filename,
			'ext'=>false,
			'type'=>false,
			'status'=>0,
			'msg'=>array(),
		);

		// Start with WP.
		$info = \wp_check_filetype($filename);
		if (isset($info['ext'], $info['type'])) {
			if (
				$info['ext'] &&
				null !== $info['ext'] = mime::_sanitize_ext($info['ext'])
			) {
				$out['naive_ext'] = $info['ext'];
			}
			if (
				$info['type'] &&
				null !== $info['type'] = mime::_sanitize_type($info['type'])
			) {
				$out['naive_type'] = $info['type'];
			}
		}

		// Now magic.
		$out['magic_type'] = mime::fileinfo($file, $filename);

		// And lastly our corrected details.
		$info = mime::check_real_filetype($file, $filename);
		if (isset($info['ext'], $info['type'])) {
			if (
				$info['ext'] &&
				null !== $info['ext'] = mime::_sanitize_ext($info['ext'])
			) {
				$out['ext'] = $info['ext'];
			}
			if (
				$info['type'] &&
				null !== $info['type'] = mime::_sanitize_type($info['type'])
			) {
				$out['type'] = $info['type'];
			}
		}

		// Fix the name?
		if ($out['ext'] && $out['ext'] !== $out['naive_ext']) {
			$out['name'] = mime::update_filename_extension($filename, $out['ext']);
		}

		// Figure out the status flags.
		if ($out['naive_ext'] && $out['naive_type']) {
			$out['status'] |= self::NAIVE_OK;
		}

		if ($out['magic_type']) {
			if ($out['magic_type'] === $out['naive_type']) {
				$out['status'] |= self::MAGIC_OK;
				$out['status'] |= self::MAGIC_MATCH;
			}
			elseif (false !== \array_search(
				$out['magic_type'],
				self::_wp_mimes(),
				true
			)) {
				$out['status'] |= self::MAGIC_OK;
			}
		}

		if ($out['type']) {
			$out['status'] |= self::FILE_ALLOWED;
			$out['status'] |= self::LOTF_OK;

			if ($out['name'] === $out['naive_name']) {
				$out['status'] |= self::LOTF_MATCH;
			}
			else {
				$out['status'] |= self::FILE_RENAMED;
			}
		}

		if ($out['ext'] && isset(aliases::TYPES[$out['ext']][0])) {
			$out['best_type'] = aliases::TYPES[$out['ext']][0];
		}

		// Last thing: the status note(s).
		$msg_format = '<strong class="lotf__%s-result">%s:</strong> %s';
		$labels = array(
			'error'=>\esc_html__('Error', 'blob-mimes'),
			'hint'=>\esc_html__('Hint', 'blob-mimes'),
			'success'=>\esc_html__('Success', 'blob-mimes'),
			'warning'=>\esc_html__('Warning', 'blob-mimes'),
		);

		if (self::FILE_ALLOWED & $out['status']) {
			$out['msg'][] = \sprintf(
				$msg_format,
				'success',
				$labels['success'],
				\esc_html__("You *should* be able to upload this file. If it's not working, a plugin or theme might be responsible.", 'blob-mimes')
			);

			$out['msg'][] = \sprintf(
				$msg_format,
				'hint',
				$labels['hint'],
				\sprintf(
					\esc_html__('If the %s disagrees — i.e. it is rejecting this file — a plugin or theme might be responsible.', 'blob-mimes'),
					\sprintf(
						'<a href="%s">%s</a>',
						\admin_url('upload.php'),
						// Use the WordPress Core translation for this.
						\esc_html__('Media Library')
					)
				)
			);

			// Renamed?
			if (self::FILE_RENAMED & $out['status']) {
				$out['msg'][] = \sprintf(
					$msg_format,
					'warning',
					$labels['warning'],
					\sprintf(
						\esc_html__('The file would be renamed to %s to match its type.', 'blob-mimes'),
						'<code>' . \esc_html($out['name']) . '</code>'
					)
				);
			}
		}
		// Naive failed?
		elseif (! (self::NAIVE_OK & $out['status'])) {
			$ext = \pathinfo($filename, \PATHINFO_EXTENSION);
			if ($ext) {
				$ext = \strtolower($ext);

				$out['msg'][] = \sprintf(
					$msg_format,
					'error',
					$labels['error'],
					\sprintf(
						\esc_html__('WP does not allow uploads with an extension of %s.', 'blob-mimes'),
						"<code>$ext</code>"
					)
				);

				// Mention `upload_mimes` as plenty of people have no
				// idea such a thing exists.
				$hint = \sprintf(
					\esc_html__('To allow new upload file types, use the %s filter.', 'blob-mimes'),
					'<a href="https://developer.wordpress.org/reference/hooks/upload_mimes/" target="_blank" rel="noopener">upload_mimes</a>'
				);

				// If we can, recommend a type.
				if (isset(aliases::TYPES[$ext][0])) {
					$hint .= ' ' . \sprintf(
						\esc_html__('For %s files, the best type is (probably) %s.', 'blob-mimes'),
						"<code>$ext</code>",
						'<code>' . aliases::TYPES[$ext][0] . '</code>'
					);

					// Let's also set a best type.
					$out['best_type'] = aliases::TYPES[$ext][0];
				}

				// Record the hint.
				$out['msg'][] = \sprintf(
					$msg_format,
					'hint',
					$labels['hint'],
					$hint
				);
			}
			else {
				$out['msg'][] = \sprintf(
					$msg_format,
					'error',
					$labels['error'],
					\esc_html__('WP does not allow uploads without an extension.', 'blob-mimes')
				);
			}
		}
		// Magic failure?
		elseif (self::MAGIC_OK & $out['status']) {
			$out['msg'][] = \sprintf(
				$msg_format,
				'error',
				$labels['error'],
				\sprintf(
					\esc_html__('WP does not allow uploads with a file type of %s.', 'blob-mimes'),
					"<code>{$out['magic_ext']}</code>"
				)
			);
		}
		else {
			$out['msg'][] = \sprintf(
				$msg_format,
				'error',
				$labels['error'],
				\esc_html__('The file type could not be determined.', 'blob-mimes')
			);
		}

		return $out;
	}

	/**
	 * WP MIMEs
	 *
	 * Pull the upload MIMEs list in a format that is more search-
	 * friendly.
	 *
	 * @return array MIMEs.
	 */
	protected static function _wp_mimes() : array {
		if (null === static::$_wp) {
			static::$_wp = array();
			$tmp = \get_allowed_mime_types();
			if (! empty($tmp)) {
				foreach ($tmp as $exts=>$type) {
					$type = \strtolower($type);
					$exts = \explode('|', $exts);
					foreach ($exts as $ext) {
						$ext = \strtolower($ext);
						static::$_wp[$ext] = $type;
					}
				}
			}
			\ksort(static::$_wp);
		}

		return static::$_wp;
	}
}
