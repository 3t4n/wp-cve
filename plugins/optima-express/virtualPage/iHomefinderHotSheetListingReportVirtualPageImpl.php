<?php

class iHomefinderHotSheetListingReportVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_HOT_SHEET_LISTING_REPORT, "{savedSearchName}: Listing Report");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_HOT_SHEET_LISTING_REPORT, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOT_SHEET_LISTING_REPORT, "listing-report");
	}
	
	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"{savedSearchDescription}\" />";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_HOT_SHEET_LISTING_REPORT, $default);
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getSavedSearchName(),
			$variableUtility->getSavedSearchDescription()
		);
	}
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "hotsheet-results")
			->addParameter("includeSearchSummary", true)
		;
		$hotSheetId = iHomefinderUtility::getInstance()->getQueryVar("savedSearchId");
		if(!empty($hotSheetId)) {
			$this->remoteRequest->addParameter("hotSheetId", $hotSheetId);
		}
		$title = $this->getTitle();
		if(empty($title)) {
			$this->remoteRequest->addParameter("includeDisplayName", false);
		}
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			$id = iHomefinderUtility::getInstance()->getQueryVar("savedSearchId");
			return iHomefinderKestrelPage::getMarketListingReportPage($id);
		} else {
			return parent::getBody();
		}
	}
	
}