<?php

class iHomefinderOrganizerViewSavedListingListVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Saved Listing List";
	}
	
	public function getPermalink() {
		return "property-organizer-saved-listings";
	}
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "property-organizer-view-saved-listing-list")
			->addParameter("includeSearchSummary", true)
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