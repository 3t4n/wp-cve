<?php

namespace QuadLayers\QLWAPP\Models;

class Contact {

	protected static $instance;

	public function get_contacts_reorder() {
		return array();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
