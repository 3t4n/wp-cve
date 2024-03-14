<?php

class iHomefinderMlsPortalBoardOfficeSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_OFFICE_SEARCH, "MLS Portal Office Search");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_OFFICE_SEARCH, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_OFFICE_SEARCH, "mls-portal-office-search");
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_OFFICE_SEARCH, $default);
	}	
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "mls-portal-office-search")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}