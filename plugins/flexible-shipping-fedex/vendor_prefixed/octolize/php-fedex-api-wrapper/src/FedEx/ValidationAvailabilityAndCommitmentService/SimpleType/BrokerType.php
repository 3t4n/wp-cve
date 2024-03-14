<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * BrokerType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class BrokerType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _EXPORT = 'EXPORT';
    const _IMPORT = 'IMPORT';
}
