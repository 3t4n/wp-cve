<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Provides data about the consolidated commodities.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property Money $TotalCustomsValue
 * @property ConsolidatedCommodityDetail[] $ConsolidatedCommodities
 */
class ConsolidatedCommoditiesSummaryDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ConsolidatedCommoditiesSummaryDetail';
    /**
     * Set TotalCustomsValue
     *
     * @param Money $totalCustomsValue
     * @return $this
     */
    public function setTotalCustomsValue(\FedExVendor\FedEx\OpenShipService\ComplexType\Money $totalCustomsValue)
    {
        $this->values['TotalCustomsValue'] = $totalCustomsValue;
        return $this;
    }
    /**
     * Set ConsolidatedCommodities
     *
     * @param ConsolidatedCommodityDetail[] $consolidatedCommodities
     * @return $this
     */
    public function setConsolidatedCommodities(array $consolidatedCommodities)
    {
        $this->values['ConsolidatedCommodities'] = $consolidatedCommodities;
        return $this;
    }
}
