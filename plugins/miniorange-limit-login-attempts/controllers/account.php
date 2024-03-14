<?php 
	
	global $mollaUtility,$mo_lla_dirName;
	$register_obj = new Mo_lla_MoWpnsUtility();

	if ( current_user_can( 'manage_options' ) and isset( $_POST['option'] ) )
	{
		$option = sanitize_text_field($_POST['option']);

		switch($option)
		{
			case "mo_lla_register_customer":
				_register_customer($_POST);	
				break;
			case "mo_lla_verify_customer":
				_verify_customer($_POST);																
				 break;
			case "mo_lla_cancel":
				_revert_back_registration();															
			     break;
			case "mo_lla_reset_password":
				_reset_password();																	
				break;
			case "mo_lla_remove_password":
				mo_lla_remove_password();																	
				break;

			case "mo_lla_goto_verifycustomer":
				mo_lla_goto_verifycustomer();																	
				break;
		}
	} 

 
	if (get_option ( 'mo_lla_verify_customer' ) == 'true' || (get_option('mo_lla_admin_email') && !get_option('mo_lla_admin_customer_key')))
	{
		$admin_email = get_option('mo_lla_admin_email') ? get_option('mo_lla_admin_email') : "";		
		include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'login.php';
	}
	else if (! $register_obj->icr()) 
	{
		delete_option ( 'password_mismatch' );
		update_option ( 'mo_lla_new_registration', 'true' );
		$current_user 	= wp_get_current_user();
		include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'register.php';
	} 
	else
	{
		$email = get_option('mo_lla_admin_email');
		$key   = get_option('mo_lla_admin_customer_key');
		$api   = get_option('mo_lla_admin_api_key');
		$token = get_option('mo_lla_customer_token');
		include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'mo_lla_profile.php';
	}
	/* REGISTRATION RELATED FUNCTIONS */

	//Function to register new customer
	function mo_lla_goto_verifycustomer (){
		update_site_option('mo_lla_verify_customer', 'true');
	}
	function _register_customer($post)
	{
		//validate and sanitize
		global $mollaUtility;
		$email 			 = sanitize_email($post['email']);
		$password 		 = sanitize_text_field($post['password']);
		$confirmPassword = sanitize_text_field($post['confirmPassword']);

		if( strlen( $password ) < 6 || strlen( $confirmPassword ) < 6)
		{
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('PASS_LENGTH'),'ERROR');
			return;
		}
		
		if( $password != $confirmPassword )
		{
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('PASS_MISMATCH'),'ERROR');
			return;
		}

		if( $mollaUtility->check_empty_or_null( $email ) || $mollaUtility->check_empty_or_null( $password ) 
			|| $mollaUtility->check_empty_or_null( $confirmPassword ) ) 
		{
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('REQUIRED_FIELDS'),'ERROR');
			return;
		} 

		update_option( 'mo_lla_admin_email', $email );
		update_option( 'mo_lla_password'   , $password );

		$customer = new Mo_lla_MocURL();
		$content  = json_decode($customer->check_customer($email), true);
	
	}
	//Function to go back to the registration page
	function _revert_back_registration()
	{
		delete_option('mo_lla_admin_email');
		delete_option('mo_lla_registration_status');
		delete_option('mo_lla_verify_customer');
	}


	//Function to reset customer's password
	function _reset_password()
	{
		$customer = new Mo_lla_MocURL();
		$forgot_password_response = json_decode($customer->mo_lla_forgot_password());
		if($forgot_password_response->status == 'SUCCESS')
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('RESET_PASS'),'SUCCESS');
	}
	function mo_lla_remove_password()
	{

		delete_option("mo_lla_admin_email");
		delete_option("mo_lla_registration_status");
		delete_option("mo_lla_verify_customer");
		delete_option("mo_lla_customer_token");
		delete_option("mo_lla_admin_customer_key");
	}


	//Function to verify customer
	function _verify_customer($post)
	{
		global $mollaUtility;

		$email 	  = sanitize_email( $post['email'] );
		$password = sanitize_text_field( $post['password'] );

		if( $mollaUtility->check_empty_or_null( $email ) || $mollaUtility->check_empty_or_null( $password ) ) 
		{
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('REQUIRED_FIELDS'),'ERROR');
			return;
		} 
		_get_current_customer($email,$password);
	}


	//Function to get customer details
	function _get_current_customer($email,$password)
	{
		$customer 	 = new Mo_lla_MocURL();
		$content     = $customer->get_customer_key($email,$password);
		$customerKey = json_decode($content, true);


		if(json_last_error() == JSON_ERROR_NONE && $customerKey['status']!='ERROR') 
		{
			update_option("mo_lla_registration_status",true);
			update_option( 'mo_lla_admin_phone', $customerKey['phone'] );
			update_option('mo_lla_admin_email',$email);
			save_success_customer_config($customerKey['id'], $customerKey['apiKey'], $customerKey['token'], $customerKey['appSecret']);
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('REG_SUCCESS'),'SUCCESS');
		} 
		else 
		{
			update_option('mo_lla_verify_customer', 'true');
			delete_option('mo_lla_new_registration');
			do_action('lla_show_message',Mo_lla_MoWpnsMessages::showMessage('ACCOUNT_EXISTS'),'ERROR');
		}
	}
	
		
	//Save all required fields on customer registration/retrieval complete.
	function save_success_customer_config($id, $apiKey, $token, $appSecret)
	{
		update_option( 'mo_lla_admin_customer_key'  , $id 		  );
		update_option( 'mo_lla_admin_api_key'       , $apiKey     );
		update_option( 'mo_lla_customer_token'		 , $token 	  );
		update_option( 'mo_lla_app_secret'			 , $appSecret );
		update_option( 'mo_lla_enable_log_requests' , true 	      );
		update_option( 'mo_lla_password'			 , ''		  );
		delete_option( 'mo_lla_verify_customer'				      );
		delete_option( 'mo_lla_registration_status'			  	  );
		delete_option( 'mo_lla_password'						  );
	}