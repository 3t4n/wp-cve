<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class UserRights extends InputBase
{
	protected $type = 'user_rights';
	public $basicValue = null;

	public function __construct($label, $name, $value, $args = [])
	{
		if (isset($args['basicValue'])) {
			$this->basicValue = $args['basicValue'];
			unset($args['basicValue']);
		}
		parent::__construct($label, $name, $value, $args);
	}

	public function getData(...$args)
	{
		$parent_data = parent::getData(...$args);
		$basic_data = [
			'basicValue' => is_callable($this->basicValue)
				? call_user_func_array($this->basicValue, $args)
				: $this->basicValue,
		];
		return array_merge($this->args, $parent_data, $basic_data);
	}
}
