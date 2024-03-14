<?php

namespace ZPOS\API;

use const ZPOS\REST_NAMESPACE;

class OrderNotes extends \WC_REST_Order_Notes_Controller
{
	protected $namespace = REST_NAMESPACE;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}
}
