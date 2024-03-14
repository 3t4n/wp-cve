<?php

class iHomefinderListingDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_DETAIL, "{listingAddress}");
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL, "homes-for-sale-details");
	}
	
	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL, null);
	}
	
	public function getMetaTags() {
		$default = "<meta property=\"og:image\" content=\"{listingPhotoUrl}\" />\n<meta property=\"og:image:width\" content=\"{listingPhotoWidth}\" />\n<meta property=\"og:image:height\" content=\"{listingPhotoHeight}\" />\n<meta name=\"description\" content=\"Photos and Property Details for {listingAddress}. Get complete property information, maps, street view, schools, walk score and more. Request additional information, schedule a showing, save to your property organizer.\" />\n<meta name=\"keywords\" content=\"{listingAddress}, {listingCity} Real Estate, {listingCity} Property for Sale\" />";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_DETAIL, $default);
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getListingAddress(),
			$variableUtility->getListingCity(),
			$variableUtility->getListingPostalCode(),
			$variableUtility->getListingPhotoUrl(),
			$variableUtility->getListingPhotoWidth(),
			$variableUtility->getListingPhotoHeight(),
			$variableUtility->getListingPrice(),
			$variableUtility->getListingSquareFeet(),
			$variableUtility->getListingBedrooms(),
			$variableUtility->getListingBathrooms(),
			$variableUtility->getListingNumber(),
			$variableUtility->getListingDescription()
		);
	}
	
	public function getContent() {
		$listingNumber = iHomefinderUtility::getInstance()->getQueryVar("listingNumber");
		$boardId = iHomefinderUtility::getInstance()->getQueryVar("boardId");
		if($this->displayRules->isKestrel()) {
			$this->remoteRequest
				->addParameter("requestType", "listing-variables")
				->addParameter("id", $listingNumber . "_" . $boardId)
			;
		} else {
			$this->remoteRequest
				->addParameters($_REQUEST)
				->addParameter("requestType", "listing-detail")
				->addParameter("ln", $listingNumber)
				->addParameter("bid", $boardId)
			;
		}
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
	/**
	 * same code in sold detail
	 */
	public function getBody() {
		if($this->displayRules->isKestrel()) {
			$listingNumber = iHomefinderUtility::getInstance()->getQueryVar("listingNumber");
			$boardId = iHomefinderUtility::getInstance()->getQueryVar("boardId");
			return iHomefinderKestrelPage::getListingPage($listingNumber, $boardId);
		} else {
			$body = $this->remoteResponse->getBody();
			if(!iHomefinderDisplayRules::getInstance()->isEurekaSearch()) {
				$previousSearchLink = $this->getPreviousSearchLink();
				if(strpos($body, "<!-- INSERT RETURN TO RESULTS LINK HERE -->") !== false) {
					$body = str_replace("<!-- INSERT RETURN TO RESULTS LINK HERE -->", $previousSearchLink, $body);
				} else {
					$body = $previousSearchLink . "<br /><br />" . $body;
				}
			}
			return $body;
		}
	}
	
}