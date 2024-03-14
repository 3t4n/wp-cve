<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Indicates the basis for pricing/rating Freight shipments.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class FreightTariffType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BASE_RATE = 'BASE_RATE';
    const _CUSTOMER_PRICING = 'CUSTOMER_PRICING';
    const _PRICING_EXPIRED = 'PRICING_EXPIRED';
}
