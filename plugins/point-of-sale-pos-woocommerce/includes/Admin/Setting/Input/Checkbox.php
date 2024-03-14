<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class Checkbox extends InputBase
{
	protected $type = 'checkbox';

	public function __construct($label, $name, $value, $checkbox, $args = [])
	{
		$args = array_merge(
			[
				'checkbox' => $checkbox,
			],
			$args
		);
		parent::__construct($label, $name, $value, $args);
	}
}
