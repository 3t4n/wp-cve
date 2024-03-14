<?php
if (!defined('ABSPATH')) {
	exit;
}
// Exit if accessed directly
include_once __DIR__ . "/../lib/Client.php";

use Dolyame\Payment\Client;

/**
 * Dolyamet Payment Gateway
 *
 * Provides a Dolyame Payment Gateway.
 *
 * @class 		WC_Getaway_dolyamepayment
 * @extends		WC_Payment_Gateway
 * @version		1.0.0
 * @author 		Dolyame
 */
class WC_Gateway_Dolyamepayment extends WC_Payment_Gateway
{
	public $notify_url;

	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->id                 = 'dolyamepayment';
		$this->has_fields         = false;
		$this->order_button_text  = __('Dolyame payment', 'dolyame_payment');
		$this->method_title       = __('Dolyame', 'dolyame_payment');
		$this->method_description = __('Dolyame payment gateway', 'dolyame_payment');
		$this->supports           = array(
			'products', 'refunds',
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->title                   = $this->get_option('title');
		$this->description             = $this->get_option('description');
		$this->instructions            = $this->get_option('instructions', $this->description);
		$this->login                   = $this->get_option('login');
		$this->password                = $this->get_option('password');
		$this->cert_path               = $this->get_option('cert_path');
		$this->key_path                = $this->get_option('key_path');
		$this->enable_log              = $this->get_option('enable_log');
		$this->request_handler         = $this->get_option('request_handler');
		$this->prefix                  = $this->get_option('prefix');
		$this->fiscalisation           = $this->get_option('fiscalisation');
		$this->ffd120                  = $this->get_option('ffd120');
		$this->product_vat             = $this->get_option('product_vat');
		$this->delivery_vat            = $this->get_option('delivery_vat');
		$this->payment_method          = $this->get_option('payment_method');
		$this->payment_object          = $this->get_option('payment_object');
		$this->delivery_payment_object = $this->get_option('delivery_payment_object');

		// Actions
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);

		// Payment listener/API hook
		add_action('woocommerce_api_wc_gateway_dolyamepayment', [$this, 'notification']);

