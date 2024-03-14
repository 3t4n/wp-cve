<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * UploadDocumentsProcessingOptionType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class UploadDocumentsProcessingOptionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _POST_SHIPMENT_UPLOAD = 'POST_SHIPMENT_UPLOAD';
    const _PRE_ETD_ENHANCEMENTS = 'PRE_ETD_ENHANCEMENTS';
    const _VALIDATE_ONLY = 'VALIDATE_ONLY';
}
