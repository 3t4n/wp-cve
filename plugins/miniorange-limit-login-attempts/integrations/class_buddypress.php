<?php
	
	class Mo_BuddyPress{

        public static function signup_errors() {
            if (!isset($_POST['signup_username']))
                return;

            $user_name = sanitize_text_field($_POST['signup_username']);
            $error = "";
            global $bp;
            // making sure we are in the registration page
            if ( !function_exists('bp_is_current_component') || !bp_is_current_component('register') ) {
                return;
            }
            $mollahandler = new Mo_lla_MoWpnsHandler();
            $mollaUtility = new Mo_lla_MoWpnsUtility();
            if (get_option('mo_lla_enable_brute_force')) {
                $userIp = $mollaUtility->get_client_ip();
                $userIp = sanitize_text_field($userIp);
                $mollahandler->add_transactions($userIp, $user_name, "User Registration","failed");

                $isWhitelisted = $mollahandler->is_whitelisted($userIp);
                if(!$isWhitelisted){
                    $failedAttempts = $mollahandler->get_failed_attempts_count($userIp);


                    //Slow Down
                    if(get_option('mo_lla_slow_down_attacks')){
                        session_start();
                        if(isset($_SESSION["mo_lla_failed_attepmts"]) && is_numeric($_SESSION["mo_lla_failed_attepmts"]))
                            $_SESSION["mo_lla_failed_attepmts"] += 1;
                        else
                            $_SESSION["mo_lla_failed_attepmts"] = 1;
                        $mo_lla_slow_down_attacks_delay = 2;
                        if(get_option('mo_lla_slow_down_attacks_delay'))
                            $mo_lla_slow_down_attacks_delay = get_option('mo_lla_slow_down_attacks_delay');
                        sleep($_SESSION["mo_lla_failed_attepmts"]*$mo_lla_slow_down_attacks_delay);
                    }


                    $allowedLoginAttepts = 5;
                    if(get_option('mo_lla_allwed_login_attempts'))
                        $allowedLoginAttepts = get_option('mo_lla_allwed_login_attempts');

                    if(get_option('mo_lla_enable_unusual_activity_email_to_user'))
                        $mo_lla_config->sendNotificationToUserForUnusualActivities($user_name, $userIp, "Failed login attempts from new IP.");

                    if($allowedLoginAttepts - $failedAttempts<=0){
                        $mo_lla_config->block_ip($userIp, "User exceeded allowed login attempts.", false);
                        if(get_option('mo_lla_enable_ip_blocked_email_to_admin'))
                            $mo_lla_config->sendIpBlockedNotification($userIp,"User exceeded allowed login attempts.");
                        require_once '../views/error/403.php';
                        exit();
                    }else {
                        if(get_option('mo_lla_show_remaining_attempts')){
                            $diff = $allowedLoginAttepts - $failedAttempts;
                            $error = "<br>You have <b>".$diff."</b> attempts remaining.";
                        }
                    }
                }
            }

            if (get_option('mo_lla_activate_recaptcha_for_buddypress_registration')) {
                $mo_lla_recaptcha_handler = new Mo_lla_reCaptcha();
                if (!$mo_lla_recaptcha_handler->recaptcha_verify(sanitize_text_field($_POST['g-recaptcha-response']))) {
                    if (!isset($bp->signup->errors)) {
                        $bp->signup->errors = array();
                    }
                    $message = "Invalid captcha. Please verify captcha again.\n$error";
                    $bp->signup->errors['recaptcha_error'] = __('Invalid captcha. Please verify captcha again.');
                    bp_core_add_message($message, 'error');
                } else {
                    bp_core_add_message($error, 'error');
                }
            } else {
                bp_core_add_message($error, 'error');
            }
        }
	}

?>