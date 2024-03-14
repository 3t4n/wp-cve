<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname(__FILE__) . './../consts/rapyd-consts.php';

class WC_Gateway_Rapyd_Cash extends WC_Rapyd_Payment_Gateway {

	public function __construct() {
		$this->id                  = RAPYD_CASH_ID;
		$this->title               = __( 'Cash', 'rapyd-payments-plugin-for-woocommerce');
		$this->method_title        = __( 'Rapyd', 'rapyd-payments-plugin-for-woocommerce');
		$this->description         = __( 'Pay with cash at a nearby convenience store or cash locations', 'rapyd-payments-plugin-for-woocommerce' );
		/* translators: link */
		$this->method_description  = __( 'Accept cash payments in your chosen countries. ', 'rapyd-payments-plugin-for-woocommerce' );
		$this->has_fields          = true;
		$this->icon                = 'https://cdn.rapyd.net/plugins/icons/cash_icon.png';
		$this->constructor_helper();
	}

	public function getCategory() {
		return RAPYD_CATEGORY_CASH;
	}

}
