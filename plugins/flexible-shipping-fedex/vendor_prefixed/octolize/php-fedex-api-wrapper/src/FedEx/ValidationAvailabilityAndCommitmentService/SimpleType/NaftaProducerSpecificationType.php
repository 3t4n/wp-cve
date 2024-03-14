<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * NaftaProducerSpecificationType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class NaftaProducerSpecificationType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _AVAILABLE_UPON_REQUEST = 'AVAILABLE_UPON_REQUEST';
    const _MULTIPLE_SPECIFIED = 'MULTIPLE_SPECIFIED';
    const _SAME = 'SAME';
    const _SINGLE_SPECIFIED = 'SINGLE_SPECIFIED';
    const _UNKNOWN = 'UNKNOWN';
}
