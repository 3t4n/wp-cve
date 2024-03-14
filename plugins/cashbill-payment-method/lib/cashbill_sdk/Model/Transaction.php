<?php
/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */
namespace CashBill\Payments\Model;

use CashBill\Payments\Interfaces\Data;
use CashBill\Payments\Services\CashBillTransactionException;

class Transaction implements Data
{
    
    /**
     *
     * @var string $title
     * @var float $amount_value
     * @var string $amount_currencyCode
     * @var string $returnUrl
     * @var string $description
     * @var string $negativeReturnUrl
     * @var string $additionalData
     * @var string $paymentChannel
     * @var string $languageCode
     * @var string $referer
     * @var Options $options
     */
    public $title;
    public $amount;
    public $returnUrl = '';
    public $description;
    public $negativeReturnUrl;
    public $additionalData;
    public $paymentChannel;
    public $languageCode;
    public $referer;
    
    /**
     *
     * @param string $title
     * @param float $amount_value
     * @param string $amount_currencyCode
     * @param string $description
     * @param string $additionalData
     * @throws CashBillTransactionException
     */
    public function __construct($title, $amount, $description, $additionalData)
    {
        if (empty($title) || empty($amount) || empty($description) || empty($additionalData)) {
            throw new CashBillTransactionException("Required params not set");
        }

        $this->title = $title;
        $this->amount = $amount;
        $this->description = $description;
        $this->additionalData = $additionalData;
    }
    
    /**
     *
     * @param string $returnUrl
     * @param string $negativeReturnUrl
     */
    public function setReturnUrls($returnUrl, $negativeReturnUrl = '')
    {
        $this->returnUrl = $returnUrl;
        $this->negativeReturnUrl = $negativeReturnUrl;
    }
    
    /**
     *
     * @param string $paymentChannel
     */
    public function setPaymentChannel($paymentChannel)
    {
        $this->paymentChannel = $paymentChannel;
    }
    
    /**
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->languageCode = $language;
    }
    
    /**
     *
     * @param string $referer
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
    }

    /**
     *
     * @see CashBillData::toArray()
     */
    public function toArray()
    {
        $array = array();
        foreach ($this as $key => $value) {
            if (!is_object($value)) {
                $array [str_replace("_", ".", $key)] = $value;
            } else {
                $array = array_merge($array, $value->toArray());
            }
        }
        
        return $array;
    }
}
