<?php

namespace FedExVendor\FedEx\ReturnTagService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identifies the set of severity values for a Notification.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Return Tag Service
 */
class NotificationSeverityType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ERROR = 'ERROR';
    const _FAILURE = 'FAILURE';
    const _NOTE = 'NOTE';
    const _SUCCESS = 'SUCCESS';
    const _WARNING = 'WARNING';
}
