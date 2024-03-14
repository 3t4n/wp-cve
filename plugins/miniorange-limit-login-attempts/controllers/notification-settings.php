<?php
	
	global $mollaUtility,$mo_lla_dirName;

	$template1	= "Hello,<br><br>The user with IP Address <b>##ipaddress##</b> has exceeded allowed trasaction limit on your website <b>".get_bloginfo()."</b> and we have blocked his IP address for further access to website.<br><br>You can login to your WordPress dashaboard to check more details.<br><br>Thanks,<br>miniOrange";
	$template2	= "Hello ##username##,<br><br>Your account was logged in from new IP Address <b>##ipaddress##</b> on website <b>".get_bloginfo()."</b>. Please <a href='mailto:".esc_attr(Mo_lla_MoWpnsConstants::SUPPORT_EMAIL)."'>contact us</a> if you don't recognise this activity.<br><br>Thanks,<br>".get_bloginfo();

	if(current_user_can( 'manage_options' )  && isset($_POST['option']) )
	{
		switch(sanitize_text_field($_POST['option']))
		{
			case "mo_lla_enable_ip_blocked_email_to_admin":
				lla_handle_notify_admin_on_ip_block($_POST);						break;
			case "mo_lla_enable_unusual_activity_email_to_user":
				lla_handle_notify_unusual_activity($_POST);						break;
			case "custom_user_template":
				lla_handle_custom_template(sanitize_text_field($_POST['custom_user_template']));		break;
            case "mo_lla_get_manual_email"	:
			    lla_handle_admin_email($_POST);                                    break; 
			case "custom_admin_template":
				lla_handle_custom_template(null,sanitize_text_field($_POST['custom_admin_template']));	break;
		}
	}
      if(!get_option("admin_email_address_status")|| get_option("admin_email_address") ==''){
     	update_option('mo_lla_enable_ip_blocked_email_to_admin','0');
       $notify_admin_on_ip_block = get_option('mo_lla_enable_ip_blocked_email_to_admin') 	 ? "" : "unchacked";
   }
	$notify_admin_on_ip_block 	   = get_option('mo_lla_enable_ip_blocked_email_to_admin') 	 ? "checked" : "";
	$notify_admin_unusual_activity = get_option('mo_lla_enable_unusual_activity_email_to_user') ? "checked" : "";

	$template1					   = get_option('custom_admin_template') ? get_option('custom_admin_template') : $template1;
	$template_type1				   = 'custom_admin_template';
	$ip_blocking_template		   = array(
										'textarea_name' => 'custom_admin_template',
										'wpautop' => false
									);
	$fromEmail 					   = get_option('mo_lla_admin_email');
	$template2					   = get_option('custom_user_template') ? get_option('custom_user_template') : $template2;
	$template_type2				   = 'custom_user_template';
	$user_activity_template		   = array(
										'textarea_name' => 'custom_user_template',
										'wpautop' => false
									);
	include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'notification-settings.php';



	/* ADMIN NOTIFICATION SETTINGS */
        function lla_handle_admin_email($postValue)
	{
		
        if(validate_email( sanitize_text_field($_POST['admin_email_address']))){
			$admin_email_address_status = isset($postValue['admin_email_address']) ? '1' :'0';
			update_option('admin_email_address',sanitize_email($postValue['admin_email_address']));
			update_option( 'admin_email_address_status', $admin_email_address_status);
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('EMAIL_SAVED'),'SUCCESS');
	    }else{
	    	do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('INVALID_EMAIL'),'ERROR');
	    }
	}
	function validate_email($str) {
     return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
     }

	//Function to handle enabling and disabling of admin notification on ip blocking
	function lla_handle_notify_admin_on_ip_block($postValue)
	{
		$enable_ip_blocked_email_to_admin = isset($postValue['enable_ip_blocked_email_to_admin']) ? true : false;
		update_option( 'mo_lla_enable_ip_blocked_email_to_admin', $enable_ip_blocked_email_to_admin);

		if($enable_ip_blocked_email_to_admin)
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('NOTIFY_ON_IP_BLOCKED'),'SUCCESS');
		else
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('DONOT_NOTIFY_ON_IP_BLOCKED'),'ERROR');
	}


	//Function to handle enabling and disabling of admin notification on unusual activity
	function lla_handle_notify_unusual_activity($postValue)
	{
		$enable_unusual_activity_email_to_user = isset($postValue['enable_unusual_activity_email_to_user']) ? true : false;
		update_option( 'mo_lla_enable_unusual_activity_email_to_user', $enable_unusual_activity_email_to_user);
			
		if($enable_unusual_activity_email_to_user)
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('NOTIFY_ON_UNUSUAL_ACTIVITY'),'SUCCESS');
		else
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('DONOT_NOTIFY_ON_UNUSUAL_ACTIVITY'),'ERROR');
	}


	//Function to save unusual activity email template
	function lla_handle_custom_template($template1,$template2=null)
	{
		if(!is_null($template1))
			update_option('custom_user_template', stripslashes($template1));

		if(!is_null($template2))
			update_option('custom_admin_template', stripslashes($template2));

		do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('TEMPLATE_SAVED'),'SUCCESS');
	}