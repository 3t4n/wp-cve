<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Indicates the status of a package inspection performed by an agent.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class InspectionStatusType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CUSTOMER_REFUSED_INSPECTION = 'CUSTOMER_REFUSED_INSPECTION';
    const _INSPECTED = 'INSPECTED';
    const _NOT_INSPECTED = 'NOT_INSPECTED';
}
