<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class Gateway_Sibs_Easycredit extends Sibs_Payment_Gateway {
	
	public $id = 'sibs_easycredit';

	
	public function sibs_get_payment_logo() {
		$logo = $this->plugins_url . '/assets/images/easycredit.png';

		return $logo;
	}

	protected function sibs_save_transactions( $order_id, $payment_result, $reference_id ) {
		parent::sibs_save_transactions( $order_id, $payment_result, $reference_id );

		$additional_info = $this->sibs_set_serialize_add_info();
	}
}

$obj = new Gateway_Sibs_Easycredit();
