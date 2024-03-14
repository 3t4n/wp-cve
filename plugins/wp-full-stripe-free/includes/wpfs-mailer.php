<?php
/** @noinspection PhpMultipleClassesDeclarationsInOneFile */

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.02.26.
 * Time: 14:16
 */
class MM_WPFS_Mailer {
    use MM_WPFS_Logger_AddOn;

    public function __construct( $loggerService ) {
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME );
    }

    public static function generateSenderStringFromNameAndEmail( $name, $email ) {
        return "{$name} <{$email}>";
    }

    /**
     * @param $form
     * @param $templateType string
     */
    private static function isEmailTemplateEnabled( $form, $templateType ) {
        $isEnabled = false;
        $templates = json_decode( $form->emailTemplates );
        if ( !is_null( $templates )) {
            $isEnabled = $templates->{$templateType}->enabled;
        }

        return $isEnabled;
    }

    /**
     * @param $form
     */
    public static function canSendSaveCardPluginReceipt($form ) {
        return self::isEmailTemplateEnabled( $form, MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED );
    }

    /**
     * @param $form
     */
    public static function canSendDonationPluginReceipt( $form ) {
        return self::isEmailTemplateEnabled( $form, MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT );
    }

    /**
     * @param $form
     */
    public static function canSendDonationStripeReceipt( $form ) {
        return self::isEmailTemplateEnabled( $form, MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT_STRIPE );
    }

    /**
     * @param $form
     */
    public static function canSendSubscriptionPluginReceipt( $form ) {
        return self::isEmailTemplateEnabled( $form, MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT );
    }

    /**
     * @param $form
     */
    public static function canSendSubscriptionStripeReceipt( $form ) {
        return self::isEmailTemplateEnabled( $form, MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT_STRIPE );
    }

    /**
     * @param $form
     */
    public static function canSendSubscriptionEndedPluginNotification( $form ) {
        return self::isEmailTemplateEnabled( $form, MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED );
    }

    /**
     * @param $form
     */
    public static function canSendPaymentPluginReceipt( $form ) {
        return self::isEmailTemplateEnabled( $form, MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT );
    }

    /**
     * @param $form
     */
    public static function canSendPaymentStripeReceipt( $form ) {
        return self::isEmailTemplateEnabled( $form, MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT_STRIPE );
    }

    /**
     * @param $formType
     *
     * @return array
     */
    public static function getEmailTemplateDescriptors($formType ) {
        $res = array();

        switch ( $formType ) {
            case MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD:
            case MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD:
                $saveCardReceipt = new \StdClass();
                $saveCardReceipt->type              = MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED;
                $saveCardReceipt->typeLabel         =
                    /* translators: Name of the email template that is used to send an email when a card is saved */
                    __('Card saved', 'wp-full-stripe-admin');
                $saveCardReceipt->typeDescription   =
                    /* translators: Description of the 'Card saved' email template */
                    __('The plugin sends this email when a customer submits a save card form.', 'wp-full-stripe-admin');
                array_push( $res, $saveCardReceipt );

                break;

            case MM_WPFS::FORM_TYPE_INLINE_DONATION:
            case MM_WPFS::FORM_TYPE_CHECKOUT_DONATION:
                $donationReceipt = new \StdClass();
                $donationReceipt->type              = MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT;
                $donationReceipt->typeLabel         =
                    /* translators: Name of the email template that is used to send an email when a donation is made */
                    __('Donation receipt', 'wp-full-stripe-admin');
                $donationReceipt->typeDescription   =
                    /* translators: Description of the 'Donation receipt' email template */
                    __('The plugin sends this email when a donor makes a donation.', 'wp-full-stripe-admin');
                array_push( $res, $donationReceipt );

                $donationReceiptStripe = new \StdClass();
                $donationReceiptStripe->type              = MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT_STRIPE;
                $donationReceiptStripe->typeLabel         =
                    /* translators: Name of the email template that Stripe sends when a donation is made */
                    __('Donation receipt (Stripe)', 'wp-full-stripe-admin');
                $donationReceiptStripe->typeDescription   =
                    /* translators: Description of the 'Donation receipt (Stripe)' email template */
                    __('Stripe sends a payment receipt when a donor makes a donation.', 'wp-full-stripe-admin');
                array_push( $res, $donationReceiptStripe );

                break;

            case MM_WPFS::FORM_TYPE_INLINE_PAYMENT:
            case MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT:
                $paymentReceipt = new \StdClass();
                $paymentReceipt->type              = MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT;
                $paymentReceipt->typeLabel         =
                    /* translators: Name of the email template that is used to send an email when a payment is made */
                    __('Payment receipt', 'wp-full-stripe-admin');
                $paymentReceipt->typeDescription   =
                    /* translators: Description of the 'Payment receipt' email template */
                    __('The plugin sends this email when a customer makes a one-time payment.', 'wp-full-stripe-admin');
                array_push( $res, $paymentReceipt );

                $paymentReceiptStripe = new \StdClass();
                $paymentReceiptStripe->type              = MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT_STRIPE;
                $paymentReceiptStripe->typeLabel         =
                    /* translators: Name of the email template that Stripe sends when a payment is made */
                    __('Payment receipt (Stripe)', 'wp-full-stripe-admin');
                $paymentReceiptStripe->typeDescription   =
                    /* translators: Description of the 'Payment receipt (Stripe)' email template */
                    __('Stripe sends this payment receipt when a customer makes a payment.', 'wp-full-stripe-admin');
                array_push( $res, $paymentReceiptStripe );

                break;

            case MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION:
            case MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION:
                $subscriptionReceipt = new \StdClass();
                $subscriptionReceipt->type              = MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT;
                $subscriptionReceipt->typeLabel         =
                    /* translators: Name of the email template that is used to send an email when a subscription is started */
                    __('Subscription receipt', 'wp-full-stripe-admin');
                $subscriptionReceipt->typeDescription   =
                    /* translators: Description of the 'Subscription receipt' email template */
                    __('The plugin sends this email when a customer subscribes to a plan.', 'wp-full-stripe-admin');
                array_push( $res, $subscriptionReceipt );

                $subscriptionEndedReceipt = new \StdClass();
                $subscriptionEndedReceipt->type              = MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED;
                $subscriptionEndedReceipt->typeLabel         =
                    /* translators: Name of the email template that is used to send an email when the plugin ends a subscription automatically */
                    __('Subscription ended', 'wp-full-stripe-admin');
                $subscriptionEndedReceipt->typeDescription   =
                    /* translators: Description of the 'Subscription ended' email template */
                    __('The plugin sends this email when payment-in-installments plan is cancelled automatically.', 'wp-full-stripe-admin');
                array_push( $res, $subscriptionEndedReceipt );

                $subscriptionReceiptStripe = new \StdClass();
                $subscriptionReceiptStripe->type              = MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT_STRIPE;
                $subscriptionReceiptStripe->typeLabel         =
                    /* translators: Name of the email template that Stripe sends when a subscription is started */
                    __('Subscription receipt (Stripe)', 'wp-full-stripe-admin');
                $subscriptionReceiptStripe->typeDescription   =
                    /* translators: Description of the 'Subscription receipt (Stripe)' email template */
                    __('Stripe sends a payment receipt when a customer subscribes to a plan.', 'wp-full-stripe-admin');
                array_push( $res, $subscriptionReceiptStripe );

            break;

        }

        return $res;
    }

    /**
     * @param $context MM_WPFS_StaticContext
     * @param $templateType
     * @return bool
     */
    public static function isTemplateEnabled( $context, $templateType ) {
        // todo: This option is not used anymore, remove all traces of it
        $sendPluginEmail = $context->getOptions()->get( MM_WPFS_Options::OPTION_RECEIPT_EMAIL_TYPE ) === MM_WPFS_Options::OPTION_VALUE_RECEIPT_EMAIL_PLUGIN;

        switch ( $templateType ) {
            case MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED:
            case MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT:
            case MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT:
            case MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT:
            case MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED:
                $templateEnabled = $sendPluginEmail;
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT_STRIPE:
            case MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT_STRIPE:
            case MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT_STRIPE:
                $templateEnabled = ! $sendPluginEmail;
                break;
        }

        return $templateEnabled;
    }

    /**
     * @param $context MM_WPFS_StaticContext
     * @param $formType
     * @param $sendEmail
     * @param false $returnObject
     *
     * @return array|false|string
     */
    public static function createDefaultEmailTemplates( $context, $formType, $sendEmail, $returnObject = false ) {
        $templateDescriptors = MM_WPFS_Mailer::getEmailTemplateDescriptors( $formType );
        $emailTemplates      = new \StdClass;

        foreach( $templateDescriptors as $currentDescriptor ) {
            $isTemplateEnabled = $sendEmail === true ? self::isTemplateEnabled( $context, $currentDescriptor->type ) : false;

            $defaultLanguage = new \StdClass;
            $defaultLanguage->subject = '';
            $defaultLanguage->body = '';

            $content = new \StdClass;
            $content->default = $defaultLanguage;

            $emailTemplate = new \StdClass;
            $emailTemplate->enabled = $isTemplateEnabled;
            $emailTemplate->senderName = '';
            $emailTemplate->senderAddress = '';
            $emailTemplate->receiverAddresses = array();
            $emailTemplate->content = $content;

            $emailTemplates->{$currentDescriptor->type} = $emailTemplate;
        }

        if ( $returnObject ) {
            return $emailTemplates;
        } else {
            return json_encode( $emailTemplates );
        }
    }

    /**
     * @param $context MM_WPFS_StaticContext
     * @param $formType
     * @param $emailTemplatesJson
     * @return array|false|mixed|StdClass|string
     */
    public static function extractEmailTemplates( $context, $formType, $emailTemplatesJson ) {
        $emailTemplates = json_decode( $emailTemplatesJson );
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            $emailTemplates = MM_WPFS_Mailer::createDefaultEmailTemplates( $context, $formType, false, true );
        }

        return $emailTemplates;
    }

    public static function updateMissingEmailTemplatesWithDefaults( &$emailTemplates ) {
        if (!property_exists($emailTemplates, MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT)) {
            $emailTemplates->paymentMade = MM_WPFS_Mailer::createDefaultPaymentReceiptTemplate();
        }
        if (!property_exists($emailTemplates, MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT)) {
            $emailTemplates->subscriptionStarted = MM_WPFS_Mailer::createDefaultSubscriptionReceiptTemplate();
        }
        if (!property_exists($emailTemplates, MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED)) {
            $emailTemplates->subscriptionFinished = MM_WPFS_Mailer::createDefaultSubscriptionEndedTemplate();
        }
        if (!property_exists($emailTemplates, MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT)) {
            $emailTemplates->donationMade = MM_WPFS_Mailer::createDefaultDonationReceiptTemplate();
        }
        if (!property_exists($emailTemplates, MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED)) {
            $emailTemplates->cardCaptured = MM_WPFS_Mailer::createDefaultCardSavedTemplate();
        }
        if (!property_exists($emailTemplates, MM_WPFS::EMAIL_TEMPLATE_ID_CUSTOMER_PORTAL_SECURITY_CODE)) {
            $emailTemplates->cardUpdateConfirmationRequest = MM_WPFS_Mailer::createDefaultCustomerPortalSecurityCodeTemplate();
        }

        $emailTemplates = apply_filters('fullstripe_email_template_defaults', $emailTemplates );
    }

    /**
     * Constructs an \StdClass with the default email receipt templates.
     *
     * @return \StdClass
     */
    public static function getDefaultEmailTemplates() {
        $emailTemplates = new \StdClass;
        self::updateMissingEmailTemplatesWithDefaults( $emailTemplates );

        return $emailTemplates;
    }

    /**
     * @param $options MM_WPFS_Options
     * @return void
     */
    public static function updateDefaultEmailTemplatesInOptions( $options ) {
        $emailReceipts = json_decode( $options->get( MM_WPFS_Options::OPTION_EMAIL_TEMPLATES ));
        MM_WPFS_Mailer::updateMissingEmailTemplatesWithDefaults($emailReceipts);
        $options->set( MM_WPFS_Options::OPTION_EMAIL_TEMPLATES, json_encode( $emailReceipts ));
    }

    /**
     * @return stdClass
     */
    public static function createDefaultSubscriptionEndedTemplate() {
        $subscriptionFinished = new stdClass();
        $subscriptionFinished->subject = 'Subscription Ended';
        $subscriptionFinished->html = '<html><body><p>Hi,</p><p>Your subscription has ended.</p><p>Thanks</p><br/>%NAME%</body></html>';

        return $subscriptionFinished;
    }

    public static function createDefaultCustomerPortalSecurityCodeTemplate() {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $homeUrl = home_url();
        $cardUpdateConfirmationRequest = new stdClass();
        $cardUpdateConfirmationRequest->subject = 'Login code for managing your account';
        $cardUpdateConfirmationRequest->html = '<html>
<body>
<p>Dear %CUSTOMER_EMAIL%,</p>

<p>You are receiving this email because you requested access to the page where you can manage your subscription(s).</p>

<br/>
<table>
    <tr>
        <td><b>Subscription management page:</b></td>
        <td><a href="https://www.example.com/manage-subscription">https://www.example.com/manage-subscription</a></td>
    </tr>
    <tr>
        <td><b>Your security code:</b></td>
        <td>%CARD_UPDATE_SECURITY_CODE%</td>
    </tr>
</table>

<br/>
<p>
    Thanks,<br/>
    %NAME%
</p>
</body>
</html>';

        return $cardUpdateConfirmationRequest;
    }

    /**
	 * @return stdClass
	 */
    public static function createDefaultPaymentReceiptTemplate() {
        $paymentMade = new stdClass();
        $paymentMade->subject = 'Payment Receipt';
        $paymentMade->html = "<html><body><p>Hi,</p><p>Here's your receipt for your payment of %AMOUNT%</p><p>Thanks</p><br/>%NAME%</body></html>";

        return $paymentMade;
    }

    /**
     * @return stdClass
     */
    public static function createDefaultDonationReceiptTemplate() {
        $paymentMade = new stdClass();
        $paymentMade->subject = 'Donation Receipt';
        $paymentMade->html = "<html><body><p>Hi,</p><p>Here's your receipt for your donation of %AMOUNT%</p><p>Thanks</p><br/>%NAME%</body></html>";

        return $paymentMade;
    }

    /**
     * @return stdClass
     */
    public static function createDefaultSubscriptionReceiptTemplate() {
        $subscriptionStarted = new stdClass();
        $subscriptionStarted->subject = 'Subscription Receipt';
        $subscriptionStarted->html = "<html><body><p>Hi,</p><p>Here's your receipt for your subscription of %AMOUNT%</p><p>Thanks</p><br/>%NAME%</body></html>";

        return $subscriptionStarted;
    }

    /**
	 * @return stdClass
	 */
    public static function createDefaultCardSavedTemplate() {
        $cardCaptured = new stdClass();
        $cardCaptured->subject = 'Card Information Saved';
        $cardCaptured->html = '<html><body><p>Hi,</p><p>Your payment information has been saved.</p><p>Thanks</p><br/>%NAME%</body></html>';

        return $cardCaptured;
    }

    /**
     * @param $form
     * @param MM_WPFS_DonationTransactionData $transactionData
     */
    public function sendDonationEmailReceipt( $form, $transactionData ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_DonationReceiptSender( $transactionData, $this->loggerService, $form );
        $sender->sendEmail();
    }

    /**
     * @param $form
     * @param MM_WPFS_DonationTransactionData $transactionData
     * @param $subject
     * @param $body
     */
    public function sendTestDonationEmailReceipt( $form, $transactionData, $subject, $body ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_TestDonationReceiptSender( $transactionData, $this->loggerService, $form );
        $sender->setSubject( $subject );
        $sender->setBody( $body );
        $sender->sendEmail();
    }

    /**
     * @param $form
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData
     */
    public function sendOneTimePaymentReceipt($form, $transactionData ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_OneTimePaymentReceiptSender( $transactionData, $this->loggerService, $form );
        $sender->sendEmail();
    }

    /**
     * @param $form
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData
     * @param $subject
     * @param $body
     */
    public function sendTestOneTimePaymentReceipt( $form, $transactionData, $subject, $body ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_TestOneTimePaymentReceiptSender( $transactionData, $this->loggerService, $form );
        $sender->setSubject( $subject );
        $sender->setBody( $body );
        $sender->sendEmail();
    }

    /**
     * @param $form
     * @param $transactionData MM_WPFS_SaveCardTransactionData
     */
	public function sendSaveCardNotification($form, $transactionData ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_SaveCardNotificationSender( $transactionData, $this->loggerService, $form );
        $sender->sendEmail();
    }

    /**
     * @param $form
     * @param $transactionData MM_WPFS_SaveCardTransactionData
     * @param $subject
     * @param $body
     */
    public function sendTestSaveCardNotification( $form, $transactionData, $subject, $body ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_TestSaveCardNotificationSender( $transactionData, $this->loggerService, $form );
        $sender->setSubject( $subject );
        $sender->setBody( $body );
        $sender->sendEmail();
    }

    /**
	 * @param $form
	 * @param MM_WPFS_SubscriptionTransactionData $transactionData
	 */
	public function sendSubscriptionStartedEmailReceipt( $form, $transactionData ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_SubscriptionEmailReceiptSender( $transactionData, $this->loggerService, $form );
        $sender->sendEmail();
	}

    /**
     * @param $form
     * @param MM_WPFS_SubscriptionTransactionData $transactionData
     * @param $subject
     * @param $body
     */
    public function sendTestSubscriptionStartedEmailReceipt( $form, $transactionData, $subject, $body ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_TestSubscriptionEmailReceiptSender( $transactionData, $this->loggerService, $form );
        $sender->setSubject( $subject );
        $sender->setBody( $body );
        $sender->sendEmail();
    }

    /**
	 * @param $form
	 * @param MM_WPFS_SubscriptionTransactionData $transactionData
	 */
	public function sendSubscriptionFinishedEmailReceipt( $form, $transactionData ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_SubscriptionEndedNotificationSender( $transactionData, $this->loggerService, $form );
        $sender->sendEmail();
	}

    /**
     * @param $form
     * @param MM_WPFS_SubscriptionTransactionData $transactionData
     * @param $subject
     * @param $body
     */
    public function sendTestSubscriptionFinishedEmailReceipt( $form, $transactionData, $subject, $body ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_TestSubscriptionEndedNotificationSender( $transactionData, $this->loggerService, $form );
        $sender->setSubject( $subject );
        $sender->setBody( $body );
        $sender->sendEmail();
    }

    /**
     * @param $transactionData MM_WPFS_MyAccountLoginTransactionData
     */
	public function sendMyAccountLoginRequest( $transactionData ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_MyAccountLoginNotificationSender( $transactionData, $this->loggerService );
        $sender->sendEmail();
    }

    /**
     * @param $transactionData MM_WPFS_MyAccountLoginTransactionData
     * @param $subject
     * @param $body
     */
    public function sendTestMyAccountLoginRequest( $transactionData, $subject, $body ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_TestMyAccountLoginNotificationSender( $transactionData, $this->loggerService );
        $sender->setSubject( $subject );
        $sender->setBody( $body );
        $sender->sendEmail();
    }

    private function createPhonyForm() {
        $form = new \StdClass;
        $form->decimalSeparator = MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT;
        $form->showCurrencySymbolInsteadOfCode = 1;
        $form->showCurrencySignAtFirstPosition = 1;
        $form->putWhitespaceBetweenCurrencyAndAmount = 0;
        $form->currency = 'USD';

        return $form;
    }

    private function sendTestEmailByTemplateType( $emailTemplateType, $form, $transactionData, $subject, $body ) {
        switch( $emailTemplateType ) {
            case MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT:
                $this->sendTestOneTimePaymentReceipt( $form, $transactionData, $subject, $body );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED:
                $this->sendTestSaveCardNotification( $form, $transactionData, $subject, $body );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT:
                $this->sendTestSubscriptionStartedEmailReceipt( $form, $transactionData, $subject, $body );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED:
                $this->sendTestSubscriptionFinishedEmailReceipt( $form, $transactionData, $subject, $body );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT:
                $this->sendTestDonationEmailReceipt( $form, $transactionData, $subject, $body );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_CUSTOMER_PORTAL_SECURITY_CODE:
                $this->sendTestMyAccountLoginRequest( $transactionData, $subject, $body );
                break;

            default:
                throw new Exception( __CLASS__ . '::' . __FUNCTION__ . "() - invalid email template type: " . $emailTemplateType );
        }
    }

    public function sendTestEmail( $recipients, $subject, $body, $emailTemplateType ) {
        $form = $this->createPhonyForm();

        foreach ( $recipients as $recipient ) {
            $transactionData = MM_WPFS_TestTransactionDataCreatorFactory::createByEmailTemplateType( $recipient, $emailTemplateType );

            $this->sendTestEmailByTemplateType( $emailTemplateType, $form, $transactionData, $subject, $body );
        }
    }
}

