<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * CreditCardSettlementScheduleType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class CreditCardSettlementScheduleType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _SETTLE_IMMEDIATELY = 'SETTLE_IMMEDIATELY';
    const _SETTLE_NEXT_DAY = 'SETTLE_NEXT_DAY';
    const _SETTLE_ON_DELIVERY = 'SETTLE_ON_DELIVERY';
}
