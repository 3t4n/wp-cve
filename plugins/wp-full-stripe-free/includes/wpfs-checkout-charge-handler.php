<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2019.08.16.
 * Time: 14:50
 */

trait MM_WPFS_CheckoutTaxTools
{
    /**
     * @param $stripeCustomer \StripeWPFS\Customer
     */
    protected function getTaxIdFromCustomer($stripeCustomer)
    {
        $customerTaxId = null;
        if (isset($stripeCustomer) && isset($stripeCustomer->tax_ids) && isset($stripeCustomer->tax_ids->data)) {
            $taxIds = $stripeCustomer->tax_ids->data;   
            if (count($taxIds) > 0) {
                $customerTaxId = $taxIds[0]->value;
            }
        }
        return $customerTaxId;
    }
}

trait MM_WPFS_CheckoutInvoiceTools
{
    /**
     * @param $transactionData MM_WPFS_PaymentTransactionData|MM_WPFS_DonationTransactionData|MM_WPFS_SubscriptionTransactionData
     * @param $stripeInvoice \StripeWPFS\Invoice
     */
    protected function setTransactionDataFromInvoice(&$transactionData, $invoice)
    {
        $transactionData->setStripeInvoiceId($invoice->id);
        $transactionData->setInvoiceUrl($invoice->invoice_pdf);
        $transactionData->setInvoiceNumber($invoice->number);
    }
}

abstract class MM_WPFS_CheckoutChargeHandler
{
    use MM_WPFS_ThankYou_AddOn;
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    /**
     * @var MM_WPFS_Database
     */
    protected $db;
    /**
     * @var MM_WPFS_Stripe
     */
    protected $stripe;
    /**
     * @var MM_WPFS_CheckoutSubmissionService
     */
    protected $checkoutSubmissionService;
    /**
     * @var MM_WPFS_TransactionDataService
     */
    protected $transactionDataService;
    /**
     * @var MM_WPFS_Mailer
     */
    protected $mailer;
    /**
     * @var MM_WPFS_EventHandler
     */
    protected $eventHandler;
    /**
     * @var MM_WPFS_Options
     */
    protected $options;

