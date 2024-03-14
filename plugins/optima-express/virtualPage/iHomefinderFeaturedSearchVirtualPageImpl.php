<?php

class iHomefinderFeaturedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage{
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_FEATURED, "Featured Properties");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED, "homes-for-sale-featured");
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_FEATURED, $default);
	}	
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "featured-search")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			return iHomefinderKestrelPage::getFeaturedListingsPage();
		} else {
			return parent::getBody();
		}
	}
	
}