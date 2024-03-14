<?php
/**
 * Helper for managing files.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Helpers;

use Watchful\Exception;
use Watchful\Audit\Files\Tools;

/**
 * Watchful Files helper class.
 */
class Files {

	/**
	 * Check that a remote file exists.
	 *
	 * @param string $url The remote URL.
	 *
	 * @return bool
	 */
	public function file_exists( $url ) {
        $response = wp_remote_get( $url, array('timeout' => 60) );
		$code     = wp_remote_retrieve_response_code( $response );

		return 200 === $code;
	}

	/**
	 * Get the first level of directories in a zip.
	 *
	 * @param string $zip The zip file location.
	 *
	 * @return array
	 *
	 * @throws Exception If the zip file does not exist.
	 */
	public function get_zip_directories( $zip ) {
		if ( ! $this->file_exists( $zip ) ) {
			throw new Exception( 'zip file does not exist', 404 );
		}

		$tmp_dir     = get_temp_dir();
		$has_tmp_dir = true;

		// Create a tmp dir if needed.
		if ( ! is_dir( $tmp_dir ) ) {
			mkdir( $tmp_dir );
			$has_tmp_dir = false;
		}

		if ( ! is_writable( $tmp_dir ) ) {
			throw new Exception( 'tmp directory is not writable', 403 );
		}

		$tmp_file     = $tmp_dir . '/' . md5( $zip ) . '.zip';
		$extract_path = $tmp_dir . '/' . md5( $zip );

		// Get the zip content.
		$connection = new Connection();
		$result     = $connection->get_curl(
			array(
				'url' => $zip,
			)
		);

		file_put_contents( $tmp_file, $result->data );

        wp_mkdir_p($extract_path);

        WP_Filesystem();

        $unzip = unzip_file($tmp_file, $extract_path);
        if (is_wp_error($unzip)) {
            throw new Exception( 'unable to unzip archive: '.$unzip->get_error_message(), 500 );
        }

		$directories     = array();
		$sub_directories = scandir( $extract_path );

		foreach ( $sub_directories as $directory ) {
			if ( '.' === $directory || '..' === $directory ) {
				continue;
			}

			if ( is_dir( $extract_path . '/' . $directory ) ) {
				$directories[] = $directory;
			}
		}

		// Remove tmp file (and tmp dir if it did not exist).
		unlink( $tmp_file );
		if ( ! $has_tmp_dir ) {
			rmdir( $tmp_dir );
		}

		// Delete the extracted directory.
		$tools = new Tools( $extract_path );
		$tools->delete();

		return $directories;
	}
}
