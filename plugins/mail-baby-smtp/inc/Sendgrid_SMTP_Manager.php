<?php 


class MSP_WPMailPlus
{
	public function __construct()
	{
		$this->defineConstants();
		// echo WPMP_PLUGIN_DIR .'templates/';die;
		require_once WPMP_PLUGIN_DIR . 'vendor/autoload.php';
		$this->initHooks();

	}


    /**
	 * Define constants which are needed for the plugin
	 */
	public function defineConstants()
	{
		define('WPMP_NAME', 'SendGrid Mailer');
		define('WPMP_VERSION', '1.3');
		define('WPMP_PLUGIN_URL', plugin_dir_url(__FILE__));
		define('WPMP_PLUGIN_DIR', plugin_dir_path(__FILE__));
	}

    /**
	 * Initiate Hooks
	 */
	public function initHooks()
	{

		add_action( 'phpmailer_init', array($this, 'wpmp_mailer_init') );
		add_action( 'wp_mail_failed', array($this, 'wpmp_mailer_failed'), 10, 1 );
		add_action( 'wp_ajax_wp_mailplus_clear_logs', array($this, 'wp_mailplus_clear_logs'));

		add_filter('wp_mail_from', array($this, 'wpmp_mail_from_mail'));
		add_filter('wp_mail_from_name', array($this, 'wpmp_mail_from_name'));
	}

    /**
	 * Update PHPMailer Instance
	 * @param $phpmailer
	 */
	public function wpmp_mailer_init($phpmailer)
	{
		// $enabled_service = get_option('MAIL_BABY_SMTP_options');
		$service_info = get_option('MAIL_BABY_SMTP_options');

		if($service_info['mailer'] == 'php')
		{
			$phpmailer->isSMTP();
			$phpmailer->Host = $service_info['smtp_host'];
			$phpmailer->SMTPAuth = false;
			if($service_info['smtp_authentication'])
				$phpmailer->SMTPAuth = true;

			$phpmailer->Port = $service_info['smtp_port'];
			$phpmailer->Username = $service_info['smtp_username'];
			$phpmailer->Password = $service_info['smtp_password'];
			$phpmailer->SMTPSecure = $service_info['smtp_encryption'];
		}
	}

	/**
	 * wp_mail_failed callback
	 * @param $wp_error
	 */
	public function wpmp_mailer_failed($wp_error)
	{
		$from_info = get_option('_wp_mailplus_from_info');
		$email_from = \WPMailPlus\BaseController::prepare_from_email($from_info['from_name'], $from_info['from_email']);
		$email_service = get_option('_wp_mailplus_enabled_service');
		if($email_service == 'smtp')
			$email_service = 'SMTP';
		else
			$email_service = 'Default';

		$to = null;
		foreach($wp_error->error_data[2]['to'] as $to_key => $mail_to) {
			$to .= $mail_to . ',';
		}

		$to = substr($to, 0, -1);

		$log_data = array('email_from' => $email_from,
			'email_to' => $to,
			'email_service' => $email_service,
			'email_subject' => $wp_error->error_data[2]['subject'],
			'status' => 'Failed',
			'message' => $wp_error->errors[2][0]
		);

		\WPMailPlus\BaseController::addLog($log_data);
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

new MSP_WPMailPlus();
// echo WPMP_PLUGIN_DIR;
require_once WPMP_PLUGIN_DIR . 'templates/Sendgrid/Integrations/SendGridService.php';

$enabled_email_service = get_option('MAIL_BABY_SMTP_options');

if($enabled_email_service == 'sendgrid'){
	// Replacing wp_mail function if enabled email service is other than default and smtp
	if(!function_exists('wpmp_mail') && !in_array($enabled_email_service, array('mailer', 'php')))
	{
		function wpmp_mail($to, $subject, $message, $headers = '', $attachments = array())
		{
			$enabled_email_service = get_option('MAIL_BABY_SMTP_options');

			if($enabled_email_service['mailer'] == 'sendgrid') {
				// $emailService = new \WPMailPlus\Integrations\SendGridService();
				$emailService = new \WPMailPlus\Integrations\SendGridService();
			}

			
			$emailService->send_mail($to, $subject, $message, $headers, $attachments);

		}
	}
}


?>