<?php

class iHomefinderOrganizerViewSavedSearchVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Saved Search";
	}
	
	public function getPermalink() {
		return "property-organizer-view-saved-search";
	}
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		$searchProfileId = iHomefinderUtility::getInstance()->getQueryVar("searchProfileId");
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "property-organizer-view-saved-search")
			->addParameter("searchProfileId", $searchProfileId)
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