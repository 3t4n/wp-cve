<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.11.29.
 * Time: 16:38
 */
class MM_WPFS_TransactionDataService {

	const KEY_PREFIX = 'wpfs_td_';
	const REQUEST_PARAM_NAME_WPFS_TRANSACTION_DATA_KEY = 'wpfs_td_key';


	/**
	 * @param $stripeCustomer \StripeWPFS\Customer
	 * @param $sessionHash string
	 * @param $securityCode string
	 *
	 * @return MM_WPFS_MyAccountLoginTransactionData
	 */
	public static function createMyAccountLoginData( $stripeCustomer, $sessionHash, $securityCode ) {
	    $data = new MM_WPFS_MyAccountLoginTransactionData();

	    $data->setCustomerName( $stripeCustomer->name );
        $data->setCustomerEmail( $stripeCustomer->email );
        $data->setSecurityCode( $securityCode );
        $data->setSessionHash( $sessionHash );

        return $data;
    }

	/**
	 * @param $emailAddress string
	 *
	 * @return MM_WPFS_MyAccountLoginTransactionData
	 */
    public static function createMyAccountLoginDataByEmail( $emailAddress ) {
        $data = new MM_WPFS_MyAccountLoginTransactionData();

        $data->setCustomerEmail( $emailAddress );

        return $data;
    }

