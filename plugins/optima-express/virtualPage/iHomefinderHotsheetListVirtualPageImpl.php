<?php

class iHomefinderHotsheetListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_HOT_SHEET_LIST, "Listing Reports");
	}
			
	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_HOT_SHEET_LISTING_REPORT, null);
	}
	
	public function getPermalink() {
		return "homes-for-sale-toppicks";
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_HOT_SHEET_LIST, $default);
	}		
			
	public function getContent() {
		$this->remoteRequest
			->addParameter("requestType", "hotsheet-list")
		;
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			return iHomefinderKestrelPage::getMarketsPage();
		} else {
			return parent::getBody();
		}
	}
	
}