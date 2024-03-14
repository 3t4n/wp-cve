<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class Radio extends InputBase
{
	protected $type = 'radio';

	public function __construct($label, $name, $value, $values, array $args = [])
	{
		$args['values'] = $values;
		parent::__construct($label, $name, $value, $args);
	}
}
