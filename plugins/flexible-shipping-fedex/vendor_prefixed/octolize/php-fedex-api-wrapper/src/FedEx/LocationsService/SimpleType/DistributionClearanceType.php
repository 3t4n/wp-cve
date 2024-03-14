<?php

namespace FedExVendor\FedEx\LocationsService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * DistributionClearanceType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 */
class DistributionClearanceType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _DESTINATION_COUNTRY_CLEARANCE = 'DESTINATION_COUNTRY_CLEARANCE';
    const _SINGLE_POINT_OF_CLEARANCE = 'SINGLE_POINT_OF_CLEARANCE';
}
