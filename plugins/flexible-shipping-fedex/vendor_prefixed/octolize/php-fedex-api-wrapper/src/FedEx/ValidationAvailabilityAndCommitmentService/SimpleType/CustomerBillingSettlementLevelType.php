<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * CustomerBillingSettlementLevelType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class CustomerBillingSettlementLevelType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _INVOICE = 'INVOICE';
    const _LINE_ITEM = 'LINE_ITEM';
    const _NOT_APPLICABLE = 'NOT_APPLICABLE';
}
