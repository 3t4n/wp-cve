<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PackagePackedByType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class PackagePackedByType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CUSTOMER = 'CUSTOMER';
    const _FEDEX_OFFICE = 'FEDEX_OFFICE';
}
