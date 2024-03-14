<?php

class iHomefinderKestrelShortcode {

	private static function getAttribute($attributes, $attribute, $type) {
		$utility = iHomefinderUtility::getInstance();
		$attribute = $utility->getVarFromArray($attribute, $attributes);
		if($attribute) {
			if($type === "int") {
				return (int) $attribute;
 			}
			if ($type === "boolean") {
				return filter_var($attribute, FILTER_VALIDATE_BOOLEAN);
			}
			if($type === "float") {
				return (float) $attribute;
			}
			if($type === "int[]") {
				$int_array = array();
				$array = $utility->delimitedStringToArray($attribute, ",", "intval");
				foreach($array as $key => $value) {
					if(is_numeric($value)) {
						array_push($int_array, (int) $value);
					}
				}
				return $int_array;
			}
			if($type === "string[]") {
				$str_array = array();
				$array = $utility->delimitedStringToArray($attribute, ",", "strval");
				foreach($array as $key => $value) {
					array_push($str_array, $value);
				}
				return $str_array;
			} 
			if(isset($attribute)) {
				return $attribute;
			}
			return;
		}
	}

	public static function getGallerySlider($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "gallerySliderWidget";
		$config["id"] = self::getAttribute($attributes, "id", "int");
		$config["width"] = self::getAttribute($attributes, "width", "int");		
		$config["height"] = self::getAttribute($attributes, "height", "int");		
		$config["rows"] = self::getAttribute($attributes, "rows", "int");		
		$config["columns"] = self::getAttribute($attributes, "columns", "int");		
		$config["nav"] = self::getAttribute($attributes, "nav", null);		
		$style = self::getAttribute($attributes, "style", null);		
		if(isset($style)) {
			$config["style"] = $style;
		} else {
			$config["style"] = "horizontal";
		}
		$config["effect"] = self::getAttribute($attributes, "effect", null);		
		$config["auto"] = self::getAttribute($attributes, "auto", "boolean");		
		$config["interval"] = self::getAttribute($attributes, "interval", "int") * 1000;		
		$config["maxResults"] = self::getAttribute($attributes, "maxResults", "int");		
		$config["status"] = self::getAttribute($attributes, "status", null);		
		$config["sort"] = self::getAttribute($attributes, "sortBy", null);		
		return $utility->getKestrelBody($config);
	}

	public static function getListingSearch($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$formData = iHomefinderFormData::getInstance();
		$cityId = self::getAttribute($attributes, "cityId", "int[]");
		$zip = null;
		$cityZip = self::getAttribute($attributes, "cityZip", "string");
		if(!empty($cityZip)) {
			$cityId = $formData->getCityIdFromCityName($cityZip);
			if(empty($cityId)) {
				$zip = $cityZip;
			}
		}
		$config = array();
		$config["component"] = "listingSearchWidget";
		$config["width"] = self::getAttribute($attributes, "width", "int");
		$config["height"] = self::getAttribute($attributes, "width", "int");
		$config["featured"] = self::getAttribute($attributes, "featured", "boolean");		
		$config["cityIds"] = $cityId;		
		$config["zip"] = $zip;		
		$config["propertyType"] = self::getAttribute($attributes, "propertyType", "string[]");		
		$config["bed"] = self::getAttribute($attributes, "bed", "int");		
		$config["bath"] = self::getAttribute($attributes, "bath", "int");		
		$config["minPrice"] = self::getAttribute($attributes, "minPrice", "float");		
		$config["maxPrice"] = self::getAttribute($attributes, "maxPrice", "float");		
		$config["sort"] = self::getAttribute($attributes, "sortBy", null);		
		$config["displayType"] = self::getAttribute($attributes, "displayType", null);		
		$config["resultsPerPage"] = self::getAttribute($attributes, "resultsPerPage", "int");		
		$config["header"] = self::getAttribute($attributes, "header", "boolean");		
		$config["includeMap"] = self::getAttribute($attributes, "includeMap", "boolean");		
		$config["status"] = self::getAttribute($attributes, "status", null);
		$config["centerlat"] = self::getAttribute($attributes, "centerlat", "float");
		$config["centerlong"] = self::getAttribute($attributes, "centerlong", "float");
		$config["address"] = self::getAttribute($attributes, "address", null);
		$config["zoom"] = self::getAttribute($attributes, "zoom", "int");
		return $utility->getKestrelBody($config);
	}

	public static function getPropertyOrganizerLoginPage() {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "propertyOrganizerLoginPage";
		return $utility->getKestrelBody($config);
	}

	public static function getLoginWidget($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "loginWidget";
		$style = self::getAttribute($attributes, "style", null);		
		if(isset($style)) {
			$config["style"] = $style;
		} else {
			$config["style"] = "horizontal";
		}
		return $utility->getKestrelBody($config);
	}

