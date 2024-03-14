<?php

namespace FedExVendor\FedEx\CloseService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies how the shipment close requests are grouped.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Close Service
 */
class CloseGroupingType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _MANIFEST_REFERENCE = 'MANIFEST_REFERENCE';
    const _SHIPPING_CYCLE = 'SHIPPING_CYCLE';
    const _TIME = 'TIME';
}
