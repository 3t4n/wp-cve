<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2019.08.06.
 * Time: 11:09
 */
class MM_WPFS_CheckoutSubmissionService
{
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    const POPUP_FORM_SUBMIT_STATUS_CREATED = 'created';
    const POPUP_FORM_SUBMIT_STATUS_PENDING = 'pending';
    const POPUP_FORM_SUBMIT_STATUS_FAILED = 'failed';
    const POPUP_FORM_SUBMIT_STATUS_CANCELLED = 'cancelled';
    const POPUP_FORM_SUBMIT_STATUS_SUCCESS = 'success';
    const POPUP_FORM_SUBMIT_STATUS_COMPLETE = 'complete';
    const POPUP_FORM_SUBMIT_STATUS_INTERNAL_ERROR = 'internal_error';
    const CHECKOUT_SESSION_STATUS_SUCCESS = 'success';
    const CHECKOUT_SESSION_STATUS_CANCELLED = 'cancelled';
    const PROCESS_RESULT_SET_TO_SUCCESS = 1;
    const PROCESS_RESULT_SET_TO_FAILED = 2;
    const PROCESS_RESULT_EXPIRED = 3;
    const PROCESS_RESULT_WAIT_FOR_STATUS_CHANGE = 4;
    const PROCESS_RESULT_INTERNAL_ERROR = 20;
    const ACTION_FULLSTRIPE_PROCESS_CHECKOUT_SUBMISSIONS = 'fullstripe_process_checkout_submissions';
    const STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH = 'wpfs-sid';
    const STRIPE_CALLBACK_PARAM_WPFS_CHECKOUT_SESSION_ID = 'wpfs-csid';
    const STRIPE_CALLBACK_PARAM_WPFS_STATUS = 'wpfs-status';

    /** @var bool */
    private static $running = false;

    /** @var $stripe MM_WPFS_Stripe */
    private $stripe = null;

    /** @var $db MM_WPFS_Database */
    private $db = null;

    /** @var MM_WPFS_Options  */
    private $options = null;

    /** @var int Iteration count for processing entries in the scheduled function */
    private $iterationCount = 5;
    /** @var int Entry count processed in one iteration */

    private $entryCount = 50;
    /** @var int how many times can be an entry processed with error before putting it in INTERNAL_ERROR status */

    private $processErrorLimit = 3;

    /**
     * MM_WPFS_CheckoutSubmissionService constructor.
     *
     * @throws Exception
     */
    public function __construct($loggerService)
    {
        $this->setup($loggerService);
        $this->hooks();

        $this->logger->debug(__FUNCTION__, 'CALLED, running=' . ($this->isRunning() ? 'true' : 'false'));
    }

