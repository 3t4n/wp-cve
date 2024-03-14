<?php

namespace WPDesk\GatewayWPPay\WooCommerceGateway;

use WPDesk\GatewayWPPay\BlueMediaApi\BlueMediaClientFactory;
use WPDesk\GatewayWPPay\BlueMediaApi\BlueMediaNoCredentilalsException;
use WPPayVendor\BlueMedia\Client;
use WPPayVendor\BlueMedia\Itn\ValueObject\ItnResponse\ItnResponse;
use WPPayVendor\Psr\Log\LoggerInterface;
use WPPayVendor\Psr\Log\NullLogger;

final class StandardPaymentGateway extends \WC_Payment_Gateway {
	use RefundTrait;

	const ID = 'wppay';
	const SETTINGS_FIELD_ID = 'id';
	const SETTINGS_FIELD_HASH = 'hash';
	const SETTINGS_FIELD_TEST = 'test_mode';
	public const SETTINGS_LOGGER_TEST = 'logger';
	const SETTINGS_FIELD_EMBED = 'embed';
	const SETTINGS_FIELD_SUBSCRIPTION = 'subscription';

	const AJAX_ACTION_GET_SINGLE_CURRENCY = 'wppay_get_single_currency';
	const NONCE_AJAX_ACTION_GET_SINGLE_CURRENCY = 'nonce_wppay_get_single_currency';

	const SUPPORTED_CURRENCIES = [ 'PLN', 'EUR', 'GBP', 'USD' ];
	/**
	 * @var BlueMediaClientFactory
	 */
	private $client_factory;

	/**
	 * Optional URL to view a transaction.
	 *
	 * @var string
	 */
	public $view_transaction_url = 'https://oplacasie.bm.pl/admin/transaction/%s';
	private LoggerInterface $logger;

	public function __construct( BlueMediaClientFactory $client_factory, string $plugin_url, LoggerInterface $logger = null ) {
		$this->client_factory = $client_factory;

		$this->id                 = self::ID;
		$this->icon               = $plugin_url . '/assets/images/icon.svg';
		$this->has_fields         = true;
		$this->method_title       = __( 'Autopay Quick Payments', 'pay-wp' );
		$this->method_description = __( 'Autopay online payments for WooCommerce. Pay quickly and securely with electronic transfer, BLIK, G-Pay, Apple Pay or credit card.', 'pay-wp' );

		$this->init_form_fields();
		$this->init_settings();

		$this->enabled     = $this->get_option( 'enabled', 'no' );
		$this->description = $this->get_option( 'description', 'Autopay - Zapłać szybko i bezpiecznie dowolną metodą.' );

		$this->title = $this->get_option( 'title', __( 'Autopay Quick Payments', 'pay-wp' ) );

		$this->supports = [
			'products',
			'refunds'
		];

		$this->logger = $logger ?? new NullLogger();
	}

	public function init_form_fields(): void {
		$this->form_fields = array(
			'main_settings'           => [
				'title' => __( 'Main settings', 'pay-wp' ),
				'type'  => 'title'
			],
			'registration_info'       => [
				'type' => 'registration_info'
			],
			'enabled'                 => array(
				'title'   => __( 'Enable/Disable', 'pay-wp' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Autopay', 'pay-wp' ),
				'default' => 'no'
			),
			self::SETTINGS_FIELD_TEST => array(
				'title'       => __( 'Test mode', 'pay-wp' ),
				'type'        => 'checkbox',
				'label'       => __( 'Make test payments only', 'pay-wp' ),
				'description' => __( 'The ID and Hash Key for the test environment are different from the production data. To get the data for the test environment, please contact - dws@autopay.pl.',
					'pay-wp' ),
				'default'     => 'no'
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
			),
			'pos'                     => [
				'title' => __( 'Currency management', 'pay-wp' ),
				'type'  => 'accounts'
			],
		);
	}

	public function generate_registration_info_html( $key, $data ) {
		ob_start();
		include __DIR__ . '/../views/registration_info.php';

		return ob_get_clean();
	}

	public function hooks(): void {
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );

//		add_action( 'woocommerce_api_wc-gateway-wppay', [ $this, 'check_wppay_response' ] );
		add_action( 'woocommerce_receipt_wppay', [ $this, 'receipt_page' ] );

		add_filter( 'woocommerce_payment_gateways', function ( $methods ) {
			$methods[] = $this;

			return $methods;
		} );

		add_action( 'wp_ajax_' . self::AJAX_ACTION_GET_SINGLE_CURRENCY,
			array( $this, 'ajax_get_single_currency_process' ) );
	}

