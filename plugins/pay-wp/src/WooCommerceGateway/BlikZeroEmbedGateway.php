<?php

namespace WPDesk\GatewayWPPay\WooCommerceGateway;

use WPDesk\GatewayWPPay\BlueMediaApi\BlueMediaClientFactory;
use WPPayVendor\Psr\Log\LoggerInterface;
use WPPayVendor\WPDesk\View\Renderer\Renderer;

final class BlikZeroEmbedGateway extends AbstractEmbedGateway {
	use RefundTrait;

	private const ID = 'wppay-blik-zero';

	private const SESSION_BLIK_PAYED        = 'blik-payed';
	private const SESSION_BLIK_TIME_STARTED = 'blik_started';
	private const JS_BLIK_TRIGGER           = 'wppay_blik_trigger';

	private const FORM_BLIK_CODE_INPUT_NAME = 'blik_code';

	private $plugin_url;
	private LoggerInterface $logger;
	private Renderer $renderer;

	public function __construct( BlueMediaClientFactory $client_factory, string $plugin_url, Renderer $renderer, LoggerInterface $logger) {
		$this->plugin_url     = $plugin_url;
		$this->client_factory = $client_factory;
		$this->renderer = $renderer;

		$this->id                 = self::ID;
		$this->icon               = $plugin_url . '/assets/images/icon-blik.png';
		$this->has_fields         = true;
		$this->method_title       = __( 'Autopay Quick Payments - BLIK', 'pay-wp' );
		$this->method_description = __( 'Autopay online payments - BLIK for WooCommerce. Pay directly from the order form, no redirects.',
			'pay-wp' );

		$this->init_form_fields();
		$this->init_settings();

		$this->enabled     = $this->get_option( 'enabled', 'no' );
		$this->description = $this->get_option( 'description', 'BLIK dla WooCommerce.' );

		$this->title = $this->get_option( 'title',
			__( 'BLIK - pay using code', 'pay-wp' ) );

		$this->supports = [
			'products',
			'refunds',
		];
		$this->logger = $logger;
	}

	public function hooks(): void {
		parent::hooks();
		if ( isset( $_POST['payment_method'] ) && $_POST['payment_method'] === self::ID ) {
			add_action( 'woocommerce_after_checkout_validation', [ $this, 'after_checkout_valudation' ] );
		}
		add_action( 'wc_ajax_wppay_refresh_blik_order_status', [ $this, 'refresh_blik_order_status' ], 10, 1 );
		add_action( 'wp_enqueue_scripts', [ $this, 'embed_blik_widget_js' ] );
	}

	public function after_checkout_valudation(): void {
		if ( empty( $_POST[ self::FORM_BLIK_CODE_INPUT_NAME ] ) ) {
			wc_add_notice( __( "Fill in BLIK code.", 'pay-wp' ), 'error' );
		}
	}

	public function process_payment( $order_id ) {
		$blik_status = get_post_meta( $order_id, self::SESSION_BLIK_PAYED, true );

		$block_checkout = isset( $_POST['block_checkout'] ); /* Check if request sent by checkout with blocks */

		if ( $blik_status !== 'SUCCESS' && ! empty( $_POST[ self::FORM_BLIK_CODE_INPUT_NAME ] ) ) {

			if ( ! $this->is_blik_valid( (int) $_POST[ self::FORM_BLIK_CODE_INPUT_NAME ] ) ) {
				$error_message =  __('Invalid BLIK code.', 'pay-wp');
				if ( $block_checkout ) {
					return [
						'result'  => 'failure',
						'message' => $error_message,
						'type'    => 'ERROR',
					];
				}
				wc_add_notice( $error_message, 'error' );
				wp_send_json( [
					'orderId'  => $order_id,
					'result'   => 'failure',
					'messages' => wc_print_notices( true ),
					'refresh'  => false,
					'reload'   => false,
				] );
			}
			$order = wc_get_order( $order_id );

			$order->update_meta_data( self::SESSION_BLIK_TIME_STARTED, time() );
			$order->update_meta_data( self::SESSION_BLIK_PAYED, 'START' );
			$order->save();

			if ( $this->send_blik_payment( (int) $_POST[ self::FORM_BLIK_CODE_INPUT_NAME ], (int) $order_id ) ) {

				if ( $block_checkout ) {
					return [
						'orderId'  => $order_id,
						'result'   => 'failure',
						'messages' => wc_print_notices( true ),
						'refresh'  => false,
						'reload'   => false,
					];
				}

				wc_add_notice( __( 'Confirm the transaction on the mobile app.', 'pay-wp' ) . '<span style="display: none;" id="autopay_blik_orderId">' . $order_id . '</span>', 'notice' );
				wp_send_json( [
					'orderId'  => $order_id,
					'result'   => 'failure',
					'messages' => wc_print_notices( true ),
					'refresh'  => false,
					'reload'   => false,
				] );
			}
		}

		return [
			'result'   => 'failure',
			'messages' => __( 'Something went wrong while processing your order. Please try again or contact us for help.', 'pay-wp' ),
		];
	}

