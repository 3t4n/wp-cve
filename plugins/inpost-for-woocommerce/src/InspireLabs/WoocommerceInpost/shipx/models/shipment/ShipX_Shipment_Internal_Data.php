<?php

namespace InspireLabs\WoocommerceInpost\shipx\models\shipment;

use InspireLabs\WoocommerceInpost\shipx\models\shipment_cost\ShipX_Shipment_Cost_Model;

/**
 * Class ShipX_Shipment_Internal_Data
 */
class ShipX_Shipment_Internal_Data {
	const API_VERSION_PRODUCTION = 1;
	const API_VERSION_SANDBOX = 2;


	/**
	 * @var string
	 */
	private $label_url;

	/**
	 * @var string
	 */
	private $status;

	/**
	 * @var string
	 */
	private $status_description;

	/**
	 * @var string
	 */
	private $status_title;

	/**
	 * @var int
	 */
	private $status_changed_timestamp;

	/**
	 * @var int
	 */
	private $inpost_id;

	/**
	 * @var  int
	 */
	private $order_id;

	/**
	 * @var string
	 */
	private $created_at;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var int
	 */
	private $tracking_number;

	/**
	 * @var ShipX_Shipment_Cost_Model
	 */
	private $shipment_cost;

	/**
	 * @var ShipX_Shipment_Dispatch_Status
	 */
	private $dispatch_status;

	/*
	 *
	 */
	private $cod_bank_iban;

	/**
	 * @var int
	 */
	private $api_version;

	/**
	 * @var ShipX_Shipment_Status_History_Item_Model[]
	 */
	private $status_history = [];

    /**
     * @var bool
     */
	private $is_weekend;

	/**
	 * @return string
	 */
	public function getLabelUrl() {
		return $this->label_url;
	}

	/**
	 * @param string $label_url
	 */
	public function setLabelUrl( $label_url ) {
		$this->label_url = $label_url;
	}

	/**
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param string $status
	 */
	public function setStatus( $status ) {
		$this->status = $status;
	}

	/**
	 * @return string
	 */
	public function getStatusDescription() {
		return $this->status_description;
	}

	/**
	 * @param string $status_description
	 */
	public function setStatusDescription( $status_description ) {
		$this->status_description = $status_description;
	}

	/**
	 * @return int
	 */
	public function getStatusChangedTimestamp() {
		return $this->status_changed_timestamp;
	}

	/**
	 * @param int $status_changed_timestamp
	 */
	public function setStatusChangedTimestamp( $status_changed_timestamp ) {
		$this->status_changed_timestamp = $status_changed_timestamp;
	}

	/**
	 * @return int
	 */
	public function getOrderId() {
		return $this->order_id;
	}

	/**
	 * @param int $order_id
	 */
	public function setOrderId( $order_id ) {
		$this->order_id = $order_id;
	}

	/**
	 * @return int
	 */
	public function getInpostId() {
		return $this->inpost_id;
	}

	/**
	 * @param int $inpost_id
	 */
	public function setInpostId( $inpost_id ) {
		$this->inpost_id = $inpost_id;
	}

	/**
	 * @return string
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * @param string $created_at
	 */
	public function setCreatedAt( $created_at ) {
		$this->created_at = $created_at;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl( $url ) {
		$this->url = $url;
	}

	/**
	 * @return int
	 */
	public function getTrackingNumber() {
		return $this->tracking_number;
	}

	/**
	 * @param int $tracking_number
	 */
	public function setTrackingNumber( $tracking_number ) {
		$this->tracking_number = $tracking_number;
	}

	/**
	 * @return ShipX_Shipment_Cost_Model
	 */
	public function getShipmentCost() {
		return $this->shipment_cost;
	}

	/**
	 * @param ShipX_Shipment_Cost_Model $shipment_cost
	 */
	public function setShipmentCost( $shipment_cost ) {
		$this->shipment_cost = $shipment_cost;
	}

	/**
	 * @return ShipX_Shipment_Dispatch_Status
	 */
	public function getDispatchStatus() {
		return $this->dispatch_status;
	}

	/**
	 * @param ShipX_Shipment_Dispatch_Status $dispatch_status
	 */
	public function setDispatchStatus( $dispatch_status ) {
		$this->dispatch_status = $dispatch_status;
	}

	/**
	 * @return string
	 */
	public function getStatusTitle() {
		return $this->status_title;
	}

	/**
	 * @param string $status_title
	 */
	public function setStatusTitle( $status_title ) {
		$this->status_title = $status_title;
	}

	/**
	 * @return int
	 */
	public function getApiVersion() {
		return $this->api_version;
	}

	/**
	 * @param int $api_version
	 */
	public function setApiVersion( $api_version ) {
		$this->api_version = $api_version;
	}

	/**
	 * @return ShipX_Shipment_Status_History_Item_Model[]
	 */
	public function get_status_history(): array {
		return $this->status_history;
	}

	/**
	 * @param ShipX_Shipment_Status_History_Item_Model[] $status_history
	 */
	public function set_status_history( array $status_history ): void {
		$this->status_history = $status_history;
	}

	public function putStatusHistoryItem( ShipX_Shipment_Status_History_Item_Model $status_history_item_model ) {
		$this->status_history[] = $status_history_item_model;
	}

	/**
	 * @return ShipX_Shipment_Status_History_Item_Model|null
	 */
	public function getLastStatusFromHistory(): ?ShipX_Shipment_Status_History_Item_Model {
		$lastStatus = end($this->status_history);
		return $lastStatus ?: null;
	}

    /**
     * @return bool
     */
    public function getWeekend() {
        return $this->is_weekend;
    }

    /**
     * @param bool $value
     */
    public function setWeekend( $value ) {
        $this->is_weekend = $value;
    }
}
