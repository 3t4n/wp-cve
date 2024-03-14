<?php

namespace FedExVendor\FedEx\LocationsService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * OperationalHoursType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 */
class OperationalHoursType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CLOSED_ALL_DAY = 'CLOSED_ALL_DAY';
    const _OPEN_ALL_DAY = 'OPEN_ALL_DAY';
    const _OPEN_BY_HOURS = 'OPEN_BY_HOURS';
}
