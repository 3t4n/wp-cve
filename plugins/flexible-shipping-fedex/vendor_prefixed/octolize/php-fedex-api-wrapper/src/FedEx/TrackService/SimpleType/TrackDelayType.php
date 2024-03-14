<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * TrackDelayType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class TrackDelayType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CUSTOMS = 'CUSTOMS';
    const _GENERAL = 'GENERAL';
    const _LOCAL = 'LOCAL';
    const _OPERATIONAL = 'OPERATIONAL';
    const _WEATHER = 'WEATHER';
}
