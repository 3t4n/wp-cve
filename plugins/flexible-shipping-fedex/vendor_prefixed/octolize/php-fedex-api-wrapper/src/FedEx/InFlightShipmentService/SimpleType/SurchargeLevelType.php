<?php

namespace FedExVendor\FedEx\InFlightShipmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * SurchargeLevelType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  In Flight Shipment Service
 */
class SurchargeLevelType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _PACKAGE = 'PACKAGE';
    const _SHIPMENT = 'SHIPMENT';
}
