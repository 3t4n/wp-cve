<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * WeightSource
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class WeightSource extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _MANUAL = 'MANUAL';
    const _SCALE = 'SCALE';
}
