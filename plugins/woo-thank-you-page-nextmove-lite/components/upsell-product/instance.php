<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Upsell_Products extends XLWCTY_Component {

	private static $instance = null;
	public $viewpath = '';
	public $upsell_product = array();
	public $grid_type = '2c';
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

return XLWCTY_Upsell_Products::get_instance();