    /**
     * @param $emailAddress string
     *
     * @return MM_WPFS_DonationTransactionData
     */
    public static function createDonationDataByEmail( $emailAddress ) {
        $data = new MM_WPFS_DonationTransactionData();

        $data->setCustomerEmail( $emailAddress );

        return $data;
    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     *
     * @return MM_WPFS_DonationTransactionData
     */
	public static function createDonationDataByFormModel( $donationFormModel ) {
        $data = new MM_WPFS_DonationTransactionData();

        $stripeCustomerId = ! is_null( $donationFormModel->getStripeCustomer() ) ? $donationFormModel->getStripeCustomer()->id : null;

        $data->setIpAddress( $donationFormModel->getIpAddress() );
        $data->setFormName( $donationFormModel->getFormName() );
        $data->setStripePaymentIntentId( $donationFormModel->getStripePaymentIntentId() );
        $data->setStripePaymentMethodId( $donationFormModel->getStripePaymentMethodId() );
        $data->setStripeCustomerId( $stripeCustomerId );
        $data->setCustomerName( $donationFormModel->getCardHolderName() );
        $data->setCustomerEmail( $donationFormModel->getCardHolderEmail() );
        $data->setCustomerPhone( $donationFormModel->getCardHolderPhone() );
        $data->setCurrency( $donationFormModel->getForm()->currency );
        $data->setAmount( $donationFormModel->getAmount() );
        $data->setDonationFrequency( $donationFormModel->getDonationFrequency() );
        $data->setProductName( $donationFormModel->getProductName() );
        $data->setBillingName( $donationFormModel->getBillingName() );
        $data->setBillingAddress( $donationFormModel->getBillingAddress() );
        $data->setShippingName( $donationFormModel->getShippingName() );
        $data->setShippingAddress( $donationFormModel->getShippingAddress() );
        $data->setCustomInputValues( $donationFormModel->getCustomInputvalues() );
        $data->setTransactionId( $donationFormModel->getTransactionId() );
		$data->setCustomFieldsJSON( $donationFormModel->getCustomFieldsJSON() );

        return $data;
    }

    /**
     * @param $emailAddress string
     *
     * @return MM_WPFS_OneTimePaymentTransactionData
     */
    public static function createOneTimePaymentDataByEmail( $emailAddress ) {
        $transactionData = new MM_WPFS_OneTimePaymentTransactionData();

        $transactionData->setCustomerEmail( $emailAddress );

        return $transactionData;
    }


    /**
	 * @param MM_WPFS_Public_PaymentFormModel $paymentFormModel
	 *
	 * @return MM_WPFS_OneTimePaymentTransactionData
	 */
	public static function createOneTimePaymentDataByModel( $paymentFormModel ) {
        $transactionData = new MM_WPFS_OneTimePaymentTransactionData();

        $transactionData->setIpAddress( $paymentFormModel->getIpAddress() );
        $transactionData->setFormName( $paymentFormModel->getFormName() );
        $transactionData->setStripePaymentMethodId( $paymentFormModel->getStripePaymentMethodId() );
        $transactionData->setStripePaymentIntentId( $paymentFormModel->getStripePaymentIntentId() );
        $transactionData->setStripeCustomerId( ! is_null( $paymentFormModel->getStripeCustomer() ) ? $paymentFormModel->getStripeCustomer()->id : null );
        $transactionData->setCustomerEmail( $paymentFormModel->getCardHolderEmail() );
        $transactionData->setCustomerPhone( $paymentFormModel->getCardHolderPhone() );
        $transactionData->setCustomerName( $paymentFormModel->getCardHolderName() );
        $transactionData->setCurrency( $paymentFormModel->getForm()->currency );
        $transactionData->setAmount( $paymentFormModel->getAmount() );
        $transactionData->setCouponCode( $paymentFormModel->getCouponCode() );
        $transactionData->setCouponId( ! is_null( $paymentFormModel->getStripeCoupon() ? $paymentFormModel->getStripeCoupon()->id : null ));
        $transactionData->setProductName( $paymentFormModel->getProductName() );
        $transactionData->setBillingName( $paymentFormModel->getBillingName() );
        $transactionData->setBillingAddress( $paymentFormModel->getBillingAddress() );
        $transactionData->setShippingName( $paymentFormModel->getShippingName() );
        $transactionData->setShippingAddress( $paymentFormModel->getShippingAddress() );
        $transactionData->setCustomInputValues( $paymentFormModel->getCustomInputvalues() );
        $transactionData->setTransactionId( $paymentFormModel->getTransactionId() );
        $transactionData->setCustomerTaxId( $paymentFormModel->getTaxId() );
        $transactionData->setProductAmountGross( 0 );
        $transactionData->setProductAmountNet( 0 );
        $transactionData->setProductAmountTax( 0 );
        $transactionData->setProductAmountDiscount( 0 );
		$transactionData->setCustomFieldsJSON( $paymentFormModel->getCustomFieldsJSON() );

		return $transactionData;
	}

	/**
	 * @param $emailAddress string
	 *
	 * @return MM_WPFS_SaveCardTransactionData
	 */
    public static function createSaveCardDataByEmail( $emailAddress) {
        $transactionData = new MM_WPFS_SaveCardTransactionData();

        $transactionData->setCustomerEmail( $emailAddress );

        return $transactionData;
    }

	/**
	 * @param $paymentFormModel MM_WPFS_Public_PaymentFormModel
	 *
	 * @return MM_WPFS_SaveCardTransactionData
	 */
	public static function createSaveCardDataByModel( $paymentFormModel ) {
        $transactionData = new MM_WPFS_SaveCardTransactionData();

        $transactionData->setIpAddress( $paymentFormModel->getIpAddress() );
        $transactionData->setFormName( $paymentFormModel->getFormName() );
        $transactionData->setStripePaymentMethodId( $paymentFormModel->getStripePaymentMethodId() );
        $transactionData->setStripePaymentIntentId( $paymentFormModel->getStripePaymentIntentId() );
        $transactionData->setStripeCustomerId( ! is_null( $paymentFormModel->getStripeCustomer() ) ? $paymentFormModel->getStripeCustomer()->id : null );
        $transactionData->setCustomerEmail( $paymentFormModel->getCardHolderEmail() );
        $transactionData->setCustomerPhone( $paymentFormModel->getCardHolderPhone() );
        $transactionData->setCustomerName( $paymentFormModel->getCardHolderName() );
        $transactionData->setBillingName( $paymentFormModel->getBillingName() );
        $transactionData->setBillingAddress( $paymentFormModel->getBillingAddress() );
        $transactionData->setShippingName( $paymentFormModel->getShippingName() );
        $transactionData->setShippingAddress( $paymentFormModel->getShippingAddress() );
        $transactionData->setCustomInputValues( $paymentFormModel->getCustomInputvalues() );
        $transactionData->setTransactionId( $paymentFormModel->getTransactionId() );
		$transactionData->setCustomFieldsJSON( $paymentFormModel->getCustomFieldsJSON() );

        return $transactionData;
    }

    /**
     * @param $emailAddress string
     *
     * @return MM_WPFS_SubscriptionTransactionData
     */
    public static function createSubscriptionDataByEmail( $emailAddress ) {
        $transactionData = new MM_WPFS_SubscriptionTransactionData();

        $transactionData->setCustomerEmail( $emailAddress );

        return $transactionData;
    }

    /**
	 * @param MM_WPFS_Public_SubscriptionFormModel $subscriptionFormModel
	 *
	 * @return MM_WPFS_SubscriptionTransactionData
	 */
	public static function createSubscriptionDataByModel( $subscriptionFormModel) {
		$form = $subscriptionFormModel->getForm();

		$billingAddress  = null;
		$shippingAddress = null;
		if ( isset( $form->showAddress ) ) {
			$billingAddress  = 1 == $form->showAddress ? $subscriptionFormModel->getBillingAddress() : null;
			$shippingAddress = 1 == $form->showAddress ? $subscriptionFormModel->getShippingAddress() : null;
		}
		if ( isset( $form->showBillingAddress ) ) {
			$billingAddress = 1 == $form->showBillingAddress ? $subscriptionFormModel->getBillingAddress() : null;
		}
		if ( isset( $form->showShippingAddress ) ) {
			$shippingAddress = 1 == $form->showShippingAddress ? $subscriptionFormModel->getShippingAddress() : null;
		}

		$stripePlanAmount = $subscriptionFormModel->getPlanAmount();
		$stripePlanSetupFee = $subscriptionFormModel->getSetupFee();
		$stripePlanQuantity = $subscriptionFormModel->getStripePlanQuantity();

		$vatPercent                      = 0;
        $planAmountGrossComposite        = MM_WPFS_Utils::calculateGrossFromNet( $stripePlanAmount, $vatPercent );
        $planSetupFeeGrossComposite      = MM_WPFS_Utils::calculateGrossFromNet( $stripePlanSetupFee, $vatPercent );
        $planAmountGrossTotalComposite   = MM_WPFS_Utils::calculateGrossFromNet( $stripePlanQuantity * $stripePlanAmount, $vatPercent );
        $planSetupFeeGrossTotalComposite = MM_WPFS_Utils::calculateGrossFromNet( $stripePlanQuantity * $stripePlanSetupFee, $vatPercent );

        $transactionData = new MM_WPFS_SubscriptionTransactionData();

        $transactionData->setIpAddress( $subscriptionFormModel->getIpAddress() );
        $transactionData->setFormName( $subscriptionFormModel->getFormName() );
        $transactionData->setStripePaymentMethodId( $subscriptionFormModel->getStripePaymentMethodId() );
        $transactionData->setStripeCustomerId( ! is_null( $subscriptionFormModel->getStripeCustomer() ) ? $subscriptionFormModel->getStripeCustomer()->id : null );
        $transactionData->setCustomerName( $subscriptionFormModel->getCardHolderName() );
        $transactionData->setCustomerEmail( $subscriptionFormModel->getCardHolderEmail() );
        $transactionData->setCustomerPhone( $subscriptionFormModel->getCardHolderPhone() );
        $transactionData->setPlanId( $subscriptionFormModel->getStripePlanId() );
        $transactionData->setPlanName( $subscriptionFormModel->getStripePlan()->product->name );
        $transactionData->setPlanCurrency( $subscriptionFormModel->getStripePlan()->currency );
        $transactionData->setSetupFeeNetAmount( $stripePlanSetupFee );
        $transactionData->setSetupFeeGrossAmount( $planSetupFeeGrossComposite['gross'] );
        $transactionData->setSetupFeeTaxAmount( $transactionData->getSetupFeeGrossAmount() - $transactionData->getSetupFeeNetAmount() );
        $transactionData->setSetupFeeNetAmountTotal( $planSetupFeeGrossTotalComposite['net'] );
        $transactionData->setSetupFeeGrossAmountTotal( $planSetupFeeGrossTotalComposite['gross'] );
        $transactionData->setSetupFeeTaxAmountTotal( $planSetupFeeGrossTotalComposite['taxValue'] );
        $transactionData->setPlanNetAmount( $stripePlanAmount );
        $transactionData->setPlanGrossAmount( $planAmountGrossComposite['gross'] );
        $transactionData->setPlanTaxAmount( $transactionData->getPlanGrossAmount() - $transactionData->getPlanNetAmount() );
        $transactionData->setPlanFutureNetAmount( $stripePlanAmount );
        $transactionData->setPlanFutureGrossAmount( $planAmountGrossComposite['gross'] );
        $transactionData->setPlanFutureTaxAmount( $transactionData->getPlanGrossAmount() - $transactionData->getPlanNetAmount() );
        $transactionData->setPlanQuantity( $stripePlanQuantity );
        $transactionData->setPlanNetAmountTotal( $planAmountGrossTotalComposite['net'] );
        $transactionData->setPlanGrossAmountTotal( $planAmountGrossTotalComposite['gross'] );
        $transactionData->setPlanTaxAmountTotal( $planAmountGrossTotalComposite['taxValue'] );
        $transactionData->setProductName( $subscriptionFormModel->getStripePlan()->product->name );
        $transactionData->setBillingName( $subscriptionFormModel->getBillingName() );
        $transactionData->setBillingAddress( $billingAddress );
        $transactionData->setShippingName( $subscriptionFormModel->getShippingName() );
        $transactionData->setShippingAddress( $shippingAddress );
        $transactionData->setCustomInputValues( $subscriptionFormModel->getCustomInputvalues() );
        $transactionData->setCouponCode( $subscriptionFormModel->getCouponCode() );
        $transactionData->setDiscountId( $subscriptionFormModel->getStripeDiscountId() );
        $transactionData->setDiscountType( $subscriptionFormModel->getStripeDiscountType() );
        $transactionData->setMetadata( $subscriptionFormModel->getMetadata() );
        $transactionData->setBillingCycleAnchorDay( $subscriptionFormModel->getBillingAnchorDay() );
        $transactionData->setProrateUntilAnchorDay( $subscriptionFormModel->getProrateUntilAnchorDay() );
        $transactionData->setTrialPeriodDays( $subscriptionFormModel->getTrialPeriodDays() );
        $transactionData->setTransactionId( $subscriptionFormModel->getTransactionId() );
        $transactionData->setCustomerTaxId( $subscriptionFormModel->getTaxId() );
        $transactionData->setBusinessName( $subscriptionFormModel->getBuyingAsBusiness() == 1 ? $subscriptionFormModel->getBusinessName() : null );
		$transactionData->setCustomFieldsJSON( $subscriptionFormModel->getCustomFieldsJSON() );

		return $transactionData;
	}

	/**
	 * Store transaction data as a transient.
	 *
	 * @param $data MM_WPFS_FormTransactionData
	 *
	 * @return null|string
	 */
	public function store( $data ) {
		$key = $this->generateKey();
		set_transient( $key, $data, 1 * HOUR_IN_SECONDS );

		return rawurlencode( $key );
	}

	/**
	 * Generates a random key currently not in use as a transient key.
	 */
	private function generateKey() {
		$key = null;
		do {
			$key = self::KEY_PREFIX . crypt( strval( round( microtime( true ) * 1000 ) ), strval( rand() ) );
		} while ( get_transient( $key ) !== false );

		return $key;
	}

	/**
	 * @param $data_key
	 *
	 * @return bool|MM_WPFS_FormTransactionData
	 */
	public function retrieve( $data_key ) {
		if ( is_null( $data_key ) ) {
			return false;
		}
		$prefix_position = strpos( $data_key, self::KEY_PREFIX );
		if ( $prefix_position === false ) {
			return false;
		}
		if ( $prefix_position == 0 ) {
			$data = get_transient( $data_key );

			if ( $data !== false ) {
				delete_transient( $data_key );
			}

			return $data;
		} else {
			return false;
		}
	}

}


abstract class MM_WPFS_TransactionData {
    protected $customerName;
    protected $customerEmail;

