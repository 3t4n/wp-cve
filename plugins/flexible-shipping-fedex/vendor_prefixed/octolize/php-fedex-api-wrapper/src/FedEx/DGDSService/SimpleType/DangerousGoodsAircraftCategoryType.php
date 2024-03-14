<?php

namespace FedExVendor\FedEx\DGDSService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * DangerousGoodsAircraftCategoryType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 */
class DangerousGoodsAircraftCategoryType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CARGO_AIRCRAFT_ONLY = 'CARGO_AIRCRAFT_ONLY';
    const _PASSENGER_AND_CARGO_AIRCRAFT = 'PASSENGER_AND_CARGO_AIRCRAFT';
}
