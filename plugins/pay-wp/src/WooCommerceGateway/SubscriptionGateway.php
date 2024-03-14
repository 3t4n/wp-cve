<?php

namespace WPDesk\GatewayWPPay\WooCommerceGateway;


use WPDesk\GatewayWPPay\BlueMediaApi\BlueMediaClient;
use WPDesk\GatewayWPPay\BlueMediaApi\BlueMediaClientFactory;
use WPDesk\GatewayWPPay\BlueMediaApi\Responses\RPANResponse;
use WPDesk\GatewayWPPay\Helpers\ArrayFlattener;
use WPDesk\GatewayWPPay\Helpers\RPANDecoder;
use WPPayVendor\Psr\Log\LoggerInterface;

class SubscriptionGateway extends AbstractEmbedGateway {
	use RefundTrait {
		process_refund as private trait_process_refund;
	}

	const ID = 'wppay_recurring';

	private const ORDER_META_KEY_RPAN             = '_autopay_rpan_value';
	private const ORDER_META_KEY_RPAN_CLIENT_HASH = '_autopay_rpan_client_hash';

	private const ACCEPTANCE_STATE_INIT_SUBSCRIPTION = 'ACCEPTED';
	private const ACCEPTANCE_STATE_PROCESS_RENEWAL   = 'NOT_APPLICABLE';

	private const RECURRING_ACTION_INIT_WITH_PAYMENT = 'INIT_WITH_PAYMENT';
	private const RECURRING_ACTION_INIT_WITH_REFUND  = 'INIT_WITH_REFUND';
	private const RECURRING_ACTION_PROCESS_RENEWAL   = 'AUTO';
	private const AUTOPAY_RECURRING_GATEWAY_ID       = 1503;
	private const MINIMAL_ALLOWED_AMOUNT             = 0.01;

	private const TRANSACTION_DATA_CLIENTHASH_KEY  = 'ClientHash';
	private const TRANSACTION_DATA_REASON_KEY      = 'reason';
	private const TRANSACTION_DATA_REDIRECTURL_KEY = 'redirecturl';

	private const RPAN_CLIENTHASH_KEY            = 'clientHash';
	private const RPAN_RECURRING_KEY             = 'recurring';
	private const RPAN_TRANSACTION_KEY           = 'transaction';
	private const RPAN_RECURRINGDATA_KEY         = 'recurringData';
	private const RPAN_PAYMENTSTATUS_KEY         = 'paymentStatus';
	private const RPAN_ORDERID_KEY               = 'orderID';
	private const RPAN_SUCCESSFUL_PAYMENT_STATUS = 'SUCCESS';
	private const RPAN_CONFIRM                   = 'CONFIRMED';

	private string $plugin_url;
	private LoggerInterface $logger;


	public function __construct( BlueMediaClientFactory $client_factory, string $plugin_url, LoggerInterface $logger ) {
		$this->id                 = self::ID;
		$this->icon               = $plugin_url . '/assets/images/icon.svg';
		$this->has_fields         = false;
		$this->method_title       = __( 'Autopay Quick Payments - Recurring', 'pay-wp' );
		$this->method_description = __( 'Autopay online payments for WooCommerce. Pay quickly and securely for subscriptions with credit card.', 'pay-wp' );

		$this->client_factory = $client_factory;
		$this->plugin_url     = $plugin_url;

		$this->init_form_fields();
		$this->init_settings();

		$this->enabled     = $this->get_option( 'enabled', 'no' );
		$this->description = $this->get_option( 'description', 'Autopay online payments for WooCommerce. Pay quickly and securely for subscriptions with credit card.' );
		$this->title       = $this->get_option( 'title', __( 'Autopay Quick Payments - Recurring', 'pay-wp' ) );

		$this->supports = [
			'products',
			'refunds',
			'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'subscription_payment_method_change',
			'subscription_payment_method_change_customer',
			'subscription_payment_method_change_admin',
			'multiple_subscriptions',
		];
		$this->logger   = $logger;
	}

