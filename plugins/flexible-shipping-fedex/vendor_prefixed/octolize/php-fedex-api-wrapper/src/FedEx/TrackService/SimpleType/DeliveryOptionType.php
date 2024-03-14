<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the different option types for delivery.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class DeliveryOptionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _INDIRECT_SIGNATURE_RELEASE = 'INDIRECT_SIGNATURE_RELEASE';
    const _REDIRECT_TO_HOLD_AT_LOCATION = 'REDIRECT_TO_HOLD_AT_LOCATION';
    const _REROUTE = 'REROUTE';
    const _RESCHEDULE = 'RESCHEDULE';
}
