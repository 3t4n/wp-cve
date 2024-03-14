<?php 

	global $mollaUtility,$mo_lla_dirName;


	if(current_user_can( 'manage_options' ) && isset($_REQUEST['option']))
	{
		switch(sanitize_text_field($_REQUEST['option']))
		{
			case "mo_lla_enable_brute_force":
				lla_handle_bf_enable_form($_POST);					break;
			case "mo_lla_brute_force_configuration":
				lla_handle_bf_configuration_form($_POST);			break;
			case "mo_lla_slow_down_attacks":
				lla_handle_dos_enable_form($_POST);					break;
			case "mo_lla_slow_down_attacks_config":
				lla_handle_dos_configuration($_POST);				break;
			case "mo_lla_activate_recaptcha":
				lla_handle_enable_recaptcha($_POST);				break;
			case "mo_lla_recaptcha_settings":
				lla_handle_recaptcha_configuration($_POST);			break;
			case "mo_lla_enable_rename_login_url":
				lla_handle_enable_rename_login_url($_POST);			break;
			case "mo_lla_rename_login_url_configuration":
				lla_handle_rename_login_url_configuration($_POST);	break;
			case "mo_lla_enable_fake_domain_blocking":
				lla_handle_domain_blocking($_POST);						
				break;
		}
	}
	

	$allwed_login_attempts 	= get_option('mo_lla_allwed_login_attempts')	  	? get_option('mo_lla_allwed_login_attempts')     : 10;
	$time_of_blocking_type 	= get_option('mo_lla_time_of_blocking_type')	  	? get_option('mo_lla_time_of_blocking_type')     : "permanent";
	$time_of_blocking_val 	= get_option('mo_lla_time_of_blocking_val')	  		? get_option('mo_lla_time_of_blocking_val')      : 3;
	$brute_force_enabled 	= get_option('mo_lla_enable_brute_force')  			? "checked" 								  	 : "";
	$remaining_attempts 	= get_option('mo_lla_show_remaining_attempts')   	? "checked" 								  	 : "";
	$slow_down_attacks		= get_option('mo_lla_slow_down_attacks') 		  	? "checked" 								  	 : "";
	$google_recaptcha		= get_option('mo_lla_activate_recaptcha')		  	? "checked"									     : "";

	$test_recaptcha_url		= add_query_arg( array('option'=>'testrecaptchaconfig'), sanitize_text_field($_SERVER['REQUEST_URI'] ));

    $test_recaptcha_url_v3		= add_query_arg( array('option'=>'testrecaptchaconfig3'), sanitize_text_field($_SERVER['REQUEST_URI'] ));
    $captcha_url		= 'https://www.google.com/recaptcha/admin#list';
    $captcha_url_v3      = 'https://www.google.com/recaptcha/admin/create';

    if(get_option('mo_lla_recaptcha_version')=='reCAPTCHA_v2'){
    $captcha_site_key	= get_option('mo_lla_recaptcha_site_key');
    $captcha_secret_key = get_option('mo_lla_recaptcha_secret_key');}

    else if(get_option('mo_lla_recaptcha_version')=='reCAPTCHA_v3'){
    $captcha_site_key	= get_option('mo_lla_recaptcha_site_key_v3');
    $captcha_secret_key = get_option('mo_lla_recaptcha_secret_key_v3');
    }

	$captcha_login			= get_option('mo_lla_activate_recaptcha_for_login') ? "checked" : "";
	$captcha_reg			= get_option('mo_lla_activate_recaptcha_for_registration') ? "checked" : "";
	$captcha_cmnt			= get_option('mo_lla_activate_recaptcha_for_comments')			? "checked"	: "";
	$captcha_bp_reg			= get_option('mo_lla_activate_recaptcha_for_buddypress_registration')	? "checked"	: "";
	$captcha_email			= get_option('mo_lla_activate_recaptcha_for_email_subscription')		? "checked"	: "";
    $pathll			        = "miniorange-limit-login-attempts/mo_limit_login_widget.php";
    $deactivateUrl 	        = wp_nonce_url(admin_url('plugins.php?action=deactivate&plugin='.$pathll), 'deactivate-plugin_'.$pathll);
    $domain_blocking        = get_option('mo_lla_enable_fake_domain_blocking') 		? "checked" : "";

	include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'login-security.php';

	//Function to handle enabling and disabling of brute force protection
	function lla_handle_bf_enable_form($postData)
	{
		$enable  =  isset($postData['enable_brute_force_protection']) ? true : false;
		update_option( 'mo_lla_enable_brute_force', $enable );

		if($enable)
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('BRUTE_FORCE_ENABLED'),'SUCCESS');
		else
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('BRUTE_FORCE_DISABLED'),'ERROR');
	}


	//Function to handle brute force configuration
	function lla_handle_bf_configuration_form($postData)
	{
		$login_attempts 	= sanitize_text_field($postData['allwed_login_attempts']);
		$blocking_type  	= sanitize_text_field($postData['time_of_blocking_type']);
		$blocking_value 	= isset($postData['time_of_blocking_val'])	 ? sanitize_text_field($postData['time_of_blocking_val'])	: false;
		$remaining_attempts = isset($postData['show_remaining_attempts'])? sanitize_text_field($postData['show_remaining_attempts']) : false;
       
		update_option( 'mo_lla_allwed_login_attempts'	, $login_attempts 		  );
		update_option( 'mo_lla_time_of_blocking_type'	, $blocking_type 		  );
		update_option( 'mo_lla_time_of_blocking_val' 	, $blocking_value   	  );
		update_option( 'mo_lla_show_remaining_attempts', $remaining_attempts 	  );

		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('CONFIG_SAVED'),'SUCCESS');
	}

	//Function to handle enabling and disabling google recaptcha
	function lla_handle_enable_recaptcha($postData)
	{
		$enable = isset($postData['mo_lla_activate_recaptcha']) ? sanitize_text_field($postData['mo_lla_activate_recaptcha']) : false;
		update_option( 'mo_lla_activate_recaptcha', $enable );

		if($enable)
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('RECAPTCHA_ENABLED'),'SUCCESS');
		else
		{
			update_option( 'mo_lla_activate_recaptcha_for_login'		, false );
			update_option( 'mo_lla_activate_recaptcha_for_registration', false );
            update_option( 'mo_lla_activate_recaptcha_for_woocommerce_login'		, false );
			update_option( 'mo_lla_activate_recaptcha_for_woocommerce_registration', false );
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('RECAPTCHA_DISABLED'),'ERROR');
		}
	}


	//Function to handle recaptcha configuration
	function lla_handle_recaptcha_configuration($postData)
	{

		$enable_login= isset($postData['mo_lla_activate_recaptcha_for_login']);
		$enable_reg  = isset($postData['mo_lla_activate_recaptcha_for_registration']);
		$site_key 	 = sanitize_text_field($_POST['mo_lla_recaptcha_site_key']);
		$secret_key  = sanitize_text_field($_POST['mo_lla_recaptcha_secret_key']); 

		update_option( 'mo_lla_activate_recaptcha_for_login'		, $enable_login );
		update_option( 'mo_lla_recaptcha_site_key'			 		, $site_key     );
		update_option( 'mo_lla_recaptcha_secret_key'				, $secret_key   );
		update_option( 'mo_lla_activate_recaptcha_for_registration', $enable_reg   );
        update_option( 'mo_lla_activate_recaptcha_for_woocommerce_login'		, $enable_login );
		update_option( 'mo_lla_activate_recaptcha_for_woocommerce_registration', $enable_reg   );
		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('RECAPTCHA_ENABLED'),'SUCCESS');
	}
	function lla_handle_enable_rename_login_url($postData){
		$enable_rename_login_url_checkbox = false;
		if(isset($postData['enable_rename_login_url_checkbox'])  && sanitize_text_field($postData['enable_rename_login_url_checkbox'])){
			$enable_rename_login_url_checkbox = sanitize_text_field($postData['enable_rename_login_url_checkbox']);
			do_action('lla_show_message','Rename Admin Login Page URL is enabled.','SUCCESS');
		}else {
			do_action('lla_show_message','Rename Admin Login Page URL is disabled.','SUCCESS');
		}

		$loginurl = get_option('mo_lla_login_page_url');
		if ($loginurl == "") {
			update_option('mo_lla_login_page_url', "mylogin");
		}
		update_option( 'mo_lla_enable_rename_login_url', $enable_rename_login_url_checkbox);
	}
		function lla_handle_domain_blocking($postvalue)
	{
		$enable_fake_emails = isset($postvalue['mo_lla_enable_fake_domain_blocking']) ? true : false;
		update_option( 'mo_lla_enable_fake_domain_blocking', $enable_fake_emails);

		if($enable_fake_emails)
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('DOMAIN_BLOCKING_ENABLED'),'SUCCESS');
		else
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('DOMAIN_BLOCKING_DISABLED'),'ERROR');
	}
	
	function lla_handle_rename_login_url_configuration($postData){
		if (isset($postData['option']) == 'mo_lla_rename_login_url_configuration') {
			
		if (isset($postData['login_page_url'])) {
				if(sanitize_text_field($postData['login_page_url'])=='login'){
					do_action('lla_show_message','URL cannot be renamed as "/login"','ERROR');
					return;
				}
				update_option('mo_lla_login_page_url', sanitize_text_field($postData['login_page_url']));
		} else {
			update_option('mo_lla_login_page_url', 'mylogin');
		}
		do_action('lla_show_message','Your configuration has been saved.','SUCCESS');
	}
	else
		exit;
	}
