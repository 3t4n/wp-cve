<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the reason to override address verification.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class AddressVerificationOverrideReasonType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CUSTOMER_PREFERENCE = 'CUSTOMER_PREFERENCE';
    const _CUSTOMER_PROVIDED_PROOF = 'CUSTOMER_PROVIDED_PROOF';
    const _MANUAL_VALIDATION = 'MANUAL_VALIDATION';
}