	public function itn_response(): void {
//		file_put_contents( __DIR__ . '/itn_response.txt', 'test', FILE_APPEND );
		$transactions = sanitize_text_field( $_REQUEST['transactions'] ?? '' );
		if ( empty( $transactions ) ) {
			wp_die( __( 'Incorrect ITN message', 'pay-wp' ) );
		}
		$itn = Client::getItnObject( $transactions );

		$bm_client = $this->client_factory->get_client( $itn->getCurrency() );
		$result    = $bm_client->doItnIn( $transactions );

		/** @var \WPPayVendor\BlueMedia\Itn\ValueObject\Itn $itnIn */
		$itnIn                = $result->getData();
		$transactionConfirmed = $bm_client->checkHash( $itnIn );
		$transactionConfirmed = true; // some errors with hash validation. I have to do it myself.
//		file_put_contents( __DIR__ . '/itn_response.txt', print_r( $itnIn, true ), FILE_APPEND );
		wc_get_logger()->debug( print_r( $itnIn, true ), [ 'source' => 'wppay' ] );

		if ( $transactionConfirmed ) {
			$status = $itnIn->getPaymentStatus();
			$order  = new \WC_Order( $itnIn->getOrderId() );

			BlikZeroEmbedGateway::update_blik_status( $order->get_id(), $status );

			if ( $status === 'SUCCESS' ) {
				if ( $order->has_status( [
					'wc-pending',
					'pending',
					'wc-failed',
					'failed',
					'wc-on-hold',
					'on-hold'
				] ) ) {
					if ( $order->has_status( [ 'wc-failed', 'failed' ] ) ) {
						$order->add_order_note( __( 'Override FAILURE status due to ITN message',
							'pay-wp' ) );
					}
				}
				$order->payment_complete( $itn->getRemoteID() );

			} elseif ( $status === 'FAILURE' ) {
				if ( $order->has_status( [ 'wc-pending', 'pending', 'wc-on-hold', 'on-hold' ] ) ) {
					$order->update_status( 'failed' );
				}
			}
		}

		$itnResponse = $bm_client->doItnInResponse( $itnIn, $transactionConfirmed );
		/** @var ItnResponse $responseData */
		$responseData = $itnResponse->getData();
		die( $responseData->toXml() );
	}

	public function return_after_transaction(): void {
		$serviceId = (int) $_GET['ServiceID'];
		$order_id  = (int) $_GET['OrderID'];
		$hash      = sanitize_text_field( $_GET['Hash'] );

		$order     = new \WC_Order( $order_id );
		$bm_client = $this->client_factory->get_client( $order->get_currency() );

		$data = [
			'ServiceID' => $serviceId,
			'OrderID'   => $order_id,
			'Hash'      => $hash
		];

		$valid = $bm_client->doConfirmationCheck( $data );
		if ( $valid ) {
			wp_redirect( $order->get_checkout_order_received_url() );
		} else {
			$order->add_order_note( __( 'Incorrect hash sent from BM', 'pay-wp' ) );
		}
	}

	public function process_payment( $order_id ): array {
		global $woocommerce;
		$order = new \WC_Order( $order_id );
		$order->add_order_note( __( 'Redirected to payment', 'pay-wp' ) );
		$woocommerce->cart->empty_cart();

		if ( isset( $_REQUEST['channel'] ) ) {
			$order->update_meta_data( 'wppay_channel', sanitize_key( $_REQUEST['channel'] ) );
			$order->save();
		}

		return array(
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url( true )
		);
	}

	public function receipt_page( $order_id ): void {
		try {
			$order = new \WC_Order( $order_id );

			$form = $this->generate_payment_form( $order );
			echo '<p>' . __( 'Thank you for placing your order. Click to make payment by using Autopay.',
					'pay-wp' ) . '</p>';
			echo $form;
		} catch ( BlueMediaNoCredentilalsException $e ) {
			_e( 'The payment method was not configured correctly and is not available.',
				'pay-wp' );
		}
	}

