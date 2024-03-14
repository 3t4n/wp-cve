<?php

namespace FedExVendor\FedEx\UploadDocumentService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Payment
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 *
 * @property \FedEx\UploadDocumentService\SimpleType\PaymentType|string $PaymentType
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
     * @param \FedEx\UploadDocumentService\SimpleType\PaymentType|string $paymentType
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
    public function setPayor(\FedExVendor\FedEx\UploadDocumentService\ComplexType\Payor $payor)
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
    public function setEPaymentDetail(\FedExVendor\FedEx\UploadDocumentService\ComplexType\EPaymentDetail $ePaymentDetail)
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
    public function setCreditCard(\FedExVendor\FedEx\UploadDocumentService\ComplexType\CreditCard $creditCard)
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
    public function setCreditCardTransactionDetail(\FedExVendor\FedEx\UploadDocumentService\ComplexType\CreditCardTransactionDetail $creditCardTransactionDetail)
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
    public function setAmount(\FedExVendor\FedEx\UploadDocumentService\ComplexType\Money $amount)
    {
        $this->values['Amount'] = $amount;
        return $this;
    }
}