    /**
     * @return mixed
     */
    public function getCustomerName() {
        return $this->customerName;
    }

    /**
     * @param mixed $customerName
     */
    public function setCustomerName( $customerName ) {
        $this->customerName = $customerName;
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail() {
        return $this->customerEmail;
    }

    /**
     * @param mixed $customerEmail
     */
    public function setCustomerEmail( $customerEmail ) {
        $this->customerEmail = $customerEmail;
    }
}


class MM_WPFS_MyAccountLoginTransactionData extends MM_WPFS_TransactionData {
    protected $securityCode;
    protected $sessionHash;

    /**
     * @return mixed
     */
    public function getSecurityCode() {
        return $this->securityCode;
    }

    /**
     * @param mixed $securityCode
     */
    public function setSecurityCode($securityCode) {
        $this->securityCode = $securityCode;
    }

    /**
     * @return mixed
     */
    public function getSessionHash() {
        return $this->sessionHash;
    }

    /**
     * @param mixed $sessionHash
     */
    public function setSessionHash($sessionHash) {
        $this->sessionHash = $sessionHash;
    }
}

abstract class MM_WPFS_FormTransactionData extends MM_WPFS_TransactionData {
    protected $ipAddress;

	protected $formName;
	protected $stripeCustomerId;
	protected $customerPhone;
	protected $billingName;
	/**
	 * @var array|null
	 */
	protected $billingAddress;
	protected $shippingName;
	/**
	 * @var array|null
	 */
	protected $shippingAddress;
	protected $customInputValues;
	protected $stripePaymentMethodId;
	protected $stripePaymentIntentId;
	/**
	 * @var array|null
	 */
	protected $metadata;
	protected $transactionId;
	/**
	 * @var string
	 */
	protected $customFieldsJSON;

