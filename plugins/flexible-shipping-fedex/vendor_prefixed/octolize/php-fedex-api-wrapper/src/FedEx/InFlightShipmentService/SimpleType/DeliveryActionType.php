<?php

namespace FedExVendor\FedEx\InFlightShipmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the actions that can be taken on a delivery option.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  In Flight Shipment Service
 */
class DeliveryActionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ADD = 'ADD';
}
