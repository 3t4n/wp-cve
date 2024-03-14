<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}


class Gateway_Sibs_DC extends Sibs_Payment_Gateway {
	
	public $id = 'sibs_dc';


	public function get_icon() {
		$icon_html = $this->sibs_get_multi_icon();
		return apply_filters( 'woocommerce_gateway_icon', $icon_html, $this->id );
	}

}

$obj = new Gateway_Sibs_DC();
