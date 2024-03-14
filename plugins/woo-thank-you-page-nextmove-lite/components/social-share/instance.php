<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Order_Share extends xlwcty_component {

	private static $instance = null;
	public $viewpath = '';
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

return XLWCTY_Order_Share::get_instance();
