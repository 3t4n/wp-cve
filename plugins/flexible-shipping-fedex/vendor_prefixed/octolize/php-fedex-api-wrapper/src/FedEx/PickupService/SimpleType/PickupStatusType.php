<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PickupStatusType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class PickupStatusType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CANCELLED = 'CANCELLED';
    const _COMPLETED = 'COMPLETED';
    const _OPEN = 'OPEN';
}
