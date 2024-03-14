<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

trait MM_WPFS_MacroReplacer_AddOn {
    /** @var array */
    protected $rawKeyValuePairs;
    /** @var array */
    protected $decoratedKeyValuePairs;
    /** @var MM_WPFS_TransactionData */
    protected $transactionData;

    public function getRawKeyValuePairs() {
        return $this->rawKeyValuePairs;
    }

    public function getDecoratedKeyValuePairs() {
        return $this->decoratedKeyValuePairs;
    }

    protected function getDecoratedMacroKeys() {
        return array_keys( $this->decoratedKeyValuePairs );
    }

    protected function getDecoratedMacroValues() {
        return array_values( $this->decoratedKeyValuePairs );
    }

    protected function replaceMacrosWithEscape($template, $escapeType ) {
        $keys 	= $this->getDecoratedMacroKeys();
        $values = $this->getDecoratedMacroValues();

        $escapedValues = array();
        foreach ( $values as $value ) {
            array_push( $escapedValues, MM_WPFS_Utils::escape( $value, $escapeType ));
        }

        $template = str_replace( $keys, $escapedValues, $template );

        return $template;
    }

    public function replaceMacrosWithHtmlEscape( $template ) {
        return $this->replaceMacrosWithEscape( $template, MM_WPFS_Utils::ESCAPE_TYPE_HTML );
    }

    public function replaceMacrosWithAttributeEscape( $template ) {
        return $this->replaceMacrosWithEscape( $template, MM_WPFS_Utils::ESCAPE_TYPE_ATTR );
    }

    public function replaceMacros( $template ) {
        return $this->replaceMacrosWithEscape( $template, MM_WPFS_Utils::ESCAPE_TYPE_NONE );
    }

    abstract protected function initRawKeyValuePairs();
    abstract protected function initDecoratedKeyValuePairs();
    abstract public static function getMacroKeys();
}

class MM_WPFS_GenericMacroReplacer {
    use MM_WPFS_MacroReplacer_AddOn;

    function __construct( $transactionData ) {
        $this->transactionData  = $transactionData;

        $this->initRawKeyValuePairs();
        $this->initDecoratedKeyValuePairs();
    }

    protected function initRawKeyValuePairs() {
        $this->rawKeyValuePairs = $this->transactionData;
    }

    protected function initDecoratedKeyValuePairs() {
        $this->decoratedKeyValuePairs = $this->transactionData;
    }

    public static function getMacroKeys() {
        return [];
    }
}

class MM_WPFS_MyAccountLoginMacroReplacer {
    use MM_WPFS_MacroReplacer_AddOn;

    function __construct( $transactionData ) {
        $this->transactionData  = $transactionData;

        $this->initRawKeyValuePairs();
        $this->initDecoratedKeyValuePairs();
    }

    public static function getMacroKeys() {
        return array(
            '%NAME%',
            '%CUSTOMERNAME%',
            '%CUSTOMER_EMAIL%',
            '%CARD_UPDATE_SECURITY_CODE%',
            '%CARD_UPDATE_SESSION_HASH%',
            '%DATE%'
        );
    }

    protected function getKeyValuePairs() {
        $siteTitle      = get_bloginfo( 'name' );
        $dateFormat     = get_option( 'date_format' );

        $keyValuePairs = array(
            '%NAME%'		                => $siteTitle,
            '%CUSTOMERNAME%'		        => $this->transactionData->getCustomerName(),
            '%CUSTOMER_EMAIL%'		        => $this->transactionData->getCustomerEmail(),
            '%CARD_UPDATE_SECURITY_CODE%'   => $this->transactionData->getSecurityCode(),
            '%CARD_UPDATE_SESSION_HASH%'    => $this->transactionData->getSessionHash(),
            '%DATE%'                        => MM_WPFS_Utils::formatTimestampWithWordpressDateFormat( time() )
        );

        return $keyValuePairs;
    }

    protected function initRawKeyValuePairs() {
        $this->rawKeyValuePairs = $this->getKeyValuePairs();
    }

    protected function initDecoratedKeyValuePairs() {
        $this->decoratedKeyValuePairs = $this->getKeyValuePairs();
    }
}

abstract class MM_WPFS_FormMacroReplacer {
    use MM_WPFS_MacroReplacer_AddOn;
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    /** @var stdClass */
    protected $form;