class MM_WPFS_TestTransactionDataCreatorFactory {
    public static function createByEmailTemplateType( $recipient, $emailTemplateType ) {
        $result = null;

        switch( $emailTemplateType ) {
            case MM_WPFS::EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT:
                $result = (new MM_WPFS_OnetimePaymentTestTransactionDataCreator())->create( $recipient );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_CARD_SAVED:
                $result = (new MM_WPFS_SaveCardTestTransactionDataCreator())->create( $recipient );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT:
            case MM_WPFS::EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED:
                $result = (new MM_WPFS_SubscriptionTestTransactionDataCreator())->create( $recipient );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_DONATION_RECEIPT:
                $result = (new MM_WPFS_DonationTestTransactionDataCreator())->create( $recipient );
                break;

            case MM_WPFS::EMAIL_TEMPLATE_ID_CUSTOMER_PORTAL_SECURITY_CODE:
                $result = (new MM_WPFS_MyAccountTestTransactionDataCreator())->create( $recipient );
                break;

            default:
                throw new Exception( __CLASS__ . '::' . __FUNCTION__ . "() - invalid email template type: " . $emailTemplateType );
        }

        return $result;
    }
}

abstract class MM_WPFS_TestTransactionDataCreator {
    const CUSTOMER_NAME = 'John Doe';

