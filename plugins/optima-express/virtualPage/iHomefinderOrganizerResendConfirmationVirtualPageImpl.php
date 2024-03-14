<?php

class iHomefinderOrganizerResendConfirmationVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Resend Confirmation Email";
	}
	
	public function getPermalink() {
		return "property-organizer-resend-confirmation-email";
	}
	
	public function getContent() {
		$email = iHomefinderUtility::getInstance()->getRequestVar("email");
		$afterLoginUrl = iHomefinderUtility::getInstance()->getRequestVar("afterLoginUrl");
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "property-organizer-resend-confirm-email")
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			return iHomefinderKestrelPage::getPropertyOrganizerPage();
		} else {
			return parent::getBody();
		}
	}
	
}