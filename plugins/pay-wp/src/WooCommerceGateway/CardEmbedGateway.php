<?php

namespace WPDesk\GatewayWPPay\WooCommerceGateway;

use WPDesk\GatewayWPPay\BlueMediaApi\BlueMediaClientFactory;

/**
 * @deprecated
 */
final class CardEmbedGateway extends AbstractEmbedGateway {
	use RefundTrait;

	const ID = 'wppay-card';

	private $plugin_url;

	public function __construct( BlueMediaClientFactory $client_factory, string $plugin_url ) {
		$this->plugin_url     = $plugin_url;
		$this->client_factory = $client_factory;

		$this->id                 = self::ID;
		$this->icon               = $plugin_url . '/assets/images/icon-visa.png';
		$this->has_fields         = true;
		$this->method_title       = __( 'Autopay Quick Payments - Card Payment', 'pay-wp' );
		$this->method_description = __( 'Autopay Online Payments - Card Payment for WooCommerce. Pay directly from the order form, no redirects.',
			'pay-wp' );

		$this->init_form_fields();
		$this->init_settings();

		$this->enabled     = $this->get_option( 'enabled', 'no' );
		$this->description = $this->get_option( 'description', 'Zapłać przy użyciu danych z karty' );

		$this->title = $this->get_option( 'title',
			__( 'Card payment', 'pay-wp' ) );

		$this->supports = [
			'products',
			'refunds',
		];
	}

	private function get_cart_action_url(): string {
		$test_mode = $this->client_factory->get_settings()->get_option( StandardPaymentGateway::SETTINGS_FIELD_TEST ) === 'yes';
		if ( $test_mode ) {
			return 'https://cards-accept.bm.pl';
		}

		return 'https://cards.bm.pl';
	}

	public function payment_fields(): void {
		$action_url = $this->get_cart_action_url();
		ob_start();
		include __DIR__ . '/../views/wppay-card-form.php';
		echo ob_get_clean();
	}

	public function embed_card_widget_js() {
		if ( $this->enabled === 'yes' && is_checkout() ) {
			wp_enqueue_script( 'wppay-card-widget', 'https://cards.bm.pl/integration/widget.js', [ 'jquery' ], 1,
				false );
			wp_enqueue_script( 'wppay-card-script', $this->plugin_url . '/assets/js/card-checkout.js',
				[ 'wppay-card-widget' ], 3, false );
		}
	}

	public function hooks(): void {
		parent::hooks();
		add_action( 'wp_enqueue_scripts', [ $this, 'embed_card_widget_js' ] );
		add_action( 'woocommerce_checkout_process', [ $this, 'validate_card_on_checkout' ] );
	}

	public function process_payment( $order_id ): array {
		global $woocommerce;
		$order            = new \WC_Order( $order_id );
		$client           = $this->client_factory->get_client( $order->get_currency() );
		$transaction_data = [
			'orderID'       => $order->get_id(),
			'amount'        => $order->get_total(),
			'description'   => apply_filters( 'wp_pay\payment\description', __( 'Autopay transaction', 'pay-wp' ), $order, $this ),
			'currency'      => $order->get_currency(),
			'customerEmail' => $order->get_billing_email(),

			'gatewayID'    => 1511,
			'paywayId'     => 1500,
			'paymentToken' => sanitize_text_field( $_REQUEST['card_token'] ),
			'walletType'   => 'WIDGET',
		];

		$response = $client->doTransactionInit( $transaction_data );
		/** @var \WPPayVendor\BlueMedia\Transaction\ValueObject\TransactionContinue $data */
		$data = $response->getData();

		if ( $data->getStatus() === 'SUCCESS' ) {
			$order->add_order_note( __( 'Card payment - Redirected to payment', 'pay-wp' ) );
			$redirect = $order->get_checkout_payment_url( true );
		} else {
			$order->add_order_note( __( 'Card payment', 'pay-wp' ) );

			$redirect = $data->getRedirectUrl();
		}
		$woocommerce->cart->empty_cart();

		return [
			'result'   => 'success',
			'redirect' => $redirect,
		];
	}

	public function validate_card_on_checkout(): void {
		if ( $_REQUEST['payment_method'] === self::ID ) {
			if ( empty( $_REQUEST['card_token'] ) ) {
				wc_add_notice( __( "Card was not authorized correctly.", 'pay-wp' ),
					'error' );
			}
		}
	}
}
