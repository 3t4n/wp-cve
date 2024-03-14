<?php

class iHomefinderSupplementalListingVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING, "Supplemental Listings");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING, "supplemental-listing");
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_SUPPLEMENTAL_LISTING, $default);
	}	
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "supplemental-listing")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			return iHomefinderKestrelPage::getSupplementalListingsPage();
		} else {
			return parent::getBody();
		}
	}
	
}