	public function hooks(): void {
		parent::hooks();
		add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, [
			$this,
			'scheduled_subscription_payment',
		], 10, 2 );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
	}

	public function process_payment( $order_id ): array {
		$order  = wc_get_order( $order_id );
		$client = $this->client_factory->get_client( $order->get_currency() );

		$transaction_data = $this->get_transaction_data( $order, self::ACCEPTANCE_STATE_INIT_SUBSCRIPTION, self::RECURRING_ACTION_INIT_WITH_PAYMENT );

		if ( $order->get_total() == 0 ) {
			$transaction_data = $this->get_transaction_data( $order, self::ACCEPTANCE_STATE_INIT_SUBSCRIPTION, self::RECURRING_ACTION_INIT_WITH_REFUND );
		}

		$response = $client->do_recurring_transaction_init( $transaction_data );
		$data     = $response->getData();

		if ( isset( $data[ self::TRANSACTION_DATA_REDIRECTURL_KEY ] ) ) {
			return [
				'result'   => 'success',
				'redirect' => $data[ self::TRANSACTION_DATA_REDIRECTURL_KEY ],
			];
		}

		wc_add_notice( __( 'An error occurred while processing the payment. Please try again. If the error persists, please contact support.', 'pay-wp' ), 'error' );
		if ( isset( $data[ self::TRANSACTION_DATA_REASON_KEY ] ) ) {
			wc_add_notice( sprintf( __( 'Reason: %s', 'pay-wp' ), $data[ self::TRANSACTION_DATA_REASON_KEY ] ), 'error' );
		}

		//TODO: Logger

		return [
			'result' => 'failure',
		];
	}

	private function process_recurring_payment( $order ) {
		$subscription = $this->get_parent_subscription( $order->get_id() );
		$parent_id    = $subscription->get_parent_id();
		$parent_order = wc_get_order( $parent_id );

		$client = $this->client_factory->get_client( $order->get_currency() );

		$transaction_data                                          = $this->get_transaction_data( $order, self::ACCEPTANCE_STATE_PROCESS_RENEWAL, self::RECURRING_ACTION_PROCESS_RENEWAL );
		$transaction_data[ self::TRANSACTION_DATA_CLIENTHASH_KEY ] = $parent_order->get_meta( self::ORDER_META_KEY_RPAN_CLIENT_HASH );

		$subscription->add_order_note( sprintf( __( 'Subscription renewal has begun. Order %s has been created', 'pay-wp' ), $order->get_id() ) );//TODO: Subscription note.

		$client->do_recurring_transaction_init( $transaction_data );
	}

	public function process_rpan() {
		$rpan_responder = new RPANResponse();

		if ( ! isset( $_POST[ self::RPAN_RECURRING_KEY ] ) ) {
			$this->logger->log( 'debug', 'No recurring data ' . print_r( $_POST, true ) );
			$rpan_responder->respond_with_failure( 'No recurring data' );
		}

		$data = ( new RPANDecoder )->base64_to_array( sanitize_text_field( $_POST[ self::RPAN_RECURRING_KEY ] ) );

		if ( ! isset( $data[ self::RPAN_TRANSACTION_KEY ][ self::RPAN_PAYMENTSTATUS_KEY ] ) || $data[ self::RPAN_TRANSACTION_KEY ][ self::RPAN_PAYMENTSTATUS_KEY ] !== self::RPAN_SUCCESSFUL_PAYMENT_STATUS ) {
			$this->logger->log( 'debug', 'Payment status is not success ' . print_r( $data, true ) );
			$rpan_responder->respond_with_failure( 'Payment status is not success' );
		}

		$order_id = $data[ self::RPAN_TRANSACTION_KEY ][ self::RPAN_ORDERID_KEY ];
		$order    = wc_get_order( $order_id );
		$client   = $this->client_factory->get_client( $order->get_currency() );

		if ( ! $this->is_response_valid( $data, $client ) ) {
			$this->logger->log( 'debug', 'Response is not valid ' . print_r( $data, true ) );
			$rpan_responder->respond_with_failure( 'Response is not valid' );
		}

		$response_hash = $client->hashTransactionParameters( [
			$client->get_service_id(),
			$data[ self::RPAN_RECURRINGDATA_KEY ][ self::RPAN_CLIENTHASH_KEY ],
			self::RPAN_CONFIRM,
		] );

		$order->update_meta_data( self::ORDER_META_KEY_RPAN, $data );
		$order->update_meta_data( self::ORDER_META_KEY_RPAN_CLIENT_HASH, $data[ self::RPAN_RECURRINGDATA_KEY ][ self::RPAN_CLIENTHASH_KEY ] );
		$order->save();

		$order->add_order_note( __( 'Subscription renewal successfully completed', 'pay-wp' ) );//TODO: Subscription note.


		$rpan_responder->respond_with_success( $client->get_service_id(), $data[ self::RPAN_RECURRINGDATA_KEY ][ self::RPAN_CLIENTHASH_KEY ], $response_hash, self::RPAN_CONFIRM );
	}

	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$refund_response = $this->trait_process_refund( $order_id, $amount, $reason );
		$order           = wc_get_order( $order_id );

		if ( is_wp_error( $refund_response ) ) {
			$order->add_order_note( sprintf( __( 'Refund failed: %s', 'pay-wp' ), $refund_response->get_error_message() ) );
			$this->logger->log( 'debug', 'Refund failed:  ' . print_r( $refund_response, true ) );

			return $refund_response;
		}

		$subscription = $this->get_parent_subscription( $order_id );
		if ( ! $subscription ) {
			$order->add_order_note( sprintf( __( 'Subscription for order %s not found', 'pay-wp' ), $order_id ) );
			$this->logger->log( 'debug', sprintf( 'Subscription for order %s not found', $order_id ) );

			return false;
		}

		$subscription->cancel_order( __( 'Subscription cancelled. Order has been refunded.', 'pay-wp' ) );
		$order->add_order_note( sprintf( __( 'Subscription %s cancelled', 'pay-wp' ), $subscription->get_id() ) );

		return $refund_response;
	}

	private function get_transaction_data( $order, string $acceptanceState, string $recurringAction ) {
		return [
			'OrderID'                  => $order->get_id(),
			'Amount'                   => $recurringAction === self::RECURRING_ACTION_INIT_WITH_REFUND ? self::MINIMAL_ALLOWED_AMOUNT : $order->get_total(),
			'Description'              => apply_filters( 'wp_pay\payment\description', __( 'Autopay transaction', 'pay-wp' ), $order, $this ),
			'GatewayID'                => self::AUTOPAY_RECURRING_GATEWAY_ID,
			'Currency'                 => $order->get_currency(),
			'CustomerEmail'            => $order->get_billing_email(),
			'RecurringAcceptanceState' => $acceptanceState,
			'RecurringAction'          => $recurringAction,
		];
	}

	public function scheduled_subscription_payment( $amount_to_charge, $order ) {
		$order->add_order_note( 'Processing renewal' );
		$this->process_recurring_payment( $order );
	}

	public function is_available() {
		$cart = WC()->cart;
		if ( ! empty( $cart ) && $this->enabled === 'yes' ) {
			/** @var WC_Product $item */
			foreach ( $cart->get_cart() as $cart_item ) {
				$item = $cart_item['data'];
				if ( $item->is_type( [ 'subscription', 'subscription_variation' ] ) ) {
					return true;
				}
				if ( isset( $cart_item['wcsatt_data']['active_subscription_scheme'] ) && ! empty( $cart_item['wcsatt_data']['active_subscription_scheme'] ) ) {
					return true;
				}
			}
		}

		return false;
	}

	private function get_parent_subscription( $order_id ) {

		$subscriptions       = wcs_get_subscriptions_for_order( $order_id, [ 'order_type' => 'any' ] );
		$parent_subscription = null;
		foreach ( $subscriptions as $subscription ) {
			$parent_subscription = $subscription;
		}

		return $parent_subscription;
	}

	private function is_response_valid( array $data, BlueMediaClient $client ): bool {
		$response_service_id = $data['serviceID'];
		$client_service_id   = $client->get_service_id();

		$authentication_data = $data;
		unset( $authentication_data['hash'] );
		$authentication_hash = $client->hashTransactionParameters( ArrayFlattener::flatten_array( $authentication_data ) );

		return ( $response_service_id === $client_service_id ) && ( $data['hash'] === $authentication_hash );
	}
}
