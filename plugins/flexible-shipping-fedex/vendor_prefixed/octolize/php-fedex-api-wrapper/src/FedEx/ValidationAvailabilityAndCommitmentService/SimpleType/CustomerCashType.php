<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Indication of cash-only account standing.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class CustomerCashType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BAD = 'BAD';
    const _GOOD = 'GOOD';
}
