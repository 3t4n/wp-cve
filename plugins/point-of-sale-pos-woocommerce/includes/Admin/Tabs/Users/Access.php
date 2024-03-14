<?php

namespace ZPOS\Admin\Tabs\Users;

use ZPOS\Admin\Setting\CoreBox;
use ZPOS\Admin\Setting\Input\UserRights;

if (!function_exists('get_editable_roles')) {
	require_once ABSPATH . '/wp-admin/includes/user.php';
}

class Access extends CoreBox
{
	protected $label;

	private static $user_manage_rights = [
		'manage_woocommerce_pos',
		'delete_woocommerce_pos',
		'access_woocommerce_pos',
		'access_woocommerce_pos_addons',
		'read_woocommerce_pos_setting',
		'read_woocommerce_pos_categories',
		'read_woocommerce_pos_gateways',
		'read_private_products',
		'read_private_shop_orders',
		'publish_shop_orders',
		'list_users',
		'edit_product',
		'edit_published_products',
		'edit_others_products',
		'edit_users',
		'promote_users',
		'read_private_shop_coupons',
		'pay_for_order',
	];

	public function __construct($parent)
	{
		$this->label = __('Configure User Role Access Permissions', 'zpos-wp-api');

		parent::__construct(
			$parent,
			$this->label,
			null,
			new UserRights(
				null,
				'pos_user_rights',
				[$this, 'getUserRights'],
				['basicValue' => [$this, 'get_basic_value']]
			)
		);
	}

	public function getUserRights()
	{
		return get_option('pos_user_rights');
	}

	public static function init($path)
	{
		register_setting('pos' . $path, 'pos_user_rights', [
			'sanitize_callback' => function ($data) {
				$user_roles = array_keys(\get_editable_roles());

				$data = array_filter(
					$data,
					function ($key) use ($user_roles) {
						return in_array($key, $user_roles);
					},
					ARRAY_FILTER_USE_KEY
				);

				$data_keys = array_keys($data);
				$data = array_combine(
					$data_keys,
					array_map(
						function ($role_right, $role) {
							$role_right_default = static::get_empty_value();
							$role_right = array_filter(
								$role_right,
								function ($key) {
									return in_array($key, static::$user_manage_rights);
								},
								ARRAY_FILTER_USE_KEY
							);
							$role_right = array_map(function ($value) {
								return $value === 'on';
							}, $role_right);

							return array_merge(
								$role_right_default,
								$role_right,
								self::get_basic_role_value($role)
							);
						},
						$data,
						$data_keys
					)
				);

				foreach ($data as $role => $role_rights) {
					$role = get_role($role);
					foreach ($role_rights as $role_right => $value) {
						if ($value) {
							$role->add_cap($role_right);
						} else {
							$role->remove_cap($role_right);
						}
					}
				}

				return false;
			},
		]);

		add_filter('pre_option_pos_user_rights', [static::class, 'get_value']);
	}

	public static function get_value()
	{
		$roles = array_keys(\get_editable_roles());

		$roles = array_combine(
			$roles,
			array_map(function ($role) {
				$role = get_role($role);

				$capabilities = $role->capabilities;
				$name = ucwords(str_replace('_', ' ', $role->name));
				$default = static::get_empty_value();

				$capabilities = array_filter(
					$capabilities,
					function ($cap) {
						return in_array($cap, static::$user_manage_rights);
					},
					ARRAY_FILTER_USE_KEY
				);

				$capabilities = array_merge($default, $capabilities, self::get_basic_role_value($role));

				return compact('capabilities', 'name');
			}, $roles)
		);

		return $roles;
	}

	public function get_basic_value()
	{
		$roles = array_keys(\get_editable_roles());
		return array_combine(
			$roles,
			array_map(function ($role) {
				return self::get_basic_role_value($role);
			}, $roles)
		);
	}

	public static function get_basic_role_value($role)
	{
		$caps = self::get_basic_value_list($role);

		return array_combine($caps, array_fill(0, count($caps), true));
	}

	public static function get_basic_value_list($role)
	{
		switch ($role) {
			case 'administrator':
				return [
					'manage_woocommerce_pos',
					'access_woocommerce_pos',
					'access_woocommerce_pos_addons',
					'read_woocommerce_pos_setting',
					'read_woocommerce_pos_categories',
					'read_woocommerce_pos_gateways',
					'delete_woocommerce_pos',
					'pay_for_order',
				];
			case 'shop_manager':
				return [
					'manage_woocommerce_pos',
					'access_woocommerce_pos',
					'access_woocommerce_pos_addons',
					'read_woocommerce_pos_setting',
					'read_woocommerce_pos_categories',
					'read_woocommerce_pos_gateways',
					'pay_for_order',
				];

			case 'cashier':
				return [
					'access_woocommerce_pos',
					'read_woocommerce_pos_setting',
					'read_woocommerce_pos_categories',
					'read_woocommerce_pos_gateways',
					'pay_for_order',
				];
			case 'kiosk':
				return [
					'access_woocommerce_pos',
					'read_woocommerce_pos_setting',
					'read_woocommerce_pos_categories',
					'read_woocommerce_pos_gateways',
					'read_woocommerce_pos_single_coupons',
					'read_woocommerce_pos_single_customers',
					'read_shop_order',
					'publish_shop_orders',
					'edit_shop_orders',
				];
			default:
				return [];
		}
	}

	public static function get_empty_value()
	{
		return array_combine(
			static::$user_manage_rights,
			array_fill(0, count(static::$user_manage_rights), false)
		);
	}
}
