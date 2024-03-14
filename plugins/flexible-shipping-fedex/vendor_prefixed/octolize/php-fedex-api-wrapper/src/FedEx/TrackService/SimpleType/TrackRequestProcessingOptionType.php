<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * TrackRequestProcessingOptionType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class TrackRequestProcessingOptionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ALLOW_PARTIAL_RESULTS = 'ALLOW_PARTIAL_RESULTS';
    const _INCLUDE_DETAILED_SCANS = 'INCLUDE_DETAILED_SCANS';
}
