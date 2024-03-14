<?php

namespace WC_BPost_Shipping\Label\Exception;

use Throwable;

class WC_BPost_Shipping_Label_Exception_Too_Much_Found extends WC_BPost_Shipping_Label_Exception_Base {
	public function __construct( $message = '', $code = 0, Throwable $previous = null ) {
		if ( ! $message ) {
			$message = bpost__( 'Too much labels found.' );
		}
		parent::__construct( $message, $code, $previous );
	}
}
