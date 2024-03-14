<?php

namespace FedExVendor\FedEx\TrackService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * TrackOtherIdentifierDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 *
 * @property TrackPackageIdentifier $PackageIdentifier
 * @property string $TrackingNumberUniqueIdentifier
 * @property \FedEx\TrackService\SimpleType\CarrierCodeType|string $CarrierCode
 */
class TrackOtherIdentifierDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'TrackOtherIdentifierDetail';
    /**
     * Set PackageIdentifier
     *
     * @param TrackPackageIdentifier $packageIdentifier
     * @return $this
     */
    public function setPackageIdentifier(\FedExVendor\FedEx\TrackService\ComplexType\TrackPackageIdentifier $packageIdentifier)
    {
        $this->values['PackageIdentifier'] = $packageIdentifier;
        return $this;
    }
    /**
     * Set TrackingNumberUniqueIdentifier
     *
     * @param string $trackingNumberUniqueIdentifier
     * @return $this
     */
    public function setTrackingNumberUniqueIdentifier($trackingNumberUniqueIdentifier)
    {
        $this->values['TrackingNumberUniqueIdentifier'] = $trackingNumberUniqueIdentifier;
        return $this;
    }
    /**
     * Set CarrierCode
     *
     * @param \FedEx\TrackService\SimpleType\CarrierCodeType|string $carrierCode
     * @return $this
     */
    public function setCarrierCode($carrierCode)
    {
        $this->values['CarrierCode'] = $carrierCode;
        return $this;
    }
}
