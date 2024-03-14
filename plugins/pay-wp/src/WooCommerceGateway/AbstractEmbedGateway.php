<?php

namespace WPDesk\GatewayWPPay\WooCommerceGateway;

use WPDesk\GatewayWPPay\BlueMediaApi\BlueMediaClientFactory;

class AbstractEmbedGateway extends \WC_Payment_Gateway {
	/**
	 * Optional URL to view a transaction.
	 *
	 * @var string
	 */
	public $view_transaction_url = 'https://oplacasie.bm.pl/admin/transaction/%s';

	/**
	 * @var BlueMediaClientFactory
	 */
	protected $client_factory;

	public function hooks(): void {
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );
	}

	public function init_form_fields(): void {
		$this->form_fields = array(
			'main_settings'           => [
				'title' => __( 'Main settings', 'pay-wp' ),
				'type'  => 'title'
			],
			'enabled'                 => array(
				'title'   => __( 'Enable/Disable', 'pay-wp' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable payments', 'pay-wp' ),
				'default' => 'no'
			),
			'title'                   => array(
				'title'       => __( 'Title', 'pay-wp' ),
				'type'        => 'text',
				'description' => __( 'The name of the payment gateway that the customer sees when placing an order.',
					'pay-wp' ),
				'desc_tip'    => false,
			),
			'description'             => array(
				'description' => __( 'Description of the payment gateway that the customer sees when placing an order.',
					'pay-wp' ),
				'default'     => __( 'Autopay online payments. Pay by electronic transfer, traditional bank transfer or credit card.',
					'pay-wp' ),
				'type'        => 'textarea',
			)
		);
	}

}
