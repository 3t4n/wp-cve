<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * The response from PMIS requests for special services data.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\NotificationSeverityType|string $HighestSeverity
 * @property Notification[] $Notifications
 * @property TransactionDetail $TransactionDetail
 * @property VersionId $Version
 * @property ShipmentSpecialServiceDescription[] $ValidShipmentSpecialServices
 * @property PackageSpecialServiceDescription[] $ValidPackageSpecialServices
 */
class GetAllSpecialServicesReply extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'GetAllSpecialServicesReply';
    /**
     * Set HighestSeverity
     *
     * @param \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\NotificationSeverityType|string $highestSeverity
     * @return $this
     */
    public function setHighestSeverity($highestSeverity)
    {
        $this->values['HighestSeverity'] = $highestSeverity;
        return $this;
    }
    /**
     * Set Notifications
     *
     * @param Notification[] $notifications
     * @return $this
     */
    public function setNotifications(array $notifications)
    {
        $this->values['Notifications'] = $notifications;
        return $this;
    }
    /**
     * Set TransactionDetail
     *
     * @param TransactionDetail $transactionDetail
     * @return $this
     */
    public function setTransactionDetail(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\TransactionDetail $transactionDetail)
    {
        $this->values['TransactionDetail'] = $transactionDetail;
        return $this;
    }
    /**
     * Set Version
     *
     * @param VersionId $version
     * @return $this
     */
    public function setVersion(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\VersionId $version)
    {
        $this->values['Version'] = $version;
        return $this;
    }
    /**
     * Set ValidShipmentSpecialServices
     *
     * @param ShipmentSpecialServiceDescription[] $validShipmentSpecialServices
     * @return $this
     */
    public function setValidShipmentSpecialServices(array $validShipmentSpecialServices)
    {
        $this->values['ValidShipmentSpecialServices'] = $validShipmentSpecialServices;
        return $this;
    }
    /**
     * Set ValidPackageSpecialServices
     *
     * @param PackageSpecialServiceDescription[] $validPackageSpecialServices
     * @return $this
     */
    public function setValidPackageSpecialServices(array $validPackageSpecialServices)
    {
        $this->values['ValidPackageSpecialServices'] = $validPackageSpecialServices;
        return $this;
    }
}
