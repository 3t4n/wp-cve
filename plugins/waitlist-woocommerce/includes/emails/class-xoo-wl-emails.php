<?php 

class Xoo_Wl_Emails{

	protected static $_instance = null;

	public $backInStock;
	public $adminNotify;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->backInStock 		= include XOO_WL_PATH.'includes/emails/class-xoo-wl-back-in-stock-email.php';
		$this->hooks();
	}

	public function hooks(){
		add_action( 'xoo_wl_email_head', array( $this, 'default_inline_style' ) );
		add_action( 'xoo_wl_email_header', array( $this, 'email_header' ) );
		add_action( 'xoo_wl_email_footer', array( $this, 'email_footer' ) );
	}


	public function email_header( $emailObj ){
		xoo_wl_helper()->get_template( '/emails/global/xoo-wl-email-header.php', array( 'emailObj' => $emailObj ) );
	}

	public function email_footer(){
		xoo_wl_helper()->get_template( '/emails/global/xoo-wl-email-footer.php' );
	}

	public function default_inline_style(){
		xoo_wl_helper()->get_template( 'emails/global/xoo-wl-email-style.php' );
	}

}
function xoo_wl_emails(){
	return Xoo_Wl_Emails::get_instance();
}