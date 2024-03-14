<?php

class iHomefinderOrganizerActivateSubscriberVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Subscriber Activation";
	}
	
	public function getPermalink() {
		return "property-organizer-activate";
	}	
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "property-organizer-activate-subscriber")
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