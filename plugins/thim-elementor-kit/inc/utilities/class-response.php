<?php
namespace Thim_EL_Kit\Utilities;

class Rest_Response {
	public $status  = 'error';
	public $message = '';
	public $data    = '';

	public function __construct() {
		$this->data = new \stdClass();
	}
}