    /**
     * @param $result MM_WPFS_TransactionData
     */
    protected function setCommonFields( $result ) {
        $result->setCustomerName( self::CUSTOMER_NAME );
    }

    public abstract function create( $recipient );
}

class MM_WPFS_MyAccountTestTransactionDataCreator extends MM_WPFS_TestTransactionDataCreator {
    public function create( $recipient ) {
        $result = MM_WPFS_TransactionDataService::createMyAccountLoginDataByEmail( $recipient );

        $this->setCommonFields( $result );

        $result->setSecurityCode( MM_WPFS_CustomerPortalService::generateSecurityCode()  );
        $result->setSessionHash( wp_generate_password( 16, false ) );

        return $result;
    }
}

abstract class MM_WPFS_TestFormTransactionDataCreator extends MM_WPFS_TestTransactionDataCreator {
    const ARRAY_KEY_ADDRESS_LINE_1 = 'line1';
    const ARRAY_KEY_ADDRESS_LINE_2 = 'line2';
    const ARRAY_KEY_ADDRESS_CITY = 'city';
    const ARRAY_KEY_ADDRESS_STATE = 'state';
    const ARRAY_KEY_ADDRESS_COUNTRY = 'country';
    const ARRAY_KEY_ADDRESS_COUNTRY_CODE = 'country_code';
    const ARRAY_KEY_ADDRESS_ZIP = 'zip';

