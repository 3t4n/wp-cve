<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Payment
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 *
 * @property \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\PaymentType|string $PaymentType
 * @property Payor $Payor
 * @property EPaymentDetail $EPaymentDetail
 * @property CreditCard $CreditCard
 * @property CreditCardTransactionDetail $CreditCardTransactionDetail
 * @property Money $Amount
 */
class Payment extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'Payment';
    /**
     * Set PaymentType
     *
     * @param \FedEx\ValidationAvailabilityAndCommitmentService\SimpleType\PaymentType|string $paymentType
     * @return $this
     */
    public function setPaymentType($paymentType)
    {
        $this->values['PaymentType'] = $paymentType;
        return $this;
    }
    /**
     * Set Payor
     *
     * @param Payor $payor
     * @return $this
     */
    public function setPayor(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\Payor $payor)
    {
        $this->values['Payor'] = $payor;
        return $this;
    }
    /**
     * FOR FEDEX INTERNAL USE ONLY
     *
     * @param EPaymentDetail $ePaymentDetail
     * @return $this
     */
    public function setEPaymentDetail(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\EPaymentDetail $ePaymentDetail)
    {
        $this->values['EPaymentDetail'] = $ePaymentDetail;
        return $this;
    }
    /**
     * Set CreditCard
     *
     * @param CreditCard $creditCard
     * @return $this
     */
    public function setCreditCard(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\CreditCard $creditCard)
    {
        $this->values['CreditCard'] = $creditCard;
        return $this;
    }
    /**
     * Set CreditCardTransactionDetail
     *
     * @param CreditCardTransactionDetail $creditCardTransactionDetail
     * @return $this
     */
    public function setCreditCardTransactionDetail(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\CreditCardTransactionDetail $creditCardTransactionDetail)
    {
        $this->values['CreditCardTransactionDetail'] = $creditCardTransactionDetail;
        return $this;
    }
    /**
     * Set Amount
     *
     * @param Money $amount
     * @return $this
     */
    public function setAmount(\FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\ComplexType\Money $amount)
    {
        $this->values['Amount'] = $amount;
        return $this;
    }
}
