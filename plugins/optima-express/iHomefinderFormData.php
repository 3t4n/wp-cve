<?php

class iHomefinderFormData {
	
	private $initialized = false;
	private $hotSheets;
	private $cities; 
	private $cityZips; 
	private $propertyTypes;
	private $agents;
	private $boards;
	private $offices;
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	private function initialize() {
		if(!$this->initialized) {
			$this->initialized = true;
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest->addParameter("requestType", "search-form-lists");
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$this->hotSheets = $remoteResponse->getHotSheets();
			$this->cities = $remoteResponse->getCities();
			$this->cityZips = $remoteResponse->getCityZips();
			$this->propertyTypes = $remoteResponse->getPropertyTypes();
			$this->agents = $remoteResponse->getAgents();
			$this->boards = $remoteResponse->getBoards();
			$this->offices = $remoteResponse->getOffices();
		}
	}
	
	public function getHotSheets() {
		$this->initialize();
		return $this->hotSheets;
	}
	
	public function getCities() {
		$this->initialize();
		return $this->cities;
	}
	
	public function getCityZips() {
		$this->initialize();
		return $this->cityZips;
	}
	
	public function getPropertyTypes() {
		$this->initialize();
		return $this->propertyTypes;
	}
	
	public function getAgents() {
		$this->initialize();
		return $this->agents;
	}

	public function getBoards() {
		$this->initialize();
		return $this->boards;
	}
	
	public function getOffices() {
		$this->initialize();
		return $this->offices;
	}

	public function getCityIdFromCityName($name) {
		$result = null;
		$cities = $this->getCities();
		foreach($cities as $city) {
			if(strcasecmp((string) $city->displayName, $name) == 0) {
				$result = (int) $city->cityId;
				break;
			}
		}
		return $result;
	}
	
}