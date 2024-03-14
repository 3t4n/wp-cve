<?php

namespace FedExVendor\FedEx\OpenShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * AsynchronousProcessingResultType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 */
class AsynchronousProcessingResultType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ASYNCHRONOUSLY_PROCESSED = 'ASYNCHRONOUSLY_PROCESSED';
    const _SYNCHRONOUSLY_PROCESSED = 'SYNCHRONOUSLY_PROCESSED';
}