    /**
     * MM_WPFS_CheckoutChargeHandler constructor.
     */
    public function __construct($loggerService)
    {
        $this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_CHECKOUT_SUBMISSION);
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        $this->db = new MM_WPFS_Database();
        $this->stripe = new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService);
        $this->checkoutSubmissionService = new MM_WPFS_CheckoutSubmissionService($this->loggerService);
        $this->transactionDataService = new MM_WPFS_TransactionDataService();
        $this->mailer = new MM_WPFS_Mailer($this->loggerService);
        $this->eventHandler = new MM_WPFS_EventHandler(
            $this->db,
            $this->mailer,
            $this->loggerService
        );
    }

    /**
     * @param MM_WPFS_Public_CheckoutPaymentFormModel|MM_WPFS_Public_CheckoutSubscriptionFormModel $formModel
     * @param \StripeWPFS\Checkout\Session $checkoutSession
     *
     * @return MM_WPFS_ChargeResult
     */
    public abstract function handle($formModel, $checkoutSession);

    /**
     * @param $stripeCustomer \StripeWPFS\Customer
     * @param $paymentMethod \StripeWPFS\PaymentMethod
     */
    protected function setBillingAddress(&$stripeCustomer, &$paymentMethod)
    {
        if(isset($stripeCustomer) && isset($paymentMethod)) {
            $stripeCustomer->name = $paymentMethod->billing_details->name;

            if (isset($paymentMethod->billing_details->address)) {
                $stripeCustomer->address = $paymentMethod->billing_details->address;
            }
        }
    }

    /**
     * @param $stripeCustomer \StripeWPFS\Customer
     * @param $checkoutSession \StripeWPFS\Checkout\Session
     */
    protected function setShippingAddress(&$stripeCustomer, &$checkoutSession)
    {
        if (isset($checkoutSession->shipping->address)) {
            $shipping = array(
                'name' => $checkoutSession->shipping->name,
                'address' => $checkoutSession->shipping->address
            );

            $stripeCustomer->shipping = $shipping;
        }
    }

    /**
     * @param $stripeCustomer \StripeWPFS\Customer
     * @param $paymentMethod \StripeWPFS\PaymentMethod
     * @param $checkoutSession \StripeWPFS\Checkout\Session
     *
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    protected function fixCustomerNamesAndAddresses(&$stripeCustomer, &$paymentMethod, &$checkoutSession)
    {
        $this->setBillingAddress($stripeCustomer, $paymentMethod);
        $this->setShippingAddress($stripeCustomer, $checkoutSession);
        $this->stripe->updateCustomer($stripeCustomer);
    }
}

class MM_WPFS_CheckoutPaymentChargeHandler extends MM_WPFS_CheckoutChargeHandler
{
    use MM_WPFS_CheckoutTaxTools;
    use MM_WPFS_CheckoutInvoiceTools;
    use MM_WPFS_FindStripeCustomer_AddOn;
    use MM_WPFS_OneTimeInvoiceCreator_AddOn;

    /**
     * @param $stripeCustomer \StripeWPFS\Customer
     * @param $formModel MM_WPFS_Public_CheckoutPaymentFormModel
     * @param $transactionData MM_WPFS_SaveCardTransactionData
     *
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    private function setMetadataAndDescriptionForStripeCustomer(&$stripeCustomer, $formModel, $transactionData)
    {
        $stripeCardSavedDescription = MM_WPFS_Utils::prepareStripeCardSavedDescription($this->staticContext, $formModel, $transactionData);

        $stripeCustomer->description = empty($stripeCardSavedDescription) ? null : $stripeCardSavedDescription;
        if (isset($stripeCustomer->metadata) && is_array($stripeCustomer->metadata)) {
            $stripeCustomer->metadata = array_merge($formModel->getMetadata(), $stripeCustomer->metadata);
        } else {
            $stripeCustomer->metadata = $formModel->getMetadata();
        }
        $this->stripe->updateCustomer($stripeCustomer);
    }

    /**
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     * @param $formModel MM_WPFS_Public_CheckoutPaymentFormModel
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData
     *
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    private function setMetadataAndDescriptionForPaymentIntent(&$paymentIntent, $formModel, $transactionData)
    {
        $stripePaymentIntentDescription = MM_WPFS_Utils::prepareStripeChargeDescription($this->staticContext, $formModel, $transactionData);

        $paymentIntent->description = empty($stripePaymentIntentDescription) ? null : $stripePaymentIntentDescription;
        if (isset($paymentIntent->metadata) && is_array($paymentIntent->metadata)) {
            $paymentIntent->metadata = array_merge($formModel->getMetadata(), $paymentIntent->metadata);
        } else {
            $paymentIntent->metadata = $formModel->getMetadata();
        }
        $this->stripe->updatePaymentIntent($paymentIntent);

        $paymentIntent = $this->stripe->retrievePaymentIntent($paymentIntent->id);
        $paymentIntent->wpfs_form = $formModel->getFormName();
    }

    /**
     * @param $paymentMethod \StripeWPFS\PaymentMethod
     * @param $formModel MM_WPFS_Public_CheckoutPaymentFormModel
     *
     * @return \StripeWPFS\Customer
     */
    private function findOrCreateStripeCustomer(&$paymentMethod, $formModel)
    {
        $stripeCustomer = $this->checkoutSubmissionService->retrieveStripeCustomerByPaymentMethod($paymentMethod);

        if (is_null($stripeCustomer)) {
            $stripeCustomer = $this->findExistingStripeCustomerAnywhereByEmail($paymentMethod->billing_details->email);
        }
        if (is_null($stripeCustomer)) {
            $metadata = $formModel->getMetadata();
            $metadata['webhookUrl'] = esc_attr(MM_WPFS_EventHandler::getWebhookEndpointURL($this->staticContext));
            $stripeCustomer = $this->stripe->createCustomerWithPaymentMethod(
                $paymentMethod->id,
                $paymentMethod->billing_details->name,
                $paymentMethod->billing_details->email,
                $metadata
            );
        } else {
            $paymentMethod = $this->stripe->attachPaymentMethodToCustomerIfMissing(
                $stripeCustomer,
                $paymentMethod,
                /* set to default */
                true
            );
        }

        return $stripeCustomer;
    }

    /**
     * @param $saveCardFormModel MM_WPFS_Public_CheckoutPaymentFormModel
     * @param $transactionData MM_WPFS_SaveCardTransactionData
     * @param $stripeCustomer \StripeWPFS\Customer
     */
    protected function fireAfterCheckoutSaveCardAction($saveCardFormModel, $transactionData, $stripeCustomer)
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

        do_action(MM_WPFS::ACTION_NAME_AFTER_CHECKOUT_SAVE_CARD, $params);
    }

    /**
     * @param $paymentFormModel MM_WPFS_Public_PaymentFormModel
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     */
    private function fireAfterCheckoutPaymentAction($paymentFormModel, $transactionData, $paymentIntent)
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

        do_action(MM_WPFS::ACTION_NAME_AFTER_CHECKOUT_PAYMENT_CHARGE, $params);
    }

    /**
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData
     * @param $stripeCustomer \StripeWPFS\Customer
     */
    private function setTransactionDataFromContext(&$transactionData, $context)
    {
        $transactionData->setCustomerTaxId($context->customerTaxId);

        $transactionData->setAmount($context->invoiceData->amount);
        $transactionData->setProductAmountGross($context->invoiceData->amount);
        $transactionData->setProductAmountTax($context->invoiceData->taxAmount);
        $transactionData->setProductAmountNet($context->invoiceData->amount - $context->invoiceData->taxAmount);
        $transactionData->setProductAmountDiscount($context->invoiceData->discountAmount);
    }

    /**
     * @param $checkoutSession \StripeWPFS\Checkout\Session
     */
    protected function getInvoiceDataFromCheckoutSession($checkoutSession)
    {
        $invoiceData = new \StdClass;

        if (count($checkoutSession->line_items->data) > 0) {
            $lineItem = $checkoutSession->line_items->data[0];

            $invoiceData->amount = $lineItem->amount_total;
            $invoiceData->currency = $lineItem->currency;
            $invoiceData->description = $lineItem->description;

            if (count($lineItem->discounts) > 0) {
                $discountItem = $lineItem->discounts[0];

                $invoiceData->discountAmount = $discountItem->amount;
                $invoiceData->couponCode = $discountItem->discount->coupon->name;
            } else {
                $invoiceData->discountAmount = 0;
                $invoiceData->couponCode = '';
            }

            $invoiceData->unitAmount = $lineItem->price->unit_amount;
            $invoiceData->quantity = $lineItem->quantity;

            if (count($lineItem->taxes) > 0) {
                $taxRates = array();
                $taxAmount = 0;

                foreach ($lineItem->taxes as $tax) {
                    $taxAmount += $tax->amount;
                    array_push($taxRates, $tax->rate->id);
                }

                $invoiceData->taxRates = $taxRates;
                $invoiceData->taxAmount = $taxAmount;
            } else {
                $invoiceData->taxRates = array();
                $invoiceData->taxAmount = 0;
            }
        } else {
            $invoiceData->amount = 0;
            $invoiceData->currency = 'usd';
            $invoiceData->description = '';
            $invoiceData->discountAmount = 0;
            $invoiceData->couponCode = '';
            $invoiceData->taxRates = array();
            $invoiceData->taxAmount = 0;
        }

        return $invoiceData;
    }

    /**
     * @param $checkoutSession \StripeWPFS\Checkout\Session
     * @param $stripeCustomer \StripeWPFS\Customer
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     * @param $paymentMethod \StripeWPFS\PaymentMethod
     *
     * @return \StdClass
     */
    protected function getContextDataFromStripeObjects($checkoutSession, $stripeCustomer, $paymentIntent, $paymentMethod)
    {
        $ctx = new \StdClass;
        $ctx->customerTaxId = $this->getTaxIdFromCustomer($stripeCustomer);
        $ctx->invoiceData = $this->getInvoiceDataFromCheckoutSession($checkoutSession);
        return $ctx;
    }

    /**
     * @param MM_WPFS_Public_CheckoutPaymentFormModel $formModel
     * @param \StripeWPFS\Checkout\Session $checkoutSession
     * @return MM_WPFS_ChargeResult
     * @throws \StripeWPFS\Exception\ApiErrorException
     *
     */
    public function handle($formModel, $checkoutSession)
    {
        $chargeResult = new MM_WPFS_ChargeResult();

        $transactionData = null;
        if (MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $formModel->getForm()->customAmount) {
            // tnagy update result with payment type
            $chargeResult->setPaymentType(MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE);

            $setupIntent = $this->checkoutSubmissionService->retrieveStripeSetupIntentByCheckoutSession($checkoutSession);
            $paymentMethod = $this->checkoutSubmissionService->retrieveStripePaymentMethodBySetupIntent($setupIntent);

            if (isset($paymentMethod)) {
                $stripeCustomer = $this->findOrCreateStripeCustomer($paymentMethod, $formModel);

                $formModel->setTransactionId($stripeCustomer->id);
                $formModel->setStripePaymentMethod($paymentMethod);

                $this->fixCustomerNamesAndAddresses($stripeCustomer, $paymentMethod, $checkoutSession);
                $formModel->setStripeCustomer($stripeCustomer, true);

                $transactionData = MM_WPFS_TransactionDataService::createSaveCardDataByModel($formModel);
                $this->setMetadataAndDescriptionForStripeCustomer($stripeCustomer, $formModel, $transactionData);

                $this->db->insertSavedCard($formModel, $transactionData);

                $this->fireAfterCheckoutSaveCardAction($formModel, $transactionData, $stripeCustomer);

                do_action(MM_WPFS::ACTION_NAME_AFTER_CHECKOUT_SAVE_CARD, $stripeCustomer);

                if (MM_WPFS_Mailer::canSendSaveCardPluginReceipt($formModel->getForm())) {
                    $this->mailer->sendSaveCardNotification($formModel->getForm(), $transactionData);
                }

                $chargeResult->setSuccess(true);
                $chargeResult->setMessageTitle(
                    /* translators: Banner title of successful transaction */
                    __('Success', 'wp-full-stripe')
                );
                $chargeResult->setMessage(
                    /* translators: Banner message of saving card successfully */
                    __('Card saved successfully!', 'wp-full-stripe')
                );
            } else {
                $chargeResult->setSuccess(false);
                $chargeResult->setMessageTitle(
                    /* translators: Banner title of failed transaction */
                    __('Failed', 'wp-full-stripe')
                );
                $chargeResult->setMessage(
                    /* It's an internal error, no need to localize it */
                    'Cannot find PaymentMethod!'
                );
            }
        } else {
            // tnagy retrieve Stripe Customer and update form model
            $stripeCustomer = $this->checkoutSubmissionService->retrieveStripeCustomerByCheckoutSession($checkoutSession);
            $paymentIntent = $this->checkoutSubmissionService->retrieveStripePaymentIntentByCheckoutSession($checkoutSession);
            $paymentMethod = $this->checkoutSubmissionService->retrieveStripePaymentMethodByPaymentIntent($paymentIntent);

            $ctx = $this->getContextDataFromStripeObjects($checkoutSession, $stripeCustomer, $paymentIntent, $paymentMethod);

            $this->fixCustomerNamesAndAddresses($stripeCustomer, $paymentMethod, $checkoutSession);
            $formModel->setStripeCustomer($stripeCustomer, true);
            $formModel->setStripePaymentMethod($paymentMethod);
            $formModel->setTransactionId($paymentIntent->id);

            $transactionData = MM_WPFS_TransactionDataService::createOneTimePaymentDataByModel($formModel);
            $this->setTransactionDataFromContext($transactionData, $ctx);

            $this->setMetadataAndDescriptionForPaymentIntent($paymentIntent, $formModel, $transactionData);
            $formModel->setStripePaymentIntent($paymentIntent);

            $latest_charge = $this->stripe->getLatestCharge($paymentIntent);

            $this->db->insertPayment($formModel, $transactionData, $latest_charge);

            if ($formModel->getForm()->generateInvoice == 1) {
                $createInvoiceOptions = new MM_WPFS_CreateOneTimeInvoiceOptions();
                $createInvoiceOptions->autoAdvance = false;
                $createInvoiceOptions->taxRateIds = $ctx->invoiceData->taxRates;
                $stripeInvoice = $this->createInvoiceForOneTimePaymentByFormModel($formModel, $createInvoiceOptions);

                $paidStripeInvoice = $this->stripe->payInvoiceOutOfBand($stripeInvoice->id);
                $this->setTransactionDataFromInvoice($transactionData, $paidStripeInvoice);
            }

            $this->fireAfterCheckoutPaymentAction($formModel, $transactionData, $paymentIntent);

            if (MM_WPFS_Mailer::canSendPaymentPluginReceipt($formModel->getForm())) {
                $this->mailer->sendOneTimePaymentReceipt($formModel->getForm(), $transactionData);
            }

            $chargeResult->setSuccess(true);
            $chargeResult->setMessageTitle(
                /* translators: Banner title of successful transaction */
                __('Success', 'wp-full-stripe')
            );
            $chargeResult->setMessage(
                /* translators: Banner message of successful payment */
                __('Payment Successful!', 'wp-full-stripe')
            );
        }

        $this->handleRedirect($formModel, $transactionData, $chargeResult);

        return $chargeResult;
    }
}

