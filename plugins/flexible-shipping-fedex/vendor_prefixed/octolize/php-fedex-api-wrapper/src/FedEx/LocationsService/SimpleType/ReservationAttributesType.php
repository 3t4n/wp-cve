<?php

namespace FedExVendor\FedEx\LocationsService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Attributes about a reservation at a FedEx location.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 */
class ReservationAttributesType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _RESERVATION_AVAILABLE = 'RESERVATION_AVAILABLE';
}
