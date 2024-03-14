<?php

namespace FedExVendor\FedEx\PickupService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * NotificationFormatType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 */
class NotificationFormatType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _HTML = 'HTML';
    const _TEXT = 'TEXT';
}
