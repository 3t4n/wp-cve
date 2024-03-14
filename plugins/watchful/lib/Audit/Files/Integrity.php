<?php
/**
 * Watchful file integrity checker.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful file integrity class.
 */
class Integrity extends AuditProcess {


	/**
	 * Compare the hashes of the core files
	 *
	 * @param int $start The start index.
	 *
	 * @return \stdClass
	 */
	public function auditCoreIntegrity( $start ) {
		$connection = new Connection();
		$data       = $connection->get_hash();
		$current    = $start;

		$result          = new \stdClass();
		$result->wrong   = array(); // Files with wrong checksums.
		$result->missing = array(); // Files missing.
		$result->skipped = array(); // Files skipped.
		$result->size    = count( $data );
		$result->start   = $start;

		$data_count = count( $data );
		while ( $this->have_time() && $current < $data_count ) {
			$file_path = $data[ $current ][0];
			$file_hash = $data[ $current ][1];
			$full_path = str_replace( 'wordpress/', ABSPATH, $data[ $current ][0] );

			$status = $this->check_integrity_file( $full_path, $file_hash, $this->get_memory_limit_in_bytes() );

			if ( 'ok' !== $status ) {
				array_push( $result->$status, preg_replace( '#^wordpress/#', '/', $file_path ) );
			}
			$current++;
		}

		$result->lastFileChecked = $file_path; // phpcs:ignore WordPress.NamingConventions.ValidVariableName
		$result->end             = $current;

		return $result;
	}

	/**
	 * Compare the md5 hash of a file with a reference.
	 *
	 * @param string $full_path    The full path of the file.
	 * @param string $file_hash    The hash of the file.
	 * @param int    $memory_limit The memory limit.
	 *
	 * @return string
	 */
	private function check_integrity_file( $full_path, $file_hash, $memory_limit ) {
		if ( ! file_exists( $full_path ) ) {
			return 'missing';
		}

		$memory_usage = memory_get_usage();
		$file_size    = filesize( $full_path );

		// Let's hope the file can be read.
		$memory_needed = $memory_usage + $file_size;
		if ( $memory_needed > $memory_limit ) {
			return 'skipped';
		}

		// Does this file have a wrong checksum?
		if ( md5_file( $full_path ) !== $file_hash ) {
			return 'wrong';
		}

		return 'ok';
	}

	/**
	 * Get the memory limit in bytes.
	 *
	 * @return int
	 */
	private function get_memory_limit_in_bytes() {
		$memory_limit = ini_get( 'memory_limit' );
		switch ( substr( $memory_limit, -1 ) ) {
			case 'K':
				$memory_limit = (int) $memory_limit * 1024;
				break;

			case 'M':
				$memory_limit = (int) $memory_limit * 1024 * 1024;
				break;

			case 'G':
				$memory_limit = (int) $memory_limit * 1024 * 1024 * 1024;
				break;
		}

		return $memory_limit;
	}
}
