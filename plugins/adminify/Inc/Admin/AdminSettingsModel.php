<?php

namespace WPAdminify\Inc\Admin;

use WPAdminify\Inc\Base_Model;

abstract class AdminSettingsModel extends Base_Model
{

	public $options = [];
	protected $prefix = '_wpadminify';
}
