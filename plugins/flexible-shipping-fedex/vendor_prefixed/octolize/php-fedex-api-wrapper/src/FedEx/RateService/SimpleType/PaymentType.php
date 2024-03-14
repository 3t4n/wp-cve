<?php

namespace FedExVendor\FedEx\RateService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PaymentType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Rate Service
 */
class PaymentType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _EPAYMENT = 'EPAYMENT';
    const _SENDER = 'SENDER';
}
