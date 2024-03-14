<?php

// phpcs:ignoreFile

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Trigger_Order_Delivered extends Trigger_Abstract_Order_Status_Base {

	public $_target_status = 'delivered';

	public function load_admin_details() {
		parent::load_admin_details();
		$this->title = __( 'Order Delivered', 'trackship-for-woocommerce' );
	}
}
