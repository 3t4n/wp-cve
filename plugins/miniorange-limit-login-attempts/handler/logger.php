<?php

class Logger
{
	function __construct()
	{
		add_action( 'log_403' , array( $this, 'log_403' ) );
		add_action( 'template_redirect', array( $this, 'log_404' ) );
	}	


	function log_403()
	{
		global $mollaUtility;
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			$userIp 		= $mollaUtility->get_client_ip();
			$userIp = sanitize_text_field($userIp);
			$url			= $mollaUtility->get_current_url();
			$user  			= wp_get_current_user();
			$username		= is_user_logged_in() ? $user->user_login : 'GUEST';
			$mo_lla_config->add_transactions($userIp,$username,Mo_lla_MoWpnsConstants::ERR_403, Mo_lla_MoWpnsConstants::ACCESS_DENIED,$url);
	}

	function log_404()
	{
		global $mollaUtility;

		if(!is_404())
			return;
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			$userIp 		= $mollaUtility->get_client_ip();
			$userIp = sanitize_text_field($userIp);
			$url			= $mollaUtility->get_current_url();
			$user  			= wp_get_current_user();
			$username		= is_user_logged_in() ? $user->user_login : 'GUEST';
			$mo_lla_config->add_transactions($userIp,$username,Mo_lla_MoWpnsConstants::ERR_404, Mo_lla_MoWpnsConstants::ACCESS_DENIED,$url);
	}
}
new Logger;