	public static function getAgentPage($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "agentPage";
		$config["id"] = self::getAttribute($attributes, "agentId", "int");
		return $utility->getKestrelBody($config);
	}

	public static function getAgentsList() {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "agentsPage";
		return $utility->getKestrelBody($config);
	}

	public static function getOfficesPage() {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "officesPage";
		return $utility->getKestrelBody($config);
	}
	
	public static function getValuationRequestFormPage() {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "valuationRequestFormPage";
		return $utility->getKestrelBody($config);
	}

	public static function getMortgageCalculatorPage() {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "mortgageCalculatorPage";
		return $utility->getKestrelBody($config);
	}

	public static function getValuationFormWidget($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "valuationFormWidget";
		$style = self::getAttribute($attributes, "style", null);		
		if(isset($style)) {
			$config["style"] = $style;
		} else {
			$config["style"] = "vertical";
		}
		return $utility->getKestrelBody($config);
	}

	public static function getContactFormPage() {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "contactFormPage";
		return $utility->getKestrelBody($config);
	}

	public static function getRegistrationFormWidget($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "registrationFormWidget";
		$config["redirectUrl"] = self::getAttribute($attributes, "url", null);
		$config["buttonText"] = self::getAttribute($attributes, "buttonText", null);
		return $utility->getKestrelBody($config);
	}

	public static function getListings() {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "listingsPage";
		return $utility->getKestrelBody($config);
	}

	public static function getMarketListingReport($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "marketListingReportPage";
		$config["id"] = self::getAttribute($attributes, "id", "int");
		$config["includeMap"] = self::getAttribute($attributes, "includeMap", "boolean");
		$config["sort"] = self::getAttribute($attributes, "sortBy", null);
		$config["header"] = self::getAttribute($attributes, "header", "boolean");
		$config["displayType"] = self::getAttribute($attributes, "displayType", null);
		$config["resultsPerPage"] = self::getAttribute($attributes, "resultsPerPage", "int");
		$config["status"] = self::getAttribute($attributes, "status", null);
		return $utility->getKestrelBody($config);
	}

	public static function getMarketOpenHomeReport($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "marketOpenHomeReportPage";
		$config["id"] = self::getAttribute($attributes, "id", "int");
		$config["includeMap"] = self::getAttribute($attributes, "includeMap", "boolean");
		$config["sort"] = self::getAttribute($attributes, "sortBy", null);
		$config["header"] = self::getAttribute($attributes, "header", "boolean");
		$config["displayType"] = self::getAttribute($attributes, "displayType", null);
		$config["resultsPerPage"] = self::getAttribute($attributes, "resultsPerPage", "int");
		$config["status"] = self::getAttribute($attributes, "status", null);
		return $utility->getKestrelBody($config);
	}

	public static function getMarketMarketReport($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "marketMarketReportPage";
		$config["id"] = self::getAttribute($attributes, "id", "int");
		$config["columns"] = self::getAttribute($attributes, "columns", "int");
		$config["header"] = self::getAttribute($attributes, "header", "boolean");
		return $utility->getKestrelBody($config);
	}

	public static function getAgentListingsWidget($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "agentListingsWidget";
		$config["id"] = self::getAttribute($attributes, "agentId", "int");
		return $utility->getKestrelBody($config);
	}

	public static function getOfficeListingsWidget($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "officeListingsWidget";
		$config["id"] = self::getAttribute($attributes, "officeId", "int");
		return $utility->getKestrelBody($config);
	}

	public static function getFeaturedListingsPage($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "listingSearchWidget";
		$config["propertyType"] = self::getAttribute($attributes, "propertyType", "string[]");	
		$config["featured"] = true;
		$config["includeMap"] = self::getAttribute($attributes, "includeMap", "boolean");
		$config["status"] = self::getAttribute($attributes, "status", "string");
		$config["sort"] = self::getAttribute($attributes, "sortBy", "string");
		$config["resultsPerPage"] = self::getAttribute($attributes, "resultsPerPage", "int");
		return $utility->getKestrelBody($config);
	}

	public static function getQuickSearchWidget($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "quickSearchWidget";
		$config["showPropertyType"] = self::getAttribute($attributes, "showPropertyType", "boolean");
		$style = self::getAttribute($attributes, "style", null);		
		if(isset($style)) {
			$config["style"] = $style;
		} else {
			$config["style"] = "vertical";
		}
		return $utility->getKestrelBody($config);
	}

	public static function getMarketReportSignupWidget($attributes) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "marketReportSignupWidget";
		$config["id"] = self::getAttribute($attributes, "id", "int");
		$reportType = self::getAttribute($attributes, "reportType", "string");
		if($reportType === "listing") {
			$config["marketReportTypeId"] = 1;
		} else if($reportType === "openHome") {
			$config["marketReportTypeId"] = 2;
		} else {
			$config["marketReportTypeId"] = 3;
		}
		return $utility->getKestrelBody($config);
	}
	
}