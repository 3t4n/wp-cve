<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies data structures that may be re-used multiple times with s single shipment.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property DangerousGoodsDetail[] $DangerousGoodsPackageConfigurations
 */
class ShipmentConfigurationData extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ShipmentConfigurationData';
    /**
     * Specifies the data that is common to dangerous goods packages in the shipment. This is populated when the shipment contains packages with identical dangerous goods commodities.
     *
     * @param DangerousGoodsDetail[] $dangerousGoodsPackageConfigurations
     * @return $this
     */
    public function setDangerousGoodsPackageConfigurations(array $dangerousGoodsPackageConfigurations)
    {
        $this->values['DangerousGoodsPackageConfigurations'] = $dangerousGoodsPackageConfigurations;
        return $this;
    }
}
