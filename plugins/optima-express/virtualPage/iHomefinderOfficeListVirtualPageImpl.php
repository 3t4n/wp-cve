<?php

class iHomefinderOfficeListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST, "Office List");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST, "office-list");
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_OFFICE_LIST, $default);
	}	
	
	public function getContent() {
		if(!$this->displayRules->isKestrelAll()) {
			$this->remoteRequest
				->addParameters($_REQUEST)
				->addParameter("requestType", "office-list")
				->addParameter("includeSearchSummary", true)
			;
			$this->remoteRequest->setCacheExpiration(60*60);
			$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		}
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			return iHomefinderKestrelPage::getOfficesPage();
		} else {
			return parent::getBody();
		}
	}
	
}