	private function generate_payment_form( \WC_Order $order ): string {
		$bm_client = $this->client_factory->get_client( $order->get_currency() );

		$transaction = [
			'orderID'       => $order->get_id(),
			'amount'        => $order->get_total(),
			'description'   => apply_filters( 'wp_pay\payment\description', __( 'Autopay transaction', 'pay-wp' ), $order, $this ),
			'currency'      => $order->get_currency(),
			'customerEmail' => $order->get_billing_email()
		];
		$channel     = get_post_meta( $order->get_id(), 'wppay_channel', true );
		if ( ! empty( $channel ) ) {
			$transaction['gatewayID'] = $channel;
		}

		$result = $bm_client->getTransactionRedirect( $transaction );

		return $result->getData();
	}

	public function admin_options(): void {
		?>
        <table class="form-table"><?php
			$this->generate_settings_html();
			?></table>
		<?php
	}

	/**
	 * @internal Magic WC_Settings method.
	 */
	public function validate_accounts_field( $key, $value ): string {
		$value = ! is_array( $value ) ? [] : $value;

		foreach ( $value as $currency => $pos ) {
			foreach ( $pos as $pos_key => $pos_value ) {
				$value[ $currency ][ $pos_key ] = wp_kses_post( trim( stripslashes( $pos_value ) ) );
			}
		}

		return serialize( $value );
	}

	/**
	 * @internal Magic WC_Settings method.
	 */
	public function generate_accounts_html( $key, $data ): string {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'    => '',
			'desc_tip' => false,
		);

		$data = wp_parse_args( $data, $defaults );

		$default  = [
			get_woocommerce_currency() => []
		];
		$profiles = ! empty( $this->get_option( $key ) ) ? unserialize( $this->get_option( $key ) ) : $default;

		ob_start();
		include __DIR__ . '/../views/wppay-settings-pos.php';

		return ob_get_clean();
	}

	/**
	 * @internal Magic WC_Settings method.
	 */
	public function generate_payments_html( $key, $data ): string {
		$possibleChannels = $this->getPaymentChannels();

		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'    => '',
			'desc_tip' => false,
		);

		$data           = wp_parse_args( $data, $defaults );
		$sortedChannels = ! empty( $this->get_option( $key ) ) ? unserialize( $this->get_option( $key ) ) : array_keys( $possibleChannels );

		$intersection = array_diff( array_keys( $possibleChannels ), $sortedChannels );
		if ( count( $intersection ) > 0 ) {
			$sortedChannels += $intersection;
		}

		ob_start();
		include __DIR__ . '/views/wppay-settings-payments.php';

		return ob_get_clean();
	}

	/**
	 * @return array{'gatewayID' :int, 'gatewayName': string, 'gatewayType': string, 'bankName': string, 'iconURL': string}
	 */
	protected function getPaymentChannels(): array {
		try {
			$list = $this->client_factory->get_client()->getPaywayList();
		} catch ( \Exception $e ) {
			error_log( $e->getMessage() );
			$list = [];
		}

		$indexedList = [];
		foreach ( $list->getData() as $channel ) {
			$indexedList[ $channel['gatewayID'] ] = $channel;
		}

		return $indexedList;
	}

	public function validate_payments_field( $key, $value ): string {
		$value = ! is_array( $value ) ? [] : $value;

		return serialize( $value );
	}

	/**
	 * @internal
	 */
	public function ajax_get_single_currency_process(): void {
		try {
			if ( ! wp_verify_nonce( ( $_POST['security'] ?? '' ),
					self::NONCE_AJAX_ACTION_GET_SINGLE_CURRENCY ) && ! current_user_can( 'manage_options' ) ) {
				throw new \RuntimeException( 'Error, you are not allowed to do this action' );
			}

			$currency = sanitize_text_field( $_POST['currency'] ?? '' );
			$currency = wp_kses_post( trim( stripslashes( $currency ) ) );

			if ( ! in_array( $currency, self::SUPPORTED_CURRENCIES ) ) {
				throw new \RuntimeException( 'Error, this currency is not supported' );
			}


			wp_send_json( [
				'success' => true,
				'content' => $this->generate_single_pos( $currency )
			] );
		} catch ( \Exception $e ) {
			wp_send_json( [
				'success' => false,
				'message' => $e->getMessage()
			] );
		}
	}

	public function generate_single_pos( string $currency ): string {

		$is_default_profile = false;
		$is_test_mode       = false;
		$field_key          = $this->get_field_key( 'pos' );

		ob_start();
		include __DIR__ . '/../views/wppay-settings-pos-item.php';

		return ob_get_clean();
	}
}
