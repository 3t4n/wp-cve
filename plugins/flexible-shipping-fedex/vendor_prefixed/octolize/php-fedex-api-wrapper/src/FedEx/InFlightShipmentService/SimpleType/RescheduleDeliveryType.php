<?php

namespace FedExVendor\FedEx\InFlightShipmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the ways to reschedule the delivery of a shipment.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  In Flight Shipment Service
 */
class RescheduleDeliveryType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _APPOINTMENT = 'APPOINTMENT';
    const _DATE_CERTAIN = 'DATE_CERTAIN';
    const _EVENING = 'EVENING';
}
