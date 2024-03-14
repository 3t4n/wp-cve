<?php

class iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Saved Search List";
	}
	
	public function getPermalink() {
		return "property-organizer-delete-saved-search-submit";
	}

	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "property-organizer-delete-saved-search-submit")
		;
		$searchProfileId = iHomefinderUtility::getInstance()->getQueryVar("searchProfileId");
		if(empty($searchProfileId)) {
			$searchProfileId = iHomefinderUtility::getInstance()->getRequestVar("searchProfileId");
		}
		$this->remoteRequest->addParameter("searchProfileId", $searchProfileId);
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