class MM_WPFS_CheckoutDonationChargeHandler extends MM_WPFS_CheckoutChargeHandler
{
    use MM_WPFS_DonationTools_AddOn;
    use MM_WPFS_CheckoutInvoiceTools;
    use MM_WPFS_OneTimeInvoiceCreator_AddOn;

    /**
     * @param $formModel MM_WPFS_Public_DonationFormModel
     * @param $transactionData MM_WPFS_DonationTransactionData
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     *
     * @throws Exception
     */
    private function updatePaymentIntent($formModel, $transactionData, &$paymentIntent)
    {
        $paymentIntent->description = MM_WPFS_Utils::prepareStripeDonationDescription($this->staticContext, $formModel, $transactionData);
        if (isset($paymentIntent->metadata) && is_array($paymentIntent->metadata)) {
            $paymentIntent->metadata = array_merge($formModel->getMetadata(), $paymentIntent->metadata);
        } else {
            $paymentIntent->metadata = $formModel->getMetadata();
        }
        $this->stripe->updatePaymentIntent($paymentIntent);
        $paymentIntent = $this->stripe->retrievePaymentIntent($paymentIntent->id);
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
     * @param $donationFormModel MM_WPFS_Public_DonationFormModel
     * @param $transactionData MM_WPFS_DonationTransactionData
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     */
    protected function fireAfterCheckoutDonationAction($donationFormModel, $transactionData, $paymentIntent)
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

        do_action(MM_WPFS::ACTION_NAME_AFTER_CHECKOUT_DONATION_CHARGE, $params);
    }

