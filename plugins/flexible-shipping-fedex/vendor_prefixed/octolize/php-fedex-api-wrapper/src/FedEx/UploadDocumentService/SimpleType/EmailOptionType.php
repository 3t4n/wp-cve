<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * EmailOptionType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class EmailOptionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _PRODUCE_PAPERLESS_SHIPPING_FORMAT = 'PRODUCE_PAPERLESS_SHIPPING_FORMAT';
    const _SUPPRESS_ACCESS_EMAILS = 'SUPPRESS_ACCESS_EMAILS';
    const _SUPPRESS_ADDITIONAL_LANGUAGES = 'SUPPRESS_ADDITIONAL_LANGUAGES';
}
