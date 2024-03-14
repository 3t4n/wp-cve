<?php

/**
 * Plugin Name: WooCommerce Przelewy24 Payment Gateway by Przelewy24 Sp. z o.o.(v1.0.6)
 */
#[AllowDynamicProperties] 

  class WFACP_Przelewy24_Payment_Gateway {
	private $card_obj = null;
	private $blik_obj = null;

	public function __construct() {
		add_action( 'wfacp_checkout_after_order_review', [ $this, 'actions' ], 99 );
	}

	public function actions() {

		$this->card_obj = WFACP_Common::remove_actions( 'woocommerce_checkout_after_order_review', 'P24_Card_Html', 'extend_checkout_page_form' );
		$this->blik_obj = WFACP_Common::remove_actions( 'woocommerce_checkout_after_order_review', 'P24_Blik_Html', 'extend_checkout_page_form' );

		if ( $this->card_obj instanceof P24_Card_Html ) {
			$this->card_obj->extend_checkout_page_form();
		}
		if ( $this->blik_obj instanceof P24_Blik_Html ) {

			$this->blik_obj->extend_checkout_page_form();
		}

	}

}
new WFACP_Przelewy24_Payment_Gateway();