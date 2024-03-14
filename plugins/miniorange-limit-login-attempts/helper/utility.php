<?php

/** miniOrange enables user to log in through mobile authentication as an additional layer of security over password.
    Copyright (C) 2015  miniOrange

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
* @package 		miniOrange OAuth
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

class Mo_lla_MoWpnsUtility
{
   
	public static function icr() 
	{
		$email 			= get_option('mo_lla_admin_email');
		$customerKey 	= get_option('mo_lla_admin_customer_key');
		if( ! $email || ! $customerKey || ! is_numeric( trim( $customerKey ) ) )
			return 0;
		else
			return 1;
	}
	
	public static function check_empty_or_null( $value )
	{
		if( ! isset( $value ) || empty( $value ) )
			return true;
		return false;
	}
	
	public static function is_curl_installed()
	{
		if  (in_array  ('curl', get_loaded_extensions()))
			return 1;
		else 
			return 0;
	}
	
	public static function is_extension_installed($name)
	{
		if  (in_array  ($name, get_loaded_extensions()))
			return true;
		else
			return false;
	}
	
	public static function get_client_ip() 
	{
		if(isset($_SERVER['REMOTE_ADDR'])){
        	$ipaddress = sanitize_text_field($_SERVER['REMOTE_ADDR']); 
		}
    	else{
        	$ipaddress = '';
		}
		return $ipaddress;
	}

	public static function check_if_valid_email($email)
	{
		$emailarray = explode("@",$email);
		if(sizeof($emailarray)==2)
			return in_array(trim($emailarray[1]), Mo_lla_MoWpnsConstants::$domains);
		else
			return false;
	}
	public static function get_current_url()
	{
		$protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || sanitize_text_field($_SERVER['SERVER_PORT']) == 443) ? "https://" : "http://";
		$url	   = $protocol . sanitize_text_field($_SERVER['HTTP_HOST'])  . sanitize_text_field($_SERVER['REQUEST_URI']);
		return $url;
	}

	//Function to handle recptcha
	function verify_recaptcha($response)
	{
		$error = new WP_Error();
		if(!empty($response))
		{
			if(!Mo_lla_reCaptcha::recaptcha_verify($response))
				$error->add('recaptcha_error', __( '<strong>ERROR</strong> : Invalid Captcha. Please verify captcha again.'));
			else
				return true;
		}
		else
			$error->add('recaptcha_error', __( '<strong>ERROR</strong> : Please verify the captcha.'));
		return $error;
	}

	function verify_recaptcha_3($response)
    {
        $error = new WP_Error();
        if(!empty($response))
        {
            if(!Mo_lla_reCaptcha::recaptcha_verify_3($response))
                $error->add('recaptcha_error', __( '<strong>ERROR</strong> : Access Denied.'));
            else
                return true;
        }
        else{

            $error->add('recaptcha_error', __( '<strong>ERROR</strong> : Please verify the captcha.'));
        }
        return $error;
    }


	function sendIpBlockedNotification($ipAddress, $reason)
	{
		global $mollaUtility;
		$subject = 'User with IP address '.$ipAddress.' is blocked | '.get_bloginfo();
		
		$toEmail = get_option('admin_email_address');
        	$content = "";
		if(get_option('custom_admin_template'))
		{
			$content = get_option('custom_admin_template');
			$content = str_replace("##ipaddress##",$ipAddress,$content);
		}
		else
			$content = $this->getMessageContent($reason,$ipAddress);
		
		if(isset($content)){
			$this->wp_mail_send_notification($toEmail,$subject,$content);
		}	
	}

	function wp_mail_send_notification($toEmail,$subject,$content){

		$headers = array('Content-Type: text/html; charset=UTF-8');
		$mail_status=wp_mail( $toEmail, $subject, $content, $headers);

	}
	
	
	function sendNotificationToUserForUnusualActivities($username, $ipAddress, $reason)
	{
		$content = "";
		//check if email not already sent

		if(get_option($ipAddress.$reason)){
			return json_encode(array("status"=>'SUCCESS','statusMessage'=>'SUCCESS'));
		}
		
		global $mollaUtility;

		$user = get_user_by( 'login', $username );
		if($user && !empty($user->user_email))
			$toEmail = $user->user_email;
		else
			return;
		
		$mo_lla_config = new Mo_lla_MoWpnsHandler();
		if($mo_lla_config->is_email_sent_to_user($username,$ipAddress))
			return;

		$fromEmail = get_option('mo_lla_admin_email');
		$subject   = 'Sign in from new location for your user account | '.get_bloginfo();

		if(get_option('custom_user_template'))
		{
			$content = get_option('custom_user_template');
			$content = str_replace("##ipaddress##",$ipAddress,$content);
			$content = str_replace("##username##",$username,$content);
		}
		else
			$content = $this->getMessageContent($reason,$ipAddress,$username,$fromEmail);
		
		// $mocURL = new Mo_lla_MocURL();
		// return $mocURL->send_notification($toEmail,$subject,$content,$fromEmail,get_bloginfo(),$username);
		return $this->wp_mail_send_notification($toEmail,$subject,$content,$fromEmail);
	}

	//Check if null what will be the message
	function getMessageContent($reason,$ipAddress,$username=null,$fromEmail=null)
	{
		switch($reason)
		{
			case Mo_lla_MoWpnsConstants::LOGIN_ATTEMPTS_EXCEEDED:
				$content = "Hello,<br><br>The user with IP Address <b>".filter_var($ipAddress)."</b> has exceeded allowed failed login attempts on your website <b>".get_bloginfo()."</b> and we have blocked his IP address for further access to website.<br><br>You can login to your WordPress dashaboard to check more details.<br><br>Thanks,<br>miniOrange" ;
				return $content;
			case Mo_lla_MoWpnsConstants::IP_RANGE_BLOCKING:
				$content = "Hello,<br><br>The user's IP Address <b>".filter_var($ipAddress)."</b> was found in IP Range specified by you in Advanced IP Blocking and we have blocked his IP address for further access to your website <b>".get_bloginfo()."</b>.<br><br>You can login to your WordPress dashaboard to check more details.<br><br>Thanks,<br>miniOrange" ;
				return $content;
			case Mo_lla_MoWpnsConstants::LOGGED_IN_FROM_NEW_IP:
				$content = "Hello ".esc_html($username).",<br><br>Your account was logged in from new IP Address <b>".filter_var($ipAddress)."</b> on website <b>".get_bloginfo()."</b>. Please <a href='mailto:".esc_html($fromEmail)."'>contact us</a> if you don't recognise this activity.<br><br>Thanks,<br>".get_bloginfo() ;
				return $content;
			case Mo_lla_MoWpnsConstants::FAILED_LOGIN_ATTEMPTS_FROM_NEW_IP:
				$subject = 'Someone trying to access you account | '.get_bloginfo();
				$content =  "Hello ".esc_html($username).",<br><br>Someone tried to login to your account from new IP Address <b>".filter_var($ipAddress)."</b> on website <b>".get_bloginfo()."</b> with failed login attempts. Please <a href='mailto:".esc_html($fromEmail)."'>contact us</a> if you don't recognise this activity.<br><br>Thanks,<br>".get_bloginfo() ;
				return $content;
			default:
				if(is_null($username))
					$content = "Hello,<br><br>The user with IP Address <b>".filter_var($ipAddress)."</b> has exceeded allowed trasaction limit on your website <b>".get_bloginfo()."</b> and we have blocked his IP address for further access to website.<br><br>You can login to your WordPress dashaboard to check more details.<br><br>Thanks,<br>miniOrange" ;
				else
					$content   = "Hello ".esc_html($username).",<br><br>Your account was logged in from new IP Address <b>".filter_var($ipAddress)."</b> on website <b>".get_bloginfo()."</b>. Please <a href='mailto:".esc_html($fromEmail)."'>contact us</a> if you don't recognise this activity.<br><br>Thanks,<br>".get_bloginfo() ;
				return $content;
		}
	}

	function getCurrentBrowser()
	{
		$useragent = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
		if(empty($useragent))
			return false;

		$useragent = strtolower($useragent);
		if(strpos($useragent, 'edge') 		!== false || strpos($useragent, 'edg') !== false)
			return 'edge';
		else if(strpos($useragent, 'opr') 	!== false)
			return 'opera';
		else if(strpos($useragent, 'chrome') !== false || strpos($useragent, 'CriOS') !== false)
			return 'chrome';
		else if(strpos($useragent, 'firefox') 	!== false)
			return 'firefox';
		else if(strpos($useragent, 'msie') 	  	!== false || strpos($useragent, 'trident') 	!==false)
			return 'ie';
		else if(strpos($useragent, 'safari') 	!== false)
			return 'safari';
	}
	 
	public static function molla_send_configuration($send_all_configuration=false){
		global $mollaUtility;
		$user_object                    = wp_get_current_user();
		$key                            = get_option('mo_lla_admin_customer_key');
		
		$space                          = "<span>&nbsp;&nbsp;&nbsp;</span>";
		$browser                        =  $mollaUtility->getCurrentBrowser();
		
		$plugin_configuration =$space."<br><br><I>Plugin Configuration :-</I>".$space.(is_multisite()?"Multisite : Yes":"Single-site : Yes").$space.( $key? "Key : ".$key :'' ).$space."Browser : ".$browser;
		
		if(is_multisite()){
			
			$plugin_configuration = $plugin_configuration.$space.($is_plugin_active_for_network?"Network activated:'Yes":"Site activated:'Yes");
		}
		if(get_site_option('mo_lla_registration_status')){
			
		   $plugin_configuration = $plugin_configuration.$space.'registration_status : '.get_option('mo_lla_registration_status');     
		}
		
		if(get_option('mo_lla_enable_brute_force'))
		{
			$plugin_configuration = $plugin_configuration.$space.' BF : '.get_option('mo_lla_enable_brute_force');
		}
		if(get_option('mo_lla_enable_rename_login_url'))
		{
			$plugin_configuration = $plugin_configuration.$space.' RLU : '.get_option('mo_lla_enable_rename_login_url');
		}
		if(get_option('mo_lla_enable_fake_domain_blocking'))
		{
			$plugin_configuration = $plugin_configuration.$space.'FB : '.get_option('mo_lla_enable_fake_domain_blocking');
		}
		if(get_option('mo_lla_activate_recaptcha'))
		{
			$plugin_configuration = $plugin_configuration.$space.' GR : '.get_option('mo_lla_activate_recaptcha');
		}
		if(get_option('WAF'))
		{
			$plugin_configuration = $plugin_configuration.$space.' WAF : '.get_option('WAF');
		}
		if(get_option('Rate_limiting'))
		{
			$plugin_configuration = $plugin_configuration.$space.'6. RL : '.get_option('Rate_limiting');
		}
        
		
		$plugin_configuration = $plugin_configuration.$space."PHP_version : " . phpversion().$space."Wordpress_version : " . get_bloginfo('version');
		if(!$send_all_configuration)
			return $plugin_configuration;

		return $plugin_configuration;
}
	
}