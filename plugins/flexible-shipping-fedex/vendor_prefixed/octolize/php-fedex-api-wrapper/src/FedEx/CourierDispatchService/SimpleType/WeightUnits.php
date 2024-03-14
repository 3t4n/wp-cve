<?php

namespace FedExVendor\FedEx\CourierDispatchService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identifies the collection of units of measure that can be associated with a weight value.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Courier Dispatch Service
 */
class WeightUnits extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _KG = 'KG';
    const _LB = 'LB';
}