	private function is_blik_valid( string $blik ): bool {
		return ( strlen( $blik ) === 6 ) && ( is_numeric( $blik ) );
	}

	public static function update_blik_status( int $order_id, string $status ): void {
		$order = wc_get_order( $order_id );
		$order->update_meta_data( self::SESSION_BLIK_PAYED, $status );
		$order->save();
	}

	private function send_blik_payment( int $code, int $order_id ): bool {
		$currency = get_woocommerce_currency();
		$client   = $this->client_factory->get_client( $currency );
		$order    = new \WC_Order( $order_id );

		$transaction_data = [
			'orderID'           => $order_id,
			'amount'            => $order->get_total( 'api' ),
			'description'       => apply_filters( 'wp_pay\payment\description',
				__( 'Autopay transaction', 'pay-wp' ), $order, $this ),
			'currency'          => $currency,
			'gatewayID'         => 509,
			'customerEmail'     => $order->get_billing_email(),
			// Identyfikator usługi Blik
			'authorizationCode' => $code,
			'blikUIDKey'        => get_current_user_id(),
			// Klucz Aliasu UID (używane w BLIK). Jest to unikalny identyfikator użytkownika w Serwisie.
			//			'BlikAMKey' => '', // Klucz Aliasu aplikacji mobilnej banku (używane w BLIK). Jest to unikalny identyfikator konta w BLIK.
			// Etykieta Aliasu UID (używane w BLIK), która będzie prezentowana Klientowi w aplikacji bankowej w celu rozróżniania kont u Partnera.
			'blikUIDLabel'      => get_current_user_id(),

		];

		$response = $client->doTransactionInit( $transaction_data );
		/** @var \WPPayVendor\BlueMedia\Transaction\ValueObject\TransactionInit $data */
		$data = $response->getData();
		$data = $data->toArray();
		if ( in_array( $data['confirmation'], [ 'PENDING', 'SUCCESS' ] ) ) {
			$order->update_meta_data( self::SESSION_BLIK_PAYED, $data['confirmation'] );
			$order->save();
		}
		if ( isset( $data['reason'] ) ) {
			$order->update_meta_data( self::SESSION_BLIK_PAYED, $data['reason'] );
			$order->save();
		}

		return true;
	}

	public function embed_blik_widget_js(): void {
			wp_enqueue_style( 'wppay-blik-field-styles', $this->plugin_url . '/assets/css/blik_field.css' );
		if ( $this->enabled === 'yes' && is_checkout() ) {
			wp_enqueue_script( 'wppay-blik-script', $this->plugin_url . 'assets/js/blik-checkout.js', [], 17, false );
		}
	}

	public function refresh_blik_order_status(): void {
		$order_id = (int) $_POST['order_id'];
		if ( empty( $order_id ) ) {
			wp_send_json( [
				'type' => 'error',
				'msg'  => __( "Error while verifying BLIK status.", 'pay-wp' ),
			] );
		}

		$started = get_post_meta( $order_id, self::SESSION_BLIK_TIME_STARTED, time() );
		if ( $started <= time() - 3 * 60 ) {

			wp_send_json( [
				'type' => 'error',
				'msg'  => __( "The code has expired.", 'pay-wp' ),
			] );
		}

		$blik_status = get_post_meta( $order_id, self::SESSION_BLIK_PAYED, true );
		if ( $blik_status === 'SUCCESS' ) {
			wp_send_json( [
				'type'     => 'success',
				'redirect' => $this->get_return_url( new \WC_Order( $order_id ) ),
			] );
		} elseif ( $blik_status === 'WRONG_TICKET' ) {
			wp_send_json( [
				'type' => 'error',
				'msg'  => __( "Invalid code.", 'pay-wp' ),
			] );
		} elseif ( $blik_status === 'MULTIPLY_PAID_TRANSACTION' ) {
			wp_send_json( [
				'type' => 'error',
				'msg'  => __( "The transaction has already been paid.", 'pay-wp' ),
			] );
		} elseif ( $blik_status === 'TICKET_EXPIRED' ) {
			wp_send_json( [
				'type' => 'error',
				'msg'  => __( "The code has expired.", 'pay-wp' ),
			] );
		} elseif ( $blik_status === 'START' || $blik_status === 'PENDING' ) {
			wp_send_json( [
				'type' => 'pending',
				'time' => $started,
				'msg'  => __( "Confirm the code in your banking app.", 'pay-wp' ),
			] );
		} else {
			wp_send_json( [
				'type' => 'error',
				'msg'  => __( "Invalid code.", 'pay-wp' ),
			] );
		}
	}

	public function payment_fields(): void {
		$this->renderer->output_render('wppay-blik-zero-form',[
			'description' => $this->get_description(),
		]);
	}

}
