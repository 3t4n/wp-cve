<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Smart_Bribes extends xlwcty_component {

	private static $instance = null;

	public $is_disable = true;

	public function __construct( $order = false ) {
		parent::__construct();

	}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


}

return XLWCTY_Smart_Bribes::get_instance();
