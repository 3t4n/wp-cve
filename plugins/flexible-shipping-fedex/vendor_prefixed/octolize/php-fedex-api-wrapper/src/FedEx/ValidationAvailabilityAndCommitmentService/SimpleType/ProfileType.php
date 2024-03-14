<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * ProfileType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class ProfileType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ENTERPRISE = 'ENTERPRISE';
    const _EXPRESS = 'EXPRESS';
    const _FREIGHT = 'FREIGHT';
    const _OFFICE = 'OFFICE';
    const _RECIPIENT = 'RECIPIENT';
    const _TECH_CONNECT = 'TECH_CONNECT';
    const _TRADE_NETWORKS = 'TRADE_NETWORKS';
}
