<?php

namespace FedExVendor\FedEx\ShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * RateRequestType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 */
class RateRequestType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CUSTOM = 'CUSTOM';
    const _INCENTIVE = 'INCENTIVE';
    const _LIST = 'LIST';
    const _NONE = 'NONE';
    const _PREFERRED = 'PREFERRED';
    const _RATED = 'RATED';
}
