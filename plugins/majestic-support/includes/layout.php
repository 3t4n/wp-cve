<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_layout {

    static function MJTC_getNoRecordFound() {
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/no-record-found.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Sorry', 'majestic-support')) . '!
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('There was no record found', 'majestic-support')) . '...
						</span>
					</div>
				</div>
		';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }
    static function MJTC_getNoRecordFoundForAjax() {
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/no-record-icon.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Sorry!', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('There was no record found...', 'majestic-support')) . '
						</span>
					</div>
				</div>
		';
        return wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getPermissionNotGranted() {
    	$loginval = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('set_login_link');
        $loginlink = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('login_link');
        $registerval = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('set_register_link');
        $registerlink = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('register_link');
        include_once(MJTC_PLUGIN_PATH . 'includes/header.php');
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Access Denied', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('You have no permission to access this page', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-user-login-btn-wrp">';
							if (MJTC_includer::MJTC_getObjectClass('user')->MJTC_uid() == 0) {
								if ($loginval == 3){
                                    $hreflink = wp_login_url();
                                }
		                        else if($loginval == 2 && $loginlink != ""){
		                            $html .= '<a class="mjtc-support-login-btn" href="'.esc_url($loginlink).'" title="Login">' . esc_html(__('Login', 'majestic-support')) . '</a>';
		                        }else{
		                            $html .= '<a class="mjtc-support-login-btn" href="'.esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'login'))).'" title="Login">' . esc_html(__('Login', 'majestic-support')) . '</a>';
		                        }
		                        $is_enable = get_option('users_can_register');/*check to make sure user registration is enabled*/
	                            if ($is_enable) {
	                            	if($registerval == 3){
		                        	    $html .= '<a class="mjtc-support-register-btn" href="'.esc_url(wp_registration_url()).'" title="Login">' . esc_html(__('Register', 'majestic-support')) . '</a>';
		                        	}else if($registerval == 2 && $registerlink != ""){
		                        	    $html .= '<a class="mjtc-support-register-btn" href="'.esc_url($registerlink).'" title="Login">' . esc_html(__('Register', 'majestic-support')) . '</a>';
		                        	}else{
		                        		$html .= '<a class="mjtc-support-register-btn" href="'.esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'userregister'))).'" title="Login">' . esc_html(__('Register', 'majestic-support')) . '</a>';
		                        	}
		                        }
	                    	}

                    $html .= '</span>
					</div>
				</div>
		';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getNotStaffMember() {
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Access Denied', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('User is not allowed to access this page.', 'majestic-support')) . '
						</span>
					</div>
				</div>
		';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getYouAreLoggedIn() {
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/already-loggedin.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Sorry!', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('You are already Logged In.', 'majestic-support')) . '
						</span>
					</div>
				</div>
		';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getStaffMemberDisable() {
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Access Denied!', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('Your account has been disabled, please contact the administrator.', 'majestic-support')) . '
						</span>
					</div>
				</div>
		';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getSystemOffline() {
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/offline.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Offline', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . wp_kses_post(majesticsupport::$_config['offline_message'], MJTC_ALLOWED_TAGS) . '
						</span>
					</div>
				</div>
		';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getUserGuest($redirect_url = '') {
        $loginval = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('set_login_link');
        $loginlink = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('login_link');
        $registerval = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('set_register_link');
        $registerlink = MJTC_includer::MJTC_getModel('configuration')->getConfigValue('register_link');
        $html = '
                <div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/not-login-icon.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('You are not logged In', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('To access the page, please login', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-user-login-btn-wrp">';
							if ($loginval == 3){
                                $hreflink = wp_login_url();
                            }
	                        else if($loginval == 2 && $loginlink != ""){
	                            $html .= '<a class="mjtc-support-login-btn" href="'.esc_url($loginlink).'" title="Login">' . esc_html(__('Login', 'majestic-support')) . '</a>';
	                        }else{
	                            $html .= '<a class="mjtc-support-login-btn" href="'.esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'login', 'mjtc_redirecturl'=>$redirect_url))).'" title="Login">' . esc_html(__('Login', 'majestic-support')) . '</a>';
	                        }
	                        $is_enable = get_option('users_can_register');/*check to make sure user registration is enabled*/
                            if ($is_enable) {
                            	if($registerval == 3){
	                        	    $html .= '<a class="mjtc-support-register-btn" href="'.esc_url(wp_registration_url()).'" title="Login">' . esc_html(__('Register', 'majestic-support')) . '</a>';
	                        	}else if($registerval == 2 && $registerlink != ""){
	                        	    $html .= '<a class="mjtc-support-register-btn" href="'.esc_url($registerlink).'" title="Login">' . esc_html(__('Register', 'majestic-support')) . '</a>';
	                        	}else{
	                        		$html .= '<a class="mjtc-support-register-btn" href="'.esc_url(majesticsupport::makeUrl(array('mjsmod'=>'majesticsupport', 'mjslay'=>'userregister', 'mjtc_redirecturl'=>$redirect_url))).'" title="Login">' . esc_html(__('Register', 'majestic-support')) . '</a>';
	                        	}
	                        }

                    $html .= '</span>
                    </div>

				</div>
        ';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getYouAreNotAllowedToViewThisPage() {
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/not-permission-icon.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Sorry!', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('User is not allowed to view this Ticket', 'majestic-support')) . '
						</span>
					</div>
				</div>
		';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getRegistrationDisabled() {
        $html = '
				<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/ban.png"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html(__('Sorry!', 'majestic-support')) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' . esc_html(__('Registration has been disabled by admin, please contact the system administrator.', 'majestic-support')) . '
						</span>
					</div>
				</div>
		';
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
    }

    static function MJTC_getFeedbackMessages($msg_type) {
    	if($msg_type == 2){
    		$img_var = '3.png';
    		$text_var_1 = esc_html(__('Sorry!', 'majestic-support'));
    		$text_var_2 = esc_html(__('You have already given the feedback for this ticket.', 'majestic-support'));
    	}elseif($msg_type == 3){
    		$img_var = 'no-record-icon.png';
    		$text_var_1 = esc_html(__('Sorry!', 'majestic-support'));
    		$text_var_2 = esc_html(__('Ticket not found...!', 'majestic-support'));
    	}else{
    		$img_var = 'not-permission-icon.png';
    		$text_var_1 = esc_html(__('Sorry!', 'majestic-support'));
    		$text_var_2 = esc_html(__('User is not allowed to view this page', 'majestic-support'));
    	}
    	if($msg_type == 4){
			$html = '
					<div class="mjtc-support-error-message-wrapper">
						<div class="mjtc-support-message-image-wrapper">
							<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/success.png"/>
						</div>
						<div class="mjtc-support-messages-data-wrapper">
							<span class="mjtc-support-messages-main-text">
						    	'. esc_html(__('Thank you so much for your feedback', 'majestic-support')) .'
							</span>
							<span class="mjtc-support-messages-block_text">
						    	'. wp_kses(majesticsupport::$_config['feedback_thanks_message'], MJTC_ALLOWED_TAGS) .'
							</span>
						</div>
					</div>';
    	}else{
	        $html = '
					<div class="mjtc-support-error-message-wrapper">
					<div class="mjtc-support-message-image-wrapper">
						<img class="mjtc-support-message-image" alt="message image" src="' . esc_url(MJTC_PLUGIN_URL) . 'includes/images/error/'.esc_attr($img_var).'"/>
					</div>
					<div class="mjtc-support-messages-data-wrapper">
						<span class="mjtc-support-messages-main-text">
					    	' . esc_html($text_var_1) . '
						</span>
						<span class="mjtc-support-messages-block_text">
					    	' .wp_kses($text_var_2, MJTC_ALLOWED_TAGS). '
						</span>
					</div>
				</div>
			';
		}
        echo wp_kses($html, MJTC_ALLOWED_TAGS);
	}

}

?>
