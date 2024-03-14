<?php

class iHomefinderKestrelWidget {

	public static function getContactFormWidget() {
		$utility = iHomefinderUtility::getInstance();
		$config = array(
			"component" => "contactFormWidget",
		);
		return $utility->getKestrelBody($config);
	}
	
	public static function getValuationFormWidget($style) {
		$utility = iHomefinderUtility::getInstance();
		$config = array(
			"component" => "valuationFormWidget",
			"style" => isset($style) ? $style : "vertical"
		);
		return $utility->getKestrelBody($config);
	}
	
	public static function getEmailSignupWidget() {
		$utility = iHomefinderUtility::getInstance();
		$config = array(
			"component" => "emailSignupWidget",
		);
		return $utility->getKestrelBody($config);
	}
	
	public static function getMarketsWidget($includeAll, $hotSheetIds) {
		$utility = iHomefinderUtility::getInstance();
		$config = array(
			"component" => "marketsWidget",
		);
		if(!$includeAll && count($hotSheetIds) > 0) {
			$config["marketIds"] = array_map("intval", $hotSheetIds);
		}
		return $utility->getKestrelBody($config);
	}
	
	public static function getMarketReportSignupWidget($hotSheetId, $marketReportTypeId) {
		$utility = iHomefinderUtility::getInstance();
		$config = array(
			"component" => "marketReportSignupWidget",
			"id" => $hotSheetId,
			"marketReportTypeId" => $marketReportTypeId,
		);
		return $utility->getKestrelBody($config);
	}
	
	public static function getLoginWidget($style) {
		$utility = iHomefinderUtility::getInstance();
		$config = array(
			"component" => "loginWidget",
			"style" => isset($style) ? $style : "vertical",
		);
		return $utility->getKestrelBody($config);
	}
	
	public static function getRequestMoreInfoFormWidget($id) {
		$utility = iHomefinderUtility::getInstance();
		$config = array(
			"component" => "requestMoreInfoFormWidget",
			"id" => $id,
		);
		return $utility->getKestrelBody($config);
	}

	public static function getPropertiesGalleryWidgetFeatured($propertyType, $resultsPerPage) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "propertiesGalleryWidget";
		$config["featured"] = true;
		$config["propertyTypes"] = explode(",", $propertyType);
		$config["resultsPerPage"] = $resultsPerPage;
		return $utility->getKestrelBody($config);
	}
	
	public static function getPropertiesGalleryWidgetHotSheet($hotSheetId, $resultsPerPage) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "propertiesGalleryWidget";
		$config["marketId"] = $hotSheetId;
		$config["resultsPerPage"] = $resultsPerPage;
		return $utility->getKestrelBody($config);
	}
	
	public static function getPropertiesGalleryWidget($cityId, $bed, $bath, $minPrice, $maxPrice, $propertyType, $resultsPerPage) {
		$utility = iHomefinderUtility::getInstance();
		$config = array();
		$config["component"] = "propertiesGalleryWidget";
		$config["cityId"] = $cityId;
		$config["propertyTypes"] = explode(",", $propertyType);
		$config["bed"] = $bed;
		$config["bath"] = $bath;
		$config["minPrice"] = $minPrice;
		$config["maxPrice"] = $maxPrice;
		$config["resultsPerPage"] = $resultsPerPage;
		return $utility->getKestrelBody($config);
	}
	
	public static function getQuickSearchWidget($style, $showPropertyType) {
		$utility = iHomefinderUtility::getInstance();
		$config = array(
			"component" => "quickSearchWidget",
			"style" => $style,
			"showPropertyType" => $showPropertyType,
		);
		return $utility->getKestrelBody($config);
	}
	
}