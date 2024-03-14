<?php namespace Includes\Libraries\Exceptions;

class BaseApiException extends BaseException {

	/**
	 * @var array
	 */
	protected $errors;

	/**
	 * @param string $message
	 * @param array  $errors
	 */
	public function __construct( $message, $errors = array() ) {
		parent::__construct( $message );

		$this->errors = $errors;
	}

	public function getErrors() {
		return $this->errors;
	}
}
