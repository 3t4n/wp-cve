<?php

namespace ZPOS\Admin\Tabs;

use ZPOS\Admin\Setting\PageTab;
use ZPOS\Admin\Tabs\Users\Access;
use ZPOS\Admin\Tabs\Users\UserSettings;
use ZPOS\Admin\Tabs\Users\Multiple;
use ZPOS\Deactivate;

class Users extends PageTab
{
	public $name;
	public $path = '/users';

	public function __construct()
	{
		parent::__construct();
		$this->name = __('Users', 'zpos-wp-api');
	}

	public function getBoxes()
	{
		return [UserSettings::class, Multiple::class, Access::class];
	}

	public function isVisible()
	{
		return in_array('administrator', wp_get_current_user()->roles);
	}

	/**
	 * @todo refactor this method later
	 */
	public static function getAllowedUsers()
	{
		if (!function_exists('get_editable_roles')) {
			require_once ABSPATH . '/wp-admin/includes/user.php';
		}

		$roles = array_keys(\get_editable_roles());
		$roles = array_filter($roles, function ($role) {
			$role = get_role($role);
			return $role->has_cap('access_woocommerce_pos');
		});
		return get_users([
			'role__in' => $roles,
		]);
	}

	public static function reset()
	{
		if (!did_action(Deactivate::class . '::resetSettings')) {
			return _doing_it_wrong(
				__METHOD__,
				'Reset POS settings should called by ' . Deactivate::class . '::resetSettings',
				'2.0.3'
			);
		}

		$boxes = [UserSettings::class, Multiple::class, Access::class];

		foreach ($boxes as $box) {
			if (method_exists($box, 'reset')) {
				call_user_func([$box, 'reset']);
			}
		}
	}
}
