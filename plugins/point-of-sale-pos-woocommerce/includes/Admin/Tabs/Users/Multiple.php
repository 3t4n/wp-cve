<?php

namespace ZPOS\Admin\Tabs\Users;

use ZPOS\Admin;
use ZPOS\Admin\Setting\Box;
use ZPOS\Admin\Setting\CoreBox;

class Multiple extends CoreBox
{
	protected $label;

	public function __construct($parent)
	{
		$this->label = __('Manage Multiple Users Access to Stations', 'zpos-wp-api');

		parent::__construct($parent, $this->label, $this->getArgs());
	}

	public function getArgs()
	{
		return [
			'nav_native_link' => true,
			'nav_link_to' => 'https://jovvie.com/features/#users',
			'nav_link' => __('Learn More', 'zpos-wp-api'),
		];
	}
}
