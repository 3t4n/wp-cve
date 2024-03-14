<?php

class iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Reset Password";
	}
	
	public function getPermalink() {
		return "property-organizer-send-login";
	}
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "property-organizer-password-email")
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