<?php

namespace FedExVendor\FedEx\OpenShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * ConsolidationSpecialServiceType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 */
class ConsolidationSpecialServiceType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BROKER_SELECT_OPTION = 'BROKER_SELECT_OPTION';
    const _PRIORITY_ALERT = 'PRIORITY_ALERT';
    const _SATURDAY_PICKUP = 'SATURDAY_PICKUP';
}