    /** @var MM_WPFS_Options */
    protected $options;

    function __construct( $form, $transactionData, $loggerService ) {
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME );
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        $this->form             = $form;
        $this->transactionData  = $transactionData;

        $this->rawKeyValuePairs = array();
        $this->decoratedKeyValuePairs = array();

        $this->initRawKeyValuePairs();
        $this->initDecoratedKeyValuePairs();
    }

    public static function getMacroKeys() {
        return array(
            '%NAME%',
            '%CUSTOMERNAME%',
            '%CUSTOMER_EMAIL%',
            '%BILLING_NAME%',
            '%ADDRESS1%',
            '%ADDRESS2%',
            '%CITY%',
            '%STATE%',
            '%COUNTRY%',
            '%COUNTRY_CODE%',
            '%ZIP%',
            '%SHIPPING_NAME%',
            '%SHIPPING_ADDRESS1%',
            '%SHIPPING_ADDRESS2%',
            '%SHIPPING_CITY%',
            '%SHIPPING_STATE%',
            '%SHIPPING_COUNTRY%',
            '%SHIPPING_COUNTRY_CODE%',
            '%SHIPPING_ZIP%',
            '%DATE%',
            '%FORM_NAME%',
            '%STRIPE_CUSTOMER_ID%',
            '%TRANSACTION_ID%',
            '%CUSTOMFIELD1%',
            '%IP_ADDRESS%'
        );
    }

    private function getKeyValuePairs() {
        $siteTitle      = get_bloginfo( 'name' );
        $dateFormat     = get_option( 'date_format' );
        $billingAddress = $this->transactionData->getBillingAddress();
        $shippingAddress = $this->transactionData->getShippingAddress();

        $keyValuePairs = array(
            '%NAME%'                  => $siteTitle,
            '%CUSTOMERNAME%'          => $this->transactionData->getCustomerName(),
            '%CUSTOMER_EMAIL%'        => $this->transactionData->getCustomerEmail(),
            '%BILLING_NAME%'          => $this->transactionData->getBillingName(),
            '%ADDRESS1%'              => is_null( $billingAddress ) ? null : $billingAddress['line1'],
            '%ADDRESS2%'              => is_null( $billingAddress ) ? null : $billingAddress['line2'],
            '%CITY%'                  => is_null( $billingAddress ) ? null : $billingAddress['city'],
            '%STATE%'                 => is_null( $billingAddress ) ? null : $billingAddress['state'],
            '%COUNTRY%'               => is_null( $billingAddress ) ? null : $billingAddress['country'],
            '%COUNTRY_CODE%'          => is_null( $billingAddress ) ? null : $billingAddress['country_code'],
            '%ZIP%'                   => is_null( $billingAddress ) ? null : $billingAddress['zip'],
            '%SHIPPING_NAME%'         => $this->transactionData->getShippingName(),
            '%SHIPPING_ADDRESS1%'     => is_null( $shippingAddress ) ? null : $shippingAddress['line1'],
            '%SHIPPING_ADDRESS2%'     => is_null( $shippingAddress ) ? null : $shippingAddress['line2'],
            '%SHIPPING_CITY%'         => is_null( $shippingAddress ) ? null : $shippingAddress['city'],
            '%SHIPPING_STATE%'        => is_null( $shippingAddress ) ? null : $shippingAddress['state'],
            '%SHIPPING_COUNTRY%'      => is_null( $shippingAddress ) ? null : $shippingAddress['country'],
            '%SHIPPING_COUNTRY_CODE%' => is_null( $shippingAddress ) ? null : $shippingAddress['country_code'],
            '%SHIPPING_ZIP%'          => is_null( $shippingAddress ) ? null : $shippingAddress['zip'],
            '%DATE%'                  => MM_WPFS_Utils::formatTimestampWithWordpressDateFormat( time() ),
            '%FORM_NAME%'             => $this->transactionData->getFormName(),
            '%STRIPE_CUSTOMER_ID%'    => $this->transactionData->getStripeCustomerId(),
            '%TRANSACTION_ID%'        => $this->transactionData->getTransactionId(),
            '%IP_ADDRESS%'            => $this->transactionData->getIpAddress()
        );

        $customInputKeyValuePairs = $this->getCustomInputKeyValuePairs();

        return array_merge( $keyValuePairs, $customInputKeyValuePairs );
    }

    protected function initRawKeyValuePairs() {
        $this->rawKeyValuePairs = array_merge( $this->rawKeyValuePairs, $this->getKeyValuePairs() );
    }

    protected function initDecoratedKeyValuePairs() {
        $this->decoratedKeyValuePairs = array_merge( $this->decoratedKeyValuePairs, $this->getKeyValuePairs() );
    }

    private function getCustomInputKeyValuePairs() {
        $keyValuePairs      = array();
        $customInputValues  = $this->transactionData->getCustomInputValues();

        $customInputFieldMaxCount = MM_WPFS::getCustomFieldMaxCount( $this->staticContext );
        $customInputValueCount    = 0;
        if ( isset( $customInputValues ) && is_array( $customInputValues ) ) {
            $customInputValueCount = count($customInputValues);
        }

        for ( $idx = 0; $idx < $customInputFieldMaxCount; $idx++ ) {
            $key = "%CUSTOMFIELD" . ( $idx+1 ) . "%";

            if ( $idx < $customInputValueCount ) {
                $value = $customInputValues[ $idx ];
            } else {
                $value = '';
            }
            $customInputElement = array( $key => $value  );

            $keyValuePairs = array_merge( $keyValuePairs, $customInputElement );
        }

        return $keyValuePairs;
    }
}

