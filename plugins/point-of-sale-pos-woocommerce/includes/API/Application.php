<?php

namespace ZPOS\API;

use WP_REST_Server, WC_REST_Controller;
use ZPOS\Model;
use const ZPOS\REST_NAMESPACE;

class Application extends WC_REST_Controller
{
	protected $namespace = REST_NAMESPACE;
	protected $rest_base = 'application';

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
	}

	public function register_routes()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		register_rest_route($this->namespace, '/' . $this->rest_base . '/support', [
			[
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => [$this, 'send_support_email'],
				'permission_callback' => [$this, 'permissionCheck'],
				'args' => [
					'email' => [
						'type' => 'email',
						'required' => true,
					],
					'name' => [
						'type' => 'string',
						'required' => true,
					],
					'message' => [
						'type' => 'string',
						'required' => true,
					],
					'log' => [
						'type' => 'file',
					],
				],
			],
		]);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/heartbeat', [
			[
				'methods' => WP_REST_Server::READABLE,
				'callback' => [$this, 'heartbeat'],
				'permission_callback' => [$this, 'permissionCheck'],
			],
		]);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/allowed_location', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'allowed_location'],
			'permission_callback' => [$this, 'permissionCheck'],
		]);

		register_rest_route($this->namespace, '/' . $this->rest_base . '/allowed_vat_types', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'allowed_vat_types'],
			'permission_callback' => [$this, 'permissionCheck'],
		]);
	}

	public function send_support_email(\WP_REST_Request $request)
	{
		$html = function () {
			return 'text/html';
		};

		$params = $request->get_params();
		$file_params = $request->get_file_params();

		$email = $params['email'];
		$name = $params['name'];
		$message = $params['message'];
		$log = $file_params['log'];
		$attachment = [];
		if ($log) {
			array_push($attachment, $log['tmp_name']);
		}

		$subject = 'Support message from ' . $name;
		ob_start();
		?>
		<h1 style="margin: 0;">Support message from <?= $name ?> &lt;<?= $email ?>&gt;</h1><br>
		<h2 style="margin: 0;">From site: <a href="<?= home_url() ?>"><?php bloginfo(); ?></a></h2>
		<hr>
		Message:<br> <?= $message ?>
		<?php
  $content = ob_get_clean();

  add_filter('wp_mail_content_type', $html);
  $success = \wp_mail('support@bizswoop.com', $subject, $content, '', $attachment);
  remove_filter('wp_mail_content_type', $html);

  return ['success' => $success];
	}

	public function heartbeat(\WP_REST_Request $request)
	{
		return ['success' => true];
	}

	public function allowed_location()
	{
		$countries = WC()->countries->get_countries();
		$states = array_reduce(
			array_keys($countries),
			function ($acc, $country) use ($countries) {
				$country_name = $countries[$country];
				$states = WC()->countries->get_states($country);

				if ($states) {
					$states = array_map(function ($state) use ($country_name) {
						return $country_name . ' - ' . $state;
					}, $states);
					$states_keys = array_map(function ($state) use ($country) {
						return $country . ':' . $state;
					}, array_keys($states));
					$states = array_combine($states_keys, $states);

					$acc = array_merge($acc, $states);
				} else {
					$acc[$country] = $country_name;
				}
				return $acc;
			},
			[]
		);

		return array_map(
			function ($value, $label) {
				return compact('value', 'label');
			},
			array_keys($states),
			$states
		);
	}

	public function allowed_vat_types(): array
	{
		$types = Model\VatControl::get_types();

		return array_map(
			function (string $value, array $type): array {
				return [
					'value' => $value,
					'label' => $type['code'],
					'country' => $type['title'],
					'iso' => $type['iso'],
				];
			},
			array_keys($types),
			$types
		);
	}

	public function permissionCheck()
	{
		return is_user_logged_in();
	}
}