    /**
     * @param $donationResult MM_WPFS_DonationCheckoutResult
     * @param $title string
     * @param $message string
     *
     * @return MM_WPFS_DonationCheckoutResult
     */
    protected function createDonationResultSuccess(&$donationResult, $title, $message)
    {
        $donationResult->setSuccess(true);
        $donationResult->setMessageTitle($title);
        $donationResult->setMessage($message);

        return $donationResult;
    }


    /**
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     * @param $stripeCustomer \StripeWPFS\Customer
     *
     * @return \StripeWPFS\PaymentMethod
     */
    protected function setDefaultPaymentMethodFromPaymentIntent($paymentIntent, &$stripeCustomer)
    {
        $paymentMethod = $this->checkoutSubmissionService->retrieveStripePaymentMethodByPaymentIntent($paymentIntent);
        if (!is_null($paymentMethod)) {
            $paymentMethod = $this->stripe->attachPaymentMethodToCustomerIfMissing(
                $stripeCustomer,
                $paymentMethod,
                /* set to default */
                true
            );
        }

        return $paymentMethod;
    }

    /**
     * @param MM_WPFS_Public_DonationFormModel $formModel
     * @param \StripeWPFS\Checkout\Session $checkoutSession
     *
     * @return MM_WPFS_ChargeResult
     * @throws Exception
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function handle($formModel, $checkoutSession)
    {
        $chargeResult = new MM_WPFS_DonationCheckoutResult();

        $stripeCustomer = $this->checkoutSubmissionService->retrieveStripeCustomerByCheckoutSession($checkoutSession);
        $paymentIntent = $this->checkoutSubmissionService->retrieveStripePaymentIntentByCheckoutSession($checkoutSession);
        $paymentMethod = $this->setDefaultPaymentMethodFromPaymentIntent($paymentIntent, $stripeCustomer);

        $this->fixCustomerNamesAndAddresses($stripeCustomer, $paymentMethod, $checkoutSession);

        $formModel->setTransactionId($paymentIntent->id);
        $formModel->setStripePaymentMethod($paymentMethod);
        $formModel->setStripeCustomer($stripeCustomer, true);

        $transactionData = MM_WPFS_TransactionDataService::createDonationDataByFormModel($formModel);

        $this->updatePaymentIntent($formModel, $transactionData, $paymentIntent);
        $this->addFormNameToPaymentIntent($paymentIntent, $formModel->getFormName());

        if ($formModel->getForm()->generateInvoice == 1) {
            $createInvoiceOptions = new MM_WPFS_CreateOneTimeInvoiceOptions();
            $createInvoiceOptions->autoAdvance = false;
            $stripeInvoice = $this->createInvoiceForOneTimePaymentByFormModel($formModel, $createInvoiceOptions);

            $paidStripeInvoice = $this->stripe->payInvoiceOutOfBand($stripeInvoice->id);
            $this->setTransactionDataFromInvoice($transactionData, $paidStripeInvoice);
        }

        $subscription = null;
        if ($this->isRecurringDonation($formModel)) {
            $subscription = $this->createSubscriptionForDonation($formModel);
        }
        $latest_charge = $this->stripe->getLatestCharge($paymentIntent);

        $this->db->insertCheckoutDonation($formModel, $paymentIntent, $subscription, $latest_charge);

        $this->fireAfterCheckoutDonationAction($formModel, $transactionData, $paymentIntent);

        // tnagy update result
        $this->createDonationResultSuccess(
            $chargeResult,
            /* translators: Banner title of successful transaction */
            __('Success', 'wp-full-stripe'),
            /* translators: Banner message of successful payment */
            __('Donation Successful!', 'wp-full-stripe')
        );

        $this->handleRedirect($formModel, $transactionData, $chargeResult);

        if (MM_WPFS_Mailer::canSendDonationPluginReceipt($formModel->getForm())) {
            $this->mailer->sendDonationEmailReceipt($formModel->getForm(), $transactionData);
        }

        return $chargeResult;
    }
}


