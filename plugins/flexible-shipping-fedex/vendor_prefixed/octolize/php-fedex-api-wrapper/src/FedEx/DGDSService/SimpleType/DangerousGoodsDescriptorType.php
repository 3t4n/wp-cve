<?php

namespace FedExVendor\FedEx\DGDSService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * FEDEX INTERNAL USE ONLY: Describes the characteristics of the dangerous goods inferred from the commodity data.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 */
class DangerousGoodsDescriptorType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ALCOHOLIC_BEVERAGE = 'ALCOHOLIC_BEVERAGE';
    const _DRY_ICE = 'DRY_ICE';
    const _EMERGENCY_CONTACT_PHONE_REQUIRED = 'EMERGENCY_CONTACT_PHONE_REQUIRED';
    const _EXCEPTED_QUANTITIES = 'EXCEPTED_QUANTITIES';
    const _INFECTIOUS_SUBSTANCE = 'INFECTIOUS_SUBSTANCE';
    const _RADIOACTIVE = 'RADIOACTIVE';
}
