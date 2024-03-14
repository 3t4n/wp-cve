<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * CreditCardType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class CreditCardType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _AMEX = 'AMEX';
    const _DANKORT = 'DANKORT';
    const _DINERS = 'DINERS';
    const _DISCOVER = 'DISCOVER';
    const _JCB = 'JCB';
    const _MASTERCARD = 'MASTERCARD';
    const _VISA = 'VISA';
}
