<?php

namespace FedExVendor\FedEx\ShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * A regulation specific classification for a battery or cell.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 */
class BatteryRegulatorySubType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _IATA_SECTION_II = 'IATA_SECTION_II';
}
