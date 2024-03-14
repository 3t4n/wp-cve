<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * ConfirmConsolidationReply
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property \FedEx\OpenShipService\SimpleType\NotificationSeverityType|string $HighestSeverity
 * @property Notification[] $Notifications
 * @property TransactionDetail $TransactionDetail
 * @property VersionId $Version
 * @property string $JobId
 * @property AsynchronousProcessingResultsDetail $AsynchronousProcessingResults
 * @property CompletedConsolidationDetail $CompletedConsolidationDetail
 */
class ConfirmConsolidationReply extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'ConfirmConsolidationReply';
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
     * Set JobId
     *
     * @param string $jobId
     * @return $this
     */
    public function setJobId($jobId)
    {
        $this->values['JobId'] = $jobId;
        return $this;
    }
    /**
     * This indicates whether the transaction was processed synchronously or asynchronously.
     *
     * @param AsynchronousProcessingResultsDetail $asynchronousProcessingResults
     * @return $this
     */
    public function setAsynchronousProcessingResults(\FedExVendor\FedEx\OpenShipService\ComplexType\AsynchronousProcessingResultsDetail $asynchronousProcessingResults)
    {
        $this->values['AsynchronousProcessingResults'] = $asynchronousProcessingResults;
        return $this;
    }
    /**
     * Set CompletedConsolidationDetail
     *
     * @param CompletedConsolidationDetail $completedConsolidationDetail
     * @return $this
     */
    public function setCompletedConsolidationDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\CompletedConsolidationDetail $completedConsolidationDetail)
    {
        $this->values['CompletedConsolidationDetail'] = $completedConsolidationDetail;
        return $this;
    }
}
