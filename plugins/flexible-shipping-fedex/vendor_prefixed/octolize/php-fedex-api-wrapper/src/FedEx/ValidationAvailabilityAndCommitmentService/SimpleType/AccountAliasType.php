<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * AccountAliasType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class AccountAliasType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BILLING = 'BILLING';
    const _ENTERPRISE = 'ENTERPRISE';
    const _PRIMARY_ACCOUNT = 'PRIMARY_ACCOUNT';
}
