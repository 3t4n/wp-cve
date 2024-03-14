<?php

class iHomefinderAdvancedSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ADVANCED_SEARCH, "Advanced Property Search");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_ADVANCED_SEARCH, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADVANCED_SEARCH, "homes-for-sale-search-advanced");
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_ADVANCED_SEARCH, $default);
	}	
	
	public function getContent() {
		if(!$this->displayRules->isKestrelAll()) {
			$boardId = iHomefinderUtility::getInstance()->getQueryVar("boardId");
			$this->remoteRequest
				->addParameters($_REQUEST)
				->addParameter("requestType", "listing-advanced-search-form")
				->addParameter("includeAreaSelectorAreas", false)
			;
			if(is_numeric($boardId)) {
				$this->remoteRequest->addParameter("boardId", $boardId);
			}
			$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		}
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			return iHomefinderKestrelPage::getListingsPage();
		} else {
			return parent::getBody();
		}
	}
	
}