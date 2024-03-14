<?php
/**
 * API Response library.
 *
 * @package GoogleAnalytics
 */

/**
 * API Response Library response.
 */
class Ga_Lib_Api_Response {

	/**
	 * Emtpy response.
	 *
	 * @var string[]
	 */
	public static $empty_response = array( '', '' );

	/**
	 * Header.
	 *
	 * @var mixed
	 */
	private $header;

	/**
	 * Body.
	 *
	 * @var mixed
	 */
	private $body;

	/**
	 * Data.
	 *
	 * @var mixed
	 */
	private $data;

	/**
	 * Constructor.
	 *
	 * @param array|null $raw_response Raw response array.
	 */
	public function __construct( $raw_response = null ) {
		if ( false === empty( $raw_response ) ) {
			$this->set_header( $raw_response[0] );
			$this->set_body( $raw_response[1] );
			$this->set_data( json_decode( $raw_response[1], true ) );
		}
	}

	/**
	 * Set header.
	 *
	 * @param mixed $header Header.
	 *
	 * @return void
	 */
	public function set_header( $header ) {
		$this->header = $header;
	}

	/**
	 * Get header.
	 *
	 * @return mixed
	 */
	public function get_header() {
		return $this->header;
	}

	/**
	 * Set body.
	 *
	 * @param mixed $body Body.
	 *
	 * @return void
	 */
	public function set_body( $body ) {
		$this->body = $body;
	}

	/**
	 * Get body.
	 *
	 * @return mixed
	 */
	public function get_body() {
		return $this->body;
	}

	/**
	 * Set data.
	 *
	 * @param mixed $data Data.
	 *
	 * @return void
	 */
	public function set_data( $data ) {
		$this->data = $data;
	}

	/**
	 * Get data.
	 *
	 * @return mixed
	 */
	public function get_data() {
		return $this->data;
	}
}
