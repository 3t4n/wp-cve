<?php

namespace ZPOS\API;

use WP_Error;
use WP_REST_Server, WP_REST_Controller, WP_REST_Request, WP_REST_Response;
use WP_User;
use const ZPOS\CLOUD_APP_NAME;
use const ZPOS\REST_NAMESPACE;
use const ZPOS\TEXTDOMAIN;

class Auth extends WP_REST_Controller
{
	protected $namespace = REST_NAMESPACE . '-auth';
	protected $rest_base = '';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes(): void
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);

		register_rest_route($this->namespace, '/validate', [
			[
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => [$this, 'validate'],
				'permission_callback' => [$this, 'check_permissions_to_validate'],
			],
		]);
	}

	public static function is_cloud_key_installed(): bool
	{
		$key_id = get_option('zpos_cloud_key_id');

		return is_int($key_id) || ctype_digit($key_id);
	}

	/**
	 * @return WP_REST_Response|WP_Error
	 */
	public function validate(WP_REST_Request $request)
	{
		$params = $request->get_params();

		foreach ($this->get_required_params_to_validate() as $required_param) {
			if (empty($params[$required_param])) {
				return new WP_Error(
					"missing_parameter_$required_param",
					sprintf(__('Error: Missing parameter %s', TEXTDOMAIN), $required_param),
					['status' => 400]
				);
			}
		}

		if (!filter_var($params['user_email'], FILTER_VALIDATE_EMAIL)) {
			return new WP_Error('invalid_user_email', __('Error: Invalid user_email', TEXTDOMAIN), [
				'status' => 400,
			]);
		}

		$consumer_key = wc_api_hash(sanitize_text_field($params['consumer_key']));
		$key_data = $this->get_data_by_consumer_key($consumer_key);

		if (empty($key_data)) {
			return new WP_Error('invalid_consumer_key', __('Error: Invalid consumer_key', TEXTDOMAIN), [
				'status' => 400,
			]);
		}

		if ('read_write' !== $key_data['permissions']) {
			return new WP_Error(
				'cloud_requires_read_write_permission',
				sprintf(__('Error: %s requires read_write permission', TEXTDOMAIN), CLOUD_APP_NAME),
				['status' => 400]
			);
		}

		$user_id = $key_data['user_id'];
		$key_owner = get_user_by('ID', $user_id);

		if (!$key_owner || empty($key_owner->user_email)) {
			return new WP_Error('invalid_key_owner', __('Error: Invalid key owner', TEXTDOMAIN), [
				'status' => 500,
			]);
		}

		$is_admin = user_can($user_id, 'administrator');
		$is_shop_manager = user_can($user_id, 'shop_manager');

		if (!$is_admin && !$is_shop_manager) {
			return new WP_Error(
				'key_owner_does_not_have_necessary_permissions',
				__('Error: Key owner does not have the necessary permissions', TEXTDOMAIN),
				['status' => 500]
			);
		}

		$pos_users_result = $this->update_pos_users($key_owner, $params['user_email']);

		if ($pos_users_result instanceof WP_Error) {
			return $pos_users_result;
		}

		update_option('zpos_cloud_key_id', intval($key_data['key_id']));

		return new WP_REST_Response([
			'success' => true,
			'assigned_users' => $pos_users_result,
		]);
	}

	public function check_permissions_to_validate(): bool
	{
		return current_user_can('read_woocommerce_pos_setting');
	}

	protected function get_required_params_to_validate(): array
	{
		return ['consumer_key', 'user_email'];
	}

	protected function get_data_by_consumer_key(string $consumer_key): array
	{
		global $wpdb;

		return $wpdb->get_row(
			$wpdb->prepare(
				"
			SELECT key_id, user_id, permissions
			FROM {$wpdb->prefix}woocommerce_api_keys
			WHERE consumer_key = %s
		",
				$consumer_key
			),
			ARRAY_A
		);
	}

	/**
	 * @return array|WP_Error
	 */
	protected function update_pos_users(WP_User $key_owner, string $user_email)
	{
		if (empty($key_owner->user_login)) {
			return new WP_Error(
				'key_owner_does_not_have_login',
				__('Error: Key owner does not have a login', TEXTDOMAIN),
				['status' => 500]
			);
		}

		$pos_users = [
			'manager' => $key_owner->user_login,
		];

		if ($user_email === $key_owner->user_email) {
			$pos_users['cashier'] = $key_owner->user_login;
		} else {
			$cloud_user = get_user_by('email', $user_email);

			if (!$cloud_user) {
				$cloud_user = $this->insert_cloud_user($user_email);

				if ($cloud_user instanceof WP_Error) {
					return $cloud_user;
				}
			}

			if (empty($cloud_user->user_login)) {
				return new WP_Error(
					'cloud_user_does_not_have_login',
					sprintf(__('Error: %s user does not have a login', TEXTDOMAIN), CLOUD_APP_NAME),
					['status' => 500]
				);
			}

			$pos_users['cashier'] = $cloud_user->user_login;
		}

		update_option('pos_user_free', $pos_users);

		return $pos_users;
	}

	/**
	 * @return WP_User|WP_Error
	 */
	protected function insert_cloud_user(string $user_email)
	{
		$user_login = explode('@', $user_email)[0];
		$is_login_already_exists = get_user_by('login', $user_login) instanceof WP_User;

		if ($is_login_already_exists) {
			$user_login .= '_' . time();
		}

		$user_id = wp_insert_user([
			'user_login' => $user_login,
			'user_email' => $user_email,
			'user_pass' => wp_generate_password(),
			'role' => 'shop_manager',
			'description' => sprintf(__('User from the %s', TEXTDOMAIN), CLOUD_APP_NAME),
		]);

		if ($user_id instanceof WP_Error) {
			return new WP_Error(
				'cloud_user_has_not_been_created',
				sprintf(__('Error: %s', TEXTDOMAIN), $user_id->get_error_message()),
				['status' => 500]
			);
		}

		return get_user_by('ID', $user_id);
	}
}