    private function setup($loggerService)
    {
        $this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_CHECKOUT_SUBMISSION);
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        $this->db = new MM_WPFS_Database();
        $this->stripe = new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService);
    }

    private function hooks()
    {
        add_action(
            self::ACTION_FULLSTRIPE_PROCESS_CHECKOUT_SUBMISSIONS,
            array(
                $this,
                'processCheckoutSubmissions'
            )
        );
    }

    /**
     * @return bool
     */
    private function isRunning()
    {
        return self::$running;
    }

    public static function onActivation()
    {
        if (!wp_next_scheduled(self::ACTION_FULLSTRIPE_PROCESS_CHECKOUT_SUBMISSIONS)) {
            wp_schedule_event(time(), WP_FULL_STRIPE_CRON_SCHEDULES_KEY_15_MIN, self::ACTION_FULLSTRIPE_PROCESS_CHECKOUT_SUBMISSIONS);
            MM_WPFS_Utils::log('MM_WPFS_CheckoutSubmissionService->onActivation(): Event scheduled.');
        }
    }

    public static function onDeactivation()
    {
        wp_clear_scheduled_hook(self::ACTION_FULLSTRIPE_PROCESS_CHECKOUT_SUBMISSIONS);
        MM_WPFS_Utils::log('MM_WPFS_CheckoutSubmissionService->onDeactivation(): Scheduled event cleared.');
    }

    /**
     * @param MM_WPFS_Public_FormModel $formModel
     *
     * @return string
     * @throws Exception
     */
    private function createSubmitEntry($formModel)
    {
        $liveMode = $this->options->get(MM_WPFS_Options::OPTION_API_MODE) === MM_WPFS::STRIPE_API_MODE_LIVE;
        $salt = wp_generate_password(16, false);
        $submitId = time() . '|' . $formModel->getFormHash() . '|' . $liveMode . '|' . $salt;
        $submitHash = hash('sha256', $submitId);

        // Remove optional anchor from the URL tail
        $rawReferrer = $formModel->getReferrer();
        $prunedReferrer = strpos($rawReferrer, '#') === false ?
            $rawReferrer :
            strstr($formModel->getReferrer(), '#', true);
        $decoratedReferrer = add_query_arg(
            array(
                self::STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH => $submitHash
            ),
            $prunedReferrer
        ) . '#' . \MM_WPFS_FormViewConstants::ATTR_ID_VALUE_PREFIX . $formModel->getFormHash();

        $this->db->insertCheckoutFormSubmit(
            $submitHash,
            $formModel->getFormHash(),
            MM_WPFS_Utils::getFormType($formModel->getForm()),
            $decoratedReferrer,
            json_encode($formModel->getPostData(), JSON_UNESCAPED_UNICODE),
            $liveMode
        );

        return $submitHash;
    }

    /**
     * @param $submitHash
     * @param $stripeCheckoutSessionId
     *
     * @return false|int
     * @throws Exception
     */
    public function updateSubmitEntryWithSessionIdToPending($submitHash, $stripeCheckoutSessionId)
    {
        return $this->db->update_popup_form_submit_by_hash(
            $submitHash,
            array(
                'checkoutSessionId' => $stripeCheckoutSessionId,
                'status' => self::POPUP_FORM_SUBMIT_STATUS_PENDING
            )
        );
    }

    /**
     * @param MM_WPFS_Public_CheckoutPaymentFormModel|MM_WPFS_Public_CheckoutSubscriptionFormModel|MM_WPFS_Public_CheckoutDonationFormModel $formModel
     *
     * @return \StripeWPFS\Checkout\Session
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function createCheckoutSession($formModel)
    {

        $submitHash = $this->createSubmitEntry($formModel);
        $fieldConfiguration = MM_WPFS::getInstance()->getFormFieldConfiguration($formModel->getFormGetParametersAsArray(), MM_WPFS_Utils::getFormType($formModel->getForm()), $formModel->getFormName());

        if ($formModel instanceof MM_WPFS_Public_CheckoutPaymentFormModel) {
            if (MM_WPFS::PAYMENT_TYPE_CARD_CAPTURE === $formModel->getForm()->customAmount) {
                $checkoutSessionParameters = (new MM_WPFS_CheckoutSessionBuilder_SaveCard($submitHash, $formModel, $fieldConfiguration, $this->stripe, $this->loggerService))->build();
            } else {
                $checkoutSessionParameters = (new MM_WPFS_CheckoutSessionBuilder_OneTimePayment($submitHash, $formModel, $fieldConfiguration, $this->stripe, $this->loggerService))->build();
            }
        } else if ($formModel instanceof MM_WPFS_Public_CheckoutDonationFormModel) {
            $checkoutSessionParameters = (new MM_WPFS_CheckoutSessionBuilder_Donation($submitHash, $formModel, $fieldConfiguration, $this->stripe, $this->loggerService))->build();
        } else if ($formModel instanceof MM_WPFS_Public_CheckoutSubscriptionFormModel) {
            $checkoutSessionParameters = (new MM_WPFS_CheckoutSessionBuilder_Subscription($submitHash, $formModel, $fieldConfiguration, $this->stripe, $this->loggerService))->build();
        } else {
            throw new Exception("Not supported checkout form model. ", $formModel);
        }

        $stripeCheckoutSession = $this->stripe->createCheckoutSession($checkoutSessionParameters);

        $this->updateSubmitEntryWithSessionIdToPending($submitHash, $stripeCheckoutSession->id);

        return $stripeCheckoutSession;
    }

    /**
     * @param $submitHash
     *
     * @return array|null|object|void
     */
    public function retrieveSubmitEntry($submitHash)
    {
        return $this->db->findPopupFormSubmitByHash($submitHash);
    }

    public function processCheckoutSubmissions()
    {
        $this->logger->debug(__FUNCTION__, 'CALLED');

        try {

            if (!$this->isRunning()) {
                $this->start();
                $this->findAndProcessSubmissions();
                $this->stop();
            }

        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Error while processing checkout submissions', $ex);
            $this->stop();
        }

        $this->logger->debug(__FUNCTION__, 'FINISHED');
    }

    private function start()
    {
        self::$running = true;
    }

    /**
     * @throws Exception
     */
    private function findAndProcessSubmissions()
    {
        $iteration = 0;
        $popupFormSubmitIdsToFaulty = array();
        $popupFormSubmitsToProcess = array();
        $popupFormSubmitsToComplete = array();
        $popupFormSubmitIdsToDelete = array();
        $popupFormSubmitsTouched = array();

        $popupFormSubmits = $this->findPopupEntries();
        if (isset($popupFormSubmits)) {

            $this->logger->debug(__FUNCTION__, 'Found ' . count($popupFormSubmits) . ' record(s) to process.');

            while ($iteration < $this->iterationCount && count($popupFormSubmits) > 0) {
                $iteration++;

                // tnagy prepare array of submits
                if (!is_array($popupFormSubmits)) {
                    $popupFormSubmits = array($popupFormSubmits);
                }

                // tnagy sort out submits by status
                foreach ($popupFormSubmits as $popupFormSubmit) {

                    if (!array_key_exists($popupFormSubmit->id, $popupFormSubmitsTouched)) {

                        // tnagy mark record as touched
                        $popupFormSubmitsTouched[$popupFormSubmit->id] = $popupFormSubmit;

                        if ($popupFormSubmit->processedWithError > $this->processErrorLimit) {
                            array_push($popupFormSubmitIdsToFaulty, $popupFormSubmit->id);
                            $this->logger->debug(__FUNCTION__, 'Proposed to FAULTY.');
                        } elseif (self::POPUP_FORM_SUBMIT_STATUS_CREATED === $popupFormSubmit->status) {
                            array_push($popupFormSubmitsToProcess, $popupFormSubmit);
                            $this->logger->debug(__FUNCTION__, 'Proposed to PROCESS.');
                        } elseif (self::POPUP_FORM_SUBMIT_STATUS_PENDING === $popupFormSubmit->status) {
                            array_push($popupFormSubmitsToProcess, $popupFormSubmit);
                            $this->logger->debug(__FUNCTION__, 'Proposed to PROCESS.');
                        } elseif (self::POPUP_FORM_SUBMIT_STATUS_SUCCESS === $popupFormSubmit->status) {
                            array_push($popupFormSubmitsToComplete, $popupFormSubmit);
                            $this->logger->debug(__FUNCTION__, 'Proposed to PROCESS.');
                        } elseif (self::POPUP_FORM_SUBMIT_STATUS_FAILED === $popupFormSubmit->status) {
                            array_push($popupFormSubmitIdsToDelete, $popupFormSubmit->id);
                            $this->logger->debug(__FUNCTION__, 'Proposed to DELETE.');
                        } elseif (self::POPUP_FORM_SUBMIT_STATUS_COMPLETE === $popupFormSubmit->status) {
                            array_push($popupFormSubmitIdsToDelete, $popupFormSubmit->id);
                            $this->logger->debug(__FUNCTION__, 'Proposed to DELETE.');
                        }
                    }

                }

                // tnagy process submits
                foreach ($popupFormSubmitsToProcess as $popupFormSubmit) {
                    $result = $this->processSinglePopupFormSubmit($popupFormSubmit);
                    if (self::PROCESS_RESULT_SET_TO_SUCCESS === $result) {
                        $this->logger->debug(__FUNCTION__, 'Checkout Form Submission successfully processed.');

                        array_push($popupFormSubmitsToComplete, $popupFormSubmit);
                        $this->logger->debug(__FUNCTION__, 'Proposed to COMPLETE.');
                    } elseif (self::PROCESS_RESULT_SET_TO_FAILED === $result) {
                        $this->logger->debug(__FUNCTION__, 'Checkout Form Submission processing failed.');

                        array_push($popupFormSubmitIdsToDelete, $popupFormSubmit->id);
                        $this->logger->debug(__FUNCTION__, 'Proposed to DELETE.');
                    } elseif (self::PROCESS_RESULT_EXPIRED === $result) {
                        $this->logger->debug(__FUNCTION__, 'CheckoutSession expired for Checkout Form Submission.');

                        $this->updateSubmitEntryWithCancelled($popupFormSubmit);
                        array_push($popupFormSubmitIdsToDelete, $popupFormSubmit->id);
                        $this->logger->debug(__FUNCTION__, 'Proposed to DELETE.');
                    } elseif (self::PROCESS_RESULT_WAIT_FOR_STATUS_CHANGE === $result) {
                        $this->logger->debug(__FUNCTION__, 'Checkout Form Submission skipped, waiting for status change.');
                    } elseif (self::PROCESS_RESULT_INTERNAL_ERROR === $result) {
                        $this->logger->error(__FUNCTION__, 'Internal error occurred during Checkout Form Submission.');
                    }
                }

                // tnagy complete submits
                foreach ($popupFormSubmitsToComplete as $popupFormSubmit) {
                    $this->updateSubmitEntryWithComplete($popupFormSubmit);
                    array_push($popupFormSubmitIdsToDelete, $popupFormSubmit->id);

                    $this->logger->debug(__FUNCTION__, 'Proposed to DELETE.');
                }

                // tnagy delete submits
                $deleted = $this->deleteSubmitEntriesById($popupFormSubmitIdsToDelete);
                $this->logger->debug(__FUNCTION__, 'Deleted ' . $deleted . ' Checkout Form Submission(s).');

                // tnagy mark submits as faulty
                $faulty = $this->updateSubmitEntriesWithInternalError($popupFormSubmitIdsToFaulty);
                $this->logger->debug(__FUNCTION__, 'Marked as FAULTY ' . $faulty . ' Checkout Form Submission(s).');

                // tnagy clear arrays
                $popupFormSubmitIdsToFaulty = array();
                $popupFormSubmitsToProcess = array();
                $popupFormSubmitsToComplete = array();
                $popupFormSubmitIdsToDelete = array();

                // tnagy load next fragment of submits
                $popupFormSubmits = $this->findPopupEntries();
            }
        }

    }

    /**
     * @return array|null|object
     */
    private function findPopupEntries()
    {
        $liveMode = $this->options->get(MM_WPFS_Options::OPTION_API_MODE) === MM_WPFS::STRIPE_API_MODE_LIVE;

        return $this->db->find_popup_form_submits($liveMode, $this->entryCount);
    }

    /**
     * @param $popupFormSubmit
     *
     * @return int
     * @throws Exception
     */
    private function processSinglePopupFormSubmit($popupFormSubmit)
    {
        try {
            if (isset($popupFormSubmit->checkoutSessionId)) {
                $checkoutSession = $this->retrieveCheckoutSession($popupFormSubmit->checkoutSessionId);
                $paymentIntent = $this->findPaymentIntentInCheckoutSession($checkoutSession);
                if (isset($paymentIntent) && \StripeWPFS\PaymentIntent::STATUS_SUCCEEDED === $paymentIntent->status) {
                    $formModel = null;
                    $checkoutChargeHandler = null;
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
                            $this->updateSubmitEntryWithSuccess($popupFormSubmit, $chargeResult->getMessageTitle(), $chargeResult->getMessage());

                            return self::PROCESS_RESULT_SET_TO_SUCCESS;
                        } else {
                            $this->updateSubmitEntryWithFailed($popupFormSubmit);

                            return self::PROCESS_RESULT_SET_TO_FAILED;
                        }
                    } else {
                        $errorMessage = 'Unknown formType=' . $popupFormSubmit->formType;
                        $this->updateSubmitEntryWithErrorCount($popupFormSubmit, $errorMessage);
                        $this->logger->error(__FUNCTION__, $errorMessage);

                        return self::PROCESS_RESULT_INTERNAL_ERROR;
                    }
                }
            }

            // tnagy check expiration
            $expirationDate = time() - 24 * 60 * 60;
            $creationDate = strtotime($popupFormSubmit->created);

            if ($creationDate < $expirationDate) {
                return self::PROCESS_RESULT_EXPIRED;
            }

        } catch (Exception $ex) {
            $this->updateSubmitEntryWithErrorCount($popupFormSubmit, $ex->getMessage());
            $this->logger->error(__FUNCTION__, 'Error while processing checkout form submit', $ex);

            return self::PROCESS_RESULT_INTERNAL_ERROR;
        }

        return self::PROCESS_RESULT_WAIT_FOR_STATUS_CHANGE;
    }

    /**
     * @param $checkoutSessionId
     *
     * @return \StripeWPFS\Checkout\Session
     */
    public function retrieveCheckoutSession($checkoutSessionId)
    {
        $checkoutSession = $this->stripe->retrieveCheckoutSessionWithParams(
            $checkoutSessionId,
            array(
                'expand' => array(
                    'customer',
                    'customer.tax_ids',
                    'payment_intent',
                    'payment_intent.payment_method',
                    'setup_intent',
                    'setup_intent.payment_method',
                    'subscription',
                    'subscription.latest_invoice.payment_intent',
                    'subscription.pending_setup_intent',
                    'line_items',
                    'line_items.data.discounts',
                    'line_items.data.taxes',
                    'line_items.data.price.product'
                )
            )
        );

        return $checkoutSession;
    }

    /**
     * @param $checkoutSession
     *
     * @return string|\StripeWPFS\PaymentIntent|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function findPaymentIntentInCheckoutSession($checkoutSession)
    {
        $paymentIntent = null;
        if (isset($checkoutSession)) {
            $paymentIntent = $this->retrieveStripePaymentIntentByCheckoutSession($checkoutSession);
            if (is_null($paymentIntent)) {
                $stripeSubscription = $this->retrieveStripeSubscriptionByCheckoutSession($checkoutSession);
                $paymentIntent = $this->findPaymentIntentInSubscription($stripeSubscription);
            }
        }

        return $paymentIntent;
    }

    /**
     * @param $checkoutSession
     *
     * @return \StripeWPFS\PaymentIntent|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function retrieveStripePaymentIntentByCheckoutSession($checkoutSession)
    {
        $stripePaymentIntent = null;
        if (isset($checkoutSession)) {
            if (isset($checkoutSession->payment_intent)) {
                $paymentIntent = $checkoutSession->payment_intent;
                if (isset($paymentIntent)) {

                    $stripePaymentIntent = $paymentIntent;
                } else {
                    $stripePaymentIntent = $this->stripe->retrievePaymentIntent($checkoutSession->payment_intent);
                }
            }
        }

        return $stripePaymentIntent;
    }

    /**
     * @param $checkoutSession
     *
     * @return \StripeWPFS\Subscription|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function retrieveStripeSubscriptionByCheckoutSession($checkoutSession)
    {
        $stripeSubscription = null;
        if (isset($checkoutSession)) {
            if (isset($checkoutSession->subscription)) {
                $subscriptionId = null;
                if (isset($checkoutSession->subscription)) {
                    $subscriptionId = $checkoutSession->subscription->id;
                } else {
                    $subscriptionId = $checkoutSession->subscription;
                }

                $stripeSubscription = $this->stripe->retrieveSubscriptionWithParams(
                    $subscriptionId,
                    array(
                        'expand' => array(
                            'latest_invoice',
                            'latest_invoice.payment_intent',
                            'latest_invoice.charge',
                            'pending_setup_intent',
                            'discount.promotion_code'
                        )
                    )
                );
            }
        }

        return $stripeSubscription;
    }

    /**
     * @param $stripeSubscription
     *
     * @return string|\StripeWPFS\PaymentIntent|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function findPaymentIntentInSubscription($stripeSubscription)
    {
        $paymentIntent = null;
        if (isset($stripeSubscription)) {
            if (isset($stripeSubscription->latest_invoice)) {
                $stripeInvoice = null;
                if (isset($stripeSubscription->latest_invoice)) {
                    $stripeInvoice = $stripeSubscription->latest_invoice;
                } else {
                    $retrieveParams = array(
                        'expand' => array(
                            'payment_intent',
                            'charge'
                        )
                    );

                    $stripeInvoice = $this->stripe->retrieveInvoiceWithParams(
                        $stripeSubscription->latest_invoice,
                        $retrieveParams
                    );
                }
                if (isset($stripeInvoice->payment_intent)) {
                    if ($stripeInvoice->payment_intent->id) {
                        $paymentIntent = $stripeInvoice->payment_intent;
                    } else {
                        $paymentIntent = $this->stripe->retrievePaymentIntent($stripeInvoice->payment_intent);
                    }
                }
            }
        }

        return $paymentIntent;
    }

    /**
     * @param $popupFormSubmit
     * @param $lastMessageTitle
     * @param $lastMessage
     *
     * @return bool|false|int
     * @throws Exception
     */
    public function updateSubmitEntryWithSuccess($popupFormSubmit, $lastMessageTitle, $lastMessage)
    {
        if (is_null($popupFormSubmit)) {
            return false;
        }

        return $this->db->update_popup_form_submit_by_hash(
            $popupFormSubmit->hash,
            array(
                'status' => self::POPUP_FORM_SUBMIT_STATUS_SUCCESS,
                'lastMessageTitle' => $lastMessageTitle,
                'lastMessage' => $lastMessage
            )
        );
    }

    /**
     * @param $popupFormSubmit
     * @param null $lastMessageTitle
     * @param null $lastMessage
     *
     * @return bool|false|int
     * @throws Exception
     */
    public function updateSubmitEntryWithFailed($popupFormSubmit, $lastMessageTitle = null, $lastMessage = null)
    {
        if (is_null($popupFormSubmit)) {
            return false;
        }

        if (is_null($lastMessageTitle)) {
            // It's an internal message, we don't localize it
            $lastMessageTitle = 'Failed';
        }
        if (is_null($lastMessage)) {
            // It's an internal message, we don't localize it
            $lastMessage = 'Payment failed!';
        }

        return $this->db->update_popup_form_submit_by_hash(
            $popupFormSubmit->hash,
            array(
                'status' => self::POPUP_FORM_SUBMIT_STATUS_FAILED,
                'lastMessageTitle' => $lastMessageTitle,
                'lastMessage' => $lastMessage
            )
        );
    }

    /**
     * @param $popupFormSubmit
     * @param null $errorMessage
     *
     * @return bool|false|int
     * @throws Exception
     */
    public function updateSubmitEntryWithErrorCount($popupFormSubmit, $errorMessage = null)
    {
        if (is_null($popupFormSubmit)) {
            return false;
        }

        return $this->db->update_popup_form_submit_by_hash(
            $popupFormSubmit->hash,
            array(
                'processedWithError' => $popupFormSubmit->processedWithError + 1,
                'errorMessage' => $errorMessage
            )
        );
    }

    /**
     * @param $popupFormSubmit
     *
     * @return false|int
     * @throws Exception
     */
    public function updateSubmitEntryWithCancelled($popupFormSubmit)
    {
        if (is_null($popupFormSubmit)) {
            return false;
        }

        return $this->db->update_popup_form_submit_by_hash(
            $popupFormSubmit->hash,
            array(
                'status' => self::CHECKOUT_SESSION_STATUS_CANCELLED,
                'lastMessageTitle' =>
                    /* translators: Banner title of cancelled transaction */
                    __('Cancelled', 'wp-full-stripe'),
                'lastMessage' =>
                    /* translators: Banner message of cancelled transaction */
                    __('The customer has cancelled the payment.', 'wp-full-stripe')
            )
        );
    }

    /**
     * @param $popupFormSubmit
     *
     * @return bool|false|int
     * @throws Exception
     */
    public function updateSubmitEntryWithComplete($popupFormSubmit)
    {
        if (is_null($popupFormSubmit)) {
            return false;
        }

        return $this->db->update_popup_form_submit_by_hash(
            $popupFormSubmit->hash,
            array(
                'status' => self::POPUP_FORM_SUBMIT_STATUS_COMPLETE
            )
        );
    }

    /**
     * @param $popupFormSubmitIdsToDelete
     *
     * @return int
     */
    private function deleteSubmitEntriesById($popupFormSubmitIdsToDelete)
    {
        $deleted = 0;
        if (
            isset($popupFormSubmitIdsToDelete)
            && is_array($popupFormSubmitIdsToDelete)
            && sizeof($popupFormSubmitIdsToDelete) > 0
        ) {
            $deleted = $this->db->delete_popup_form_submits_by_id($popupFormSubmitIdsToDelete);
        }

        return $deleted;
    }

    /**
     * @param $popupFormSubmitIdsToInternalError
     *
     * @return int
     * @throws Exception
     */
    private function updateSubmitEntriesWithInternalError($popupFormSubmitIdsToInternalError)
    {
        $updated = 0;
        if (
            isset($popupFormSubmitIdsToInternalError)
            && is_array($popupFormSubmitIdsToInternalError)
            && sizeof($popupFormSubmitIdsToInternalError) > 0
        ) {
            $updated = $this->db->update_popup_form_submits_with_status_by_id(
                MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_INTERNAL_ERROR,
                $popupFormSubmitIdsToInternalError
            );
        }

        return $updated;
    }

    private function stop()
    {
        self::$running = false;
    }

    /**
     * @param $checkoutSession
     *
     * @return \StripeWPFS\SetupIntent|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function findSetupIntentInCheckoutSession($checkoutSession)
    {
        $setupIntent = null;
        if (isset($checkoutSession)) {
            $setupIntent = $this->retrieveStripeSetupIntentByCheckoutSession($checkoutSession);
            if (is_null($setupIntent)) {
                $stripeSubscription = $this->retrieveStripeSubscriptionByCheckoutSession($checkoutSession);
                $setupIntent = $this->findSetupIntentInSubscription($stripeSubscription);
            }
        }

        return $setupIntent;
    }

    /**
     * @param $checkoutSession
     *
     * @return \StripeWPFS\SetupIntent|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function retrieveStripeSetupIntentByCheckoutSession($checkoutSession)
    {
        $stripeSetupIntent = null;
        if (isset($checkoutSession)) {
            if (isset($checkoutSession->setup_intent)) {
                if ($checkoutSession->setup_intent->id) {
                    $stripeSetupIntent = $checkoutSession->setup_intent;
                } else {
                    $stripeSetupIntent = $this->stripe->retrieveSetupIntent($checkoutSession->setup_intent);
                }
            }
        }

        return $stripeSetupIntent;
    }

    /**
     * @param $stripeSubscription
     *
     * @return \StripeWPFS\SetupIntent|null
     */
    public function findSetupIntentInSubscription($stripeSubscription)
    {
        $setupIntent = null;
        if (isset($stripeSubscription)) {
            if (isset($stripeSubscription->pending_setup_intent)) {
                if ($stripeSubscription->pending_setup_intent->id) {
                    $setupIntent = $stripeSubscription->pending_setup_intent;
                } else {
                    $setupIntent = $this->stripe->retrieveSetupIntent($stripeSubscription->pending_setup_intent);
                }
            }
        }

        return $setupIntent;
    }

    /**
     * @param \StripeWPFS\Checkout\Session $checkoutSession
     *
     * @return \StripeWPFS\Customer
     */
    public function retrieveStripeCustomerByCheckoutSession($checkoutSession)
    {
        $stripeCustomer = null;
        if (isset($checkoutSession)) {
            if (isset($checkoutSession->customer)) {
                $customer = $checkoutSession->customer;
                if ($customer->id) {
                    $stripeCustomer = $customer;
                } else {
                    $stripeCustomer = $this->stripe->retrieveCustomer($checkoutSession->customer);
                }
            }
        }

        return $stripeCustomer;
    }

    /**
     * @param $setupIntent
     *
     * @return string|\StripeWPFS\PaymentMethod|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function retrieveStripePaymentMethodBySetupIntent($setupIntent)
    {
        $stripePaymentMethod = null;
        if (isset($setupIntent)) {
            if (isset($setupIntent->payment_method)) {
                if ($setupIntent->payment_method->id) {
                    $stripePaymentMethod = $setupIntent->payment_method;
                } else {
                    $stripePaymentMethod = $this->stripe->retrievePaymentMethod($setupIntent->payment_method);
                }
            }
        }

        return $stripePaymentMethod;
    }

    /**
     * @param $paymentIntent
     *
     * @return string|\StripeWPFS\PaymentMethod|null
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public function retrieveStripePaymentMethodByPaymentIntent($paymentIntent)
    {
        $stripePaymentMethod = null;
        if (isset($paymentIntent)) {
            if (isset($paymentIntent->payment_method)) {
                $paymentMethod = $paymentIntent->payment_method;
                if (isset( $paymentMethod ) && isset($paymentMethod->id)) {
                    $stripePaymentMethod = $paymentMethod;
                } else {
                    $stripePaymentMethod = $this->stripe->retrievePaymentMethod($paymentIntent->payment_method);
                }
            }
        }

        return $stripePaymentMethod;
    }

    /**
     * @param \StripeWPFS\PaymentMethod $paymentMethod
     *
     * @return null|\StripeWPFS\Customer
     */
    public function retrieveStripeCustomerByPaymentMethod($paymentMethod)
    {
        $stripeCustomer = null;
        if (isset($paymentMethod)) {
            if (isset($paymentMethod->customer)) {
                if ($paymentMethod->customer->id) {
                    $stripeCustomer = $paymentMethod->customer;
                } else {
                    $stripeCustomer = $this->stripe->retrieveCustomer($paymentMethod->customer);
                }
            }
        }

        return $stripeCustomer;
    }

}

