<?php

class MM_WPFS_CreateCustomerContext
{
    public $paymentMethodId;
    public $paymentIntentId;
    public $cardHolderName;
    public $cardHolderEmail;
    public $cardHolderPhone;
    public $businessName;
    public $taxCountry;
    public $taxState;
    public $taxPostalCode;
    public $taxIdType;
    public $taxId;
    public $billingName;
    public $billingAddress;
    public $shippingName;
    public $shippingAddress;
    public $metadata;
    public $isStripeTax;
}

class MM_WPFS_CreateOneTimeInvoiceOptions
{
    public $autoAdvance;
    public $taxRateIds = null;
}

class MM_WPFS_CreateOneTimeInvoiceContext
{
    public $stripeCustomerId;
    public $stripePriceId;
    public $currency;
    public $amount;
    public $productName;
    public $stripeCouponId;
    public $isStripeTax;
    public $taxCountry;
    public $taxState;
    public $taxPostalCode;
    public $taxIdType;
    public $taxId;
}

class MM_WPFS_CreateSubscriptionOptions
{
    public $taxRateIds = null;
}

class MM_WPFS_CreateSubscriptionContext
{
    public $stripeCustomerId;
    public $stripePriceId;
    public $stripePaymentMethodId;
    public $setupFee;
    public $trialPeriodDays;
    public $stripePlanQuantity;
    public $billingCycleAnchorDay;
    public $prorateUntilAnchorDay;
    public $metadata;
    public $discountId;
    public $discountType;
    public $productName;
    public $isStripeTax;
}

abstract class MM_WPFS_OneTimeInvoiceContextCreator
{
    /** @var MM_WPFS_Public_PaymentFormModel|MM_WPFS_Public_DonationFormModel */
    protected $formModel;

    public function __construct($formModel)
    {
        $this->formModel = $formModel;
    }

    public function getContext()
    {
        $result = new MM_WPFS_CreateOneTimeInvoiceContext();

        $result->stripeCustomerId = $this->formModel->getStripeCustomer()->id;
        $result->currency = $this->formModel->getForm()->currency;
        $result->amount = $this->formModel->getAmount();
        $result->productName = $this->formModel->getProductName();

        return $result;
    }
}

class MM_WPFS_OneTimeInvoiceContextCreator_DonationForm extends MM_WPFS_OneTimeInvoiceContextCreator
{
    public function __construct($formModel)
    {
        parent::__construct($formModel);
    }

    /**
     * @return MM_WPFS_Public_DonationFormModel
     *
     * This getter is created so that we can have type hints in the IDE
     */
    protected function getFormModel()
    {
        return $this->formModel;
    }

    public function getContext()
    {
        $result = parent::getContext();

        $result->stripePriceId = null;
        $result->stripeCouponId = null;
        $result->isStripeTax = false;
        $result->taxCountry = null;
        $result->taxState = null;
        $result->taxPostalCode = null;
        $result->taxIdType = null;
        $result->taxId = null;

        return $result;
    }
}

class MM_WPFS_OneTimeInvoiceContextCreator_PaymentForm extends MM_WPFS_OneTimeInvoiceContextCreator
{
    public function __construct($formModel)
    {
        parent::__construct($formModel);
    }

    /**
     * @return MM_WPFS_Public_PaymentFormModel
     *
     * This getter is created so that we can have type hints in the IDE
     */
    protected function getFormModel()
    {
        return $this->formModel;
    }

    public function getContext()
    {
        $result = parent::getContext();

        $formModel = $this->getFormModel();

        $result->stripePriceId = $formModel->getPriceId();
        $result->stripeCouponId = is_null($formModel->getStripeCoupon()) ? null : $formModel->getStripeCoupon()->id;
        $result->isStripeTax = ($formModel->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX);
        $result->taxCountry = $formModel->getTaxCountry();
        $result->taxState = $formModel->getTaxState();
        $result->taxPostalCode = $formModel->getTaxZip();
        $result->taxIdType = $formModel->getTaxIdType();
        $result->taxId = $formModel->getTaxId();

        return $result;
    }
}

trait MM_WPFS_OneTimeInvoiceCreator_AddOn
{
    protected function createOneTimeInvoiceContext($formModel)
    {
        if ($formModel instanceof MM_WPFS_Public_PaymentFormModel) {
            return (new MM_WPFS_OneTimeInvoiceContextCreator_PaymentForm($formModel))->getContext();
        } else if ($formModel instanceof MM_WPFS_Public_DonationFormModel) {
            return (new MM_WPFS_OneTimeInvoiceContextCreator_DonationForm($formModel))->getContext();
        } else {
            throw new Exception(__CLASS__ . '::' . __FUNCTION__ . '(): form model type not supported.');
        }
    }


    protected function createInvoiceForOneTimePaymentByFormModel($formModel, $options)
    {
        return $this->stripe->createInvoiceForOneTimePayment($this->createOneTimeInvoiceContext($formModel), $options);
    }

    protected function createPreviewInvoiceForOneTimePaymentByFormModel($formModel, $options)
    {
        return $this->stripe->createPreviewInvoiceForOneTimePayment($this->createOneTimeInvoiceContext($formModel), $options);
    }
}

class MM_WPFS_CreateCustomerOptions
{
    public $addMetadata;
}

abstract class MM_WPFS_CustomerContextCreator
{
    /** @var MM_WPFS_Public_PaymentFormModel|MM_WPFS_Public_SubscriptionFormModel|MM_WPFS_Public_DonationFormModel */
    protected $formModel;

    public function __construct($formModel)
    {
        $this->formModel = $formModel;
    }

    protected function findBillingName()
    {
        return is_null($this->formModel->getBillingName()) ? $this->formModel->getCardHolderName() : $this->formModel->getBillingName();
    }

    public function getContext()
    {
        $result = new MM_WPFS_CreateCustomerContext();

        $result->paymentMethodId = $this->formModel->getStripePaymentMethodId();
        $result->paymentIntentId = $this->formModel->getStripePaymentIntentId();
        $result->cardHolderName = $this->formModel->getCardHolderName();
        $result->cardHolderEmail = $this->formModel->getCardHolderEmail();
        $result->cardHolderPhone = $this->formModel->getCardHolderPhone();

        $result->billingName = $this->findBillingName();
        $result->billingAddress = $this->formModel->getBillingAddress();
        $result->shippingName = $this->formModel->getShippingName();
        $result->shippingAddress = $this->formModel->getShippingAddress();

        $result->metadata = $this->formModel->getMetadata();

        return $result;
    }
}

class MM_WPFS_CustomerContextCreator_PaymentForm extends MM_WPFS_CustomerContextCreator
{
    public function __construct($formModel)
    {
        parent::__construct($formModel);
    }

    /**
     * @return MM_WPFS_Public_PaymentFormModel|MM_WPFS_Public_SubscriptionFormModel
     *
     * This getter is created so that we can have type hints in the IDE
     */
    protected function getFormModel()
    {
        return $this->formModel;
    }

    public function getContext()
    {
        $result = parent::getContext();

        $formModel = $this->getFormModel();

        $result->businessName = $formModel->getBusinessName();
        $result->taxIdType = $formModel->getTaxIdType();
        $result->taxId = $formModel->getTaxId();
        $result->taxCountry = $formModel->getTaxCountry();
        $result->taxState = $formModel->getTaxState();
        $result->taxPostalCode = $formModel->getTaxZip();
        $result->isStripeTax = ($formModel->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX);

        return $result;
    }
}

class MM_WPFS_CustomerContextCreator_DonationForm extends MM_WPFS_CustomerContextCreator
{
    public function __construct($formModel)
    {
        parent::__construct($formModel);
    }

    /**
     * @return MM_WPFS_Public_DonationFormModel
     *
     * This getter is created so that we can have type hints in the IDE
     */
    protected function getFormModel()
    {
        return $this->formModel;
    }

    public function getContext()
    {
        $result = parent::getContext();

        $result->businessName = null;
        $result->taxCountry = null;
        $result->taxState = null;
        $result->taxPostalCode = null;
        $result->taxIdType = null;
        $result->taxId = null;
        $result->isStripeTax = false;

        return $result;
    }
}

class MM_WPFS_SubscriptionContextCreator
{
    /** @var MM_WPFS_Public_SubscriptionFormModel */
    protected $formModel;
    /** @var MM_WPFS_SubscriptionTransactionData */
    protected $transactionData;

    public function __construct($formModel, $transactionData)
    {
        $this->formModel = $formModel;
        $this->transactionData = $transactionData;
    }

    public function getContext()
    {
        $result = new MM_WPFS_CreateSubscriptionContext();

        $transactionData = $this->transactionData;

        $result->stripeCustomerId = $transactionData->getStripeCustomerId();
        $result->stripePriceId = $transactionData->getPlanId();
        $result->stripePaymentMethodId = $transactionData->getStripePaymentMethodId();
        $result->setupFee = $transactionData->getSetupFeeNetAmount();
        $result->trialPeriodDays = $transactionData->getTrialPeriodDays();
        $result->stripePlanQuantity = $transactionData->getPlanQuantity();
        $result->billingCycleAnchorDay = $transactionData->getBillingCycleAnchorDay();
        $result->prorateUntilAnchorDay = $transactionData->getProrateUntilAnchorDay();
        $result->metadata = $transactionData->getMetadata();
        $result->discountId = $transactionData->getDiscountId();
        $result->discountType = $transactionData->getDiscountType();
        $result->productName = $transactionData->getProductName();
        $result->isStripeTax = ($this->formModel->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX);

        return $result;
    }
}

trait MM_WPFS_DonationTools_AddOn
{
    /* @var stripe MM_WPFS_Database */
    /* @var mailer MM_WPFS_Mailer */

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     *
     * @return boolean
     */
    private function isRecurringDonation($donationFormModel)
    {
        $res = false;

        $donationFrequencies = array(
            MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_DAILY,
            MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_WEEKLY,
            MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_MONTHLY,
            MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ANNUAL
        );

        if (false === array_search($donationFormModel->getDonationFrequency(), $donationFrequencies)) {
            $res = false;
        } else {
            $res = true;
        }

        return $res;
    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     * @param $transactionData MM_WPFS_DonationTransactionData
     */
    protected function sendDonationEmailReceipt($donationFormModel, $transactionData)
    {
        $this->mailer->sendDonationEmailReceipt($donationFormModel->getForm(), $transactionData);
    }

    /**
     * @param $currency string
     * @param $donationFrequency string
     *
     * @return string
     */
    protected function constructDonationPlanID($currency, $donationFrequency)
    {
        return MM_WPFS::DONATION_PLAN_ID_PREFIX . ucfirst($currency) . ucfirst($donationFrequency);
    }

    protected function localizeDonationPlanName($frequency)
    {
        $res = "";

        switch ($frequency) {
            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_DAILY:
                $res = __('Daily donation (%s)');
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_WEEKLY:
                $res = __('Weekly donation (%s)');
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_MONTHLY:
                $res = __('Monthly donation (%s)');
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ANNUAL:
                $res = __('Annual donation (%s)');
                break;
        }

        return $res;

    }

    /**
     * @param $donationInterval string
     *
     * @return string
     */
    protected function translateFrequencyToInterval($donationFrequency)
    {
        $res = 'month';

        switch ($donationFrequency) {
            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_DAILY:
                $res = 'day';
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_WEEKLY:
                $res = 'week';
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_MONTHLY:
                $res = 'month';
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ANNUAL:
                $res = 'year';
                break;
        }

        return $res;
    }

    /**
     * @param $planID string
     * @param $currency string
     * @param $donationFrequency string
     *
     * @return \StripeWPFS\Plan
     * @throws Exception
     */
    protected function createDonationPlan($planID, $currency, $donationFrequency)
    {
        $planName = sprintf($this->localizeDonationPlanName($donationFrequency), strtoupper($currency));
        $interval = $this->translateFrequencyToInterval($donationFrequency);
        $plan = $this->stripe->createRecurringDonationPlan($planID, $planName, $currency, $interval, 1);

        return $plan;
    }

    /**
     * @param $planId
     *
     * @return \StripeWPFS\Price
     */
    protected function retrieveDonationPlan($planId)
    {
        $plan = $this->stripe->retrievePlan($planId);

        if (is_null($plan) || !$plan->active) {
            $plan = null;
            $plans = $this->stripe->retrieveDonationPlansWithLookupKey($planId);
            if (count($plans->data) > 0) {
                $plan = $plans->data[0];
            }
        }

        return $plan;
    }

    /**
     * @param $currency string
     * @param $donationFrequency string
     *
     * @return \StripeWPFS\Plan
     * @throws Exception
     */
    protected function createOrRetrieveDonationPlan($currency, $donationFrequency)
    {
        $planId = $this->constructDonationPlanID($currency, $donationFrequency);

        $plan = $this->retrieveDonationPlan($planId);
        if (is_null($plan)) {
            $plan = $this->createDonationPlan($planId, $currency, $donationFrequency);
        }

        return $plan;
    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     *
     * @return \StripeWPFS\Subscription
     * @throws Exception
     */
    protected function createSubscriptionForDonation($donationFormModel)
    {
        $plan = $this->createOrRetrieveDonationPlan($donationFormModel->getForm()->currency, $donationFormModel->getDonationFrequency());
        $subscription = $this->stripe->subscribeCustomerToPlan($donationFormModel->getStripeCustomer()->id, $plan->id);

        $this->stripe->createUsageRecordForSubscription($subscription, $donationFormModel->getAmount());

        return $subscription;
    }
}

/**
 * Class MM_WPFS_Customer deals with customer front-end input i.e. payment forms submission
 */
class MM_WPFS_Customer
{
    use MM_WPFS_DonationTools_AddOn;
    use MM_WPFS_ThankYou_AddOn;
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;
    use MM_WPFS_FindStripeCustomer_AddOn;
    use MM_WPFS_OneTimeInvoiceCreator_AddOn;

    const DEFAULT_CHECKOUT_LINE_ITEM_IMAGE = 'https://stripe.com/img/documentation/checkout/marketplace.png';

    /** @var $stripe MM_WPFS_Stripe */
    protected $stripe = null;

    /** @var $db MM_WPFS_Database */
    protected $db = null;

    /** @var $mailer MM_WPFS_Mailer */
    protected $mailer = null;

    /** @var MM_WPFS_TransactionDataService */
    private $transactionDataService = null;

    /** @var MM_WPFS_CheckoutSubmissionService */
    private $checkoutSubmissionService = null;

    /** @var MM_WPFS_Options */
    protected $options = null;

    public function __construct($loggerService)
    {
        $this->setup($loggerService);
        $this->hooks();
    }

    private function setup($loggerService)
    {
        $this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME);
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        $this->stripe = new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService);
        $this->db = new MM_WPFS_Database();
        $this->mailer = new MM_WPFS_Mailer($this->loggerService);
        $this->transactionDataService = new MM_WPFS_TransactionDataService();
        $this->checkoutSubmissionService = new MM_WPFS_CheckoutSubmissionService($this->loggerService);
    }

