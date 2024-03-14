<?php
class MailWtbp extends ModuleWtbp {
	public function init() {
		parent::init();
	}
	public function send( $to, $subject, $message, $fromName = '', $fromEmail = '', $replyToName = '', $replyToEmail = '', $additionalHeaders = array(), $additionalParameters = array() ) {
		$type = 'wp_mail';
		$res = false;
		switch ($type) {
			case 'wp_mail': 
			default:
				$res = $this->sendWpMail( $to, $subject, $message, $fromName, $fromEmail, $replyToName, $replyToEmail, $additionalHeaders, $additionalParameters );
				if (!$res) {
					// Sometimes it return false, but email was sent, and in such cases
					// - in errors array there are only one - empty string - value.
					// Let's count this for now as Success sending
					$mailErrors = array_filter( $this->getMailErrors() );
					if (empty($mailErrors)) {
						$res = true;
					}
				}
				break;
		}
		return $res;
	}
	public function sendWpMail( $to, $subject, $message, $fromName = '', $fromEmail = '', $replyToName = '', $replyToEmail = '', $additionalHeaders = array(), $additionalParameters = array() ) {
		$headersArr = array();
		$eol = "\r\n";
		if (!empty($fromName) && !empty($fromEmail)) {
			$headersArr[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
		}
		if (!empty($replyToName) && !empty($replyToEmail)) {
			$headersArr[] = 'Reply-To: ' . $replyToName . ' <' . $replyToEmail . '>';
		}
		if (!function_exists('wp_mail')) {
			FrameWtbp::_()->loadPlugins();
		}
		if (!FrameWtbp::_()->getModule('options')->get('disable_email_html_type')) {
			add_filter('wp_mail_content_type', array($this, 'mailContentType'));
		}

		$attach = null;
		if (isset($additionalParameters['attach']) && !empty($additionalParameters['attach'])) {
			$attach = $additionalParameters['attach'];
		}
		if (empty($attach)) {
			$result = wp_mail($to, $subject, $message, implode($eol, $headersArr));
		} else {
			$result = wp_mail($to, $subject, $message, implode($eol, $headersArr), $attach);
		}
		if (!FrameWtbp::_()->getModule('options')->get('disable_email_html_type')) {
			remove_filter('wp_mail_content_type', array($this, 'mailContentType'));
		}

		return $result;
	}
	public function getMailErrors() {
		global $ts_mail_errors;
		$type = FrameWtbp::_()->getModule('options')->get('mail_send_engine');
		switch ($type) {
			case 'smtp': 
			case 'sendmail':
				return $this->getErrors();
				break;
			case 'wp_mail': 
			default:
				// Clear prev. send errors at first
				$ts_mail_errors = array();

				// Let's try to get errors about mail sending from WP
				if (!isset($ts_mail_errors)) {
					$ts_mail_errors = array();
				}
				if (empty($ts_mail_errors)) {
					$ts_mail_errors[] = esc_html__('Cannot send email - problem with send server', 'woo-product-tables');
				}
				return $ts_mail_errors;
				break;
		}
	}
	public function mailContentType( $contentType ) {
		$contentType = 'text/html';
		return $contentType;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	public function addOptions( $opts ) {
		$opts[ $this->getCode() ] = array(
			'label' => esc_html__('Mail', 'woo-product-tables'),
			'opts' => array(
				'mail_function_work' => array('label' => esc_html__('Mail function tested and work', 'woo-product-tables'), 'desc' => ''),
				'notify_email' => array('label' => esc_html__('Notify Email', 'woo-product-tables'), 'desc' => esc_html__('Email address used for all email notifications from plugin', 'woo-product-tables'), 'html' => 'text', 'def' => get_option('admin_email')),
			),
		);
		return $opts;
	}
}
