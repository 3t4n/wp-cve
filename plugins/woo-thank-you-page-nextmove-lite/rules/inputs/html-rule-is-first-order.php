<?php
defined( 'ABSPATH' ) || exit;

class xlwcty_Input_Html_Rule_Is_First_Order {
	public function __construct() {
		// vars
		$this->type = 'Html_Rule_Is_First_Order';

		$this->defaults = array(
			'default_value' => '',
			'class'         => '',
			'placeholder'   => '',
		);
	}

	public function render( $field, $value = null ) {

		_e( 'This Page will show on very first order for the customer.', 'woo-thank-you-page-nextmove-lite' );
	}

}
