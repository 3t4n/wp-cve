<?php

class iHomefinderOrganizerEditSavedSearchVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Saved Search List";
	}
	
	public function getPermalink() {
		return "property-organizer-edit-saved-search-submit";
	}
	
	public function getContent() {
		//searchProfileName is used only in fixed width
		$searchProfileName = iHomefinderUtility::getInstance()->getQueryVar("searchProfileName");
		if(!empty($searchProfileName)) {
			$this->remoteRequest->addParameter("name", $searchProfileName);
		}
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "property-organizer-edit-saved-search-submit")
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			return iHomefinderKestrelPage::getEmailAlertsPage();
		} else {
			return parent::getBody();
		}
	}
	
}