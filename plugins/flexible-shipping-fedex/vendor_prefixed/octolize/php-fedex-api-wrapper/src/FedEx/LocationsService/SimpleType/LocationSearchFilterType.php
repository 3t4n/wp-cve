<?php

namespace FedExVendor\FedEx\LocationsService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the crieteria used to filter the location search results.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 */
class LocationSearchFilterType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _EXCLUDE_LOCATIONS_OUTSIDE_COUNTRY = 'EXCLUDE_LOCATIONS_OUTSIDE_COUNTRY';
    const _EXCLUDE_LOCATIONS_OUTSIDE_STATE_OR_PROVINCE = 'EXCLUDE_LOCATIONS_OUTSIDE_STATE_OR_PROVINCE';
    const _EXCLUDE_UNAVAILABLE_LOCATIONS = 'EXCLUDE_UNAVAILABLE_LOCATIONS';
}
