<?php
/**
 * Watchful Robots.txt file test.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit\Tests;

use Watchful\Audit\Audit;
use Watchful\Helpers\Connection;

/**
 * Robots.txt file test class.
 */
class RobotsTxt extends Audit {

	/**
	 * File signatures.
	 *
	 * @var array
	 */
	private $signatures;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->loadSignatures();
	}

	/**
	 * Run the test.
	 *
	 * @return mixed
	 */
	public function run() {
		$file_path = ABSPATH . '/robots.txt';

		if ( ! file_exists( $file_path ) ) {
			return $this->response->send_ok();
		}

		$matches      = array();
		$temp_matches = array();

		$content = implode( '', file( $file_path, FILE_IGNORE_NEW_LINES ) );

		foreach ( $this->signatures as $signature ) {
			if ( 'regex.wp-robotstxt' !== $signature->type ) {
				continue;
			}

			if ( preg_match_all( $signature->signature, $content, $temp_matches ) ) {
				$matches = array_merge( $matches, $temp_matches[0] );
			}
		}

		if ( empty( $matches ) ) {
			return $this->response->send_ok();
		}

		return $this->response->send_ko( $matches );
	}

	/**
	 * Load a list of signatures, with cache
	 */
	private function loadSignatures() {
		$signatures = get_transient( 'signatures' );

		if ( false === $signatures ) {
			$connection = new Connection();
			$signatures = $connection->get_signatures();

			set_transient( 'signatures', $signatures, 6 * 3600 );
		}

		$this->signatures = $signatures;
	}
}
