<?php

class iHomefinderAgentDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL, "{agentName}, {agentDesignation}");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL, "agent-detail");
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getAgentName(),
			$variableUtility->getAgentDesignation()
		);
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_AGENT_DETAIL, $default);
	}	
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		$this->remoteRequest
			->addParameter("requestType", "agent-detail")
			->addParameter("includeSearchSummary", false)
		;
		if(!$this->remoteRequest->hasParameter("agentID")) {
			$agentId = iHomefinderUtility::getInstance()->getQueryVar("agentId");
			$this->remoteRequest->addParameter("agentID", $agentId);
		}
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			$id = iHomefinderUtility::getInstance()->getQueryVar("agentId");
			return iHomefinderKestrelPage::getAgentPage($id);
		} else {
			return parent::getBody();
		}
	}
	
}