<?php

class iHomefinderMlsPortalBoardOfficeDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		$default = null;
		if(is_object($this->remoteResponse) && $this->remoteResponse->hasTitle()) {
			$default = $this->remoteResponse->getTitle();
		}
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_MLS_PORTAL_BOARD_OFFICE_DETAIL, $default);
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_MLS_PORTAL_BOARD_OFFICE_DETAIL, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MLS_PORTAL_BOARD_OFFICE_DETAIL, "mls-portal-office-detail");
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getOfficeName()
		);
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_MLS_PORTAL_BOARD_OFFICE_DETAIL, $default);
	}	
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", "mls-portal-office-detail")
		;
		$boardOfficeId = iHomefinderUtility::getInstance()->getQueryVar("boardOfficeId");
		if(is_numeric($boardOfficeId)) {
			$this->remoteRequest->addParameter("boardOfficeId", $boardOfficeId);
		}
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}