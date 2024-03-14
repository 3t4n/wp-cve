<?php

/**
 * Class MM_WPFS_Admin deals with admin back-end input i.e. create plans, transfers
 */
class MM_WPFS_Admin
{
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    const HTTPS_DASHBOARD_STRIPE_COM = "https://dashboard.stripe.com/";
    const PATH_TEST = "test/";
    const PATH_CUSTOMERS = 'customers/';
    const PATH_CHARGES = 'charges/';
    const PATH_PAYMENTS = 'payments/';
    const PATH_SUBSCRIPTIONS = 'subscriptions/';
    const PATH_PRODUCTS = 'products/';

    /** @var MM_WPFS_Stripe */
    private $stripe = null;

    /** @var MM_WPFS_Database */
    private $db = null;

    /** @var MM_WPFS_Mailer */
    private $mailer = null;

    /** @var $eventHandler MM_WPFS_EventHandler */
    private $eventHandler = null;

    /** @var MM_WPFS_Options  */
    private $options = null;

    public function __construct($loggerService)
    {
        $this->initLogger($loggerService, MM_WPFS_LoggerService::MODULE_ADMIN);
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        $this->stripe = new MM_WPFS_Stripe(MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService);
        $this->db = new MM_WPFS_Database();
        $this->mailer = new MM_WPFS_Mailer($this->loggerService);
        $this->eventHandler = new MM_WPFS_EventHandler(
            $this->db,
            $this->mailer,
            $this->loggerService
        );

        $this->hooks();
    }

    private function hooks()
    {
        // actions for forms
        add_action('wp_ajax_wpfs-create-form', array($this, 'createForm'));
        add_action('wp_ajax_wpfs-delete-form', array($this, 'deleteForm'));
        add_action('wp_ajax_wpfs-clone-form', array($this, 'cloneForm'));

        // actions for subscription forms
        add_action('wp_ajax_wpfs-save-inline-subscription-form', array($this, 'saveInlineSubscriptionForm'));
        add_action('wp_ajax_wpfs-save-checkout-subscription-form', array($this, 'saveCheckoutSubscriptionForm'));

        // actions for payment forms
        add_action('wp_ajax_wpfs-save-inline-payment-form', array($this, 'saveInlinePaymentForm'));
        add_action('wp_ajax_wpfs-save-checkout-payment-form', array($this, 'saveCheckoutPaymentForm'));

        // actions for save card forms
        add_action('wp_ajax_wpfs-save-inline-save-card-form', array($this, 'saveInlineSaveCardForm'));
        add_action('wp_ajax_wpfs-save-checkout-save-card-form', array($this, 'saveCheckoutSaveCardForm'));

        // actions for donation forms
        add_action('wp_ajax_wpfs-save-inline-donation-form', array($this, 'saveInlineDonationForm'));
        add_action('wp_ajax_wpfs-save-checkout-donation-form', array($this, 'saveCheckoutDonationForm'));

        // actions for payments
        add_action('wp_ajax_wpfs-delete-payment', array($this, 'deletePayment'));
        add_action('wp_ajax_wpfs-capture-payment', array($this, 'capturePayment'));
        add_action('wp_ajax_wpfs-refund-payment', array($this, 'refundPayment'));
        add_action('wp_ajax_wpfs-get-payment-details', array($this, 'getPaymentDetails'));

        // actions for subscriptions
        add_action('wp_ajax_wpfs-cancel-subscription', array($this, 'cancelSubscription'));
        add_action('wp_ajax_wpfs-delete-subscription', array($this, 'deleteSubscription'));
        add_action('wp_ajax_wpfs-get-subscription-details', array($this, 'getSubscriptionDetails'));

        // actions for donations
        add_action('wp_ajax_wpfs-refund-donation', array($this, 'refundDonation'));
        add_action('wp_ajax_wpfs-cancel-donation', array($this, 'cancelDonation'));
        add_action('wp_ajax_wpfs-delete-donation', array($this, 'deleteDonation'));
        add_action('wp_ajax_wpfs-get-donation-details', array($this, 'getDonationDetails'));

        // actions for saved cards
        add_action('wp_ajax_wpfs-delete-saved-card', array($this, 'deleteSavedCard'));
        add_action('wp_ajax_wpfs-get-saved-card-details', array($this, 'getSavedCardDetails'));

        // actions for settings pages
        add_action('wp_ajax_wpfs-save-stripe-account', array($this, 'saveStripeAccount'));
        add_action('wp_ajax_wpfs-create-stripe-connect-account', array($this, 'createStripeConnectAccount'));
        add_action('wp_ajax_wpfs-add-stripe-account', array($this, 'addStripeAccount'));
        add_action('wp_ajax_wpfs-save-my-account', array($this, 'saveMyAccount'));
        add_action('wp_ajax_wpfs-save-security', array($this, 'saveSecurity'));
        add_action('wp_ajax_wpfs-save-email-options', array($this, 'saveEmailOptions'));
        add_action('wp_ajax_wpfs-save-email-templates', array($this, 'saveEmailTemplates'));
        add_action('wp_ajax_wpfs-save-forms-options', array($this, 'saveFormsOptions'));
        add_action('wp_ajax_wpfs-save-forms-appearance', array($this, 'saveFormsAppearance'));
        add_action('wp_ajax_wpfs-save-wp-dashboard', array($this, 'saveWordpressDashboard'));
        add_action('wp_ajax_wpfs-save-logs', array($this, 'saveLogs'));
        add_action('wp_ajax_wpfs-empty-logs', array($this, 'emptyLogs'));

        // actions for in-form ajax requests
        add_action('wp_ajax_wpfs-get-onetime-products', array($this, 'getOnetimeProducts'));
        add_action('wp_ajax_wpfs-get-recurring-products', array($this, 'getRecurringProducts'));
        add_action('wp_ajax_wpfs-get-tax-rates', array($this, 'getTaxRates'));
        add_action('wp_ajax_wpfs-send-test-email', array($this, 'sendTestEmail'));

        // handle stripe webhook events
        add_action('admin_post_nopriv_handle_wpfs_event', array(
            $this,
            'fullstripe_handle_wpfs_event'
        ));

        add_action('admin_init', array($this->loggerService, 'downloadLog'));
    }

