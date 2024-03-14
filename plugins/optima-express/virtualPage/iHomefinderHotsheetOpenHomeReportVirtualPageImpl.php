<?php

class iHomefinderHotsheetOpenHomeReportVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_HOT_SHEET_OPEN_HOME_REPORT, "{savedSearchName}: Open Home Report");
	}
	
	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_HOT_SHEET_OPEN_HOME_REPORT, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOT_SHEET_OPEN_HOME_REPORT, "open-home-report");
	}
	
	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"{savedSearchDescription}\" />";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_HOT_SHEET_OPEN_HOME_REPORT, $default);
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getSavedSearchName(),
			$variableUtility->getSavedSearchDescription()
		);
	}
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "open-home-hotsheet-report")
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
			return iHomefinderKestrelPage::getMarketOpenHomeReportPage($id);
		} else {
			return parent::getBody();
		}
	}
	
}