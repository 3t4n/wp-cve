<?php

class iHomefinderOfficeDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL, "{officeName}");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL, "office-detail");
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getOfficeName()
		);
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_OFFICE_DETAIL, $default);
	}	
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "office-detail")
		;
		$officeId = iHomefinderUtility::getInstance()->getQueryVar("officeId");
		if(is_numeric($officeId)) {
			$this->remoteRequest->addParameter("officeID", $officeId);
		}
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			$id = iHomefinderUtility::getInstance()->getQueryVar("officeId");
			return iHomefinderKestrelPage::getOfficePage($id);
		} else {
			return parent::getBody();
		}
	}
	
}