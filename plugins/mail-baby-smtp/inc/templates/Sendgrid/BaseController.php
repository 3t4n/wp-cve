<?php
/**
 * WP SendGrid Mailer Plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace WPMailPlus;

class BaseController
{
	/**
	 * List of available service
	 * @var array
	 */
	public static $available_service = array('default' => 'Default', 'smtp' => 'SMTP', 'sendgrid' => 'Sendgrid');

	public function __construct()
	{
		if($_POST) {
			if (!isset($_POST['_mailplus_wpnonce']) || !wp_verify_nonce($_POST['_mailplus_wpnonce'], 'MailPlusSettings')) {
				print 'Error occurred while verifying nonce.';
				exit;
			}
			else {
				$this->processData($_POST);
			}
		}
	}

	public static function output($file, $data)
	{
		$allowed_html = array(
            'a'      => array(
                'class' => array(),
                'id' => array(),
                'href'  => array(),
                'title' => array(),
            ),
            'script'     => array(),
            'em'     => array(),
            'img' => array(),
            'td' => array(),
            'tr' => array()
        );
		extract($data);
		ob_start();
		include( WPMP_PLUGIN_DIR . 'includes/' . $file );
		$output = ob_get_contents();
		ob_end_clean();
		echo wp_kses($output, $allowed_html);
	}

	/**
	 * Add Log to table
	 * @param $data
	 */
	public static function addLog($data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "mailplus_logs";
		$wpdb->insert(
			$table_name,
			array(
				'sent_time' => current_time( 'mysql' ),
				'mail_from' => $data['email_from'],
				'mail_to' => $data['email_to'],
				'email_service' => $data['email_service'],
				'email_subject' => $data['email_subject'],
				'status' => $data['status'], // Success/Failed
				'message' => $data['message'],
			)
		);
	}

	/**
	 * Prepare from email to add log entries
	 * @param $name
	 * @param $email
	 * @return string
	 */
	public static function prepare_from_email($name, $email)
	{
		if(!empty($name) && !empty($email))
			return $name . ' - ' . $email;
		else if(empty($name) && !empty($email))
			return $email;
		else if(!empty($name) && empty($email))
			return $name;

	}

	/**
	 * Store data from MailPlus Settings Form
	 * @param $data
	 */
	private function processData($data)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";die;
		$data_to_store = array();
		$email_service = $data['email_service'];
		if(!empty($email_service) && array_key_exists($email_service, BaseController::$available_service))
		{
			$field_to_store = array('smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_authentication', 'sendgrid_api_key', 'smtp_encryption');
			// Store From information
			$header_info = array('from_name' => $data['from_name'], 'from_email' => $data['from_email']);
			update_option('_wp_mailplus_from_info', $header_info);
			// Store Mail SendGrid Plus enabled service
			update_option('_wp_mailplus_enabled_service', $email_service);
			if($email_service != 'default') {
				foreach ($data as $field_key => $field_value) {
					if (in_array($field_key, $field_to_store)) {
						$data_to_store[$field_key] = $field_value;
					}
				}

				// Store Settings form data
				update_option('_wp_mailplus_service_info', $data_to_store);
			}
		}
	}
}