    const STRIPE_CUSTOMER_ID = 'cus_FDgty569gn546NB465';
    const CUSTOMER_PHONE = '+1-562-555-0177';

    const BILLING_NAME = "John 'Billing' Doe";
    const BILLING_LINE_1 = '50553 Bartell Circles';
    const BILLING_LINE_2 = '2nd floor, C building';
    const BILLING_CITY = 'Tabithaburgh';
    const BILLING_ZIP = '31965';
    const BILLING_STATE = 'Bilzen';
    const BILLING_COUNTRY = 'United States';
    const BILLING_COUNTRY_CODE = 'US';

    const SHIPPING_NAME = "John 'Shipping' Doe";
    const SHIPPING_LINE_1 = '206 Jimmie Terrace';
    const SHIPPING_LINE_2 = 'Apt. 052';
    const SHIPPING_CITY = 'East Paulinestad';
    const SHIPPING_ZIP = '76284';
    const SHIPPING_STATE = 'Bilzen';
    const SHIPPING_COUNTRY = 'United States';
    const SHIPPING_COUNTRY_CODE = 'US';

    const IP_ADDRESS = '127.0.0.1';

    const CUSTOM_FIELD_WEBSITE_URL = 'https://paymentsplugin.com';

    const PAYMENT_METHOD_ID = 'pm_Fg56hBd56bfy8Hnbasd3';
    const PAYMENT_INTENT_ID = 'pi_XZCv3fASD4HFGd7ujNdf';

