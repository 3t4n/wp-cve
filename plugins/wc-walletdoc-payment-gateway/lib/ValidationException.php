<?php
/**
 * WalletdocWcValidationException
 * - used to generate the exception releted to validation which raised when response
 *   from Walletdoc server is not as desired.
 *   used to throw the Validation errors at the time of creating order.
 *	 used to throw the authentication failed errors.
 */
Class WalletdocWcValidationException extends Exception
{
	private $errors;
	private $apiResponse;
	function __construct($message,$errors,$apiResponse)
	{
		parent::__construct($message,0);
		$this->errors = $errors;
		$this->apiResponse = $apiResponse;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	public function getResponse(){
		return $this->apiResponse;
	}
}