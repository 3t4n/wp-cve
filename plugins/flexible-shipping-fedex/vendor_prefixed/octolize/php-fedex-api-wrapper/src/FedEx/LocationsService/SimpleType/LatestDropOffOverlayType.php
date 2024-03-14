<?php

namespace FedExVendor\FedEx\LocationsService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the reason for the overlay of the daily last drop off time for a carrier.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 */
class LatestDropOffOverlayType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _US_WEST_COAST = 'US_WEST_COAST';
}