    /**
     * @return mixed
     */
    public function getIpAddress() {
        return $this->ipAddress;
    }

    /**
     * @param mixed $ipAddress
     */
    public function setIpAddress( $ipAddress ) {
        $this->ipAddress = $ipAddress;
    }

	/**
	 * @return mixed
	 */
	public function getFormName() {
		return $this->formName;
	}

	/**
	 * @param mixed $formName
	 */
	public function setFormName( $formName ) {
		$this->formName = $formName;
	}

	/**
	 * @return mixed
	 */
	public function getStripeCustomerId() {
		return $this->stripeCustomerId;
	}

	/**
	 * @param mixed $stripeCustomerId
	 */
	public function setStripeCustomerId( $stripeCustomerId ) {
		$this->stripeCustomerId = $stripeCustomerId;
	}

	/**
	 * @return mixed
	 */
	public function getCustomerPhone() {
		return $this->customerPhone;
	}

	/**
	 * @param mixed $customerPhone
	 */
	public function setCustomerPhone( $customerPhone ) {
		$this->customerPhone = $customerPhone;
	}

	/**
	 * @return mixed
	 */
	public function getBillingName() {
		return $this->billingName;
	}

	/**
	 * @param mixed $billingName
	 */
	public function setBillingName( $billingName ) {
		$this->billingName = $billingName;
	}

