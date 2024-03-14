<?php

namespace FedExVendor\FedEx\LocationsService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * ShippingActionType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 */
class ShippingActionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _DELIVERIES = 'DELIVERIES';
    const _PICKUPS = 'PICKUPS';
}