    protected function getAddressArray( $line1, $line2, $city, $state, $countryName, $countryCode, $zip ) {
        return array(
            self::ARRAY_KEY_ADDRESS_LINE_1       => is_null( $line1 ) ? '' : $line1,
            self::ARRAY_KEY_ADDRESS_LINE_2       => is_null( $line2 ) ? '' : $line2,
            self::ARRAY_KEY_ADDRESS_CITY         => is_null( $city ) ? '' : $city,
            self::ARRAY_KEY_ADDRESS_STATE        => is_null( $state ) ? '' : $state,
            self::ARRAY_KEY_ADDRESS_COUNTRY      => is_null( $countryName ) ? '' : $countryName,
            self::ARRAY_KEY_ADDRESS_COUNTRY_CODE => is_null( $countryCode ) ? '' : $countryCode,
            self::ARRAY_KEY_ADDRESS_ZIP          => is_null( $zip ) ? '' : $zip
        );
    }

    /**
     * @param $result MM_WPFS_FormTransactionData
     */
    protected function setFormCommonFields( $result ) {
        $result->setIpAddress( self::IP_ADDRESS );
        $result->setStripeCustomerId( self::STRIPE_CUSTOMER_ID );
        $result->setCustomerPhone( self::CUSTOMER_PHONE );

        $result->setBillingName( self::BILLING_NAME );
        $result->setBillingAddress( $this->getAddressArray(
           self::BILLING_LINE_1,
            self::BILLING_LINE_2,
            self::BILLING_CITY,
            self::BILLING_STATE,
            self::BILLING_COUNTRY,
            self::BILLING_COUNTRY_CODE,
            self::BILLING_ZIP
        ));

        $result->setShippingName( self::SHIPPING_NAME );
        $result->setShippingAddress( $this->getAddressArray(
            self::SHIPPING_LINE_1,
            self::SHIPPING_LINE_2,
            self::SHIPPING_CITY,
            self::SHIPPING_STATE,
            self::SHIPPING_COUNTRY,
            self::SHIPPING_COUNTRY_CODE,
            self::SHIPPING_ZIP
        ));

        $result->setCustomInputValues( [
            self::CUSTOM_FIELD_WEBSITE_URL
        ]);

        $result->setStripePaymentMethodId( self::PAYMENT_METHOD_ID );
        $result->setStripePaymentIntentId( self::PAYMENT_INTENT_ID );
    }
}

interface MM_WPFS_TestTransactionGenerator_InvoiceTools_Constants {
    const INVOICE_NUMBER = 'INV_00001';
    const INVOICE_URL = 'https://paymentsplugin.com';
    const RECEIPT_URL = 'https://paymentsplugin.com';
}

trait MM_WPFS_TestTransactionGenerator_InvoiceTools {
    /**
     * @param $result MM_WPFS_OneTimePaymentTransactionData|MM_WPFS_SubscriptionTransactionData|MM_WPFS_DonationTransactionData
     * @noinspection PhpUndefinedClassConstantInspection
     */
    protected function setInvoiceFields( $result ) {
        $result->setInvoiceNumber( self::INVOICE_NUMBER );
        $result->setInvoiceUrl( self::INVOICE_URL );
        $result->setReceiptUrl( self::RECEIPT_URL );
    }
}

interface MM_WPFS_TestTransactionGenerator_TaxTools_Constants {
    const CUSTOMER_TAX_ID = 'TAX-0000001';
}

trait MM_WPFS_TestTransactionGenerator_TaxTools {
    /**
     * @param $result MM_WPFS_OneTimePaymentTransactionData|MM_WPFS_SubscriptionTransactionData
     * @noinspection PhpUndefinedClassConstantInspection
     */
    protected function setTaxFields( $result ) {
        $result->setCustomerTaxId( self::CUSTOMER_TAX_ID );
    }
}


class MM_WPFS_OnetimePaymentTestTransactionDataCreator extends MM_WPFS_TestFormTransactionDataCreator implements MM_WPFS_TestTransactionGenerator_InvoiceTools_Constants, MM_WPFS_TestTransactionGenerator_TaxTools_Constants {
    const TRANSACTION_ID = 'pi_CV2Fky5bD8GlaEC23';
    const FORM_NAME = 'paymentForm';

    const PRODUCT_NAME = 'Gold package';
    const NET_AMOUNT = 4999;
    const TAX_AMOUNT = 499;
    const GROSS_AMOUNT = 5498;

