<?php
class YcfSendEmail{

	private $formId;
	private $formData;
	private $formMessage;

	public function __construct() {

		$this->init();
	}

	public function init() {

		add_action('wp_ajax_contactForm', array($this, 'ycfContactFormData'));
		add_action('wp_ajax_nopriv_contactForm', array($this, 'ycfContactFormData'));
	}

	public function __call($name, $args) {

		$methodPrefix = substr($name, 0, 3);
		$methodProperty = lcfirst(substr($name,3));

		if ($methodPrefix=='get') {
			return $this->$methodProperty;
		}
		else if ($methodPrefix=='set') {
			$this->$methodProperty = $args[0];
		}
	}

	public function ycfContactFormData() {
	
		$formResponse = array();

		if(!isset($_POST)) {
			$formResponse['status'] = 400;
			$formResponse['message'] = 'Invalid Form Data';

			json_encode($formResponse);
			die();
		}
		$formData = sanitize_text_field($_POST['formData']);
		parse_str($formData, $formData);
		$settings = sanitize_text_field($_POST['formSettings']);
		$formId = sanitize_text_field($settings['formId']);
		$ycfMessage = sanitize_text_field($settings['ycfMessage']);

		$this->setFormId($formId);
		$this->setFormData($formData);
		$this->setFormMessage($ycfMessage);
		$this->changeMessageKeys();

		$sendFromEmail = $settings['sendFromEmail'];
		$sendToEmail = $settings['sendToEmail'];
		$contactFormSendEmailSubject = $settings['contactFormSendEmailSubject'];

		$email = sanitize_email($formData['ycf-email']);
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
		if (!preg_match($regex, $email)) {
			$formResponse['status'] = 400;
			$formResponse['message'] = 'Invalid email address';

			json_encode($formResponse);
		}

		//set UTF-8
		$headers  = 'MIME-Version: 1.0'."\r\n";
		//$headers .= 'From: '.$sendFromEmail.''."\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8'."\r\n";
		$message = $this->getFormMessage();

		$sendStatus = mail($sendToEmail, $contactFormSendEmailSubject, $message, $headers);
		var_dump($sendStatus);
		die();
	}

	public function changeMessageKeys() {

		$formId = $this->getFormId();
		$message = $this->getFormMessage();
		$formDataString = '';
		$formData = $this->getFormData();
		$nameLabelData = YcfContactFormData::getFormListNameAndLabelsById($formId);
		$patternFormData = '/\[form_data]/';

		if(!empty($nameLabelData)) {
			$nameLabelData = json_decode($nameLabelData, true);
			if(is_array($nameLabelData)) {
				foreach($nameLabelData as $label => $name) {
					if(isset($formData[$name])) {
						$name = $formData[$name];
					}
					else {
						$name = '';
					}
					$formDataString .= "<b>$label</b>: ".$name.'<br>';
				}
			}
		}
		$message = preg_replace($patternFormData, $formDataString, $message);
		$this->setFormMessage($message);
	}
}

$sendEmailObj = new YcfSendEmail();