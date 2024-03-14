<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * UsmcaCertifierSpecificationType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class UsmcaCertifierSpecificationType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _EXPORTER = 'EXPORTER';
    const _IMPORTER = 'IMPORTER';
    const _PRODUCER = 'PRODUCER';
}
