<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Documents the kind and quantity of an individual hazardous commodity in a package.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 *
 * @property ValidatedHazardousCommodityDescription $Description
 * @property HazardousCommodityQuantityDetail $Quantity
 * @property float $MassPoints
 * @property HazardousCommodityOptionDetail $Options
 * @property NetExplosiveDetail $NetExplosiveDetail
 */
class ValidatedHazardousCommodityContent extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ValidatedHazardousCommodityContent';
    /**
     * Identifies and describes an individual hazardous commodity.
     *
     * @param ValidatedHazardousCommodityDescription $description
     * @return $this
     */
    public function setDescription(\FedExVendor\FedEx\ShipService\ComplexType\ValidatedHazardousCommodityDescription $description)
    {
        $this->values['Description'] = $description;
        return $this;
    }
    /**
     * Specifies the amount of the commodity in alternate units.
     *
     * @param HazardousCommodityQuantityDetail $quantity
     * @return $this
     */
    public function setQuantity(\FedExVendor\FedEx\ShipService\ComplexType\HazardousCommodityQuantityDetail $quantity)
    {
        $this->values['Quantity'] = $quantity;
        return $this;
    }
    /**
     * The mass points are a calculation used by ADR regulations for measuring the risk of a particular hazardous commodity.
     *
     * @param float $massPoints
     * @return $this
     */
    public function setMassPoints($massPoints)
    {
        $this->values['MassPoints'] = $massPoints;
        return $this;
    }
    /**
     * Customer-provided specifications for handling individual commodities.
     *
     * @param HazardousCommodityOptionDetail $options
     * @return $this
     */
    public function setOptions(\FedExVendor\FedEx\ShipService\ComplexType\HazardousCommodityOptionDetail $options)
    {
        $this->values['Options'] = $options;
        return $this;
    }
    /**
     * The total mass of the contained explosive substances, without the mass of any casings, bullets, shells, etc.
     *
     * @param NetExplosiveDetail $netExplosiveDetail
     * @return $this
     */
    public function setNetExplosiveDetail(\FedExVendor\FedEx\ShipService\ComplexType\NetExplosiveDetail $netExplosiveDetail)
    {
        $this->values['NetExplosiveDetail'] = $netExplosiveDetail;
        return $this;
    }
}
