<?php

namespace FedExVendor\FedEx\ShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PurposeOfShipmentType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 */
class PurposeOfShipmentType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _GIFT = 'GIFT';
    const _NOT_SOLD = 'NOT_SOLD';
    const _PERSONAL_EFFECTS = 'PERSONAL_EFFECTS';
    const _REPAIR_AND_RETURN = 'REPAIR_AND_RETURN';
    const _SAMPLE = 'SAMPLE';
    const _SOLD = 'SOLD';
}
