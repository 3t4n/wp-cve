<?php

class iHomefinderShortcodeDispatcher {
	
	const HOT_SHEETS_SHORTCODE = "optima_express_toppicks";
	const HOT_SHEET_OPEN_HOME_REPORT = "optima_express_open_home_report";
	const HOT_SHEET_MARKET_REPORT = "optima_express_market_report";
	const FEATURED_SHORTCODE = "optima_express_featured";
	const SEARCH_RESULTS_SHORTCODE = "optima_express_search_results";
	const QUICK_SEARCH_SHORTCODE = "optima_express_quick_search";
	const SEARCH_BY_ADDRESS_SHORTCODE = "optima_express_address_search";
	const SEARCH_BY_LISTING_ID_SHORTCODE = "optima_express_listing_search";
	const MAP_SEARCH_SHORTCODE = "optima_express_map_search";
	const EUREKA_SEARCH_SHORTCODE = "optima_express_search";
	const AGENT_LISTINGS_SHORTCODE = "optima_express_agent_listings";
	const OFFICE_LISTINGS_SHORTCODE = "optima_express_office_listings";
	const GALLERY_SLIDER_SHORTCODE = "optima_express_gallery_slider";
	const BASIC_SEARCH_SHORTCODE = "optima_express_basic_search";
	const ADVANCED_SEARCH_SHORTCODE = "optima_express_advanced_search";
	const ORGANIZER_LOGIN_SHORTCODE = "optima_express_organizer_login";
	const ORGANIZER_LOGIN_WIGET_SHORTCODE = "optima_express_organizer_login_widget";
	const AGENT_DETAIL_SHORTCODE = "optima_express_agent_detail";
	const AGENT_LIST_SHORTCODE = "optima_express_agent_list";
	const OFFICE_LIST_SHORTCODE = "optima_express_office_list";
	const VALUATION_FORM_SHORTCODE = "optima_express_valuation_form";
	const MORTGAGE_CALCULATOR_SHORTCODE = "optima_express_mortgage_calculator";
	const VALUATION_WIDGET_SHORTCODE = "optima_express_valuation_widget";
	const CONTACT_FORM_SHORTCODE = "optima_express_contact_form";
	const EMAIL_ALERTS_SHORTCODE = "optima_express_email_alerts";
	const REGISTRATION_FORM_SHORTCODE = "optima_express_registration_form";
	const HOT_SHEET_REPORT_SIGNUP_SHORTCODE = "optima_express_campaign_signup";
	
	private static $instance;
	private $virtualPageFactory;
	private $enqueueResource;
	private $displayRules;
	