    const COUPON_CODE = '20PERCENTOFF';

    const CURRENCY = 'USD';

    use MM_WPFS_TestTransactionGenerator_InvoiceTools;
    use MM_WPFS_TestTransactionGenerator_TaxTools;

    /**
     * @param $result MM_WPFS_OneTimePaymentTransactionData
     */
    protected function setFormSpecificFields( $result ) {
        $result->setFormName( self::FORM_NAME );
        $result->setTransactionId( self::TRANSACTION_ID );

        $result->setProductName( self::PRODUCT_NAME );

        $result->setProductAmountNet( self::NET_AMOUNT );
        $result->setProductAmountTax( self::TAX_AMOUNT );
        $result->setProductAmountGross( self::GROSS_AMOUNT );
        $result->setProductAmountDiscount( 0 );
        $result->setAmount( self::GROSS_AMOUNT );

        $result->setCouponCode( self::COUPON_CODE );

        $result->setCurrency( self::CURRENCY );
    }

    public function create( $recipient ) {
        $result = MM_WPFS_TransactionDataService::createOneTimePaymentDataByEmail( $recipient );

        $this->setCommonFields( $result );
        $this->setFormCommonFields( $result );
        $this->setFormSpecificFields( $result );
        $this->setInvoiceFields( $result );
        $this->setTaxFields( $result );

        return $result;
    }
}

class MM_WPFS_SaveCardTestTransactionDataCreator extends MM_WPFS_TestFormTransactionDataCreator {
    const TRANSACTION_ID = 'cus_Bdfhg54Dkj2XZ9Nfgu65';
    const FORM_NAME = 'saveCardForm';

    /**
     * @param $result MM_WPFS_SaveCardTransactionData
     */
    protected function setFormSpecificFields( $result ) {
        $result->setFormName( self::FORM_NAME );
        $result->setTransactionId( self::TRANSACTION_ID );
    }

    public function create( $recipient ) {
        $result = MM_WPFS_TransactionDataService::createSaveCardDataByEmail( $recipient );

        $this->setCommonFields( $result );
        $this->setFormCommonFields( $result );
        $this->setFormSpecificFields( $result );

        return $result;
    }
}

class MM_WPFS_DonationTestTransactionDataCreator extends MM_WPFS_TestFormTransactionDataCreator implements MM_WPFS_TestTransactionGenerator_InvoiceTools_Constants{
    use MM_WPFS_TestTransactionGenerator_InvoiceTools;

    const TRANSACTION_ID = 'pi_Vfdg9Kef3Bga8Ge4rPhk';
    const FORM_NAME = 'donationForm';

    const DONATION_AMOUNT = 777;
    const DONATION_FREQUENCY = 'monthly';

    const CURRENCY = 'USD';

    /**
     * @param $result MM_WPFS_DonationTransactionData
     */
    protected function setFormSpecificFields( $result ) {
        $result->setFormName( self::FORM_NAME );
        $result->setTransactionId( self::TRANSACTION_ID );

        $result->setAmount( self::DONATION_AMOUNT );
        $result->setDonationFrequency( self::DONATION_FREQUENCY );

        $result->setCurrency( self::CURRENCY );
    }

    public function create( $recipient ) {
        $result = MM_WPFS_TransactionDataService::createDonationDataByEmail( $recipient );

        $this->setCommonFields( $result );
        $this->setFormCommonFields( $result );
        $this->setFormSpecificFields( $result );
        $this->setInvoiceFields( $result );

        return $result;
    }
}

class MM_WPFS_SubscriptionTestTransactionDataCreator extends MM_WPFS_TestFormTransactionDataCreator implements MM_WPFS_TestTransactionGenerator_InvoiceTools_Constants, MM_WPFS_TestTransactionGenerator_TaxTools_Constants {
    const TRANSACTION_ID = 'sub_Bdfhg54Dkj2XZ9Nfgu65';
    const FORM_NAME = 'subscriptionForm';

    const PLAN_QUANTITY = 1;
    const PLAN_NAME = 'Gold plan';
    const PLAN_ID = 'price_bFGhbrtyg4gJsxe3';
    const SETUP_FEE_NET = 1000;
    const SETUP_FEE_TAX = 150;
    const SETUP_FEE_GROSS = 1150;
    const PLAN_AMOUNT_NET = 4000;
    const PLAN_AMOUNT_TAX = 600;
    const PLAN_AMOUNT_GROSS = 4600;

    const COUPON_CODE = '20PERCENTOFF';

    const CURRENCY = 'USD';

    use MM_WPFS_TestTransactionGenerator_InvoiceTools;
    use MM_WPFS_TestTransactionGenerator_TaxTools;

    /**
     * @param $result MM_WPFS_SubscriptionTransactionData
     */
    protected function setFormSpecificFields( $result ) {
        $result->setFormName( self::FORM_NAME );
        $result->setTransactionId( self::TRANSACTION_ID );

        $result->setPlanName( self::PLAN_NAME );
        $result->setProductName( self::PLAN_NAME );
        $result->setPlanId( self::PLAN_ID );

        $result->setPlanQuantity( self::PLAN_QUANTITY );
        $result->setAmount( self::PLAN_AMOUNT_GROSS + self::SETUP_FEE_GROSS );
        $result->setPlanNetAmount( self::PLAN_AMOUNT_NET );
        $result->setPlanTaxAmount( self::PLAN_AMOUNT_TAX );
        $result->setPlanGrossAmount( self::PLAN_AMOUNT_GROSS );
        $result->setPlanNetAmountTotal( self::PLAN_AMOUNT_NET );
        $result->setPlanTaxAmountTotal( self::PLAN_AMOUNT_TAX );
        $result->setPlanGrossAmountTotal( self::PLAN_AMOUNT_GROSS );
        $result->setSetupFeeNetAmount( self::SETUP_FEE_NET );
        $result->setSetupFeeTaxAmount( self::SETUP_FEE_TAX );
        $result->setSetupFeeGrossAmount( self::SETUP_FEE_GROSS );
        $result->setSetupFeeNetAmountTotal( self::SETUP_FEE_NET );
        $result->setSetupFeeTaxAmountTotal( self::SETUP_FEE_TAX );
        $result->setSetupFeeGrossAmountTotal( self::SETUP_FEE_GROSS );

        $result->setCouponCode( self::COUPON_CODE );

        $result->setPlanCurrency( self::CURRENCY );
    }

