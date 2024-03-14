<?php

namespace FedExVendor\FedEx\OpenShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * ProhibitionStatusType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 */
class ProhibitionStatusType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _PROHIBITED = 'PROHIBITED';
    const _WAIVED = 'WAIVED';
}
