<?php

namespace FedExVendor\FedEx\OpenShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PaymentType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 */
class PaymentType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ACCOUNT = 'ACCOUNT';
    const _COLLECT = 'COLLECT';
    const _EPAYMENT = 'EPAYMENT';
    const _RECIPIENT = 'RECIPIENT';
    const _SENDER = 'SENDER';
    const _THIRD_PARTY = 'THIRD_PARTY';
}
