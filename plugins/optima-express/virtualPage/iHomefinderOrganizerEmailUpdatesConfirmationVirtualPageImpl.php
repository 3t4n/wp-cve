<?php

class iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Email Updates Confirmation";
	}	
	
	public function getPermalink() {
		return "email-updates-confirmation";
	}

	public function getContent() {
		$message = iHomefinderUtility::getInstance()->getQueryVar("message");
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "property-organizer-email-updates-confirmation")
			->addParameter("message", $message)
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
