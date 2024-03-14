<?php

namespace FedExVendor\FedEx\CloseService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * CloseWithDocumentsProcessingOptionType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Close Service
 */
class CloseWithDocumentsProcessingOptionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ERROR_IF_OPEN_SHIPMENTS_FOUND = 'ERROR_IF_OPEN_SHIPMENTS_FOUND';
    const _WARNING_IF_OPEN_SHIPMENTS_FOUND = 'WARNING_IF_OPEN_SHIPMENTS_FOUND';
}
