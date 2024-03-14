<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the usage type of the data item under consideration.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class UsageType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _PRIMARY = 'PRIMARY';
    const _SECONDARY = 'SECONDARY';
}
