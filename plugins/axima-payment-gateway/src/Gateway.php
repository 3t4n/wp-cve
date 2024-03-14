<?php
/**
 * @author Tomáš Blatný
 */

namespace Pays\PaymentGate;


use WC_Payment_Gateway;
use WC_Tax;

class Gateway extends WC_Payment_Gateway
{

	const JSON_FILE = 'https://www.pays.cz/api/public/vatsetup/';


	/** @var Database */
	private $database;


	public function __construct(Database $database)
	{
		$this->database = $database;
		$this->id = 'pays';

		$this->method_title = 'Pays.cz';
		$this->method_description = 'Pay using pays.cz service';

		$this->init_form_fields();
		$this->init_settings();

		$this->title = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );

		add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
	}


	public function init_form_fields()
	{
		$this->form_fields = array(
			'enabled' => array(
				'title' => __('Enable/Disable', 'woocommerce'),
				'type' => 'checkbox',
				'label' => __('Enable pays.cz payment', 'woocommerce'),
				'default' => 'yes'
			),
			'title' => array(
				'title' => __('Title', 'woocommerce' ),
				'type' => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default' => __('Pays.cz', 'woocommerce'),
				'desc_tip' => true,
			),
			'description' => array(
				'title' => __('Customer Message', 'woocommerce'),
				'type' => 'textarea',
				'default' => ''
			)
		);
	}


	public function process_payment($orderId)
	{
		$order = wc_get_order($orderId);

		if (!in_array($order->get_currency(), array('CZK', 'USD', 'EUR'), TRUE)) {
			return array(
				'result' => 'fail',
				'redirect' => '',
			);
		}

		$this->database->insert(Plugin::TABLE_PAYMENTS, array(
            'identifier' => $merchantOrderNumber = $this->getMerchantOrderNumber($order->get_order_number()),
			'status' => Plugin::STATUS_INITIATED,
			'customer' => $order->get_formatted_billing_full_name() . ', ' . $order->get_billing_email(),
			'order' => $orderId,
			'amount' => $order->get_total() . ' ' . $order->get_currency(),
			'date_initiated' => date('Y-m-d G:i:s'),
		));

		$params = array(
            'Merchant' => Plugin::getMerchantId(),
            'Shop' => Plugin::getShopId(),
            'Currency' => $order->get_currency(),
            'Amount' => $order->get_total() * 100,
            'Email' => $order->get_billing_email(),
            'MerchantOrderNumber' => $merchantOrderNumber,
        );

        $link = 'https://www.pays.cz/paymentorder?' . http_build_query($params);

		if (wc_tax_enabled()) {
			$base = array();
			$taxes = array();
			foreach ($order->get_items() as $item) {
				$taxRate = (int) round($item['line_tax'] / $item['line_total'] * 100);
				if (!isset($base[$taxRate])) {
					$base[$taxRate] = 0;
				}
				if (!isset($taxes[$taxRate])) {
					$taxes[$taxRate] = 0;
				}
				$base[$taxRate] += (int) round($item['line_total'] * 100);
				$taxes[$taxRate] += (int) round($item['line_tax'] * 100);
			}

			$content = @file_get_contents(self::JSON_FILE);
			$result = [
				21 => 'Standard',
				15 => '1Reduced',
				10 => '2Reduced',
				0 => 'Zero',
			];
			if ($content) {
				$result = array();
				foreach (json_decode($content, JSON_OBJECT_AS_ARRAY) as $value => $key) {
					$result[(int) $key] = $value;
				}
			}

			foreach ($result as $key => $value) {
				if (isset($base[$key], $taxes[$key])) {
					if ($key !== 0) {
						$link .= '&TaxBase' . $value . '=' . $base[$key] . '&TaxVAT' . $value . '=' . $taxes[$key];
					} else {
						$link .= '&Tax' . $value . '=' . $base[$key];
					}
				}
			}
		}

		return array(
			'result' => 'success',
			'redirect' => $link,
		);
	}


	private function getMerchantOrderNumber($orderId)
	{
		return str_pad($orderId, 10, '0', STR_PAD_LEFT);
	}


}
