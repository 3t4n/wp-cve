<?php

class WC_QuickPay_MobilePay_Subscriptions extends WC_QuickPay_Instance {

	public $main_settings = null;

	public const instance_id = 'mobilepay-subscriptions';

	public function __construct() {
		parent::__construct();

		$this->supports = [
			'products',
			'subscriptions',
			'subscription_cancellation',
			'subscription_reactivation',
			'subscription_suspension',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change_admin',
			'subscription_payment_method_change_customer',
			'refunds',
			'multiple_subscriptions',
		];

		// Get gateway variables
		$this->id = self::instance_id;

		$this->method_title = 'QuickPay - MobilePay Subscriptions';

		$this->setup();

		$this->title       = $this->s( 'title' );
		$this->description = $this->s( 'description' );

		add_filter( 'woocommerce_quickpay_cardtypelock_mobilepay-subscriptions', [ $this, 'filter_cardtypelock' ] );
		add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, [ WC_QP(), 'scheduled_subscription_payment' ], 10, 2 );
		add_filter( 'woocommerce_quickpay_transaction_params_invoice', [ $this, 'maybe_remove_phone_number' ], 10, 2 );
		add_filter( 'woocommerce_available_payment_gateways', [ $this, 'adjust_available_gateways' ] );
		add_filter( "woocommerce_quickpay_create_recurring_payment_data_{$this->id}", [ $this, 'recurring_payment_data' ], 10, 3 );
		add_action( 'woocommerce_quickpay_callback_subscription_authorized', [ $this, 'on_subscription_authorized' ], 10, 3 );
		add_action( 'woocommerce_quickpay_scheduled_subscription_payment_after', [ $this, 'on_after_scheduled_payment_created' ], 10, 2 );
		add_filter( 'woocommerce_quickpay_callback_payment_captured', [ $this, 'maybe_process_order_on_capture' ], 10, 2 );
		add_filter( 'woocommerce_subscription_payment_meta', [ $this, 'woocommerce_subscription_payment_meta' ], 10, 2 );
		add_action( 'woocommerce_quickpay_callback_subscription_cancelled', [ $this, 'on_subscription_cancelled' ], 10, 4 );
		add_filter( 'woocommerce_quickpay_payment_cancelled_order_transition_status', [ $this, 'payment_cancelled_order_transition_status' ], 10, 4 );
		add_filter( 'woocommerce_quickpay_payment_cancelled_order_transition_status_note', [ $this, 'payment_cancelled_order_transition_status_note' ], 10, 4 );
	}

	/**
	 * @param WC_Subscription $subscription
	 * @param WC_Order $order
	 * @param stdClass $operation
	 * @param stdClass $json
	 *
	 * @throws Exception
	 */
	public function on_subscription_cancelled( $subscription, WC_Order $order, $operation, $json ): void {
		if ( $subscription->get_payment_method() === $this->id && ( $transition_to = $this->s( 'mps_transaction_cancellation_status' ) ) ) {
			$allowed_transition_from = apply_filters( 'woocommerce_quickpay_mps_cancelled_from_status', [ 'active' ], $subscription, $order, $json );
			if ( $subscription->has_status( $allowed_transition_from ) && ! $subscription->has_status( $transition_to ) && WC_QuickPay_Helper::is_subscription_status( $transition_to ) ) {
				$subscription->update_status( $transition_to, ! empty( $operation->aq_status_msg ) ? $operation->aq_status_msg : __( 'Subscription transaction has been cancelled by merchant or customer', 'woo-quickpay' ) );
			}
		}
	}


	/**
	 *  Perform gateway specific order status updates in case of specific scenarios:
	 *
	 *  code 50000: Payment failed to execute during the due-date.
	 *  code 50001: User rejected the Pending payment in MobilePay
	 *
	 * @param $transition_to_status
	 * @param WC_Order $order
	 * @param $transaction
	 * @param $operation
	 *
	 * @return mixed
	 */
	public function payment_cancelled_order_transition_status( $transition_to_status, WC_Order $order, $transaction, $operation ) {
		if ( $this->is_cancelled_transaction_failed( $operation, $order ) ) {
			$transition_to_status = 'failed';
		}

		return $transition_to_status;
	}

	/**
	 *  Perform gateway specific order status updates in case of specific scenarios:
	 *
	 *  code 50000: Payment failed to execute during the due-date.
	 *  code 50001: User rejected the Pending payment in MobilePay
	 *
	 * @param $note
	 * @param WC_Order $order
	 * @param $transaction
	 * @param $operation
	 *
	 * @return mixed
	 */
	public function payment_cancelled_order_transition_status_note( $note, WC_Order $order, $transaction, $operation ) {
		if ( $this->is_cancelled_transaction_failed( $operation, $order ) ) {
			$note = sprintf( '%s - %s', $note, $operation->aq_status_msg );
		}

		return $note;
	}

	/**
	 * Checks if the cancel operation on a payment should be considered as a failed payment.
	 *
	 * aq_status_code 50000: Payment failed to execute during the due-date.
	 * aq_status_code 50001: User rejected the Pending payment in MobilePay
	 *
	 * @param $operation
	 * @param WC_Order $order
	 *
	 * @return bool
	 */
	private function is_cancelled_transaction_failed( $operation, WC_Order $order ): bool {
		return in_array( (int) $operation->aq_status_code, [ 50000, 50001 ], true ) && $order->get_payment_method() === $this->id;
	}

	/**
	 * init_form_fields function.
	 *
	 * Initiates the plugin settings form fields
	 *
	 * @access public
	 */
	public function init_form_fields(): void {
		$this->form_fields = [
			'enabled'                             => [
				'title'   => __( 'Enable', 'woo-quickpay' ),
				'type'    => 'checkbox',
				'label'   => sprintf( __( 'Enable %s payment', 'woo-quickpay' ), $this->get_sanitized_method_title() ),
				'default' => 'no'
			],
			'_Shop_setup'                         => [
				'type'  => 'title',
				'title' => __( 'Shop setup', 'woo-quickpay' ),
			],
			'title'                               => [
				'title'       => __( 'Title', 'woo-quickpay' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => $this->get_sanitized_method_title(),
			],
			'description'                         => [
				'title'       => __( 'Customer Message', 'woo-quickpay' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickpay' ),
				'default'     => sprintf( __( 'Pay with %s', 'woo-quickpay' ), $this->get_sanitized_method_title() ),
			],
			[
				'type'  => 'title',
				'title' => 'Checkout'
			],
			'checkout_instant_activation'         => [
				'title'       => __( 'Activate subscriptions immediately.', 'woo-quickpay' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'woo-quickpay' ),
				'default'     => 'no',
				'description' => __( 'Activates the subscription after the customer authorizes an agreement. <strong>Not suitable for membership pages selling virtual products</strong> as the first payment might take up to 48 hours to either succeed or fail. Read more <a href="https://learn.quickpay.net/helpdesk/da/articles/payment-methods/mobilepay-subscriptions/#oprettelse-af-abonnement" target="_blank">here</a>', 'woo-quickpay' ),
			],
			'checkout_prefill_phone_number'       => [
				'title'       => __( 'Pre-fill phone number', 'woo-quickpay' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'woo-quickpay' ),
				'default'     => 'yes',
				'description' => __( 'When enabled the customer\'s phone number will be used on the MobilePay payment page.', 'woo-quickpay' ),
			],
			[
				'type'  => 'title',
				'title' => 'Renewals'
			],
			'renewal_keep_active'                 => [
				'title'       => __( 'Keep subscription active', 'woo-quickpay' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable', 'woo-quickpay' ),
				'default'     => 'no',
				'description' => __( 'When enabled the subscription will automatically be activated after scheduling the renewal payment. If the payment fails the subscription will be put on-hold.', 'woo-quickpay' ),
			],
			[
				'type'  => 'title',
				'title' => __( 'Agreements', 'woo-quickpay' )
			],
			'mps_transaction_cancellation_status' => [
				'title'             => __( 'Cancelled agreements status', 'woo-quickpay' ),
				'type'              => 'select',
				'class'             => 'wc-enhanced-select',
				'css'               => 'width: 450px;',
				'default'           => 'none',
				'description'       => __( 'Changes subscription status in case of cancelled payment agreement from either the QuickPay manager or the customer\'s MobilePay app', 'woo-quickpay' ),
				'options'           => $this->get_mps_cancel_agreement_status_options(),
				'custom_attributes' => [
					'data-placeholder' => __( 'Select status', 'woo-quickpay' )
				]
			],
		];
	}

	private function get_mps_cancel_agreement_status_options() {
		return apply_filters( 'woocommerce_quickpay_mps_cancel_agreement_status_options', [
			'none'      => __( 'Do nothing', 'woo-quickpay' ),
			'on-hold'   => wc_get_order_status_name( 'on-hold' ),
			'cancelled' => wc_get_order_status_name( 'cancelled' ),
		], $this );
	}

	/**
	 * filter_cardtypelock function.
	 *
	 * Sets the cardtypelock
	 *
	 * @access public
	 * @return string
	 */
	public function filter_cardtypelock() {
		return 'mobilepay-subscriptions';
	}

	/**
	 * If disabled, the phone number won't be sent to MobilePay which means that customers will have to type in their
	 * phone numbers manually.
	 *
	 * @param array $data
	 * @param WC_Order $order
	 *
	 * @return array
	 */
	public function maybe_remove_phone_number( array $data, WC_Order $order ): array {
		if ( $order->get_payment_method() === $this->id ) {
			if ( ! WC_QuickPay_Helper::option_is_enabled( $this->s( 'checkout_prefill_phone_number' ) ) ) {
				if ( isset( $data['phone_number'] ) ) {
					$data['phone_number'] = null;
				}
			}
		}

		return $data;
	}

	/**
	 * Only show the gateway if the cart contains a subscription product
	 *
	 * @param $available_gateways
	 *
	 * @return mixed
	 */
	public function adjust_available_gateways( $available_gateways ) {
		if ( isset( $available_gateways[ $this->id ] )
		     && WC_QuickPay_Subscription::plugin_is_active()
		     && ( is_cart() || is_checkout() ) && ! WC_Subscriptions_Cart::cart_contains_subscription()
		     && ! WC_Subscriptions_Change_Payment_Gateway::$is_request_to_change_payment ) {
			unset( $available_gateways[ $this->id ] );
		}

		return $available_gateways;
	}

	/**
	 * @param array $data
	 * @param WC_Order $order
	 * @param int $subscription_id
	 *
	 * @return array
	 */
	public function recurring_payment_data( $data, WC_Order $order, $subscription_id ): array {
		if ( empty( $data['due_date'] ) ) {
			$data['auto_capture_at'] = wp_date( 'Y-m-d', strtotime( 'now + 2 days' ), apply_filters( 'woocommerce_quickpay_mps_timezone', null, $data, $order, $subscription_id ) );
			$data['description']     = sprintf( __( 'Payment of #%s', 'woo-quickpay' ), $order->get_order_number() );
		}

		return $data;
	}

	/**
	 * If enabled, the module will activate the subscription after an agreement has been authorized, but
	 *
	 * @param WC_Subscription $subscription
	 * @param WC_Order $parent_order
	 * @param mixed $transaction
	 */
	public function on_subscription_authorized( WC_Subscription $subscription, WC_Order $parent_order, $transaction ): void {
		try {
			if ( $subscription->get_payment_method() === self::instance_id ) {
				$instant_activation = WC_QuickPay_Helper::option_is_enabled( $this->s( 'checkout_instant_activation' ) );

				if ( $instant_activation && ! $subscription->has_status( 'active' ) ) {
					$subscription->update_status( 'active', __( "'Activate subscriptions immediately.' enabled. Activating subscription due to authorized MobilePay agreement", 'woo-quickpay' ) );
					$subscription->save();
				}
			}
		} catch ( \Exception $e ) {
			( new WC_QuickPay_Log() )->add( 'Unable to activate subscription immediately after payment authorization: ' . $e->getMessage() );
		}
	}

	/**
	 * @param WC_Subscription $subscription
	 * @param WC_Order $renewal_order
	 */
	public function on_after_scheduled_payment_created( $subscription, WC_Order $renewal_order ): void {
		if ( WC_QuickPay_Helper::option_is_enabled( $this->s( 'renewal_keep_active' ) ) ) {
			try {
				$subscription->update_status( 'active' );
			} catch ( \Exception $e ) {
				$subscription->add_order_note( $e->getMessage() );
			}
		}
	}

	/**
	 * @param WC_Order $order
	 * @param mixed $transaction
	 *
	 * @return void
	 */
	public function maybe_process_order_on_capture( WC_Order $order, $transaction ): void {
		if ( $order->get_payment_method() === $this->id && $order->needs_payment() ) {
			$order->payment_complete( $transaction->id );
		}
	}

	/**
	 * Declare gateway's meta data requirements in case of manual payment gateway changes performed by admins.
	 *
	 * @param array $payment_meta
	 *
	 * @param WC_Subscription $subscription
	 *
	 * @return array
	 */
	public function woocommerce_subscription_payment_meta( $payment_meta, $subscription ): array {
		$payment_meta[ $this->id ] = [
			'post_meta' => [
				'_quickpay_transaction_id' => [
					'value' => WC_QuickPay_Order_Utils::get_transaction_id( $subscription ),
					'label' => __( 'QuickPay Transaction ID', 'woo-quickpay' ),
				],
			],
		];

		return $payment_meta;
	}
}
