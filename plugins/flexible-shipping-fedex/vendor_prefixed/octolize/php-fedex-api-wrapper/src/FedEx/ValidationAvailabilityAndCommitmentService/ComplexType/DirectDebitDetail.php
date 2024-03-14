<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * DirectDebitDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\ChargeClassificationType|string[] $ChargeClassifications
 * @property string $LegalEntity
 * @property BankAccountDetail $BankAccount
 * @property BankDetail $BankDetail
 * @property DirectDebitMandateDetail $Mandate
 */
class DirectDebitDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'DirectDebitDetail';
    /**
     * Set ChargeClassifications
     *
     * @param \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\ChargeClassificationType[]|string[] $chargeClassifications
     * @return $this
     */
    public function setChargeClassifications(array $chargeClassifications)
    {
        $this->values['ChargeClassifications'] = $chargeClassifications;
        return $this;
    }
    /**
     * This represents the legal entity that is being approved to receive direct debit funds from the customer.
     *
     * @param string $legalEntity
     * @return $this
     */
    public function setLegalEntity($legalEntity)
    {
        $this->values['LegalEntity'] = $legalEntity;
        return $this;
    }
    /**
     * Set BankAccount
     *
     * @param BankAccountDetail $bankAccount
     * @return $this
     */
    public function setBankAccount(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\BankAccountDetail $bankAccount)
    {
        $this->values['BankAccount'] = $bankAccount;
        return $this;
    }
    /**
     * Set BankDetail
     *
     * @param BankDetail $bankDetail
     * @return $this
     */
    public function setBankDetail(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\BankDetail $bankDetail)
    {
        $this->values['BankDetail'] = $bankDetail;
        return $this;
    }
    /**
     * Set Mandate
     *
     * @param DirectDebitMandateDetail $mandate
     * @return $this
     */
    public function setMandate(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\DirectDebitMandateDetail $mandate)
    {
        $this->values['Mandate'] = $mandate;
        return $this;
    }
}
