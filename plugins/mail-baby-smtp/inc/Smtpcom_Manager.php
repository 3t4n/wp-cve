<?php

class SMTPCom{

	public function __construct()
	{
		$this->defineConstants();
		// echo WPMP_PLUGIN_DIR .'templates/';die;
		// require_once WPMP_PLUGIN_DIR . 'vendor/autoload.php';
		$this->initHooks();

	}

	 /**
	 * Define constants which are needed for the plugin
	 */
	public function defineConstants()
	{
		define('WPMP_NAME', 'Smtpcom Mailer');
		define('WPMP_VERSION', '1.3');
		define('WPMP_PLUGIN_URL', plugin_dir_url(__FILE__));
		define('WPMP_PLUGIN_DIR', plugin_dir_path(__FILE__));
	}

	public function initHooks()
	{
		// add_action( 'phpmailer_init', array($this, 'mailer_init') );
		//add_action( 'wp_mail_failed', array($this, 'mailer_failed'), 10, 1 );
		//add_action( 'wp_ajax_wp_mailplus_clear_logs', array($this, 'wp_mailplus_clear_logs'));
		add_filter('wp_mail_from', array($this, 'wpmp_mail_from_mail'));
		add_filter('wp_mail_from_name', array($this, 'wpmp_mail_from_name'));
	}

	

	/**
	 * Filter From Name
	 * @param string $from_name
	 * @return string
 	*/
	public function wpmp_mail_from_name($from_name)
	{
		$more_info = get_option('MAIL_BABY_SMTP_options');
		if(isset($more_info['from_name']) && !empty($more_info['from_name']))
			return $more_info['from_name'];
		return $from_name;
	}

	/**
	 * Filter From Email
	 * @param string $from_email
	 * @return string
	 */
	public function wpmp_mail_from_mail($from_email)
	{
		$more_info = get_option('MAIL_BABY_SMTP_options');
		if(isset($more_info['from_email']) && !empty($more_info['from_email']))
			return $more_info['from_email'];
		return $from_email;
	}

}


new SMTPCom();

$enabled_email_service = get_option('MAIL_BABY_SMTP_options');
// echo "<pre>";
// print_r($enabled_email_service);
// echo "</pre>";
require_once WPMP_PLUGIN_DIR . 'templates/SMTPCom/SmtpcomService.php';

if($enabled_email_service['mailer'] == 'smtp') {
	if(!function_exists('wpmp_mail') && !in_array($enabled_email_service, array('mailer', 'php')))
	{
		function wpmp_mail($to, $subject, $message, $headers = '', $attachments = array())
		{

			$enabled_email_service = get_option('MAIL_BABY_SMTP_options');

			if($enabled_email_service['mailer'] == 'smtp') {
				$emailService = new \SMTPCom\SmtpcomService();
				
			}

			$emailService->SMTP_send_mail($to, $subject, $message, $headers, $attachments);
		}
	}
}


?>