	private function __construct() {
		$this->virtualPageFactory = iHomefinderVirtualPageFactory::getInstance();
		$this->enqueueResource = iHomefinderEnqueueResource::getInstance();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function initialize() {
		add_shortcode(self::HOT_SHEETS_SHORTCODE, array($this, "getListingReport"));
		add_shortcode(self::HOT_SHEET_OPEN_HOME_REPORT, array($this, "getOpenHomeReport"));
		add_shortcode(self::HOT_SHEET_MARKET_REPORT, array($this, "getMarketReport"));
		add_shortcode(self::FEATURED_SHORTCODE, array($this, "getFeaturedListings"));
		add_shortcode(self::SEARCH_RESULTS_SHORTCODE, array($this, "getSearchResults"));
		add_shortcode(self::QUICK_SEARCH_SHORTCODE, array($this, "getQuickSearch"));
		if($this->displayRules->isSearchByAddressEnabled()) {
			add_shortcode(self::SEARCH_BY_ADDRESS_SHORTCODE, array($this, "getSearchByAddress"));
		} else {
			add_shortcode(self::SEARCH_BY_ADDRESS_SHORTCODE, array($this, "getEmpty"));
		}
		if($this->displayRules->isSearchByListingIdEnabled()) {
			add_shortcode(self::SEARCH_BY_LISTING_ID_SHORTCODE, array($this, "getSearchByListingId"));
		} else {
			add_shortcode(self::SEARCH_BY_LISTING_ID_SHORTCODE, array($this, "getEmpty"));
		}
		add_shortcode(self::MAP_SEARCH_SHORTCODE, array($this, "getMapSearch"));
		add_shortcode(self::EUREKA_SEARCH_SHORTCODE, array($this, "getEurekaSearch"));
		add_shortcode(self::AGENT_LISTINGS_SHORTCODE, array($this, "getAgentListings"));
		add_shortcode(self::OFFICE_LISTINGS_SHORTCODE, array($this, "getOfficeListings"));
		add_shortcode(self::GALLERY_SLIDER_SHORTCODE, array($this, "getGallerySlider"));
		add_shortcode(self::BASIC_SEARCH_SHORTCODE, array($this, "getBasicSearch"));
		add_shortcode(self::ADVANCED_SEARCH_SHORTCODE, array($this, "getAdvancedSearch"));
		add_shortcode(self::ORGANIZER_LOGIN_SHORTCODE, array($this, "getOrganizerLogin"));
		add_shortcode(self::ORGANIZER_LOGIN_WIGET_SHORTCODE, array($this, "getOrganizerLoginWidget"));
		add_shortcode(self::AGENT_DETAIL_SHORTCODE, array($this, "getAgentDetail"));
		add_shortcode(self::AGENT_LIST_SHORTCODE, array($this, "getAgentList"));
		add_shortcode(self::OFFICE_LIST_SHORTCODE, array($this, "getOfficeList"));
		add_shortcode(self::VALUATION_FORM_SHORTCODE, array($this, "getValuationForm"));
		add_shortcode(self::VALUATION_WIDGET_SHORTCODE, array($this, "getValuationWidget"));
		add_shortcode(self::CONTACT_FORM_SHORTCODE, array($this, "getContactForm"));
		add_shortcode(self::EMAIL_ALERTS_SHORTCODE, array($this, "getEmailAlerts"));
		add_shortcode(self::REGISTRATION_FORM_SHORTCODE, array($this, "getRegistrationForm"));
		add_shortcode(self::HOT_SHEET_REPORT_SIGNUP_SHORTCODE, array($this, "getHotSheetReportSignup"));
		add_shortcode(self::MORTGAGE_CALCULATOR_SHORTCODE, array($this, "getMortgageCalculator"));
	}
	
	/**
	 * @deprecated use constant
	 */
	public function getToppicksShortcode() {
		return self::HOT_SHEETS_SHORTCODE;
	}
	
	/**
	 * @deprecated use constant
	 */
	public function getFeaturedShortcode() {
		return self::FEATURED_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getSearchResultsShortcode() {
		return self::SEARCH_RESULTS_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getQuickSearchShortcode() {
		return self::QUICK_SEARCH_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getSearchByAddressShortcode() {
		return self::SEARCH_BY_ADDRESS_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getSearchByListingIdShortcode() {
		return self::SEARCH_BY_LISTING_ID_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getMapSearchShortcode() {
		return self::MAP_SEARCH_SHORTCODE;
	}		

	/**
	 * @deprecated use constant
	 */
	public function getAgentListingsShortcode() {
		return self::AGENT_LISTINGS_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getOfficeListingsShortcode() {
		return self::OFFICE_LISTINGS_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getListingGalleryShortcode() {
		return self::GALLERY_SLIDER_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getBasicSearchShortcode() {
		return self::BASIC_SEARCH_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getAdvancedSearchShortcode() {
		return self::ADVANCED_SEARCH_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getOrganizerLoginShortcode() {
		return self::ORGANIZER_LOGIN_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getAgentDetailShortcode() {
		return self::AGENT_DETAIL_SHORTCODE;
	}
	
	/**
	 * @deprecated use constant
	 */
	public function getValuationFormShortcode() {
		return self::VALUATION_FORM_SHORTCODE;
	}		
	
	public function getBasicSearch($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getListingSearch($attributes);
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}

	public function getAdvancedSearch($attributes) {
		$boardId = $this->getAttribute($attributes, "boardId");
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getListingSearch($attributes);
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM);
			$virtualPage->addParameter("boardId", $boardId);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}

	public function getOrganizerLogin($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getPropertyOrganizerLoginPage();
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGIN);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}

	public function getOrganizerLoginWidget($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getLoginWidget($attributes);
		} else {
			$style = $this->getAttribute($attributes, "style");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "property-organizer-login-form")
				->addParameter("style", $style)
				->addParameter("smallView", true)
			;
			if($this->displayRules->isNoId() === true) {
				$remoteRequest->addParameter("noId", true);
			}
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}

	public function getAgentDetail($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getAgentPage($attributes);
		} else {
			$agentId = $this->getAttribute($attributes, "agentId");
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_DETAIL);
			$virtualPage->addParameter("context", "shortcode");
			$virtualPage->addParameter("agentID", $agentId);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getAgentList($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getAgentsList();
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_LIST);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getOfficeList($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getOfficesPage();
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::OFFICE_LIST);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getValuationForm($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getValuationRequestFormPage();
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::VALUATION_FORM);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getMortgageCalculator($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getMortgageCalculatorPage();
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::MORTGAGE_CALCULATOR);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getValuationWidget($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getValuationFormWidget($attributes);
		} else {
			$style = $this->getAttribute($attributes, "style");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "valuation-form-widget")
				->addParameter("smallView", true)
				->addParameter("style", $style)
			;
			if($this->displayRules->isNoId() === true) {
				$remoteRequest->addParameter("noId", true);
			}
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}
	
	public function getContactForm($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getContactFormPage();
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::CONTACT_FORM);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getRegistrationForm($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getRegistrationFormWidget($attributes);
		} else {
			$url = $this->getAttribute($attributes, "url");
			$buttonText = $this->getAttribute($attributes, "buttonText");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "registration-form")
				->addParameter("url", $url)
				->addParameter("buttonText", $buttonText)
			;
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}
	
	public function getEmailAlerts($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getListings();
		} else {
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getListingReport($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getMarketListingReport($attributes);
		} else {
			$id = $this->getAttribute($attributes, "id");
			$includeMap = $this->getAttribute($attributes, "includeMap");
			$sortBy = $this->getAttribute($attributes, "sortBy");
			$header = $this->getAttribute($attributes, "header");
			$displayType = $this->getAttribute($attributes, "displayType");
			$resultsPerPage = $this->getAttribute($attributes, "resultsPerPage");
			$status = $this->getAttribute($attributes, "status");
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOT_SHEET_LISTING_REPORT);
			$virtualPage->addParameter("hotSheetId", $id);
			$virtualPage->addParameter("includeMap", $includeMap);
			$virtualPage->addParameter("sortBy", $sortBy);
			if($header == "true") {
				$virtualPage->addParameter("gallery", false);
			} else {
				$virtualPage->addParameter("gallery", true);
			}
			$virtualPage->addParameter("displayType", $displayType);
			$virtualPage->addParameter("resultsPerPage", $resultsPerPage);
			$virtualPage->addParameter("status", $status);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getOpenHomeReport($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getMarketOpenHomeReport($attributes);
		} else {
			$id = $this->getAttribute($attributes, "id");
			$includeMap = $this->getAttribute($attributes, "includeMap");
			$sortBy = $this->getAttribute($attributes, "sortBy");
			$header = $this->getAttribute($attributes, "header");
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOT_SHEET_OPEN_HOME_REPORT);
			$virtualPage->addParameter("hotSheetId", $id);
			$virtualPage->addParameter("includeMap", $includeMap);
			$virtualPage->addParameter("sortBy", $sortBy);
			if($header == "true") {
				$virtualPage->addParameter("gallery", false);
			} else {
				$virtualPage->addParameter("gallery", true);
			}
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getMarketReport($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getMarketMarketReport($attributes);
		} else {
			$id = $this->getAttribute($attributes, "id");
			$header = $this->getAttribute($attributes, "header");
			$columns = $this->getAttribute($attributes, "columns");
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOT_SHEET_MARKET_REPORT);
			$virtualPage->addParameter("hotSheetId", $id);
			if($header == "true") {
				$virtualPage->addParameter("gallery", false);
			} else {
				$virtualPage->addParameter("gallery", true);
			}
			$virtualPage->addParameter("numColumns", $columns);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getAgentListings($attributes) {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getAgentListingsWidget($attributes);
		} else {
			$agentId = $this->getAttribute($attributes, "agentId");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "agent-or-office-listings")
				->addParameter("agentId", $agentId)
			;
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}

	public function getOfficeListings($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getOfficeListingsWidget($attributes);
		} else {
			$officeId = $this->getAttribute($attributes, "officeId");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "agent-or-office-listings")
				->addParameter("officeId", $officeId)
			;
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}
	
	public function getFeaturedListings($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getFeaturedListingsPage($attributes);
		} else {
			$includeMap = $this->getAttribute($attributes, "includeMap");
			$sortBy = $this->getAttribute($attributes, "sortBy");
			$header = $this->getAttribute($attributes, "header");
			$displayType = $this->getAttribute($attributes, "displayType");
			$resultsPerPage = $this->getAttribute($attributes, "resultsPerPage");
			$propertyType = $this->getAttribute($attributes, "propertyType");
			$status = $this->getAttribute($attributes, "status");
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::FEATURED_SEARCH);
			$virtualPage->addParameter("includeMap", $includeMap);
			$virtualPage->addParameter("sortBy", $sortBy);
			if($header == "true") {
				$virtualPage->addParameter("gallery", false);
			} else {
				$virtualPage->addParameter("gallery", true);
			}
			$virtualPage->addParameter("displayType", $displayType);
			$virtualPage->addParameter("resultsPerPage", $resultsPerPage);
			$virtualPage->addParameter("propertyType", $propertyType);
			$virtualPage->addParameter("status", $status);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}

	public function getSearchResults($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getListingSearch($attributes);
		} else {
			$bath = $this->getAttribute($attributes, "bath");
			$bed = $this->getAttribute($attributes, "bed");
			$cityId = $this->getAttribute($attributes, "cityId");
			$cityZip = $this->getAttribute($attributes, "cityZip");
			$minPrice = $this->getAttribute($attributes, "minPrice");
			$maxPrice = $this->getAttribute($attributes, "maxPrice");
			$propertyType = $this->getAttribute($attributes, "propertyType");
			$staticView = $this->getAttribute($attributes, "staticView");
			$includeMap = $this->getAttribute($attributes, "includeMap");
			$sortBy = $this->getAttribute($attributes, "sortBy");
			$header = $this->getAttribute($attributes, "header");
			$displayType = $this->getAttribute($attributes, "displayType");
			$resultsPerPage = $this->getAttribute($attributes, "resultsPerPage");
			$status = $this->getAttribute($attributes, "status");
			$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
			if(is_numeric($cityId)) {
				$virtualPage->addParameter("cityId", $cityId);
			}
			if(!empty($cityZip)) {
				$searchLinkInfo = new iHomefinderSearchLinkInfo(null, $cityZip, $propertyType, $minPrice, $maxPrice);
				if($searchLinkInfo->hasPostalCode()) {
					$virtualPage->addParameter("zip", $searchLinkInfo->getPostalCode());
				} else {
					$virtualPage->addParameter("city", $searchLinkInfo->getCity());
					if($searchLinkInfo->hasState()) {
						$virtualPage->addParameter("state", $searchLinkInfo->getState());
					}
				}
			}			
			if(!empty($propertyType)) {
				$virtualPage->addParameter("propertyType", $propertyType);
			}
			if(is_numeric($bed)) {
				$virtualPage->addParameter("bedrooms", $bed);
			}
			if(is_numeric($bath)) {
				$virtualPage->addParameter("bathcount", $bath);
			}
			if( is_numeric($minPrice)) {
				$virtualPage->addParameter("minListPrice", $minPrice);
			}
			if(is_numeric($maxPrice)) {
				$virtualPage->addParameter("maxListPrice", $maxPrice);
			}
			$virtualPage->addParameter("includeMap", $includeMap);
			$virtualPage->addParameter("sortBy", $sortBy);
			if($header == "true") {
				$virtualPage->addParameter("gallery", false);
			} else {
				$virtualPage->addParameter("gallery", true);
			}
			$virtualPage->addParameter("displayType", $displayType);
			$virtualPage->addParameter("resultsPerPage", $resultsPerPage);
			$virtualPage->addParameter("status", $status);
			if (iHomefinderDisplayRules::getInstance()->isEurekaSearch() && $staticView === null) {
				$staticView = true;
			}
			$virtualPage->addParameter("staticView", $staticView);
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			$this->enqueueResource->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getQuickSearch($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getQuickSearchWidget($attributes);
		} else {
			$style = $this->getAttribute($attributes, "style");
			$showPropertyType = $this->getAttribute($attributes, "showPropertyType");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "listing-search-form")
				->addParameter("smallView", true)
				->addParameter("style", $style)
				->addParameter("showPropertyType", $showPropertyType)
			;
			if($this->displayRules->isNoId() === true) {
				$remoteRequest->addParameter("noId", true);
			}
			$remoteRequest->setCacheExpiration(60*60);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}

	public function getSearchByAddress($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getQuickSearchWidget($attributes);
		} else {
			$style = "universal";
			$showPropertyType = false;
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "search-by-address-form")
				->addParameter("smallView", true)
				->addParameter("style", $this->getAttribute($attributes, "style"))
			;
			if($this->displayRules->isNoId() === true) {
				$remoteRequest->addParameter("noId", true);
			}
			$remoteRequest->setCacheExpiration(60*60);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}
	
	public function getSearchByListingId($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getQuickSearchWidget($attributes);
		} else {
			$style = "universal";
			$showPropertyType = false;
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "search-by-listing-id-form")
				->addParameter("smallView", true)
			;
			if($this->displayRules->isNoId() === true) {
				$remoteRequest->addParameter("noId", true);
			}
			$remoteRequest->setCacheExpiration(60*60);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}
	
	public function getMapSearch($attributes) {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getListingSearch($attributes);
		} else {
			$width = $this->getAttribute($attributes, "width");
			$height = $this->getAttribute($attributes, "height");
			$centerlat = $this->getAttribute($attributes, "centerlat");
			$centerlong = $this->getAttribute($attributes, "centerlong");
			$address = $this->getAttribute($attributes, "address");
			$zoom = $this->getAttribute($attributes, "zoom");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "map-search-widget")
				->addParameter("width", $width)
				->addParameter("height", $height)
				->addParameter("centerlat", $centerlat)
				->addParameter("centerlong", $centerlong)
				->addParameter("address", $address)
				->addParameter("zoom", $zoom)
			;
			$remoteRequest->setCacheExpiration(60*60);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}
	
	public function getEurekaSearch($attributes) {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getListingSearch($attributes);
		} else {
			$height = $this->getAttribute($attributes, "height");
			$address = $this->getAttribute($attributes, "address");
			$zoom = $this->getAttribute($attributes, "zoom");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "eureka-search")
				->addParameter("height", $height)
				->addParameter("address", $address)
				->addParameter("zoom", $zoom)
			;
			$remoteRequest->setCacheExpiration(60*60);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}

	public function getGallerySlider($attributes) {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getGallerySlider($attributes);
		} else {
			$hotSheetId = $this->getAttribute($attributes, "hotSheetId");
			if(empty($hotSheetId)) {
				/**
				 * @deprecated use hotSheetId
				 */
				$hotSheetId = $this->getAttribute($attributes, "id");
			}
			$width = $this->getAttribute($attributes, "width");
			$height = $this->getAttribute($attributes, "height");
			$rows = $this->getAttribute($attributes, "rows");
			$columns = $this->getAttribute($attributes, "columns");
			$nav = $this->getAttribute($attributes, "nav");
			$style = $this->getAttribute($attributes, "style");
			$effect = $this->getAttribute($attributes, "effect");
			$auto = $this->getAttribute($attributes, "auto");
			$interval = $this->getAttribute($attributes, "interval");
			$maxResults = $this->getAttribute($attributes, "maxResults");
			$status = $this->getAttribute($attributes, "status");
			$sortBy = $this->getAttribute($attributes, "sortBy");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "listing-gallery-slider")
				->addParameter("hid", $hotSheetId)
				->addParameter("width", $width)
				->addParameter("height", $height)
				->addParameter("rows", $rows)
				->addParameter("columns", $columns)
				->addParameter("navigation", $nav)
				->addParameter("style", $style)
				->addParameter("effect", $effect)
				->addParameter("auto", $auto)
				->addParameter("interval", $interval)
				->addParameter("maxResults", $maxResults)
				->addParameter("status", $status)
				->addParameter("sortBy", $sortBy)
			;
			if($this->displayRules->isNoId() === true) {
				$remoteRequest->addParameter("noId", true);
			}
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}
	
