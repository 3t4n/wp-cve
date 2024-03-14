<?php

	class Mo_lla_reCaptcha
	{
		function __construct()
		{
			add_filter( 'admin_init' 	, array($this, 'handle_recaptcha'     ), 11, 3 	);
			add_action( 'login_form' 	, array($this, 'custom_login_fields'  )			);
			add_action( 'register_form' , array($this, 'register_with_captcha')			);
			add_action( 'woocommerce_register_form' , array($this, 'woocommerce_register_with_captcha'));
			add_action( 'woocommerce_login_form', array($this, 'woocommerce_login_with_captcha'));
			add_action( 'woocommerce_review_order_before_submit', array($this, 'woocommerce_register_with_captcha_checkout'));
		}


		//Function to handle Testing Mo_lla_reCaptcha
		function handle_recaptcha()
		{
			global $mollaUtility,$mo_lla_dirName;
			if (current_user_can( 'manage_options' ))
			{ 
				if(isset($_REQUEST['option']) && sanitize_text_field($_REQUEST['option'])=='testrecaptchaconfig')
				{	
						if(array_key_exists('g-recaptcha-response',$_POST))
						{
							$userIp 	= $mollaUtility->get_client_ip();
							$userIp = sanitize_text_field($userIp);
							$mocURL 	= new Mo_lla_MocURL;
                        $response 	= $mocURL->mo_lla_validate_recaptcha($userIp,sanitize_text_field($_POST['g-recaptcha-response']));
							$content	= json_decode($response, true);
							
							if(isset($content['error-codes']) && in_array("invalid-input-secret", $content['error-codes']))
								echo "<br><br><h2 style=color:red;text-align:center>Invalid Secret Key.</h2>";
							else if(isset($content['success']) && $content['success']==1)
								{
									echo "<br><br><h2 style=color:green;text-align:center>Test was successful and captcha verified.</h2>";
								}
							else
								echo "<br><br><h2 style=color:red;text-align:center>Invalid captcha. Please try again.</h2>";
						}
						Mo_lla_show_google_recaptcha_form();
				}
                if(isset($_REQUEST['option']) && sanitize_text_field($_REQUEST['option']=='testrecaptchaconfig3'))
                {
                    if(array_key_exists('g-recaptcha-response',$_REQUEST))
                    {
                        $userIp 	= $mollaUtility->get_client_ip();
                        $userIp     = filter_var($userIp, FILTER_VALIDATE_IP);

                        $mocURL 	= new Mo_lla_MocURL;
                        $response 	= $mocURL->mo_lla_get_Captcha_v3(sanitize_text_field($_REQUEST['g-recaptcha-response']));
                        $content	= json_decode($response, true);

                        if(isset($content['error-codes']) && in_array("invalid-input-secret", $content['error-codes']))
                            echo "<br><br><h2 style=color:red;text-align:center>Invalid Secret Key.</h2>";
                        else if(isset($content['success']) && $content['success']==1)
                        {
                            if($content['success']==1)
                            {
                                if($content['score']>=0.9)
                                {
                                    echo "<br><br><h2 style=color:green;text-align:center>Welcome!</h2>";
                                    echo "<h2 style=color:green;text-align:center>Test was successful and captcha verified.</h2>";
                                }
                                else echo "<br><br><h2 style=color:red;text-align:center>Captcha verification failed! Permission denied.</h2>";
                            }
                        }
                        else
                            echo "<br><br><h2 style=color:red;text-align:center>Invalid captcha. Please try again.</h2>";
                    }
                    mo_lla_show_google_recaptcha_form_v3();
                }
            }
        }


		function custom_login_fields()
		{
            global $mollaUtility,$mo2f_dirName;
            if(get_option('mo_lla_activate_recaptcha_for_login') )
            {
                if(get_option('mo_lla_recaptcha_version')=='reCAPTCHA_v3'){
                    mo_lla_show_google_recaptcha_form_v3_login();
                }
                else if(get_option('mo_lla_recaptcha_version')=='reCAPTCHA_v2')
                {
                   wp_register_script( 'wpns_catpcha_js',esc_url(Mo_lla_MoWpnsConstants::RECAPTCHA_URL));
				   wp_enqueue_script( 'wpns_catpcha_js' );
                    echo '<div class="g-recaptcha" data-sitekey="'.esc_html(get_option("mo_lla_recaptcha_site_key")).'"></div>';
                    echo '<style>#login{ width:349px;padding:2% 0 0; }.g-recaptcha{margin-bottom:5%;}#loginform{padding-bottom:20px;}</style>';
                }
            }
        }
		
		function register_with_captcha(){
			if(get_option('mo_lla_activate_recaptcha_for_registration'))
			{
                if(get_option('mo_lla_recaptcha_version')=='reCAPTCHA_v3')
                    mo_lla_show_google_recaptcha_form_v3_login();
                else if(get_option('mo_lla_recaptcha_version')=='reCAPTCHA_v2')
                    mo_lla_show_google_recaptcha_form_v2_login();
            }
        }

                 function woocommerce_register_with_captcha(){
			if(get_option('mo_lla_activate_recaptcha_for_woocommerce_registration'))
			{
				wp_register_script( 'wpns_catpcha_js',esc_url(Mo_lla_MoWpnsConstants::RECAPTCHA_URL));
				wp_enqueue_script( 'wpns_catpcha_js' );
				echo '<div class="g-recaptcha" data-sitekey="'.esc_html(get_option("mo_lla_recaptcha_site_key")).'"></div>';
				echo '<style>#login{ width:349px;padding:2% 0 0; }.g-recaptcha{margin-bottom:5%;}#registerform{padding-bottom:20px;}</style>';
			}
		}
		
		function woocommerce_login_with_captcha(){
			if(get_option('mo_lla_activate_recaptcha_for_woocommerce_login'))
			{
				
				wp_register_script( 'wpns_catpcha_js',esc_url(Mo_lla_MoWpnsConstants::RECAPTCHA_URL));
				wp_enqueue_script( 'wpns_catpcha_js' );
				     
				echo '<div class="g-recaptcha" data-sitekey="'.esc_html(get_option("mo_lla_recaptcha_site_key")).'"></div>';
				echo '<style>#login{ width:349px;padding:2% 0 0; }.g-recaptcha{margin-bottom:5%;}#loginform{padding-bottom:20px;}</style>';
			}
		}
	
		function woocommerce_register_with_captcha_checkout(){
			
			if (!is_user_logged_in()){
				if(get_option('mo_lla_activate_recaptcha_for_woocommerce_registration'))
				{
					wp_register_script( 'wpns_catpcha_js',esc_url(Mo_lla_MoWpnsConstants::RECAPTCHA_URL));
					wp_enqueue_script( 'wpns_catpcha_js' );
					echo '<div class="g-recaptcha" data-sitekey="'.esc_html(get_option("mo_lla_recaptcha_site_key")).'"></div>';
					echo '<style>#login{ width:349px;padding:2% 0 0; }.g-recaptcha{margin-bottom:5%;}#registerform{padding-bottom:20px;}</style>';
				}
			}
		}
		
		
		public static function recaptcha_verify($response)
		{
			global $mollaUtility;
			$userIp 	= $mollaUtility->get_client_ip();
			$userIp 	= sanitize_text_field($userIp);
			$mocURL 	= new Mo_lla_MocURL;
			$response 	= $mocURL->mo_lla_validate_recaptcha($userIp,$response);
			$content	= json_decode($response, true);
			$isvalid 	= isset($content['success']) && $content['success']==1 ? true : false;
			return $isvalid;
		}

		public static function recaptcha_verify_3($response)
        {
            global $mollaUtility;
            $userIp 	= $mollaUtility->get_client_ip();
            $userIp     = filter_var($userIp, FILTER_VALIDATE_IP);
            $mocURL 	= new Mo_lla_MocURL;
            $response 	= $mocURL->mo_lla_get_Captcha_v3($response);
            $content	= json_decode($response, true);
            $isvalid 	= isset($content['success']) && $content['success']==1 && $content['score']>=0.9? true : false;
            return $isvalid;
        }

	}
	new Mo_lla_reCaptcha;