class MM_WPFS_CheckoutSubscriptionChargeHandler extends MM_WPFS_CheckoutChargeHandler
{
    use MM_WPFS_CheckoutTaxTools;
    use MM_WPFS_CheckoutInvoiceTools;

    /**
     * @param $subscription \StripeWPFS\Subscription
     *
     * @return string
     */
    private function getCouponCode($subscription)
    {
        $res = null;

        if (isset($subscription->discount)) {
            $discount = $subscription->discount;

            if (isset($discount->promotion_code) && isset($discount->promotion_code->code)) {
                $res = $discount->promotion_code->code;
            } elseif (isset($discount->coupon) && isset($discount->coupon->name)) {
                $res = $discount->coupon->name;
            }
        }

        return $res;
    }

    /**
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     * @param $subscription \StripeWPFS\Subscription
     * @param $formModel MM_WPFS_Public_CheckoutSubscriptionFormModel
     */
    private function updateTransactionData(&$transactionData, $subscription, $formModel)
    {
        $latestInvoice = $this->getLatestInvoice($subscription);
        $this->setTransactionDataFromInvoice($transactionData, $latestInvoice);

        $transactionData->setReceiptUrl(isset($latestInvoice->charge) ? $latestInvoice->charge->receipt_url : null);
    }

    /**
     * @param $stripeSubscription \StripeWPFS\Subscription
     *
     * @return array
     */
    private function extractPopupFormSubmit($stripeSubscription)
    {
        $popupFormSubmit = null;
        // tnagy retrieve Stripe Subscription and update form model
        if (isset($stripeSubscription)) {
            if (isset($stripeSubscription->metadata) && isset($stripeSubscription->metadata->client_reference_id)) {
                $submitHash = $stripeSubscription->metadata->client_reference_id;
                $popupFormSubmit = $this->db->findPopupFormSubmitByHash($submitHash);
            }
        }

        return $popupFormSubmit;
    }

