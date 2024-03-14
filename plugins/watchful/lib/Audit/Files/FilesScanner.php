<?php
/**
 * File scanner test class.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit\Files;

use Watchful\Audit\AuditProcess;
use Watchful\Helpers\Connection;
use Watchful\Helpers\FSPermissions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful file scanner test class.
 */
class FilesScanner extends AuditProcess {

	const MINIMUMFOLDERSPERMISSION = 755;
	const MINIMUMFILESPERMISSION   = 644;

	/**
	 * The file system structure.
	 *
	 * @var \stdClass
	 */
	private $structure;

	/**
	 * File hashes.
	 *
	 * @var array
	 */
	private $hashes;

	/**
	 * Non core files.
	 *
	 * @var array
	 */
	private $non_core_files;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		parent::__construct();

		$recursive_listing    = new RecursiveListing();
		$connection           = new Connection();
		$this->structure      = $recursive_listing->get_structure( ABSPATH );
		$this->hashes         = $connection->get_hash();
		$this->non_core_files = $recursive_listing->get_non_core_files( $this->structure, $this->hashes );
	}

	/**
	 * Audit the file system permissions.
	 *
	 * @param int $start The starting index.
	 *
	 * @return \stdClass
	 */
	public function auditFilesPermissions( $start = 0 ) {
		$files             = $this->structure->files;
		$result            = new \stdClass();
		$result->wrong     = array(); // Files with wrong permission.
		$result->unchecked = array(); // Files no checked.
		$result->size      = count( $files );
		$result->start     = $start;

		$current = $start;
		while ( $this->have_time() && $current < $result->size - 1 ) {
			$path_from_root = str_replace( ABSPATH, '/', $files[ $current ] );
			try {
				$permission = FSPermissions::from_path( $files[ $current ] );
				if ( $permission->is_higher( self::MINIMUMFILESPERMISSION ) ) {
					$result->wrong[] = array( $path_from_root, $permission->get_unix() );
				}
			} catch ( \Exception $e ) {
				$result->unchecked[] = array( $path_from_root, null );
			}
			$current++;
		}

		$result->lastFileChecked = $files[ $current ]; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
		$result->end             = $current;

		return $result;
	}

	/**
	 * Compare the hashes of the core files
	 *
	 * @param int $start The start index.
	 *
	 * @return \stdClass
	 */
	public function auditFoldersPermissions( $start = 0 ) {
		$folders = $this->structure->dirs;

		$result            = new \stdClass();
		$result->wrong     = array(); // Files with wrong permission.
		$result->unchecked = array(); // Files with wrong permission.
		$result->size      = count( $folders );
		$result->start     = $start;

		$current       = $start;
		$folders_count = count( $folders );
		while ( $this->have_time() && $current < $folders_count - 1 ) {
			$path_from_root = preg_replace( '#^' . substr( ABSPATH, 0, -1 ) . '#i', '', $folders[ $current ] );
			$path_from_root = ( '' === $path_from_root ) ? '/' : $path_from_root;

			try {
				$permission = FSPermissions::from_path( $folders[ $current ] );
				if ( $permission->is_higher( self::MINIMUMFOLDERSPERMISSION ) ) {
					$result->wrong[] = array( $path_from_root, $permission->get_unix() );
				}
			} catch ( \Exception $e ) {
				$result->unchecked[] = array( $path_from_root, null );
			}
			$current++;
		}
		$result->lastFolderChecked = $folders[ $current ]; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
		$result->end               = $current;

		return $result;
	}

	/**
	 * Audit malware scanner.
	 *
	 * @param int $start The start index.
	 *
	 * @return \stdClass
	 */
	public function auditMalwareScanner( $start = 0 ) {
		$files         = $this->non_core_files;
		$result        = new \stdClass();
		$result->wrong = array(); // Files with problems.
		$result->size  = count( $files );
		$result->start = $start;
		$connection    = new Connection();
		$signatures    = $connection->get_signatures();

		$current     = $start;
		$files_count = count( $files );
		while ( $this->have_time() && $current < $files_count - 1 ) {
			$check = $this->check_signatures( $files[ $current ], $signatures );
			if ( $check ) {
				$result->wrong[] = $check;
			}
			$current++;
		}

		$result->lastFileChecked = $files[ $current ]; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
		$result->end             = $current;

		return $result;
	}

	/**
	 * Check file signatures.
	 *
	 * @param string $file       The file to check.
	 * @param string $signatures The signatures to check.
	 * @return array|bool
	 */
	private function check_signatures( $file, $signatures ) {
		if ( ! $this->need_to_check_this_file( $file ) ) {
			return false;
		}

		$contents       = null;
		$file_extension = pathinfo( $file, PATHINFO_EXTENSION );
		$path_from_root = str_replace( ABSPATH, '/', $file );

		if ( 'php' === $file_extension ) {
			// Return content without comments.
			$contents = php_strip_whitespace( $file );
		}

		// If not a PHP file or if previous function return null
		// see PHP bug https://bugs.php.net/bug.php?id=29606.
		if ( empty( $contents ) ) {
			$contents = file_get_contents( $file );
		}

		foreach ( $signatures as $signature ) {
			if ( 'regex-' . $file_extension === $signature->type || 'regex' === $signature->type && 'php' === $file_extension ) {
				if ( preg_match_all( '#(\{|\(|\s|\/\*.*\*\/|@|^)' . $signature->signature . '#i', $contents, $matches ) ) {
					return array(
						'path'         => $path_from_root,
						'match'        => substr( $matches[0][0], 0, 50 ),
						'reason'       => $signature->reason,
						'signature_id' => $signature->id,
					);
				}
			} elseif ( 'regex-htaccess' === $signature->type && 'htaccess' === $file_extension ) {
				if ( preg_match_all( '#(\{|\(|\s|\/\*.*\*\/|@|^)' . $signature->signature . '#i', $contents, $matches ) ) {
					return array(
						'path'         => $path_from_root,
						'match'        => $matches[0],
						'reason'       => $signature->reason,
						'signature_id' => $signature->id,
					);
				}
			} elseif ( 'filename' === $signature->type ) {
				if ( preg_match( '#' . $signature->signature . '#i', basename( $file ), $match ) ) {
					return array(
						'path'         => $path_from_root,
						'match'        => $match[0],
						'reason'       => $signature->reason,
						'signature_id' => $signature->id,
					);
				}
			}
		}

		return false;
	}

	/**
	 * Determine if a file needs to be checked.
	 *
	 * @param string $path The path to the file.
	 *
	 * @return boolean
	 */
	private function need_to_check_this_file( $path ) {

		$safe_extensions = array(
			'DS_Store',
			'zip',
			'gzip',
			'gz',
			'doc',
			'docx',
			'xls',
			'xlsx',
			'ppt',
			'pptx',
			'pdf',
			'rtf',
			'mno',
			'ashx',
			'png',
			'psd',
			'wott',
			'ttf',
			'css',
			'swf',
			'flv',
			'po',
			'mo',
			'mov',
			'qt',
			'pot',
			'eot',
			'ini',
			'svg',
			'mpeg',
			'mvk',
			'mp3',
			'less',
			'sql',
			'wsdl',
			'woff',
			'xml',
			'php_expire',
			'jpa',
		);

		$exclude_paths = array(
			'%/akeeba_json.php',
			'%/akeeba_backend.id%.php',
			'%/akeeba_backend.php',
			'%/akeeba_backend.id%.log',
			'%/akeeba_backend.log',
			'%/akeeba_lazy.php',
			'%/akeeba_frontend.php',
			'%/stats/webalizer.current',
			'%/stats/usage_%.html',
			'%/components/libraries/cmslib/cache/cache__%',
			'%/cache/com_watchfulli%',
			'%/plugins/system/akgeoip/lib/vendor/guzzle/guzzle/%',
		);

		$path_parts = pathinfo( $path );

		// If file deleted after caching.
		if ( ! file_exists( $path ) ) {
			return false;
		}

		foreach ( $exclude_paths as $exclude_path ) {
			$exclude_regex = $this->generate_regex( $exclude_path );

			if ( preg_match( '#' . $exclude_regex . '#', $path_parts['dirname'] ) ) {
				return false;
			}
		}

		// Not check files > 2Mo.
		if ( filesize( $path ) > 2097152 ) {
			return false;
		}

		if ( isset( $path_parts['extension'] ) && in_array( $path_parts['extension'], $safe_extensions, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Generate Regex from path
	 * Input %/akeeba_json.php
	 * Output .*\/akeeba_json\.php
	 *
	 * @param string $path Path to be converted.
	 *
	 * @return string Regex
	 */
	private function generate_regex( $path ) {
		$patterns = array(
			'#\.#',
			'#/#',
			'#%#',
		);

		$replacements = array(
			'\.',
			'\/',
			'.*',
		);

		return preg_replace( $patterns, $replacements, $path );
	}

}
