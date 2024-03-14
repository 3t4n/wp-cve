<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identifies features of service requested for a Transborder Distribution shipment.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class TransborderDistributionSpecialServiceType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _FEDEX_LTL = 'FEDEX_LTL';
}
