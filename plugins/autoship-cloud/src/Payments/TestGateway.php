<?php

class Autoship_Payments_TestGateway extends WC_Payment_Gateway {
	public function __construct() {
		$this->id = 'autoship-test-gateway';
		$this->method_title = __( 'Autoship Test Gateway', 'autoship' );
		$this->method_description = __( 'This payment gateway is for testing Autoship only.', 'autoship' );
		$this->has_fields = true;

		$this->init_form_fields();
		$this->init_settings();

		$this->title = $this->get_option( 'title' );

		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title' => __( 'Enable/Disable', 'autoship' ),
				'type' => 'checkbox',
				'label' => __( 'Enable Autoship Test Gateway', 'autoship' ),
				'default' => 'no'
			),
			'title' => array(
				'title' => __( 'Title', 'autoship' ),
				'type' => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'autoship' ),
				'default' => __( 'Autoship Test Gateway', 'autoship' ),
				'desc_tip'      => true,
			),
			'description' => array(
				'title' => __( 'Description', 'autoship' ),
				'type' => 'textarea',
				'default' => '',
				'placeholder' => $this->get_default_description()
			)
		);
	}

	function process_payment( $order_id ) {
		// Get order
		$order = wc_get_order( $order_id );

		// Set tokenization meta
		$gateway_customer_id = 'test-customer-' . $order->get_user_id();
		$gateway_payment_id = 'test-payment';
		$order->set_payment_method( $this->id );
		$order->add_meta_data( '_autoship_test_gateway_customer_id', $gateway_customer_id );
		$order->add_meta_data( '_autoship_test_gateway_payment_id', $gateway_payment_id );

		// Set complete
		$transaction_id = 'test-' . time();
		$order->payment_complete( $transaction_id );

		// Remove cart
		WC()->cart->empty_cart();

		// Return thankyou redirect
		return array(
			'result' => 'success',
			'redirect' => $this->get_return_url( $order )
		);
	}

	function payment_fields() {
		$description = $this->get_description();
		if ( empty( $description ) ) {
			$description = $this->get_default_description();
		}
		echo '<div class="autoship-test-gateway-description">' . $description . '</div>';
	}

	function get_default_description() {
		return __( 'Complete a test checkout. No payments will be collected.', 'autoship' );
	}

}