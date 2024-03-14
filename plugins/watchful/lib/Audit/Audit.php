<?php
/**
 * Watchful auditor.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit;

use stdClass;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful Audit class.
 */
class Audit extends AuditProcess {

	/**
	 * List of passwords.
	 *
	 * @var array
	 */
	protected $passwords;

	/** @var ScannerResponse  */
	protected $response;

	/**
	 * The class constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->loadPasswords();
		$this->response = new ScannerResponse();
	}

	/**
	 * Compare two values and return the correct status
	 *
	 * @param mixed  $value          The given value to check.
	 * @param mixed  $expected_value The expected value to compare against.
	 * @param string $comparaison    The compareison enumeration: ==,<,>,<=,>=.
	 *
	 * @return stdClass
	 */
	public function check_value( $value, $expected_value, $comparaison = '==' ) {
		$map = array(
			'>=' => $value >= $expected_value,
			'>'  => $value > $expected_value,
			'<=' => $value <= $expected_value,
			'<'  => $value < $expected_value,
			'==' => $value == $expected_value, // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
			'!=' => $value != $expected_value, // phpcs:ignore WordPress.PHP.StrictComparisons.LooseComparison
		);

		if ( $map[ $comparaison ] ) {
			return $this->response->send_ok( $value );
		}
		return $this->response->send_ko( $value );
	}

	/**
	 * Load a list of password, with cache
	 */
	private function loadPasswords() {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        \WP_Filesystem();
        global $wp_filesystem;
        $passwords = $wp_filesystem->get_contents( WATCHFUL_PLUGIN_DIR . 'lib/Audit/Resources/weak_passwords.txt' );

        if ( ! empty( $passwords ) ) {
            $passwords = preg_split("/(\r\n|\n|\r)/", $passwords);
        }
        $this->passwords = $passwords;
	}

	/**
	 * Check that the file is not accessible.
	 *
	 * @param string $relative_path Path to the file.
	 *
	 * @return stdClass
	 */
	protected function checkFileAccess( $relative_path ) {
		$url = get_bloginfo( 'wpurl' ) . '/' . $relative_path . '?rnd=' . wp_rand();

		$response = wp_remote_get( $url );
		$code     = wp_remote_retrieve_response_code( $response );

		if ( ! is_wp_error( $response ) && 200 === $code ) {
			return $this->response->send_ko();
		}

		return $this->response->send_ok();
	}
}
