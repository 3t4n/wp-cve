<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class Gateway_Sibs_Mbway extends Sibs_Payment_Gateway {
	
	public $id = 'sibs_mbway';

	public $title = 'SIBS MB WAY';

	public function sibs_get_payment_logo() {
		return $this->plugins_url . '/assets/images/mbway.png';
	}
}

$obj = new Gateway_Sibs_Mbway();
