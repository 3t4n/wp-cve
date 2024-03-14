<?php

namespace FedExVendor\FedEx\DGDSService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * This attribute type identifies characteristics of a dangerous goods regulation that influence how FedEx systems process dangerous goods shipments.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 */
class DangerousGoodsRegulationAttributeType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _DRY_ICE_DECLARATION_REQUIRED = 'DRY_ICE_DECLARATION_REQUIRED';
}
