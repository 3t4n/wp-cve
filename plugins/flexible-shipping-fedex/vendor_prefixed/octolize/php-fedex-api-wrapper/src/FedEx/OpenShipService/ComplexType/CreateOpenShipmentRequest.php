<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * CreateOpenShipmentRequest
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property WebAuthenticationDetail $WebAuthenticationDetail
 * @property ClientDetail $ClientDetail
 * @property TransactionDetail $TransactionDetail
 * @property VersionId $Version
 * @property AsynchronousProcessingOptionsRequested $AsynchronousProcessingOptions
 * @property string $Index
 * @property ConsolidationKey $ConsolidationKey
 * @property \FedEx\OpenShipService\SimpleType\CreateOpenShipmentActionType|string[] $Actions
 * @property RequestedShipment $RequestedShipment
 */
class CreateOpenShipmentRequest extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CreateOpenShipmentRequest';
    /**
     * Descriptive data to be used in authentication of the sender's identity (and right to use FedEx web services).
     *
     * @param WebAuthenticationDetail $webAuthenticationDetail
     * @return $this
     */
    public function setWebAuthenticationDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\WebAuthenticationDetail $webAuthenticationDetail)
    {
        $this->values['WebAuthenticationDetail'] = $webAuthenticationDetail;
        return $this;
    }
    /**
     * Set ClientDetail
     *
     * @param ClientDetail $clientDetail
     * @return $this
     */
    public function setClientDetail(\FedExVendor\FedEx\OpenShipService\ComplexType\ClientDetail $clientDetail)
    {
        $this->values['ClientDetail'] = $clientDetail;
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
     * This is used to specify processing options related to synchronous or asynchronous processing.
     *
     * @param AsynchronousProcessingOptionsRequested $asynchronousProcessingOptions
     * @return $this
     */
    public function setAsynchronousProcessingOptions(\FedExVendor\FedEx\OpenShipService\ComplexType\AsynchronousProcessingOptionsRequested $asynchronousProcessingOptions)
    {
        $this->values['AsynchronousProcessingOptions'] = $asynchronousProcessingOptions;
        return $this;
    }
    /**
     * Customer-assigned identifier for this shipment (must be unique for stand-alone open shipments, or unique within consolidation if consolidation key is provided).
     *
     * @param string $index
     * @return $this
     */
    public function setIndex($index)
    {
        $this->values['Index'] = $index;
        return $this;
    }
    /**
     * If provided, identifies the consolidation to which this open shipment should be added after successful creation.
     *
     * @param ConsolidationKey $consolidationKey
     * @return $this
     */
    public function setConsolidationKey(\FedExVendor\FedEx\OpenShipService\ComplexType\ConsolidationKey $consolidationKey)
    {
        $this->values['ConsolidationKey'] = $consolidationKey;
        return $this;
    }
    /**
     * Specifies the optional actions to be performed during the creation of this open shipment.
     *
     * @param \FedEx\OpenShipService\SimpleType\CreateOpenShipmentActionType[]|string[] $actions
     * @return $this
     */
    public function setActions(array $actions)
    {
        $this->values['Actions'] = $actions;
        return $this;
    }
    /**
     * Set RequestedShipment
     *
     * @param RequestedShipment $requestedShipment
     * @return $this
     */
    public function setRequestedShipment(\FedExVendor\FedEx\OpenShipService\ComplexType\RequestedShipment $requestedShipment)
    {
        $this->values['RequestedShipment'] = $requestedShipment;
        return $this;
    }
}
