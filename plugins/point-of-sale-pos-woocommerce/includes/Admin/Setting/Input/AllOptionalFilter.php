<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class AllOptionalFilter extends InputBase
{
	protected $type = 'all_optional_filter';

	public function __construct($label, $name, $value, $values, $valueForAll, array $args = [])
	{
		$args['values'] = $values;
		$args['valueForAll'] = $valueForAll;
		if (isset($args['sanitize'])) {
			$args['_sanitize'] = $args['sanitize'];
		}
		$args['sanitize'] = [$this, 'sanitize'];
		parent::__construct($label, $name, $value, $args);
	}

	public function sanitize($value)
	{
		if ($value == $this->args['valueForAll']) {
			return $this->args['valueForAll'];
		}

		if (isset($this->args['_sanitize']) && is_callable($this->args['_sanitize'])) {
			return call_user_func($this->args['_sanitize'], $value);
		}

		return $value;
	}
}