abstract class MM_WPFS_CheckoutSessionBuilder
{
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;
    protected $submitHash;
    /** @var MM_WPFS_Public_FormModel */
    protected $formModel;
    /** @var MM_WPFS_Options */
    protected $options;
    /** @var MM_WPFS_Stripe */
    protected $stripe;
    /** @var MM_WPFS_FormFieldConfiguration[] $fieldConfiguration */
    protected $fieldConfiguration;

    public function __construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService)
    {
        $this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_CHECKOUT_SUBMISSION);
        $this->stripe = $stripe;
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        $this->submitHash = $submitHash;
        $this->formModel = $formModel;
        $this->fieldConfiguration = $fieldConfiguration;
    }

    /**
     * @param $submitHash
     *
     * @return string
     */
    protected function buildCheckoutSessionSuccessURL($submitHash)
    {
        return $this->buildCheckoutSessionStatusURL($submitHash, MM_WPFS_CheckoutSubmissionService::CHECKOUT_SESSION_STATUS_SUCCESS);
    }

    /**
     * @param $submitHash
     *
     * @return string
     */
    protected function buildCheckoutSessionCancelURL($submitHash)
    {
        return $this->buildCheckoutSessionStatusURL($submitHash, MM_WPFS_CheckoutSubmissionService::CHECKOUT_SESSION_STATUS_CANCELLED);
    }


    /**
     * @param $submitHash
     * @param $status
     *
     * @return string
     */
    private function buildCheckoutSessionStatusURL($submitHash, $status)
    {
        return add_query_arg(
            array(
                'action' => 'wp_full_stripe_handle_checkout_session',
                MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH => $submitHash,
                MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_STATUS => $status
            ),
            admin_url('admin-ajax.php')
        );
    }

    /**
     * @param $formModel MM_WPFS_Public_FormModel
     * @return array
     */
    protected function prepareCountryFilterParams()
    {
        return [
            'formName' => $this->formModel->getForm()->name,
            'formType' => MM_WPFS_Utils::getFormType($this->formModel->getForm()),
        ];
    }

    /**
     * @param $formModel
     * @return string[]
     */
    private function getShippingCountryCodes()
    {
        $countryCodes = MM_WPFS_Countries::getAvailableCheckoutCountryCodes();

        try {
            $countryCodes = apply_filters(
                'fullstripe_shipping_countries',
                $countryCodes,
                $this->prepareCountryFilterParams()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Error while filtering shipping countries', $ex);
        }

        return $countryCodes;
    }

    protected function updateLineItemWithDescription(&$lineItem)
    {
        if (isset($this->formModel->getForm()->companyName) && !empty($this->formModel->getForm()->companyName)) {
            $lineItem['description'] = $this->formModel->getForm()->companyName;
        }
    }

    public function build()
    {
        $collectBillingAddress = isset($this->formModel->getForm()->showBillingAddress) && 1 == $this->formModel->getForm()->showBillingAddress ? 'required' : 'auto';

        $sessionData = [
            'client_reference_id' => $this->submitHash,
            'billing_address_collection' => $collectBillingAddress,
            'success_url' => $this->buildCheckoutSessionSuccessURL($this->submitHash),
            'cancel_url' => $this->buildCheckoutSessionCancelURL($this->submitHash),
        ];

        if ($this->formModel->getForm()->showShippingAddress) {
            $sessionData['shipping_address_collection'] = array(
                'allowed_countries' => $this->getShippingCountryCodes()
            );
        }

        $customerIdField = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_CUSTOMER_ID];
        $isCustomerIdConfigurable = $customerIdField->isConfigurable() && !is_null($customerIdField->getValue());
        // $emailField = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_EMAIL];
        // $isEmailConfigurable = $emailField->isConfigurable() && !is_null($emailField->getValue());

        if ($isCustomerIdConfigurable) {
            $sessionData['customer'] = $customerIdField->getValue();
        // disabling in the hope that the email can now be set by the customer
        // } else if ($isEmailConfigurable) {
        //     $sessionData['customer_email'] = $emailField->getValue();
        }

        if (isset($this->formModel->getForm()->preferredLanguage)) {
            $sessionData['locale'] = $this->formModel->getForm()->preferredLanguage;
        }

        return $sessionData;
    }
}

