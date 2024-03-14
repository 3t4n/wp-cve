<?php

class WC_GPayments_Connection extends WC_Payment_Gateway
{

	function __construct()
	{
		$this->id = "wc-4gpayments";
		$this->method_title = "4Geeks Payments";
		$this->new_method_label  = "4Geeks Payments";
		$this->method_description = "Acepte pagos con tarjetas en WooCommerce a través de 4Geeks Payments.";

		$this->init_form_fields();
		$this->init_settings();

		$this->title = __('Pagar con tarjeta', '4gpayments');
		$this->description = __('Pagar con tu tarjeta de débito o crédito.', 'wc-4gpayments');
		$this->icon = 'https://storage.googleapis.com/mesmerizing-matrix-1380/1/2018/08/logo-4geeks-black.png';


		// support default form with credit card
		$this->supports = array(
			'products',
			'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change_customer',
			'subscription_payment_method_change',
			'subscription_payment_method_change_admin'
		);

		// setting defines


		// Turn these settings into variables we can use
		foreach ($this->settings as $setting_key => $value) {
			$this->$setting_key = $value;
		}

		// Save settings
		if (is_admin()) {
			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		}

		add_action('woocommerce_api_payment_ok', array($this, 'woogatewaypro_payment_ok'));
	} // Here is the  End __construct()

	public function woogatewaypro_payment_ok()
	{
		$order_id = $_GET['order_id'];
		$back_error = str_replace("\'", "", $_GET['back_error']);
		global $woocommerce;
		$customer_order = new WC_Order($order_id);

		//API base URL
		$api_url = 'https://api.4geeks.io/v1/payments/public/' . $_GET['id_payment'];

		$data_to_send = array(
			"grant_type" => "client_credentials",
			"client_id" => $this->client_id,
			"client_secret" => $this->client_secret
		);

		$response = wp_remote_post($api_url, array(
			'method'    => 'GET',
			'timeout'   => 90,
			'blocking' => true,
		));

		$JsonResponse = json_decode($response['body']);
		if ($JsonResponse->status == 'succeeded') {
			// Payment successful
			$customer_order->add_order_note(__('Pago completo.', 'wc-4gpayments'));

			// paid order marked
			$customer_order->payment_complete();

			// this is important part for empty cart
			$woocommerce->cart->empty_cart();
			header('Location: ' . $this->get_return_url($customer_order));
			die();
		} else {
			if ($JsonResponse->status == 'canceled') {
				$customer_order->add_order_note('Pago cancelado');
				wc_add_notice("El pago fue cancelado, por favor vuelve a intentarlo", 'error');
			} else {
				$customer_order->add_order_note('Error: intentar pagar');
				wc_add_notice("Error al pagar, por favor vuelve a intentarlo", 'error');
			}

			header('Location: ' . $back_error);
			die();
		}
	}

	// administration fields for specific Gateway
	public function init_form_fields()
	{
		$this->form_fields = array(
			'enabled' => array(
				'title'		=> __('Activo / Inactivo', 'wc-4gpayments'),
				'label'		=> __('Activar esta forma de pago', 'wc-4gpayments'),
				'type'		=> 'checkbox',
				'default'	=> 'no',
			),
			'client_id' => array(
				'title'		=> __('Client ID', 'wc-4gpayments'),
				'type'		=> 'text',
				'desc_tip'	=> __('API Client ID provisto por 4Geeks Payments.', 'wc-4gpayments'),
				'custom_attributes' => array(
					'required' => 'required'
				),
			),
			'client_secret' => array(
				'title'		=> __('Client Secret', 'wc-4gpayments'),
				'type'		=> 'password',
				'desc_tip'	=> __('API Client Secret provisto por 4Geeks Payments.', 'wc-4gpayments'),
				'custom_attributes' => array(
					'required' => 'required'
				),
			)
		);
	}