    /**
     * @param $subscription \StripeWPFS\Subscription
     *
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    private function processStripeEvents($subscription)
    {
        $popupFormSubmit = $this->extractPopupFormSubmit($subscription);

        if (isset($popupFormSubmit) && isset($popupFormSubmit->relatedStripeEventIDs)) {
            $relatedStripeEventIDs = $this->retrieveStripeEventIDs($popupFormSubmit->relatedStripeEventIDs);

            foreach ($relatedStripeEventIDs as $relatedStripeEventID) {
                $stripeEvent = $this->retrieveStripeEvent($relatedStripeEventID);
                if (isset($stripeEvent)) {
                    $this->eventHandler->handle($stripeEvent);
                }
            }
        }
    }


    /**
     * @param $stripePaymentIntent \StripeWPFS\PaymentIntent
     * @param $stripeSetupIntent \StripeWPFS\SetupIntent
     *
     * @return string|\StripeWPFS\PaymentMethod|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    private function extractPaymentMethod($stripePaymentIntent, $stripeSetupIntent)
    {
        $paymentMethod = $this->checkoutSubmissionService->retrieveStripePaymentMethodByPaymentIntent($stripePaymentIntent);
        if (is_null($paymentMethod)) {
            $paymentMethod = $this->checkoutSubmissionService->retrieveStripePaymentMethodBySetupIntent($stripeSetupIntent);
        }

        return $paymentMethod;
    }


    /**
     * @param $stripePaymentIntent \StripeWPFS\PaymentIntent
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     */
    private function updateSubscriptionToRunning($stripePaymentIntent, $transactionData)
    {
        if (isset($stripePaymentIntent)) {
            if (
                \StripeWPFS\PaymentIntent::STATUS_SUCCEEDED === $stripePaymentIntent->status
                || \StripeWPFS\PaymentIntent::STATUS_REQUIRES_CAPTURE === $stripePaymentIntent->status
                || \StripeWPFS\PaymentIntent::STATUS_PROCESSING === $stripePaymentIntent->status
            ) {
                $this->db->updateSubscriptionByPaymentIntentToRunning($stripePaymentIntent->id);
            }
        } else if ($transactionData->getPlanGrossAmountAndGrossSetupFeeTotal() == 0) {
            // If there is no charge then there is no payment intent
            $this->db->updateSubscriptionToRunning($transactionData->getTransactionId());
        }
    }

    /**
     * @param $stripeSubscription
     * @param $formModel MM_WPFS_Public_CheckoutSubscriptionFormModel
     */
    private function setMetadataForSubscription($stripeSubscription, $formModel)
    {
        if (isset($stripeSubscription->metadata) && is_array($stripeSubscription->metadata)) {
            $stripeSubscription->metadata = array_merge($formModel->getMetadata(), $stripeSubscription->metadata);
        } else {
            $stripeSubscription->metadata = $formModel->getMetadata();
        }
        $this->stripe->updateSubscription($stripeSubscription);
    }

    /**
     * @param $formModel MM_WPFS_Public_CheckoutSubscriptionFormModel
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     * @param $subscription \StripeWPFS\Subscription
     */
    private function fireAfterSubscriptionAction($subscriptionFormModel, $transactionData, $subscription)
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