class MM_WPFS_CheckoutSessionBuilder_SaveCard extends MM_WPFS_CheckoutSessionBuilder
{
    public function __construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService)
    {
        parent::__construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService);
    }

    public function build()
    {
        $sessionData = parent::build();

        $sessionData['mode'] = 'setup';
        $sessionData['customer_creation'] = 'always'; // always capture a customer. we need it post payment for internal logic

        return $sessionData;
    }
}

trait MM_WPFS_CheckoutSessionBuilder_PhoneNumber_AddOn
{
    protected function buildPhoneNumber(&$sessionData)
    {
        if (
            !is_null($this->formModel->getForm()->collectPhoneNumber) &&
            1 == $this->formModel->getForm()->collectPhoneNumber
        ) {
            $sessionData['phone_number_collection'] = [
                'enabled' => true,
            ];
        }
    }
}

class MM_WPFS_CheckoutSessionBuilder_Donation extends MM_WPFS_CheckoutSessionBuilder
{
    use MM_WPFS_CheckoutSessionBuilder_PhoneNumber_AddOn;

    public function __construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService)
    {
        parent::__construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService);
    }

    /**
     * @return string
     * @throws Exception
     */
    protected function getProductLabel(): string
    {
        return $this->formModel->getProductName() . ' (' . MM_WPFS_Localization::getDonationFrequencyLabel($this->formModel->getDonationFrequency()) . ')';
    }

    public function build()
    {
        $sessionData = parent::build();

        $this->buildPhoneNumber($sessionData);

        $sessionData['mode'] = 'payment';
        $sessionData['payment_intent_data'] = array(
            'setup_future_usage' => 'off_session'
        );
        $sessionData['customer_creation'] = 'always'; // always capture a customer. we need it post payment for internal logic

        $productData = array(
            'name' => $this->getProductLabel()
        );
        if (isset($this->formModel->getForm()->companyName) && !empty($this->formModel->getForm()->companyName)) {
            $productData['description'] = $this->formModel->getForm()->companyName;
        }

        if (!empty($this->formModel->getForm()->image)) {
            $productData['images'] = [
                $this->formModel->getForm()->image
            ];
        }

        $lineItem = array(
            'price_data' => array(
                'currency' => $this->formModel->getForm()->currency,
                'product_data' => $productData,
                'unit_amount' => $this->formModel->getAmount(),
            ),
            'quantity' => 1,
        );

        $sessionData['line_items'] = array($lineItem);

        return $sessionData;
    }
}


