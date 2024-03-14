<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identifies types of scales used in weighing Freight shipments
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class WeighingScaleType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _FEDEX_SCALE = 'FEDEX_SCALE';
    const _PUBLIC_SCALE = 'PUBLIC_SCALE';
}
