<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * GetModifyOpenShipmentResultsReply
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property \FedEx\OpenShipService\SimpleType\NotificationSeverityType|string $HighestSeverity
 * @property Notification[] $Notifications
 * @property TransactionDetail $TransactionDetail
 * @property VersionId $Version
 * @property CompletedShipmentDetail $CompletedShipmentDetail
 * @property ShippingDocument[] $ErrorLabels
 * @property ShipmentAdvisoryDetail $AdvisoryDetail
 */
class GetModifyOpenShipmentResultsReply extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'GetModifyOpenShipmentResultsReply';
    /**
     * Set HighestSeverity
     *
     * @param \FedEx\OpenShipService\SimpleType\NotificationSeverityType|string $highestSeverity
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
    public function setTransactionDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\TransactionDetail $transactionDetail)
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
    public function setVersion(\FedExVendor\FedEx\OpenShipService\ComplexType\VersionId $version)
    {
        $this->values['Version'] = $version;
        return $this;
    }
    /**
     * Set CompletedShipmentDetail
     *
     * @param CompletedShipmentDetail $completedShipmentDetail
     * @return $this
     */
    public function setCompletedShipmentDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\CompletedShipmentDetail $completedShipmentDetail)
    {
        $this->values['CompletedShipmentDetail'] = $completedShipmentDetail;
        return $this;
    }
    /**
     * Set ErrorLabels
     *
     * @param ShippingDocument[] $errorLabels
     * @return $this
     */
    public function setErrorLabels(array $errorLabels)
    {
        $this->values['ErrorLabels'] = $errorLabels;
        return $this;
    }
    /**
     * Set AdvisoryDetail
     *
     * @param ShipmentAdvisoryDetail $advisoryDetail
     * @return $this
     */
    public function setAdvisoryDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\ShipmentAdvisoryDetail $advisoryDetail)
    {
        $this->values['AdvisoryDetail'] = $advisoryDetail;
        return $this;
    }
}