	/**
	 * @return mixed
	 */
	public function getBillingAddress() {
		return $this->billingAddress;
	}

	/**
	 * @param array|null $billingAddress
	 */
	public function setBillingAddress( $billingAddress ) {
		$this->billingAddress = $billingAddress;
	}

	/**
	 * @return mixed
	 */
	public function getShippingName() {
		return $this->shippingName;
	}

	/**
	 * @param mixed $shippingName
	 */
	public function setShippingName( $shippingName ) {
		$this->shippingName = $shippingName;
	}

	/**
	 * @return mixed
	 */
	public function getShippingAddress() {
		return $this->shippingAddress;
	}

	/**
	 * @param mixed $shippingAddress
	 */
	public function setShippingAddress( $shippingAddress ) {
		$this->shippingAddress = $shippingAddress;
	}

	/**
	 * @return mixed
	 */
	public function getCustomInputValues() {
		return $this->customInputValues;
	}

	/**
	 * @param mixed $customInputValues
	 */
	public function setCustomInputValues( $customInputValues ) {
		$this->customInputValues = $customInputValues;
	}

	/**
	 * @return mixed
	 */
	public function getStripePaymentMethodId() {
		return $this->stripePaymentMethodId;
	}

	/**
	 * @param mixed $stripePaymentMethodId
	 */
	public function setStripePaymentMethodId( $stripePaymentMethodId ) {
		$this->stripePaymentMethodId = $stripePaymentMethodId;
	}

	/**
	 * @return mixed
	 */
	public function getStripePaymentIntentId() {
		return $this->stripePaymentIntentId;
	}

	/**
	 * @param mixed $stripePaymentIntentId
	 */
	public function setStripePaymentIntentId( $stripePaymentIntentId ) {
		$this->stripePaymentIntentId = $stripePaymentIntentId;
	}

	/**
	 * @return mixed
	 */
	public function getMetadata() {
		return $this->metadata;
	}

	/**
	 * @param mixed $metadata
	 */
	public function setMetadata( $metadata ) {
		$this->metadata = $metadata;
	}

	/**
	 * @return mixed
	 */
	public function getTransactionId() {
		return $this->transactionId;
	}

	/**
	 * @param mixed $transactionId
	 */
	public function setTransactionId( $transactionId ) {
		$this->transactionId = $transactionId;
	}

	/**
	 * @return string
	 */
	public function getCustomFieldsJSON() {
		return $this->customFieldsJSON;
	}

	/**
	 * @param string $customFieldsJSON
	 */
	public function setCustomFieldsJSON( $customFieldsJSON ) {
		$this->customFieldsJSON = $customFieldsJSON;
	}
	
}


class MM_WPFS_SaveCardTransactionData extends MM_WPFS_FormTransactionData {

}


class MM_WPFS_DonationTransactionData extends MM_WPFS_PaymentTransactionData {
    protected $donationFrequency;

    /**
     * @return mixed
     */
    public function getDonationFrequency() {
        return $this->donationFrequency;
    }

    /**
     * @param mixed $donationFrequency
     */
    public function setDonationFrequency( $donationFrequency ) {
        $this->donationFrequency = $donationFrequency;
    }
}


abstract class MM_WPFS_PaymentTransactionData extends MM_WPFS_FormTransactionData {
	protected $vatPercent;
    protected $couponCode;
    protected $invoiceUrl;
    protected $invoiceNumber;
    protected $receiptUrl;
    protected $productName;
    protected $amount;
    protected $currency;
    protected $customerTaxId;
    protected $businessName;
    protected $stripeInvoiceId;

    /**
     * @return mixed
     */
    public function getStripeInvoiceId() {
        return $this->stripeInvoiceId;
    }

    /**
     * @param mixed $stripeInvoiceId
     */
    public function setStripeInvoiceId( $stripeInvoiceId ) {
        $this->stripeInvoiceId = $stripeInvoiceId;
    }

    /**
     * @return mixed
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency( $currency ) {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount( $amount ) {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getProductName() {
        return $this->productName;
    }

    /**
     * @param mixed $productName
     */
    public function setProductName( $productName ) {
        $this->productName = $productName;
    }

    /**
     * @return mixed
     */
    public function getInvoiceUrl() {
        return $this->invoiceUrl;
    }

    /**
     * @param mixed $invoiceUrl
     */
    public function setInvoiceUrl( $invoiceUrl ) {
        $this->invoiceUrl = $invoiceUrl;
    }

	/**
	 * @return mixed
	 */
	public function getInvoiceNumber() {
		return $this->invoiceNumber;
	}

	/**
	 * @param mixed $invoiceNumber
	 */
	public function setInvoiceNumber( $invoiceNumber ) {
		$this->invoiceNumber = $invoiceNumber;
	}

