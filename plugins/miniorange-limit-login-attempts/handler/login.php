<?php 

	class Mo_lla_LoginHandler
	{
		function __construct()
		{
			
            add_action( 'init' , array( $this, 'mo_lla_init' ) ); 
			add_action('wp_login', array( $this, 'mo_lla_login_success_update'),2);
			if(get_option('mo_lla_activate_recaptcha_for_login')||get_option('mo_lla_activate_recaptcha_for_woocommerce_login') || get_option('mo_lla_enable_rename_login_url') || get_option('mo_lla_enable_brute_force'))
            {
                remove_filter('authenticate', 'wp_authenticate_username_password',20 );
                if(get_option('mo_lla_enable_rename_login_url')){
                    add_filter   ('authenticate', array( $this, 'rename_login_custom_authenticate') ,1, 3 );
                }else
                    add_filter('authenticate', array( $this, 'custom_authenticate') ,1, 3 );
            }
			if(get_option('mo_lla_enable_brute_force'))
			{
				add_action('wp_login', array( $this, 'mo_lla_login_success'),1);
				add_action('wp_login_failed', array( $this, 'mo_lla_login_failed'));	
			}
            if(get_option('mo_lla_activate_recaptcha_for_woocommerce_registration') ){
				add_action( 'woocommerce_register_post', array( $this,'wooc_validate_user_captcha_register'), 1, 3);
			}
		}

		function mo_lla_init()
		{  
           
            global $mollaUtility,$mo_lla_dirName;
			$WAFEnabled = get_option('WAFEnabled');
			$WAFLevel = get_option('WAF');
			if($WAFEnabled == 1)
			{
				if($WAFLevel == 'PluginLevel')
				{   
				
					if(file_exists($mo_lla_dirName .'handler'.DIRECTORY_SEPARATOR.'mo-waf-plugin.php'))
						include_once($mo_lla_dirName .'handler'.DIRECTORY_SEPARATOR.'mo-waf-plugin.php');
					
				}
			}
			
				$userIp 	= $mollaUtility->get_client_ip();
				$lla_database = new Mo_lla_MoWpnsDB;
				$lla_count_ips_blocked = $lla_database->get_time_of_block_ip($userIp);
				$mo_lla_config = new Mo_lla_MoWpnsHandler();
				$isWhitelisted   = $mo_lla_config->is_whitelisted($userIp);
				$isIpBlocked = false;

				if(!$isWhitelisted){
					$isIpBlocked = $mo_lla_config->is_ip_blocked_in_anyway($userIp);                    
				}
				$mo_check_status_time = new Mo_lla_MoWpnsHandler();
				$mo_check_status_time->mollm_check_ip_duration();
				
				
				if(!is_bool($isIpBlocked) && $isIpBlocked['status']){
				    $error_message = $isIpBlocked['message'] ;
				 	include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'error'.DIRECTORY_SEPARATOR.'403.php';
				 	exit;
				 }

				$requested_uri = sanitize_text_field($_SERVER["REQUEST_URI"]);
           	    $option = false;
				  
				if (is_user_logged_in())
				{
                    $mo2f_url_login = (string) get_option('mo_lla_login_page_url', "false");
					
					$val=strpos($requested_uri, $mo2f_url_login);

					if (strpos($requested_uri, $mo2f_url_login) !== false) {
                        wp_safe_redirect(site_url()."/wp-admin". ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . sanitize_text_field($_SERVER['QUERY_STRING']) : '' ) );
						die;
					}
				} else {
					$option = get_option('mo_lla_enable_rename_login_url');
				}

				
				if ($option) {

                    global $pagenow;
	
					if (strpos($requested_uri, '/wp-login.php?checkemail=confirm')!==false) {	
						$requested_uri = str_replace("wp-login.php","",$requested_uri);
						wp_redirect($requested_uri);
						die;
					} else if (strpos($requested_uri, '/wp-login.php?checkemail=registered')!==false) {
						$requested_uri = str_replace("wp-login.php","",$requested_uri);
						wp_redirect($requested_uri);
						die;
					}else if (strpos($requested_uri, '/wp-login.php?action=logout')!==false || strpos($requested_uri, '/wp-login.php?loggedout')!==false) {
						$requested_uri = str_replace("wp-login.php",get_option('mo_lla_login_page_url'),$requested_uri);
						wp_redirect($requested_uri);
						die;
					}else if (strpos($requested_uri, '/wp-login.php?action=postpass')!==false) {
						wp_redirect($requested_uri);
					}else if ($pagenow =='wp-login.php' && strpos($requested_uri, '/wp-login.php')!==false && !is_user_logged_in()) {
						if(!isset($_POST['wp-submit'])){
							http_response_code(404);
							die;
						}					
					}
					else if (strpos($requested_uri, get_option('mo_lla_login_page_url'))!== false) {
						if(!isset($user_login))
						$user_login='';
						if(!isset($error))
						$error='';
						
						@require_once ABSPATH . 'wp-login.php';
						die;
					}
				}
				if(is_user_logged_in())
				{   
					$this->user_inactive_logout_action();
				}
		}
         
        

		function wooc_validate_user_captcha_register($username, $email, $validation_errors) {
			if (empty($_POST['g-recaptcha-response'])) {
				$validation_errors->add( 'woocommerce_recaptcha_error', __('Please verify the captcha', 'woocommerce' ) );
			}
		}
		//Our custom logic for user authentication
		function custom_authenticate($user, $username, $password)
		{
		
			global $mollaUtility;
			$error = new WP_Error();
			if(empty($username) && empty ($password))
				return $error;
			if(empty($username))
				$error->add('empty_username', __('<strong>ERROR</strong>: Username field is empty.'));
			if(empty($password))
				$error->add('empty_password', __('<strong>ERROR</strong>: Password field is empty.'));


			
			$error1 = wp_authenticate_username_password($user,$username,$password);

	
			if(is_wp_error($error1))
			{
				//$this->mo_lla_login_failed($username);
				return $error1;
			}
			if(empty($error->errors))
			{
				$user  = get_user_by("login",$username);
				if($user)
				{
                    if(get_option('mo_lla_activate_recaptcha_for_login'))
                    {
                        $captcha_version = get_option('mo_lla_recaptcha_version');
                        if($captcha_version=='reCAPTCHA_v3'){
                            $recaptchaError = $mollaUtility->verify_recaptcha_3(sanitize_text_field($_POST['g-recaptcha-response']));
                        }else if($captcha_version=='reCAPTCHA_v2'){
                            $recaptchaError = $mollaUtility->verify_recaptcha(sanitize_text_field($_POST['g-recaptcha-response']));
                        }
                    }
					if(!empty($recaptchaError->errors) )
					    {
                           
                          $recaptchaError = isset($recaptchaError) ? $recaptchaError : 'Error'; 	
                          $error = $recaptchaError;
                        }
					if(empty($error->errors)){

						if(!get_option('mo_lla_enable_brute_force'))
						{
						   $this->mo_lla_login_success($username);

						}
						return $user;
					}
				}
				else
					$error->add('empty_password', __('<strong>ERROR</strong>: Invalid Username.'));

			}
			return $error;
		}

       function rename_login_custom_authenticate($user, $username, $password)
		{
			global $mollaUtility;
			$error = new WP_Error();
		    $user = wp_authenticate_username_password( $user, $username, $password );
			if(empty($username) || empty($password) ){

			    $userIp 		= $mollaUtility->get_client_ip();
			    $mo_lla_config = new Mo_lla_MoWpnsHandler();
                $failedAttempts 	 = $mo_lla_config->get_failed_attempts_count($userIp);
                $allowedLoginAttempts = get_option('mo_lla_allwed_login_attempts') ? get_option('mo_lla_allwed_login_attempts') : 10;
                if($allowedLoginAttempts - $failedAttempts <= 0)
                    $this->handle_login_attempt_exceeded($userIp);
                else if(get_option('mo_lla_show_remaining_attempts') && $allowedLoginAttempts - $failedAttempts != $allowedLoginAttempts){
                    $this->show_limit_login_left($allowedLoginAttempts,$failedAttempts);
                    $error->add('empty_username', __('<strong>ERROR</strong>: Invalid username or Password.'));
                }
				return $error;
		    }
		    if(is_wp_error( $user )){
		        $error->add('empty_username', __('<strong>ERROR</strong>: Invalid username or Password.'));
                return $user;
		    }

			if(empty($error->errors)){

				$user  = get_user_by("login",$username);

				if($user)
				{
					 if(get_option('mo_lla_activate_recaptcha_for_login'))
                    {
                        $captcha_version = get_option('mo_lla_recaptcha_version');
                        if($captcha_version=='reCAPTCHA_v3'){
                            $recaptchaError = $mollaUtility->verify_recaptcha_3(sanitize_text_field($_POST['g-recaptcha-response']));
                        }else if($captcha_version=='reCAPTCHA_v2'){
                            $recaptchaError = $mollaUtility->verify_recaptcha(sanitize_text_field($_POST['g-recaptcha-response']));
                        }
                    }
						$error = $recaptchaError;
 					if(empty($error->errors)){
						if(!get_option('mo_lla_enable_brute_force'))
						{
						   $this->mo_lla_login_success($username);
						}
						return $user;
					}
				}
				else
					$error->add('empty_password', __('<strong>ERROR</strong>: Invalid Username or password.'));
			}

		}
        function mo_lla_login_success_update($username){
			//wp_login also run when brute force option is not unabled
			$user = get_user_by( 'login', $username );
		    update_user_meta($user->ID,'last_active_time',date('H:i:s'));
		}
		function mo_lla_login_success($username)
		{   
			global $mollaUtility;
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			$userIp 		= $mollaUtility->get_client_ip();
            filter_var($userIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);

            $mo_lla_config->move_failed_transactions_to_past_failed($userIp);
            //*************************** */
			$mo_lla_config->unblock_ip_using_ip($userIp);
			//*************************** */
			if(get_option('mo_lla_enable_unusual_activity_email_to_user'))
				$mollaUtility->sendNotificationToUserForUnusualActivities($username, $userIp, Mo_lla_MoWpnsConstants::LOGGED_IN_FROM_NEW_IP);
         

			$mo_lla_config->add_transactions($userIp, $username, Mo_lla_MoWpnsConstants::LOGIN_TRANSACTION, Mo_lla_MoWpnsConstants::SUCCESS);
		}
		//Function to handle failed user login attempt
		function mo_lla_login_failed($username)
		{
           
			global $mollaUtility;
			$userIp 		= $mollaUtility->get_client_ip();
            filter_var($userIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
			if(empty($userIp) || empty($username) || !get_option('mo_lla_enable_brute_force'))
				return;
             
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			$isWhitelisted  = $mo_lla_config->is_whitelisted($userIp);
			if($isWhitelisted){
				return;
			}
			$mo_lla_config->add_transactions($userIp, $username, Mo_lla_MoWpnsConstants::LOGIN_TRANSACTION, Mo_lla_MoWpnsConstants::FAILED);

		    if(get_option('mo_lla_enable_unusual_activity_email_to_user'))
			    $mollaUtility->sendNotificationToUserForUnusualActivities($username, $userIp,Mo_lla_MoWpnsConstants::FAILED_LOGIN_ATTEMPTS_FROM_NEW_IP);

			$failedAttempts 	 = $mo_lla_config->get_failed_attempts_count($userIp);
			$allowedLoginAttepts = get_option('mo_lla_allwed_login_attempts') ? get_option('mo_lla_allwed_login_attempts') : 10;

			if($allowedLoginAttepts - $failedAttempts<=0)
				$this->handle_login_attempt_exceeded($userIp);
			else if(get_option('mo_lla_show_remaining_attempts'))
				$this->show_limit_login_left($allowedLoginAttepts,$failedAttempts);
			
				
		}

		//Function to show number of attempts remaining
		function show_limit_login_left($allowedLoginAttepts,$failedAttempts)
		{
			global $error;
			$diff = $allowedLoginAttepts - $failedAttempts;

			$error = "<br>You have <b>".$diff."</b> login attempts remaining.";
		}
		//Function to handle login limit exceeded
		function handle_login_attempt_exceeded($userIp)
		{
			global $mollaUtility, $mo_lla_dirName;
			$mo_lla_config = new Mo_lla_MoWpnsHandler();
			$error_message = "Number of failed login attempts exceeded.";
			$mo_lla_config->block_ip($userIp, Mo_lla_MoWpnsConstants::LOGIN_ATTEMPTS_EXCEEDED, false);
			include $mo_lla_dirName . 'views'.DIRECTORY_SEPARATOR.'error'.DIRECTORY_SEPARATOR.'403.php';
			
		}
		function user_inactive_logout_action(){
	        if (is_user_logged_in() && get_option('mo_lla_inactive_user_logout')) {
	            $current_time = date('H:i:s');
	            $last_active_time = get_user_meta(get_current_user_id(),'last_active_time',true);
	            $inactive_logout_duration = get_option('mo_inactive_logout_duration');
	            $difference = strtotime($current_time) - strtotime($last_active_time);
	            $user = wp_get_current_user();
	            $roles = $user->roles[0];
	            if ($difference >= $inactive_logout_duration) {
	                if ("administrator" == $roles) {
	                    if (get_option('mo_inactive_allowed_admin_session')) {
	                        wp_logout();
	                    } else {
	                        update_user_meta(get_current_user_id(),'last_active_time',$current_time);
	                    }
	                } else {
	                    wp_logout();
	                }
	            } else {
	                update_user_meta(get_current_user_id(),'last_active_time',$current_time);
	            }
	        }
    	}

	}
	new Mo_lla_LoginHandler;
