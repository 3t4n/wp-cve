<?php

namespace FedExVendor\FedEx\PickupService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * PickupTagDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 *
 * @property string $ServiceType
 * @property string $PackagingType
 * @property string[] $ShipmentSpecialServices
 * @property Payment $ShippingChargesPayment
 * @property ContactAndAddress $RecipientLocation
 * @property string $RmaNumber
 * @property Money $TotalInsuredValue
 * @property PickupPackageDetail[] $PackageDetails
 */
class PickupTagDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'PickupTagDetail';
    /**
     * Set ServiceType
     *
     * @param string $serviceType
     * @return $this
     */
    public function setServiceType($serviceType)
    {
        $this->values['ServiceType'] = $serviceType;
        return $this;
    }
    /**
     * Set PackagingType
     *
     * @param string $packagingType
     * @return $this
     */
    public function setPackagingType($packagingType)
    {
        $this->values['PackagingType'] = $packagingType;
        return $this;
    }
    /**
     * Set ShipmentSpecialServices
     *
     * @param string $shipmentSpecialServices
     * @return $this
     */
    public function setShipmentSpecialServices($shipmentSpecialServices)
    {
        $this->values['ShipmentSpecialServices'] = $shipmentSpecialServices;
        return $this;
    }
    /**
     * Set ShippingChargesPayment
     *
     * @param Payment $shippingChargesPayment
     * @return $this
     */
    public function setShippingChargesPayment(\FedExVendor\FedEx\PickupService\ComplexType\Payment $shippingChargesPayment)
    {
        $this->values['ShippingChargesPayment'] = $shippingChargesPayment;
        return $this;
    }
    /**
     * Set RecipientLocation
     *
     * @param ContactAndAddress $recipientLocation
     * @return $this
     */
    public function setRecipientLocation(\FedExVendor\FedEx\PickupService\ComplexType\ContactAndAddress $recipientLocation)
    {
        $this->values['RecipientLocation'] = $recipientLocation;
        return $this;
    }
    /**
     * Set RmaNumber
     *
     * @param string $rmaNumber
     * @return $this
     */
    public function setRmaNumber($rmaNumber)
    {
        $this->values['RmaNumber'] = $rmaNumber;
        return $this;
    }
    /**
     * Set TotalInsuredValue
     *
     * @param Money $totalInsuredValue
     * @return $this
     */
    public function setTotalInsuredValue(\FedExVendor\FedEx\PickupService\ComplexType\Money $totalInsuredValue)
    {
        $this->values['TotalInsuredValue'] = $totalInsuredValue;
        return $this;
    }
    /**
     * Set PackageDetails
     *
     * @param PickupPackageDetail[] $packageDetails
     * @return $this
     */
    public function setPackageDetails(array $packageDetails)
    {
        $this->values['PackageDetails'] = $packageDetails;
        return $this;
    }
}
