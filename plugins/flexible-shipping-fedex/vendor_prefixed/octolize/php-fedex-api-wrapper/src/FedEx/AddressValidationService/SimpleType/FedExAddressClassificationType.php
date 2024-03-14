<?php

namespace FedExVendor\FedEx\AddressValidationService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the address classification (business vs. residential)
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Address Validation Service
 */
class FedExAddressClassificationType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BUSINESS = 'BUSINESS';
    const _MIXED = 'MIXED';
    const _RESIDENTIAL = 'RESIDENTIAL';
    const _UNKNOWN = 'UNKNOWN';
}
