<?php

namespace WPDesk\GatewayWPPay\BlueMediaApi\Handlers;

use WPDesk\GatewayWPPay\WooCommerceGateway\StandardPaymentGateway;
use WPDesk\GatewayWPPay\WooCommerceGateway\SubscriptionGateway;
use WPPayVendor\Psr\Log\LoggerInterface;
use WPPayVendor\WPDesk\PluginBuilder\Plugin\Hookable;

class APIHandler implements Hookable {

	private const ITN_TYPE    = 'itn';
	private const RPAN_TYPE   = 'rpan';
	private const RETURN_TYPE = 'return';
	private const TYPE_KEY    = 'type';

	private StandardPaymentGateway $standard_payment_gateway;
	private SubscriptionGateway $subscription_gateway;
	private LoggerInterface $logger;

	public function __construct( StandardPaymentGateway $standard_payment_gateway, SubscriptionGateway $subscription_gateway, LoggerInterface $logger ) {
		$this->standard_payment_gateway = $standard_payment_gateway;
		$this->subscription_gateway     = $subscription_gateway;
		$this->logger                   = $logger;
	}

	public function hooks() {
		add_action( 'woocommerce_api_wc-gateway-wppay', [ $this, 'check_autopay_response' ] );
	}

	public function check_autopay_response() {
		$type = sanitize_text_field( $_GET[ self::TYPE_KEY ] );
		$this->logger->debug(print_r($_GET, true ));

		switch ( $type ) {
			case self::ITN_TYPE:
				$this->standard_payment_gateway->itn_response();
				break;
			case self::RPAN_TYPE:
				$this->subscription_gateway->process_rpan();
				break;
			case self::RETURN_TYPE:
			default:
				$this->standard_payment_gateway->return_after_transaction();
				break;
		}
	}


}
