<?php
class YcfShortCode {
	
	public function __construct() {
		add_shortCode('ycf_form', array($this, 'doshorctode'));
	}

	public function doshorctode($arg) {

		$contactFormId = $arg['id'];
		$formData = YcfContactFormData::getFormFieldsDataById($contactFormId);

		if(isset($formData)) {
			$this->includeData($contactFormId);
			$this->includeCss($contactFormId);
			$options = $formData['fields_data'];
			$options = json_decode($options, true);
		
			$formBuilderObj = new YcfBuilder();
			$formBuilderObj->setFormId((int)$arg['id']);
			$formBuilderObj->setFormElementsData($options);
			$form = $formBuilderObj->render();
			echo $form;
				
		}
	}

	private function includeData($formId) {

		$formData = YcfContactFormData::getSavedData($formId);
		$ycfFormData = array(
			'ycfWpAjaxUrl' => admin_url('admin-ajax.php'),
			'ycfValidateEmail' => 'Please enter a valid email.',
			'ycfRequiredField' => 'This field is required.',
			'sendToEmail' => $formData['contact-form-send-to-email'],
			'sendFromEmail' => $formData['contact-form-send-from-email'],
			'contactFormSendEmailSubject' => $formData['contact-form-send-email-subject'],
			'formId' => $formId,
			'ycfMessage' => $formData['ycf-message']
		);

		wp_enqueue_script('ycfFormJs');
		wp_localize_script('ycfFormJs', 'YcfFormData'.$formId, $ycfFormData);
		wp_enqueue_script('ycfValidate');

		echo "<script type=\"text/javascript\">
			jQuery(document).ready(function () {
				var formObj = new YcfForm();
				formObj.init($formId);
			});
		</script>";
	}

	private function includeCss($formId) {

		wp_register_style('theme1css', YCF_CSS_URL.'/form/theme1.css', array(), YCF_VERSION);
		wp_register_style('ycfFormStyle', YCF_CSS_URL.'/form/ycfFormStyle.css', array(), YCF_VERSION);
		wp_enqueue_style('theme1css');
		wp_enqueue_style('ycfFormStyle');
		$formObj = new YcfContactFormData();
		$formObj->setFormId($formId);
		$contactFormWidth = $formObj->getOptionValue('contact-form-width');
		$contactFormWidthMeasure = $formObj->getOptionValue('contact-form-width-measure');
		echo "<style>
			.ycf-form-$formId {
				width: $contactFormWidth$contactFormWidthMeasure;
			}
		</style>";
	}
}