        do_action(MM_WPFS::ACTION_NAME_AFTER_CHECKOUT_SUBSCRIPTION_CHARGE, $params);
    }

    /**
     * @param $lineItem
     * @return bool
     */
    protected function isSetupFeeLineItem($lineItem)
    {
        $result = false;
        $metaData = $lineItem->price->product->metadata;

        if (
            $metaData !== null && isset($metaData->type) && 
            $metaData->type === 'setupFee'
        ) {
            $result = true;
        }

        return $result;
    }

    protected function extractPricingDataFromLineItem($lineItem)
    {
        $result = new \StdClass;

        $result->amount = $lineItem->amount_total;
        $result->currency = $lineItem->currency;
        $result->description = $lineItem->description;

        if (count($lineItem->discounts) > 0) {
            $discountItem = $lineItem->discounts[0];

            $result->discountAmount = $discountItem->amount;
            $result->couponCode = $discountItem->discount->coupon['name'];
        } else {
            $result->discountAmount = 0;
            $result->couponCode = '';
        }

        $result->unitAmount = $lineItem->price->unit_amount;
        $result->quantity = $lineItem->quantity;

        if (count($lineItem->taxes) > 0) {
            $taxRates = array();
            $taxAmount = 0;

            foreach ($lineItem->taxes as $tax) {
                $taxAmount += $tax->amount;
                array_push($taxRates, $tax->rate->id);
            }

            $result->taxRates = $taxRates;
            $result->taxAmount = $taxAmount;
        } else {
            $result->taxRates = array();
            $result->taxAmount = 0;
        }

        return $result;
    }

    /**
     * @param $checkoutSession \StripeWPFS\Checkout\Session
     */
    protected function getInvoiceDataFromCheckoutSession($checkoutSession)
    {
        $invoiceData = new \StdClass;
        $currency = null;

        if (count($checkoutSession->line_items->data) > 0) {
            foreach ($checkoutSession->line_items->data as $lineItem) {
                if ($this->isSetupFeeLineItem($lineItem)) {
                    $invoiceData->setupFee = $this->extractPricingDataFromLineItem($lineItem);
                } else {
                    $invoiceData->plan = $this->extractPricingDataFromLineItem($lineItem);
                }
            }
        } else {
            $result = new \StdClass;

            $result->currency = 'usd';
            $result->quantity = 0;
            $result->unitAmount = 0;
            $result->amount = 0;
            $result->discountAmount = 0;
            $result->taxAmount = 0;
            $result->description = '';
            $result->couponCode = '';
            $result->taxRates = array();

            $invoiceData->plan = $result;
        }

        return $invoiceData;
    }

    /**
     * @param $checkoutSession \StripeWPFS\Checkout\Session
     * @param $stripeCustomer \StripeWPFS\Customer
     * @param $paymentIntent \StripeWPFS\PaymentIntent
     * @param $paymentMethod \StripeWPFS\PaymentMethod
     *
     * @returns \StdClass
     */
    protected function getContextDataFromStripeObjects($checkoutSession, $stripeCustomer, $paymentIntent, $paymentMethod)
    {
        $ctx = new \StdClass;

        $ctx->customerTaxId = $this->getTaxIdFromCustomer($stripeCustomer);

        $ctx->invoiceData = $this->getInvoiceDataFromCheckoutSession($checkoutSession);

        return $ctx;
    }

    /**
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     * @param $data
     */
    protected function setPricingPlaceholders(&$transactionData, $data)
    {
        $setupFeeAmount = 0;
        $setupFeeDiscountAmount = 0;
        $setupFeeTaxAmount = 0;
        $setupFeeUnitAmount = 0;
        if (property_exists($data, 'setupFee')) {
            $setupFeeAmount = $data->setupFee->amount;
            $setupFeeDiscountAmount = $data->setupFee->discountAmount;
            $setupFeeTaxAmount = $data->setupFee->taxAmount;
            $setupFeeUnitAmount = $data->setupFee->unitAmount;
        }

        $transactionData->setAmount($setupFeeAmount + $data->plan->amount);
        $transactionData->setPlanQuantity($data->plan->quantity);
        $transactionData->setPlanCurrency($data->plan->currency);
        if ($data->plan->quantity == 1) {
            $transactionData->setPlanGrossAmountTotal($data->plan->amount);
            $transactionData->setPlanGrossAmount($transactionData->getPlanGrossAmountTotal());

            $transactionData->setPlanTaxAmountTotal($data->plan->taxAmount);
            $transactionData->setPlanTaxAmount($transactionData->getPlanTaxAmountTotal());

            $transactionData->setPlanNetAmountTotal($transactionData->getPlanGrossAmountTotal() - $transactionData->getPlanTaxAmountTotal());
            $transactionData->setPlanNetAmount($transactionData->getPlanNetAmountTotal());

            $transactionData->setSetupFeeGrossAmountTotal($setupFeeAmount);
            $transactionData->setSetupFeeGrossAmount($transactionData->getSetupFeeGrossAmountTotal());

            $transactionData->setSetupFeeTaxAmountTotal($setupFeeTaxAmount);
            $transactionData->setSetupFeeTaxAmount($transactionData->getSetupFeeTaxAmountTotal());

            $transactionData->setSetupFeeNetAmountTotal($transactionData->getSetupFeeGrossAmountTotal() - $transactionData->getSetupFeeTaxAmountTotal());
            $transactionData->setSetupFeeNetAmount($transactionData->getSetupFeeNetAmountTotal());
        } else {
            $transactionData->setPlanGrossAmountTotal($data->plan->amount);
            $planUnitAmount = (int) round($transactionData->getPlanGrossAmountTotal() / $data->plan->quantity);
            $transactionData->setPlanGrossAmount($planUnitAmount);

            $transactionData->setPlanTaxAmountTotal($data->plan->taxAmount);
            $planTaxUnitAmount = (int) round($transactionData->getPlanTaxAmountTotal() / $data->plan->quantity);
            $transactionData->setPlanTaxAmount($planTaxUnitAmount);

            $transactionData->setPlanNetAmountTotal($transactionData->getPlanGrossAmountTotal() - $transactionData->getPlanTaxAmountTotal());
            $planNetUnitAmount = (int) round($transactionData->getPlanNetAmountTotal() / $data->plan->quantity);
            $transactionData->setPlanNetAmount($planNetUnitAmount);

            $transactionData->setSetupFeeGrossAmountTotal($setupFeeAmount);
            $setupFeeUnitAmount = (int) round($transactionData->getSetupFeeGrossAmountTotal() / $data->plan->quantity);
            $transactionData->setSetupFeeGrossAmount($setupFeeUnitAmount);

            $transactionData->setSetupFeeTaxAmountTotal($setupFeeTaxAmount);
            $setupFeeTaxUnitAmount = (int) round($transactionData->getSetupFeeTaxAmountTotal() / $data->plan->quantity);
            $transactionData->setSetupFeeTaxAmount($setupFeeTaxUnitAmount);

            $transactionData->setSetupFeeNetAmountTotal($transactionData->getSetupFeeGrossAmountTotal() - $transactionData->getSetupFeeTaxAmountTotal());
            $setupFeeNetUnitAmount = (int) round($transactionData->getSetupFeeNetAmountTotal() / $data->plan->quantity);
            $transactionData->setSetupFeeNetAmount($setupFeeNetUnitAmount);
        }
    }

    /**
     * @param $transactionData MM_WPFS_SubscriptionTransactionData
     * @param $stripeCustomer \StripeWPFS\Customer
     */
    private function setTransactionDataFromContext(&$transactionData, $context)
    {
        $transactionData->setCustomerTaxId($context->customerTaxId);

        $this->setPricingPlaceholders($transactionData, $context->invoiceData);
    }

    public function handle($formModel, $checkoutSession)
    {
        $chargeResult = new MM_WPFS_ChargeResult();

        $stripeCustomer = $this->checkoutSubmissionService->retrieveStripeCustomerByCheckoutSession($checkoutSession);
        $stripeSubscription = $this->checkoutSubmissionService->retrieveStripeSubscriptionByCheckoutSession($checkoutSession);
        $stripePaymentIntent = $this->checkoutSubmissionService->findPaymentIntentInCheckoutSession($checkoutSession);
        $stripeSetupIntent = $this->checkoutSubmissionService->findSetupIntentInCheckoutSession($checkoutSession);
        $paymentMethod = $this->extractPaymentMethod($stripePaymentIntent, $stripeSetupIntent);

        if (!is_null($paymentMethod)) {
            $paymentMethod = $this->stripe->attachPaymentMethodToCustomerIfMissing(
                $stripeCustomer,
                $paymentMethod,
                /* set to default */
                true
            );
        }

        $ctx = $this->getContextDataFromStripeObjects($checkoutSession, $stripeCustomer, $stripePaymentIntent, $paymentMethod);

        $this->fixCustomerNamesAndAddresses($stripeCustomer, $paymentMethod, $checkoutSession);
        $formModel->setStripeCustomer($stripeCustomer, true);
        $formModel->setStripePaymentMethod($paymentMethod);
        $formModel->setStripePaymentIntent($stripePaymentIntent);
        $formModel->setStripeSetupIntent($stripeSetupIntent);
        $formModel->setStripeSubscription($stripeSubscription);
        $formModel->setTransactionId($stripeSubscription->id);

        $formModel->setCouponCode($this->getCouponCode($stripeSubscription));

        $transactionData = MM_WPFS_TransactionDataService::createSubscriptionDataByModel($formModel);
        $this->setTransactionDataFromContext($transactionData, $ctx);
        $this->updateTransactionData($transactionData, $stripeSubscription, $formModel);

        $this->setMetadataForSubscription($stripeSubscription, $formModel);

        $this->db->insertSubscriber($formModel, $transactionData);

        $this->updateSubscriptionToRunning($stripePaymentIntent, $transactionData);

        $this->processStripeEvents($stripeSubscription);

        $this->fireAfterSubscriptionAction($formModel, $transactionData, $stripeSubscription);

        $chargeResult->setSuccess(true);
        $chargeResult->setMessageTitle(
            /* translators: Banner title of successful transaction */
            __('Success', 'wp-full-stripe')
        );
        $chargeResult->setMessage(
            /* translators: Banner message of successful payment */
            __('Payment Successful!', 'wp-full-stripe')
        );

        $this->handleRedirect($formModel, $transactionData, $chargeResult);

        if (MM_WPFS_Mailer::canSendSubscriptionPluginReceipt($formModel->getForm())) {
            $this->mailer->sendSubscriptionStartedEmailReceipt($formModel->getForm(), $transactionData);
        }

        return $chargeResult;
    }

    /**
     * @param $encodedStripeEventIDs
     *
     * @return array|mixed|object
     */
    protected function retrieveStripeEventIDs($encodedStripeEventIDs)
    {
        $decodedStripeEventIDs = json_decode($encodedStripeEventIDs);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $decodedStripeEventIDs = array();
        }
        if (!is_array($decodedStripeEventIDs)) {
            $decodedStripeEventIDs = array();
        }

        return $decodedStripeEventIDs;
    }

    /**
     * @param $stripeEventID
     *
     * @return \StripeWPFS\Event
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    protected function retrieveStripeEvent($stripeEventID)
    {
        return $this->stripe->retrieveEvent($stripeEventID);
    }

    /**
     * @param $stripeSubscription
     *
     * @return \StripeWPFS\Invoice
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    protected function getLatestInvoice($stripeSubscription)
    {
        $result = null;

        if (isset($stripeSubscription->latest_invoice)) {
            $result = $stripeSubscription->latest_invoice;
        } else {
            $params = array(
                'expand' => array(
                    'payment_intent',
                    'charge'
                )
            );
            $result = $this->stripe->retrieveInvoiceWithParams($stripeSubscription->latest_invoice, $params);
        }

        return $result;
    }

    protected function getLatestInvoiceUrl($stripeSubscription)
    {
        $latestInvoice = $this->stripe->retrieveInvoice($stripeSubscription->latest_invoice);

        return $latestInvoice->invoice_pdf;
    }

}