class MM_WPFS_SaveCardMacroReplacer extends MM_WPFS_FormMacroReplacer {
    function __construct( $form, $transactionData, $loggerService ) {
        parent::__construct( $form, $transactionData, $loggerService );
    }
}

abstract class MM_WPFS_ProductMacroReplacer extends MM_WPFS_FormMacroReplacer {
    function __construct( $form, $transactionData, $loggerService ) {
        parent::__construct( $form, $transactionData, $loggerService );
    }

    public static function getMacroKeys() {
        return array_merge( parent::getMacroKeys(), array(
            '%PRODUCT_NAME%',
            '%CUSTOMER_PHONE%',
        ));
    }

    private function getKeyValuePairs() {
        $keyValuePairs = array(
            '%PRODUCT_NAME%'    => $this->transactionData->getProductName(),
            '%CUSTOMER_PHONE%'    => $this->transactionData->getCustomerPhone(),
        );

        return $keyValuePairs;
    }

    protected function initRawKeyValuePairs() {
        parent::initRawKeyValuePairs();

        $this->rawKeyValuePairs = array_merge( $this->rawKeyValuePairs, $this->getKeyValuePairs() );
    }

    protected function initDecoratedKeyValuePairs() {
        parent::initDecoratedKeyValuePairs();

        $this->decoratedKeyValuePairs = array_merge( $this->decoratedKeyValuePairs, $this->getKeyValuePairs() );
    }
}

abstract class MM_WPFS_OneTimeInvolvedMacroReplacer extends MM_WPFS_ProductMacroReplacer {
    function __construct( $form, $transactionData, $loggerService ) {
        parent::__construct( $form, $transactionData, $loggerService );
    }

    public static function getMacroKeys() {
        return array_merge( parent::getMacroKeys(), array(
            '%AMOUNT%',
            '%INVOICE_URL%',
            '%INVOICE_NUMBER%',
        ));
    }

    protected function initRawKeyValuePairs() {
        parent::initRawKeyValuePairs();

        $keyValuePairs = array(
            '%AMOUNT%' =>               $this->transactionData->getAmount(),
            '%INVOICE_URL%'             => $this->transactionData->getInvoiceUrl(),
            '%INVOICE_NUMBER%'          => $this->transactionData->getInvoiceNumber(),
        );

        $this->rawKeyValuePairs = array_merge( $this->rawKeyValuePairs, $keyValuePairs );
    }

    protected function initDecoratedKeyValuePairs() {
        parent::initDecoratedKeyValuePairs();

        $keyValuePairs = array(
            '%AMOUNT%'		            => MM_WPFS_Currencies::formatAndEscapeByForm(
                $this->form,
                $this->transactionData->getCurrency(),
                $this->transactionData->getAmount()
            ),
            '%INVOICE_URL%'             => $this->transactionData->getInvoiceUrl(),
            '%INVOICE_NUMBER%'          => $this->transactionData->getInvoiceNumber(),
        );

        $this->decoratedKeyValuePairs = array_merge( $this->decoratedKeyValuePairs, $keyValuePairs );
    }
}


