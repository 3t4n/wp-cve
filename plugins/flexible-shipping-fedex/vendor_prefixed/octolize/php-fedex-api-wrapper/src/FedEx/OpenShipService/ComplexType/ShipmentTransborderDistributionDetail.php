<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies the attributes of a shipment related to its role in a transborder distribution (consolidation).
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property TransborderDistributionSpecialServicesRequested $SpecialServicesRequested
 * @property TransborderDistributionSummaryDetail $SummaryDetail
 */
class ShipmentTransborderDistributionDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ShipmentTransborderDistributionDetail';
    /**
     * Specifies special services to be performed on this shipment as part of a transborder distribution.
     *
     * @param TransborderDistributionSpecialServicesRequested $specialServicesRequested
     * @return $this
     */
    public function setSpecialServicesRequested(\FedExVendor\FedEx\OpenShipService\ComplexType\TransborderDistributionSpecialServicesRequested $specialServicesRequested)
    {
        $this->values['SpecialServicesRequested'] = $specialServicesRequested;
        return $this;
    }
    /**
     * Provides summary totals across all CRNs in a distribution.
     *
     * @param TransborderDistributionSummaryDetail $summaryDetail
     * @return $this
     */
    public function setSummaryDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\TransborderDistributionSummaryDetail $summaryDetail)
    {
        $this->values['SummaryDetail'] = $summaryDetail;
        return $this;
    }
}
