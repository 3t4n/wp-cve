<?php

class iHomefinderVariableUtility {
	
	private static $instance;
	
	private function __construct() {
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * @param string $input
	 * @param array<iHomefinderVariable> $variables
	 * @return string
	 */
	public function replaceVariable($input, array $variables) {
		$result = $input;
		if(is_array($variables)) {
			foreach($variables as $variable) {
				if(is_a($variable, "iHomefinderVariable")) {
					$result = str_replace($variable->getNameWithAffix(), $variable->getValue(), $result);
				}
			}
		}
		return $result;
	}
	
	/**
	 * @param array<iHomefinderVariable> $variables
	 * @return array
	 */
	public function getAffixedArray($variables) {
		$result = array();
		foreach($variables as $variable) {
			$result[] = array(
				"name" => $variable->getNameWithAffix(),
				"value" => $variable->getValue(),
				"description" => $variable->getDescription(),
			);
		}
		return $result;
	}
	
	public function getListingAddress() {
		return new iHomefinderVariable("listingAddress", null, "Listing Address");
	}
	
	public function getListingCity() {
		return new iHomefinderVariable("listingCity", null, "Listing City");
	}
	
	public function getListingPostalCode() {
		return new iHomefinderVariable("listingPostalCode", null, "Listing Postal Code");
	}
	
	public function getListingPhotoUrl() {
		return new iHomefinderVariable("listingPhotoUrl", null, "Listing Photo URL");
	}
	
	public function getListingPhotoWidth() {
		return new iHomefinderVariable("listingPhotoWidth", null, "Listing Photo Width");
	}
	
	public function getListingPhotoHeight() {
		return new iHomefinderVariable("listingPhotoHeight", null, "Listing Photo Height");
	}
	
	public function getListingPrice() {
		return new iHomefinderVariable("listingPrice", null, "Listing Price");
	}
	
	public function getListingSoldPrice() {
		return new iHomefinderVariable("listingSoldPrice", null, "Listing Sold Price");
	}
	
	public function getListingSquareFeet() {
		return new iHomefinderVariable("listingSquareFeet", null, "Listing Square Feet");
	}
	
	public function getListingBedrooms() {
		return new iHomefinderVariable("listingBedrooms", null, "Listing # of Bedrooms");
	}
	
	public function getListingBathrooms() {
		return new iHomefinderVariable("listingBathrooms", null, "Listing # of Bathrooms");
	}
	
	public function getListingNumber() {
		return new iHomefinderVariable("listingNumber", null, "Listing Number");
	}
	
	public function getListingDescription() {
		return new iHomefinderVariable("listingDescription", null, "Listing Description");
	}
	
	public function getSavedSearchId() {
		return new iHomefinderVariable("savedSearchId", null, "Market ID");
	}
	
	public function getSavedSearchName() {
		return new iHomefinderVariable("savedSearchName", null, "Market Name" );
	}
	
	public function getSavedSearchDescription() {
		return new iHomefinderVariable("savedSearchDescription", null, "Market Description");
	}
	
	public function getAgentId() {
		return new iHomefinderVariable("agentId", null, "Agent ID");
	}
	
	public function getAgentName() {
		return new iHomefinderVariable("agentName", null, "Agent Name");
	}
	
	public function getAgentDesignation() {
		return new iHomefinderVariable("agentDesignation", null, "Agent Designation");
	}
	
	public function getOfficeId() {
		return new iHomefinderVariable("officeId", null, "Office ID");
	}
	
	public function getOfficeName() {
		return new iHomefinderVariable("officeName", null, "Office Name");
	}
	
}