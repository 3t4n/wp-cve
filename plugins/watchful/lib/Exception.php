<?php
/**
 * Watchful exception definitions.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     watchful
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful;

/**
 * Class for Watchful excepction definitions.
 */
class Exception extends \Exception {
	/**
	 * Exception data.
	 *
	 * @var mixed
	 */
	private $data = null;

	/**
	 * Constructor for our custom Exceptions. It is compatible with the
	 * standard php exceptions as well.
	 *
	 * @param string $message Either a full message or a short key similar to \WP_Error codes.
	 * @param int    $code    The inherited \Exception code, used as status code for the HTTP response.
	 * @param mixed  $data    Either some data related to the message or a \Throwable for the standard php \Exception.
	 */
	public function __construct( $message = '', $code = 0, $data = null ) {
		// Handle php standard format for Exceptions with $previous as 3rd parameter.
		if ( $data instanceof \Exception || $data instanceof \Throwable ) {
			return parent::__construct( $message, $code, $data );
		}

		$this->data = $data;
		return parent::__construct( $message, $code, null );
	}

	/**
	 * Get the exception data.
	 *
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}
}
