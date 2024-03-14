<?php

namespace WC_BPost_Shipping\Api\Exception;

use WC_BPost_Shipping\Exception\WC_BPost_Shipping_Exception;

abstract class WC_BPost_Shipping_Api_Exception_Abstract extends WC_BPost_Shipping_Exception {

	public function get_short_name() {
		return str_replace( 'WC_BPost_Shipping_Api_', '', get_class( $this ) );
	}
}
