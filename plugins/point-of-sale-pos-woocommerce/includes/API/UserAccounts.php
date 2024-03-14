<?php

namespace ZPOS\API;

use WP_Error;
use WP_REST_Server, WC_REST_Controller, WP_REST_Request, WP_REST_Response;
use WP_User;
use WP_User_Query;
use ZPOS\Plugin;
use ZPOS\Admin\Tabs\Users\UserSettings;
use const ZPOS\CLOUD_APP_NAME;
use const ZPOS\REST_NAMESPACE;
use const ZPOS\TEXTDOMAIN;

class UserAccounts extends WC_REST_Controller
{
	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'user-accounts';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes(): void
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);

		register_rest_route($this->namespace, '/' . $this->rest_base, [
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [$this, 'get_user_accounts'],
				'permission_callback' => [$this, 'check_permissions_to_get_user_accounts'],
			],
			[
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => [$this, 'create_user_account'],
				'permission_callback' => [$this, 'check_permissions_to_create_user_account'],
			],
		]);
	}

	public function get_user_accounts(WP_REST_Request $request): WP_REST_Response
	{
		$params = $request->get_params();
		$page = isset($params['page']) && is_numeric($params['page']) ? intval($params['page']) : -1;
		$per_page =
			isset($params['per_page']) && is_numeric($params['per_page'])
				? intval($params['per_page'])
				: 20;
		$emails_to_filter =
			isset($params['emails_to_filter']) && is_string($params['emails_to_filter'])
				? array_map('sanitize_email', explode(',', $params['emails_to_filter']))
				: [];
		$is_multiple = Plugin::isActive('MultipleUsersPOS');
		$query_results = $is_multiple
			? $this->get_pos_multiple_users($page, $per_page, $emails_to_filter)
			: $this->get_pos_users();
		$query_results['is_multiple'] = $is_multiple;

		return new WP_REST_Response($query_results);
	}

	public function check_permissions_to_get_user_accounts(): bool
	{
		return is_user_logged_in();
	}

	/**
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_user_account(WP_REST_Request $request)
	{
		$params = $request->get_params();

		foreach ($this->get_required_params_to_create_user_account() as $required_param) {
			if (empty($params[$required_param])) {
				return new WP_Error(
					"missing_parameter_$required_param",
					sprintf(__('Error: Missing parameter %s', TEXTDOMAIN), $required_param),
					['status' => 400]
				);
			}
		}

		if (!is_string($params['first_name'])) {
			return new WP_Error('invalid_first_name', __('Error: Invalid first_name', TEXTDOMAIN), [
				'status' => 400,
			]);
		}

		if (!in_array($params['role'], $this->get_available_user_account_roles(), true)) {
			return new WP_Error(
				'role_is_not_allowed',
				__('Error: This role is not allowed', TEXTDOMAIN),
				[
					'status' => 400,
				]
			);
		}

		$user_id = wp_insert_user([
			'user_login' => $params['user_login'],
			'user_email' => $params['user_email'],
			'first_name' => $params['first_name'],
			'user_pass' => wp_generate_password(),
			'role' => $params['role'],
			'description' => sprintf(__('User from the %s', TEXTDOMAIN), CLOUD_APP_NAME),
		]);

		if ($user_id instanceof WP_Error) {
			return new WP_Error(
				'user_account_has_not_been_created',
				sprintf(__('Error: %s', TEXTDOMAIN), $user_id->get_error_message()),
				['status' => 500]
			);
		}

		$user = get_user_by('ID', $user_id);

		return new WP_REST_Response([
			'user_account_created' => [
				'name' => $user->display_name,
				'login' => $user->user_login,
				'email' => $user->user_email,
				'roles' => $user->roles,
			],
		]);
	}

	public function check_permissions_to_create_user_account(): bool
	{
		return current_user_can('create_users');
	}

	protected function get_required_params_to_create_user_account(): array
	{
		return ['user_login', 'user_email', 'first_name', 'role'];
	}

	protected function get_available_user_account_roles(): array
	{
		global $wp_roles;

		return array_filter(array_keys($wp_roles->get_names()), function (string $role): bool {
			$role = get_role($role);

			return $role->has_cap('access_woocommerce_pos');
		});
	}

	protected function get_pos_users(): array
	{
		$users = array_map(function (string $user_login): WP_User {
			return get_user_by('login', $user_login);
		}, array_values(UserSettings::getUsers()));

		return [
			'user_accounts' => $this->prepare_user_accounts($users),
			'count_total' => count($users),
		];
	}

	protected function get_pos_multiple_users(
		int $page,
		int $per_page,
		array $emails_to_filter
	): array {
		global $wpdb;

		$roles = $this->get_available_user_account_roles();
		$offset = ($page - 1) * $per_page;
		$where_clauses = [];

		if ($emails_to_filter) {
			$email_placeholders = implode(',', array_fill(0, count($emails_to_filter), '%s'));
			$where_clauses[] = "u.user_email IN ($email_placeholders)";
		}

		if ($roles) {
			$role_clauses = array_map(function (string $role) use ($wpdb) {
				return $wpdb->prepare(
					'(um.meta_key = %s AND um.meta_value LIKE %s)',
					$wpdb->prefix . 'capabilities',
					'%"' . $role . '"%'
				);
			}, $roles);
			$where_clauses[] = '(' . implode(' OR ', $role_clauses) . ')';
		}

		$sql = "SELECT SQL_CALC_FOUND_ROWS DISTINCT u.ID FROM {$wpdb->users} u ";
		$sql .= $roles ? "LEFT JOIN {$wpdb->usermeta} um ON (u.ID = um.user_id) " : '';
		$sql .= $where_clauses ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
		$sql .= 0 < $page ? $wpdb->prepare(' LIMIT %d, %d', $offset, $per_page) : '';
		$prepared_sql = $emails_to_filter ? $wpdb->prepare($sql, $emails_to_filter) : $sql;
		$results = $wpdb->get_results($prepared_sql, ARRAY_A);
		$users = array_map(function (array $user): WP_User {
			return get_user_by('ID', $user['ID']);
		}, $results);

		return [
			'user_accounts' => $this->prepare_user_accounts($users),
			'count_total' => $wpdb->get_var('SELECT FOUND_ROWS()'),
		];
	}

	protected function prepare_user_accounts(array $user_query_results): array
	{
		return array_values(
			array_map(function (WP_User $user): array {
				return [
					'name' => $user->display_name,
					'login' => $user->user_login,
					'email' => $user->user_email,
					'roles' => $user->roles,
				];
			}, $user_query_results)
		);
	}
}
