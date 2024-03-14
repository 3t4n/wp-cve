<?php

namespace FedExVendor\FedEx\CountryService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * NotificationSeverityType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Country Service
 */
class NotificationSeverityType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ERROR = 'ERROR';
    const _FAILURE = 'FAILURE';
    const _NOTE = 'NOTE';
    const _SUCCESS = 'SUCCESS';
    const _WARNING = 'WARNING';
}
