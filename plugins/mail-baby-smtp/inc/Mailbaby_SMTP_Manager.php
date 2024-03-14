<?php



class MailBaby{



	public function __construct()

	{

		$this->defineMailbabyConstants();

		// echo WPMP_PLUGIN_DIR .'templates/';die;

		// require_once WPMP_PLUGIN_DIR . 'vendor/autoload.php';

		$this->initMailbabyHooks();



	}



	 /**

	 * Define constants which are needed for the plugin

	 */

	public function defineMailbabyConstants()

	{

		define('WPMP_NAME', 'Mail Baby');

		define('WPMP_VERSION', '1.3');

		define('WPMP_PLUGIN_URL', plugin_dir_url(__FILE__));

		define('WPMP_PLUGIN_DIR', plugin_dir_path(__FILE__));

	}



	public function initMailbabyHooks()

	{

		// add_action( 'phpmailer_init', array($this, 'mailer_init') );

		//add_action( 'wp_mail_failed', array($this, 'mailer_failed'), 10, 1 );

		//add_action( 'wp_ajax_wp_mailplus_clear_logs', array($this, 'wp_mailplus_clear_logs'));

		add_filter('wp_mail_from', array($this, 'wp_mail_from_mail'));

		add_filter('wp_mail_from_name', array($this, 'wp_mail_from_name'));

	}



	



	/**

	 * Filter From Name

	 * @param string $from_name

	 * @return string

 	*/

	public function wp_mail_from_name($from_name)

	{

		$more_info = get_option('MAIL_BABY_SMTP_options');

		if ( isset($more_info['from_name'] ) && !empty( $more_info['from_name'] ) ){
			
			return $more_info['from_name'];

		} else {

			return get_bloginfo('name');

		}

		return $from_name;

	}



	/**

	 * Filter From Email

	 * @param string $from_email

	 * @return string

	 */

	public function wp_mail_from_mail($from_email)

	{

		$more_info = get_option('MAIL_BABY_SMTP_options');

		if(isset($more_info['from_email']) && !empty($more_info['from_email'])){

			return $more_info['from_email'];

		} else {

			return get_option('admin_email');
			
		}

		return $from_email;

	}



}





new MailBaby();



$enabled_email_service = get_option('MAIL_BABY_SMTP_options');



require_once WPMP_PLUGIN_DIR . 'templates/Mailbaby/MailbabyService.php';



if($enabled_email_service['mailer'] == 'mailbaby') {

	

	if(!function_exists('wp_mail') && !in_array($enabled_email_service, array('mailer', 'php')))

	{

		function wp_mail($to, $subject, $message, $headers = '', $attachments = array())

		{



			$enabled_email_service = get_option('MAIL_BABY_SMTP_options');



			if($enabled_email_service['mailer'] == 'mailbaby') {

				$emailService = new \MailBaby\MailbabyService();

			}	



			return $emailService->MAILBABY_send_mail($to, $subject, $message, $headers, $attachments);

		}

	}

}





?>





