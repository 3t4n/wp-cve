<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class Gateway_Sibs_DD extends Sibs_Payment_Gateway {

	public $id = 'sibs_dd';


	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/sepa.png';
	}

	protected function sibs_save_transactions( $order_id, $payment_result, $reference_id ) {
		parent::sibs_save_transactions( $order_id, $payment_result, $reference_id );

		$additional_info = $this->sibs_set_serialize_add_info();
	}

}

$obj = new Gateway_Sibs_DD();
