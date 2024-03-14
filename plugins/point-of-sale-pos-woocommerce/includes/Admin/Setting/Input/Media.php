<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class Media extends InputBase
{
	protected $type = 'media';

	public function __construct($label, $name, $value)
	{
		$args = [
			'sub_label' => $label
		];
		parent::__construct(null, $name, $value, $args);
	}
}
