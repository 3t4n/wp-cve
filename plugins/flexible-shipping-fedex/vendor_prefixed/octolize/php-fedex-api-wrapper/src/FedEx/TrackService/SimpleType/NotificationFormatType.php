<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * NotificationFormatType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class NotificationFormatType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _HTML = 'HTML';
    const _TEXT = 'TEXT';
}
