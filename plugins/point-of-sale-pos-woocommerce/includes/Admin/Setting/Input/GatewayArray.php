<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class GatewayArray extends InputBase
{
	protected $type = 'gateway_array';

	public function __construct($label, $name, $value, $description = null)
	{
		$args = [
			'description' => $description,
		];
		parent::__construct($label, $name, $value, $args);
	}
}
