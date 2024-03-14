<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * ChargeClassificationType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class ChargeClassificationType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _DUTIES_AND_TAXES = 'DUTIES_AND_TAXES';
    const _TRANSPORTATION = 'TRANSPORTATION';
}
