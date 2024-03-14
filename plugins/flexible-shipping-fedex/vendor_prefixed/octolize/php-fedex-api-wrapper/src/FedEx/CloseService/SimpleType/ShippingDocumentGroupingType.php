<?php

namespace FedExVendor\FedEx\CloseService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies how to organize all shipping documents of the same type.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Close Service
 */
class ShippingDocumentGroupingType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CONSOLIDATED_BY_DOCUMENT_TYPE = 'CONSOLIDATED_BY_DOCUMENT_TYPE';
    const _CONSOLIDATED_BY_IMAGE_TYPE = 'CONSOLIDATED_BY_IMAGE_TYPE';
    const _INDIVIDUAL = 'INDIVIDUAL';
}
