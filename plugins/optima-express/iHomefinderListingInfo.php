<?php

class iHomefinderListingInfo {
	
	private $listingNumber = null;
	private $boardId = null;
	private $address = null;
	private $clientPropertyId = null;
	private $sold = false;
	
	public function __construct($listingNumber, $boardId, $address, $clientPropertyId, $sold) {
		$this->listingNumber = $listingNumber;
		$this->boardId = $boardId;
		$this->address = $address;
		$this->clientPropertyId = $clientPropertyId;
		$this->sold = $sold;
	}
	
	public function getListingNumber() {
		return $this->listingNumber;
	}
	
	public function getAddress() {
		return $this->address;
	}

	public function getBoardId() {
		return $this->boardId;
	}
	
	public function getClientPropertyId() {
		return $this->clientPropertyId;
	}
	
	public function getSold() {
		return $this->sold;
	}

	public function getListingId() {
		if($this->listingNumber && $this->boardId) {
			return $this->listingNumber . "_" . $this->boardId;
		}
		if($this->clientPropertyId) {
			return $this->clientPropertyId . "_0";
		}
	}
	
}