    public function create( $recipient ) {
        $result = MM_WPFS_TransactionDataService::createSubscriptionDataByEmail( $recipient );

        $this->setCommonFields( $result );
        $this->setFormCommonFields( $result );
        $this->setFormSpecificFields( $result );
        $this->setInvoiceFields( $result );
        $this->setTaxFields( $result );

        return $result;
    }
}


abstract class MM_WPFS_MailerTask {
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    const TEMPLATE_TYPE_PAYMENT_RECEIPT                     = "PaymentReceipt";
    const TEMPLATE_TYPE_DONATION_RECEIPT                    = "DonationReceipt";
    const TEMPLATE_TYPE_SUBSCRIPTION_RECEIPT                = "SubscriptionReceipt";
    const TEMPLATE_TYPE_SUBSCRIPTION_ENDED                  = "SubscriptionEnded";
    const TEMPLATE_TYPE_CARD_SAVED                          = "CardSaved";
    const TEMPLATE_TYPE_MANAGE_SUBSCRIPTIONS_SECURITY_CODE  = "ManageSubscriptionsSecurityCode";

    protected $template;
    protected $form;
    /**
     * @var $transactionData MM_WPFS_TransactionData
     */
    protected $transactionData;

    /** @var MM_WPFS_Options */
    protected $options;
    protected $formName;

    public function __construct( $transactionData, $loggerService, $form = null ) {
        $this->options          = new MM_WPFS_Options();
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME );

        $this->initStaticContext();

        $this->transactionData  = $transactionData;
        $this->form             = $form;
    }

    protected abstract function getSubjectAndMessage();
    protected abstract function getMacroReplacer();

    public final function sendEmail( ) {
        list( $subject, $message ) = $this->getSubjectAndMessage();

        /**
         * @var $replacer MM_WPFS_MacroReplacer_AddOn
         */
        $replacer = $this->getMacroReplacer();

        $subjectParams = [
            'template'                  => $this->template,
            'formName'                  => $this->formName,
            'rawPlaceholders'           => $replacer->getRawKeyValuePairs(),
            'decoratedPlaceholders'     => $replacer->getDecoratedKeyValuePairs(),
        ];
        $subject = $replacer->replaceMacrosWithHtmlEscape(
            do_shortcode( apply_filters( MM_WPFS::FILTER_NAME_MODIFY_EMAIL_SUBJECT, $subject, $subjectParams ) )
        );

        $messageParams = [
            'template'                  => $this->template,
            'formName'                  => $this->formName,
            'rawPlaceholders'           => $replacer->getRawKeyValuePairs(),
            'decoratedPlaceholders'     => $replacer->getDecoratedKeyValuePairs(),
        ];
        $message = $replacer->replaceMacrosWithHtmlEscape(
            do_shortcode( apply_filters( MM_WPFS::FILTER_NAME_MODIFY_EMAIL_MESSAGE, $message, $messageParams ) )
        );

        $this->sendEmailViaWordpress( $this->transactionData->getCustomerEmail(), $subject, $message );

    }

    private function sendEmailViaWordpress($email, $subject, $message ) {
        $senderName = html_entity_decode( get_bloginfo( 'name' ) );
        $senderEmail = $this->options->get( MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS );

        $headers[] = "Content-type: text/html";
        $headers[] = 'From: ' . MM_WPFS_Mailer::generateSenderStringFromNameAndEmail( $senderName, $senderEmail );

        $bccEmails = json_decode( $this->options->get( MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES ));
        foreach ( $bccEmails as $bccEmail ) {
            $headers[] = 'Bcc: <' . $bccEmail . '>';
        }

        wp_mail( $email, $subject, $message, apply_filters( 'fullstripe_email_headers_filter', $headers ));
    }

    protected function getEmailReceipts(){
        $emailReceipts = json_decode( $this->options->get( MM_WPFS_Options::OPTION_EMAIL_TEMPLATES ));

        return $emailReceipts;
    }
}

class MM_WPFS_DonationReceiptSender extends MM_WPFS_MailerTask {
    /**
     * @var $transactionData MM_WPFS_DonationTransactionData
     */

    public function __construct( $transactionData, $loggerService, $form = null ) {
        parent::__construct( $transactionData, $loggerService, $form );

        $this->template = MM_WPFS_MailerTask::TEMPLATE_TYPE_DONATION_RECEIPT;
        $this->formName = $this->transactionData->getFormName();
    }

    protected function getMacroReplacer() {
        return new MM_WPFS_DonationMacroReplacer( $this->form, $this->transactionData, $this->loggerService );
    }

    protected function getSubjectAndMessage() {
        $emailReceipts = $this->getEmailReceipts();

        return array(
            $emailReceipts->donationMade->subject,
            $emailReceipts->donationMade->html
        );
    }
}

trait MM_WPFS_CustomTemplateBasedSender {
    protected $subject;
    protected $body;

    public function setSubject( $subject ) {
        $this->subject = $subject;
    }

    public function setBody( $body ) {
        $this->body = $body;
    }

    protected function getSubjectAndMessage() {
        return array(
            $this->subject,
            $this->body
        );
    }
}

class MM_WPFS_TestDonationReceiptSender extends MM_WPFS_DonationReceiptSender {
    use MM_WPFS_CustomTemplateBasedSender;
}

class MM_WPFS_GenericEmailNotificationSender extends MM_WPFS_MailerTask {

    protected $decoratedKeyValuePairs;

    public function setTemplateType( $templateType ) {
        $this->template = $templateType;
    }

