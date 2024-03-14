<?php

namespace ZPOS\Admin\Stations\Tabs;

use ZPOS\Admin\Setting\PostTab;
use ZPOS\Admin\Stations\Tabs\Users\AutoLogout;

class Users extends PostTab
{
	public $name = 'Users';
	public $path = '/users';

	public function getBoxes()
	{
		return [AutoLogout::class];
	}
}
