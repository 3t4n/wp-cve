<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Specifies the application that is responsible for managing the document id.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class UploadDocumentIdProducer extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CUSTOMER = 'CUSTOMER';
    const _FEDEX_CAFE = 'FEDEX_CAFE';
    const _FEDEX_CSHP = 'FEDEX_CSHP';
    const _FEDEX_FXRS = 'FEDEX_FXRS';
    const _FEDEX_GSMW = 'FEDEX_GSMW';
    const _FEDEX_GTM = 'FEDEX_GTM';
    const _FEDEX_INET = 'FEDEX_INET';
}