	public function getHotSheetReportSignup($attributes) {
		if($this->displayRules->isKestrelAll()) {
			$content = iHomefinderKestrelShortcode::getMarketReportSignupWidget($attributes);
		} else {
			$hotSheetId = $this->getAttribute($attributes, "id");
			$hotSheetReportType = $this->getAttribute($attributes, "reportType");
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("requestType", "email-signup")
				->addParameter("hotSheetId", $hotSheetId)
				->addParameter("hotSheetReportType", $hotSheetReportType)
			;
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			$this->enqueueResource->addToFooter($remoteResponse->getHead());
		}
		return $content;
	}

	public function getEmpty() {
		return null;
	}
	
	/**
	 * all values in the $attributes array are convered to lowercase
	 */
	private function getAttribute($attributes, $key) {
		return iHomefinderUtility::getInstance()->getVarFromArray($key, $attributes);
	}
	
	/**
	 * used by iHomefinderAdmin to generate shortcode string for community pages
	 */
	public function buildSearchResultsShortcode($cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice) {
		$result = $this->buildShortcode(self::SEARCH_RESULTS_SHORTCODE, array(
			"cityZip" => $cityZip,
			"propertyType" => $propertyType,
			"bed" => $bed,
			"bath" => $bath,
			"minPrice" => $minPrice,
			"maxPrice" => $maxPrice,	
		));
		return $result;
	}
	
	/**
	 * @param string $slug
	 * @param array $attributes
	 * @return string
	 */
	private function buildShortcode($slug, array $attributes) {
		$result = "[";
		$result .= $slug;
		if(is_array($attributes)) {
			foreach($attributes as $name => $value) {
				$result .= " " . $name . "=\"" . $value . "\"";
			}
		}
		$result .= "]";
		return $result;
	}

}