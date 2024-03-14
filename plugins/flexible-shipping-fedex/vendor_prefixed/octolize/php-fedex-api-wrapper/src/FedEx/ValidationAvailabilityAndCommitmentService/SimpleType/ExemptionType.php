<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * ExemptionType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class ExemptionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _EXEMPT = 'EXEMPT';
    const _NOT_EXEMPT = 'NOT_EXEMPT';
}
