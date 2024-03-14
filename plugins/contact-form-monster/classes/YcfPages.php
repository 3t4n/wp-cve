<?php
Class YcfPages {

	public $functionsObj;
	public $ycfFormData;
	public $formBuilderObj;

	public function __construct() {
		
	}

	public function mainPage() {

		require_once(YCF_VIEWS."ycfMainView.php");
	}

	public function addNewPage() {

		$formId = (int)sanitize_text_field($_GET['formId']);
		$formBuilderObj = $this->formBuilderObj;

		if(!isset($formId)) {
			$formOptionsData = $formBuilderObj->defaultFormObjectData();
		}
		else {
			$formOptionsData = YcfContactFormData::getFormListById($formId);
		}

		$formBuilderObj->setFormElementsData($formOptionsData);
		$formDataObj = $this->ycfFormData;
		$formDataObj->setFormId($formId);
		@$formTitle = $formDataObj->getOptionValue('ycf-form-title');
		@$contactFormSendToEmail = $formDataObj->getOptionValue('contact-form-send-to-email');
		@$contactFormSendFromEmail = $formDataObj->getOptionValue('contact-form-send-from-email');
		@$contactFormSendEmailSubject = $formDataObj->getOptionValue('contact-form-send-email-subject');
		@$ycfMessage = $formDataObj->getOptionValue('ycf-message');
		@$contactFormWidth = $formDataObj->getOptionValue('contact-form-width');
		@$contactFormWidthMeasure = $formDataObj->getOptionValue('contact-form-width-measure');

		require_once(YCF_VIEWS."expmAddNew.php");
	}

}