		if (!$this->is_valid_for_use()) {
			$this->enabled = false;
		}
	}

	/**
	 * Check if this gateway is enabled and available in the user's country
	 *
	 * @access public
	 * @return bool
	 */
	public function is_valid_for_use()
	{
		if (!in_array(get_woocommerce_currency(), apply_filters('woocommerce_dolyamepayment_supported_currencies', array('RUB')))) {
			return false;
		}

		return true;
	}

	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 */
	public function admin_options()
	{
		if ($this->is_valid_for_use()) {
			parent::admin_options();
		} else {
			?>
			<div class="inline error"><p><strong><?php _e('Gateway Disabled', 'woocommerce');?></strong>: <?php _e('Dolyame does not support your store currency.', 'dolyame_payment');?></p></div>
			<?php
}
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 *
	 * @access public
	 * @return void
	 */
	public function init_form_fields()
	{
		$this->form_fields = array(
			'enabled'                 => array(
				'title'   => __('Enable/Disable', 'woocommerce'),
				'type'    => 'checkbox',
				'label'   => __('Enable Dolyame payment', 'dolyame_payment'),
				'default' => 'yes',
			),

			'title'                   => array(
				'title'       => __('Title', 'woocommerce'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default'     => __('Pay Dolyame', 'dolyame_payment'),
				'desc_tip'    => true,
			),
			'description'             => array(
				'title'       => __('Description', 'woocommerce'),
				'type'        => 'textarea',
				'description' => __('Payment method description that the customer will see on your checkout.', 'woocommerce'),
				'default'     => __('Split the payment into 4 parts without commissions and overpayments', 'dolyame_payment'),
				'desc_tip'    => true,
			),

			'login'                   => array(
				'title'   => __('Login', 'dolyame_payment'),
				'type'    => 'text',
				'default' => '',
			),
			'password'                => array(
				'title'   => __('Password', 'dolyame_payment'),
				'type'    => 'text',
				'default' => '',
			),

			'cert_path'               => array(
				'title'       => __('Cert path', 'dolyame_payment'),
				'type'        => 'textarea',
				'default'     => '',
				'description' => __('Cert path relative to website root', 'dolyame_payment'),
			),
			'key_path'                => array(
				'title'       => __('Key path', 'dolyame_payment'),
				'type'        => 'textarea',
				'default'     => '',
				'description' => __('Key path elative to website root', 'dolyame_payment'),
			),
			'prefix'                  => array(
				'title'       => __('Prefix', 'dolyame_payment'),
				'type'        => 'text',
				'default'     => '',
				'description' => __('Order prefix', 'dolyame_payment'),
			),
			'enable_log'              => array(
				'title'       => __('Logging', 'dolyame_payment'),
				'type'        => 'checkbox',
				'default'     => 'no',
				'label'       => __('Enable logging', 'dolyame_payment'),
				'description' => '',
			),
			'request_handler'         => array(
				'title'       => __('Request handler', 'dolyame_payment'),
				'type'        => 'select',
				'default'     => 'file',
				'label'       => '',
				'description' => '',
				'options' => array(
					'file'   => __('Stream', 'dolyame_payment'),
					'curl'   => __('cURL', 'dolyame_payment'),
				),
			),
			'fiscalisation'           => array(
				'title'       => __('Fiscalisation', 'dolyame_payment'),
				'type'        => 'checkbox',
				'default'     => 'no',
				'label'       => __('Enable fiscalisation', 'dolyame_payment'),
				'description' => '',
			),
			'product_vat'             => array(
				'title'   => __('VAT', 'dolyame_payment'),
				'type'    => 'select',
				'options' => array(
					'none'   => __('No VAT', 'dolyame_payment'),
					'vat0'   => __('VAT 0%', 'dolyame_payment'),
					'vat10'  => __('VAT 10%', 'dolyame_payment'),
					'vat20'  => __('VAT 20%', 'dolyame_payment'),
					'vat110' => __('VAT 10/110', 'dolyame_payment'),
					'vat120' => __('VAT 20/120', 'dolyame_payment'),
				),
				'default' => 'none',
			),
			'delivery_vat'            => array(
				'title'   => __('Delivery VAT', 'dolyame_payment'),
				'type'    => 'select',
				'options' => array(
					'none'   => __('No VAT', 'dolyame_payment'),
					'vat0'   => __('VAT 0%', 'dolyame_payment'),
					'vat10'  => __('VAT 10%', 'dolyame_payment'),
					'vat20'  => __('VAT 20%', 'dolyame_payment'),
					'vat110' => __('VAT 10/110', 'dolyame_payment'),
					'vat120' => __('VAT 20/120', 'dolyame_payment'),
				),
				'default' => 'none',
			),
			'payment_method'          => array(
				'title'   => __('Payment method', 'dolyame_payment'),
				'type'    => 'select',
				'options' => array(
					'full_prepayment' => __('full prepayment', 'dolyame_payment'),
					'full_payment'    => __('full payment', 'dolyame_payment'),
				),
				'default' => 'full_prepayment',
			),
			'ffd120'                  => array(
				'title'       => __('FFD120', 'dolyame_payment'),
				'type'        => 'checkbox',
				'default'     => 'no',
				'label'       => __('Enable ffd120', 'dolyame_payment'),
				'description' => '',
			),
			'payment_object'          => array(
				'title'   => __('Payment object', 'dolyame_payment'),
				'type'    => 'select',
				'options' => array(
					'commodity'             => __('commodity', 'dolyame_payment'),
					'excise'                => __('excise', 'dolyame_payment'),
					'job'                   => __('job', 'dolyame_payment'),
					'service'               => __('service', 'dolyame_payment'),
					'gambling_bet'          => __('gambling bet', 'dolyame_payment'),
					'gambling_prize'        => __('gambling prize', 'dolyame_payment'),
					'lottery'               => __('lottery', 'dolyame_payment'),
					'lottery_prize'         => __('lottery prize', 'dolyame_payment'),
					'intellectual_activity' => __('intellectual activity', 'dolyame_payment'),
					'payment'               => __('payment', 'dolyame_payment'),
					'agent_commission'      => __('agent commission', 'dolyame_payment'),
					'composite'             => __('composite', 'dolyame_payment'),
					'another'               => __('another', 'dolyame_payment'),
				),
				'default' => 'commodity',
			),
			'delivery_payment_object' => array(
				'title'   => __('Delivery payment object', 'dolyame_payment'),
				'type'    => 'select',
				'options' => array(
					'commodity'             => __('commodity', 'dolyame_payment'),
					'excise'                => __('excise', 'dolyame_payment'),
					'job'                   => __('job', 'dolyame_payment'),
					'service'               => __('service', 'dolyame_payment'),
					'gambling_bet'          => __('gambling bet', 'dolyame_payment'),
					'gambling_prize'        => __('gambling prize', 'dolyame_payment'),
					'lottery'               => __('lottery', 'dolyame_payment'),
					'lottery_prize'         => __('lottery prize', 'dolyame_payment'),
					'intellectual_activity' => __('intellectual activity', 'dolyame_payment'),
					'payment'               => __('payment', 'dolyame_payment'),
					'agent_commission'      => __('agent commission', 'dolyame_payment'),
					'composite'             => __('composite', 'dolyame_payment'),
					'another'               => __('another', 'dolyame_payment'),
				),
				'default' => 'service',
			),

		);
	}

	/**
	 * Process the payment and return the result
	 *
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment($order_id)
	{
		$order = new WC_Order($order_id);

		$data = $this->prepareData($order);
		$link = $this->createPaymentLink($data);
		return array('result' => 'success', 'redirect' => $link);
	}

	public function process_refund($orderId, $amount = null, $reason = '')
	{
		$order = new WC_Order($orderId);
		$data  = $this->prepareRefundData($order);

		$client = $this->initClient();

		$response = $client->refund($this->prefix . $order->get_order_number(), $data);

		$order->update_meta_data('_dolyame_refund_id', $response['refund_id']);
		$order->add_order_note(
			sprintf(__('Refunded %1$s - Refund ID: %2$s', 'woocommerce'), $data['amount'], $response['refund_id'])
		);
		return true;
	}

	private function prepareData($order)
	{
		$notifyUrl = WC()->api_request_url('WC_Gateway_Dolyamepayment');
		$notifyUrl = add_query_arg(['order_id' => $order->get_id()], $notifyUrl);
		$prepaid = $this->calcPrepaid($order);
		$data      = [
			'order'            => [
				'id'             => $this->prefix . $order->get_order_number(),
				'amount'         => $order->get_total(),
				'prepaid_amount' => $prepaid,
				'items'          => $this->getOrderItems($order),
			],
			'client_info'      => [
				'first_name' => $order->get_shipping_first_name(),
				'last_name'  => $order->get_shipping_last_name(),
				'phone'      => $this->getPhone($order),
				'email'      => $order->get_billing_email(),
			],
			'notification_url' => $notifyUrl,
			'fail_url'         => $order->get_cancel_order_url_raw(),
			'success_url'      => $this->get_return_url($order),
		];
		if ($this->fiscalisation == 'yes') {
			$data['fiscalization_settings'] = ['type' => 'enabled'];
		}
		return $data;
	}

	private function calcPrepaid($order)
	{
		$prepaid = 0;
		$items     = $order->get_items(['fee']);
		foreach ($items as $item) {
			if ($item->get_total() >= 0) {
				continue;
			}
			$prepaid -= $item->get_total();
		}
		return $prepaid;
	}

	private function prepareRefundData($order)
	{
		$refundItems  = [];
		$orderRefunds = $order->get_refunds();
		foreach ($orderRefunds as $refund) {
			if ($refund->get_refunded_payment()) {
				continue;
			}

			foreach ($refund->get_items(['line_item']) as $item_id => $item) {
				$quantity = $item->get_quantity(); // Quantity: zero or negative integer
				if ($quantity >= 0) {
					continue;
				}
				if ($item->get_subtotal() == 0) {
					continue;
				}

				$item = [
					"name"     => $item->get_name(),
					"quantity" => $quantity * -1,
					"price"    => number_format(round(($item->get_subtotal() + $item->get_subtotal_tax()) / $quantity, 2), 2, '.', ''),
				];
				if ($this->fiscalisation  == 'yes') {
					$receipt = [];
					$receipt['payment_method'] = $this->payment_method;
					$receipt['tax']            = $this->product_vat;
					if ($this->ffd120) {
						$receipt['payment_object']   = $this->payment_object;
						$receipt['measurement_unit'] = __('unit', 'dolyame_payment');
					}
					$item['receipt'] = $receipt;
				}

				$refundItems[] = $item;
			}

			$prepaid = 0;

			foreach ($refund->get_items(['fee']) as $item_id => $item) {
				if ($item->get_total() >= 0) {
					$prepaid += $item->get_total();
					continue;
				}

				$item = [
					"name"     => $item->get_name(),
					"quantity" => 1,
					"price"    => number_format(round(($item->get_total() + $item->get_total_tax()) * -1, 2), 2, '.', ''),
				];
				if ($this->fiscalisation  == 'yes') {
					$receipt = [];
					$receipt['payment_method'] = $this->payment_method;
					$receipt['tax']            = $this->product_vat;
					if ($this->ffd120) {
						$receipt['payment_object']   = $this->payment_object;
						$receipt['measurement_unit'] = __('unit', 'dolyame_payment');
					}
					$item['receipt'] = $receipt;
				}
				$refundItems[] = $item;
			}

			foreach ($refund->get_items(['shipping']) as $item_id => $item) {
				if ($item->get_total() == 0) {
					continue;
				}

				$item = [
					"name"     => $item->get_name(),
					"quantity" => 1,
					"price"    => number_format(round(($item->get_total() + $item->get_total_tax()) * -1, 2), 2, '.', ''),
				];
				if ($this->fiscalisation  == 'yes') {
					$receipt = [];
					$receipt['payment_method'] = $this->payment_method;
					$receipt['tax']            = $this->delivery_vat;
					if ($this->ffd120) {
						$receipt['payment_object']   = $this->delivery_payment_object;
						$receipt['measurement_unit'] = __('unit', 'dolyame_payment');
					}
					$item['receipt'] = $receipt;
				}
				$refundItems[] = $item;
			}
		}

		$refundItems = apply_filters('dolyame_payment_refund_items', $refundItems);

		$refundableSum = array_reduce($refundItems, function ($carry, $item) {
			return $carry + $item['quantity'] * $item['price'];
		}, 0);

		$refundableSum -= $prepaid;

		$data = [
			'amount'                  => number_format($refundableSum, 2, '.', ''),
			'returned_items'          => $refundItems,
			'refunded_prepaid_amount' => $prepaid,
		];
		if ($this->fiscalisation  == 'yes') {
			$data['fiscalization_settings'] = ['type' => 'enabled'];
		}
		return $data;
	}

	private function getPhone($order)
	{
		$phone = $order->get_billing_phone();
		$phone = preg_replace("#[^\d]#", "", $phone);
		if (!preg_match("#[7|8]{0,1}(\d{10})#", $phone, $match)) {
			return '';
		}
		return '+7' . $match[1];
	}

	private function createPaymentLink($data)
	{
		$client   = $this->initClient();
		$response = $client->create($data);
		return $response['link'];
	}

	private function getOrderItems($order)
	{
		$positions = [];
		$items     = $order->get_items(['line_item', 'fee', 'shipping']);
		foreach ($items as $item) {
			if ($item->get_total() <= 0) {
				continue;
			}
			$isDelivery = 0;
			if ($item instanceof WC_Order_Item_Shipping) {
				$isDelivery = 1;
			}

			$position = [
				'name'     => $item->get_name(),
				'quantity' => $item->get_quantity(),
				'price'    => number_format(round(($item->get_total() + $item->get_total_tax()) / $item->get_quantity(), 2), 2, '.', ''),
			];

			if ($this->fiscalisation == 'yes') {
				$receipt = [];
				$receipt['payment_method'] = $this->payment_method;
				$receipt['tax']            = ($isDelivery) ? $this->delivery_vat : $this->product_vat;
				if ($this->ffd120) {
					$receipt['payment_object']   = ($isDelivery) ? $this->delivery_payment_object : $this->payment_object;
					$receipt['measurement_unit'] = __('unit', 'dolyame_payment');
				}
				$position['receipt'] = $receipt;
			}

			$positions[] = $position;
		}

		$positions = apply_filters('dolyame_payment_order_items', $positions);

		return $positions;
	}

	public function notification()
	{
		$orderId = $_REQUEST['order_id'];

		if (function_exists("wc_get_order")) {
			$order = wc_get_order($orderId);
		} else {
			$order = new WC_Order($orderId);
		}

		if (!$order) {
			throw new \Exception('Order not found');
		}

		$info = $this->getTransactionInfo($this->prefix . $order->get_order_number());

		if (
			$info['status'] === 'waiting_for_commit'
			|| $info['status'] === 'wait_for_commit'
		) {
			$this->commitPayment($order);
			return true;
		}

		if ($info['status'] !== 'committed') {
			exit();
		}

		if ($order->has_status('completed')) {
			exit();
		}
		$order->payment_complete(time());

		exit();
	}

	private function getTransactionInfo($orderId)
	{
		$client = $this->initClient();
		$result = $client->info($orderId);
		return $result;
	}

	private function commitPayment($order)
	{
		$data = [
			'amount'         => $order->get_total(),
			'items'          => $this->getOrderItems($order),
			'prepaid_amount' => $this->calcPrepaid($order),
		];

		$client = $this->initClient();
		$result = $client->commit($this->prefix . $order->get_order_number(), $data);
		return $result;
	}

	private function initClient()
	{
		$api = new Client($this->login, $this->password);
		$certPath = $this->cert_path;
		if (strpos($this->cert_path, "-----BEGIN CERTIFICATE-----") === false) {
			$certPath = realpath(ABSPATH) . '/' . ltrim($this->cert_path, '/');
		}
		$api->setCertPath($certPath);

		$keyPath = $this->key_path;
		if (strpos($keyPath, "--") === false ){
			$keyPath = realpath(ABSPATH) . '/' . ltrim($this->key_path, '/');
		}
		$api->setKeyPath($keyPath);
		if ($this->request_handler === 'file') {
			$api->useFileRequestHandler();
		}
		if ($this->enable_log == 'yes') {
			$logger = wc_get_logger();
			$api->setLogger($logger);
		}
		return $api;
	}
}
