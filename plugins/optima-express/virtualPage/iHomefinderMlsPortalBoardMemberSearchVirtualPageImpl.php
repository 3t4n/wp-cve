<?php

class iHomefinderMlsPortalBoardMemberSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_MEMBER_SEARCH, "MLS Portal Agent Search");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_MEMBER_SEARCH, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_MEMBER_SEARCH, "mls-portal-agent-search");
	}
			
	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_MEMBER_SEARCH, $default);
	}	

	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "mls-portal-agent-search")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}