    /**
     * @param $paymentStatus
     *
     * @return string|void
     */
    public static function getPaymentStatusLabel($paymentStatus)
    {
        if (MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $paymentStatus) {
            $label =
                /* translators: The 'Authorized' payment status */
                __('Authorized', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::PAYMENT_STATUS_PAID === $paymentStatus) {
            $label =
                /* translators: The 'Paid' payment status */
                __('Paid', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::PAYMENT_STATUS_EXPIRED === $paymentStatus) {
            $label =
                /* translators: The 'Expired' payment status */
                __('Expired', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::PAYMENT_STATUS_RELEASED === $paymentStatus) {
            $label =
                /* translators: The 'Released' payment status */
                __('Released', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::PAYMENT_STATUS_REFUNDED === $paymentStatus) {
            $label =
                /* translators: The 'Refunded' payment status */
                __('Refunded', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::PAYMENT_STATUS_FAILED === $paymentStatus) {
            $label =
                /* translators: The 'Failed' payment status */
                __('Failed', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::PAYMENT_STATUS_PENDING === $paymentStatus) {
            $label =
                /* translators: The 'Pending' payment status */
                __('Pending', 'wp-full-stripe-admin');
        } else {
            $label =
                /* translators: The 'Unknown' payment status */
                __('Unknown', 'wp-full-stripe-admin');
        }

        return $label;
    }

    /**
     * @param $subscriptionStatus
     *
     * @return string
     */
    public static function getSubscriberStatusLabel($subscriptionStatus): string
    {
        if (MM_WPFS::SUBSCRIBER_STATUS_RUNNING === $subscriptionStatus) {
            $label =
                /* translators: The 'Running' subscription status */
                __('Running', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::SUBSCRIBER_STATUS_INCOMPLETE === $subscriptionStatus) {
            $label =
                /* translators: The 'Incomplete' subscription status */
                __('Incomplete', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::SUBSCRIBER_STATUS_CANCELLED === $subscriptionStatus) {
            $label =
                /* translators: The 'Canceled' subscription status */
                __('Canceled', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::SUBSCRIBER_STATUS_ENDED === $subscriptionStatus) {
            $label =
                /* translators: The 'Ended' subscription status */
                __('Ended', 'wp-full-stripe-admin');
        } else {
            $label =
                /* translators: The 'Unknown' subscription status */
                __('Unknown', 'wp-full-stripe-admin');
        }

        return $label;
    }

    /**
     * @param $subscriptionForm
     *
     * @return string
     */
    public static function getSubscriberStatusLabelByForm($subscriptionForm)
    {
        $statusLabel = MM_WPFS_Admin::getSubscriberStatusLabel($subscriptionForm->status);
        if ($subscriptionForm->chargeMaximumCount > 0) {
            $statusLabel = sprintf('%1$s (%2$d/%3$d)', $statusLabel, $subscriptionForm->chargeCurrentCount, $subscriptionForm->chargeMaximumCount);
        }

        return $statusLabel;
    }

    public static function getDonationStatusLabel($donationStatus): string
    {
        if (MM_WPFS::DONATION_STATUS_RUNNING === $donationStatus) {
            $label =
                /* translators: The 'Running' donation status */
                __('Running', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::DONATION_STATUS_PAID === $donationStatus) {
            $label =
                /* translators: The 'Paid' donation status */
                __('Paid', 'wp-full-stripe-admin');
        } elseif (MM_WPFS::DONATION_STATUS_REFUNDED === $donationStatus) {
            $label =
                /* translators: The 'Refunded' donation status */
                __('Refunded', 'wp-full-stripe-admin');
        } else {
            $label =
                /* translators: The 'Unknown' donation status */
                __('Unknown', 'wp-full-stripe-admin');
        }

        return $label;
    }

    /**
     * @param $liveMode int
     *
     * @return string
     */
    public static function getApiModeLabelFromInteger($liveMode): string
    {
        return $liveMode == 1 ?
            /* translators: The 'Live' API mode status */
            __('Live', 'wp-full-stripe-admin') :
            /* translators: The 'Test' API mode status */
            __('Test', 'wp-full-stripe-admin');
    }

    /**
     * @param $apiMode string
     *
     * @return string
     */
    public static function getApiModeLabelFromString($apiMode): string
    {
        return $apiMode === MM_WPFS::STRIPE_API_MODE_LIVE ?
            /* translators: The 'Live' API mode status */
            __('Live', 'wp-full-stripe-admin') :
            /* translators: The 'Test' API mode status */
            __('Test', 'wp-full-stripe-admin');
    }

    /**
     * @param $apiMode
     *
     * @return int
     */
    public static function getApiModeIntegerFromString($apiMode): int
    {
        return $apiMode === MM_WPFS::STRIPE_API_MODE_LIVE ? 1 : 0;
    }

    /**
     * @param $interval
     * @param $intervalCount
     *
     * @return string|void
     */
    public static function formatIntervalLabelAdmin($interval, $intervalCount)
    {
        $intervalLabel = __('No interval', 'wp-full-stripe-admin');

        if ($interval === "year") {
            $intervalLabel = sprintf(
                /* translators: Singular and plural annual interval label
                 * p1: interval count
                 */
                _n('year', '%d years', $intervalCount, 'wp-full-stripe-admin'),
                number_format_i18n($intervalCount)
            );
        } elseif ($interval === "month") {
            $intervalLabel = sprintf(
                /* translators: Singular and plural monthly interval label
                 * p1: interval count
                 */
                _n('month', '%d months', $intervalCount, 'wp-full-stripe-admin'),
                number_format_i18n($intervalCount)
            );
        } elseif ($interval === "week") {
            $intervalLabel = sprintf(
                /* translators: Singular and plural weekly interval label
                 * p1: interval count
                 */
                _n('week', '%d weeks', $intervalCount, 'wp-full-stripe-admin'),
                number_format_i18n($intervalCount)
            );
        } elseif ($interval === "day") {
            $intervalLabel = sprintf(
                /* translators: Singular and plural daily interval label
                 * p1: interval count
                 */
                _n('day', '%d days', $intervalCount, 'wp-full-stripe-admin'),
                number_format_i18n($intervalCount)
            );
        }

        return $intervalLabel;
    }

    public static function translateLabelAdmin($label)
    {
        return MM_WPFS_Localization::translateLabel($label, 'wp-full-stripe-admin');
    }

    /**
     * @param $formType string
     * @param $emailTemplates string
     * @param $model MM_WPFS_Admin_FormModel
     */
    protected function updateFormEmailTemplates($formType, $emailTemplatesJson, &$model)
    {
        $emailTemplates = MM_WPFS_Mailer::extractEmailTemplates($this->staticContext, $formType, $emailTemplatesJson);

        foreach ($model->getEmailTemplatesHidden() as $srcTemplate) {
            if (property_exists($emailTemplates, $srcTemplate->type)) {
                $emailTemplates->{$srcTemplate->type}->enabled = $srcTemplate->enabled;
            }
        }

        $model->setEmailTemplates(json_encode($emailTemplates));
    }

    /**
     * @param $model MM_WPFS_Admin_InlineSaveCardFormModel
     */
    protected function updateInlineSaveCardFormEmailTemplates(&$model)
    {
        $form = $this->db->getInlinePaymentFormById($model->getId());

        $this->updateFormEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD, $form->emailTemplates, $model);
    }

    /**
     * @param $model MM_WPFS_Admin_CheckoutSaveCardFormModel
     */
    protected function updateCheckoutSaveCardFormEmailTemplates(&$model)
    {
        $form = $this->db->getCheckoutPaymentFormById($model->getId());

        $this->updateFormEmailTemplates(MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD, $form->emailTemplates, $model);
    }

    function saveInlineSaveCardForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {

                $inlineSaveCardFormModel = new MM_WPFS_Admin_InlineSaveCardFormModel($this->loggerService);
                $bindingResult = $inlineSaveCardFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->updateInlineSaveCardFormEmailTemplates($inlineSaveCardFormModel);

                    $this->db->updateInlinePaymentForm($inlineSaveCardFormModel->getId(), $inlineSaveCardFormModel->getData());
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a save card form is saved */
                            __('Save card form saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a save card form is saved */
                        __('There was an error saving the save card form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a save card form is saved */
                    __('Save card form saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $model MM_WPFS_Admin_InlinePaymentFormModel
     */
    protected function updateInlinePaymentFormEmailTemplates(&$model)
    {
        $form = $this->db->getInlinePaymentFormById($model->getId());

        $this->updateFormEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_PAYMENT, $form->emailTemplates, $model);
    }

    function saveInlinePaymentForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {

                $inlinePaymentFormModel = new MM_WPFS_Admin_InlinePaymentFormModel($this->loggerService);
                $bindingResult = $inlinePaymentFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->updateInlinePaymentFormEmailTemplates($inlinePaymentFormModel);

                    $this->db->updateInlinePaymentForm($inlinePaymentFormModel->getId(), $inlinePaymentFormModel->getData());
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a payment form is saved */
                            __('Payment form saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a payment form is saved */
                        __('There was an error saving the payment form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a payment form is saved */
                    __('Payment form saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $model MM_WPFS_Admin_CheckoutPaymentFormModel
     */
    protected function updateCheckoutPaymentFormEmailTemplates(&$model)
    {
        $form = $this->db->getCheckoutPaymentFormById($model->getId());

        $this->updateFormEmailTemplates(MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT, $form->emailTemplates, $model);
    }

    function saveCheckoutPaymentForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {

                $checkoutPaymentFormModel = new MM_WPFS_Admin_CheckoutPaymentFormModel($this->loggerService);
                $bindingResult = $checkoutPaymentFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->updateCheckoutPaymentFormEmailTemplates($checkoutPaymentFormModel);

                    $this->db->updateCheckoutPaymentForm($checkoutPaymentFormModel->getId(), $checkoutPaymentFormModel->getData());
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a payment form is saved */
                            __('Payment form saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a payment form is saved */
                        __('There was an error saving the payment form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a payment form is saved */
                    __('Payment form saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $model MM_WPFS_Admin_InlineSubscriptionFormModel
     */
    protected function updateInlineSubscriptionFormEmailTemplates(&$model)
    {
        $form = $this->db->getInlineSubscriptionFormById($model->getId());

        $this->updateFormEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION, $form->emailTemplates, $model);
    }

    function saveInlineSubscriptionForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {

                $inlineSubscriptionFormModel = new MM_WPFS_Admin_InlineSubscriptionFormModel($this->loggerService);
                $bindingResult = $inlineSubscriptionFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->updateInlineSubscriptionFormEmailTemplates($inlineSubscriptionFormModel);

                    $this->db->updateInlineSubscriptionForm($inlineSubscriptionFormModel->getId(), $inlineSubscriptionFormModel->getData());
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a subscription form is saved */
                            __('Subscription form saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a subscription form is saved */
                        __('There was an error saving the subscription form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a subscription form is saved */
                    __('Subscription form saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $model MM_WPFS_Admin_CheckoutSubscriptionFormModel
     */
    protected function updateCheckoutSubscriptionFormEmailTemplates(&$model)
    {
        $form = $this->db->getCheckoutSubscriptionFormById($model->getId());

        $this->updateFormEmailTemplates(MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION, $form->emailTemplates, $model);
    }

    function saveCheckoutSubscriptionForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {

                $checkoutSubscriptionFormModel = new MM_WPFS_Admin_CheckoutSubscriptionFormModel($this->loggerService);
                $bindingResult = $checkoutSubscriptionFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->updateCheckoutSubscriptionFormEmailTemplates($checkoutSubscriptionFormModel);

                    $this->db->updateCheckoutSubscriptionForm($checkoutSubscriptionFormModel->getId(), $checkoutSubscriptionFormModel->getData());
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a subscription form is saved */
                            __('Subscription form saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a subscription form is saved */
                        __('There was an error saving the subscription form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a subscription form is saved */
                    __('Subscription form saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $model MM_WPFS_Admin_InlineDonationFormModel
     */
    protected function updateInlineDonationFormEmailTemplates(&$model)
    {
        $form = $this->db->getInlineDonationFormById($model->getId());

        $this->updateFormEmailTemplates(MM_WPFS::FORM_TYPE_INLINE_DONATION, $form->emailTemplates, $model);
    }

    function saveInlineDonationForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $inlineDonationFormModel = new MM_WPFS_Admin_InlineDonationFormModel($this->loggerService);
                $bindingResult = $inlineDonationFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->updateInlineDonationFormEmailTemplates($inlineDonationFormModel);

                    $this->db->updateInlineDonationForm($inlineDonationFormModel->getId(), $inlineDonationFormModel->getData());
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a donation form is saved */
                            __('Donation form saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a donation form is saved */
                        __('There was an error saving the donation form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a donation form is saved */
                    __('Donation form saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $model MM_WPFS_Admin_CheckoutDonationFormModel
     */
    protected function updateCheckoutDonationFormEmailTemplates(&$model)
    {
        $form = $this->db->getInlineDonationFormById($model->getId());

        $this->updateFormEmailTemplates(MM_WPFS::FORM_TYPE_CHECKOUT_DONATION, $form->emailTemplates, $model);
    }

    function saveCheckoutDonationForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $checkoutDonationFormModel = new MM_WPFS_Admin_CheckoutDonationFormModel($this->loggerService);
                $bindingResult = $checkoutDonationFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->updateCheckoutDonationFormEmailTemplates($checkoutDonationFormModel);

                    $this->db->updateCheckoutDonationForm($checkoutDonationFormModel->getId(), $checkoutDonationFormModel->getData());
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a donation form is saved */
                            __('Donation form saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a donation form is saved */
                        __('There was an error saving the donation form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a donation form is saved */
                    __('Donation form saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    function saveCheckoutSaveCardForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {

                $checkoutSaveCardFormModel = new MM_WPFS_Admin_CheckoutSaveCardFormModel($this->loggerService);
                $bindingResult = $checkoutSaveCardFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->updateCheckoutSaveCardFormEmailTemplates($checkoutSaveCardFormModel);

                    $this->db->updateCheckoutPaymentForm($checkoutSaveCardFormModel->getId(), $checkoutSaveCardFormModel->getData());
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a save card form is saved */
                            __('Save card form saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a save card form is saved */
                        __('There was an error saving the save card form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a save card form is saved */
                    __('Save card form saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlug(MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function gatherSubscriptionDetails($id): array
    {
        $subscription = $this->db->findSubscriberById($id);

        $subscriptionStatus = $subscription->status;
        $liveMode = $subscription->livemode;
        $stripeCustomerId = $subscription->stripeCustomerID;
        $stripeSubscriptionId = $subscription->stripeSubscriptionID;
        list($paymentMethodCssClass, $paymentMethodTooltip) = $this->getPaymentMethodStyleForPayment($subscription);

        $stripePlan = $this->stripe->retrievePlan($subscription->planID);

        $subscriptionDetails = array(
            'id' => $subscription->subscriberID,
            'subscriptionId' => $stripeSubscriptionId,
            'subscriptionUrl' => $this->buildStripeSubscriptionUrl($stripeSubscriptionId, $liveMode),
            'subscriptionStatus' => $subscriptionStatus,
            'paymentMethodCssClass' => $paymentMethodCssClass,
            'paymentMethodTooltip' => $paymentMethodTooltip,
            'localizedSubscriptionStatus' => MM_WPFS_Admin::getSubscriberStatusLabelByForm($subscription),
            'localizedAmount' => MM_WPFS_Admin::getSubscriptionAmountLabel($this->staticContext, $subscription, $stripePlan),
            'date' => MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat(strtotime($subscription->created)),
            'customerId' => $stripeCustomerId,
            'customerUrl' => $this->buildStripeCustomerUrl($stripeCustomerId, $liveMode),
            'customerName' => $subscription->name,
            'customerEmail' => $subscription->email,
            'customerPhone' => $subscription->phoneNumber,
            'formDisplayName' => $subscription->formName,
            'isLiveMode' => $subscription->livemode,
            'localizedApiMode' => $this->getLocalizedApiMode($liveMode),
            'coupon' => $subscription->coupon,
            'ipAddress' => $subscription->ipAddressSubmit,
            'customFields' => $this->decodeCustomFieldsJSON($subscription)
        );

        if (!is_null($stripePlan)) {
            $subscriptionDetails['productName'] = $stripePlan->product->name;
        } else {
            $subscriptionDetails['productName'] =
                /* translators: This label is displayed when a product has no name */
                __('(Not available)', 'wp-full-stripe-admin');
        }

        if (!empty($subscription->addressCountry)) {
            $subscriptionDetails['billingName'] = !empty($subscription->billingName) ? $subscription->billingName : null;
            $subscriptionDetails['billingAddressLine1'] = !empty($subscription->addressLine1) ? $subscription->addressLine1 : null;
            ;
            $subscriptionDetails['billingAddressLine2'] = !empty($subscription->addressLine2) ? $subscription->addressLine2 : null;
            ;
            $subscriptionDetails['billingAddressCity'] = !empty($subscription->addressCity) ? $subscription->addressCity : null;
            ;
            $subscriptionDetails['billingAddressState'] = !empty($subscription->addressState) ? $subscription->addressState : null;
            ;
            $subscriptionDetails['billingAddressZip'] = !empty($subscription->addressZip) ? $subscription->addressZip : null;
            ;
            $subscriptionDetails['billingAddressCountry'] = !empty($subscription->addressCountry) ? $subscription->addressCountry : null;
            ;
        } else {
            $subscriptionDetails['billingAddressCountry'] = null;
        }

        if (!empty($subscription->shippingAddressCountry)) {
            $subscriptionDetails['shippingName'] = !empty($subscription->shippingName) ? $subscription->shippingName : null;
            $subscriptionDetails['shippingAddressLine1'] = !empty($subscription->shippingAddressLine1) ? $subscription->shippingAddressLine1 : null;
            ;
            $subscriptionDetails['shippingAddressLine2'] = !empty($subscription->shippingAddressLine2) ? $subscription->shippingAddressLine2 : null;
            ;
            $subscriptionDetails['shippingAddressCity'] = !empty($subscription->shippingAddressCity) ? $subscription->shippingAddressCity : null;
            ;
            $subscriptionDetails['shippingAddressState'] = !empty($subscription->shippingAddressState) ? $subscription->addressState : null;
            ;
            $subscriptionDetails['shippingAddressZip'] = !empty($subscription->shippingAddressZip) ? $subscription->shippingAddressZip : null;
            ;
            $subscriptionDetails['shippingAddressCountry'] = !empty($subscription->shippingAddressCountry) ? $subscription->shippingAddressCountry : null;
            ;
        } else {
            $subscriptionDetails['shippingAddressCountry'] = null;
        }

        return $subscriptionDetails;
    }

    function getSubscriptionDetails()
    {
        $id = $_POST['id'];

        try {
            $subscriptionDetails = $this->gatherSubscriptionDetails($id);

            $return = array(
                'success' => true,
                'data' => $subscriptionDetails
            );
        } catch (WPFS_UserFriendlyException $e) {
            $return = array(
                'success' => false,
                'data' => array()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Error while gathering subscriptiption details', $ex);
            $return = array(
                'success' => false,
                'data' => array()
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }


    function cancelSubscription()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_cancel_subscriber_action', $id); // todo: review action name and signature
                $subscriber = $this->db->findSubscriberById($id);

                if ($subscriber) {
                    $this->db->cancelSubscription($id);
                    $this->stripe->cancelSubscription($subscriber->stripeCustomerID, $subscriber->stripeSubscriptionID);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label after a subscription is canceled */
                            __('Subscription canceled.', 'wp-full-stripe-admin'),
                        'redirectURL' => add_query_arg(
                            array(
                                'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                                'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS
                            ),
                            admin_url('admin.php')
                        )
                    );
                } else {
                    $return = array('success' => false);
                }
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error while canceling subscription', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a subscription is canceled */
                        __('There was an error canceling the subscription: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a subscription is canceled */
                    __('Subscription canceled.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    function cancelDonation()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_cancel_donation_action', $id);    // todo: review action name and signature
                $donation = $this->db->getDonation($id);

                if ($donation) {
                    $this->db->cancelDonationByDonationId($id);
                    $this->stripe->cancelSubscription($donation->stripeCustomerID, $donation->stripeSubscriptionID);
                }

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label after a recurring donation is canceled */
                        __('Donation canceled.', 'wp-full-stripe-admin'),
                    'redirectURL' => add_query_arg(
                        array(
                            'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS
                        ),
                        admin_url('admin.php')
                    )
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error while canceling donation', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a recurring donation is canceled */
                        __('There was an error canceling the donation: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }

        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a recurring donation is canceled */
                    __('Donation canceled.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    public function deleteSubscription()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_delete_subscription_record_action', $id);     // todo: review action name and signature
                $this->db->deleteSubscriptionById($id);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label after a subscription is deleted */
                        __('Subscription deleted.', 'wp-full-stripe-admin'),
                    'redirectURL' => add_query_arg(
                        array(
                            'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS
                        ),
                        admin_url('admin.php')
                    )
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error while deleting subscription', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a subscription is deleted */
                        __('There was an error deleting the subscription: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }

        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a subscription is deleted */
                    __('Subscription deleted.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SUBSCRIPTIONS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    function deletePayment()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_delete_payment_action', $id); // todo: revise action name and parameters
                $this->db->deletePayment($id);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label after a one-time payment is deleted */
                        __('Payment deleted.', 'wp-full-stripe-admin'),
                    'redirectURL' => add_query_arg(
                        array(
                            'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS
                        ),
                        admin_url('admin.php')
                    )
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error while deleting payment', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a one-time payment is deleted */
                        __('There was an error deleting the payment: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a one-time payment is deleted */
                    __('Payment deleted.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    function deleteDonation()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_delete_donation_action', $id);        // todo: review action name and signature
                $this->db->deleteDonation($id);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label after a donaton is deleted */
                        __('Donation deleted.', 'wp-full-stripe-admin'),
                    'redirectURL' => add_query_arg(
                        array(
                            'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS
                        ),
                        admin_url('admin.php')
                    )
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error while deleting donation', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label after a donaton is deleted */
                        __('There was an error deleting the donation: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label after a donaton is deleted */
                    __('Donation deleted.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $isLiveMode
     *
     * @return string
     */
    private function getLocalizedApiMode($isLiveMode): string
    {
        return $isLiveMode == 1 ?
            /* translators: The 'Live' API mode status */
            __('Live', 'wp-full-stripe-admin') :
            /* translators: The 'Test' API mode status */
            __('Test', 'wp-full-stripe-admin');
    }

    /**
     * @param $liveMode
     *
     * @return string
     */
    public static function buildStripeBaseUrlStatic($liveMode): string
    {
        $href = self::HTTPS_DASHBOARD_STRIPE_COM;
        if ($liveMode == 0) {
            $href .= self::PATH_TEST;
        }

        return $href;
    }

    /**
     * @param $liveMode
     *
     * @return string
     */
    public static function buildStripeProductsUrlStatic($liveMode): string
    {
        $href = self::buildStripeBaseUrlStatic($liveMode);
        $href .= self::PATH_PRODUCTS;

        return $href;
    }

    /**
     * @param $liveMode
     * @return string
     */
    protected function buildStripeBaseUrl($liveMode): string
    {
        return self::buildStripeBaseUrlStatic($liveMode);
    }

    /**
     * @param $stripeSubscriptionId
     * @param $liveMode
     *
     * @return string
     */
    protected function buildStripeSubscriptionUrl($stripeSubscriptionId, $liveMode): string
    {
        $href = $this->buildStripeBaseUrl($liveMode);
        $href .= self::PATH_SUBSCRIPTIONS . $stripeSubscriptionId;

        return $href;
    }

    /**
     * @param $stripePaymentId
     * @param $liveMode
     *
     * @return string
     */
    protected function buildStripePaymentUrl($stripePaymentId, $liveMode): string
    {
        $href = $this->buildStripeBaseUrl($liveMode);
        $href .= self::PATH_PAYMENTS . $stripePaymentId;

        return $href;
    }

    /**
     * @param $stripeCustomerId
     * @param $liveMode
     *
     * @return string
     */
    protected function buildStripeCustomerUrl($stripeCustomerId, $liveMode): string
    {
        $href = $this->buildStripeBaseUrl($liveMode);
        $href .= self::PATH_CUSTOMERS . $stripeCustomerId;

        return $href;
    }


    protected function getPaymentMethodStyleForPayment($payment)
    {
        return array(
            'wpfs-credit-card wpfs-credit-card--generic wpfs-credit-card--lg',
            /* translators: Label for the credit or debit card payment method */
            __('Credit or debit card', 'wp-full-stripe-admin')
        );
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function gatherPaymentDetails($id): array
    {
        $payment = $this->db->getPayment($id);

        $paymentStatus = MM_WPFS_Utils::getPaymentStatus($payment);
        $liveMode = $payment->livemode;
        $stripeCustomerId = $payment->stripeCustomerID;
        $stripePaymentId = $payment->eventID;
        list($paymentMethodCssClass, $paymentMethodTooltip) = $this->getPaymentMethodStyleForPayment($payment);

        $paymentDetails = array(
            'id' => $payment->paymentID,
            'paymentId' => $stripePaymentId,
            'paymentUrl' => $this->buildStripePaymentUrl($stripePaymentId, $liveMode),
            'paymentStatus' => $paymentStatus,
            'paymentMethodCssClass' => $paymentMethodCssClass,
            'paymentMethodTooltip' => $paymentMethodTooltip,
            'localizedPaymentStatus' => MM_WPFS_Admin::getPaymentStatusLabel($paymentStatus),
            'date' => MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat(strtotime($payment->created)),
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'localizedAmount' => MM_WPFS_Currencies::formatAndEscape($this->staticContext, $payment->currency, $payment->amount),
            'customerId' => $stripeCustomerId,
            'customerUrl' => $this->buildStripeCustomerUrl($stripeCustomerId, $liveMode),
            'customerName' => $payment->name,
            'customerEmail' => $payment->email,
            'customerPhone' => $payment->phoneNumber,
            'formDisplayName' => $payment->formName,
            'isLiveMode' => $payment->livemode,
            'localizedApiMode' => $this->getLocalizedApiMode($liveMode),
            'coupon' => $payment->coupon,
            'ipAddress' => $payment->ipAddressSubmit,
            'customFields' => $this->decodeCustomFieldsJSON($payment)
        );

        if (!empty($payment->addressCountry)) {
            $paymentDetails['billingName'] = !empty($payment->billingName) ? $payment->billingName : null;
            $paymentDetails['billingAddressLine1'] = !empty($payment->addressLine1) ? $payment->addressLine1 : null;
            ;
            $paymentDetails['billingAddressLine2'] = !empty($payment->addressLine2) ? $payment->addressLine2 : null;
            ;
            $paymentDetails['billingAddressCity'] = !empty($payment->addressCity) ? $payment->addressCity : null;
            ;
            $paymentDetails['billingAddressState'] = !empty($payment->addressState) ? $payment->addressState : null;
            ;
            $paymentDetails['billingAddressZip'] = !empty($payment->addressZip) ? $payment->addressZip : null;
            ;
            $paymentDetails['billingAddressCountry'] = !empty($payment->addressCountry) ? $payment->addressCountry : null;
            ;
        } else {
            $paymentDetails['billingAddressCountry'] = null;
        }

        if (!empty($payment->shippingAddressCountry)) {
            $paymentDetails['shippingName'] = !empty($payment->shippingName) ? $payment->shippingName : null;
            $paymentDetails['shippingAddressLine1'] = !empty($payment->shippingAddressLine1) ? $payment->shippingAddressLine1 : null;
            ;
            $paymentDetails['shippingAddressLine2'] = !empty($payment->shippingAddressLine2) ? $payment->shippingAddressLine2 : null;
            ;
            $paymentDetails['shippingAddressCity'] = !empty($payment->shippingAddressCity) ? $payment->shippingAddressCity : null;
            ;
            $paymentDetails['shippingAddressState'] = !empty($payment->shippingAddressState) ? $payment->addressState : null;
            ;
            $paymentDetails['shippingAddressZip'] = !empty($payment->shippingAddressZip) ? $payment->shippingAddressZip : null;
            ;
            $paymentDetails['shippingAddressCountry'] = !empty($payment->shippingAddressCountry) ? $payment->shippingAddressCountry : null;
            ;
        } else {
            $paymentDetails['shippingAddressCountry'] = null;
        }

        return $paymentDetails;
    }

    function getPaymentDetails()
    {
        $id = $_POST['id'];

        try {
            $paymentDetails = $this->gatherPaymentDetails($id);

            $return = array(
                'success' => true,
                'data' => $paymentDetails
            );
        } catch (WPFS_UserFriendlyException $e) {
            $return = array(
                'success' => false,
                'data' => array()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Error while gathering payment details', $ex);

            $return = array(
                'success' => false,
                'data' => array()
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $id
     *
     * @return array
     */
    private function gatherDonationDetails($id): array
    {
        $donation = $this->db->getDonation($id);

        $paymentStatus = MM_WPFS_Utils::getDonationPaymentStatus($donation);
        $donationStatus = MM_WPFS_Utils::getDonationStatus($donation);
        $liveMode = $donation->livemode;
        $stripeCustomerId = $donation->stripeCustomerID;
        $stripePaymentIntentId = $donation->stripePaymentIntentID;
        $stripeSubscriptionId = $donation->stripeSubscriptionID;

        list($paymentMethodCssClass, $paymentMethodTooltip) = $this->getPaymentMethodStyleForPayment($donation);

        $paymentDetails = array(
            'id' => $donation->donationID,
            'paymentIntentId' => $stripePaymentIntentId,
            'paymentIntentUrl' => $this->buildStripePaymentUrl($stripePaymentIntentId, $liveMode),
            'subscriptionId' => $stripeSubscriptionId,
            'subscriptionUrl' => !is_null($stripeSubscriptionId) ? $this->buildStripeSubscriptionUrl($stripeSubscriptionId, $liveMode) : null,
            'paymentStatus' => $paymentStatus,
            'donationStatus' => $donationStatus,
            'localizedDonationStatus' => MM_WPFS_Admin::getDonationStatusLabel($donationStatus),
            'paymentMethodCssClass' => $paymentMethodCssClass,
            'paymentMethodTooltip' => $paymentMethodTooltip,
            'date' => MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat(strtotime($donation->created)),
            'amount' => $donation->amount,
            'currency' => $donation->currency,
            'localizedAmount' => MM_WPFS_Currencies::formatAndEscape($this->staticContext, $donation->currency, $donation->amount),
            'customerId' => $stripeCustomerId,
            'customerUrl' => $this->buildStripeCustomerUrl($stripeCustomerId, $liveMode),
            'customerName' => $donation->name,
            'customerEmail' => $donation->email,
            'customerPhone' => $donation->phoneNumber,
            'formDisplayName' => $donation->formName,
            'isLiveMode' => $donation->livemode,
            'localizedApiMode' => $this->getLocalizedApiMode($liveMode),
            'ipAddress' => $donation->ipAddressSubmit,
            'customFields' => $this->decodeCustomFieldsJSON($donation)
        );

        if (!empty($donation->addressCountry)) {
            $paymentDetails['billingName'] = !empty($donation->billingName) ? $donation->billingName : null;
            $paymentDetails['billingAddressLine1'] = !empty($donation->addressLine1) ? $donation->addressLine1 : null;
            ;
            $paymentDetails['billingAddressLine2'] = !empty($donation->addressLine2) ? $donation->addressLine2 : null;
            ;
            $paymentDetails['billingAddressCity'] = !empty($donation->addressCity) ? $donation->addressCity : null;
            ;
            $paymentDetails['billingAddressState'] = !empty($donation->addressState) ? $donation->addressState : null;
            ;
            $paymentDetails['billingAddressZip'] = !empty($donation->addressZip) ? $donation->addressZip : null;
            ;
            $paymentDetails['billingAddressCountry'] = !empty($donation->addressCountry) ? $donation->addressCountry : null;
            ;
        } else {
            $paymentDetails['billingAddressCountry'] = null;
        }

        if (!empty($donation->shippingAddressCountry)) {
            $paymentDetails['shippingName'] = !empty($donation->shippingName) ? $donation->shippingName : null;
            $paymentDetails['shippingAddressLine1'] = !empty($donation->shippingAddressLine1) ? $donation->shippingAddressLine1 : null;
            ;
            $paymentDetails['shippingAddressLine2'] = !empty($donation->shippingAddressLine2) ? $donation->shippingAddressLine2 : null;
            ;
            $paymentDetails['shippingAddressCity'] = !empty($donation->shippingAddressCity) ? $donation->shippingAddressCity : null;
            ;
            $paymentDetails['shippingAddressState'] = !empty($donation->shippingAddressState) ? $donation->addressState : null;
            ;
            $paymentDetails['shippingAddressZip'] = !empty($donation->shippingAddressZip) ? $donation->shippingAddressZip : null;
            ;
            $paymentDetails['shippingAddressCountry'] = !empty($donation->shippingAddressCountry) ? $donation->shippingAddressCountry : null;
            ;
        } else {
            $paymentDetails['shippingAddressCountry'] = null;
        }

        return $paymentDetails;
    }

    function getDonationDetails()
    {
        $id = $_POST['id'];

        try {
            $donationDetails = $this->gatherDonationDetails($id);

            $return = array(
                'success' => true,
                'data' => $donationDetails
            );
        } catch (WPFS_UserFriendlyException $e) {
            $return = array(
                'success' => false,
                'data' => array()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Error while gathering donation details', $ex);

            $return = array(
                'success' => false,
                'data' => array()
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }



    function refundPayment()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_refund_payment_action', $id);     // todo: rethink action name and parameters
                $success = $this->voidOrRefundPayment($id);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label for a successfully refunded payment */
                        __('Payment refunded.', 'wp-full-stripe-admin'),
                    'redirectURL' => add_query_arg(
                        array(
                            'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS
                        ),
                        admin_url('admin.php')
                    )
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error while refunding payment', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for a payment that cannot be refunded */
                        __('There was an error refunding the payment: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for a successfully refunded payment */
                    __('Payment refunded.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    function refundDonation()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_refund_donation_action', $id);        // todo: review action name and signature
                $success = $this->voidOrRefundDonation($id);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label for a successfully refunded donation */
                        __('Donation refunded.', 'wp-full-stripe-admin'),
                    'redirectURL' => add_query_arg(
                        array(
                            'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS
                        ),
                        admin_url('admin.php')
                    )
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error while refunding donation', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for a donation that cannot be refunded */
                        __('There was an error refunding the donation: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for a successfully refunded donation */
                    __('Donation refunded.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_DONATIONS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $id
     *
     * @return mixed|null|\StripeWPFS\ApiResource
     */
    private function voidOrRefundDonation($id)
    {
        $this->logger->debug(__FUNCTION__, 'CALLED, id=' . print_r($id, true));

        $donation = null;
        if (!is_null($id)) {
            $donation = $this->db->getDonation($id);
        }

        $refundedSuccessfully = false;
        if (isset($donation)) {
            $donationStatus = MM_WPFS_Utils::getDonationPaymentStatus($donation);

            if (
                MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $donationStatus ||
                MM_WPFS::PAYMENT_STATUS_PAID === $donationStatus
            ) {
                $refund = $this->stripe->cancelOrRefundPaymentIntent($donation->stripePaymentIntentID);
                if (isset($refund)) {
                    $paymentIntent = $refund;
                    if (\StripeWPFS\PaymentIntent::STATUS_CANCELED === $paymentIntent->status) {
                        $refundedSuccessfully = true;
                    }
                } elseif (isset($refund)) {
                    if (MM_WPFS::REFUND_STATUS_SUCCEEDED === $refund->status) {
                        $refundedSuccessfully = true;
                    }
                }

                if ($refundedSuccessfully) {
                    $this->db->updateDonationByPaymentIntentId(
                        $donation->stripePaymentIntentID,
                        array(
                            'refunded' => true
                        )
                    );
                }

                return $refundedSuccessfully;
            }
        }

        return false;
    }


    /**
     * @param $paymentId
     *
     * @return bool
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    private function voidOrRefundPayment($paymentId)
    {
        $this->logger->debug(__FUNCTION__, 'CALLED, id=' . print_r($paymentId, true));

        $payment = null;
        if (!is_null($paymentId)) {
            $payment = $this->db->getPayment($paymentId);
        }

        $refundedSuccessfully = false;
        if (isset($payment)) {
            $paymentStatus = MM_WPFS_Utils::getPaymentStatus($payment);
            $paymentObjectType = MM_WPFS_Utils::getPaymentObjectType($payment);

            $this->logger->debug(__FUNCTION__, "payment_status={$paymentStatus}, payment_object_type={$paymentObjectType}");

            if (
                MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $paymentStatus
                || MM_WPFS::PAYMENT_STATUS_PAID === $paymentStatus
            ) {
                if (MM_WPFS::PAYMENT_OBJECT_TYPE_STRIPE_CHARGE === $paymentObjectType) {
                    $refund = $this->stripe->refundCharge($payment->eventID);

                    if (isset($refund) && MM_WPFS::REFUND_STATUS_SUCCEEDED === $refund->status) {
                        $refundedSuccessfully = true;
                    }
                } elseif (MM_WPFS::PAYMENT_OBJECT_TYPE_STRIPE_PAYMENT_INTENT === $paymentObjectType) {
                    $refund = $this->stripe->cancelOrRefundPaymentIntent($payment->eventID);
                    if (isset($refund)) {
                        $paymentIntent = $refund;
                        if (\StripeWPFS\PaymentIntent::STATUS_CANCELED === $paymentIntent->status) {
                            $refundedSuccessfully = true;
                        }
                    } elseif (isset($refund)) {
                        if (MM_WPFS::REFUND_STATUS_SUCCEEDED === $refund->status) {
                            $refundedSuccessfully = true;
                        }
                    }
                }
                if ($refundedSuccessfully) {
                    $this->db->updatePaymentByEventId(
                        $payment->eventID,
                        array(
                            'refunded' => true
                        )
                    );
                }

                return $refundedSuccessfully;
            }
        }

        return false;
    }

    function capturePayment()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_capture_payment_action', $id);        // todo: revise action name and params
                $success = $this->captureChargeAndPaymentIntent($id);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label for an authorize & capture payment which has been captured successfully */
                        __('Payment captured.', 'wp-full-stripe-admin'),
                    'redirectURL' => add_query_arg(
                        array(
                            'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS
                        ),
                        admin_url('admin.php')
                    )
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error while capturing payment', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for an authorize & capture payment which cannot be captured */
                        __('There was an error capturing the payment: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for an authorize & capture payment which has been captured successfully */
                    __('Payment captured.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_PAYMENTS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $paymentId
     *
     * @return bool
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    private function captureChargeAndPaymentIntent($paymentId)
    {
        $this->logger->debug(__FUNCTION__, 'CALLED, payment_id=' . print_r($paymentId, true));

        $payment = $this->db->getPayment($paymentId);
        if (isset($payment)) {
            $paymentStatus = MM_WPFS_Utils::getPaymentStatus($payment);
            $paymentObjectType = MM_WPFS_Utils::getPaymentObjectType($payment);

            $this->logger->debug(__FUNCTION__, "payment_status={$paymentStatus}, payment_object_type={$paymentObjectType}");

            if (MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $paymentStatus) {
                // TODO: fix this so that it doesn't rely on the object being a stripe object
                if (MM_WPFS::PAYMENT_OBJECT_TYPE_STRIPE_CHARGE === $paymentObjectType) {
                    $charge = $this->stripe->captureCharge($payment->eventID);
                    if (isset($charge)) {
                        if (true === $charge->captured && MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $charge->status) {
                            $this->db->updatePaymentByEventId(
                                $charge->id,
                                array(
                                    'paid' => $charge->paid,
                                    'captured' => $charge->captured,
                                    'refunded' => $charge->refunded,
                                    'last_charge_status' => $charge->status,
                                    'failure_code' => $charge->failure_code,
                                    'failure_message' => $charge->failure_message
                                )
                            );

                            return true;
                        }
                    }
                } elseif (MM_WPFS::PAYMENT_OBJECT_TYPE_STRIPE_PAYMENT_INTENT === $paymentObjectType) {
                    $paymentIntent = $this->stripe->capturePaymentIntent($payment->eventID);
                    $lastCharge = $this->stripe->getLatestCharge($paymentIntent);
                    if (isset($lastCharge)) {
                        if (true === $lastCharge->captured && MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED === $lastCharge->status) {
                            $this->db->updatePaymentByEventId(
                                $paymentIntent->id,
                                array(
                                    'paid' => $lastCharge->paid,
                                    'captured' => $lastCharge->captured,
                                    'refunded' => $lastCharge->refunded,
                                    'last_charge_status' => $lastCharge->status,
                                    'failure_code' => $lastCharge->failure_code,
                                    'failure_message' => $lastCharge->failure_message
                                )
                            );

                            return true;
                        }
                    }
                }

            }
        }

        return false;
    }



    /**
     * @param $id
     *
     * @return array
     */
    private function gatherSavedCardDetails($id): array
    {
        $savedCard = $this->db->getSavedCard($id);

        $liveMode = $savedCard->livemode;
        $stripeCustomerId = $savedCard->stripeCustomerID;
        list($paymentMethodCssClass, $paymentMethodTooltip) = $this->getPaymentMethodStyleForPayment($savedCard);

        $savedCardDetails = array(
            'id' => $savedCard->captureID,
            'customerId' => $stripeCustomerId,
            'customerUrl' => $this->buildStripeCustomerUrl($stripeCustomerId, $liveMode),
            'paymentMethodCssClass' => $paymentMethodCssClass,
            'paymentMethodTooltip' => $paymentMethodTooltip,
            'date' => MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat(strtotime($savedCard->created)),
            'customerName' => $savedCard->name,
            'customerEmail' => $savedCard->email,
            'formDisplayName' => $savedCard->formName,
            'isLiveMode' => $liveMode,
            'localizedApiMode' => $this->getLocalizedApiMode($liveMode),
            'ipAddress' => $savedCard->ipAddressSubmit,
            'customFields' => $this->decodeCustomFieldsJSON($savedCard)
        );

        if (!empty($savedCard->addressCountry)) {
            $savedCardDetails['billingName'] = !empty($savedCard->billingName) ? $savedCard->billingName : null;
            $savedCardDetails['billingAddressLine1'] = !empty($savedCard->addressLine1) ? $savedCard->addressLine1 : null;
            ;
            $savedCardDetails['billingAddressLine2'] = !empty($savedCard->addressLine2) ? $savedCard->addressLine2 : null;
            ;
            $savedCardDetails['billingAddressCity'] = !empty($savedCard->addressCity) ? $savedCard->addressCity : null;
            ;
            $savedCardDetails['billingAddressState'] = !empty($savedCard->addressState) ? $savedCard->addressState : null;
            ;
            $savedCardDetails['billingAddressZip'] = !empty($savedCard->addressZip) ? $savedCard->addressZip : null;
            ;
            $savedCardDetails['billingAddressCountry'] = !empty($savedCard->addressCountry) ? $savedCard->addressCountry : null;
            ;
        } else {
            $savedCardDetails['billingAddressCountry'] = null;
        }

        if (!empty($savedCard->shippingAddressCountry)) {
            $savedCardDetails['shippingName'] = !empty($savedCard->shippingName) ? $savedCard->shippingName : null;
            $savedCardDetails['shippingAddressLine1'] = !empty($savedCard->shippingAddressLine1) ? $savedCard->shippingAddressLine1 : null;
            ;
            $savedCardDetails['shippingAddressLine2'] = !empty($savedCard->shippingAddressLine2) ? $savedCard->shippingAddressLine2 : null;
            ;
            $savedCardDetails['shippingAddressCity'] = !empty($savedCard->shippingAddressCity) ? $savedCard->shippingAddressCity : null;
            ;
            $savedCardDetails['shippingAddressState'] = !empty($savedCard->shippingAddressState) ? $savedCard->addressState : null;
            ;
            $savedCardDetails['shippingAddressZip'] = !empty($savedCard->shippingAddressZip) ? $savedCard->shippingAddressZip : null;
            ;
            $savedCardDetails['shippingAddressCountry'] = !empty($savedCard->shippingAddressCountry) ? $savedCard->shippingAddressCountry : null;
            ;
        } else {
            $savedCardDetails['shippingAddressCountry'] = null;
        }

        return $savedCardDetails;
    }

    function getSavedCardDetails()
    {
        $id = $_POST['id'];

        try {
            $savedCardDetails = $this->gatherSavedCardDetails($id);

            $return = array(
                'success' => true,
                'data' => $savedCardDetails
            );
        } catch (WPFS_UserFriendlyException $e) {
            $return = array(
                'success' => false,
                'data' => array()
            );
        } catch (Exception $ex) {
            $this->logger->error(__FUNCTION__, 'Error reading save card details', $ex);

            $return = array(
                'success' => false,
                'data' => array()
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }


    function deleteSavedCard()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = $_POST['id'];

            try {
                do_action('fullstripe_admin_delete_card_capture_action', $id);        // todo: review action name and signature
                $this->db->deleteSavedCard($id);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label for successfully deleted save card record */
                        __('Saved card deleted.', 'wp-full-stripe-admin'),
                    'redirectURL' => add_query_arg(
                        array(
                            'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                            'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SAVED_CARDS
                        ),
                        admin_url('admin.php')
                    )
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $ex) {
                $this->logger->error(__FUNCTION__, 'Error deleting saved card', $ex);

                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for save card record that cannot deleted */
                        __('There was an error deleting the saved card: ', 'wp-full-stripe-admin') . $ex->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for successfully deleted save card record */
                    __('Saved card deleted.', 'wp-full-stripe-admin'),
                'redirectURL' => add_query_arg(
                    array(
                        'page' => MM_WPFS_Admin_Menu::SLUG_TRANSACTIONS,
                        'tab' => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_SAVED_CARDS
                    ),
                    admin_url('admin.php')
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    public function cloneForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = stripslashes($_POST['id']);
            $type = stripslashes($_POST['type']);
            $layout = stripslashes($_POST['layout']);
            $newFormName = stripslashes($_POST['newFormName']);
            $newFormDisplayName = stripslashes($_POST['newFormDisplayName']);
            $editNewForm = 'true' === stripslashes($_POST['editNewForm']);

            try {
                $newFormId = (new MM_WPFS_Admin_CloneFormFactory())->cloneForm($id, $type, $layout, $newFormName, $newFormDisplayName);
                $redirectUrl = admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_FORMS);
                if (true === $editNewForm) {
                    $redirectUrl = MM_WPFS_Utils::getFormEditUrl($newFormId, $type, $layout);
                }

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label for cloned form */
                        __('Form cloned.', 'wp-full-stripe-admin'),
                    'redirectURL' => $redirectUrl
                );
            } catch (WPFS_UserFriendlyException $e) {
                $return = array(
                    'success' => false,
                    'msg' => $e->getMessage()
                );
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for a form that cannot be cloned */
                        __('There was an error cloning the form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for cloned form */
                    __('Form cloned.', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;

    }

    public function deleteForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            $id = stripslashes($_POST['id']);
            $type = stripslashes($_POST['type']);
            $layout = stripslashes($_POST['layout']);

            try {
                (new MM_WPFS_Admin_DeleteFormFactory())->deleteForm($id, $type, $layout);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label for a deleted form */
                        __('Form deleted.', 'wp-full-stripe-admin'),
                    'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_FORMS)
                );
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for a form that cannot be deleted */
                        __('There was an error deleting the form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for a deleted form */
                    __('Form deleted.', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }


        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $stripeAccountModel MM_WPFS_Admin_StripeAccountModel
     */
    protected function saveStripeSettings(MM_WPFS_Admin_StripeAccountModel $stripeAccountModel)
    {
        $this->options->setSeveral([
            MM_WPFS_Options::OPTION_API_TEST_SECRET_KEY => $stripeAccountModel->getTestSecretKey(),
            MM_WPFS_Options::OPTION_API_TEST_PUBLISHABLE_KEY => $stripeAccountModel->getTestPublishableKey(),
            MM_WPFS_Options::OPTION_API_LIVE_SECRET_KEY => $stripeAccountModel->getLiveSecretKey(),
            MM_WPFS_Options::OPTION_API_LIVE_PUBLISHABLE_KEY => $stripeAccountModel->getLivePublishableKey(),
            MM_WPFS_Options::OPTION_API_MODE => $stripeAccountModel->getApiMode()
        ]);
    }

    public function saveStripeAccount()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $stripeAccountModel = new MM_WPFS_Admin_StripeAccountModel($this->loggerService);
                $bindingResult = $stripeAccountModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveStripeSettings($stripeAccountModel);
                    $redirectUrl = admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the Stripe settings */
                            __('Stripe settings saved.', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to save the Stripe settings */
                        __('There was an error saving Stripe settings: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the Stripe settings */
                    __('Stripe settings saved.', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    public function addStripeAccount()
    {
        $accountId = isset($_POST['account_id']) ? sanitize_text_field($_POST['account_id']) : '';
        $mode = isset($_POST['mode']) ? sanitize_text_field($_POST['mode']) : '';

        if ($mode == 'test') {
            $this->options->set(MM_WPFS_OPTIONS::OPTION_TEST_ACCOUNT_ID, $accountId);
            $this->options->set(MM_WPFS_Options::OPTION_USE_WP_TEST_PLATFORM, 1);
        } else if ($mode == 'live') {
            $this->options->set(MM_WPFS_OPTIONS::OPTION_LIVE_ACCOUNT_ID, $accountId);
            $this->options->set(MM_WPFS_Options::OPTION_USE_WP_LIVE_PLATFORM, 1);
        }
    }

    public function createStripeConnectAccount()
    {
        $currentUrl = isset($_POST['current_page_url']) ? sanitize_text_field($_POST['current_page_url']) : '';
        $mode = isset($_POST['mode']) ? sanitize_text_field($_POST['mode']) : '';
        $accountLink = '';
        $response = null;

        if ($mode == 'test') {
            $apiUrl = MM_WPFS_Options::OPTION_GOOGLE_CLOUD_FUNCTIONS_URL . '/createTestConnectAccount';

            $response = wp_remote_post($apiUrl, array(
                'method' => 'POST',
                'headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8'
                ),
                'body' => json_encode(
                    array(
                        'returnUrl' => $currentUrl,
                    )
                )
            )
            );
        } else if ($mode == 'live') {
            $apiUrl = MM_WPFS_Options::OPTION_GOOGLE_CLOUD_FUNCTIONS_URL . '/createLiveConnectAccount';

            $response = wp_remote_post($apiUrl, array(
                'method' => 'POST',
                'headers' => array(
                    'Content-Type' => 'application/json; charset=utf-8'
                ),
                'body' => json_encode(
                    array(
                        'returnUrl' => $currentUrl
                    )
                )
            )
            );

        }
        // if it's an error, we should show it to the user
        if (!$response || is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
            $error_message = $response->get_error_message();
            echo json_encode(
                array(
                    'success' => false,
                    'msg' => 'Something went wrong: ' . $error_message
                )
            );
        } else {
            $body = wp_remote_retrieve_body($response);
            $body = json_decode($body);

            $accountLink = $body->accountLink;

            echo json_encode(
                array(
                    'success' => true,
                    'msg' => 'Connect account created successfully, redirecting to Stripe...',
                    'redirectURL' => $accountLink
                )
            );
        }

        exit;
    }

    /**
     * @param $stripeAccountModel MM_WPFS_Admin_MyAccountModel
     */
    protected function saveMyAccountSettings(MM_WPFS_Admin_MyAccountModel $stripeAccountModel)
    {
        $this->options->setSeveral([
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_SUBSCRIPTIONS_TO_CUSTOMERS => $stripeAccountModel->getShowSubscriptions(),
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS => $stripeAccountModel->getCancelSubscriptions(),
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS => $stripeAccountModel->getWhenCancelSubscriptions(),
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS => $stripeAccountModel->getUpdowngradeSubscriptions(),
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION => $stripeAccountModel->getShowInvoices(),
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SCROLLING_PANE_INTO_VIEW => $stripeAccountModel->getScrollingPaneIntoView()
        ]);
    }

    /**
     *
     */
    public function saveMyAccount()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $myAccountModel = new MM_WPFS_Admin_MyAccountModel($this->loggerService);
                $bindingResult = $myAccountModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveMyAccountSettings($myAccountModel);
                    $redirectUrl = admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the Customer portal settings */
                            __('Customer portal settings saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to save the Customer portal settings */
                        __('There was an error saving Customer portal settings: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the Customer portal settings */
                    __('Customer portal settings saved', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $wpDashboardModel MM_WPFS_Admin_WordpressDashboardModel
     */
    protected function saveWordpressDashboardSettings(MM_WPFS_Admin_WordpressDashboardModel $wpDashboardModel)
    {
        $this->options->setSeveral([
            MM_WPFS_Options::OPTION_DECIMAL_SEPARATOR_SYMBOL => $wpDashboardModel->getDecimalSeparator(),
            MM_WPFS_Options::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE => $wpDashboardModel->getUseSymbolNotCode(),
            MM_WPFS_Options::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION => $wpDashboardModel->getCurrencySymbolAtFirstPosition(),
            MM_WPFS_Options::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT => $wpDashboardModel->getPutSpaceBetweenSymbolAndAmount()
        ]);
    }

    /**
     *
     */
    public function saveWordpressDashboard()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $wpDashboardModel = new MM_WPFS_Admin_WordpressDashboardModel($this->loggerService);
                $bindingResult = $wpDashboardModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveWordpressDashboardSettings($wpDashboardModel);
                    $redirectUrl = admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the WordPress dashboard settings */
                            __('WordPress dashboard settings saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Success banner label for not being able to save the WordPress dashboard settings */
                        __('There was an error saving WordPress dashboard settings: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the WordPress dashboard settings */
                    __('WordPress dashboard settings saved', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $logsModel MM_WPFS_Admin_LogsModel
     */
    protected function saveLogSettings(MM_WPFS_Admin_LogsModel $logsModel)
    {
        $this->options->setSeveral([
            MM_WPFS_Options::OPTION_LOG_LEVEL => $logsModel->getLogLevel(),
            MM_WPFS_Options::OPTION_LOG_TO_WEB_SERVER => $logsModel->getLogToWebServer(),
            MM_WPFS_Options::OPTION_CATCH_UNCAUGHT_ERRORS => $logsModel->getCatchUncaughtErrors(),
        ]);
    }

    public function saveLogs()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $logsModel = new MM_WPFS_Admin_LogsModel($this->loggerService);
                $bindingResult = $logsModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveLogSettings($logsModel);
                    $redirectUrl = admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the Log settings */
                            __('Log settings saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to save the Log settings */
                        __('There was an error saving log settings: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the log settings */
                    __('Log settings saved', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    public function emptyLogs()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $this->db->deleteLogs();
                $redirectUrl = admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS);

                $return = array(
                    'success' => true,
                    'msg' =>
                        /* translators: Success banner label for deleting the log entries */
                        __('Log entries deleted', 'wp-full-stripe-admin'),
                    'redirectURL' => $redirectUrl
                );
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to delete logs */
                        __('There was an error emptying the log: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for deleting the log entries */
                    __('Log entries deleted', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $securityModel MM_WPFS_Admin_SecurityModel
     */
    protected function saveSecuritySettings(MM_WPFS_Admin_SecurityModel $securityModel)
    {
        $this->options->setSeveral([
            MM_WPFS_Options::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA => $securityModel->getSecureInlineForms(),
            MM_WPFS_Options::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA => $securityModel->getSecureCheckoutForms(),
            MM_WPFS_Options::OPTION_SECURE_CUSTOMER_PORTAL_WITH_GOOGLE_RE_CAPTCHA => $securityModel->getSecureCustomerPortal(),
            MM_WPFS_Options::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY => $securityModel->getReCaptchaSecretKey(),
            MM_WPFS_Options::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY => $securityModel->getReCaptchaSiteKey(),
        ]);
    }

    /**
     *
     */
    public function saveSecurity()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $securityModel = new MM_WPFS_Admin_SecurityModel($this->loggerService);
                $bindingResult = $securityModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveSecuritySettings($securityModel);
                    $redirectUrl = admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS);

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the security settings */
                            __('Security settings saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to save the security settings */
                        __('There was an error saving Security settings: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the security settings */
                    __('Security settings saved', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_SETTINGS)
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $optionsModel MM_WPFS_Admin_EmailOptionsModel
     */
    protected function saveEmailOptionsSettings($optionsModel)
    {
        $fromAddress = get_bloginfo('admin_email');
        if ($optionsModel->getFromAddress() === MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_VALUE_FROM_ADDRESS_CUSTOM) {
            $fromAddress = $optionsModel->getFromAddressCustom();
        }

        $bccAddresses = $optionsModel->getSendCopyToList();
        if (!empty($optionsModel->getSendCopyToAdmin())) {
            if (array_search($optionsModel->getSendCopyToAdmin(), $bccAddresses) === false) {
                array_push($bccAddresses, $optionsModel->getSendCopyToAdmin());
            }
        }

        $this->options->setSeveral([
            MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS => $fromAddress,
            MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES => json_encode($bccAddresses)
        ]);
    }

    /**
     *
     */
    public function saveEmailOptions()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $optionsModel = new MM_WPFS_Admin_EmailOptionsModel($this->loggerService);
                $bindingResult = $optionsModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveEmailOptionsSettings($optionsModel);
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(
                        MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS,
                        array(
                            MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS
                        )
                    );

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the email notification settings */
                            __('Email notification options saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to save the email notification settings */
                        __('There was an error saving Email notification options: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the email notification settings */
                    __('Email notification options saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(
                    MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS,
                    array(
                        MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS
                    )
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }


    /**
     * @param $templatesModel MM_WPFS_Admin_EmailTemplatesModel
     */
    protected function saveEmailTemplatesSettings($templatesModel)
    {
        $this->options->set(MM_WPFS_Options::OPTION_EMAIL_TEMPLATES, json_encode($templatesModel->getEmailTemplates()));
    }

    /**
     *
     */
    public function saveEmailTemplates()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $templatesModel = new MM_WPFS_Admin_EmailTemplatesModel($this->loggerService);
                $bindingResult = $templatesModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveEmailTemplatesSettings($templatesModel);
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(
                        MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS,
                        array(
                            MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES
                        )
                    );

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the email templates */
                            __('Email templates saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to save the email templates */
                        __('There was an error saving the email templates: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the email templates */
                    __('Email templates saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(
                    MM_WPFS_Admin_Menu::SLUG_SETTINGS_EMAIL_NOTIFICATIONS,
                    array(
                        MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_TEMPLATES
                    )
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $formsOptionsModel MM_WPFS_Admin_FormsOptionsModel
     */
    protected function saveFormsOptionsSettings($formsOptionsModel)
    {
        $this->options->setSeveral([
            MM_WPFS_Options::OPTION_FILL_IN_EMAIL_FOR_LOGGED_IN_USERS => $formsOptionsModel->getFillInEmail(),
            MM_WPFS_Options::OPTION_SET_FORM_FIELDS_VIA_URL_PARAMETERS => $formsOptionsModel->getSetFormFieldsViaUrlParameters()
        ]);
    }

    /**
     *
     */
    public function saveFormsOptions()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $formsOptionsModel = new MM_WPFS_Admin_FormsOptionsModel($this->loggerService);
                $bindingResult = $formsOptionsModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveFormsOptionsSettings($formsOptionsModel);
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(
                        MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS,
                        array(
                            MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS
                        )
                    );

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the form options */
                            __('Form options saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to save the form options */
                        __('There was an error saving Forms settings: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the form options */
                    __('Form options saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(
                    MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS,
                    array(
                        MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_OPTIONS
                    )
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $formsAppearanceModel MM_WPFS_Admin_FormsAppearanceModel
     */
    protected function saveFormsAppearanceSettings($formsAppearanceModel)
    {
        $this->options->set(MM_WPFS_Options::OPTION_FORM_CUSTOM_CSS, $formsAppearanceModel->getCustomCss());
    }

    /**
     *
     */
    public function saveFormsAppearance()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $formsAppearanceModel = new MM_WPFS_Admin_FormsAppearanceModel($this->loggerService);
                $bindingResult = $formsAppearanceModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $this->saveFormsAppearanceSettings($formsAppearanceModel);
                    $redirectUrl = MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(
                        MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS,
                        array(
                            MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE
                        )
                    );

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for saving the form appearance settings */
                            __('Form appearance settings saved', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to save the form appearance settings */
                        __('There was an error saving Form appearance settings: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for saving the form appearance settings */
                    __('Form appearance settings saved', 'wp-full-stripe-admin'),
                'redirectURL' => MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams(
                    MM_WPFS_Admin_Menu::SLUG_SETTINGS_FORMS,
                    array(
                        MM_WPFS_Admin_Menu::PARAM_NAME_TAB => MM_WPFS_Admin_Menu::PARAM_VALUE_TAB_APPEARANCE
                    )
                )
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    public function createForm()
    {
        if (!MM_WPFS_Utils::isDemoMode()) {
            try {
                $createFormModel = new MM_WPFS_Admin_CreateFormModel($this->loggerService);
                $bindingResult = $createFormModel->bind();

                if ($bindingResult->hasErrors()) {
                    $return = MM_WPFS_Utils::generateReturnValueFromBindings($bindingResult);
                } else {
                    $formId = (new MM_WPFS_Admin_CreateFormFactory())->createForm($createFormModel);
                    $redirectUrl = MM_WPFS_Utils::getFormEditUrl($formId, $createFormModel->getType(), $createFormModel->getLayout());

                    $return = array(
                        'success' => true,
                        'msg' =>
                            /* translators: Success banner label for creating a new form */
                            __('Form created. Redirecting to edit form.', 'wp-full-stripe-admin'),
                        'redirectURL' => $redirectUrl
                    );
                }
            } catch (Exception $e) {
                $return = array(
                    'success' => false,
                    'msg' =>
                        /* translators: Error banner label for not being able to create a new form */
                        __('There was an error creating the form: ', 'wp-full-stripe-admin') . $e->getMessage()
                );
            }
        } else {
            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Success banner label for creating a new form */
                    __('Form created.', 'wp-full-stripe-admin'),
                'redirectURL' => admin_url('admin.php?page=' . MM_WPFS_Admin_Menu::SLUG_FORMS)
            );
        }


        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    protected function getOnetimeProductsForSelector()
    {
        $result = array();

        $onetimePrices = $this->stripe->getOnetimePrices();

        foreach ($onetimePrices as $onetimePrice) {
            $product = new \StdClass;

            $product->stripePriceId = $onetimePrice->id;
            $product->currency = $onetimePrice->currency;
            $product->price = $onetimePrice->unit_amount;
            $product->name = $onetimePrice->product->name;

            array_push($result, $product);
        }

        return $result;
    }

    public function getOnetimeProducts()
    {
        try {
            $onetimeProducts = $this->getOnetimeProductsForSelector();

            $return = array(
                'success' => true,
                'data' => $onetimeProducts
            );
        } catch (Exception $e) {
            $return = array(
                'success' => false,
                'msg' =>
                    /* translators: Error message for not being able to fetch one-time products from Stripe */
                    __('There was an error getting one-time products from Stripe: ', 'wp-full-stripe-admin') . $e->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    protected function getTaxRatesForSelector()
    {
        $result = array();

        $taxRates = $this->stripe->getTaxRates();

        foreach ($taxRates as $taxRate) {
            $res = new \StdClass;

            $res->displayName = $taxRate->display_name;
            $res->inclusive = $taxRate->inclusive;
            $res->countryCode = $taxRate->country;
            $res->countryLabel = MM_WPFS_Countries::getCountryNameFor($taxRate->country);
            $res->stateCode = $taxRate->state;
            $res->stateLabel = MM_WPFS_States::getStateNameFor($taxRate->state);
            $res->percentage = $taxRate->percentage;
            $res->jurisdiction = $taxRate->jurisdiction;
            $res->taxRateId = $taxRate->id;

            array_push($result, $res);
        }

        return $result;
    }

    public function getTaxRates()
    {
        try {
            $taxRates = $this->getTaxRatesForSelector();

            $return = array(
                'success' => true,
                'data' => $taxRates
            );
        } catch (Exception $e) {
            $return = array(
                'success' => false,
                'msg' =>
                    /* translators: Error message for not being able to fetch tax rates from Stripe */
                    __('There was an error getting tax rates from Stripe: ', 'wp-full-stripe-admin') . $e->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    public function sendTestEmail()
    {
        try {
            $data = json_decode(rawurldecode(stripslashes($_POST['data'])));
            $this->mailer->sendTestEmail($data->recipients, $data->subject, $data->body, $data->emailTemplateType);

            $return = array(
                'success' => true,
                'msg' =>
                    /* translators: Error message for not being able to fetch tax rates from Stripe */
                    __('Test email sent successfully.', 'wp-full-stripe-admin')
            );
        } catch (Exception $ex) {
            $return = array(
                'success' => false,
                'msg' =>
                    /* translators: Error message for not being able to send the test email */
                    __('There was an error sending the test email: ', 'wp-full-stripe-admin') . $ex->getMessage()
            );

            $this->logger->error(__FUNCTION__, 'Error sending test email', $ex);
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * @param $recurringPrice \StripeWPFS\Price
     */
    protected function isSupportedRecurringPrice($recurringPrice)
    {
        if ($recurringPrice->billing_scheme === \StripeWPFS\Price::BILLING_SCHEME_TIERED) {
            return false;
        }
        if ($recurringPrice->recurring->usage_type === 'metered') {
            return false;
        }

        return true;
    }

    protected function getRecurringProductsForSelector()
    {
        $result = array();

        $recurringPrices = $this->stripe->getRecurringPrices();
        foreach ($recurringPrices as $recurringPrice) {
            if (!$this->isSupportedRecurringPrice($recurringPrice)) {
                continue;
            }

            $product = new \StdClass;
            $product->stripePriceId = $recurringPrice->id;
            $product->currency = $recurringPrice->currency;
            $product->price = $recurringPrice->unit_amount;
            $product->name = $recurringPrice->product->name;
            $product->interval = $recurringPrice->recurring->interval;
            $product->intervalCount = $recurringPrice->recurring->interval_count;
            $product->usageType = $recurringPrice->recurring->usage_type;     // licensed or metered
            $product->billingScheme = $recurringPrice->billing_scheme;              // per_unit or tiered
            $product->tiersMode = $recurringPrice->tiers_mode;                  // graduated or volume

            array_push($result, $product);
        }

        return $result;
    }

    public function getRecurringProducts()
    {
        try {
            $recurringProducts = $this->getRecurringProductsForSelector();

            $return = array(
                'success' => true,
                'data' => $recurringProducts
            );
        } catch (Exception $e) {
            $return = array(
                'success' => false,
                'msg' =>
                    /* translators: Error message for not being able to fetch recurring products from Stripe */
                    __('There was an error getting recurring products from Stripe: ', 'wp-full-stripe-admin') . $e->getMessage()
            );
        }

        header("Content-Type: application/json");
        echo json_encode($return);
        exit;
    }

    /**
     * Stripe Web hook handler
     */
    function fullstripe_handle_wpfs_event()
    {
        $this->eventHandler->handleRESTRequest();
    }

    /**
     * @param $interval
     * @param $intervalCount
     *
     * @return string
     * @throws Exception
     */
    public static function getSubscriptionIntervalLabel($interval, $intervalCount)
    {
        $formatStr = '';

        switch ($interval) {
            case 'day':
                /* translators: Recurring pricing descriptor.
                 * p1: interval count
                 */
                $formatStr = _n(
                    'daily',
                    'every %d days',
                    $intervalCount,
                    'wp-full-stripe-admin'
                );
                break;

            case 'week':
                /* translators: Recurring pricing descriptor.
                 * p1: interval count
                 */
                $formatStr = _n(
                    'weekly',
                    'every %d weeks',
                    $intervalCount,
                    'wp-full-stripe-admin'
                );
                break;

            case 'month':
                /* translators: Recurring pricing descriptor.
                 * p1: interval count
                 */
                $formatStr = _n(
                    'monthly',
                    'every %d months',
                    $intervalCount,
                    'wp-full-stripe-admin'
                );
                break;

            case 'year':
                /* translators: Recurring pricing descriptor.
                 * p1: interval count
                 */
                $formatStr = _n(
                    'annually',
                    'every %d year',
                    $intervalCount,
                    'wp-full-stripe-admin'
                );
                break;

            default:
                throw new Exception(sprintf('%s.%s(): Unknown plan interval \'%s\'.', __CLASS__, __FUNCTION__, $interval));
                break;
        }

        return $intervalCount > 1 ? sprintf($formatStr, $intervalCount) : $formatStr;
    }

    /**
     * @param $context MM_WPFS_StaticContext
     * @param $subscription
     * @param $stripePlan \StripeWPFS\Price|null
     * @return string
     */
    public static function getSubscriptionAmountLabel($context, $subscription, $stripePlan)
    {
        $amountLabel = '';

        if (!is_null($stripePlan)) {
            $formattedAmount = MM_WPFS_Currencies::formatAndEscape($context, $stripePlan->currency, $stripePlan->unit_amount);

            if ($subscription->quantity > 1) {
                $amountLabel = sprintf('%1$dx %2$s', $subscription->quantity, $formattedAmount);
            } else {
                $amountLabel = $formattedAmount;
            }
        } else {
            if ($subscription->quantity > 1) {
                $amountLabel = sprintf('%1$dx %2$s', $subscription->quantity, $subscription->planID);
            } else {
                $amountLabel = $subscription->planID;
            }
        }

        return $amountLabel;
    }

    /**
     * @param $record
     *
     * @return mixed
     */
    private function decodeCustomFieldsJSON($record)
    {
        $rawCustomFields = array();
        if (isset($record) && isset($record->customFields)) {
            $rawCustomFields = json_decode($record->customFields, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $rawCustomFields = array();
            }
        }

        return $rawCustomFields;
    }

}


class MM_WPFS_Admin_DeleteFormFactory
{
    /** @var MM_WPFS_Database */
    private $db = null;

    public function __construct()
    {
        $this->db = new MM_WPFS_Database();
    }

    /**
     * @param $id string
     * @param $type string
     * @param $layout string
     *
     * @throws Exception
     */
    public function deleteForm($id, $type, $layout)
    {
        if (
            MM_WPFS::FORM_TYPE_PAYMENT == $type &&
            MM_WPFS::FORM_LAYOUT_INLINE == $layout
        ) {
            $this->db->deleteInlinePaymentForm($id);
        } elseif (
            MM_WPFS::FORM_TYPE_PAYMENT == $type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT == $layout
        ) {
            $this->db->deleteCheckoutPaymentForm($id);
        } elseif (
            MM_WPFS::FORM_TYPE_SUBSCRIPTION == $type &&
            MM_WPFS::FORM_LAYOUT_INLINE == $layout
        ) {
            $this->db->deleteInlineSubscriptionForm($id);
        } elseif (
            MM_WPFS::FORM_TYPE_SUBSCRIPTION == $type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT == $layout
        ) {
            $this->db->deleteCheckoutSubscriptionForm($id);
        } elseif (
            MM_WPFS::FORM_TYPE_DONATION == $type &&
            MM_WPFS::FORM_LAYOUT_INLINE == $layout
        ) {
            $this->db->deleteInlineDonationForm($id);
        } elseif (
            MM_WPFS::FORM_TYPE_DONATION == $type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT == $layout
        ) {
            $this->db->deleteCheckoutDonationForm($id);
        } elseif (
            MM_WPFS::FORM_TYPE_SAVE_CARD == $type &&
            MM_WPFS::FORM_LAYOUT_INLINE == $layout
        ) {
            $this->db->deleteInlinePaymentForm($id);
        } elseif (
            MM_WPFS::FORM_TYPE_SAVE_CARD == $type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT == $layout
        ) {
            $this->db->deleteCheckoutPaymentForm($id);
        }
    }
}


class MM_WPFS_Admin_CloneFormFactory
{
    /** @var MM_WPFS_Database */
    private $db = null;

    public function __construct()
    {
        $this->db = new MM_WPFS_Database();
    }

    /**
     * @param $id
     * @param $newFormName
     * @param $newFormDisplayName
     *
     * @throws Exception
     */
    private function cloneCheckoutPaymentForm($id, $newFormName, $newFormDisplayName)
    {
        $existingForm = $this->db->getCheckoutPaymentFormByName($newFormName);

        if ($existingForm === null) {
            $form = $this->db->getCheckoutPaymentFormAsArrayById($id);

            unset($form['checkoutFormID']);
            $form['name'] = $newFormName;
            $form['displayName'] = $newFormDisplayName;

            $this->db->insertCheckoutPaymentForm($form);

            $newForm = $this->db->getCheckoutPaymentFormByName($newFormName);
            return $newForm->checkoutFormID;
        } else {
            throw new WPFS_UserFriendlyException(
                /* translators: Error message for not being able to clone a form
                   p1: form name
                */
                __("Cannot clone form because a form with id '{$newFormName}' already exists.", 'wp-full-stripe-admin')
            );
        }
    }

    /**
     * @param $id
     * @param $newFormName
     * @param $newFormDisplayName
     *
     * @throws WPFS_UserFriendlyException|Exception
     */
    private function cloneInlinePaymentForm($id, $newFormName, $newFormDisplayName)
    {
        $existingForm = $this->db->getInlinePaymentFormByName($newFormName);

        if ($existingForm === null) {
            $form = $this->db->getInlinePaymentFormAsArrayById($id);

            unset($form['paymentFormID']);
            $form['name'] = $newFormName;
            $form['displayName'] = $newFormDisplayName;

            $this->db->insertInlinePaymentForm($form);

            $newForm = $this->db->getInlinePaymentFormByName($newFormName);
            return $newForm->paymentFormID;
        } else {
            throw new WPFS_UserFriendlyException(
                /* translators: Error message for not being able to clone a form
                   p1: form name
                */
                __("Cannot clone form because a form with id '{$newFormName}' already exists.", 'wp-full-stripe-admin')
            );
        }
    }

    /**
     * @param $id
     * @param $newFormName
     * @param $newFormDisplayName
     *
     * @throws Exception
     */
    private function cloneCheckoutSubscriptionForm($id, $newFormName, $newFormDisplayName)
    {
        $existingForm = $this->db->getCheckoutSubscriptionFormByName($newFormName);

        if ($existingForm === null) {
            $form = $this->db->getCheckoutSubscriptionFormAsArrayById($id);

            unset($form['checkoutSubscriptionFormID']);
            $form['name'] = $newFormName;
            $form['displayName'] = $newFormDisplayName;

            $this->db->insertCheckoutSubscriptionForm($form);

            $newForm = $this->db->getCheckoutSubscriptionFormByName($newFormName);
            return $newForm->checkoutSubscriptionFormID;
        } else {
            throw new WPFS_UserFriendlyException(
                /* translators: Error message for not being able to clone a form
                   p1: form name
                */
                __("Cannot clone form because a form with id '{$newFormName}' already exists.", 'wp-full-stripe-admin')
            );
        }
    }

    /**
     * @param $id
     * @param $newFormName
     * @param $newFormDisplayName
     *
     * @throws Exception
     */
    private function cloneInlineSubscriptionForm($id, $newFormName, $newFormDisplayName)
    {
        $existingForm = $this->db->getInlineSubscriptionFormByName($newFormName);

        if ($existingForm === null) {
            $form = $this->db->getInlineSubscriptionFormAsArrayById($id);

            unset($form['subscriptionFormID']);
            $form['name'] = $newFormName;
            $form['displayName'] = $newFormDisplayName;

            $this->db->insertInlineSubscriptionForm($form);

            $newForm = $this->db->getInlineSubscriptionFormByName($newFormName);
            return $newForm->subscriptionFormID;
        } else {
            throw new WPFS_UserFriendlyException(
                /* translators: Error message for not being able to clone a form
                   p1: form name
                */
                __("Cannot clone form because a form with id '{$newFormName}' already exists.", 'wp-full-stripe-admin')
            );
        }
    }

    /**
     * @param $id
     * @param $newFormName
     * @param $newFormDisplayName
     *
     * @throws Exception
     */
    private function cloneCheckoutDonationForm($id, $newFormName, $newFormDisplayName)
    {
        $existingForm = $this->db->getCheckoutDonationFormByName($newFormName);

        if ($existingForm === null) {
            $form = $this->db->getCheckoutDonationFormAsArrayById($id);

            unset($form['checkoutDonationFormID']);
            $form['name'] = $newFormName;
            $form['displayName'] = $newFormDisplayName;

            $this->db->insertCheckoutDonationForm($form);

            $newForm = $this->db->getCheckoutDonationFormByName($newFormName);
            return $newForm->checkoutDonationFormID;
        } else {
            throw new WPFS_UserFriendlyException(
                /* translators: Error message for not being able to clone a form
                   p1: form name
                */
                __("Cannot clone form because a form with id '{$newFormName}' already exists.", 'wp-full-stripe-admin')
            );
        }
    }

    /**
     * @param $id
     * @param $newFormName
     * @param $newFormDisplayName
     *
     * @throws Exception
     */
    private function cloneInlineDonationForm($id, $newFormName, $newFormDisplayName)
    {
        $existingForm = $this->db->getInlineDonationFormByName($newFormName);

        if ($existingForm === null) {
            $form = $this->db->getInlineDonationFormAsArrayById($id);

            unset($form['donationFormID']);
            $form['name'] = $newFormName;
            $form['displayName'] = $newFormDisplayName;

            $this->db->insertInlineDonationForm($form);

            $newForm = $this->db->getInlineDonationFormByName($newFormName);
            return $newForm->donationFormID;
        } else {
            throw new WPFS_UserFriendlyException(
                /* translators: Error message for not being able to clone a form
                   p1: form name
                */
                __("Cannot clone form because a form with id '{$newFormName}' already exists.", 'wp-full-stripe-admin')
            );
        }
    }

    /**
     * @param $id
     * @param $type
     * @param $layout
     * @param $newFormName
     * @param $newFormDisplayName
     * @return string|null
     *
     * @throws WPFS_UserFriendlyException|Exception
     */
    public function cloneForm($id, $type, $layout, $newFormName, $newFormDisplayName)
    {
        $newId = null;

        if (
            MM_WPFS::FORM_TYPE_PAYMENT === $type &&
            MM_WPFS::FORM_LAYOUT_INLINE === $layout
        ) {
            $newId = $this->cloneInlinePaymentForm($id, $newFormName, $newFormDisplayName);
        } elseif (
            MM_WPFS::FORM_TYPE_PAYMENT === $type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $layout
        ) {
            $newId = $this->cloneCheckoutPaymentForm($id, $newFormName, $newFormDisplayName);
        } elseif (
            MM_WPFS::FORM_TYPE_SUBSCRIPTION === $type &&
            MM_WPFS::FORM_LAYOUT_INLINE === $layout
        ) {
            $newId = $this->cloneInlineSubscriptionForm($id, $newFormName, $newFormDisplayName);
        } elseif (
            MM_WPFS::FORM_TYPE_SUBSCRIPTION === $type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $layout
        ) {
            $newId = $this->cloneCheckoutSubscriptionForm($id, $newFormName, $newFormDisplayName);
        } elseif (
            MM_WPFS::FORM_TYPE_DONATION === $type &&
            MM_WPFS::FORM_LAYOUT_INLINE === $layout
        ) {
            $newId = $this->cloneInlineDonationForm($id, $newFormName, $newFormDisplayName);
        } elseif (
            MM_WPFS::FORM_TYPE_DONATION === $type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $layout
        ) {
            $newId = $this->cloneCheckoutDonationForm($id, $newFormName, $newFormDisplayName);
        } elseif (
            MM_WPFS::FORM_TYPE_SAVE_CARD === $type &&
            MM_WPFS::FORM_LAYOUT_INLINE === $layout
        ) {
            $newId = $this->cloneInlinePaymentForm($id, $newFormName, $newFormDisplayName);
        } elseif (
            MM_WPFS::FORM_TYPE_SAVE_CARD === $type &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $layout
        ) {
            $newId = $this->cloneCheckoutPaymentForm($id, $newFormName, $newFormDisplayName);
        }

        return $newId;
    }
}


class MM_WPFS_Admin_CreateFormFactory
{

    const CHARGE_TYPE_IMMEDIATE = 'immediate';
    const AMOUNT_10_USD = 1000;
    const CURRENCY_USD = 'usd';
    const CUSTOM_AMOUNT_LIST_OF_AMOUNTS = 'list_of_amounts';
    const CUSTOM_AMOUNT_SPECIFIED_AMOUNT = 'specified_amount';
    const CUSTOM_AMOUNT_SAVE_CARD = 'card_capture';
    const AMOUNT_SELECTOR_STYLE_DROPDOWN = 'dropdown';
    const PREFERRED_LANGUAGE_AUTO = 'auto';
    const DECIMAL_SEPARATOR_DOT = 'dot';
    const VAT_RATE_TYPE_NO_VAT = 'no_vat';
    const PLAN_SELECTOR_STYLE_DROPDOWN = 'dropdown';

    /** @var MM_WPFS_Database */
    private $db = null;

    public function __construct()
    {
        $this->db = new MM_WPFS_Database();
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return array
     */
    private function compileInlinePaymentFormData($displayName, $name)
    {
        $form = array();

        $form['name'] = $name;
        $form['displayName'] = $displayName;
        $form['formTitle'] = '';
        $form['chargeType'] = self::CHARGE_TYPE_IMMEDIATE;
        $form['amount'] = self::AMOUNT_10_USD;
        $form['currency'] = self::CURRENCY_USD;
        $form['customAmount'] = self::CUSTOM_AMOUNT_LIST_OF_AMOUNTS;
        $form['amountSelectorStyle'] = self::AMOUNT_SELECTOR_STYLE_DROPDOWN;
        $form['vatRateType'] = MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX;
        $form['vatRates'] = json_encode(array());
        $form['buttonTitle'] = MM_WPFS_Utils::getDefaultPaymentButtonTitle();
        $form['customInputTitle'] = '';
        $form['stripeDescription'] = MM_WPFS_Utils::getDefaultPaymentStripeDescription();
        $form['termsOfUseLabel'] = MM_WPFS_Utils::getDefaultTermsOfUseLabel();
        $form['termsOfUseNotCheckedErrorMessage'] = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
        $form['preferredLanguage'] = self::PREFERRED_LANGUAGE_AUTO;
        $form['decimalSeparator'] = self::DECIMAL_SEPARATOR_DOT;

        return $form;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return string
     * @throws Exception
     */
    private function createInlinePaymentForm($displayName, $name)
    {
        $form = $this->compileInlinePaymentFormData($displayName, $name);
        $this->db->insertInlinePaymentForm($form);

        $res = $this->db->getInlinePaymentFormByName($name);
        return $res->paymentFormID;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return array
     */
    private function compileCheckoutPaymentFormData($displayName, $name)
    {
        $form = array();

        $form['name'] = $name;
        $form['displayName'] = $displayName;
        $form['companyName'] = '';
        $form['productDesc'] = MM_WPFS_Utils::getDefaultProductDescription();
        $form['chargeType'] = self::CHARGE_TYPE_IMMEDIATE;
        $form['amount'] = self::AMOUNT_10_USD;
        $form['currency'] = self::CURRENCY_USD;
        $form['customAmount'] = self::CUSTOM_AMOUNT_LIST_OF_AMOUNTS;
        $form['amountSelectorStyle'] = self::AMOUNT_SELECTOR_STYLE_DROPDOWN;
        $form['openButtonTitle'] = MM_WPFS_Utils::getDefaultPaymentOpenButtonTitle();
        $form['buttonTitle'] = MM_WPFS_Utils::getDefaultPaymentButtonTitle();
        $form['customInputTitle'] = '';
        $form['stripeDescription'] = MM_WPFS_Utils::getDefaultPaymentStripeDescription();
        $form['termsOfUseLabel'] = MM_WPFS_Utils::getDefaultTermsOfUseLabel();
        $form['termsOfUseNotCheckedErrorMessage'] = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
        $form['preferredLanguage'] = self::PREFERRED_LANGUAGE_AUTO;
        $form['decimalSeparator'] = self::DECIMAL_SEPARATOR_DOT;

        return $form;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return string
     * @throws Exception
     */
    private function createCheckoutPaymentForm($displayName, $name)
    {
        $form = $this->compileCheckoutPaymentFormData($displayName, $name);
        $this->db->insertCheckoutPaymentForm($form);

        $res = $this->db->getCheckoutPaymentFormByName($name);
        return $res->checkoutFormID;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return array
     */
    private function compileInlineSubscriptionFormData($displayName, $name)
    {
        $form = array();

        $form['name'] = $name;
        $form['displayName'] = $displayName;
        $form['formTitle'] = '';
        $form['customInputTitle'] = '';
        $form['buttonTitle'] = MM_WPFS_Utils::getDefaultSubscriptionButtonTitle();
        $form['termsOfUseLabel'] = MM_WPFS_Utils::getDefaultTermsOfUseLabel();
        $form['termsOfUseNotCheckedErrorMessage'] = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
        $form['planSelectorStyle'] = self::PLAN_SELECTOR_STYLE_DROPDOWN;
        $form['preferredLanguage'] = self::PREFERRED_LANGUAGE_AUTO;
        $form['decimalSeparator'] = self::DECIMAL_SEPARATOR_DOT;
        $form['decoratedPlans'] = json_encode(array());
        $form['vatRateType'] = MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX;
        $form['vatRates'] = json_encode(array());

        return $form;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return string
     * @throws Exception
     */
    private function createInlineSubscriptionForm($displayName, $name)
    {
        $form = $this->compileInlineSubscriptionFormData($displayName, $name);
        $this->db->insertInlineSubscriptionForm($form);

        $res = $this->db->getInlineSubscriptionFormByName($name);
        return $res->subscriptionFormID;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return array
     */
    private function compileCheckoutSubscriptionFormData($displayName, $name)
    {
        $form = array();

        $form['name'] = $name;
        $form['displayName'] = $displayName;
        $form['companyName'] = '';
        $form['productDesc'] = MM_WPFS_Utils::getDefaultProductDescription();
        $form['customInputTitle'] = '';
        $form['buttonTitle'] = MM_WPFS_Utils::getDefaultSubscriptionButtonTitle();
        $form['openButtonTitle'] = MM_WPFS_Utils::getDefaultSubscriptionOpenButtonTitle();
        $form['termsOfUseLabel'] = MM_WPFS_Utils::getDefaultTermsOfUseLabel();
        $form['termsOfUseNotCheckedErrorMessage'] = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
        $form['planSelectorStyle'] = self::PLAN_SELECTOR_STYLE_DROPDOWN;
        $form['preferredLanguage'] = self::PREFERRED_LANGUAGE_AUTO;
        $form['decimalSeparator'] = self::DECIMAL_SEPARATOR_DOT;
        $form['decoratedPlans'] = json_encode(array());
        $form['vatRateType'] = MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX;
        $form['vatRates'] = json_encode(array());

        return $form;
    }

    /**
     * @param $displayName string
     * @param $name string
     * @return string
     * @throws Exception
     */
    private function createCheckoutSubscriptionForm($displayName, $name)
    {
        $form = $this->compileCheckoutSubscriptionFormData($displayName, $name);
        $this->db->insertCheckoutSubscriptionForm($form);

        $res = $this->db->getCheckoutSubscriptionFormByName($name);
        return $res->checkoutSubscriptionFormID;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return array
     */
    private function compileInlineDonationFormData($displayName, $name)
    {
        $form = array();

        $form['name'] = $name;
        $form['displayName'] = $displayName;
        $form['currency'] = self::CURRENCY_USD;
        $form['donationAmounts'] = json_encode(array('100', '200', '500', '1000', '2000', '5000', '10000'));
        $form['allowCustomDonationAmount'] = 1;
        $form['allowMonthlyRecurring'] = 1;
        $form['allowAnnualRecurring'] = 1;
        $form['stripeDescription'] = MM_WPFS_Utils::getDefaultDonationDescription();
        $form['buttonTitle'] = MM_WPFS_Utils::getDefaultDonationButtonTitle();
        $form['preferredLanguage'] = self::PREFERRED_LANGUAGE_AUTO;
        $form['decimalSeparator'] = self::DECIMAL_SEPARATOR_DOT;
        $form['termsOfUseLabel'] = MM_WPFS_Utils::getDefaultTermsOfUseLabel();
        $form['termsOfUseNotCheckedErrorMessage'] = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
        $form['customInputTitle'] = '';

        return $form;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return string
     * @throws Exception
     */
    private function createInlineDonationForm($displayName, $name)
    {
        $form = $this->compileInlineDonationFormData($displayName, $name);
        $this->db->insertInlineDonationForm($form);

        $res = $this->db->getInlineDonationFormByName($name);
        return $res->donationFormID;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return array
     */
    private function compileCheckoutDonationFormData($displayName, $name)
    {
        $form = array();

        $form['name'] = $name;
        $form['displayName'] = $displayName;
        $form['currency'] = self::CURRENCY_USD;
        $form['donationAmounts'] = json_encode(array('100', '200', '500', '1000', '2000', '5000', '10000'));
        $form['allowCustomDonationAmount'] = 1;
        $form['allowMonthlyRecurring'] = 1;
        $form['allowAnnualRecurring'] = 1;
        $form['stripeDescription'] = MM_WPFS_Utils::getDefaultDonationDescription();
        $form['companyName'] = '';
        $form['productDesc'] = MM_WPFS_Utils::getDefaultDonationProductDescription();
        $form['openButtonTitle'] = MM_WPFS_Utils::getDefaultDonationOpenButtonTitle();
        $form['buttonTitle'] = MM_WPFS_Utils::getDefaultDonationButtonTitle();
        $form['preferredLanguage'] = self::PREFERRED_LANGUAGE_AUTO;
        $form['decimalSeparator'] = self::DECIMAL_SEPARATOR_DOT;
        $form['termsOfUseLabel'] = MM_WPFS_Utils::getDefaultTermsOfUseLabel();
        $form['termsOfUseNotCheckedErrorMessage'] = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
        $form['customInputTitle'] = '';

        return $form;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return string
     * @throws Exception
     */
    private function createCheckoutDonationForm($displayName, $name)
    {
        $form = $this->compileCheckoutDonationFormData($displayName, $name);
        $this->db->insertCheckoutDonationForm($form);

        $res = $this->db->getCheckoutDonationFormByName($name);
        return $res->checkoutDonationFormID;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return array
     */
    private function compileInlineSaveCardFormData($displayName, $name)
    {
        $form = array();

        $form['name'] = $name;
        $form['displayName'] = $displayName;
        $form['formTitle'] = '';
        $form['chargeType'] = self::CHARGE_TYPE_IMMEDIATE;
        $form['amount'] = 0;
        $form['currency'] = self::CURRENCY_USD;
        $form['customAmount'] = self::CUSTOM_AMOUNT_SAVE_CARD;
        $form['amountSelectorStyle'] = self::AMOUNT_SELECTOR_STYLE_DROPDOWN;
        $form['buttonTitle'] = MM_WPFS_Utils::getDefaultSaveCardButtonTitle();
        $form['customInputTitle'] = '';
        $form['stripeDescription'] = MM_WPFS_Utils::getDefaultSaveCardDescription();
        $form['termsOfUseLabel'] = MM_WPFS_Utils::getDefaultTermsOfUseLabel();
        $form['termsOfUseNotCheckedErrorMessage'] = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
        $form['preferredLanguage'] = self::PREFERRED_LANGUAGE_AUTO;
        $form['decimalSeparator'] = self::DECIMAL_SEPARATOR_DOT;

        return $form;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return string
     * @throws Exception
     */
    private function createInlineSaveCardForm($displayName, $name)
    {
        $form = $this->compileInlineSaveCardFormData($displayName, $name);
        $this->db->insertInlinePaymentForm($form);

        $res = $this->db->getInlinePaymentFormByName($name);
        return $res->paymentFormID;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return array
     */
    private function compileCheckoutSaveCardFormData($displayName, $name)
    {
        $form = array();

        $form['name'] = $name;
        $form['displayName'] = $displayName;
        $form['companyName'] = '';
        $form['productDesc'] = MM_WPFS_Utils::getDefaultProductDescription();
        $form['chargeType'] = self::CHARGE_TYPE_IMMEDIATE;
        $form['amount'] = self::AMOUNT_10_USD;
        $form['currency'] = self::CURRENCY_USD;
        $form['customAmount'] = self::CUSTOM_AMOUNT_SAVE_CARD;
        $form['amountSelectorStyle'] = self::AMOUNT_SELECTOR_STYLE_DROPDOWN;
        $form['openButtonTitle'] = MM_WPFS_Utils::getDefaultSaveCardButtonTitle();
        $form['buttonTitle'] = MM_WPFS_Utils::getDefaultSaveCardButtonTitle();
        $form['customInputTitle'] = '';
        $form['stripeDescription'] = MM_WPFS_Utils::getDefaultSaveCardDescription();
        $form['termsOfUseLabel'] = MM_WPFS_Utils::getDefaultTermsOfUseLabel();
        $form['termsOfUseNotCheckedErrorMessage'] = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
        $form['preferredLanguage'] = self::PREFERRED_LANGUAGE_AUTO;
        $form['decimalSeparator'] = self::DECIMAL_SEPARATOR_DOT;

        return $form;
    }

    /**
     * @param $displayName string
     * @param $name string
     *
     * @return string
     * @throws Exception
     */
    private function createCheckoutSaveCardForm($displayName, $name)
    {
        $form = $this->compileCheckoutSaveCardFormData($displayName, $name);
        $this->db->insertCheckoutPaymentForm($form);

        $res = $this->db->getCheckoutPaymentFormByName($name);
        return $res->checkoutFormID;
    }

    /**
     * @param $createFormModel MM_WPFS_Admin_CreateFormModel
     * @return string|null
     *
     * @throws Exception
     */
    public function createForm($createFormModel)
    {
        $formId = null;

        if (
            MM_WPFS::FORM_TYPE_PAYMENT === $createFormModel->getType() &&
            MM_WPFS::FORM_LAYOUT_INLINE === $createFormModel->getLayout()
        ) {
            $formId = $this->createInlinePaymentForm($createFormModel->getDisplayName(), $createFormModel->getName());
        } elseif (
            MM_WPFS::FORM_TYPE_PAYMENT === $createFormModel->getType() &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $createFormModel->getLayout()
        ) {
            $formId = $this->createCheckoutPaymentForm($createFormModel->getDisplayName(), $createFormModel->getName());
        } elseif (
            MM_WPFS::FORM_TYPE_SUBSCRIPTION === $createFormModel->getType() &&
            MM_WPFS::FORM_LAYOUT_INLINE === $createFormModel->getLayout()
        ) {
            $formId = $this->createInlineSubscriptionForm($createFormModel->getDisplayName(), $createFormModel->getName());
        } elseif (
            MM_WPFS::FORM_TYPE_SUBSCRIPTION === $createFormModel->getType() &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $createFormModel->getLayout()
        ) {
            $formId = $this->createCheckoutSubscriptionForm($createFormModel->getDisplayName(), $createFormModel->getName());
        } elseif (
            MM_WPFS::FORM_TYPE_DONATION === $createFormModel->getType() &&
            MM_WPFS::FORM_LAYOUT_INLINE === $createFormModel->getLayout()
        ) {
            $formId = $this->createInlineDonationForm($createFormModel->getDisplayName(), $createFormModel->getName());
        } elseif (
            MM_WPFS::FORM_TYPE_DONATION === $createFormModel->getType() &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $createFormModel->getLayout()
        ) {
            $formId = $this->createCheckoutDonationForm($createFormModel->getDisplayName(), $createFormModel->getName());
        } elseif (
            MM_WPFS::FORM_TYPE_SAVE_CARD === $createFormModel->getType() &&
            MM_WPFS::FORM_LAYOUT_INLINE === $createFormModel->getLayout()
        ) {
            $formId = $this->createInlineSaveCardForm($createFormModel->getDisplayName(), $createFormModel->getName());
        } elseif (
            MM_WPFS::FORM_TYPE_SAVE_CARD === $createFormModel->getType() &&
            MM_WPFS::FORM_LAYOUT_CHECKOUT === $createFormModel->getLayout()
        ) {
            $formId = $this->createCheckoutSaveCardForm($createFormModel->getDisplayName(), $createFormModel->getName());
        } else {
            throw new Exception("Unsupported form type");
        }

        return $formId;
    }
}

class MacroHelperTools
{
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public static function getMacroDescriptions(): array
    {
        $macros = array(
            '%CUSTOMERNAME%' => __("Customer's cardholder name", 'wp-full-stripe-admin'),
            '%CUSTOMER_EMAIL%' => __("Customer's email address", 'wp-full-stripe-admin'),
            '%CUSTOMER_PHONE%' => __("Customer's phone number", 'wp-full-stripe-admin'),
            '%CUSTOMER_TAX_ID%' => __("Customer's tax id", 'wp-full-stripe-admin'),
            '%STRIPE_CUSTOMER_ID%' => __("Identifier of the Stripe customer", 'wp-full-stripe-admin'),
            '%NAME%' => __("Name of your WordPress site", 'wp-full-stripe-admin'),
            '%FORM_NAME%' => __("Name of the form used to make the transaction", 'wp-full-stripe-admin'),
            '%DATE%' => __("Current date", 'wp-full-stripe-admin'),
            '%TRANSACTION_ID%' => __("Identifier of the Stripe object created in the transaction", 'wp-full-stripe-admin'),
            '%AMOUNT%' => __("Gross payment amount", 'wp-full-stripe-admin'),
            '%PRODUCT_NAME%' => __("Name of product purchased", 'wp-full-stripe-admin'),
            '%PRODUCT_AMOUNT_GROSS%' => __("Gross amount of product purchased", 'wp-full-stripe-admin'),
            '%PRODUCT_AMOUNT_TAX%' => __("Tax of product purchased", 'wp-full-stripe-admin'),
            '%PRODUCT_AMOUNT_NET%' => __("Net amount of product purchased", 'wp-full-stripe-admin'),
            '%PLAN_NAME%' => __("Name of the subscription plan", 'wp-full-stripe-admin'),
            '%PLAN_QUANTITY%' => __("The number of subscription plans purchased", 'wp-full-stripe-admin'),
            '%PLAN_AMOUNT%' => __("Gross amount of the subscription plan", 'wp-full-stripe-admin'),
            '%PLAN_AMOUNT_TOTAL%' => __("Gross subscription amount (= PLAN_AMOUNT × PLAN_QUANTITY)", 'wp-full-stripe-admin'),
            '%PLAN_AMOUNT_GROSS%' => __("Gross amount of the subscription plan", 'wp-full-stripe-admin'),
            '%PLAN_AMOUNT_GROSS_TOTAL%' => __("Gross subscription amount (= PLAN_AMOUNT_GROSS × PLAN_QUANTITY)", 'wp-full-stripe-admin'),
            '%PLAN_AMOUNT_NET%' => __("Net amount of the subscription plan", 'wp-full-stripe-admin'),
            '%PLAN_AMOUNT_NET_TOTAL%' => __("Net subscription amount (= PLAN_AMOUNT_NET × PLAN_QUANTITY)", 'wp-full-stripe-admin'),
            '%PLAN_AMOUNT_VAT%' => __("VAT amount of the subscription plan", 'wp-full-stripe-admin'),
            '%PLAN_AMOUNT_VAT_TOTAL%' => __("VAT amount of the subscription (= PLAN_AMOUNT_VAT × PLAN_QUANTITY)", 'wp-full-stripe-admin'),
            '%PLAN_FUTURE_AMOUNT_NET%' => __("Net amount of the subscription plan after trial", 'wp-full-stripe-admin'),
            '%PLAN_FUTURE_AMOUNT_VAT%' => __("VAT amount of the subscription plan after trial", 'wp-full-stripe-admin'),
            '%PLAN_FUTURE_AMOUNT_GROSS%' => __("Gross amount of the subscription plan after trial", 'wp-full-stripe-admin'),
            '%SETUP_FEE%' => __("Gross setup fee of the subscription plan", 'wp-full-stripe-admin'),
            '%SETUP_FEE_TOTAL%' => __("Gross setup fee (= SETUP_FEE × PLAN_QUANTITY)", 'wp-full-stripe-admin'),
            '%SETUP_FEE_GROSS%' => __("Gross setup fee of the subscription plan", 'wp-full-stripe-admin'),
            '%SETUP_FEE_GROSS_TOTAL%' => __("Gross setup fee (= SETUP_FEE × PLAN_QUANTITY)", 'wp-full-stripe-admin'),
            '%SETUP_FEE_NET%' => __("Net setup fee of the subscription plan", 'wp-full-stripe-admin'),
            '%SETUP_FEE_NET_TOTAL%' => __("Net setup fee (= SETUP_FEE_NET × PLAN_QUANTITY)", 'wp-full-stripe-admin'),
            '%SETUP_FEE_VAT%' => __("VAT amount of the subscription plan’s setup fee", 'wp-full-stripe-admin'),
            '%SETUP_FEE_VAT_TOTAL%' => __("VAT amount of the subscription’s setup fee (= SETUP_FEE_VAT × PLAN_QUANTITY)", 'wp-full-stripe-admin'),
            '%INVOICE_URL%' => __("Link to the downloadable PDF invoice of the payment", 'wp-full-stripe-admin'),
            '%INVOICE_NUMBER%' => __("Invoice number of the payment", 'wp-full-stripe-admin'),
            '%RECEIPT_URL%' => __("Link to the downloadable PDF receipt of the payment", 'wp-full-stripe-admin'),
            '%BILLING_NAME%' => __("Billing name", 'wp-full-stripe-admin'),
            '%ADDRESS1%' => __("Billing address line 1", 'wp-full-stripe-admin'),
            '%ADDRESS2%' => __("Billing address line 2", 'wp-full-stripe-admin'),
            '%CITY%' => __("Billing address city", 'wp-full-stripe-admin'),
            '%STATE%' => __("Billing address state", 'wp-full-stripe-admin'),
            '%ZIP%' => __("Billing address zip (or postal) code", 'wp-full-stripe-admin'),
            '%COUNTRY%' => __("Billing address country", 'wp-full-stripe-admin'),
            '%COUNTRY_CODE%' => __("ISO code of the billing address country", 'wp-full-stripe-admin'),
            '%SHIPPING_NAME%' => __("Shipping name", 'wp-full-stripe-admin'),
            '%SHIPPING_ADDRESS1%' => __("Shipping address line 1", 'wp-full-stripe-admin'),
            '%SHIPPING_ADDRESS2%' => __("Shipping address line 2", 'wp-full-stripe-admin'),
            '%SHIPPING_CITY%' => __("Shipping address city", 'wp-full-stripe-admin'),
            '%SHIPPING_STATE%' => __("Shipping address state", 'wp-full-stripe-admin'),
            '%SHIPPING_ZIP%' => __("Shipping address zip (or postal) code", 'wp-full-stripe-admin'),
            '%SHIPPING_COUNTRY%' => __("Shipping address country", 'wp-full-stripe-admin'),
            '%SHIPPING_COUNTRY_CODE%' => __("ISO code of the shipping address country", 'wp-full-stripe-admin'),
            '%COUPON_CODE%' => __("Coupon code redeemed on the form", 'wp-full-stripe-admin'),
            '%DONATION_FREQUENCY%' => __("Donation frequency (one-time, daily, weekly, monthly, or annual)", 'wp-full-stripe-admin'),
            '%CUSTOMFIELD1%' => __("Custom field 1 value", 'wp-full-stripe-admin'),
            '%CARD_UPDATE_SECURITY_CODE%' => __("Login code generated by the Customer Portal page", 'wp-full-stripe-admin'),
            '%IP_ADDRESS%' => __("Customer's IP address", 'wp-full-stripe-admin')
        );

        return $macros;
    }
}
