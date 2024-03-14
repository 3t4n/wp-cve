<?php

namespace FedExVendor\FedEx\DGDSService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * CompletedDangerousGoodsHandlingUnitGroup
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 *
 * @property string $Id
 * @property int $NumberOfHandlingUnits
 * @property DangerousGoodsHandlingUnitShippingDetail $HandlingUnitShippingDetail
 */
class CompletedDangerousGoodsHandlingUnitGroup extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CompletedDangerousGoodsHandlingUnitGroup';
    /**
     * Set Id
     *
     * @param string $id
     * @return $this
     */
    public function setId($id)
    {
        $this->values['Id'] = $id;
        return $this;
    }
    /**
     * Set NumberOfHandlingUnits
     *
     * @param int $numberOfHandlingUnits
     * @return $this
     */
    public function setNumberOfHandlingUnits($numberOfHandlingUnits)
    {
        $this->values['NumberOfHandlingUnits'] = $numberOfHandlingUnits;
        return $this;
    }
    /**
     * Set HandlingUnitShippingDetail
     *
     * @param DangerousGoodsHandlingUnitShippingDetail $handlingUnitShippingDetail
     * @return $this
     */
    public function setHandlingUnitShippingDetail(\FedExVendor\FedEx\DGDSService\ComplexType\DangerousGoodsHandlingUnitShippingDetail $handlingUnitShippingDetail)
    {
        $this->values['HandlingUnitShippingDetail'] = $handlingUnitShippingDetail;
        return $this;
    }
}
