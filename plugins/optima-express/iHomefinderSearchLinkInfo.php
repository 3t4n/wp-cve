<?php

class iHomefinderSearchLinkInfo {
	
	private $linkText;
	private $cityZip;
	private $propertyType;
	private $minPrice;
	private $maxPrice;
	private $zip;

	public function __construct($linkText, $cityZip, $propertyType, $minPrice, $maxPrice) {
		$this->linkText = $linkText;
		$this->cityZip = $cityZip;
		$this->propertyType = $propertyType;
		$this->minPrice = $minPrice;
		$this->maxPrice = $maxPrice;
	}
	
	public function getLinkText() {
		return $this->linkText;
	}
	
	public function getCityZip() {
		return $this->cityZip;
	}
	
	public function getPropertyType() {
		return $this->propertyType;
	}
	
	public function getMinPrice() {
		return $this->minPrice;
	}
	
	public function getMaxPrice() {
		return $this->maxPrice;
	}
	
	public function getCity() {
		$city = null;
		if(!$this->hasPostalCode()) {
			if($this->hasState()) {
				//chop off the state from the array of parts.
				//state is the last part
				$cityState = $this->getCityZip();
				$statePosition = strrpos($cityState, ",");
				$city = substr($cityState, 0, $statePosition);
			} else {
				$city = $this->getCityZip();
			}
		}
		return $city;
	}
	
	public function getState() {
		$state = null;
		if($this->hasState()) {
			$cityState = $this->getCityZip();
			$statePosition = strrpos($cityState, ",") + 1;
			$state = substr($cityState, $statePosition);
		}
		return $state;
	}
	
	public function getPostalCode() {			
		if($this->hasPostalCode()) {
			return $this->cityZip;
		}
		return null;
	}
	
	public static function compare($a, $b) {
		$al = strtolower($a->linkText);
		$bl = strtolower($b->linkText);
		if($al == $bl) {
			return 0;
		}
		return ($al > $bl) ? +1 : -1;
	}	

	
	public function hasPostalCode() {
		$result = false;
		
		//US Postal Code
		if(preg_match("/\d{5}(-\d{4})?/", $this->cityZip) == 1) { 
			$result=true;
		}
		
		//Canadian Postal Code
		if(preg_match("/^([a-zA-Z]\d[a-zA-z]()?\d[a-zA-Z]\d)$/", $this->cityZip) == 1) {
			$result = true;
		}

		return $result;
	}
	
	public function hasState() {
		$result = false;
		//ends with a value like ", CA" or ",CA"
		if(preg_match("/,\s*[a-zA-Z]{2}$/", $this->cityZip) == 1) {
			$result = true;
		} 
		return $result;
	}
		
}