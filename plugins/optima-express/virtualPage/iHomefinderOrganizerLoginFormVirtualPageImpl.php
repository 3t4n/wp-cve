<?php

class iHomefinderOrganizerLoginFormVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ORGANIZER_LOGIN, "Organizer Login");
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORGANIZER_LOGIN, "property-organizer-login");
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_ORGANIZER_LOGIN, $default);
	}	
	
	public function getContent() {
		$utility = iHomefinderUtility::getInstance();
		$subscriberId = $utility->getRequestVar("subscriberId");
		if(!empty($subscriberId)) {
			iHomefinderStateManager::getInstance()->setSubscriberId($subscriberId);
		}
		$rememberMe = $utility->getRequestVar("rememberMe");
		if($rememberMe === "1") {
			iHomefinderStateManager::getInstance()->setRememberMe(true);
		}
		$requestType = "property-organizer-login-form";
		if($utility->hasRequestVar("email") || $utility->hasRequestVar("username") || $utility->hasRequestVar("password")) {
			$requestType = "property-organizer-login-submit";
		}
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("requestType", $requestType)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}

	public function getBody() {
		if($this->displayRules->isKestrelAll()) {
			return iHomefinderKestrelPage::getPropertyOrganizerPage();
		} else {
			return parent::getBody();
		}
	}
	
}