<?php

namespace ZPOS\Admin\Tabs\Users;

use ZPOS\Admin\Setting\CoreBox;
use ZPOS\Admin\Setting\Input\Select;
use ZPOS\Admin\Tabs\Users;
use ZPOS\Deactivate;

class UserSettings extends CoreBox
{
	protected $label;

	public function __construct($parent)
	{
		$this->label = __('Manage User Access to Stations', 'zpos-wp-api');

		parent::__construct(
			$parent,
			$this->label,
			null,
			new Select(
				__('Cashier Role', 'zpos-wp-api'),
				'pos_user_free[cashier]',
				[$this, 'getCashier'],
				[$this, 'getValues']
			),
			new Select(
				__('Manager Role', 'zpos-wp-api'),
				'pos_user_free[manager]',
				[$this, 'getManager'],
				[$this, 'getValues']
			)
		);
	}

	public static function getUsers(): array
	{
		return get_option('pos_user_free', static::getDefaultUsers());
	}

	public function getCashier()
	{
		return static::getUsers()['cashier'];
	}

	public function getManager()
	{
		return static::getUsers()['manager'];
	}

	public static function init($path)
	{
		register_setting('pos' . $path, 'pos_user_free', [
			'default' => static::getDefaultUsers(),
		]);
	}

	public function getValues()
	{
		return array_map(function ($user) {
			return ['value' => $user->user_login, 'label' => $user->user_login];
		}, Users::getAllowedUsers());
	}

	protected static function getDefaultUsers()
	{
		$users = Users::getAllowedUsers();
		$defaultUser = isset($users[0]) ? $users[0]->user_login : '';

		return [
			'cashier' => $defaultUser,
			'manager' => $defaultUser,
		];
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

		delete_option('pos_user_free');
	}
}