    public function setDecoratedKeyValuePairs( $keyValuePairs ) {
        $this->decoratedKeyValuePairs = $keyValuePairs;
    }

    protected function getSubjectAndMessage() {
        $emailReceipts = $this->getEmailReceipts();

        return array(
            $emailReceipts->{$this->template}->subject,
            $emailReceipts->{$this->template}->html
        );
    }

    protected function getMacroReplacer() {
        return new MM_WPFS_GenericMacroReplacer( $this->decoratedKeyValuePairs );
    }
}

class MM_WPFS_MyAccountLoginNotificationSender extends MM_WPFS_MailerTask {
    /**
     * @var $transactionData MM_WPFS_MyAccountLoginTransactionData
     */

    public function __construct( $transactionData, $loggerService, $form = null ) {
        parent::__construct( $transactionData, $loggerService, $form );

        $this->template = MM_WPFS_MailerTask::TEMPLATE_TYPE_MANAGE_SUBSCRIPTIONS_SECURITY_CODE;
        $this->formName = null;
    }

    protected function getMacroReplacer() {
        return new MM_WPFS_MyAccountLoginMacroReplacer( $this->transactionData );
    }

    protected function getSubjectAndMessage() {
        $emailReceipts = $this->getEmailReceipts();

        return array(
            $emailReceipts->cardUpdateConfirmationRequest->subject,
            $emailReceipts->cardUpdateConfirmationRequest->html
        );
    }
}

class MM_WPFS_TestMyAccountLoginNotificationSender extends MM_WPFS_MyAccountLoginNotificationSender {
    use MM_WPFS_CustomTemplateBasedSender;
}

class MM_WPFS_OneTimePaymentReceiptSender extends MM_WPFS_MailerTask {
    /**
     * @var $transactionData MM_WPFS_OneTimePaymentTransactionData
     */

    public function __construct( $transactionData, $loggerService, $form = null ) {
        parent::__construct( $transactionData, $loggerService, $form );

        $this->template = MM_WPFS_MailerTask::TEMPLATE_TYPE_PAYMENT_RECEIPT;
        $this->formName = $this->transactionData->getFormName();
    }

    protected function getMacroReplacer() {
        return new MM_WPFS_OneTimePaymentMacroReplacer( $this->form, $this->transactionData, $this->loggerService );
    }

    protected function getSubjectAndMessage() {
        $emailReceipts = $this->getEmailReceipts();

        return array(
            $emailReceipts->paymentMade->subject,
            $emailReceipts->paymentMade->html
        );
    }
}

class MM_WPFS_TestOneTimePaymentReceiptSender extends MM_WPFS_OneTimePaymentReceiptSender {
    use MM_WPFS_CustomTemplateBasedSender;
}

class MM_WPFS_SaveCardNotificationSender extends MM_WPFS_MailerTask {
    /**
     * @var $transactionData MM_WPFS_SaveCardTransactionData
     */

    public function __construct( $transactionData, $loggerService, $form = null ) {
        parent::__construct( $transactionData, $loggerService, $form );

        $this->template = MM_WPFS_MailerTask::TEMPLATE_TYPE_CARD_SAVED;
        $this->formName = $this->transactionData->getFormName();
    }

    protected function getMacroReplacer() {
        return new MM_WPFS_SaveCardMacroReplacer( $this->form, $this->transactionData, $this->loggerService );
    }

    protected function getSubjectAndMessage() {
        $emailReceipts = $this->getEmailReceipts();

        return array(
            $emailReceipts->cardCaptured->subject,
            $emailReceipts->cardCaptured->html
        );
    }
}

class MM_WPFS_TestSaveCardNotificationSender extends MM_WPFS_SaveCardNotificationSender {
    use MM_WPFS_CustomTemplateBasedSender;
}


class MM_WPFS_SubscriptionEmailReceiptSender extends MM_WPFS_MailerTask {
    /**
     * @var $transactionData MM_WPFS_SubscriptionTransactionData
     */

    public function __construct( $transactionData, $loggerService, $form = null ) {
        parent::__construct( $transactionData, $loggerService, $form );

        $this->template = MM_WPFS_MailerTask::TEMPLATE_TYPE_SUBSCRIPTION_RECEIPT;
        $this->formName = $this->transactionData->getFormName();
    }

    protected function getMacroReplacer() {
        return new MM_WPFS_SubscriptionMacroReplacer( $this->form, $this->transactionData, $this->loggerService );
    }

    protected function getSubjectAndMessage() {
        $emailReceipts = $this->getEmailReceipts();

        return array(
            $emailReceipts->subscriptionStarted->subject,
            $emailReceipts->subscriptionStarted->html
        );
    }
}

class MM_WPFS_TestSubscriptionEmailReceiptSender extends MM_WPFS_SubscriptionEmailReceiptSender {
    use MM_WPFS_CustomTemplateBasedSender;
}

class MM_WPFS_SubscriptionEndedNotificationSender extends MM_WPFS_MailerTask {
    /**
     * @var $transactionData MM_WPFS_SubscriptionTransactionData
     */

    public function __construct( $transactionData, $loggerService, $form = null ) {
        parent::__construct( $transactionData, $loggerService, $form );

        $this->template = MM_WPFS_MailerTask::TEMPLATE_TYPE_SUBSCRIPTION_ENDED;
        $this->formName = $this->transactionData->getFormName();
    }

    protected function getMacroReplacer() {
        return new MM_WPFS_SubscriptionMacroReplacer( $this->form, $this->transactionData, $this->loggerService );
    }

    protected function getSubjectAndMessage() {
        $emailReceipts = $this->getEmailReceipts();

        return array(
            $emailReceipts->subscriptionFinished->subject,
            $emailReceipts->subscriptionFinished->html
        );
    }
}

class MM_WPFS_TestSubscriptionEndedNotificationSender extends MM_WPFS_SubscriptionEndedNotificationSender {
    use MM_WPFS_CustomTemplateBasedSender;
}
