<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*
* Error Handle calss
*/
class MXMTZC_Display_Error
{

	/**
	* Error notice
	*/
	public $mxmtzc_error_notice = '';

	public function __construct( $mxmtzc_error_notice )
	{

		$this->mxmtzc_error_notice = $mxmtzc_error_notice;

	}

	public function mxmtzc_show_error()
	{
		add_action( 'admin_notices', function() { ?>

			<div class="notice notice-error is-dismissible">

			    <p><?php echo $this->mxmtzc_error_notice; ?></p>
			    
			</div>
		    
		<?php } );
	}

}