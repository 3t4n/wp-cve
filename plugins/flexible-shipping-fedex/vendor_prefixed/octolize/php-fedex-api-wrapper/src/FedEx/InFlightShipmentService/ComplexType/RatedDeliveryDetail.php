<?php

namespace FedExVendor\FedEx\InFlightShipmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * RatedDeliveryDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  In Flight Shipment Service
 *
 * @property int $TotalPieces
 * @property Money $TotalNetCharge
 * @property Money $TotalSurcharges
 * @property Surcharge[] $Surcharges
 */
class RatedDeliveryDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'RatedDeliveryDetail';
    /**
     * Set TotalPieces
     *
     * @param int $totalPieces
     * @return $this
     */
    public function setTotalPieces($totalPieces)
    {
        $this->values['TotalPieces'] = $totalPieces;
        return $this;
    }
    /**
     * Total charges for this shipment.
     *
     * @param Money $totalNetCharge
     * @return $this
     */
    public function setTotalNetCharge(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\Money $totalNetCharge)
    {
        $this->values['TotalNetCharge'] = $totalNetCharge;
        return $this;
    }
    /**
     * Total surcharges for this shipment.
     *
     * @param Money $totalSurcharges
     * @return $this
     */
    public function setTotalSurcharges(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\Money $totalSurcharges)
    {
        $this->values['TotalSurcharges'] = $totalSurcharges;
        return $this;
    }
    /**
     * Specifies the details about surcharges.
     *
     * @param Surcharge[] $surcharges
     * @return $this
     */
    public function setSurcharges(array $surcharges)
    {
        $this->values['Surcharges'] = $surcharges;
        return $this;
    }
}
