<?php

namespace ZPOS\Admin\Stations\Tabs\Users;

use ZPOS\Admin\Setting\CoreBox;
use ZPOS\Admin\Setting\Input\Select;
use ZPOS\Admin\Setting\PostTab;

class AutoLogout extends CoreBox
{
	protected $label;

	public function __construct($parent)
	{
		$this->label = __('User Auto Logout Action', 'zpos-wp-api');

		parent::__construct(
			$parent,
			$this->label,
			null,
			new Select(
				null,
				'pos_auto_logout',
				$parent->getValue('pos_auto_logout'),
				self::get_logout_values(),
				['sanitize' => [$this, 'sanitize']]
			)
		);
	}

	public static function getDefaultValue($value, $post, $name)
	{
		switch ($name) {
			case 'pos_auto_logout':
				list($default) = self::getHelperData();
				return $default;
			default:
				return $value;
		}
	}

	public static function getHelperData()
	{
		$keys = array_map(function ($option) {
			return $option['value'];
		}, self::get_logout_values());
		$default = $keys[0];
		return [$default, $keys];
	}

	public function sanitize($data)
	{
		list($default, $keys) = self::getHelperData();

		return in_array($data, $keys) ? $data : $default;
	}

	public static function get_logout_values()
	{
		return [
			['value' => '0', 'label' => __('Disable auto logout', 'zpos-wp-api')],
			['value' => '5', 'label' => __('Auto logout in 5 mins', 'zpos-wp-api')],
			['value' => '15', 'label' => __('Auto logout in 15 mins', 'zpos-wp-api')],
			['value' => '30', 'label' => __('Auto logout in 30 mins', 'zpos-wp-api')],
			['value' => '60', 'label' => __('Auto logout in 60 mins', 'zpos-wp-api')],
		];
	}
}
