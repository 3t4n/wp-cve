<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class SwitchInput extends InputBase
{
	protected $type = 'switch_input';

	public function __construct($label, $name, $value, $input_label, $args = [])
	{
		$args['input_label'] = $input_label;
		parent::__construct($label, $name, $value, $args);
	}
}