abstract class MM_WPFS_CheckoutSessionBuilder_Product extends MM_WPFS_CheckoutSessionBuilder
{
    use MM_WPFS_CheckoutSessionBuilder_PhoneNumber_AddOn;

    public function __construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService)
    {
        parent::__construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService);
    }

    public function build()
    {
        $sessionData = parent::build();

        $this->buildPhoneNumber($sessionData);

        if ($this->formModel->getForm()->showCouponInput == '1') {
            $couponField = $this->fieldConfiguration[MM_WPFS_ConfigurableFormFields::FIELD_COUPON];
            $isCouponConfigurable = $couponField->isConfigurable() && !is_null($couponField->getValue());
            $coupon = null;

            if ($isCouponConfigurable) {
                try {
                    $coupon = $this->stripe->retrievePromotionalCode($couponField->getValue());
                } catch (Exception $ex) {
                    // Just let it fall through
                }
            }

            if (!$isCouponConfigurable || is_null($coupon)) {
                $sessionData['allow_promotion_codes'] = 'true';
            } else {
                $sessionData['discounts'] = [
                    [
                        'promotion_code' => $coupon->id
                    ]
                ];
            }
        }

        $taxRateType = $this->formModel->getForm()->vatRateType;
        if ($taxRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX) {
            $sessionData['automatic_tax'] = [
                'enabled' => true,
            ];
        }
        if (
            $taxRateType !== MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX &&
            1 == $this->formModel->getForm()->collectCustomerTaxId
        ) {
            $sessionData['tax_id_collection'] = [
                'enabled' => true,
            ];
        }

        return $sessionData;
    }

    protected function updateLineItemWithTax(&$lineItem)
    {
        $taxRateType = $this->formModel->getForm()->vatRateType;
        switch ($taxRateType) {
            case MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED:
            case MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC:
                $taxRates = MM_WPFS_Pricing::extractTaxRateIdsStatic(json_decode($this->formModel->getForm()->vatRates));
                $index = $taxRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED ? 'tax_rates' : 'dynamic_tax_rates';

                $lineItem[$index] = $taxRates;
                break;
        }
    }
}

