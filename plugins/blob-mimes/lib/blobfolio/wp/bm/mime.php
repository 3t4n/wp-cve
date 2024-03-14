<?php
/**
 * Lord of the Files: Handling MIME Types.
 *
 * MIME and filetype management.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

use blobfolio\wp\bm\admin;
use blobfolio\wp\bm\mime\aliases;
use getID3;
use Throwable;



class mime {
	/**
	 * Return MIME aliases for a particular file extension.
	 *
	 * @see {https://www.iana.org/assignments/media-types}
	 * @see {https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types}
	 * @see {http://hg.nginx.org/nginx/raw-file/default/conf/mime.types}
	 * @see {https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in}
	 * @see {https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml}
	 * @see {https://github.com/Blobfolio/righteous-mimes}
	 *
	 * @public
	 *
	 * @param string $ext File extension.
	 * @return array|bool MIME types or false.
	 */
	public static function get_aliases(string $ext='') {
		// If the extension is totally bogus there's nothing for us to
		// even filter.
		if (! $ext || (null === $ext = self::_sanitize_ext($ext))) {
			return false;
		}

		$out = array();

		// Add our known aliases.
		if (isset(aliases::TYPES[$ext])) {
			$out = aliases::TYPES[$ext];

			// Add group data?
			if (
				'gz' !== $ext &&
				'zip' !== $ext &&
				isset(aliases::GROUPS[$ext])
			) {
				if (aliases::GROUP_GZIP & aliases::GROUPS[$ext]) {
					$out = \array_merge($out, aliases::TYPES['gz']);
				}
				if (aliases::GROUP_JSON & aliases::GROUPS[$ext]) {
					$out = \array_merge($out, aliases::TYPES['json']);
				}
				if (aliases::GROUP_TEXT & aliases::GROUPS[$ext]) {
					$out[] = 'text/plain';
				}
				if (aliases::GROUP_XML & aliases::GROUPS[$ext]) {
					$out[] = 'application/xml';
					$out[] = 'text/xml';
				}
				if (aliases::GROUP_ZIP & aliases::GROUPS[$ext]) {
					$out[] = 'application/zip';
					$out[] = 'application/octet-stream';
				}
			}
		}

		// WP's information *should* already be in our list but people
		// do weird things sometimes...
		if (null !== $type = self::_wp_mimes($ext)) {
			$out[] = $type;
		}

		if (empty($out)) {
			$out = false;
		}
		else {
			$out = \array_unique($out);
			\sort($out);
		}

		// Filter.
		return admin::filter('lotf_get_mime_aliases', array($out, $ext));
	}

	/**
	 * Assign a new extension to a filename.
	 *
	 * @public
	 *
	 * @param string $filename The original filename.
	 * @param string $ext The new extension.
	 * @return string The renamed file.
	 */
	public static function update_filename_extension(
		string $filename,
		string $ext
	) : string {
		$filename_parts = \explode('.', \trim($filename));

		// Remove the old extension.
		if (\count($filename_parts) > 1) {
			\array_pop($filename_parts);
		}

		// Add the new extension.
		if (null !== $ext = self::_sanitize_ext($ext)) {
			$filename_parts[] = $ext;
		}

		return \implode('.', $filename_parts);
	}

	/**
	 * Check extension and MIME pairing.
	 *
	 * @see {https://github.com/php/php-src/blob/3ef069d26c7863b059325b9a0d26cac31c97fe4b/ext/fileinfo/libmagic/readcdf.c}
	 *
	 * @public
	 *
	 * @param string $ext File extension.
	 * @param string $mime MIME type.
	 * @return bool True/false.
	 */
	public static function check_alias(string $ext='', string $mime='') : bool {
		// Standardize inputs. If these are totally bad we're done.
		if (
			! $ext ||
			! $mime ||
			(null === $ext = self::_sanitize_ext($ext)) ||
			(null === $mime = self::_sanitize_type($mime))
		) {
			return false;
		}

		// Can't continue if the extension is not in the database.
		if (false === ($mimes = self::get_aliases($ext))) {
			// Filter.
			return false;
		}

		// Before looking for matches, convert any generic CDFV2 types
		// into an equally generic, but less variable, type.
		if (0 === \strpos($mime, 'application/cdfv2')) {
			$mime = aliases::TYPE_OFFICE;
		}

		// "Default" and empty MIMEs don't need to trigger a failure.
		if (
			aliases::TYPE_DEFAULT === $mime ||
			aliases::TYPE_EMPTY === $mime
		) {
			return true;
		}

		// Overlap is success!
		if (null !== ($tests = self::_ambiguate_type($mime))) {
			$found = \array_intersect($tests, $mimes);
			$match = ! empty($found);
		}
		else {
			$match = false;
		}

		// Filter.
		return $match;
	}

	/**
	 * Check Allowed Aliases
	 *
	 * This will cycle through each allowed ext/MIME pair to see if an
	 * alias matches anything.
	 *
	 * @param string $alias MIME alias.
	 * @param ?array $mimes Allowed MIME types.
	 * @return array|bool Array containing ext and type keys or false.
	 */
	public static function check_allowed_aliases(string $alias, ?array $mimes=null) {
		// Default MIMEs.
		if (empty($mimes)) {
			$mimes = \get_allowed_mime_types();
		}

		if (
			empty($mimes) ||
			(null === $alias = self::_sanitize_type($alias))
		) {
			return false;
		}

		$ext = $type = false;

		// Direct hit!
		if (false !== $extensions = \array_search($alias, $mimes, true)) {
			$extensions = \explode('|', $extensions);
			$ext = $extensions[0];
			$type = $alias;

			return \compact('ext', 'type');
		}

		// Try all extensions.
		foreach ($mimes as $extensions=>$mime) {
			$extensions = \explode('|', $extensions);
			foreach ($extensions as $extension) {
				if (self::check_alias($extension, $alias)) {
					$ext = $extension;
					$type = $mime;

					return \compact('ext', 'type');
				}
			}
		}

		return false;
	}

	/**
	 * Retrieve the "real" file type from the file.
	 *
	 * This extends `wp_check_filetype()` to additionally consider
	 * content-based indicators of a file's true type.
	 *
	 * The content-based type will override the name-based type if
	 * available and included in the $mimes list.
	 *
	 * A false response will be set if the extension is not allowed, or
	 * if a "real MIME" was found and that MIME is not allowed.
	 *
	 * @see wp_check_filetype()
	 * @see wp_check_filetype_and_ext()
	 *
	 * @public
	 *
	 * @param string $file Full path to the file.
	 * @param ?string $filename The name of the file (may differ from $file due to $file being in a tmp directory).
	 * @param ?array  $mimes Optional. Key is the file extension with value as the mime type.
	 * @return array|bool Values with extension first and mime type or false.
	 */
	public static function check_real_filetype(
		string $file,
		?string $filename=null,
		?array $mimes= null
	) {
		// Default filename.
		if (empty($filename)) {
			$filename = \basename($file);
		}

		// Default MIMEs.
		if (empty($mimes)) {
			$mimes = \get_allowed_mime_types();
		}

		// Run a name-based check first.
		$clean = $checked = \wp_check_filetype($filename, $mimes);

		// We need a second copy for comparison purposes (namely
		// reliable lowercasing). If we end up deferring to WP's
		// original assessment, we'll return the original $checked
		// version.
		if ($clean['ext']) {
			$clean['ext'] = self::_sanitize_ext($clean['ext']) ?? false;
		}
		if ($clean['type']) {
			$clean['type'] = self::_sanitize_type($clean['type']) ?? false;
		}

		// Only dig deeper if we can.
		if (
			false !== $clean['ext'] &&
			false !== $clean['type'] &&
			false !== $real_mime = self::fileinfo($file, $filename)
		) {
			// Media files with generic extensions might be detected as
			// audio instead of video or vice versa.
			$tmp = $clean;
			self::_check_av_id3($file, $tmp['ext'], $tmp['type']);
			if (
				$tmp['type'] &&
				$tmp['ext'] &&
				\in_array(
					\strtolower($tmp['ext']),
					array('m4a', 'm4v', 'mp4', 'oga', 'ogg', 'ogv'),
					true
				)
			) {
				$checked['type'] = $tmp['type'];
				$checked['ext'] = $tmp['ext'];
				return $checked;
			}

			// We might need to override fileinfo.
			if ($real_mime !== $clean['type']) {
				// SVGs are often misidentified if they are missing the
				// leading XML tag and/or DOCTYPE declarations. It's
				// probably fine if the file begins with an opening SVG
				// tag and has a closing tag somewhere thereafter.
				if (
					'image/svg+xml' === $clean['type'] &&
					'svgz' !== $clean['ext']
				) {
					$tmp = @\file_get_contents($file);
					if (
						$tmp &&
						'<svg' === \substr(\trim(\strtolower($tmp)), 0, 4) &&
						false !== \stripos($tmp, '</svg>')
					) {
						$real_mime = 'image/svg+xml';
					}
				}

				// JSON doesn't have much magic for fileinfo to work
				// with, but we can assume if it parses, it's JSON.
				elseif (
					\function_exists('json_decode') &&
					self::_check_group_alias($clean['ext'], aliases::GROUP_JSON)
				) {
					$tmp = @\file_get_contents($file);
					if (\is_string($tmp) && $tmp) {
						$tmp2 = @\json_decode($tmp, true);
						if (null !== $tmp2) {
							$real_mime = $checked['type'];
						}
					}
				}

				// It is easy to confuse XML with HTML, usually because
				// a document is missing a leading XML tag.
				elseif (
					('text/html' === $real_mime) &&
					self::_check_group_alias($clean['ext'], aliases::GROUP_XML)
				) {
					$real_mime = $checked['type'];
				}
			}

			// Evaluate our real MIME.
			if (
				(false !== $real_mime) &&
				($real_mime !== $clean['type']) &&
				! self::check_alias($clean['ext'], $real_mime)
			) {
				// Maybe this type belongs to another allowed extension.
				if (false !== $result = self::check_allowed_aliases($real_mime, $mimes)) {
					// We can replace the type and/or extension if we
					// know of something better, *but* if both expected
					// and magic results are related to MS Office, we
					// we should just trust the expected.
					if (
						! $result['ext'] ||
						! self::_check_group_alias($checked['ext'], aliases::GROUP_OFFICE) ||
						! self::_check_group_alias($result['ext'], aliases::GROUP_OFFICE)
					) {
						$checked['ext'] = $result['ext'];
						$checked['type'] = $result['type'];
					}
				}
				// Otherwise reject the results.
				else {
					$checked['ext'] = false;
					$checked['type'] = false;
				}
			}
		}// End content-based type checking.

		// Filter.
		return $checked;
	}



	// -----------------------------------------------------------------
	// Internal Helpers
	// -----------------------------------------------------------------

	/**
	 * Fileinfo Wrapper
	 *
	 * Query the magic MIME of a given file without any alias handling,
	 * etc.
	 *
	 * @param string $file File.
	 * @param ?string $filename Filename.
	 * @return mixed Type or false.
	 */
	public static function fileinfo(string $file, ?string $filename) {
		// Easy fails.
		if (
			! \extension_loaded('fileinfo') ||
			! \defined('FILEINFO_MIME_TYPE') ||
			! @\is_file($file) ||
			! @\filesize($file)
		) {
			return false;
		}

		// If the file name doesn't match file's name, let's make a
		// copy so fileinfo can get full context!
		$cloned = false;
		if (
			$filename &&
			\basename($file) !== $filename &&
			\pathinfo($filename, \PATHINFO_EXTENSION)
		) {
			$tmp = \wp_unique_filename(\dirname($file), $filename);
			if ($tmp) {
				$clone = \dirname($file) . "/$tmp";
				@\copy($file, $clone);
				if (\is_file($clone)) {
					$cloned = true;
					$file = $clone;
				}
			}
		}

		try {
			// Fall back to fileinfo, if available.
			if (
				\extension_loaded('fileinfo') &&
				\defined('FILEINFO_MIME_TYPE')
			) {
				$finfo = \finfo_open(\FILEINFO_MIME_TYPE);
				$real_mime = \finfo_file($finfo, $file);
				\finfo_close($finfo);
				if ($real_mime) {
					// Fix a file info duplication bug.
					$real_mime = static::_fileinfo_77784(\strval($real_mime));

					// Account for inconsistent return values.
					if (null === $real_mime = self::_sanitize_type($real_mime)) {
						$real_mime = false;
					}
				}
				else {
					$real_mime = false;
				}
			}
		} catch (Throwable $e) {
			$real_mime = false;
		}

		// If we cloned the file to get here, remove it now.
		if ($cloned) {
			\unlink($file);
		}

		// Return what we've got.
		return $real_mime;
	}

	/**
	 * Ambiguate Type
	 *
	 * This converts a type/subtype into a combination of type/subtype,
	 * type/x-subtype, etc., for look-up convenience. Most or all of the
	 * results will be wrong.
	 *
	 * @param string $type Type.
	 * @return ?array Types.
	 */
	private static function _ambiguate_type(string $type) : ?array {
		$type = \strtolower(\sanitize_mime_type($type));
		if (
			! $type ||
			aliases::TYPE_DEFAULT === $type ||
			aliases::TYPE_EMPTY === $type
		) {
			return null;
		}

		list($type, $subtype) = \explode('/', $type);

		// Start with three obvious choices.
		$subtype = \preg_replace('/^(x\-|vnd\.)/', '', $subtype);
		$out = array(
			"$type/$subtype",
			"$type/x-$subtype",
			"$type/vnd.$subtype",
		);

		// Fonts have historically lived in either of two places, giving
		// us twice as many sources to check.
		if ('font' === $type) {
			$out[] = "application/font-$subtype";
			$out[] = "application/x-font-$subtype";
			$out[] = "application/vnd.font-$subtype";
		}
		// Equal and opposite to the above.
		elseif (0 === \strpos($subtype, 'font-')) {
			$font = \substr($subtype, 5);
			$out[] = "font/$font";
			$out[] = "font/x-$font";
			$out[] = "font/vnd.$font";
		}
		// Make office searching easier.
		elseif (0 === \strpos($out[0], 'application/cdfv2')) {
			$out[] = aliases::TYPE_OFFICE;
		}

		// Sort and return!
		$out = \array_unique($out);
		\sort($out);
		return $out;
	}

	/**
	 * Check AV ID3
	 *
	 * @param string $file File.
	 * @param string|bool $ext Extension.
	 * @param string|bool $type Type.
	 * @return void Nothing.
	 */
	private static function _check_av_id3(
		string $file,
		&$ext,
		&$type
	) : void {
		// This is only for MP4 and OGG content with ID3 tags.
		if (
			! $type ||
			! $ext ||
			! \in_array(
				\strtolower($ext),
				array('m4a', 'm4v', 'mp4', 'oga', 'ogg', 'ogv'),
				true
			) ||
			(null === $id3 = self::_id3($file))
		) {
			return;
		}

		switch (\strtolower($ext)) {
			case 'm4a':
			case 'm4v':
			case 'mp4':
				// It is really audio!
				if (
					// Because the MIME type says so.
					(
						isset($id3['mime_type']) &&
						(0 === \strpos($id3['mime_type'], 'audio/'))
					) ||
					// Because the signature says so.
					(
						isset($id3['quicktime']['ftype']['signature']) &&
						('M4A' === $id3['quicktime']['ftype']['signature'])
					) ||
					// Because there's no video.
					! isset(
						$id3['video']['resolution_x'],
						$id3['video']['resolution_y']
					) ||
					! $id3['video']['resolution_x'] ||
					! $id3['video']['resolution_y']
				) {
					$ext = 'm4a';
					$type = self::_wp_mimes($ext, true) ?? false;
				}
				// It must be video.
				else {
					if ('m4a' === \strtolower($ext)) {
						$ext = 'm4v';
					}
					$type = self::_wp_mimes('m4v', true) ?? false;
				}

				break;

			case 'oga':
			case 'ogg':
			case 'ogv':
				// It is really video!
				if (
					(
						isset($data['mime_type']) &&
						(0 === \strpos($data['mime_type'], 'video/'))
					) ||
					(
						isset(
							$id3['video']['resolution_x'],
							$id3['video']['resolution_y']
						) &&
						$id3['video']['resolution_x'] &&
						$id3['video']['resolution_y']
					)
				) {
					$ext = 'ogv';
					$type = self::_wp_mimes($ext, true) ?? false;
				}
				else {
					if ('ogv' === \strtolower($ext)) {
						$ext = 'oga';
						$type = self::_wp_mimes($ext, true) ?? false;
					}
				}

				break;
		}

		// Kill the extension if the type is dead.
		if (! $type) {
			$ext = false;
			$type = false;
		}
	}

	/**
	 * Check Group Alias
	 *
	 * @param string $ext Extension.
	 * @param int $group Group.
	 * @return bool True/false.
	 */
	private static function _check_group_alias(string $ext, int $group) : bool {
		return (bool) (
			0 < $group &&
			(null !== $ext = self::_sanitize_ext($ext)) &&
			isset(aliases::GROUPS[$ext]) &&
			($group & aliases::GROUPS[$ext])
		);
	}

	/**
	 * Fileinfo Double Fix
	 *
	 * This fixes a silly bug in `fileinfo.so` causing some (mostly MS
	 * Office) types to get duplicated like "application/typeapplication/type".
	 *
	 * @see {https://bugs.php.net/bug.php?id=77784}
	 *
	 * @param string $mime MIME.
	 * @return string MIME.
	 */
	private static function _fileinfo_77784(string $mime) : string {
		// MIME types should only have one "/", unless they've maybe been
		// duplicated!
		if (2 === \substr_count($mime, '/')) {
			// Cut the string in half and compare the halves to see if they're
			// the same.
			$len = (int) \strlen($mime);
			$halflen = (int) \floor($len / 2);
			if ($halflen * 2 === $len) {
				$a = \substr($mime, 0, $halflen);
				$b = \substr($mime, $halflen);
				if ($a === $b) {
					return $b;
				}
			}
		}

		return $mime;
	}

	/**
	 * Parse ID3 Tags
	 *
	 * @param string $file File path.
	 * @return ?array Details.
	 */
	private static function _id3(string $file) : ?array {
		if (! $file || ! \is_file($file)) {
			return null;
		}

		// Load ID3.
		if (! \defined('GETID3_TEMP_DIR')) {
			\define('GETID3_TEMP_DIR', \get_temp_dir());
		}
		require_once \ABSPATH . \WPINC . '/ID3/getid3.php';

		$id3 = new getID3();
		$data = $id3->analyze($file);

		return empty($data) ? null : $data;
	}

	/**
	 * Sanitize File Extension
	 *
	 * The result isn't necessarily valid, but rather is formatted the
	 * way we need for comparisons, etc.
	 *
	 * @param string $ext Extension.
	 * @return ?string Extension or null.
	 */
	public static function _sanitize_ext(string $ext) : ?string {
		$ext = \rtrim(\ltrim(\strtolower($ext), '.'), '.');
		return $ext ? $ext : null;
	}

	/**
	 * Sanitize MIME Type
	 *
	 * The result isn't necessarily valid, but rather is formatted the
	 * way we need for comparisons, etc.
	 *
	 * @param string $type Type.
	 * @return ?string Type or null.
	 */
	public static function _sanitize_type(string $type) : ?string {
		$type = \strtolower(\sanitize_mime_type($type));
		return $type ? $type : null;
	}

	/**
	 * Mapped WP MIME Types
	 *
	 * Return allowed MIMEs mapped for searching by extension.
	 * Unfortunately due to the nature of filters, we have to crunch
	 * this dynamically each time.
	 *
	 * @param ?string $ext Extension.
	 * @param bool $strict Only allowed types.
	 * @return mixed Type(s) or null.
	 */
	private static function _wp_mimes(?string $ext = null, bool $strict = false) {
		$search = null;
		if ($ext && (null === $search = self::_sanitize_ext($ext))) {
			return null;
		}

		$out = array();

		// Lord of the Files factors WordPress' default types into its
		// static list so we can ignore it unless a site hooks into the
		// "mime_types" filter.
		if (! $strict && \has_filter('mime_types')) {
			$tmp = \wp_get_mime_types();
			if (! empty($tmp)) {
				foreach ($tmp as $exts=>$type) {
					if (null === $type = self::_sanitize_type($type)) {
						continue;
					}

					// Just one extension.
					if (false === \strpos($exts, '|')) {
						if (null !== ($exts = self::_sanitize_ext($exts))) {
							$out[$exts] = $type;
						}
					}
					// Multiple extensions.
					else {
						$exts = \explode('|', $exts);
						foreach ($exts as $ext) {
							if (null !== $ext = self::_sanitize_ext($ext)) {
								$out[$ext] = $type;
							}
						}
					}
				}
			}
		}

		// We should always pull the allowed types explicitly.
		$tmp = \get_allowed_mime_types();
		if (! empty($tmp)) {
			foreach ($tmp as $exts=>$type) {
				if (null === $type = self::_sanitize_type($type)) {
					continue;
				}

				// Just one extension.
				if (false === \strpos($exts, '|')) {
					if (null !== ($exts = self::_sanitize_ext($exts))) {
						$out[$exts] = $type;
					}
				}
				// Multiple extensions.
				else {
					$exts = \explode('|', $exts);
					foreach ($exts as $ext) {
						if (null !== $ext = self::_sanitize_ext($ext)) {
							$out[$ext] = $type;
						}
					}
				}
			}
		}

		// Do we just want a specific entry?
		if ($search) {
			return $out[$search] ?? null;
		}

		// Sort and return them all.
		\ksort($out);
		return $out;
	}
}
