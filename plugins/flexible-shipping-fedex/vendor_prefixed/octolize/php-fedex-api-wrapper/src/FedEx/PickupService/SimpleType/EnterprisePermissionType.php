<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * EnterprisePermissionType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class EnterprisePermissionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ALLOWED = 'ALLOWED';
    const _ALLOWED_BY_EXCEPTION = 'ALLOWED_BY_EXCEPTION';
    const _DISALLOWED = 'DISALLOWED';
}
