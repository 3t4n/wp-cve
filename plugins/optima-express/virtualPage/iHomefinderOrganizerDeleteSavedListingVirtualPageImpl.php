<?php

class iHomefinderOrganizerDeleteSavedListingVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Saved Listing List";
	}

	public function getPermalink() {
		return "property-organizer-delete-saved-listing-submit";
	}	
	
	public function getContent() {
		$savedListingId = iHomefinderUtility::getInstance()->getQueryVar("savedListingId");
		$this->remoteRequest
			->addParameter("requestType", "property-organizer-delete-saved-listing-submit")
			->addParameter("savedListingId", $savedListingId)
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