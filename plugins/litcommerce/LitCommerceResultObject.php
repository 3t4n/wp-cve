<?php

class LitCommerce_Result_Object
{
	/** @var bool */
	public $success;

	/** @var string */
	public $message;

	/** @var array */
	public $data;

	public function __construct($success, $message = '', $data = array())
	{
		$this->success = $success;
		$this->message = $message;
		$this->data    = $data;
	}
}
