<?php
defined( 'ABSPATH' ) || exit;

class xlwcty_Input_Html_Always {
	public function __construct() {
		// vars
		$this->type = 'Html_Always';

		$this->defaults = array(
			'default_value' => '',
			'class'         => '',
			'placeholder'   => '',
		);
	}

	public function render( $field, $value = null ) {

		_e( 'Trigger will always display for all shoppers on your site. This will override any other rule you define.', 'woo-thank-you-page-nextmove-lite' );
	}

}
