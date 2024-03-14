<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Acl_WordPress_Notallowed_Controller_HC_MVC extends _HC_MVC
{
	public function execute()
	{
		wp_die( __( 'Sorry, you are not allowed to access this page.' ), 403 );
	}
}