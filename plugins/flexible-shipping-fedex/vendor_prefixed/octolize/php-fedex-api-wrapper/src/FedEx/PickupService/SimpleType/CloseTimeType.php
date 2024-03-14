<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * CloseTimeType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class CloseTimeType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CUSTOMER_SPECIFIED = 'CUSTOMER_SPECIFIED';
    const _DEFAULT = 'DEFAULT';
}
