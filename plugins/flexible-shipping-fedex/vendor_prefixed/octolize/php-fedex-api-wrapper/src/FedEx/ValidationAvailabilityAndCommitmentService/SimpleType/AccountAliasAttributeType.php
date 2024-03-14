<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * AccountAliasAttributeType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class AccountAliasAttributeType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _INCLUDE_ALIAS_ON_BOL = 'INCLUDE_ALIAS_ON_BOL';
    const _PRIMARY_ACCOUNT_ALIAS_IS_RESPONSIBLE_FOR_PAYMENT = 'PRIMARY_ACCOUNT_ALIAS_IS_RESPONSIBLE_FOR_PAYMENT';
}
