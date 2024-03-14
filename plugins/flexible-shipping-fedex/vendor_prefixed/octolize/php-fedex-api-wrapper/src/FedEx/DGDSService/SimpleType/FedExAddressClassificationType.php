<?php

namespace FedExVendor\FedEx\DGDSService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identification of a fedex Address classificationTypes
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 */
class FedExAddressClassificationType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BUSINESS = 'BUSINESS';
    const _MIXED = 'MIXED';
    const _RESIDENTIAL = 'RESIDENTIAL';
    const _UNCONFIRMED_BUSINESS = 'UNCONFIRMED_BUSINESS';
    const _UNCONFIRMED_RESIDENCE = 'UNCONFIRMED_RESIDENCE';
    const _UNKNOWN = 'UNKNOWN';
}
