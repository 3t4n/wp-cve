<?php

namespace FedExVendor\FedEx\OpenShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identifies the source of regulation for hazardous commodity data.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 */
class HazardousCommodityRegulationType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ADR = 'ADR';
    const _DOT = 'DOT';
    const _IATA = 'IATA';
    const _ORMD = 'ORMD';
}
