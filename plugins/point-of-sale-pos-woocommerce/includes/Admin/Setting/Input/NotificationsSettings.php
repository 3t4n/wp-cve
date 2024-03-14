<?php

namespace ZPOS\Admin\Setting\Input;

use ZPOS\Admin\Setting\InputBase;

class NotificationsSettings extends InputBase
{
	protected $type = 'notifications_settings';

	public function __construct($label, $name, $value, $values, $inputs, $args = [])
	{
		$args['values'] = $values;
		$args['inputs'] = $inputs;
		parent::__construct($label, $name, $value, $args);
	}
}