    private function hooks()
    {
        add_action('wp_ajax_wp_full_stripe_subscription_charge', array($this, 'fullstripe_subscription_charge'));
        add_action('wp_ajax_nopriv_wp_full_stripe_subscription_charge', array(
            $this,
            'fullstripe_subscription_charge'
        ));
        add_action('wp_ajax_wpfs-check-coupon', array($this, 'fullstripe_check_coupon'));
        add_action('wp_ajax_nopriv_wpfs-check-coupon', array($this, 'fullstripe_check_coupon'));

        add_action('wp_ajax_wp_get_Setup_Intent_Client_Secret', array($this, 'get_Setup_Intent_Client_Secret'));
        add_action('wp_ajax_wp_full_stripe_inline_payment_charge', array(
            $this,
            'fullstripe_inline_payment_charge'
        ));
        add_action('wp_ajax_wp_full_stripe_inline_donation_charge', array(
            $this,
            'fullstripe_inline_donation_charge'
        ));
        add_action('wp_ajax_nopriv_wp_get_Setup_Intent_Client_Secret', array(
            $this,
            'get_Setup_Intent_Client_Secret'
        ));
        add_action('wp_ajax_nopriv_wp_full_stripe_inline_payment_charge', array(
            $this,
            'fullstripe_inline_payment_charge'
        ));
        add_action('wp_ajax_nopriv_wp_full_stripe_inline_donation_charge', array(
            $this,
            'fullstripe_inline_donation_charge'
        ));
        add_action('wp_ajax_wp_full_stripe_inline_subscription_charge', array(
            $this,
            'fullstripe_inline_subscription_charge'
        ));
        add_action('wp_ajax_nopriv_wp_full_stripe_inline_subscription_charge', array(
            $this,
            'fullstripe_inline_subscription_charge'
        ));
        add_action('wp_ajax_wp_full_stripe_popup_payment_charge', array(
            $this,
            'fullstripe_checkout_payment_charge'
        ));
        add_action('wp_ajax_nopriv_wp_full_stripe_popup_payment_charge', array(
            $this,
            'fullstripe_checkout_payment_charge'
        ));
        add_action('wp_ajax_wp_full_stripe_popup_donation_charge', array(
            $this,
            'fullstripe_checkout_donation_charge'
        ));
        add_action('wp_ajax_nopriv_wp_full_stripe_popup_donation_charge', array(
            $this,
            'fullstripe_checkout_donation_charge'
        ));
        add_action('wp_ajax_wp_full_stripe_popup_subscription_charge', array(
            $this,
            'fullstripe_checkout_subscription_charge'
        ));
        add_action('wp_ajax_nopriv_wp_full_stripe_popup_subscription_charge', array(
            $this,
            'fullstripe_checkout_subscription_charge'
        ));
        add_action('wp_ajax_wp_full_stripe_handle_checkout_session', array(
            $this,
            'fullstripe_handle_checkout_session'
        ));
        add_action('wp_ajax_nopriv_wp_full_stripe_handle_checkout_session', array(
            $this,
            'fullstripe_handle_checkout_session'
        ));

        // actions for pricing/tax calculations
        add_action('wp_ajax_wpfs-calculate-pricing', array($this, 'calculatePricing'));
        add_action('wp_ajax_nopriv_wpfs-calculate-pricing', array($this, 'calculatePricing'));
    }

    function fullstripe_handle_checkout_session()
    {

        try {
            $this->logger->debug(__FUNCTION__, 'CALLED');

            $submitHash = isset(
                $_GET[MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH]
                ) ? sanitize_text_field($_GET[MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH]) : null;
            $submitStatus = isset(
                $_GET[MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_STATUS]
                ) ? sanitize_text_field($_GET[MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_STATUS]) : null;
            $popupFormSubmit = null;

            $this->logger->debug(__FUNCTION__, "submitHash={$submitHash}, submitStatus={$submitStatus}");

            if (!empty($submitHash) && !empty($submitStatus)) {
                $popupFormSubmit = $this->checkoutSubmissionService->retrieveSubmitEntry($submitHash);
                if (!is_null($popupFormSubmit) && isset($popupFormSubmit->checkoutSessionId)) {
                    $checkoutSession = $this->checkoutSubmissionService->retrieveCheckoutSession($popupFormSubmit->checkoutSessionId);


                    if (MM_WPFS_CheckoutSubmissionService::CHECKOUT_SESSION_STATUS_SUCCESS === $submitStatus) {

                        /**
                         * @var MM_WPFS_CheckoutChargeHandler
                         */
                        $checkoutChargeHandler = null;
                        $formModel = null;
                        if (
                            MM_WPFS_Utils::isCheckoutPaymentFormType($popupFormSubmit->formType) ||
                            MM_WPFS_Utils::isCheckoutSaveCardFormType($popupFormSubmit->formType)
                        ) {
                            $formModel = new MM_WPFS_Public_CheckoutPaymentFormModel($this->loggerService);
                            $checkoutChargeHandler = new MM_WPFS_CheckoutPaymentChargeHandler($this->loggerService);
                        } elseif (MM_WPFS_Utils::isCheckoutSubscriptionFormType($popupFormSubmit->formType)) {
                            $formModel = new MM_WPFS_Public_CheckoutSubscriptionFormModel($this->loggerService);
                            $checkoutChargeHandler = new MM_WPFS_CheckoutSubscriptionChargeHandler($this->loggerService);
                        } elseif (MM_WPFS_Utils::isCheckoutDonationFormType($popupFormSubmit->formType)) {
                            $formModel = new MM_WPFS_Public_CheckoutDonationFormModel($this->loggerService);
                            $checkoutChargeHandler = new MM_WPFS_CheckoutDonationChargeHandler($this->loggerService);
                        }

                        if (!is_null($formModel) && !is_null($checkoutChargeHandler)) {
                            $postData = $formModel->extractFormModelDataFromPopupFormSubmit($popupFormSubmit);
                            $checkoutSessionData = $formModel->extractFormModelDataFromCheckoutSession($checkoutSession);
                            $postData = array_merge($postData, $checkoutSessionData);
                            $formModel->bindByArray(
                                $postData
                            );

                            $chargeResult = $checkoutChargeHandler->handle($formModel, $checkoutSession);

                            if ($chargeResult->isSuccess()) {
                                $this->checkoutSubmissionService->updateSubmitEntryWithSuccess($popupFormSubmit, $chargeResult->getMessageTitle(), $chargeResult->getMessage());
                                $redirectURL = $popupFormSubmit->referrer;
                                if ($chargeResult->isRedirect()) {
                                    $redirectURL = $chargeResult->getRedirectURL();
                                }
                                wp_redirect($redirectURL);

                                $this->logger->debug(__FUNCTION__, 'Submit entry successfully processed, redirect to=' . $redirectURL);
                            } else {
                                $this->checkoutSubmissionService->updateSubmitEntryWithFailed($popupFormSubmit);
                                wp_redirect($popupFormSubmit->referrer);

                                $this->logger->debug(__FUNCTION__, 'Submit entry failed, redirect to=' . $popupFormSubmit->referrer);
                            }
                        } else {
                            $this->logger->debug(__FUNCTION__, "Cannot find handler and form model for form type '" . $popupFormSubmit->formType . "'.");
                        }
                    } else {
                        // tnagy mark submission as failed
                        $this->checkoutSubmissionService->updateSubmitEntryWithCancelled($popupFormSubmit);
                        wp_redirect($popupFormSubmit->referrer);

                        $this->logger->debug(__FUNCTION__, "Submit entry cancelled, redirect to=" . $popupFormSubmit->referrer);
                    }
                } else {
                    // tnagy submit entry not found
                    $this->logger->error(__FUNCTION__, 'Submit entry not found: submitHash=' . $submitHash . ', submitStatus=' . $submitStatus);

                    status_header(500);
                }

            } else {
                // tnagy submit hash and/or submit status is empty
                $this->logger->error(__FUNCTION__, 'SubmitHash and/or submitStatus is empty: submitHash=' . $submitHash . ', submitStatus=' . $submitStatus);

                status_header(500);
            }

        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Error handling the checkout session', $ex);

            if (isset($popupFormSubmit)) {
                $this->checkoutSubmissionService->updateSubmitEntryWithFailed($popupFormSubmit, __('Internal Error', 'wp-full-stripe'), MM_WPFS_Localization::translateLabel($ex->getMessage()));
                wp_redirect($popupFormSubmit->referrer);
            } else {
                status_header(500);
            }
        }

        $this->logger->debug(__FUNCTION__, 'FINISHED');

        exit;
    }

    protected function createCustomerContext($formModel)
    {
        if (
            $formModel instanceof MM_WPFS_Public_PaymentFormModel ||
            $formModel instanceof MM_WPFS_Public_SubscriptionFormModel
        ) {
            return (new MM_WPFS_CustomerContextCreator_PaymentForm($formModel))->getContext();
        } else if ($formModel instanceof MM_WPFS_Public_DonationFormModel) {
            return (new MM_WPFS_CustomerContextCreator_DonationForm($formModel))->getContext();
        } else {
            throw new Exception(__CLASS__ . '::' . __FUNCTION__ . '(): form model type not supported.');
        }
    }

    protected function createSubscriptionContext($formModel, $transactionData)
    {
        if ($formModel instanceof MM_WPFS_Public_SubscriptionFormModel) {
            return (new MM_WPFS_SubscriptionContextCreator($formModel, $transactionData))->getContext();
        } else {
            throw new Exception(__CLASS__ . '::' . __FUNCTION__ . '(): form model type not supported.');
        }
    }

