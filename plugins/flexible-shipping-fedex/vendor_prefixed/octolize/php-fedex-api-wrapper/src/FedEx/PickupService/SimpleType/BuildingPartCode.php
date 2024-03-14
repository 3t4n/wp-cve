<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * BuildingPartCode
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class BuildingPartCode extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _APARTMENT = 'APARTMENT';
    const _BUILDING = 'BUILDING';
    const _DEPARTMENT = 'DEPARTMENT';
    const _FLOOR = 'FLOOR';
    const _ROOM = 'ROOM';
    const _SUITE = 'SUITE';
}
