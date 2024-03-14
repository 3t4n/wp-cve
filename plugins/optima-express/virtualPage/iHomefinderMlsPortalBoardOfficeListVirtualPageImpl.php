<?php

class iHomefinderMlsPortalBoardOfficeListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_OFFICE_LIST, "MLS Portal Office List");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_OFFICE_LIST, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_OFFICE_LIST, "mls-portal-office-list");
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_OFFICE_LIST, $default);
	}	
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "mls-portal-office-results")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}