<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * OfficeOrderDeliveryMethodType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class OfficeOrderDeliveryMethodType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _COURIER = 'COURIER';
    const _OTHER = 'OTHER';
    const _PICKUP = 'PICKUP';
    const _SHIPMENT = 'SHIPMENT';
}
