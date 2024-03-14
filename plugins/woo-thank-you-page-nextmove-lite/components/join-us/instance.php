<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Social_Sharing extends XLWCTY_Component {

	private static $instance = null;
	public $viewpath = '';
	public $is_disable = true;

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

return XLWCTY_Social_Sharing::get_instance();
