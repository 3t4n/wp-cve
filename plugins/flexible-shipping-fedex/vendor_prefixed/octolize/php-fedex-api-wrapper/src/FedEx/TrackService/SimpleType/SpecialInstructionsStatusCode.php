<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * SpecialInstructionsStatusCode
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class SpecialInstructionsStatusCode extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ACCEPTED = 'ACCEPTED';
    const _CANCELLED = 'CANCELLED';
    const _DENIED = 'DENIED';
    const _HELD = 'HELD';
    const _MODIFIED = 'MODIFIED';
    const _RELINQUISHED = 'RELINQUISHED';
    const _REQUESTED = 'REQUESTED';
    const _SET = 'SET';
}
