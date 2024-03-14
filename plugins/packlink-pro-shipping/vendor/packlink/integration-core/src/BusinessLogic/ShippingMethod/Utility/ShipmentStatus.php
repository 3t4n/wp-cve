<?php

namespace Packlink\BusinessLogic\ShippingMethod\Utility;

/**
 * Class ShipmentStatus.
 *
 * @package Packlink\BusinessLogic\ShippingMethod\Utilitys
 */
class ShipmentStatus
{
    /**
     * Status when draft is created.
     */
    const STATUS_PENDING = 'pending';
    /**
     * Status when carrier accepted package.
     */
    const STATUS_ACCEPTED = 'processing';
    /**
     * Status when labels are ready and shipment can be fulfilled.
     */
    const STATUS_READY = 'readyForShipping';
    /**
     * Status when shipment is in transit.
     */
    const STATUS_IN_TRANSIT = 'inTransit';
    /**
     * Status when shipment is completed.
     */
    const STATUS_DELIVERED = 'delivered';
    /**
     * Status when shipment is cancelled.
     */
    const STATUS_CANCELLED = 'cancelled';
    /**
     * Status when shipmnet is out for delivery
     */
    const OUT_FOR_DELIVERY = 'outForDelivery';
    /**
     * Status when some incident happens
     */
    const INCIDENT = 'incident';

    /**
     * Maps raw shipment status from Packlink to shipment status.
     *
     * @param string $shipmentStatus Raw shipment status from Packlink.
     *
     * @return string Shipment status.
     */
    public static function getStatus($shipmentStatus)
    {
        switch ($shipmentStatus) {
            case 'DELIVERED':
            case 'RETURNED_TO_SENDER':
                return self::STATUS_DELIVERED;
            case 'IN_TRANSIT':
                return self::STATUS_IN_TRANSIT;
            case 'READY_TO_PRINT':
            case 'READY_FOR_COLLECTION':
            case 'COMPLETED':
            case 'CARRIER_OK':
                return self::STATUS_READY;
            case 'CARRIER_KO':
            case 'LABELS_KO':
            case 'INTEGRATION_KO':
            case 'PURCHASE_SUCCESS':
            case 'CARRIER_PENDING':
            case 'RETRY':
                return self::STATUS_ACCEPTED;
            case 'CANCELED':
                return self::STATUS_CANCELLED;
            case 'OUT_FOR_DELIVERY':
                return self::OUT_FOR_DELIVERY;
            case 'INCIDENT' :
                return self::INCIDENT;
            case 'AWAITING_COMPLETION':
            case 'READY_TO_PURCHASE':
            default:
                return self::STATUS_PENDING;
        }
    }

    /**
     * Gets possible shipment statuses.
     *
     * @return string[]
     */
    public static function getPossibleStatuses()
    {
        return array(
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
            self::STATUS_READY,
            self::STATUS_IN_TRANSIT,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
            self::INCIDENT,
            self::OUT_FOR_DELIVERY,
        );
    }
}
