<?php

namespace FedExVendor\FedEx\LocationsService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Describes relationship between origin and destination countries.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 */
class CountryRelationshipType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _DOMESTIC = 'DOMESTIC';
    const _INTERNATIONAL = 'INTERNATIONAL';
}
