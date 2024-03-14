<?php

namespace FedExVendor\FedEx\DGDSService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PhysicalFormType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 */
class PhysicalFormType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _GAS = 'GAS';
    const _LIQUID = 'LIQUID';
    const _SOLID = 'SOLID';
    const _SPECIAL = 'SPECIAL';
}