class MM_WPFS_CheckoutSessionBuilder_OneTimePayment extends MM_WPFS_CheckoutSessionBuilder_Product
{
    public function __construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService)
    {
        parent::__construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService);
    }

    public function build()
    {
        $sessionData = parent::build();

        $sessionData['mode'] = 'payment';
        $sessionData['payment_intent_data'] = array(
            'setup_future_usage' => 'off_session'
        );
        $sessionData['customer_creation'] = 'always'; // always capture a customer. we need it post payment for internal logic

        $lineItem = [];
        if (!is_null($this->formModel->getPriceId())) {
            $lineItem = array(
                'price' => $this->formModel->getPriceId(),
                'quantity' => 1
            );
        } else {
            $lineItem = array(
                'price_data' => array(
                    'currency' => $this->formModel->getForm()->currency,
                    'product_data' => array(
                        'name' => $this->formModel->getProductName()
                    ),
                    'unit_amount' => $this->formModel->getAmount(),
                ),
                'quantity' => 1
            );

            if (isset($this->formModel->getForm()->companyName) && !empty($this->formModel->getForm()->companyName)) {
                $lineItem['price_data']['product_data']['description'] = $this->formModel->getForm()->companyName;
            }

            $imagesValueArray = $this->prepareImagesValueArray($this->formModel->getForm());
            if (count($imagesValueArray) > 0) {
                $lineItem['images'] = $imagesValueArray;
            }
        }

        $this->updateLineItemWithTax($lineItem);
        $sessionData['line_items'] = array($lineItem);

        return $sessionData;
    }

    private function prepareImagesValueArray($checkoutForm)
    {
        $imagesValue = array();
        if (empty($checkoutForm->image)) {
            // $imagesValue = [ self::DEFAULT_CHECKOUT_LINE_ITEM_IMAGE ];
        } else {
            array_push($imagesValue, $checkoutForm->image);
        }

        return $imagesValue;
    }
}

