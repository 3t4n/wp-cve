<?php
/**
 * Watchful scanner response.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful\Audit;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful scanner response class.
 */
class ScannerResponse {

	/**
	 * Get the response.
	 *
	 * @param mixed $values The values for the response.
	 * @param int   $error  The error code.
	 *
	 * @return \stdClass
	 */
	private function get_results( $values, $error ) {
		$rep         = new \stdClass();
		$rep->error  = $error;
		$rep->values = $values;
		return $rep;
	}

	/**
	 * Send a positive response with no errors.
	 *
	 * @param mixed $value The value for the response.
	 *
	 * @return \stdClass
	 */
	public function send_ok( $value = null ) {
		return $this->get_results( $value, 0 );
	}

	/**
	 * Send a negative response with an error code of 1.
	 *
	 * @param mixed $value The value for the response.
	 *
	 * @return \stdClass
	 */
	public function send_ko( $value = null ) {
		return $this->get_results( $value, 1 );
	}

	/**
	 * Send an unknown response.
	 *
	 * @param mixed $value The value for the response.
	 *
	 * @return \stdClass
	 */
	public function send_unknow( $value = null ) {
		return $this->get_results( $value, 999 );
	}
}
