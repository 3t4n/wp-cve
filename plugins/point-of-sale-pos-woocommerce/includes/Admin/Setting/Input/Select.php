<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class Select extends InputBase
{
	protected $type = 'select';

	/**
	 * @param $label string|null
	 * @param $name string
	 * @param $value callable|mixed
	 * @param $values callable|mixed
	 * @param array $args
	 */
	public function __construct($label, $name, $value, $values, array $args = [])
	{
		$args['values'] = $values;
		parent::__construct($label, $name, $value, $args);
	}

	public function getData(...$args)
	{
		$data = parent::getData(...$args);
		$extend_data = [
			'values' => is_callable($this->args['values'])
				? call_user_func_array($this->args['values'], $args)
				: $this->args['values'],
		];
		return array_merge($data, $extend_data);
	}
}
