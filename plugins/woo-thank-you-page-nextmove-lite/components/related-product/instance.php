<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Related_Products extends XLWCTY_Component {

	private static $instance = null;
	public $viewpath = '';
	public $related_product = array();
	public $is_disable = true;
	public $grid_type = '2c';

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

return XLWCTY_Related_Products::get_instance();
