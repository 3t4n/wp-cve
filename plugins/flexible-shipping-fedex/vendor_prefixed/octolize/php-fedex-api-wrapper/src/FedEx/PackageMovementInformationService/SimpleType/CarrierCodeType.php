<?php

namespace FedExVendor\FedEx\PackageMovementInformationService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identification of a FedEx operating company (transportation).
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class CarrierCodeType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _FDXE = 'FDXE';
    const _FDXG = 'FDXG';
}
