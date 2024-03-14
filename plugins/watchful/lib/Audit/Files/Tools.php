<?php
/**
 * Watchful file audit tools.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit\Files;

use Watchful\Exception;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful file audit tools class.
 */
class Tools {

	/**
	 * File path.
	 *
	 * @var string
	 */
	private $path;

	/**
	 * Tools constructor.
	 *
	 * @param string $path  The file path.
	 * @param bool   $write Flag to check if path is readable.
	 *
	 * @throws Exception If path not readable.
	 */
	public function __construct( $path, $write = false ) {
		$this->path = $path;

		if ( ! is_readable( $path ) && ! $write ) {
			throw new Exception( $path . ' path not readable', 400 );
		}
	}

	/**
	 * Read contents of a file
	 *
	 * @return string
	 */
	public function read() {
		$result = file_get_contents( $this->path );

		return $result;
	}

	/**
	 * Change permissions on a file
	 *
	 * @param string|octal $permissions The new file permissions.
	 *
	 * @return bool
	 *
	 * @throws Exception If permissions invalid.
	 */
	public function chmod( $permissions ) {
		if ( ! $this->is_valid_permission( $permissions ) ) {
			throw new Exception( 'permissions ' . $permissions . ' is invalid', 400 );
		}

		// Change permissions.
		$result = @chmod( $this->path, octdec( $permissions ) );

		if ( ! $result ) {
			throw new Exception( 'permissions could not be set', 400 );
		}

		// Done.
		return $result;
	}

	/**
	 * Delete a file or folder
	 *
	 * @return boolean
	 * @see Tools::rmdir
	 */
	public function delete() {
		if ( is_dir( $this->path ) ) {
			$result = $this->rmdir();
		} else {
			$result = unlink( $this->path );
		}

		return $result;
	}

	/**
	 * Writes data to a file
	 *
	 * @param string       $data      Data to write.
	 * @param string|octal $fileperms Permissions of file, optional.
	 * @param string|octal $dirperms  Permissions of file base directory, optional.
	 *
	 * @return bool
	 *
	 * @throws Exception If permissions are invalid.
	 */
	public function write( $data, $fileperms, $dirperms ) {
		if ( ! $this->is_valid_permission( $fileperms ) ) {
			throw new Exception( 'file permissions ' . $fileperms . ' are invalid', 400 );
		}

		if ( ! $this->is_valid_permission( $dirperms ) ) {
			throw new Exception( 'directory permissions ' . $dirperms . ' are invalid', 400 );
		}

		// Confirm that base directory exists before attempting to create file.
		$dir = dirname( $this->path );
		if ( ! is_dir( $dir ) ) {
			mkdir( $dir, octdec( $dirperms ), true );
		}
		// Write data to file.
		$result = file_put_contents( $this->path, $data );
		// Force permissions on file.
		$permissions = @chmod( $this->path, octdec( $fileperms ) );

		if ( ! $permissions ) {
			throw new Exception( 'fileperms could not be set', 400 );
		}

		restore_error_handler();

		// Technically, a file could be written with 0 bytes.
		return is_numeric( $result );
	}

	/**
	 * Internal directory removal
	 *
	 * @return boolean
	 * @see Tools::delete
	 */
	private function rmdir() {
		$recursive_listing = new RecursiveListing();
		$structure         = $recursive_listing->get_structure( $this->path );
		$result            = true;

		// Break cache for tree to delete in case it is requested somewhere else.
		$recursive_listing->clear_path_cache( $this->path );

		// Remove all files first.
		if ( ! empty( $structure->files ) ) {
			foreach ( $structure->files as $file ) {
				$result = $result && unlink( $file );
			}
			unset( $structure->files );
		}
		// Sort directories by reverse alphabetical order.
		// This should allow a walk through the array to rmdir each.
		if ( $result && ! empty( $structure->dirs ) ) {
			rsort( $structure->dirs );
			foreach ( $structure->dirs as $dir ) {
				if ( strpos( $dir, $this->path ) === false ) {
					continue;
				}

				$result = $result && rmdir( $dir );
			}
			unset( $structure->dirs );
		}

		return $result;
	}

	/**
	 * Check that the given permissions are valid
	 *
	 * @param string $permissions The permissions to check.
	 *
	 * @return bool
	 */
	private function is_valid_permission( $permissions ) {
		$chars = str_split( $permissions );

		foreach ( $chars as $char ) {
			if ( ! is_numeric( $char ) || $char > 7 || $char < 0 ) {
				return false;
			}
		}

		return true;
	}

}
