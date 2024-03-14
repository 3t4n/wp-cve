<?php

namespace FedExVendor\FedEx\PickupService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * PickupHistorySummaryRequest
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Pickup Service
 *
 * @property WebAuthenticationDetail $WebAuthenticationDetail
 * @property ClientDetail $ClientDetail
 * @property UserDetail $UserDetail
 * @property TransactionDetail $TransactionDetail
 * @property VersionId $Version
 * @property \FedEx\PickupService\SimpleType\PickupServiceLevelType|string $ServiceLevel
 * @property string $EarliestPickupDate
 * @property int $ResultsToSkip
 * @property int $ResultsRequested
 * @property PagingRequestDetail $PagingDetail
 * @property \FedEx\PickupService\SimpleType\CarrierCodeType|string[] $CarrierCode
 * @property \FedEx\PickupService\SimpleType\PickupType|string $PickupType
 * @property string $StandingInstructionId
 * @property PickupLookupCondition[] $Conditions
 */
class PickupHistorySummaryRequest extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'PickupHistorySummaryRequest';
    /**
     * Descriptive data to be used in authentication of the sender's identity (and right to use FedEx web services).
     *
     * @param WebAuthenticationDetail $webAuthenticationDetail
     * @return $this
     */
    public function setWebAuthenticationDetail(\FedExVendor\FedEx\PickupService\ComplexType\WebAuthenticationDetail $webAuthenticationDetail)
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
    public function setClientDetail(\FedExVendor\FedEx\PickupService\ComplexType\ClientDetail $clientDetail)
    {
        $this->values['ClientDetail'] = $clientDetail;
        return $this;
    }
    /**
     * Set UserDetail
     *
     * @param UserDetail $userDetail
     * @return $this
     */
    public function setUserDetail(\FedExVendor\FedEx\PickupService\ComplexType\UserDetail $userDetail)
    {
        $this->values['UserDetail'] = $userDetail;
        return $this;
    }
    /**
     * Set TransactionDetail
     *
     * @param TransactionDetail $transactionDetail
     * @return $this
     */
    public function setTransactionDetail(\FedExVendor\FedEx\PickupService\ComplexType\TransactionDetail $transactionDetail)
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
    public function setVersion(\FedExVendor\FedEx\PickupService\ComplexType\VersionId $version)
    {
        $this->values['Version'] = $version;
        return $this;
    }
    /**
     * Set ServiceLevel
     *
     * @param \FedEx\PickupService\SimpleType\PickupServiceLevelType|string $serviceLevel
     * @return $this
     */
    public function setServiceLevel($serviceLevel)
    {
        $this->values['ServiceLevel'] = $serviceLevel;
        return $this;
    }
    /**
     * Set EarliestPickupDate
     *
     * @param string $earliestPickupDate
     * @return $this
     */
    public function setEarliestPickupDate($earliestPickupDate)
    {
        $this->values['EarliestPickupDate'] = $earliestPickupDate;
        return $this;
    }
    /**
     * Set ResultsToSkip
     *
     * @param int $resultsToSkip
     * @return $this
     */
    public function setResultsToSkip($resultsToSkip)
    {
        $this->values['ResultsToSkip'] = $resultsToSkip;
        return $this;
    }
    /**
     * Set ResultsRequested
     *
     * @param int $resultsRequested
     * @return $this
     */
    public function setResultsRequested($resultsRequested)
    {
        $this->values['ResultsRequested'] = $resultsRequested;
        return $this;
    }
    /**
     * Set PagingDetail
     *
     * @param PagingRequestDetail $pagingDetail
     * @return $this
     */
    public function setPagingDetail(\FedExVendor\FedEx\PickupService\ComplexType\PagingRequestDetail $pagingDetail)
    {
        $this->values['PagingDetail'] = $pagingDetail;
        return $this;
    }
    /**
     * Provides the information of Carrier for which pickup history summaries needs to be provided.
     *
     * @param \FedEx\PickupService\SimpleType\CarrierCodeType[]|string[] $carrierCode
     * @return $this
     */
    public function setCarrierCode(array $carrierCode)
    {
        $this->values['CarrierCode'] = $carrierCode;
        return $this;
    }
    /**
     * Set PickupType
     *
     * @param \FedEx\PickupService\SimpleType\PickupType|string $pickupType
     * @return $this
     */
    public function setPickupType($pickupType)
    {
        $this->values['PickupType'] = $pickupType;
        return $this;
    }
    /**
     * Set StandingInstructionId
     *
     * @param string $standingInstructionId
     * @return $this
     */
    public function setStandingInstructionId($standingInstructionId)
    {
        $this->values['StandingInstructionId'] = $standingInstructionId;
        return $this;
    }
    /**
     * Set of criteria which must be satisfied by any resulting Pickup History Summary.
     *
     * @param PickupLookupCondition[] $conditions
     * @return $this
     */
    public function setConditions(array $conditions)
    {
        $this->values['Conditions'] = $conditions;
        return $this;
    }
}