class MM_WPFS_CheckoutSessionBuilder_Subscription extends MM_WPFS_CheckoutSessionBuilder_Product
{
    public function __construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService)
    {
        parent::__construct($submitHash, $formModel, $fieldConfiguration, $stripe, $loggerService);
    }

    public function build()
    {
        $sessionData = parent::build();
        if ( isset($sessionData['customer_creation']) && !empty($sessionData['customer_creation'])) {
            unset($sessionData['customer_creation']);
            // customer creation is implicit for subscriptions and wil cause an error if included
        }

        $subscriptionData = [];
        $lineItems = array();

        $setupFee = $this->formModel->getSetupFee();
        $trialDays = $this->formModel->getTrialPeriodDays();

        if ($trialDays > 0) {
            $subscriptionData['trial_period_days'] = $trialDays;
        }

        if ($setupFee > 0) {
            $setupFeeLineItem = array(
                'price_data' => array(
                    'currency' => $this->formModel->getStripePlan()->currency,
                    'product_data' => array(
                        'name' => sprintf(
                            /* translators: It's a line item for the initial payment of a subscription  */
                            __('One-time setup fee (plan: %s)', 'wp-full-stripe'),
                            MM_WPFS_Localization::translateLabel($this->formModel->getStripePlan()->product->name)
                        ),
                        'metadata' => [
                            'type' => 'setupFee'
                        ],
                    ),
                    'unit_amount' => $setupFee
                ),
                'description' => sprintf(
                    // It's an internal description, no need to localize it
                    'Subscription plan: %s, quantity: %d',
                    $this->formModel->getStripePlan()->id,
                    $this->formModel->getStripePlanQuantity()
                ),
                'quantity' => $this->formModel->getStripePlanQuantity()
            );

            $this->updateLineItemWithTax($setupFeeLineItem);
            array_push($lineItems, $setupFeeLineItem);
        }

        $planLineItem = array(
            'price' => $this->formModel->getStripePlan()->id,
            'quantity' => $this->formModel->getStripePlanQuantity()
        );
        $this->updateLineItemWithTax($planLineItem);
        array_push($lineItems, $planLineItem);

        $sessionData = array_merge($sessionData, [
            'mode' => 'subscription',
            'line_items' => $lineItems,
            'subscription_data' => $subscriptionData,
        ]);

        return $sessionData;
    }
}
