<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * ConsolidatedCommodity
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property ConsolidatedCommodityDestinationDetail $DestinationDetail
 * @property Commodity $Commodity
 */
class ConsolidatedCommodity extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ConsolidatedCommodity';
    /**
     * Set DestinationDetail
     *
     * @param ConsolidatedCommodityDestinationDetail $destinationDetail
     * @return $this
     */
    public function setDestinationDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\ConsolidatedCommodityDestinationDetail $destinationDetail)
    {
        $this->values['DestinationDetail'] = $destinationDetail;
        return $this;
    }
    /**
     * Set Commodity
     *
     * @param Commodity $commodity
     * @return $this
     */
    public function setCommodity(\FedExVendor\FedEx\OpenShipService\ComplexType\Commodity $commodity)
    {
        $this->values['Commodity'] = $commodity;
        return $this;
    }
}