class MM_WPFS_OneTimePaymentMacroReplacer extends MM_WPFS_OneTimeInvolvedMacroReplacer {
    /**
     * @param $form array
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData
     */
    function __construct( $form, $transactionData, $loggerService ) {
        parent::__construct( $form, $transactionData, $loggerService );
    }

    public static function getMacroKeys() {
        return array_merge( parent::getMacroKeys(), array(
            '%COUPON_CODE%',
            '%CUSTOMER_TAX_ID%',
            '%PRODUCT_AMOUNT_GROSS%',
            '%PRODUCT_AMOUNT_TAX%',
            '%PRODUCT_AMOUNT_NET%',
            '%PRODUCT_AMOUNT_DISCOUNT%'
        ));
    }

    protected function initRawKeyValuePairs() {
        parent::initRawKeyValuePairs();

        $keyValuePairs = array(
            '%COUPON_CODE%'             => $this->transactionData->getCouponCode(),
            '%CUSTOMER_TAX_ID%'         => $this->transactionData->getCustomerTaxId(),
            '%PRODUCT_AMOUNT_GROSS%'    => $this->transactionData->getProductAmountGross(),
            '%PRODUCT_AMOUNT_TAX%'      => $this->transactionData->getProductAmountTax(),
            '%PRODUCT_AMOUNT_NET%'      => $this->transactionData->getProductAmountNet(),
            '%PRODUCT_AMOUNT_DISCOUNT%' => $this->transactionData->getProductAmountDiscount(),
        );

        $this->rawKeyValuePairs = array_merge( $this->rawKeyValuePairs, $keyValuePairs );
    }

    protected function initDecoratedKeyValuePairs() {
        parent::initDecoratedKeyValuePairs();

        $keyValuePairs = array(
            '%COUPON_CODE%'     => $this->transactionData->getCouponCode(),
            '%CUSTOMER_TAX_ID%' => $this->transactionData->getCustomerTaxId(),
            '%PRODUCT_AMOUNT_GROSS%'  => MM_WPFS_Currencies::formatAndEscapeByForm(
                $this->form,
                $this->transactionData->getCurrency(),
                $this->transactionData->getProductAmountGross()
            ),
            '%PRODUCT_AMOUNT_TAX%'  => MM_WPFS_Currencies::formatAndEscapeByForm(
                $this->form,
                $this->transactionData->getCurrency(),
                $this->transactionData->getProductAmountTax()
            ),
            '%PRODUCT_AMOUNT_NET%'  => MM_WPFS_Currencies::formatAndEscapeByForm(
                $this->form,
                $this->transactionData->getCurrency(),
                $this->transactionData->getProductAmountNet()
            ),
            '%PRODUCT_AMOUNT_DISCOUNT%'  => MM_WPFS_Currencies::formatAndEscapeByForm(
                $this->form,
                $this->transactionData->getCurrency(),
                $this->transactionData->getProductAmountDiscount()
            ),
        );

        $this->decoratedKeyValuePairs = array_merge( $this->decoratedKeyValuePairs, $keyValuePairs );
    }
}

class MM_WPFS_DonationMacroReplacer extends MM_WPFS_OneTimeInvolvedMacroReplacer {
    /**
     * @param $form array
     * @param $transactionData MM_WPFS_DonationTransactionData
     */
    function __construct( $form, $transactionData, $loggerService ) {
        parent::__construct( $form, $transactionData, $loggerService );
    }

    public static function getMacroKeys() {
        return array_merge( parent::getMacroKeys(), array(
            '%DONATION_FREQUENCY%',
        ));
    }

    protected function initRawKeyValuePairs() {
        parent::initRawKeyValuePairs();

        $keyValuePairs = array(
            '%DONATION_FREQUENCY%'    => MM_WPFS_Localization::getDonationFrequencyLabel( $this->transactionData->getDonationFrequency() ),
        );

        $this->rawKeyValuePairs = array_merge( $this->rawKeyValuePairs, $keyValuePairs );
    }

