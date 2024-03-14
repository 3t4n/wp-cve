<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * CreditCardSettlementScheduleType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class CreditCardSettlementScheduleType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _SETTLE_IMMEDIATELY = 'SETTLE_IMMEDIATELY';
    const _SETTLE_NEXT_DAY = 'SETTLE_NEXT_DAY';
    const _SETTLE_ON_DELIVERY = 'SETTLE_ON_DELIVERY';
}
