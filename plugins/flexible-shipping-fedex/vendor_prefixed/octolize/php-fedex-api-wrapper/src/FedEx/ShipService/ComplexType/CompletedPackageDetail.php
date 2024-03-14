<?php

namespace FedExVendor\FedEx\ShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * CompletedPackageDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Ship Service
 *
 * @property int $SequenceNumber
 * @property TrackingId[] $TrackingIds
 * @property int $GroupNumber
 * @property \FedEx\ShipService\SimpleType\OversizeClassType|string $OversizeClass
 * @property PackageRating $PackageRating
 * @property SpecialServiceDescription[] $SpecialServiceDescriptions
 * @property PackageOperationalDetail $OperationalDetail
 * @property ShippingDocument $Label
 * @property ShippingDocument[] $PackageDocuments
 * @property CodReturnPackageDetail $CodReturnDetail
 * @property \FedEx\ShipService\SimpleType\SignatureOptionType|string $SignatureOption
 * @property Weight $DryIceWeight
 * @property CompletedHazardousPackageDetail $HazardousPackageDetail
 */
class CompletedPackageDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CompletedPackageDetail';
    /**
     * Set SequenceNumber
     *
     * @param int $sequenceNumber
     * @return $this
     */
    public function setSequenceNumber($sequenceNumber)
    {
        $this->values['SequenceNumber'] = $sequenceNumber;
        return $this;
    }
    /**
     * Set TrackingIds
     *
     * @param TrackingId[] $trackingIds
     * @return $this
     */
    public function setTrackingIds(array $trackingIds)
    {
        $this->values['TrackingIds'] = $trackingIds;
        return $this;
    }
    /**
     * An identifier of each group of identical packages.
     *
     * @param int $groupNumber
     * @return $this
     */
    public function setGroupNumber($groupNumber)
    {
        $this->values['GroupNumber'] = $groupNumber;
        return $this;
    }
    /**
     * Set OversizeClass
     *
     * @param \FedEx\ShipService\SimpleType\OversizeClassType|string $oversizeClass
     * @return $this
     */
    public function setOversizeClass($oversizeClass)
    {
        $this->values['OversizeClass'] = $oversizeClass;
        return $this;
    }
    /**
     * All package-level rating data for this package, which may include data for multiple rate types.
     *
     * @param PackageRating $packageRating
     * @return $this
     */
    public function setPackageRating(\FedExVendor\FedEx\ShipService\ComplexType\PackageRating $packageRating)
    {
        $this->values['PackageRating'] = $packageRating;
        return $this;
    }
    /**
     * Set SpecialServiceDescriptions
     *
     * @param SpecialServiceDescription[] $specialServiceDescriptions
     * @return $this
     */
    public function setSpecialServiceDescriptions(array $specialServiceDescriptions)
    {
        $this->values['SpecialServiceDescriptions'] = $specialServiceDescriptions;
        return $this;
    }
    /**
     * Set OperationalDetail
     *
     * @param PackageOperationalDetail $operationalDetail
     * @return $this
     */
    public function setOperationalDetail(\FedExVendor\FedEx\ShipService\ComplexType\PackageOperationalDetail $operationalDetail)
    {
        $this->values['OperationalDetail'] = $operationalDetail;
        return $this;
    }
    /**
     * Set Label
     *
     * @param ShippingDocument $label
     * @return $this
     */
    public function setLabel(\FedExVendor\FedEx\ShipService\ComplexType\ShippingDocument $label)
    {
        $this->values['Label'] = $label;
        return $this;
    }
    /**
     * All package-level shipping documents (other than labels and barcodes). For use in loads after January, 2008.
     *
     * @param ShippingDocument[] $packageDocuments
     * @return $this
     */
    public function setPackageDocuments(array $packageDocuments)
    {
        $this->values['PackageDocuments'] = $packageDocuments;
        return $this;
    }
    /**
     * Specifies the information associated with this package that has COD special service in a ground shipment.
     *
     * @param CodReturnPackageDetail $codReturnDetail
     * @return $this
     */
    public function setCodReturnDetail(\FedExVendor\FedEx\ShipService\ComplexType\CodReturnPackageDetail $codReturnDetail)
    {
        $this->values['CodReturnDetail'] = $codReturnDetail;
        return $this;
    }
    /**
     * Actual signature option applied, to allow for cases in which the original value conflicted with other service features in the shipment.
     *
     * @param \FedEx\ShipService\SimpleType\SignatureOptionType|string $signatureOption
     * @return $this
     */
    public function setSignatureOption($signatureOption)
    {
        $this->values['SignatureOption'] = $signatureOption;
        return $this;
    }
    /**
     * Set DryIceWeight
     *
     * @param Weight $dryIceWeight
     * @return $this
     */
    public function setDryIceWeight(\FedExVendor\FedEx\ShipService\ComplexType\Weight $dryIceWeight)
    {
        $this->values['DryIceWeight'] = $dryIceWeight;
        return $this;
    }
    /**
     * Documents the kinds and quantities of all hazardous commodities in the current package, using updated hazardous commodity description data.
     *
     * @param CompletedHazardousPackageDetail $hazardousPackageDetail
     * @return $this
     */
    public function setHazardousPackageDetail(\FedExVendor\FedEx\ShipService\ComplexType\CompletedHazardousPackageDetail $hazardousPackageDetail)
    {
        $this->values['HazardousPackageDetail'] = $hazardousPackageDetail;
        return $this;
    }
}
