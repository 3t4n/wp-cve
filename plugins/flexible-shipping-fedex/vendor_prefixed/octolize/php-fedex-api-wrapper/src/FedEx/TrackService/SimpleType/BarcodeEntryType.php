<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * BarcodeEntryType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class BarcodeEntryType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _MANUAL_ENTRY = 'MANUAL_ENTRY';
    const _SCAN = 'SCAN';
}
