<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies different values of eligibility status
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class EligibilityType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ELIGIBLE = 'ELIGIBLE';
    const _INELIGIBLE = 'INELIGIBLE';
    const _POSSIBLY_ELIGIBLE = 'POSSIBLY_ELIGIBLE';
}
