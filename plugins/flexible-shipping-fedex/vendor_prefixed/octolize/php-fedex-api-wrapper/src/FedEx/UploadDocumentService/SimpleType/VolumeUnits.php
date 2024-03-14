<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Units of three-dimensional volume/cubic measure.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class VolumeUnits extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CUBIC_FT = 'CUBIC_FT';
    const _CUBIC_M = 'CUBIC_M';
}
