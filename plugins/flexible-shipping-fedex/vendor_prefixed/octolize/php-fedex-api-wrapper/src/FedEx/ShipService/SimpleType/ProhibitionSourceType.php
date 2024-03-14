<?php

namespace FedExVendor\FedEx\ShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * ProhibitionSourceType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 */
class ProhibitionSourceType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _FEDEX = 'FEDEX';
    const _GOVERNMENT = 'GOVERNMENT';
}
