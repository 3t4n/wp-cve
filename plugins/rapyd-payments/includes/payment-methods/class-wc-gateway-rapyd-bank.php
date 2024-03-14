<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname(__FILE__) . './../consts/rapyd-consts.php';

class WC_Gateway_Rapyd_Bank extends WC_Rapyd_Payment_Gateway {

	public function __construct() {
		$this->id                  = RAPYD_BANK_ID;
		$this->title               = __( 'Bank', 'rapyd-payments-plugin-for-woocommerce');
		$this->method_title        = __( 'Rapyd', 'rapyd-payments-plugin-for-woocommerce');
		$this->description         = __( 'Pay directly from your bank account', 'rapyd-payments-plugin-for-woocommerce' );
		/* translators: link */
		$this->method_description  = __( 'Accept bank payments in your chosen countries. ', 'rapyd-payments-plugin-for-woocommerce' );
		$this->has_fields          = true;
		$this->icon                = 'https://cdn.rapyd.net/plugins/icons/banktra_icon.png';
		$this->constructor_helper();
	}

	public function getCategory() {
		return RAPYD_CATEGORY_BANK;
	}

}
