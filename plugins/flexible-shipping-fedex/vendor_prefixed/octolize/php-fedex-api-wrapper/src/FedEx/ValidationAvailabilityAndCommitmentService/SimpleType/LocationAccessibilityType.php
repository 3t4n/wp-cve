<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Indicates how this can be accessed.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class LocationAccessibilityType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _INSIDE = 'INSIDE';
    const _OUTSIDE = 'OUTSIDE';
}
