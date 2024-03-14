<?php
/**
 * ShareThis API client exception.
 *
 * @package GoogleAnalytics
 */

/**
 * ShareThis API client exception.
 */
class Ga_Lib_Sharethis_Api_Client_Exception extends Ga_Lib_Api_Client_Exception {

	/**
	 * Constructor.
	 *
	 * @param string $msg Message object.
	 */
	public function __construct( $msg ) {
		$data = json_decode( $msg, true );
		parent::__construct( ! empty( $data['error'] ) ? $data['error'] : $msg );
	}
}