    /**
     * @param $formModel MM_WPFS_Public_SubscriptionFormModel
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     * @param $options MM_WPFS_CreateSubscriptionOptions
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    protected function createSubscription($formModel, $transactionData, $options)
    {
        return $this->stripe->createSubscriptionForCustomer($this->createSubscriptionContext($formModel, $transactionData), $options);
    }

    function get_Setup_Intent_Client_Secret()
    {
        $result = null;
        try {
            $result = $this->stripe->createSetupIntent();
            $result = $result->client_secret;
        } catch (Exception $ex) {
            $result = array(
                'success' => false,
                'messageTitle' =>
                    /* translators: Banner title of internal error */
                    __('Internal Error', 'wp-full-stripe'),
                'message' => MM_WPFS_Localization::translateLabel($ex->getMessage()),
                'exceptionMessage' => $ex->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode(array('clientSecret' => $result));
        exit;
    }

    function fullstripe_inline_payment_charge()
    {

        try {

            $paymentFormModel = new MM_WPFS_Public_InlinePaymentFormModel($this->loggerService);
            $bindingResult = $paymentFormModel->bind();

            if ($bindingResult->hasErrors()) {
                $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
            } else {
                if (MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $paymentFormModel->getForm()->customAmount) {
                    $result = $this->processSetupIntent($paymentFormModel);
                } else {
                    $result = $this->processPaymentIntentCharge($paymentFormModel);
                }
                $return = $result->getAsArray();
            }
        } catch (WPFS_UserFriendlyException $ex) {
            $this->logger->error(__FUNCTION__, 'User-friendly exception while handling payment charge', $ex);

            $messageTitle = is_null($ex->getTitle()) ?
                /* translators: Banner title of an error returned from an extension point by a developer */
                __('Internal Error', 'wp-full-stripe') :
                $ex->getTitle();
            $message = $ex->getMessage();
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (\StripeWPFS\Exception\CardException $ex) {
            $this->logger->error(__FUNCTION__, 'Stripe card exception while handling payment charge', $ex);

            $messageTitle =
                /* translators: Banner title of error returned by Stripe */
                __('Stripe Error', 'wp-full-stripe');
            $message = $this->stripe->resolveErrorMessageByCode($ex->getCode());
            if (is_null($message)) {
                $message = MM_WPFS_Localization::translateLabel($ex->getMessage());
            }
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Generic exception while handling payment charge', $ex);

            $return = array(
                'success' => false,
                'messageTitle' =>
                    /* translators: Banner title of internal error */
                    __('Internal Error', 'wp-full-stripe'),
                'message' => MM_WPFS_Localization::translateLabel($ex->getMessage()),
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (Error $err) {
            $this->logger->error(__FUNCTION__, 'Generic error while handling payment charge', $err);

            $return = array(
                'success' => false,
                'messageTitle' =>
                    /* translators: Banner title of internal error */
                    __('Internal Error', 'wp-full-stripe'),
                'message' => MM_WPFS_Localization::translateLabel($err->getMessage()),
                'exceptionMessage' => $err->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode(apply_filters('fullstripe_inline_payment_charge_return_message', $return));
        exit;

    }

    function fullstripe_inline_donation_charge()
    {

        try {

            $donationFormModel = new MM_WPFS_Public_InlineDonationFormModel($this->loggerService);
            $bindingResult = $donationFormModel->bind();

            if ($bindingResult->hasErrors()) {
                $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
            } else {
                $result = $this->processDonationPaymentIntentCharge($donationFormModel);
                $return = $result->getAsArray();
            }
        } catch (WPFS_UserFriendlyException $ex) {
            $this->logger->error(__FUNCTION__, 'User-friendly exception while handling donation charge', $ex);

            $messageTitle = is_null($ex->getTitle()) ?
                /* translators: Banner title of an error returned from an extension point by a developer */
                __('Internal Error', 'wp-full-stripe') :
                $ex->getTitle();
            $message = $ex->getMessage();
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (\StripeWPFS\Exception\CardException $ex) {
            $this->logger->error(__FUNCTION__, 'Stripe card exception while handling donation charge', $ex);

            $messageTitle =
                /* translators: Banner title of error returned by Stripe */
                __('Stripe Error', 'wp-full-stripe');
            $message = $this->stripe->resolveErrorMessageByCode($ex->getCode());
            if (is_null($message)) {
                $message = MM_WPFS_Localization::translateLabel($ex->getMessage());
            }
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Generic exception while handling donation charge', $ex);

            $return = array(
                'success' => false,
                'messageTitle' =>
                    /* translators: Banner title of internal error */
                    __('Internal Error', 'wp-full-stripe'),
                'message' => MM_WPFS_Localization::translateLabel($ex->getMessage()),
                'exceptionMessage' => $ex->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode(apply_filters('fullstripe_inline_donation_charge_return_message', $return));
        exit;

    }

    /**
     * @param $saveCardFormModel MM_WPFS_Public_InlinePaymentFormModel
     * @param $transactionData MM_WPFS_PaymentTransactionData
     */
    protected function fireBeforeInlineSaveCardAction($saveCardFormModel, $transactionData)
    {
        $params = array(
            'email' => $saveCardFormModel->getCardHolderEmail(),
            'urlParameters' => $saveCardFormModel->getFormGetParametersAsArray(),
            'formName' => $saveCardFormModel->getFormName(),
            'stripeClient' => $this->stripe->getStripeClient(),
        );

        do_action(MM_WPFS::ACTION_NAME_BEFORE_SAVE_CARD, $params);
    }

    /**
     * @param $saveCardFormModel MM_WPFS_Public_PaymentFormModel
     * @param $transactionData MM_WPFS_SaveCardTransactionData
     * @param $stripeCustomer \StripeWPFS\Customer
     */
    protected function fireAfterInlineSaveCardAction($saveCardFormModel, $transactionData, $stripeCustomer)
    {
        $replacer = new MM_WPFS_SaveCardMacroReplacer($saveCardFormModel->getForm(), $transactionData, $this->loggerService);

        $params = array(
            'email' => $saveCardFormModel->getCardHolderEmail(),
            'urlParameters' => $saveCardFormModel->getFormGetParametersAsArray(),
            'formName' => $saveCardFormModel->getFormName(),
            'stripeClient' => $this->stripe->getStripeClient(),
            'stripeCustomer' => $stripeCustomer,
            'rawPlaceholders' => $replacer->getRawKeyValuePairs(),
            'decoratedPlaceholders' => $replacer->getDecoratedKeyValuePairs(),
        );

        do_action(MM_WPFS::ACTION_NAME_AFTER_SAVE_CARD, $params);
    }

    /**
     * @param MM_WPFS_Public_PaymentFormModel $paymentFormModel
     *
     * @return MM_WPFS_ChargeResult
     */
    private function processSetupIntent($paymentFormModel)
    {
        $this->logger->debug(__FUNCTION__, 'CALLED');

        $setupIntentResult = new MM_WPFS_SetupIntentResult();

        if (empty($paymentFormModel->getStripeSetupIntentId())) {
            $this->logger->debug(__FUNCTION__, 'Creating SetupIntent...');

            $setupIntent = $this->stripe->createSetupIntentWithPaymentMethod($paymentFormModel->getStripePaymentMethodId());
            $setupIntent = $this->stripe->confirmSetupIntent($setupIntent);

        } else {
            $this->logger->debug(__FUNCTION__, 'Retrieving SetupIntent...');

            $setupIntent = $this->stripe->retrieveSetupIntent($paymentFormModel->getStripeSetupIntentId());
        }

        $transactionData = null;
        if (isset($setupIntent)) {
            if (
                \StripeWPFS\SetupIntent::STATUS_REQUIRES_ACTION === $setupIntent->status
                && 'use_stripe_sdk' === $setupIntent->next_action->type
            ) {
                $this->logger->debug(__FUNCTION__, 'SetupIntent requires action...');

                $setupIntentResult->setSuccess(false);
                $setupIntentResult->setRequiresAction(true);
                $setupIntentResult->setSetupIntentClientSecret($setupIntent->client_secret);
                $setupIntentResult->setMessageTitle(
                    /* translators: Banner title of pending transaction requiring a second factor authentication (SCA/PSD2) */
                    __('Action required', 'wp-full-stripe')
                );
                $setupIntentResult->setMessage(
                    /* translators: Banner message of a pending card saving transaction requiring a second factor authentication (SCA/PSD2) */
                    __('Saving this card requires additional action before completion!', 'wp-full-stripe')
                );
            } elseif (\StripeWPFS\SetupIntent::STATUS_SUCCEEDED === $setupIntent->status) {
                $this->logger->debug(__FUNCTION__, 'SetupIntent succeeded.');

                $this->fireBeforeInlineSaveCardAction($paymentFormModel, $transactionData);

                $createCustomerOptions = new MM_WPFS_CreateCustomerOptions();
                $createCustomerOptions->addMetadata = true;
                $this->createOrRetrieveCustomerByFormModel($paymentFormModel, $createCustomerOptions);

                $transactionData = MM_WPFS_TransactionDataService::createSaveCardDataByModel($paymentFormModel);
                $stripeCardSavedDescription = MM_WPFS_Utils::prepareStripeCardSavedDescription($this->staticContext, $paymentFormModel, $transactionData);

                $stripeCustomer = $paymentFormModel->getStripeCustomer();
                $stripeCustomer->description = $stripeCardSavedDescription;
                $this->stripe->updateCustomer($stripeCustomer);

                $paymentFormModel->setTransactionId($paymentFormModel->getStripeCustomer()->id);
                $transactionData->setTransactionId($paymentFormModel->getTransactionId());

                $this->db->insertSavedCard($paymentFormModel, $transactionData);

                $this->fireAfterInlineSaveCardAction($paymentFormModel, $transactionData, $stripeCustomer);

                $setupIntentResult->setRequiresAction(false);
                $setupIntentResult->setSuccess(true);
                $setupIntentResult->setMessageTitle(
                    /* translators: Banner title of successful transaction */
                    __('Success', 'wp-full-stripe')
                );
                $setupIntentResult->setMessage(
                    /* translators: Banner message of saving card successfully */
                    __('Saving Card Successful!', 'wp-full-stripe')
                );
            } else {
                $setupIntentResult->setSuccess(false);
                $setupIntentResult->setMessageTitle(
                    /* translators: Banner title of failed transaction */
                    __('Failed', 'wp-full-stripe')
                );
                $setupIntentResult->setMessage(
                    // This is an internal error, no need to localize it
                    sprintf("Invalid SetupIntent status '%s'.", $setupIntent->status)
                );
            }
        }

        $this->handleRedirect($paymentFormModel, $transactionData, $setupIntentResult);

        if ($setupIntentResult->isSuccess()) {
            if (MM_WPFS_Mailer::canSendSaveCardPluginReceipt($paymentFormModel->getForm())) {
                $this->mailer->sendSaveCardNotification($paymentFormModel->getForm(), $transactionData);
            }
        }

        return $setupIntentResult;
    }

    protected function determineTaxCountry($taxCountry, $billingAddress)
    {
        $result = null;
        $billingCountry = !is_null($billingAddress) ? $billingAddress['country_code'] : null;

        if (!empty($billingCountry)) {
            $result = $billingCountry;
        }
        if (is_null($result) && !empty($taxCountry)) {
            $result = $taxCountry;
        }

        return $result;
    }

    private function createOrRetrieveCustomerByFormModel($formModel, $options)
    {
        $createOrRetrieveResult = $this->createOrRetrieveCustomer($this->createCustomerContext($formModel), $options);

        $formModel->setStripeCustomer($createOrRetrieveResult->getCustomer());
        $formModel->setStripePaymentMethod($createOrRetrieveResult->getPaymentMethod());
    }

    /**
     * Updates the \StripeWPFS\Customer object's address property with an appropriate address array.
     *
     * @param $stripeCustomer \StripeWPFS\Customer
     * @param $ctx MM_WPFS_CreateCustomerContext
     */
    public function updateCustomerBillingAddress(&$stripeCustomer, $ctx)
    {
        $stripeArrayHash = MM_WPFS_Utils::prepareStripeBillingAddressHashFromArray($ctx->billingAddress);
        if (isset($stripeArrayHash)) {
            $stripeCustomer->address = $stripeArrayHash;
        }
        if (!empty($ctx->billingName)) {
            $stripeCustomer->name = $ctx->billingName;
        }
    }

    /**
     * @param $ctx MM_WPFS_CreateCustomerContext
     * @return array
     */
    protected function prepareTaxAddress($ctx)
    {
        $result = array();

        if (!empty($ctx->taxState)) {
            $result['state'] = $ctx->taxState;
        }
        if (!empty($ctx->taxCountry)) {
            $result['country'] = $ctx->taxCountry;
        }
        if (!empty($ctx->taxPostalCode)) {
            $result['postal_code'] = $ctx->taxPostalCode;
        }

        return $result;
    }

    protected function updateCustomerTaxAddress(&$stripeCustomer, $ctx)
    {
        $stripeCustomer->address = $this->prepareTaxAddress($ctx);
    }

    /**
     * Updates the \StripeWPFS\Customer object's shipping property with an appropriate address array.
     *
     * @param $stripeCustomer \StripeWPFS\Customer
     * @param $shippingName
     * @param $shippingPhone
     * @param $shippingAddress array
     */
    public function updateCustomerShippingAddress(&$stripeCustomer, $shippingName, $shippingPhone, $shippingAddress)
    {
        $stripeShippingHash = MM_WPFS_Utils::prepareStripeShippingHashFromArray($shippingName, $shippingPhone, $shippingAddress);
        $stripeCustomer->shipping = $stripeShippingHash;
    }

    protected function isTaxIdAddedToCustomer($stripeCustomerId, $taxIdType, $taxId)
    {
        $result = false;

        $taxIdItems = $this->stripe->getTaxIdsForCustomer($stripeCustomerId);
        foreach ($taxIdItems->data as $taxIdItem) {
            if ($taxIdItem->type === $taxIdType && $taxIdItem->value === $taxId) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * This function creates or retrieves a Stripe Customer. As a first step it validates the given PaymentMethod's CVC
     * check then tries to retrieve an existing Stripe Customer by the given email address.
     * If no Stripe Customer has been found then it tries to retrieve a Customer stored by the PaymentMethod and if
     * there is no Stripe Customer at all then creates one.
     * If an existing Stripe Customer was found then it tries to attach the given PaymentMethod if the PaymentMethod's
     * card fingerprint is not currently found in the list of PaymentMethods currently attached to this Customer to
     * avoid the duplication of identical PaymentMethods.
     *
     * @param $ctx MM_WPFS_CreateCustomerContext
     * @param $optins MM_WPFS_CreateCustomerOptions
     *
     * @return MM_WPFS_CreateOrRetrieveCustomerResult
     * @throws Exception
     */
    private function createOrRetrieveCustomer($ctx, $options)
    {
        $result = new MM_WPFS_CreateOrRetrieveCustomerResult();

        $paymentMethod = $this->stripe->validatePaymentMethodCVCCheck($ctx->paymentMethodId);

        $stripeCustomer = $this->findExistingStripeCustomerAnywhereByEmail($ctx->cardHolderEmail);
        if (!isset($stripeCustomer) && isset($paymentMethod->customer)) {
            $stripeCustomer = $this->stripe->retrieveCustomer($paymentMethod->customer);
        }
        if (!isset($stripeCustomer)) {
            $this->logger->debug(__FUNCTION__, "Creating Stripe Customer with PaymentMethod...");
            $metadata = $options->addMetadata ? $ctx->metadata : null;
            $metadata['webhookUrl'] = esc_attr(MM_WPFS_EventHandler::getWebhookEndpointURL($this->staticContext));

            $stripeCustomer = $this->stripe->createCustomerWithPaymentMethod(
                $paymentMethod->id,
                MM_WPFS_Utils::determineCustomerName($ctx->cardHolderName, $ctx->businessName, $ctx->billingName),
                $ctx->cardHolderEmail,
                $metadata,
                $ctx->taxIdType,
                $ctx->taxId
            );
        } else {
            $this->logger->debug(__FUNCTION__, "Attaching PaymentMethod to existing Stripe Customer...");

            $attachedPaymentMethod = $this->stripe->attachPaymentMethodToCustomerIfMissing(
                $stripeCustomer,
                $paymentMethod,
                /* set to default */
                true
            );
            $paymentMethod = $attachedPaymentMethod;

            if ($options->addMetadata) {
                $stripeCustomer->metadata = $ctx->metadata;
            }

            if (!empty($ctx->taxIdType) && !empty($ctx->taxId)) {
                if (!$this->isTaxIdAddedtoCustomer($stripeCustomer->id, $ctx->taxIdType, $ctx->taxId)) {
                    $this->stripe->createTaxIdForCustomer($stripeCustomer->id, $ctx->taxIdType, $ctx->taxId);
                }
            }
        }

        if (!is_null($ctx->billingAddress)) {
            $this->updateCustomerBillingAddress($stripeCustomer, $ctx);
        } else if (!empty($ctx->taxCountry)) {
            $this->updateCustomerTaxAddress($stripeCustomer, $ctx);
        }
        if (!is_null($ctx->shippingAddress)) {
            $this->updateCustomerShippingAddress($stripeCustomer, $ctx->shippingName, $ctx->cardHolderPhone, $ctx->shippingAddress);
        }

        $stripeCustomer->name = MM_WPFS_Utils::determineCustomerName($ctx->cardHolderName, $ctx->businessName, $ctx->billingName);

        $this->stripe->updateCustomer($stripeCustomer);

        $result->setPaymentMethod($paymentMethod);
        $result->setCustomer($stripeCustomer);

        return $result;
    }

    /**
     * @param $paymentIntentResult MM_WPFS_DonationPaymentIntentResult
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     * @param $title string
     * @param $message string
     *
     * @return MM_WPFS_DonationPaymentIntentResult
     */
    protected function createPaymentIntentResultActionRequired(&$paymentIntentResult, $paymentIntent, $title, $message)
    {
        $paymentIntentResult->setSuccess(false);
        $paymentIntentResult->setRequiresAction(true);
        $paymentIntentResult->setPaymentIntentClientSecret($paymentIntent->client_secret);
        $paymentIntentResult->setIsManualConfirmation($paymentIntent->confirmation_method === 'manual');
        $paymentIntentResult->setMessageTitle($title);
        $paymentIntentResult->setMessage($message);

        return $paymentIntentResult;
    }

    /**
     * @param $paymentIntentResult MM_WPFS_DonationPaymentIntentResult
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     * @param $title string
     * @param $message string
     *
     * @return MM_WPFS_DonationPaymentIntentResult
     */
    protected function createPaymentIntentResultSuccess(&$paymentIntentResult, $title, $message)
    {
        $paymentIntentResult->setRequiresAction(false);
        $paymentIntentResult->setSuccess(true);
        $paymentIntentResult->setMessageTitle($title);
        $paymentIntentResult->setMessage($message);

        return $paymentIntentResult;
    }

    /**
     * @param $paymentIntentResult MM_WPFS_DonationPaymentIntentResult
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     * @param $title string
     * @param $message string
     *
     * @return MM_WPFS_DonationPaymentIntentResult
     */
    protected function createPaymentIntentResultFailed(&$paymentIntentResult, $title, $message)
    {
        $paymentIntentResult->setSuccess(false);
        $paymentIntentResult->setMessageTitle($title);
        $paymentIntentResult->setMessage($message);

        return $paymentIntentResult;
    }

    /**
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     * @param $formName
     */
    protected function addFormNameToPaymentIntent(&$paymentIntent, $formName)
    {
        $paymentIntent->wpfs_form = $formName;
    }

    /**
     * @param $formModel MM_WPFS_Public_FormModel
     *
     * @return boolean
     */
    protected function modelNeedsPaymentIntent($formModel)
    {
        return empty($formModel->getStripePaymentIntentId());
    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     * @param $transactionData MM_WPFS_DonationTransactionData
     *
     * @return \StripeWPFS\PaymentIntent
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    protected function createPaymentIntentForDonation($donationFormModel, $transactionData)
    {
        $donationDescription = MM_WPFS_Utils::prepareStripeDonationDescription($this->staticContext, $donationFormModel, $transactionData);
        if ($donationFormModel->getForm()->generateInvoice == 1) {
            $createInvoiceOptions = new MM_WPFS_CreateOneTimeInvoiceOptions();
            $createInvoiceOptions->autoAdvance = true;
            $stripeInvoice = $this->createInvoiceForOneTimePaymentByFormModel($donationFormModel, $createInvoiceOptions);

            $finalizedInvoice = $this->stripe->finalizeInvoice($stripeInvoice->id);

            $this->stripe->updatePaymentIntentByInvoice(
                $finalizedInvoice,
                $donationFormModel->getStripePaymentMethodId(),
                $donationDescription,
                $donationFormModel->getMetadata(),
                MM_WPFS_Mailer::canSendDonationStripeReceipt($donationFormModel->getForm()) ? $donationFormModel->getCardHolderEmail() : null
            );

            $paymentIntent = $this->stripe->retrievePaymentIntent($finalizedInvoice->payment_intent);
        } else {
            $metadata = $donationFormModel->getMetadata();
            $metadata['webhookUrl'] = esc_attr(MM_WPFS_EventHandler::getWebhookEndpointURL($this->staticContext));
            $paymentIntent = $this->stripe->createPaymentIntent(
                $donationFormModel->getStripePaymentMethodId(),
                $donationFormModel->getStripeCustomer()->id,
                $donationFormModel->getForm()->currency,
                $donationFormModel->getAmount(),
                true,
                $donationDescription,
                $metadata,
                MM_WPFS_Mailer::canSendDonationStripeReceipt($donationFormModel->getForm()) ? $donationFormModel->getCardHolderEmail() : null
            );
        }

        return $paymentIntent;
    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     * @param $transactionData MM_WPFS_DonationTransactionData
     *
     * @return \StripeWPFS\PaymentIntent
     * @throws Exception
     */
    protected function createOrRetrievePaymentIntentForDonation($donationFormModel, $transactionData)
    {
        $paymentIntent = null;

        if ($this->modelNeedsPaymentIntent($donationFormModel)) {
            $paymentIntent = $this->createPaymentIntentForDonation($donationFormModel, $transactionData);

            $donationFormModel->setTransactionId($paymentIntent->id);
            $transactionData->setTransactionId($donationFormModel->getTransactionId());
        } else {
            $paymentIntent = $this->stripe->retrievePaymentIntent($donationFormModel->getStripePaymentIntentId());

            if (isset($paymentIntent)) {
                if (\StripeWPFS\PaymentIntent::STATUS_REQUIRES_CONFIRMATION === $paymentIntent->status) {
                    $paymentIntent->confirm();
                }

                $donationFormModel->setTransactionId($paymentIntent->id);
                $transactionData->setTransactionId($donationFormModel->getTransactionId());
            }
        }

        return $paymentIntent;
    }

    /**
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     *
     * @return boolean
     */
    protected function paymentIntentRequiresAction($paymentIntent)
    {
        return \StripeWPFS\PaymentIntent::STATUS_REQUIRES_ACTION === $paymentIntent->status
            && 'use_stripe_sdk' === $paymentIntent->next_action->type;
    }

    /**
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     *
     * @return boolean
     */
    protected function paymentIntentSucceeded($paymentIntent)
    {
        return \StripeWPFS\PaymentIntent::STATUS_SUCCEEDED === $paymentIntent->status
            || \StripeWPFS\PaymentIntent::STATUS_REQUIRES_CAPTURE === $paymentIntent->status;
    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     */
    protected function fireBeforeInlineDonationAction($donationFormModel, $transactionData)
    {
        $params = array(
            'email' => $donationFormModel->getCardHolderEmail(),
            'urlParameters' => $donationFormModel->getFormGetParametersAsArray(),
            'formName' => $donationFormModel->getFormName(),
            'currency' => $transactionData->getCurrency(),
            'frequency' => $donationFormModel->getDonationFrequency(),
            'amount' => $donationFormModel->getAmount(),
            'stripeClient' => $this->stripe->getStripeClient()
        );

        do_action(MM_WPFS::ACTION_NAME_BEFORE_DONATION_CHARGE, $params);
    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     */
    protected function fireAfterInlineDonationAction($donationFormModel, $transactionData, $paymentIntent)
    {
        $replacer = new MM_WPFS_DonationMacroReplacer($donationFormModel->getForm(), $transactionData, $this->loggerService);

        $params = array(
            'email' => $donationFormModel->getCardHolderEmail(),
            'urlParameters' => $donationFormModel->getFormGetParametersAsArray(),
            'formName' => $donationFormModel->getFormName(),
            'currency' => $transactionData->getCurrency(),
            'frequency' => $donationFormModel->getDonationFrequency(),
            'amount' => $donationFormModel->getAmount(),
            'stripeClient' => $this->stripe->getStripeClient(),
            'stripePaymentIntent' => $paymentIntent,
            'stripeSubscription' => $donationFormModel->getStripeSubscription(),
            'rawPlaceholders' => $replacer->getRawKeyValuePairs(),
            'decoratedPlaceholders' => $replacer->getDecoratedKeyValuePairs(),
        );

        do_action(MM_WPFS::ACTION_NAME_AFTER_DONATION_CHARGE, $params);
    }

    /**
     * @param $paymentIntent
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData|MM_WPFS_DonationTransactionData
     */
    private function setInvoiceDataFromPaymentIntent($paymentIntent, $transactionData)
    {

        $charge = $this->stripe->getLatestCharge($paymentIntent);

        if (!empty($charge->invoice)) {
            $invoice = $this->stripe->retrieveInvoice($charge->invoice);

            $transactionData->setStripeInvoiceId($invoice->id);
            $transactionData->setInvoiceUrl($invoice->invoice_pdf);
            $transactionData->setInvoiceNumber($invoice->number);
        }
    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     *
     * @return MM_WPFS_ChargeResult
     * @throws Exception
     */
    private function processDonationPaymentIntentCharge($donationFormModel)
    {
        $paymentIntentResult = new MM_WPFS_DonationPaymentIntentResult();
        $paymentIntentResult->setNonce($donationFormModel->getNonce());

        $createCustomerOptions = new MM_WPFS_CreateCustomerOptions();
        $createCustomerOptions->addMetadata = false;
        $this->createOrRetrieveCustomerByFormModel($donationFormModel, $createCustomerOptions);

        $transactionData = MM_WPFS_TransactionDataService::createDonationDataByFormModel($donationFormModel);

        $this->fireBeforeInlineDonationAction($donationFormModel, $transactionData);

        $paymentIntent = $this->createOrRetrievePaymentIntentForDonation($donationFormModel, $transactionData);

        if (isset($paymentIntent) && $paymentIntent !== null) {
            if ($this->paymentIntentRequiresAction($paymentIntent)) {
                $this->createPaymentIntentResultActionRequired(
                    $paymentIntentResult,
                    $paymentIntent,
                    /* translators: Banner title of pending transaction requiring a second factor authentication (SCA/PSD2) */
                    __('Action required', 'wp-full-stripe'),
                    /* translators: Banner message of a one-time payment requiring a second factor authentication (SCA/PSD2) */
                    __('The donation needs additional action before completion!', 'wp-full-stripe')
                );
            } else if ($this->paymentIntentSucceeded($paymentIntent)) {
                $this->setInvoiceDataFromPaymentIntent($paymentIntent, $transactionData);
                $this->addFormNameToPaymentIntent($paymentIntent, $donationFormModel->getFormName());

                $subscription = null;
                if ($this->isRecurringDonation($donationFormModel)) {
                    $subscription = $this->createSubscriptionForDonation($donationFormModel);
                    $donationFormModel->setStripeSubscription($subscription);
                }
                $latest_charge = $this->stripe->getLatestCharge($paymentIntent);

                $this->db->insertInlineDonation($donationFormModel, $paymentIntent, $subscription, $latest_charge);

                $this->fireAfterInlineDonationAction($donationFormModel, $transactionData, $paymentIntent);

                $this->createPaymentIntentResultSuccess(
                    $paymentIntentResult,
                    /* translators: Banner title of successful transaction */
                    __('Success', 'wp-full-stripe'),
                    /* translators: Banner message of successful payment */
                    __('Donation Successful!', 'wp-full-stripe')
                );

            } else {

                $this->createPaymentIntentResultFailed(
                    $paymentIntentResult,
                    /* translators: Banner title of failed transaction */
                    __('Failed', 'wp-full-stripe'),
                    // This is an internal error, no need to localize it
                    sprintf("Invalid PaymentIntent status '%s'.", $paymentIntent->status)
                );
            }
        } else {
            $this->createPaymentIntentResultFailed(
                $paymentIntentResult,
                /* translators: Banner title of failed transaction */
                __('Failed', 'wp-full-stripe'),
                // This is an internal error, no need to localize it
                "PaymentIntent was neither created nor retrieved."
            );
        }

        $this->handleRedirect($donationFormModel, $transactionData, $paymentIntentResult);

        if ($paymentIntentResult->isSuccess()) {
            if (MM_WPFS_Mailer::canSendDonationPluginReceipt($donationFormModel->getForm())) {
                $this->mailer->sendDonationEmailReceipt($donationFormModel->getForm(), $transactionData);
            }
        }

        return $paymentIntentResult;
    }

    /**
     * @param $paymentFormModel MM_WPFS_Public_PaymentFormModel
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData
     */
    private function fireBeforeInlinePaymentAction($paymentFormModel, $transactionData)
    {
        $params = array(
            'email' => $paymentFormModel->getCardHolderEmail(),
            'urlParameters' => $paymentFormModel->getFormGetParametersAsArray(),
            'formName' => $paymentFormModel->getFormName(),
            'priceId' => $paymentFormModel->getPriceId(),
            'productName' => $paymentFormModel->getProductName(),
            'currency' => $transactionData->getCurrency(),
            'amount' => $paymentFormModel->getAmount(),
            'stripeClient' => $this->stripe->getStripeClient(),
        );

        do_action(MM_WPFS::ACTION_NAME_BEFORE_PAYMENT_CHARGE, $params);
    }

    /**
     * @param $paymentFormModel MM_WPFS_Public_PaymentFormModel
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     */
    private function fireAfterInlinePaymentAction($paymentFormModel, $transactionData, $paymentIntent)
    {
        $replacer = new MM_WPFS_OneTimePaymentMacroReplacer($paymentFormModel->getForm(), $transactionData, $this->loggerService);

        $params = array(
            'email' => $paymentFormModel->getCardHolderEmail(),
            'urlParameters' => $paymentFormModel->getFormGetParametersAsArray(),
            'formName' => $paymentFormModel->getFormName(),
            'priceId' => $paymentFormModel->getPriceId(),
            'productName' => $paymentFormModel->getProductName(),
            'currency' => $transactionData->getCurrency(),
            'amount' => $transactionData->getAmount(),
            'stripeClient' => $this->stripe->getStripeClient(),
            'stripePaymentIntent' => $paymentIntent,
            'rawPlaceholders' => $replacer->getRawKeyValuePairs(),
            'decoratedPlaceholders' => $replacer->getDecoratedKeyValuePairs(),
        );

        do_action(MM_WPFS::ACTION_NAME_AFTER_PAYMENT_CHARGE, $params);
    }

    /**
     * @param $formModel MM_WPFS_Public_PaymentFormModel
     * @return mixed
     */
    protected function getTaxCountry($formModel)
    {
        $result = null;

        $result = $formModel->getBillingAddressCountry();
        if (empty($result)) {
            $result = $formModel->getTaxCountry();
        }

        return $result;
    }

    /**
     * @param $formModel MM_WPFS_Public_PaymentFormModel
     * @return null
     */
    protected function getTaxState($formModel)
    {
        $result = null;

        $result = $formModel->getBillingAddressState();
        if (empty($result)) {
            $result = $formModel->getTaxState();
        }

        return $result;
    }

    /**
     * @param $formModel MM_WPFS_Public_PaymentFormModel|MM_WPFS_Public_SubscriptionFormModel
     */
    protected function getApplicableTaxRates($formModel)
    {
        $result = [];

        if ($formModel->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED) {
            $result = json_decode($formModel->getForm()->vatRates);
        } else if ($formModel->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC) {
            $result = MM_WPFS_PriceCalculator::filterApplicableTaxRatesStatic(
                $this->getTaxCountry($formModel),
                $this->getTaxState($formModel),
                json_decode($formModel->getForm()->vatRates),
                $formModel->getTaxId()
            );
        }

        return $result;
    }

    /**
     * @param $transactionData MM_WPFS_PaymentTransactionData
     * @param $invoice \StripeWPFS\Invoice
     */
    private function updatePaymentTransactionDataPricing($transactionData, $invoice)
    {
        $pricingDetails = MM_WPFS_Pricing::extractSimplifiedPricingFromInvoiceLineItems($invoice->lines->data);

        $transactionData->setProductAmountDiscount($pricingDetails->discountAmount);
        $transactionData->setProductAmountNet($pricingDetails->totalAmount - $pricingDetails->discountAmount - $pricingDetails->taxAmountInclusive);
        $transactionData->setProductAmountTax($pricingDetails->taxAmountExclusive + $pricingDetails->taxAmountInclusive);
        $transactionData->setProductAmountGross($transactionData->getProductAmountNet() + $transactionData->getProductAmountTax());
        $transactionData->setAmount($transactionData->getProductAmountGross());
    }

    /**
     * @param MM_WPFS_Public_PaymentFormModel $paymentFormModel
     *
     * @return MM_WPFS_ChargeResult
     * @throws Exception
     */
    private function processPaymentIntentCharge($paymentFormModel)
    {
        $this->logger->debug(__FUNCTION__, "CALLED");


        $paymentIntentResult = new MM_WPFS_PaymentIntentResult();
        $paymentIntentResult->setNonce($paymentFormModel->getNonce());

        $createCustomerOptions = new MM_WPFS_CreateCustomerOptions();
        $createCustomerOptions->addMetadata = false;
        $this->createOrRetrieveCustomerByFormModel($paymentFormModel, $createCustomerOptions);

        $transactionData = MM_WPFS_TransactionDataService::createOneTimePaymentDataByModel($paymentFormModel);
        $stripeChargeDescription = MM_WPFS_Utils::prepareStripeChargeDescription($this->staticContext, $paymentFormModel, $transactionData);

        $this->fireBeforeInlinePaymentAction($paymentFormModel, $transactionData);

        if (empty($paymentFormModel->getStripePaymentIntentId())) {
            $taxRateIds = MM_WPFS_Pricing::extractTaxRateIdsStatic($this->getApplicableTaxRates($paymentFormModel));

            if ($paymentFormModel->getForm()->generateInvoice == 1) {
                if (MM_WPFS_Utils::hasToCapturePaymentIntentByFormModel($paymentFormModel)) {
                    $createInvoiceOptions = new MM_WPFS_CreateOneTimeInvoiceOptions();
                    $createInvoiceOptions->autoAdvance = true;
                    $createInvoiceOptions->taxRateIds = $taxRateIds;
                    $stripeInvoice = $this->createInvoiceForOneTimePaymentByFormModel($paymentFormModel, $createInvoiceOptions);

                    $finalizedInvoice = $this->stripe->finalizeInvoice($stripeInvoice->id);

                    $this->updatePaymentTransactionDataPricing($transactionData, $finalizedInvoice);
                    $transactionData->setStripeInvoiceId($finalizedInvoice->id);
                    $transactionData->setInvoiceUrl($finalizedInvoice->invoice_pdf);
                    $transactionData->setInvoiceNumber($finalizedInvoice->number);

                    $this->stripe->updatePaymentIntentByInvoice(
                        $finalizedInvoice,
                        $paymentFormModel->getStripePaymentMethodId(),
                        $stripeChargeDescription,
                        $paymentFormModel->getMetadata(),
                        MM_WPFS_Mailer::canSendPaymentStripeReceipt($paymentFormModel->getForm()) ? $paymentFormModel->getCardHolderEmail() : null
                    );

                    $paymentIntent = $this->stripe->retrievePaymentIntent($finalizedInvoice->payment_intent);
                    $paymentFormModel->setTransactionId($paymentIntent->id);
                    $transactionData->setTransactionId($paymentFormModel->getTransactionId());
                } else {
                    $createInvoiceOptions = new MM_WPFS_CreateOneTimeInvoiceOptions();
                    $createInvoiceOptions->autoAdvance = false;
                    $createInvoiceOptions->taxRateIds = $taxRateIds;
                    $stripeInvoice = $this->createInvoiceForOneTimePaymentByFormModel($paymentFormModel, $createInvoiceOptions);

                    $paidStripeInvoice = $this->stripe->payInvoiceOutOfBand($stripeInvoice->id);

                    $this->updatePaymentTransactionDataPricing($transactionData, $paidStripeInvoice);
                    $transactionData->setStripeInvoiceId($paidStripeInvoice->id);
                    $transactionData->setInvoiceUrl($paidStripeInvoice->invoice_pdf);
                    $transactionData->setInvoiceNumber($paidStripeInvoice->number);

                    $metadata = $paymentFormModel->getMetadata();
                    $metadata['webhookUrl'] = esc_attr(MM_WPFS_EventHandler::getWebhookEndpointURL($this->staticContext));
                    $paymentIntent = $this->stripe->createPaymentIntent(
                        $paymentFormModel->getStripePaymentMethod()->id,
                        $paymentFormModel->getStripeCustomer()->id,
                        $paymentFormModel->getForm()->currency,
                        $transactionData->getProductAmountGross(),
                        false,
                        $stripeChargeDescription,
                        $metadata,
                        MM_WPFS_Mailer::canSendPaymentStripeReceipt($paymentFormModel->getForm()) ? $paymentFormModel->getCardHolderEmail() : null
                    );
                    $paymentFormModel->setTransactionId($paymentIntent->id);
                    $transactionData->setTransactionId($paymentFormModel->getTransactionId());
                }
            } else {
                $createInvoiceOptions = new MM_WPFS_CreateOneTimeInvoiceOptions();
                $createInvoiceOptions->autoAdvance = true;
                $createInvoiceOptions->taxRateIds = $taxRateIds;
                $previewInvoice = $this->createPreviewInvoiceForOneTimePaymentByFormModel($paymentFormModel, $createInvoiceOptions);

                $this->updatePaymentTransactionDataPricing($transactionData, $previewInvoice);
                $transactionData->setStripeInvoiceId(null);
                $transactionData->setInvoiceUrl(null);
                $transactionData->setInvoiceNumber(null);

                $metadata = $paymentFormModel->getMetadata();
                $metadata['webhookUrl'] = esc_attr(MM_WPFS_EventHandler::getWebhookEndpointURL($this->staticContext));
                $paymentIntent = $this->stripe->createPaymentIntent(
                    $paymentFormModel->getStripePaymentMethodId(),
                    $paymentFormModel->getStripeCustomer()->id,
                    $paymentFormModel->getForm()->currency,
                    $transactionData->getProductAmountGross(),
                    MM_WPFS_Utils::hasToCapturePaymentIntentByFormModel($paymentFormModel),
                    $stripeChargeDescription,
                    $metadata,
                    MM_WPFS_Mailer::canSendPaymentStripeReceipt($paymentFormModel->getForm()) ? $paymentFormModel->getCardHolderEmail() : null
                );
                $paymentFormModel->setTransactionId($paymentIntent->id);
                $transactionData->setTransactionId($paymentFormModel->getTransactionId());
            }
        } else {

            $this->logger->debug(__FUNCTION__, "Retrieving PaymentIntent...");

            $paymentIntent = $this->stripe->retrievePaymentIntent($paymentFormModel->getStripePaymentIntentId());
            if (isset($paymentIntent)) {
                if (\StripeWPFS\PaymentIntent::STATUS_REQUIRES_CONFIRMATION === $paymentIntent->status) {
                    $paymentIntent->confirm();
                }

                $paymentFormModel->setTransactionId($paymentIntent->id);
                $transactionData->setTransactionId($paymentFormModel->getTransactionId());
            }
        }

        if (isset($paymentIntent)) {
            if (
                \StripeWPFS\PaymentIntent::STATUS_REQUIRES_ACTION === $paymentIntent->status
                && 'use_stripe_sdk' === $paymentIntent->next_action->type
            ) {
                $this->logger->debug(__FUNCTION__, "PaymentIntent requires action...");

                $paymentIntentResult->setSuccess(false);
                $paymentIntentResult->setIsManualConfirmation($paymentIntent->confirmation_method === 'manual');
                $paymentIntentResult->setRequiresAction(true);
                $paymentIntentResult->setPaymentIntentClientSecret($paymentIntent->client_secret);
                $paymentIntentResult->setMessageTitle(
                    /* translators: Banner title of pending transaction requiring a second factor authentication (SCA/PSD2) */
                    __('Action required', 'wp-full-stripe')
                );
                $paymentIntentResult->setMessage(
                    /* translators: Banner message of a one-time payment requiring a second factor authentication (SCA/PSD2) */
                    __('The payment needs additional action before completion!', 'wp-full-stripe')
                );
            } elseif (
                \StripeWPFS\PaymentIntent::STATUS_SUCCEEDED === $paymentIntent->status
                || \StripeWPFS\PaymentIntent::STATUS_REQUIRES_CAPTURE === $paymentIntent->status
            ) {
                $this->logger->debug(__FUNCTION__, "processPaymentIntentCharge(): PaymentIntent succeeded.");

                $paymentIntent->wpfs_form = $paymentFormModel->getFormName();
                $paymentFormModel->setStripePaymentIntent($paymentIntent);
                $latest_charge = $this->stripe->getLatestCharge($paymentIntent);

                $this->db->insertPayment($paymentFormModel, $transactionData, $latest_charge);

                $this->fireAfterInlinePaymentAction($paymentFormModel, $transactionData, $paymentIntent);

                $paymentIntentResult->setRequiresAction(false);
                $paymentIntentResult->setSuccess(true);
                $paymentIntentResult->setMessageTitle(
                    /* translators: Banner title of successful transaction */
                    __('Success', 'wp-full-stripe')
                );
                $paymentIntentResult->setMessage(
                    /* translators: Banner message of successful payment */
                    __('Payment Successful!', 'wp-full-stripe')
                );
            } else {
                $paymentIntentResult->setSuccess(false);
                $paymentIntentResult->setMessageTitle(
                    /* translators: Banner title of failed transaction */
                    __('Failed', 'wp-full-stripe')
                );
                $paymentIntentResult->setMessage(
                    // This is an internal error, no need to localize it
                    sprintf("Invalid PaymentIntent status '%s'.", $paymentIntent->status)
                );
            }
        }

        $this->handleRedirect($paymentFormModel, $transactionData, $paymentIntentResult);

        if ($paymentIntentResult->isSuccess()) {
            if (MM_WPFS_Mailer::canSendPaymentPluginReceipt($paymentFormModel->getForm())) {
                $this->mailer->sendOneTimePaymentReceipt($paymentFormModel->getForm(), $transactionData);
            }
        }

        return $paymentIntentResult;
    }

    /**
     * @param MM_WPFS_TransactionResult $transactionResult
     *
     * @return array
     */
    private function generateReturnValueFromTransactionResult($transactionResult)
    {
        $returnValue = array(
            'success' => $transactionResult->isSuccess(),
            'messageTitle' => $transactionResult->getMessageTitle(),
            'message' => $transactionResult->getMessage(),
            'redirect' => $transactionResult->isRedirect(),
            'redirectURL' => $transactionResult->getRedirectURL(),
            'requiresAction' => $transactionResult->isRequiresAction(),
            'paymentIntentClientSecret' => $transactionResult->getPaymentIntentClientSecret(),
            'setupIntentClientSecret' => $transactionResult->getSetupIntentClientSecret(),
            'formType' => $transactionResult->getFormType(),
            'nonce' => $transactionResult->getNonce(),
        );

        return $returnValue;
    }

    function fullstripe_inline_subscription_charge()
    {

        try {

            $subscriptionFormModel = new MM_WPFS_Public_InlineSubscriptionFormModel($this->loggerService);
            $bindingResult = $subscriptionFormModel->bind();


            if ($bindingResult->hasErrors()) {
                $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
            } else {
                $subscriptionResult = $this->processSubscription($subscriptionFormModel);
                $return = self::generateReturnValueFromTransactionResult($subscriptionResult);
            }
        } catch (WPFS_UserFriendlyException $ex) {
            $this->logger->error(__FUNCTION__, "User-friendly exception while processing subscription charge", $ex);

            $messageTitle = is_null($ex->getTitle()) ?
                /* translators: Banner title of an error returned from an extension point by a developer */
                __('Internal Error', 'wp-full-stripe') :
                $ex->getTitle();
            $message = $ex->getMessage();
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (\StripeWPFS\Exception\CardException $ex) {
            $this->logger->error(__FUNCTION__, "Card exception while processing subscription charge", $ex);

            $messageTitle =
                /* translators: Banner title of error returned by Stripe */
                __('Stripe Error', 'wp-full-stripe');
            $message = $this->stripe->resolveErrorMessageByCode($ex->getCode());
            if (is_null($message)) {
                $message = MM_WPFS_Localization::translateLabel($ex->getMessage());
            }
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, "Generic exception while processing subscription charge", $ex);

            $return = array(
                'success' => false,
                'messageTitle' =>
                    /* Banner title of internal error */
                    __('Internal Error', 'wp-full-stripe'),
                'message' => MM_WPFS_Localization::translateLabel($ex->getMessage()),
                'exceptionMessage' => $ex->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode(apply_filters('fullstripe_inline_subscription_charge_return_message', $return));
        exit;

    }

    /**
     * @param $subscriptionFormModel MM_WPFS_Public_SubscriptionFormModel
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     */
    protected function fireBeforeInlineSubscriptionAction($subscriptionFormModel, $transactionData)
    {
        $params = array(
            'email' => $subscriptionFormModel->getCardHolderEmail(),
            'urlParameters' => $subscriptionFormModel->getFormGetParametersAsArray(),
            'formName' => $subscriptionFormModel->getFormName(),
            'productName' => $subscriptionFormModel->getProductName(),
            'planId' => $subscriptionFormModel->getStripePlanId(),
            'currency' => $transactionData->getPlanCurrency(),
            'amount' => $subscriptionFormModel->getPlanAmount(),
            'setupFee' => $subscriptionFormModel->getSetupFee(),
            'quantity' => $subscriptionFormModel->getStripePlanQuantity(),
            'stripeClient' => $this->stripe->getStripeClient(),
        );

        do_action(MM_WPFS::ACTION_NAME_BEFORE_SUBSCRIPTION_CHARGE, $params);
    }

    /**
     * @param $subscriptionFormModel MM_WPFS_Public_SubscriptionFormModel
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     * @param $subscription \StripeWPFS\Subscription
     */
    protected function fireAfterInlineSubscriptionAction($subscriptionFormModel, $transactionData, $subscription)
    {
        $replacer = new MM_WPFS_SubscriptionMacroReplacer($subscriptionFormModel->getForm(), $transactionData, $this->loggerService);

        $params = array(
            'email' => $subscriptionFormModel->getCardHolderEmail(),
            'urlParameters' => $subscriptionFormModel->getFormGetParametersAsArray(),
            'formName' => $subscriptionFormModel->getFormName(),
            'productName' => $subscriptionFormModel->getProductName(),
            'planId' => $subscriptionFormModel->getStripePlanId(),
            'currency' => $transactionData->getPlanCurrency(),
            'amount' => $transactionData->getAmount(),
            'setupFee' => $subscriptionFormModel->getSetupFee(),
            'quantity' => $subscriptionFormModel->getStripePlanQuantity(),
            'stripeClient' => $this->stripe->getStripeClient(),
            'stripeSubscription' => $subscription,
            'rawPlaceholders' => $replacer->getRawKeyValuePairs(),
            'decoratedPlaceholders' => $replacer->getDecoratedKeyValuePairs(),
        );

        do_action(MM_WPFS::ACTION_NAME_AFTER_SUBSCRIPTION_CHARGE, $params);
    }

    /**
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     * @param $invoice \StripeWPFS\Invoice
     */
    private function updateSubscriptionTransactionDataPricing(&$transactionData, $invoice)
    {
        $pricingDetails = MM_WPFS_Pricing::extractSubscriptionPricingFromInvoiceLineItems($invoice->lines->data);

        $setupFeeAmount =
            $setupFeeTaxInclusive =
            $setupFeeTaxExclusive =
            $setupFeeDiscount = 0;
        if ($pricingDetails->setupFee !== null) {
            $setupFeeAmount = $pricingDetails->setupFee->amount;
            $setupFeeTaxExclusive = $pricingDetails->setupFee->taxExclusive;
            $setupFeeTaxInclusive = $pricingDetails->setupFee->taxInclusive;
            $setupFeeDiscount = $pricingDetails->setupFee->discount;
        }

        $transactionData->setPlanQuantity($pricingDetails->product->quantity);

        if ($pricingDetails->product->amount == 0 && $transactionData->getTrialPeriodDays() > 0) {
            // the subscription is in a trial phase so Stripe replied back with a $0 value
            // save the original values so that they can be used in templates
            $transactionData->setPlanFutureNetAmount($transactionData->getPlanNetAmount());
            $transactionData->setPlanFutureTaxAmount($transactionData->getPlanTaxAmount());
            $transactionData->setPlanFutureGrossAmount($transactionData->getPlanNetAmount() + $transactionData->getPlanTaxAmount());
        } else {
            $transactionData->setPlanFutureNetAmount(0);
            $transactionData->setPlanFutureTaxAmount(0);
            $transactionData->setPlanFutureGrossAmount(0);
        }

        $transactionData->setPlanNetAmountTotal($pricingDetails->product->amount - $pricingDetails->product->discount - $pricingDetails->product->taxInclusive);
        $transactionData->setPlanTaxAmountTotal($pricingDetails->product->taxInclusive + $pricingDetails->product->taxExclusive);
        $transactionData->setPlanGrossAmountTotal($transactionData->getPlanNetAmountTotal() + $transactionData->getPlanTaxAmountTotal());
        $transactionData->setPlanNetAmount($transactionData->getPlanNetAmountTotal() / $transactionData->getPlanQuantity());
        $transactionData->setPlanTaxAmount($transactionData->getPlanTaxAmountTotal() / $transactionData->getPlanQuantity());
        $transactionData->setPlanGrossAmount($transactionData->getPlanGrossAmountTotal() / $transactionData->getPlanQuantity());

        $transactionData->setSetupFeeNetAmountTotal($setupFeeAmount - $setupFeeDiscount - $setupFeeTaxInclusive);
        $transactionData->setSetupFeeTaxAmountTotal($setupFeeTaxExclusive + $setupFeeTaxInclusive);
        $transactionData->setSetupFeeGrossAmountTotal($transactionData->getSetupFeeNetAmountTotal() + $transactionData->getSetupFeeTaxAmountTotal());
        $transactionData->setSetupFeeNetAmount($transactionData->getSetupFeeNetAmountTotal() / $transactionData->getPlanQuantity());
        $transactionData->setSetupFeeTaxAmount($transactionData->getSetupFeeTaxAmountTotal() / $transactionData->getPlanQuantity());
        $transactionData->setSetupFeeGrossAmount($transactionData->getSetupFeeGrossAmountTotal() / $transactionData->getPlanQuantity());

        $transactionData->setAmount($transactionData->getPlanGrossAmountTotal() + $transactionData->getSetupFeeGrossAmountTotal());
    }

    private function getInvoiceId($invoiceOrId)
    {
        if (isset($invoiceOrId)) {
            return $invoiceOrId->id;
        }

        return $invoiceOrId;
    }

    private function retrieveInvoiceExpanded($invoice)
    {
        $expandedInvoice = null;

        if (isset($invoice) && isset($invoice->charge)) {
            $expandedInvoice = $invoice;
        } else {
            $expandedInvoice = $this->stripe->retrieveInvoiceWithParams(
                $this->getInvoiceId($invoice),
                array(
                    'expand' => array(
                        'charge'
                    )
                )
            );
        }

        return $expandedInvoice;
    }

    /**
     * @param MM_WPFS_Public_SubscriptionFormModel $subscriptionFormModel
     *
     * @return MM_WPFS_SubscriptionResult
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    private function processSubscription($subscriptionFormModel)
    {
        $subscriptionResult = new MM_WPFS_SubscriptionResult();
        $subscriptionResult->setNonce($subscriptionFormModel->getNonce());

        $createCustomerOptions = new MM_WPFS_CreateCustomerOptions();
        $createCustomerOptions->addMetadata = false;
        $this->createOrRetrieveCustomerByFormModel($subscriptionFormModel, $createCustomerOptions);

        $transactionData = MM_WPFS_TransactionDataService::createSubscriptionDataByModel($subscriptionFormModel);

        $stripeSubscription = null;
        $stripePaymentIntent = null;
        $stripeSetupIntent = null;
        $stripeCustomer = null;
        if (
            empty($subscriptionFormModel->getStripePaymentIntentId())
            && empty($subscriptionFormModel->getStripeSetupIntentId())
        ) {
            $this->logger->debug(__FUNCTION__, "Creating Subscription...");

            $this->fireBeforeInlineSubscriptionAction($subscriptionFormModel, $transactionData);

            $createSubscriptionOptions = new MM_WPFS_CreateSubscriptionOptions();
            $createSubscriptionOptions->taxRateIds = MM_WPFS_Pricing::extractTaxRateIdsStatic($this->getApplicableTaxRates($subscriptionFormModel));
            $stripeSubscription = $this->createSubscription($subscriptionFormModel, $transactionData, $createSubscriptionOptions);

            $stripeCustomer = $this->stripe->retrieveCustomer($stripeSubscription->customer);
            $subscriptionFormModel->setStripeCustomer($stripeCustomer);
            $subscriptionFormModel->setStripeSubscription($stripeSubscription);
            $subscriptionFormModel->setTransactionId($stripeSubscription->id);
            $transactionData->setTransactionId($stripeSubscription->id);

            if (isset($stripeSubscription)) {
                if (isset($stripeSubscription->latest_invoice) && isset($stripeSubscription->latest_invoice->id)) {
                    $stripePaymentIntent = $stripeSubscription->latest_invoice->payment_intent;

                    // Update payment intent for metadata
                    $this->updatePaymentIntentWithMetadataAndWebhookUrl($stripePaymentIntent, $subscriptionFormModel);

                    $subscriptionFormModel->setStripePaymentIntent($stripePaymentIntent);
                    $subscriptionFormModel->setTransactionId($stripeSubscription->id);
                    $transactionData->setTransactionId($subscriptionFormModel->getTransactionId());
                }
                if (isset($stripeSubscription->pending_setup_intent)) {
                    $stripeSetupIntent = $stripeSubscription->pending_setup_intent;
                    $subscriptionFormModel->setStripeSetupIntent($stripeSetupIntent);
                    $subscriptionFormModel->setTransactionId($stripeSubscription->id);
                    $transactionData->setTransactionId($subscriptionFormModel->getTransactionId());
                }
            }

            // tnagy insert subscriber
            $this->db->insertSubscriber($subscriptionFormModel, $transactionData);

        } else {
            $this->logger->debug(__FUNCTION__, "Retrieving Subscription...");

            if (!empty($subscriptionFormModel->getStripePaymentIntentId())) {
                $stripePaymentIntent = $this->stripe->retrievePaymentIntent($subscriptionFormModel->getStripePaymentIntentId());
                if (isset($stripePaymentIntent)) {
                    // Update payment intent for metadata
                    $this->updatePaymentIntentWithMetadataAndWebhookUrl($stripePaymentIntent, $subscriptionFormModel);

                    $stripeCustomer = $this->stripe->retrieveCustomer($stripePaymentIntent->customer);
                    $subscriptionFormModel->setStripeCustomer($stripeCustomer);
                    // tnagy update transaction id
                    $wpfsSubscriber = $this->db->findSubscriberByPaymentIntentId($stripePaymentIntent->id);
                    if (isset($wpfsSubscriber) && isset($wpfsSubscriber->stripeSubscriptionID)) {
                        $subscriptionFormModel->setTransactionId($wpfsSubscriber->stripeSubscriptionID);
                        $transactionData->setTransactionId($subscriptionFormModel->getTransactionId());
                        $stripeSubscription = $this->stripe->retrieveSubscription($wpfsSubscriber->stripeSubscriptionID);
                    }
                }
            }
            if (!empty($subscriptionFormModel->getStripeSetupIntentId())) {
                $stripeSetupIntent = $this->stripe->retrieveSetupIntent($subscriptionFormModel->getStripeSetupIntentId());
                if (isset($stripeSetupIntent)) {
                    // Update payment intent for metadata
                    $this->updatePaymentIntentWithMetadataAndWebhookUrl($stripeSetupIntent, $subscriptionFormModel);

                    $stripeCustomer = $this->stripe->retrieveCustomer($stripeSetupIntent->customer);
                    $subscriptionFormModel->setStripeCustomer($stripeCustomer);
                    // tnagy update transaction id
                    $wpfsSubscriber = $this->db->findSubscriberBySetupIntentId($stripeSetupIntent->id);
                    if (isset($wpfsSubscriber) && isset($wpfsSubscriber->stripeSubscriptionID)) {
                        $subscriptionFormModel->setTransactionId($wpfsSubscriber->stripeSubscriptionID);
                        $transactionData->setTransactionId($subscriptionFormModel->getTransactionId());
                        $stripeSubscription = $this->stripe->retrieveSubscription($wpfsSubscriber->stripeSubscriptionID);
                    }
                }
            }
        }

        if (isset($stripeSubscription->latest_invoice)) {
            $latestInvoice = $this->retrieveInvoiceExpanded($stripeSubscription->latest_invoice);

            $transactionData->setInvoiceUrl($latestInvoice->invoice_pdf);
            $transactionData->setInvoiceNumber($latestInvoice->number);
            $transactionData->setReceiptUrl(isset($latestInvoice->charge) ? $latestInvoice->charge->receipt_url : null);
            $this->updateSubscriptionTransactionDataPricing($transactionData, $latestInvoice);
        }
        $transactionData->setStripeCustomerId($stripeCustomer->id);

        // log the transaction data since something is wrong here
        // MM_WPFS_Utils::log("handle(): transaction data {$transactionData->getJSONString()}");
        // $this->logger->debug(__FUNCTION__, "transaction data {$transactionData->getJSONString()}");

        $this->handleIntent($subscriptionResult, $stripeSubscription, $stripePaymentIntent, $stripeSetupIntent);
        $this->handleRedirect($subscriptionFormModel, $transactionData, $subscriptionResult);
        if ($subscriptionResult->isSuccess()) {
            $this->fireAfterInlineSubscriptionAction($subscriptionFormModel, $transactionData, $stripeSubscription);

            if (MM_WPFS_Mailer::canSendSubscriptionPluginReceipt($subscriptionFormModel->getForm())) {
                $this->mailer->sendSubscriptionStartedEmailReceipt($subscriptionFormModel->getForm(), $transactionData);
            }
        }

        return $subscriptionResult;
    }

    /**
     * Updates the given result by the given PaymentIntent or SetupIntent. When no PaymentIntent nor
     * SetupIntent are given, we consider the subscription as successful.
     *
     * @param MM_WPFS_SubscriptionResult $subscriptionResult
     * @param \StripeWPFS\Subscription $subscription
     * @param \StripeWPFS\PaymentIntent $paymentIntent
     * @param \StripeWPFS\SetupIntent $setupIntent
     */
    private function handleIntent($subscriptionResult, $subscription, $paymentIntent, $setupIntent)
    {
        if (isset($paymentIntent)) {
            if (
                \StripeWPFS\PaymentIntent::STATUS_REQUIRES_ACTION === $paymentIntent->status
                && 'use_stripe_sdk' === $paymentIntent->next_action->type
            ) {
                $this->logger->debug(__FUNCTION__, "PaymentIntent requires action...");

                $subscriptionResult->setSuccess(false);
                $subscriptionResult->setRequiresAction(true);
                $subscriptionResult->setPaymentIntentClientSecret($paymentIntent->client_secret);
                $subscriptionResult->setMessageTitle(
                    /* translators: Banner title of pending transaction requiring a second factor authentication (SCA/PSD2) */
                    __('Action required', 'wp-full-stripe')
                );
                $subscriptionResult->setMessage(
                    /* translators: Banner message of a one-time payment requiring a second factor authentication (SCA/PSD2) */
                    __('The payment needs additional action before completion!', 'wp-full-stripe')
                );
            } elseif (
                \StripeWPFS\PaymentIntent::STATUS_SUCCEEDED === $paymentIntent->status
                || \StripeWPFS\PaymentIntent::STATUS_REQUIRES_CAPTURE === $paymentIntent->status
                || \StripeWPFS\PaymentIntent::STATUS_PROCESSING === $paymentIntent->status
            ) {
                $this->logger->debug(__FUNCTION__, "PaymentIntent succeeded.");

                $this->db->updateSubscriptionByPaymentIntentToRunning($paymentIntent->id);
                $subscriptionResult->setRequiresAction(false);
                $subscriptionResult->setSuccess(true);
                $subscriptionResult->setMessageTitle(
                    /* translators: Banner title of successful transaction */
                    __('Success', 'wp-full-stripe')
                );
                $subscriptionResult->setMessage(
                    /* translators: Banner message of successful payment */
                    __('Payment Successful!', 'wp-full-stripe')
                );
            } else {
                $subscriptionResult->setSuccess(false);
                $subscriptionResult->setMessageTitle(
                    /* translators: Banner title of failed transaction */
                    __('Failed', 'wp-full-stripe')
                );
                $subscriptionResult->setMessage(
                    // This is an internal error, no need to localize it
                    sprintf("Invalid PaymentIntent status '%s'.", $paymentIntent->status)
                );
            }
        } elseif (isset($setupIntent)) {
            if (
                \StripeWPFS\SetupIntent::STATUS_REQUIRES_ACTION === $setupIntent->status
                && 'use_stripe_sdk' === $setupIntent->next_action->type
            ) {
                $this->logger->debug(__FUNCTION__, "SetupIntent requires action...");

                $subscriptionResult->setSuccess(false);
                $subscriptionResult->setRequiresAction(true);
                $subscriptionResult->setSetupIntentClientSecret($setupIntent->client_secret);
                $subscriptionResult->setMessageTitle(
                    /* translators: Banner title of pending transaction requiring a second factor authentication (SCA/PSD2) */
                    __('Action required', 'wp-full-stripe')
                );
                $subscriptionResult->setMessage(
                    /* translators: Banner message of a one-time payment requiring a second factor authentication (SCA/PSD2) */
                    __('The payment needs additional action before completion!', 'wp-full-stripe')
                );
            } elseif (
                \StripeWPFS\SetupIntent::STATUS_SUCCEEDED === $setupIntent->status
            ) {
                $this->logger->debug(__FUNCTION__, "SetupIntent succeeded.");

                $this->db->updateSubscriptionBySetupIntentToRunning($setupIntent->id);
                $subscriptionResult->setRequiresAction(false);
                $subscriptionResult->setSuccess(true);
                $subscriptionResult->setMessageTitle(
                    /* translators: Banner title of successful transaction */
                    __('Success', 'wp-full-stripe')
                );
                $subscriptionResult->setMessage(
                    /* translators: Banner message of successful payment */
                    __('Payment Successful!', 'wp-full-stripe')
                );
            } else {
                $subscriptionResult->setSuccess(false);
                $subscriptionResult->setMessageTitle(
                    /* translators: Banner title of failed transaction */
                    __('Failed', 'wp-full-stripe')
                );
                $subscriptionResult->setMessage(
                    // This is an internal error, no need to localize it
                    sprintf("Invalid PaymentIntent status '%s'.", $setupIntent->status)
                );
            }
        } else {
            /*
             * WPFS-1012: When a Subscription has a trial period without a setup fee then the Invoice has no
             * PaymentIntent. When SCA is not triggered then the pending SetupIntent is also missing.
             * In these cases the PaymentIntent and SetupIntent are both null.
             * We consider these subscriptions as successful.
             */
            $this->db->updateSubscriptionToRunning($subscription->id);
            $subscriptionResult->setRequiresAction(false);
            $subscriptionResult->setSuccess(true);
            $subscriptionResult->setMessageTitle(
                /* translators: Banner title of successful transaction */
                __('Success', 'wp-full-stripe')
            );
            $subscriptionResult->setMessage(
                /* translators: Banner message of successful payment */
                __('Payment Successful!', 'wp-full-stripe')
            );
        }
    }

    /**
     * @param $stripePaymentIntent
     * @param $subscriptionFormModel
     * @return void
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function updatePaymentIntentWithMetadataAndWebhookUrl($stripePaymentIntent, $subscriptionFormModel): void
    {
        // Update payment intent for metadata
        $metadata = $subscriptionFormModel->getMetadata();
        $metadata['webhookUrl'] = esc_attr(MM_WPFS_EventHandler::getWebhookEndpointURL($this->staticContext));
        $stripePaymentIntent->metadata = $metadata;
        $this->stripe->updatePaymentIntent($stripePaymentIntent);
    }

    /**
     * @param $saveCardFormModel MM_WPFS_Public_CheckoutPaymentFormModel
     */
    protected function fireBeforeCheckoutSaveCardAction($saveCardFormModel)
    {
        $params = array(
            'urlParameters' => $saveCardFormModel->getFormGetParametersAsArray(),
            'formName' => $saveCardFormModel->getFormName(),
            'stripeClient' => $this->stripe->getStripeClient(),
        );

        do_action(MM_WPFS::ACTION_NAME_BEFORE_CHECKOUT_SAVE_CARD, $params);
    }

    /**
     * @param $paymentFormModel MM_WPFS_Public_CheckoutPaymentFormModel
     * @param $transactionData MM_WPFS_PaymentTransactionData
     */
    private function fireBeforeCheckoutPaymentAction($paymentFormModel, $transactionData)
    {
        $params = array(
            'urlParameters' => $paymentFormModel->getFormGetParametersAsArray(),
            'formName' => $paymentFormModel->getFormName(),
            'priceId' => $paymentFormModel->getPriceId(),
            'productName' => $paymentFormModel->getProductName(),
            'currency' => $transactionData->getCurrency(),
            'amount' => $paymentFormModel->getAmount(),
            'stripeClient' => $this->stripe->getStripeClient(),
        );

        do_action(MM_WPFS::ACTION_NAME_BEFORE_CHECKOUT_PAYMENT_CHARGE, $params);
    }

    function fullstripe_checkout_payment_charge()
    {
        try {
            $paymentFormModel = new MM_WPFS_Public_CheckoutPaymentFormModel($this->loggerService);
            $bindingResult = $paymentFormModel->bind();

            if ($bindingResult->hasErrors()) {
                $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
            } else {
                if (MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $paymentFormModel->getForm()->customAmount) {
                    $this->fireBeforeCheckoutSaveCardAction($paymentFormModel);
                } else {
                    $transactionData = MM_WPFS_TransactionDataService::createOneTimePaymentDataByModel($paymentFormModel);
                    $this->fireBeforeCheckoutPaymentAction($paymentFormModel, $transactionData);
                }

                $checkoutSession = $this->checkoutSubmissionService->createCheckoutSession($paymentFormModel);
                $return = $this->generateReturnValueFromCheckoutSession($checkoutSession);
            }
        } catch (WPFS_UserFriendlyException $ex) {
            $this->logger->error(__FUNCTION__, "User-friendly exception while submitting checkout payment charge.", $ex);

            $messageTitle = is_null($ex->getTitle()) ?
                /* translators: Banner title of an error returned from an extension point by a developer */
                __('Internal Error', 'wp-full-stripe') :
                $ex->getTitle();
            $message = $ex->getMessage();
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (\StripeWPFS\Exception\CardException $ex) {
            $this->logger->error(__FUNCTION__, "Card exception while submitting checkout payment charge.", $ex);

            $messageTitle =
                /* translators: Banner title of error returned by Stripe */
                __('Stripe Error', 'wp-full-stripe');
            $message = $this->stripe->resolveErrorMessageByCode($ex->getCode());
            if (is_null($message)) {
                $message = MM_WPFS_Localization::translateLabel($ex->getMessage());
            }
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, "Generic exception while submitting checkout payment charge.", $ex);

            $return = array(
                'success' => false,
                'messageTitle' =>
                    /* translators: Banner title of internal error */
                    __('Internal Error', 'wp-full-stripe'),
                'message' => MM_WPFS_Localization::translateLabel($ex->getMessage()),
                'exceptionMessage' => $ex->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode(apply_filters('fullstripe_checkout_payment_charge_return_message', $return));
        exit;

    }

    /**
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     * @param $transactionData MM_WPFS_DonationTransactionData
     */
    protected function fireBeforeCheckoutDonationAction($donationFormModel, $transactionData)
    {
        $params = array(
            'urlParameters' => $donationFormModel->getFormGetParametersAsArray(),
            'formName' => $donationFormModel->getFormName(),
            'currency' => $transactionData->getCurrency(),
            'frequency' => $donationFormModel->getDonationFrequency(),
            'amount' => $donationFormModel->getAmount(),
            'stripeClient' => $this->stripe->getStripeClient()
        );

        do_action(MM_WPFS::ACTION_NAME_BEFORE_CHECKOUT_DONATION_CHARGE, $params);
    }

    function fullstripe_checkout_donation_charge()
    {
        try {

            $donationFormModel = new MM_WPFS_Public_CheckoutDonationFormModel($this->loggerService);
            $bindingResult = $donationFormModel->bind();
            if ($bindingResult->hasErrors()) {
                $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
            } else {
                $transactionData = MM_WPFS_TransactionDataService::createDonationDataByFormModel($donationFormModel);
                $this->fireBeforeCheckoutDonationAction($donationFormModel, $transactionData);

                $checkoutSession = $this->checkoutSubmissionService->createCheckoutSession($donationFormModel);
                $return = $this->generateReturnValueFromCheckoutSession($checkoutSession);
            }
        } catch (WPFS_UserFriendlyException $ex) {
            $this->logger->error(__FUNCTION__, "User-friendly exception while submitting checkout donation charge.", $ex);

            $messageTitle = is_null($ex->getTitle()) ?
                /* translators: Banner title of an error returned from an extension point by a developer */
                __('Internal Error', 'wp-full-stripe') :
                $ex->getTitle();
            $message = $ex->getMessage();
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (\StripeWPFS\Exception\CardException $ex) {
            $this->logger->error(__FUNCTION__, "Card exception while submitting checkout donation charge.", $ex);

            $messageTitle =
                /* translators: Banner title of error returned by Stripe */
                __('Stripe Error', 'wp-full-stripe');
            $message = $this->stripe->resolveErrorMessageByCode($ex->getCode());
            if (is_null($message)) {
                $message = MM_WPFS_Localization::translateLabel($ex->getMessage());
            }
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, "Generic exception while submitting checkout donation charge.", $ex);

            $return = array(
                'success' => false,
                'messageTitle' =>
                    /* translators: Banner title of internal error */
                    __('Internal Error', 'wp-full-stripe'),
                'message' => MM_WPFS_Localization::translateLabel($ex->getMessage()),
                'exceptionMessage' => $ex->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode(apply_filters('fullstripe_checkout_donation_charge_return_message', $return));
        exit;

    }

    /**
     * @param \StripeWPFS\Checkout\Session $checkoutSession
     *
     * @return array
     */
    private function generateReturnValueFromCheckoutSession($checkoutSession)
    {
        return array(
            'success' => true,
            'checkoutSessionId' => $checkoutSession->id,
            'redirectUrl' => $checkoutSession->url
        );
    }

    /**
     * @param $subscriptionFormModel MM_WPFS_Public_SubscriptionFormModel
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     */
    protected function fireBeforeCheckoutSubscriptionAction($subscriptionFormModel, $transactionData)
    {
        $params = array(
            'urlParameters' => $subscriptionFormModel->getFormGetParametersAsArray(),
            'formName' => $subscriptionFormModel->getFormName(),
            'productName' => $subscriptionFormModel->getProductName(),
            'planId' => $subscriptionFormModel->getStripePlanId(),
            'currency' => $transactionData->getPlanCurrency(),
            'amount' => $subscriptionFormModel->getPlanAmount(),
            'setupFee' => $subscriptionFormModel->getSetupFee(),
            'quantity' => $subscriptionFormModel->getStripePlanQuantity(),
            'stripeClient' => $this->stripe->getStripeClient(),
        );

        do_action(MM_WPFS::ACTION_NAME_BEFORE_CHECKOUT_SUBSCRIPTION_CHARGE, $params);
    }

    function fullstripe_checkout_subscription_charge()
    {
        try {

            $subscriptionFormModel = new MM_WPFS_Public_CheckoutSubscriptionFormModel($this->loggerService);
            $bindingResult = $subscriptionFormModel->bind();

            if ($bindingResult->hasErrors()) {
                $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
            } else {
                $this->fireBeforeCheckoutSubscriptionAction(
                    $subscriptionFormModel,
                    MM_WPFS_TransactionDataService::createSubscriptionDataByModel($subscriptionFormModel)
                );

                $checkoutSession = $this->checkoutSubmissionService->createCheckoutSession($subscriptionFormModel);
                $return = $this->generateReturnValueFromCheckoutSession($checkoutSession);
            }
        } catch (WPFS_UserFriendlyException $ex) {
            $this->logger->error(__FUNCTION__, "User-friendly exception while submitting checkout subscription charge.", $ex);

            $messageTitle = is_null($ex->getTitle()) ?
                /* translators: Banner title of an error returned from an extension point by a developer */
                __('Internal Error', 'wp-full-stripe') :
                $ex->getTitle();
            $message = $ex->getMessage();
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (\StripeWPFS\Exception\CardException $ex) {
            $this->logger->error(__FUNCTION__, "Card exception while submitting checkout subscription charge.", $ex);

            $messageTitle =
                /* translators: Banner title of error returned by Stripe */
                __('Stripe Error', 'wp-full-stripe');
            $message = $this->stripe->resolveErrorMessageByCode($ex->getCode());
            if (is_null($message)) {
                $message = MM_WPFS_Localization::translateLabel($ex->getMessage());
            }
            $return = array(
                'success' => false,
                'messageTitle' => $messageTitle,
                'message' => $message,
                'exceptionMessage' => $ex->getMessage()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, "Generic exception while submitting checkout subscription charge.", $ex);

            $return = array(
                'success' => false,
                'messageTitle' =>
                    /* translators: Banner title of internal error */
                    __('Internal Error', 'wp-full-stripe'),
                'message' => MM_WPFS_Localization::translateLabel($ex->getMessage()),
                'exceptionMessage' => $ex->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode(apply_filters('fullstripe_checkout_subscription_charge_return_message', $return));
        exit;

    }

    /**
     * @throws Exception
     */
    function fullstripe_check_coupon()
    {
        $return = [];
        $couponCode = $_POST['code'];

        $formType = $_POST['taxData']['formType'];
        $formId = $_POST['taxData']['formId'];
        $form = MM_WPFS::getInstance()->getFormByTypeAndName($formType, $formId);
        $formHash = MM_WPFS_Utils::generateFormHash($formType, MM_WPFS_Utils::getFormId($form), $form->name);
        $bindingResult = new MM_WPFS_BindingResult($formHash);

        $fieldName = MM_WPFS_FormView::FIELD_COUPON;
        $fieldId = MM_WPFS_Utils::generateFormElementId($fieldName, $formHash);

        if (empty($couponCode)) {
            $bindingResult->addFieldError(
                $fieldName,
                $fieldId,
                /* translators: Banner message of expired coupon */
                __('Please enter a coupon code', 'wp-full-stripe')
            );

            $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
        } else {
            $coupon = $this->stripe->retrieveCouponByPromotionalCodeOrCouponCode($couponCode);

            if (is_null($coupon) || false == $coupon->valid) {
                $bindingResult->addFieldError(
                    $fieldName,
                    $fieldId,
                    /* translators: Banner message of expired coupon */
                    __('This coupon has expired', 'wp-full-stripe')
                );

                $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
            } else {
                $result = MM_WPFS::getInstance()->isCouponApplicableToForm(
                    $coupon,
                    $formType,
                    $formId,
                    $_POST['taxData']['currentPriceId']
                );

                if (!$result->applicableToForm) {
                    $bindingResult->addFieldError(
                        $fieldName,
                        $fieldId,
                        /* translators: Banner message of a coupon that cannot be applied to the products of the form */
                        __('This coupon cannot be applied to these products', 'wp-full-stripe')
                    );

                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else if (!$result->applicableToProduct) {
                    $bindingResult->addFieldError(
                        $fieldName,
                        $fieldId,
                        /* translators: Banner message of a coupon that cannot be applied to the products of the form */
                        __('This coupon cannot be applied to the selected product', 'wp-full-stripe')
                    );

                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $pricingData = new \StdClass;
                    $pricingData->formType = $formType;
                    $pricingData->formId = $formId;
                    $pricingData->country = $_POST['taxData']['country'];
                    $pricingData->state = $_POST['taxData']['state'];
                    $pricingData->zip = $_POST['taxData']['zip'];
                    $pricingData->taxIdType = $_POST['taxData']['taxIdType'];
                    $pricingData->taxId = $_POST['taxData']['taxId'];
                    $pricingData->couponCode = $coupon->id;
                    $pricingData->couponPercentOff = !($coupon->amount_off > 0);
                    $pricingData->customAmount = isset($_POST['taxData']['customAmount']) && $_POST['taxData']['customAmount'] !== '' ? $_POST['taxData']['customAmount'] : null;
                    $pricingData->quantity = $_POST['taxData']['quantity'];
                    $pricingData->stripeTax = ($formType === MM_WPFS::FORM_TYPE_INLINE_PAYMENT || $formType == MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION) &&
                        $form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX;

                    try {
                        $productPricing = MM_WPFS_Pricing::createFormPriceCalculator($pricingData, $this->loggerService)->getProductPrices();
                        $discountedPriceIds = MM_WPFS::getInstance()->getDiscountedPriceIdsByCouponAndForm($coupon, $_POST['taxData']['formType'], $_POST['taxData']['formId']);
                    } catch (WPFS_InvalidTaxIdException $tax) {
                        $fieldName = MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_ID;
                        $fieldId = MM_WPFS_Utils::generateFormElementId($fieldName, $formHash);
                        $error =
                            __('Invalid tax id', 'wp-full-stripe');

                        $bindingResult->addFieldError($fieldName, $fieldId, $error);
                    } catch (Exception $ex) {
                        $this->logger->error(__FUNCTION__, "Cannot apply coupon", $ex);
                        $bindingResult->addGlobalError($ex->getMessage());
                    }

                    if (!empty($productPricing) && !$bindingResult->hasErrors()) {
                        $return = array(
                            'msg_title' =>
                                /* translators: Banner title for messages related to applying a coupon */
                                __('Coupon redemption', 'wp-full-stripe'),
                            'msg' =>
                                /* translators: Banner message of successfully applying a coupon */
                                __('The coupon has been applied successfully', 'wp-full-stripe'),
                            'coupon' => array(
                                'id' => $coupon->id,
                                'name' => $couponCode,
                                'currency' => $coupon->currency,
                                'percent_off' => $coupon->percent_off,
                                'amount_off' => $coupon->amount_off,
                                'discounted_price_ids' => $discountedPriceIds
                            ),
                            'success' => true,
                            'productPricing' => $productPricing
                        );
                    } else {
                        $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                    }
                }
            }
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    public function calculatePricing()
    {
        try {
            $couponId = null;
            $coupon = null;

            if (!empty($_POST['coupon'])) {
                $coupon = $this->stripe->retrieveCouponByPromotionalCodeOrCouponCode($_POST['coupon']);
                $couponId = !is_null($coupon) ? $coupon->id : null;
            }

            $formType = $_POST['formType'];
            $formId = $_POST['formId'];

            $form = MM_WPFS::getInstance()->getFormByTypeAndName($formType, $formId);

            $pricingData = new \StdClass;
            $pricingData->formType = $formType;
            $pricingData->formId = $formId;
            $pricingData->country = $_POST['country'];
            $pricingData->state = $_POST['state'];
            $pricingData->zip = $_POST['zip'];
            $pricingData->taxIdType = $_POST['taxIdType'];
            $pricingData->taxId = $_POST['taxId'];
            $pricingData->couponCode = $couponId;
            $pricingData->customAmount = !empty($_POST['customAmount']) ? $_POST['customAmount'] : null;
            $pricingData->quantity = $_POST['quantity'];
            $pricingData->stripeTax = ($formType === MM_WPFS::FORM_TYPE_INLINE_PAYMENT || $formType == MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION) &&
                $form->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX;

            if (!empty($pricingData->couponCode)) {
                $pricingData->couponPercentOff = !($coupon->amount_off > 0);
            } else {
                $pricingData->couponPercentOff = true;
            }

            $formHash = MM_WPFS_Utils::generateFormHash($formType, MM_WPFS_Utils::getFormId($form), $form->name);
            $bindingResult = new MM_WPFS_BindingResult($formHash);
            try {
                $pricing = MM_WPFS_Pricing::createFormPriceCalculator($pricingData, $this->loggerService)->getProductPrices();
            } catch (WPFS_InvalidTaxIdException $tax) {
                $fieldName = MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_ID;
                $fieldId = MM_WPFS_Utils::generateFormElementId($fieldName, $formHash);
                $error =
                    __('Invalid tax id', 'wp-full-stripe');

                $bindingResult->addFieldError($fieldName, $fieldId, $error);
            } catch (Exception $ex) {
                $bindingResult->addGlobalError($ex->getMessage());
            }

            if (!empty($pricing) && !$bindingResult->hasErrors()) {
                $return = array(
                    'success' => true,
                    'productPricing' => $pricing
                );
            } else {
                $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
            }

        } catch (Exception $e) {
            $return = array(
                'success' => false,
                'msg' => __('There was an error calculating product pricing: ', 'wp-full-stripe') . $e->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

}

class MM_WPFS_TransactionResult
{

    /**
     * @var boolean
     */
    protected $success = false;
    /**
     * @var string
     */
    protected $messageTitle;
    /**
     * @var string
     */
    protected $message;
    /**
     * @var boolean
     */
    protected $redirect = false;
    /**
     * @var string
     */
    protected $redirectURL;
    /**
     * @var boolean
     */
    protected $requiresAction = false;
    /**
     * @var string
     */
    protected $paymentIntentClientSecret;
    /**
     * @var string
     */
    protected $setupIntentClientSecret;
    /**
     * @var string
     */
    protected $formType;
    /**
     * @var string
     */
    protected $nonce;

    /**
     * @var boolean
     */
    protected $isManualConfirmation = false;

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getMessageTitle()
    {
        return $this->messageTitle;
    }

    /**
     * @param string $messageTitle
     */
    public function setMessageTitle($messageTitle)
    {
        $this->messageTitle = $messageTitle;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return boolean
     */
    public function isRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param boolean $redirect
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @return string
     */
    public function getRedirectURL()
    {
        return $this->redirectURL;
    }

    /**
     * @param string $redirectURL
     */
    public function setRedirectURL($redirectURL)
    {
        $this->redirectURL = $redirectURL;
    }

    /**
     * @return boolean
     */
    public function isRequiresAction()
    {
        return $this->requiresAction;
    }

    /**
     * @param boolean $requiresAction
     */
    public function setRequiresAction($requiresAction)
    {
        $this->requiresAction = $requiresAction;
    }

    /**
     * @return mixed
     */
    public function getPaymentIntentClientSecret()
    {
        return $this->paymentIntentClientSecret;
    }

    /**
     * @param mixed $paymentIntentClientSecret
     */
    public function setPaymentIntentClientSecret($paymentIntentClientSecret)
    {
        $this->paymentIntentClientSecret = $paymentIntentClientSecret;
    }

    /**
     * @return string
     */
    public function getSetupIntentClientSecret()
    {
        return $this->setupIntentClientSecret;
    }

    /**
     * @param string $setupIntentClientSecret
     */
    public function setSetupIntentClientSecret($setupIntentClientSecret)
    {
        $this->setupIntentClientSecret = $setupIntentClientSecret;
    }

    /**
     * @return string
     */
    public function getFormType()
    {
        return $this->formType;
    }

    /**
     * @param string $formType
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;
    }

    /**
     * @return string
     */
    public function getNonce()
    {
        return $this->nonce;
    }

    /**
     * @param string $nonce
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
    }

    /**
     * @return bool
     */
    public function isManualConfirmation(): bool
    {
        return $this->isManualConfirmation;
    }

    /**
     * @param bool $isManualConfirmation
     */
    public function setIsManualConfirmation(bool $isManualConfirmation)
    {
        $this->isManualConfirmation = $isManualConfirmation;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }
}

class MM_WPFS_PaymentIntentResult extends MM_WPFS_ChargeResult
{

    /**
     * MM_WPFS_PaymentIntentResult constructor.
     */
    public function __construct()
    {
        $this->formType = MM_WPFS::FORM_TYPE_INLINE_PAYMENT;
    }

    public function getAsArray()
    {
        return array(
            'success' => $this->success,
            'messageTitle' => $this->messageTitle,
            'message' => $this->message,
            'redirect' => $this->redirect,
            'redirectURL' => $this->redirectURL,
            'requiresAction' => $this->requiresAction,
            'paymentIntentClientSecret' => $this->paymentIntentClientSecret,
            'setupIntentClientSecret' => $this->setupIntentClientSecret,
            'formType' => $this->formType,
            'nonce' => $this->nonce,
            'isManualConfirmation' => $this->isManualConfirmation,
        );
    }
}

class MM_WPFS_DonationPaymentIntentResult extends MM_WPFS_ChargeResult
{

    /**
     * MM_WPFS_DonationPaymentIntentResult constructor.
     */
    public function __construct()
    {
        $this->formType = MM_WPFS::FORM_TYPE_INLINE_DONATION;
    }

    public function getAsArray()
    {
        return array(
            'success' => $this->success,
            'messageTitle' => $this->messageTitle,
            'message' => $this->message,
            'redirect' => $this->redirect,
            'redirectURL' => $this->redirectURL,
            'requiresAction' => $this->requiresAction,
            'paymentIntentClientSecret' => $this->paymentIntentClientSecret,
            'setupIntentClientSecret' => $this->setupIntentClientSecret,
            'formType' => $this->formType,
            'nonce' => $this->nonce,
            'isManualConfirmation' => $this->isManualConfirmation
        );
    }
}

class MM_WPFS_DonationCheckoutResult extends MM_WPFS_ChargeResult
{

    /**
     * MM_WPFS_DonationCheckoutResult constructor.
     */
    public function __construct()
    {
        $this->formType = MM_WPFS::FORM_TYPE_CHECKOUT_DONATION;
    }
}

class MM_WPFS_SetupIntentResult extends MM_WPFS_ChargeResult
{

    /**
     * MM_WPFS_PaymentIntentResult constructor.
     */
    public function __construct()
    {
        $this->formType = MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD;
    }

    public function getAsArray()
    {
        return array(
            'success' => $this->success,
            'messageTitle' => $this->messageTitle,
            'message' => $this->message,
            'redirect' => $this->redirect,
            'redirectURL' => $this->redirectURL,
            'requiresAction' => $this->requiresAction,
            'paymentIntentClientSecret' => $this->paymentIntentClientSecret,
            'setupIntentClientSecret' => $this->setupIntentClientSecret,
            'formType' => $this->formType,
            'nonce' => $this->nonce,
        );
    }
}


class MM_WPFS_ChargeResult extends MM_WPFS_TransactionResult
{

    /**
     * @var string
     */
    protected $paymentType;

    /**
     * @var boolean
     */
    protected $isManualConfirmation;


    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @return bool
     */
    public function isManualConfirmation(): bool
    {
        return $this->isManualConfirmation;
    }

    /**
     * @param bool $isManualConfirmation
     */
    public function setIsManualConfirmation(bool $isManualConfirmation)
    {
        $this->isManualConfirmation = $isManualConfirmation;
    }
}

class MM_WPFS_SubscriptionResult extends MM_WPFS_TransactionResult
{

    /**
     * MM_WPFS_SubscriptionResult constructor.
     */
    public function __construct()
    {
        $this->formType = MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION;
    }
}

class MM_WPFS_CreateOrRetrieveCustomerResult
{

    /**
     * @var \StripeWPFS\Customer
     */
    private $customer;
    /**
     * @var \StripeWPFS\PaymentMethod
     */
    private $paymentMethod;

    /**
     * @return \StripeWPFS\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param \StripeWPFS\Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return \StripeWPFS\PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param \StripeWPFS\PaymentMethod $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

}
