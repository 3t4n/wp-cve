<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * TrackAdvanceNotificationStatusType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class TrackAdvanceNotificationStatusType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BACK_ON_TRACK = 'BACK_ON_TRACK';
    const _FAIL = 'FAIL';
}
