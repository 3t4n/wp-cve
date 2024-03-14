<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identifies types of special equipment used in loading/unloading Freight shipments
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class SpecialEquipmentType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _FORK_LIFT = 'FORK_LIFT';
}