    protected function initDecoratedKeyValuePairs() {
        parent::initDecoratedKeyValuePairs();

        $keyValuePairs = array(
            '%DONATION_FREQUENCY%' => MM_WPFS_Localization::getDonationFrequencyLabel( $this->transactionData->getDonationFrequency() ),
        );

        $this->decoratedKeyValuePairs = array_merge( $this->decoratedKeyValuePairs, $keyValuePairs );
    }
}

class MM_WPFS_SubscriptionMacroReplacer extends MM_WPFS_ProductMacroReplacer {
    /**
     * @param $form array
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     */
    function __construct( $form, $transactionData, $loggerService ) {
        parent::__construct( $form, $transactionData, $loggerService );
    }

    public static function getMacroKeys() {
        return array_merge( parent::getMacroKeys(), array(
            '%SETUP_FEE%',
            '%SETUP_FEE_NET%',
            '%SETUP_FEE_GROSS%',
            '%SETUP_FEE_VAT%',
            '%SETUP_FEE_VAT_RATE%',
            '%SETUP_FEE_TOTAL%',
            '%SETUP_FEE_NET_TOTAL%',
            '%SETUP_FEE_GROSS_TOTAL%',
            '%SETUP_FEE_VAT_TOTAL%',
            '%PLAN_NAME%',
            '%PLAN_AMOUNT%',
            '%PLAN_AMOUNT_NET%',
            '%PLAN_AMOUNT_GROSS%',
            '%PLAN_AMOUNT_VAT%',
            '%PLAN_AMOUNT_VAT_RATE%',
            '%PLAN_QUANTITY%',
            '%PLAN_AMOUNT_TOTAL%',
            '%PLAN_AMOUNT_NET_TOTAL%',
            '%PLAN_AMOUNT_GROSS_TOTAL%',
            '%PLAN_AMOUNT_VAT_TOTAL%',
            '%PLAN_FUTURE_AMOUNT_NET%',
            '%PLAN_FUTURE_AMOUNT_VAT%',
            '%PLAN_FUTURE_AMOUNT_GROSS%',
            '%INVOICE_URL%',
            '%INVOICE_NUMBER%',
            '%RECEIPT_URL%',
            '%AMOUNT%',
            '%COUPON_CODE%',
            '%CUSTOMER_TAX_ID%'
        ));
    }

    protected function initRawKeyValuePairs() {
        parent::initRawKeyValuePairs();

        $keyValuePairs = array(
            '%SETUP_FEE%'               => $this->transactionData->getSetupFeeGrossAmount(),
            '%SETUP_FEE_NET%'           => $this->transactionData->getSetupFeeNetAmount(),
            '%SETUP_FEE_GROSS%'         => $this->transactionData->getSetupFeeGrossAmount(),
            '%SETUP_FEE_VAT%'           => $this->transactionData->getSetupFeeTaxAmount(),
            '%SETUP_FEE_TOTAL%'         => $this->transactionData->getSetupFeeGrossAmountTotal(),
            '%SETUP_FEE_NET_TOTAL%'     => $this->transactionData->getSetupFeeNetAmountTotal(),
            '%SETUP_FEE_GROSS_TOTAL%'   => $this->transactionData->getSetupFeeGrossAmountTotal(),
            '%SETUP_FEE_VAT_TOTAL%'     => $this->transactionData->getSetupFeeTaxAmountTotal(),
            '%PLAN_NAME%'               => $this->transactionData->getPlanName(),
            '%PLAN_AMOUNT%'             => $this->transactionData->getPlanGrossAmount(),
            '%PLAN_FUTURE_AMOUNT_NET%'  => $this->transactionData->getPlanFutureNetAmount(),
            '%PLAN_FUTURE_AMOUNT_VAT%'  => $this->transactionData->getPlanFutureTaxAmount(),
            '%PLAN_FUTURE_AMOUNT_GROSS%' => $this->transactionData->getPlanFutureGrossAmount(),
            '%PLAN_AMOUNT_NET%'         => $this->transactionData->getPlanNetAmount(),
            '%PLAN_AMOUNT_GROSS%'       => $this->transactionData->getPlanGrossAmount(),
            '%PLAN_AMOUNT_VAT%'         => $this->transactionData->getPlanTaxAmount(),
            '%PLAN_QUANTITY%'           => $this->transactionData->getPlanQuantity(),
            '%PLAN_AMOUNT_TOTAL%'       => $this->transactionData->getPlanGrossAmountTotal(),
            '%PLAN_AMOUNT_NET_TOTAL%'   => $this->transactionData->getPlanNetAmountTotal(),
            '%PLAN_AMOUNT_GROSS_TOTAL%' => $this->transactionData->getPlanGrossAmountTotal(),
            '%PLAN_AMOUNT_VAT_TOTAL%'   => $this->transactionData->getPlanTaxAmountTotal(),
            '%INVOICE_URL%'             => $this->transactionData->getInvoiceUrl(),
            '%INVOICE_NUMBER%'          => $this->transactionData->getInvoiceNumber(),
            '%RECEIPT_URL%'             => $this->transactionData->getReceiptUrl(),
            '%AMOUNT%'                  => $this->transactionData->getPlanGrossAmountAndGrossSetupFeeTotal(),
            '%COUPON_CODE%'             => $this->transactionData->getCouponCode(),
            '%CUSTOMER_TAX_ID%'         => $this->transactionData->getCustomerTaxId(),
        );

        $this->rawKeyValuePairs = array_merge( $this->rawKeyValuePairs, $keyValuePairs );
    }

