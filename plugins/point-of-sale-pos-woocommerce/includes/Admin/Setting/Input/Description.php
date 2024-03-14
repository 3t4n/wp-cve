<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class Description extends InputBase
{
	protected $type = 'description';

	public function __construct($value, $label = null)
	{
		parent::__construct($label, null, $value);
	}
}
