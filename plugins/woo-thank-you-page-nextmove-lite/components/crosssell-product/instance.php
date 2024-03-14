<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Cross_Sell_Product extends XLWCTY_Component {

	private static $instance = null;
	public $is_disable = true;
	public $viewpath = '';


	public function __construct( $order = false ) {
		parent::__construct();

		$this->viewpath = __DIR__ . '/views/view.php';
	}

	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


}

return XLWCTY_Cross_Sell_Product::get_instance();