	/**
	 * @return mixed
	 */
	public function getReceiptUrl() {
		return $this->receiptUrl;
	}

	/**
	 * @param mixed $receiptUrl
	 */
	public function setReceiptUrl( $receiptUrl ) {
		$this->receiptUrl = $receiptUrl;
	}

	/**
	 * @return mixed
	 */
	public function getVatPercent() {
		return $this->vatPercent;
	}

	/**
	 * @param mixed $vatPercent
	 */
	public function setVatPercent( $vatPercent ) {
		$this->vatPercent = $vatPercent;
	}

    /**
     * @return mixed
     */
    public function getCouponCode() {
        return $this->couponCode;
    }

    /**
     * @param mixed $couponCode
     */
    public function setCouponCode( $couponCode ) {
        $this->couponCode = $couponCode;
    }

    /**
     * @return mixed
     */
    public function getCustomerTaxId() {
        return $this->customerTaxId;
    }

    /**
     * @param mixed $customerTaxId
     */
    public function setCustomerTaxId($customerTaxId) {
        $this->customerTaxId = $customerTaxId;
    }

    /**
     * @return mixed
     */
    public function getBusinessName() {
        return $this->businessName;
    }

    /**
     * @param mixed $businessName
     */
    public function setBusinessName( $businessName ) {
        $this->businessName = $businessName;
    }


}

class MM_WPFS_OneTimePaymentTransactionData extends MM_WPFS_PaymentTransactionData {
    protected $productAmountGross;
    protected $productAmountNet;
    protected $productAmountTax;
    protected $productAmountDiscount;
    protected $couponId;

    /**
     * @return mixed
     */
    public function getCouponId() {
        return $this->couponId;
    }

    /**
     * @param mixed $couponId
     */
    public function setCouponId( $couponId ) {
        $this->couponId = $couponId;
    }

    /**
     * @return mixed
     */
    public function getProductAmountGross() {
        return $this->productAmountGross;
    }

    /**
     * @param mixed $productAmountGross
     */
    public function setProductAmountGross( $productAmountGross)  {
        $this->productAmountGross = $productAmountGross;
    }

    /**
     * @return mixed
     */
    public function getProductAmountNet() {
        return $this->productAmountNet;
    }

    /**
     * @param mixed $productAmountNet
     */
    public function setProductAmountNet($productAmountNet) {
        $this->productAmountNet = $productAmountNet;
    }

    /**
     * @return mixed
     */
    public function getProductAmountTax() {
        return $this->productAmountTax;
    }

    /**
     * @param mixed $productAmountTax
     */
    public function setProductAmountTax($productAmountTax ) {
        $this->productAmountTax = $productAmountTax;
    }

    /**
     * @return mixed
     */
    public function getProductAmountDiscount() {
        return $this->productAmountDiscount;
    }

    /**
     * @param mixed $productAmountDiscount
     */
    public function setProductAmountDiscount($productAmountDiscount) {
        $this->productAmountDiscount = $productAmountDiscount;
    }

}

class MM_WPFS_SubscriptionTransactionData extends MM_WPFS_PaymentTransactionData {
	protected $planId;
	protected $planName;
	protected $planCurrency;
	protected $planNetAmount;
	protected $planFutureNetAmount;
	protected $planFutureTaxAmount;
	protected $planFutureGrossAmount;
	protected $planGrossAmount;
	protected $planTaxAmount;
	protected $planAmountVATRate;
	protected $planNetAmountTotal;
	protected $planGrossAmountTotal;
	protected $planTaxAmountTotal;
	protected $setupFeeNetAmount;
	protected $setupFeeGrossAmount;
	protected $setupFeeTaxAmount;
	protected $planSetupFeeVATRate;
	protected $setupFeeNetAmountTotal;
	protected $setupFeeGrossAmountTotal;
	protected $setupFeeTaxAmountTotal;
	protected $planQuantity;
	protected $billingCycleAnchorDay;
	protected $prorateUntilAnchorDay;
	protected $trialPeriodDays;
    protected $stripeInvoiceId;
    protected $discountId;
    protected $discountType;

    /**
     * @return mixed
     */
    public function getStripeInvoiceId() {
        return $this->stripeInvoiceId;
    }

    /**
     * @param mixed $stripeInvoiceId
     */
    public function setStripeInvoiceId( $stripeInvoiceId ) {
        $this->stripeInvoiceId = $stripeInvoiceId;
    }

    /**
     * @return mixed
     */
    public function getTrialPeriodDays() {
        return $this->trialPeriodDays;
    }

    /**
     * @param mixed $trialPeriodDays
     */
    public function setTrialPeriodDays( $trialPeriodDays ) {
        $this->trialPeriodDays = $trialPeriodDays;
    }

	/**
	 * @return integer
	 */
	public function getProrateUntilAnchorDay() {
		return $this->prorateUntilAnchorDay;
	}

