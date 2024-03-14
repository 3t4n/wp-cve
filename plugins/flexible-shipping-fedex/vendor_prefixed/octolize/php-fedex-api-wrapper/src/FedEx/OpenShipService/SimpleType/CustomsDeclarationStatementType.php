<?php

namespace FedExVendor\FedEx\OpenShipService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * This indicates the different statements, declarations, acts, and certifications that may apply to a shipment.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 */
class CustomsDeclarationStatementType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _USMCA_LOW_VALUE = 'USMCA_LOW_VALUE';
}
