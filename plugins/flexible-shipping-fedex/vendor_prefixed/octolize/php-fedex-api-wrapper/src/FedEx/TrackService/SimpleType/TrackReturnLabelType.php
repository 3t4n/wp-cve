<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * TrackReturnLabelType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class TrackReturnLabelType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _EMAIL = 'EMAIL';
    const _PRINT = 'PRINT';
}
