<?php

namespace FedExVendor\FedEx\DGDSService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * DangerousGoodsInnerReceptacleDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Dangerous Goods Data Service
 *
 * @property PreciseQuantity $Quantity
 */
class DangerousGoodsInnerReceptacleDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'DangerousGoodsInnerReceptacleDetail';
    /**
     * Set Quantity
     *
     * @param PreciseQuantity $quantity
     * @return $this
     */
    public function setQuantity(\FedExVendor\FedEx\DGDSService\ComplexType\PreciseQuantity $quantity)
    {
        $this->values['Quantity'] = $quantity;
        return $this;
    }
}
