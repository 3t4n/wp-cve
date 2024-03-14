<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname(__FILE__) . './../consts/rapyd-consts.php';

class WC_Gateway_Rapyd_Card extends WC_Rapyd_Payment_Gateway {

	public function __construct() {
		$this->id                  = RAPYD_CARD_ID;
		$this->title               = __( 'Card', 'rapyd-payments-plugin-for-woocommerce');
		$this->method_title        = __( 'Rapyd', 'rapyd-payments-plugin-for-woocommerce');
		$this->description         = __( 'Pay with local and international card brands', 'rapyd-payments-plugin-for-woocommerce' );
		/* translators: link */
		$this->method_description  = __( 'Accept card payments in your chosen countries. ', 'rapyd-payments-plugin-for-woocommerce' );
		$this->has_fields          = true;
		$this->icon                = 'https://cdn.rapyd.net/plugins/icons/card_icon.png';
		$this->supports = array(
			'products',
			'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			//'subscription_payment_method_change',
			//'subscription_payment_method_change_customer',
			//'subscription_payment_method_change_admin',
			'multiple_subscriptions'
		);
		$this->constructor_helper();
		//add subscription scheduled payment hook
		if (class_exists('WC_Subscriptions_Order')) {
			add_action('woocommerce_scheduled_subscription_payment_' . $this->id, array($this, 'scheduled_subscription_payment'), 10, 2);
		}
	}

	public function getCategory() {
		return RAPYD_CATEGORY_CARD;
	}

}

