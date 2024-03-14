<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identifies account status unique to FedEx Freight.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class FreightAccountStatusType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ESTABLISHED = 'ESTABLISHED';
    const _SCHEDULED_FOR_DELETION = 'SCHEDULED_FOR_DELETION';
    const _UNESTABLISHED = 'UNESTABLISHED';
}
