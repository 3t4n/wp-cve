<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PickupEventType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class PickupEventType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ASSIGNED_TO_DRIVER = 'ASSIGNED_TO_DRIVER';
    const _CANCELED = 'CANCELED';
    const _DRIVER_ARRIVED = 'DRIVER_ARRIVED';
    const _DRIVER_DEPARTED = 'DRIVER_DEPARTED';
    const _DRIVER_EN_ROUTE = 'DRIVER_EN_ROUTE';
    const _EXCEPTION = 'EXCEPTION';
    const _PICKED_UP = 'PICKED_UP';
    const _SCHEDULED = 'SCHEDULED';
}