	// Response handled for payment gateway
	public function process_payment($order_id)
	{
		$host = $_SERVER["HTTP_HOST"];
		$url = $_SERVER["REQUEST_URI"];

		global $woocommerce;

		$locale = apply_filters('plugin_locale', is_admin() ? get_user_locale() : get_locale(), $domain);
		$mofile = $domain . '-' . $locale . '.mo';

		$customer_order = new WC_Order($order_id);

		//API Auth URL
		$api_auth_url = 'https://api.4geeks.io/authentication/token/';

		//API base URL
		$api_url = 'https://api.4geeks.io/v1/payments/checkout/';

		$data_to_send = array(
			"grant_type" => "client_credentials",
			"client_id" => $this->client_id,
			"client_secret" => $this->client_secret
		);

		$response_token = wp_remote_post($api_auth_url, array(
			'method' => 'POST',
			'timeout' => 90,
			'blocking' => true,
			'headers' => array('content-type' => 'application/json'),
			'body' => json_encode($data_to_send, true)
		));

		$api_token = json_decode(wp_remote_retrieve_body($response_token), true)['access_token'];
		$list_items = $customer_order->get_items();
		$name_items = [];
		foreach ($list_items as &$value) {
			array_push($name_items, $value->get_name());
		}
		$payload = array(
			"amount"             	=> $customer_order->get_total(),
			"description"           => "WC " . $order_id,
			"items" => $name_items,
			"currency"           	=> get_woocommerce_currency(),
			"customer" => array(
				"first_name" => $_POST["billing_first_name"],
				"last_name" => $_POST["billing_last_name"],
				"email" => $_POST["billing_email"],
				"phone" => $_POST["billing_phone"],
				"country" => $_POST["billing_country"],
				"address" => array(
					"city" => $_POST["billing_city"],
					"zip_code" => $_POST["billing_postcode"],
					"country" => $_POST["billing_country"],
					"address" => $_POST["billing_address_1"],
				)
			),
			"return_url" => $host . explode("?", $url)[0] . "wc-api/payment_ok?order_id=" . $order_id . "&back_error='" . $_SERVER['HTTP_REFERER'] . "'"

		);
		//echo var_dump($payload);
		//die();
		// Send this payload to 4GP for processing
		$response = wp_remote_post($api_url, array(
			'method'    => 'POST',
			'body'      => json_encode($payload, true),
			'timeout'   => 90,
			'blocking' => true,
			'headers' => array('authorization' => 'bearer ' . $api_token, 'content-type' => 'application/json'),
		));

		$JsonResponse = json_decode($response['body']);
		//echo var_dump($JsonResponse);die;
		if ($mofile != '-es_CR.mo') {
			$response_Detail = $JsonResponse->content;
		} else {
			$response_Detail = $JsonResponse->content;
		}

		if (is_wp_error($response))
			throw new Exception(__('Hubo un problema para comunicarse con el procesador de pagos...', 'wc-4gpayments'));

		if (empty($response['body']))
			throw new Exception(__('La respuesta no obtuvo nada.', 'wc-4gpayments'));

		// get body response while get not error
		$responde_code = wp_remote_retrieve_response_code($response);
		// 1 or 4 means the transaction was a success

		if ($JsonResponse->code == 200 || $JsonResponse->code == 202) {
			return array(
				'result'   => 'success',
				'redirect' => $JsonResponse->data->redirect,
			);
		} else {
			//transiction fail
			wc_add_notice($response_Detail, 'error');
			$customer_order->add_order_note('Error: ' . $response_Detail);
		}
	}

	// Validate fields
	public function validate_fields()
	{
		return true;
	}


	public function do_ssl_check()
	{
		if ($this->enabled == "yes") {
			if (get_option('woocommerce_force_ssl_checkout') == "no") {
				echo "<div class=\"error\"><p>" . sprintf(__("<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>"), $this->method_title, admin_url('admin.php?page=wc-settings&tab=checkout')) . "</p></div>";
			}
		}
	}
}