	/**
	 * @param integer $prorateUntilAnchorDay
	 */
	public function setProrateUntilAnchorDay( $prorateUntilAnchorDay ) {
		$this->prorateUntilAnchorDay = $prorateUntilAnchorDay;
	}

	/**
	 * @return integer
	 */
	public function getBillingCycleAnchorDay() {
		return $this->billingCycleAnchorDay;
	}

	/**
	 * @param integer $billingCycleAnchorDay
	 */
	public function setBillingCycleAnchorDay( $billingCycleAnchorDay ) {
		$this->billingCycleAnchorDay = $billingCycleAnchorDay;
	}


	public function getPlanGrossAmountAndGrossSetupFeeTotal() {
		return $this->planGrossAmountTotal + $this->setupFeeGrossAmountTotal;
	}

	/**
	 * @return mixed
	 */
	public function getPlanNetAmountTotal() {
		return $this->planNetAmountTotal;
	}

	/**
	 * @param mixed $planNetAmountTotal
	 */
	public function setPlanNetAmountTotal( $planNetAmountTotal ) {
		$this->planNetAmountTotal = $planNetAmountTotal;
	}

	/**
	 * @return mixed
	 */
	public function getPlanGrossAmountTotal() {
		return $this->planGrossAmountTotal;
	}

	/**
	 * @param mixed $planGrossAmountTotal
	 */
	public function setPlanGrossAmountTotal( $planGrossAmountTotal ) {
		$this->planGrossAmountTotal = $planGrossAmountTotal;
	}

	/**
	 * @return mixed
	 */
	public function getPlanTaxAmountTotal() {
		return $this->planTaxAmountTotal;
	}

	/**
	 * @param mixed $planTaxAmountTotal
	 */
	public function setPlanTaxAmountTotal($planTaxAmountTotal ) {
		$this->planTaxAmountTotal = $planTaxAmountTotal;
	}

	/**
	 * @return mixed
	 */
	public function getSetupFeeNetAmountTotal() {
		return $this->setupFeeNetAmountTotal;
	}

	/**
	 * @param mixed $setupFeeNetAmountTotal
	 */
	public function setSetupFeeNetAmountTotal($setupFeeNetAmountTotal ) {
		$this->setupFeeNetAmountTotal = $setupFeeNetAmountTotal;
	}

	/**
	 * @return mixed
	 */
	public function getSetupFeeGrossAmountTotal() {
		return $this->setupFeeGrossAmountTotal;
	}

	/**
	 * @param mixed $setupFeeGrossAmountTotal
	 */
	public function setSetupFeeGrossAmountTotal($setupFeeGrossAmountTotal ) {
		$this->setupFeeGrossAmountTotal = $setupFeeGrossAmountTotal;
	}

	/**
	 * @return mixed
	 */
	public function getSetupFeeTaxAmountTotal() {
		return $this->setupFeeTaxAmountTotal;
	}

	/**
	 * @param mixed $setupFeeTaxAmountTotal
	 */
	public function setSetupFeeTaxAmountTotal($setupFeeTaxAmountTotal ) {
		$this->setupFeeTaxAmountTotal = $setupFeeTaxAmountTotal;
	}

	/**
	 * @return mixed
	 */
	public function getPlanId() {
		return $this->planId;
	}

	/**
	 * @param mixed $planId
	 */
	public function setPlanId( $planId ) {
		$this->planId = $planId;
	}

	/**
	 * @return mixed
	 */
	public function getPlanName() {
		return $this->planName;
	}

	/**
	 * @param mixed $planName
	 */
	public function setPlanName( $planName ) {
		$this->planName = $planName;
	}

	/**
	 * @return mixed
	 */
	public function getPlanCurrency() {
		return $this->planCurrency;
	}

	/**
	 * @param mixed $planCurrency
	 */
	public function setPlanCurrency( $planCurrency ) {
		$this->planCurrency = $planCurrency;
	}

	/**
	 * @return mixed
	 */
	public function getPlanNetAmount() {
		return $this->planNetAmount;
	}

	/**
	 * @param mixed $planNetAmount
	 */
	public function setPlanNetAmount( $planNetAmount ) {
		$this->planNetAmount = $planNetAmount;
	}

	/**
	 * @return mixed
	 */
	public function getPlanFutureNetAmount() {
		return $this->planFutureNetAmount;
	}

	/**
	 * @param mixed $planFutureNetAmount
	 */
	public function setPlanFutureNetAmount( $planFutureNetAmount ) {
		$this->planFutureNetAmount = $planFutureNetAmount;
	}

	/**
	 * @return mixed
	 */
	public function getPlanFutureTaxAmount() {
		return $this->planFutureTaxAmount;
	}
	/**
	 * @param mixed $planFutureTaxAmount
	 */
	public function setPlanFutureTaxAmount( $planFutureTaxAmount ) {
		$this->planFutureTaxAmount = $planFutureTaxAmount;
	}

