<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * RestrictionsAndPrivilegesPolicyExceptionType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class RestrictionsAndPrivilegesPolicyExceptionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _POLICIES_NOT_FOUND = 'POLICIES_NOT_FOUND';
    const _SERVICE_UNAVAILABLE = 'SERVICE_UNAVAILABLE';
}