    protected function initDecoratedKeyValuePairs() {
        parent::initDecoratedKeyValuePairs();

        $stripePlanCurrency = $this->transactionData->getPlanCurrency();

        $keyValuePairs = array(
            '%SETUP_FEE%'               => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getSetupFeeGrossAmount() ),
            '%SETUP_FEE_NET%'           => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getSetupFeeNetAmount() ),
            '%SETUP_FEE_GROSS%'         => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getSetupFeeGrossAmount() ),
            '%SETUP_FEE_VAT%'           => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getSetupFeeTaxAmount() ),
            '%SETUP_FEE_TOTAL%'         => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getSetupFeeGrossAmountTotal() ),
            '%SETUP_FEE_NET_TOTAL%'     => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getSetupFeeNetAmountTotal() ),
            '%SETUP_FEE_GROSS_TOTAL%'   => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getSetupFeeGrossAmountTotal() ),
            '%SETUP_FEE_VAT_TOTAL%'     => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getSetupFeeTaxAmountTotal() ),
            '%PLAN_NAME%'               => $this->transactionData->getPlanName(),
            '%PLAN_AMOUNT%'             => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanGrossAmount() ),
            '%PLAN_AMOUNT_NET%'         => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanNetAmount() ),
            '%PLAN_AMOUNT_GROSS%'       => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanGrossAmount() ),
            '%PLAN_AMOUNT_VAT%'         => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanTaxAmount() ),
            '%PLAN_QUANTITY%'           => $this->transactionData->getPlanQuantity(),
            '%PLAN_AMOUNT_TOTAL%'       => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanGrossAmountTotal() ),
            '%PLAN_AMOUNT_NET_TOTAL%'   => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanNetAmountTotal() ),
            '%PLAN_AMOUNT_GROSS_TOTAL%' => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanGrossAmountTotal() ),
            '%PLAN_AMOUNT_VAT_TOTAL%'   => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanTaxAmountTotal() ),
            '%PLAN_FUTURE_AMOUNT_GROSS%' => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanFutureGrossAmount() ),
            '%PLAN_FUTURE_AMOUNT_VAT%'  => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanFutureTaxAmount() ),
            '%PLAN_FUTURE_AMOUNT_NET%'  => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanFutureNetAmount() ),
            '%INVOICE_URL%'             => $this->transactionData->getInvoiceUrl(),
            '%INVOICE_NUMBER%'          => $this->transactionData->getInvoiceNumber(),
            '%RECEIPT_URL%'             => $this->transactionData->getReceiptUrl(),
            '%AMOUNT%'                  => MM_WPFS_Currencies::formatAndEscapeByForm( $this->form, $stripePlanCurrency, $this->transactionData->getPlanGrossAmountAndGrossSetupFeeTotal() ),
            '%COUPON_CODE%'             => $this->transactionData->getCouponCode(),
            '%CUSTOMER_TAX_ID%'         => $this->transactionData->getCustomerTaxId(),
        );

        $this->decoratedKeyValuePairs = array_merge( $this->decoratedKeyValuePairs, $keyValuePairs );
    }
}