	/**
	 * @return mixed
	 */
	public function getPlanFutureGrossAmount() {
		return $this->planFutureGrossAmount;
	}
	/**
	 * @param mixed $planFutureGrossAmount
	 */
	public function setPlanFutureGrossAmount( $planFutureGrossAmount ) {
		$this->planFutureGrossAmount = $planFutureGrossAmount;
	}

	/**
	 * @return mixed
	 */
	public function getPlanGrossAmount() {
		return $this->planGrossAmount;
	}

	/**
	 * @param mixed $planGrossAmount
	 */
	public function setPlanGrossAmount( $planGrossAmount ) {
		$this->planGrossAmount = $planGrossAmount;
	}

	/**
	 * @return mixed
	 */
	public function getPlanTaxAmount() {
		return $this->planTaxAmount;
	}

	/**
	 * @param mixed $planTaxAmount
	 */
	public function setPlanTaxAmount($planTaxAmount ) {
		$this->planTaxAmount = $planTaxAmount;
	}

	/**
	 * @return mixed
	 */
	public function getSetupFeeNetAmount() {
		return $this->setupFeeNetAmount;
	}

	/**
	 * @param mixed $SetupFeeNetAmount
	 */
	public function setSetupFeeNetAmount($SetupFeeNetAmount ) {
		$this->setupFeeNetAmount = $SetupFeeNetAmount;
	}

	/**
	 * @return mixed
	 */
	public function getSetupFeeGrossAmount() {
		return $this->setupFeeGrossAmount;
	}

	/**
	 * @param mixed $setupFeeGrossAmount
	 */
	public function setSetupFeeGrossAmount($setupFeeGrossAmount ) {
		$this->setupFeeGrossAmount = $setupFeeGrossAmount;
	}

	/**
	 * @return mixed
	 */
	public function getSetupFeeTaxAmount() {
		return $this->setupFeeTaxAmount;
	}

	/**
	 * @param mixed $setupFeeTaxAmount
	 */
	public function setSetupFeeTaxAmount($setupFeeTaxAmount ) {
		$this->setupFeeTaxAmount = $setupFeeTaxAmount;
	}

	/**
	 * @return mixed
	 */
	public function getPlanQuantity() {
		return $this->planQuantity;
	}

	/**
	 * @param mixed $planQuantity
	 */
	public function setPlanQuantity( $planQuantity ) {
		$this->planQuantity = $planQuantity;
	}

    /**
     * @return mixed
     */
    public function getDiscountId() {
        return $this->discountId;
    }

    /**
     * @param mixed $discountId
     */
    public function setDiscountId($discountId ) {
        $this->discountId = $discountId;
    }

    /**
     * @return mixed
     */
    public function getDiscountType() {
        return $this->discountType;
    }

    /**
     * @param mixed $discountId
     */
    public function setDiscountType( $discountType ) {
        $this->discountType = $discountType;
    }

	/**
	 * Get string with JSON of transaction data
	 * @return string
	 */
	public function getJSONString() {
		$data = array(
			'planId' => $this->planId,
			'planName' => $this->planName,
			'planCurrency' => $this->planCurrency,
			'planNetAmount' => $this->planNetAmount,
			'planGrossAmount' => $this->planGrossAmount,
			'planTaxAmount' => $this->planTaxAmount,
			'planFutureNetAmount' => $this->planFutureNetAmount,
			'planFutureTaxAmount' => $this->planFutureTaxAmount,
			'planFutureGrossAmount' => $this->planFutureGrossAmount,
			'planAmountVATRate' => $this->planAmountVATRate,
			'planNetAmountTotal' => $this->planNetAmountTotal,
			'planGrossAmountTotal' => $this->planGrossAmountTotal,
			'planTaxAmountTotal' => $this->planTaxAmountTotal,
			'setupFeeNetAmount' => $this->setupFeeNetAmount,
			'setupFeeGrossAmount' => $this->setupFeeGrossAmount,
			'setupFeeTaxAmount' => $this->setupFeeTaxAmount,
			'planSetupFeeVATRate' => $this->planSetupFeeVATRate,
			'setupFeeNetAmountTotal' => $this->setupFeeNetAmountTotal,
			'setupFeeGrossAmountTotal' => $this->setupFeeGrossAmountTotal,
			'setupFeeTaxAmountTotal' => $this->setupFeeTaxAmountTotal,
			'planQuantity' => $this->planQuantity,
			'billingCycleAnchorDay' => $this->billingCycleAnchorDay,
			'prorateUntilAnchorDay' => $this->prorateUntilAnchorDay,
			'trialPeriodDays' => $this->trialPeriodDays,
			'stripeInvoiceId' => $this->stripeInvoiceId,
			'discountId' => $this->discountId,
			'discountType' => $this->discountType
		);
		return json_encode($data);
	}
}