<?php

namespace InspireLabs\WoocommerceInpost\shipx\services\shipment;

use InspireLabs\WoocommerceInpost\EasyPack;
use Exception;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Internal_Data;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Status_History_Item_Model;

class ShipX_Shipment_Status_Service {

	const SHIPX_STATUSES_OPTION_KEY = 'shipx_statuses';

	/**
	 * @var ShipX_Shipment_Service
	 */
	private $shipment_service;

	/**
	 * @var array
	 */
	private $statuses;

	public function __construct() {
		$this->shipment_service = EasyPack()->get_shipment_service();
		$statuses               = $this->get_statuses_from_db();
		if ( empty( $statuses ) ) {
			$this->synchronise_statuses();
		} else {
			$this->statuses = $statuses;
		}
	}

	private function synchronise_statuses() {
		$statuses = $this->get_statuses_from_api();

		if ( is_array( $statuses ) && ! empty( $statuses ) ) {
			$this->statuses = $statuses['items'];
			$this->update_statuses();
		}
	}

	/**
	 * @return array
	 */
	public function get_statuses_from_db() {
		return get_option( self::SHIPX_STATUSES_OPTION_KEY );
	}

	/**
	 * @return array
	 */
	public function get_statuses_key_value() {
		$return = [
			'any' => __( 'Any status', 'woocommerce-inpost' ),
		];
		foreach ( $this->get_statuses_from_db() as $item ) {
			$return[ $item['name'] ] = $item['name'];
		}

		return $return;
	}

	/**
	 * @return string
	 */
	private function get_statuses_from_api() {
		try {
			return EasyPack_API()->get_statuses();
		} catch ( Exception $exception ) {
			return null;
		}
	}

	private function update_statuses() {
		update_option( self::SHIPX_STATUSES_OPTION_KEY, $this->statuses );
	}

	/**
	 * @param string $search
	 *
	 * @return string|null
	 */
	public function getStatusTitle( $search ) {
		foreach ( $this->statuses as $status ) {
			if ( $search === $status['name'] ) {
				return $status['title'];
			}
		}

		return null;
	}

	public function getStatusDescription( $search ) {
		foreach ( $this->statuses as $status ) {
			if ( $search === $status['name'] ) {
				return $status['description'];
			}
		}

		return null;
	}

	/**
	 * @param ShipX_Shipment_Model $shipment
	 */
	public function refreshStatus( ShipX_Shipment_Model $shipment ) {

		try {
			$search_results = EasyPack_API()->customer_parcel_get_by_id( $shipment->getInternalData()->getInpostId() );
		} catch ( Exception $e ) {
			$search_results['items'] = [];
		}

		if ( empty( $search_results['id'] ) ) {
			$shipment->getInternalData()->setStatus( 'new' );
			$shipment->getInternalData()->setStatusChangedTimestamp(time());

			$shipment->getInternalData()->setStatusTitle( __( 'Not created yet', 'woocommerce-inpost' ) );
			$shipment->getInternalData()->setStatusDescription( null );
		} else {
			$shipment->getInternalData()->setTrackingNumber( $search_results['tracking_number'] );
			$status_id = $search_results['status'];
			$shipment->getInternalData()->setStatus( $search_results['status'] );
			$shipment->getInternalData()->setStatusChangedTimestamp(time());
			$shipment->getInternalData()->setStatusTitle( $this->getStatusTitle( $status_id ) );
			$shipment->getInternalData()->setStatusDescription( $this->getStatusDescription( $status_id ) );
		}

		$this->shipment_service->update_shipment_to_db( $shipment );
	}

	public function formatStatusHistory( ShipX_Shipment_Internal_Data $internalData ) {

		$return = '';
		foreach ( $internalData->get_status_history() as $item ) {
			$return .= $item->get_name() . ' ' . date( 'd-m-Y H:i:s', (int) $item->get_timestamp() + 7200 );
			$return .= '<br>';
		}

		return $return;


	}
}