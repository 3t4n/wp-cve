<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class ActionConfirmLink extends InputBase
{
	protected $type = 'action-confirm-link';

	public function __construct($label, $src, $classname, $confirm, $args = [])
	{
		$args = array_merge(
			[
				'confirm' => $confirm,
				'text' => $label,
				'classname' => $classname,
			],
			$args
		);
		parent::__construct(null, null, $src, $args);
	}
}
