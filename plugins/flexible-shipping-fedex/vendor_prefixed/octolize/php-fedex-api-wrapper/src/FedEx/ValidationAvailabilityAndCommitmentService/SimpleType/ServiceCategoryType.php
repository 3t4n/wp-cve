<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * ServiceCategoryType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class ServiceCategoryType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _EXPRESS_FREIGHT = 'EXPRESS_FREIGHT';
    const _EXPRESS_PARCEL = 'EXPRESS_PARCEL';
    const _GROUND_HOME_DELIVERY = 'GROUND_HOME_DELIVERY';
}
