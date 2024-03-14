<?php

namespace ZPOS;

class API
{
	public function __construct()
	{
		add_action('rest_api_init', [$this, 'register_rest_routes'], 5);
		new API\Woocommerce\Orders();
	}

	public function register_rest_routes()
	{
		$this->authenticate_cloud_app_user();
		(new API\Auth())->register_routes();

		if (Frontend::checkUserAssign()) {
			// need check debug mode to prevent errors
			wp_debug_mode();

			$classes = [
				API\Applications::class,
				API\Application::class,
				API\Products::class,
				API\ProductVariations::class,
				API\ProductTags::class,
				API\Coupons::class,
				API\Cart::class,
				API\TaxClasses::class,
				API\Taxes::class,
				API\Categories::class,
				API\Groups::class,
				API\Gateways::class,
				API\Orders::class,
				API\Settings::class,
				API\Stations::class,
				API\FrontEndSettings::class,
				API\Setting\Option::class,
				API\Customers::class,
				API\PrintLocation::class,
				API\Shipping::class,
				API\UserAccounts::class,
				API\OrderNotes::class,
			];

			foreach ($classes as $class) {
				/* @var $controller \WP_REST_Controller */
				$controller = new $class();
				$controller->register_routes();
			}
		} else {
			$this->rewrite_rest_routes();
		}
	}

	public static function get_raw_data()
	{
		global $HTTP_RAW_POST_DATA;
		if (!isset($HTTP_RAW_POST_DATA)) {
			$HTTP_RAW_POST_DATA = trim(file_get_contents('php://input'));
		}
		return json_decode($HTTP_RAW_POST_DATA, true);
	}

	public static function is_pos()
	{
		return isset($_SERVER['HTTP_X_POS']) && $_SERVER['HTTP_X_POS'];
	}

	public function rewrite_rest_routes()
	{
		register_rest_route(REST_NAMESPACE, '/(.+)', [
			'callback' => [$this, 'error'],
			'permission_callback' => '__return_true',
		]);
	}

	public function error()
	{
		return rest_ensure_response(
			new \WP_REST_Response(
				[
					'message' => __('Change your User Assignments to access the POS system.', 'zpos-wp-api'),
					'title' => __('User Assignments Error', 'zpos-wp-api'),
					'code' => 'reload',
					'button' => [
						'link' => add_query_arg('page', 'pos#/users', admin_url('admin.php')),
						'title' => __('Go to User Assignment Settings', 'zpos-wp-api'),
					],
				],
				402
			)
		);
	}

	private function authenticate_cloud_app_user()
	{
		if (
			!(
				isset($_SERVER['HTTP_X_POS_CLOUD_APP_USER_EMAIL']) &&
				$_SERVER['HTTP_X_POS_CLOUD_APP_USER_EMAIL']
			)
		) {
			return;
		}

		$pos_cloud_user = get_user_by('email', $_SERVER['HTTP_X_POS_CLOUD_APP_USER_EMAIL']);

		if (!$pos_cloud_user) {
			wp_die(__('Current user does not exist.', 'zpos-wp-api'));
		}

		$current_user_email = wp_get_current_user()->user_email;

		if ($pos_cloud_user->user_email !== $current_user_email) {
			wp_set_current_user($pos_cloud_user->ID);
		}
	}
}
