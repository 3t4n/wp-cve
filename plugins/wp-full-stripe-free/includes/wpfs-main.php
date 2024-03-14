<?php

/*
WP Full Pay
https://paymentsplugin.com
Complete Stripe payments integration for WordPress
CoastMountain
7.0.7
https://paymentsplugin.com
*/

class MM_WPFS {
	const VERSION = '7.0.7';
	const REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS = 'wpfs_rendered_forms';

    const HANDLE_WP_FULL_STRIPE_JS = 'wp-full-stripe-js';

    const SHORTCODE_FULLSTRIPE_FORM = 'fullstripe_form';
    const HANDLE_WP_FULL_STRIPE_UTILS_JS = 'wp-full-stripe-utils-js';
    const HANDLE_SPRINTF_JS = 'sprintf-js';
    const HANDLE_STRIPE_JS_V_3 = 'stripe-js-v3';
    const HANDLE_STYLE_WPFS_VARIABLES = 'wpfs-variables-css';
    const HANDLE_STYLE_WPFS_FORMS = 'wpfs-forms-css';
    const HANDLE_GOOGLE_RECAPTCHA_V_2 = 'google-recaptcha-v2';
    const SOURCE_GOOGLE_RECAPTCHA_V2_API_JS = 'https://www.google.com/recaptcha/api.js';

    // Generic form types
    const FORM_TYPE_PAYMENT = 'payment';
    const FORM_TYPE_SUBSCRIPTION = 'subscription';
    const FORM_TYPE_DONATION = 'donation';
    const FORM_TYPE_SAVE_CARD = 'save_card';

    const FORM_TYPE_INLINE_PAYMENT = 'inline_payment';
    const FORM_TYPE_CHECKOUT_PAYMENT = 'checkout_payment';
    const FORM_TYPE_INLINE_SUBSCRIPTION = 'inline_subscription';
    const FORM_TYPE_CHECKOUT_SUBSCRIPTION = 'checkout_subscription';
    const FORM_TYPE_INLINE_SAVE_CARD = 'inline_save_card';
    const FORM_TYPE_CHECKOUT_SAVE_CARD = 'checkout_save_card';
    const FORM_TYPE_INLINE_DONATION = 'inline_donation';
    const FORM_TYPE_CHECKOUT_DONATION = 'checkout_donation';

    // legacy form types, used only for shortcodes
    const FORM_TYPE_POPUP_PAYMENT = 'popup_payment';
    const FORM_TYPE_POPUP_SUBSCRIPTION = 'popup_subscription';
    const FORM_TYPE_POPUP_SAVE_CARD = 'popup_save_card';
    const FORM_TYPE_POPUP_DONATION = 'popup_donation';

    const FORM_TYPE_ADMIN_CREATE_FORM = 'createForm';
    const FORM_TYPE_ADMIN_CONFIGURE_STRIPE_ACCOUNT = 'configureStripeAccount';
    const FORM_TYPE_ADMIN_CONFIGURE_CUSTOMER_PORTAL = 'configureMyAccount';
    const FORM_TYPE_ADMIN_CONFIGURE_SECURITY = 'configureSecurity';
    const FORM_TYPE_ADMIN_CONFIGURE_EMAIL_OPTIONS = 'configureEmailOptions';
    const FORM_TYPE_ADMIN_CONFIGURE_EMAIL_TEMPLATES = 'configureEmailTemplates';
    const FORM_TYPE_ADMIN_CONFIGURE_FORMS_OPTIONS = 'configureFormsOptions';
    const FORM_TYPE_ADMIN_CONFIGURE_FORMS_APPEARANCE = 'configureFormsAppearance';
    const FORM_TYPE_ADMIN_CONFIGURE_WP_DASHBOARD = 'configureWpDashboard';
    const FORM_TYPE_ADMIN_CONFIGURE_LOGGING = 'configureLogging';
    const FORM_TYPE_ADMIN_EMPTY_LOG = 'emptyLog';

    const FORM_TYPE_ADMIN_INLINE_SAVE_CARD_FORM = 'inlineSaveCardForm';
    const FORM_TYPE_ADMIN_CHECKOUT_SAVE_CARD_FORM = 'checkoutSaveCardForm';
    const FORM_TYPE_ADMIN_INLINE_DONATION_FORM = 'inlineDonationForm';
    const FORM_TYPE_ADMIN_CHECKOUT_DONATION_FORM = 'checkoutDonationForm';
    const FORM_TYPE_ADMIN_ADD_CUSTOM_FIELD = 'addCustomField';
    const FORM_TYPE_ADMIN_ADD_SUGGESTED_DONATION_AMOUNT = 'addSuggestedDonationAmount';
    const FORM_TYPE_ADMIN_INLINE_PAYMENT_FORM = 'inlinePaymentForm';
    const FORM_TYPE_ADMIN_CHECKOUT_PAYMENT_FORM = 'checkoutPaymentForm';
    const FORM_TYPE_ADMIN_INLINE_SUBSCRIPTION_FORM = 'inlineSubscriptionForm';
    const FORM_TYPE_ADMIN_CHECKOUT_SUBSCRIPTION_FORM = 'checkoutSubscriptionForm';
    const FORM_TYPE_ADMIN_ADD_PLAN_PROPERTIES = 'addPlanProperties';
    const FORM_TYPE_ADMIN_EDIT_PRODUCT_PROPERTIES = 'editProductProperties';
    const FORM_TYPE_ADMIN_SEND_TEST_EMAIL = 'sendTestEmail';

    const FORM_LAYOUT_INLINE = 'inline';
    const FORM_LAYOUT_CHECKOUT = 'checkout';

    const STRIPE_API_MODE_TEST = 'test';
    const STRIPE_API_MODE_LIVE = 'live';

    const TEST_ACCOUNT_ID = 'testAccountId';
    const LIVE_ACCOUNT_ID = 'liveAccountId';
    const USE_WP_TEST_PLATFORM = 'useWpTestPlatform';
    const USE_WP_LIVE_PLATFORM = 'useWpLivePlatform';

    const REDIRECT_TYPE_SHOW_CONFIRMATION_MESSAGE = 'showConfirmationMessage';
    const REDIRECT_TYPE_TO_PAGE_OR_POST = 'redirectToPageOrPost';
    const REDIRECT_TYPE_TO_CUSTOM_URL = 'redirecToCustomUrl';

    const VAT_RATE_TYPE_NO_VAT = 'no_vat';
    const VAT_RATE_TYPE_FIXED_VAT = 'fixed_vat';
    const VAT_RATE_TYPE_CUSTOM_VAT = 'custom_vat';

    const NO_VAT_PERCENT = 0.0;

    const DEFAULT_BILLING_COUNTRY_INITIAL_VALUE = 'US';

    const PREFERRED_LANGUAGE_AUTO = 'auto';

    const DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT = 10;

    const PAYMENT_TYPE_LIST_OF_AMOUNTS = 'list_of_amounts';
    const PAYMENT_TYPE_CUSTOM_AMOUNT = 'custom_amount';
    const PAYMENT_TYPE_SPECIFIED_AMOUNT = 'specified_amount';
    const PAYMENT_TYPE_CARD_CAPTURE = 'card_capture';

    const CURRENCY_USD = 'usd';

    const CANCEL_SUBSCRIPTION_IMMEDIATELY = 'immediately';
    const CANCEL_SUBSCRIPTION_AT_PERIOD_END = 'atPeriodEnd';

    const DECIMAL_SEPARATOR_SYMBOL_DOT = 'dot';
    const DECIMAL_SEPARATOR_SYMBOL_COMMA = 'comma';

    const CHARGE_TYPE_IMMEDIATE = 'immediate';
    const CHARGE_TYPE_AUTHORIZE_AND_CAPTURE = 'authorize_and_capture';

    const PAYMENT_METHOD_CARD = 'card';

    const STRIPE_CHARGE_STATUS_SUCCEEDED = 'succeeded';
    const STRIPE_CHARGE_STATUS_PENDING = 'pending';
    const STRIPE_CHARGE_STATUS_FAILED = 'failed';

    const PAYMENT_STATUS_UNKNOWN = 'unknown';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';
    const PAYMENT_STATUS_EXPIRED = 'expired';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_AUTHORIZED = 'authorized';
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_RELEASED = 'released';

    const REFUND_STATUS_SUCCEEDED = 'succeeded';
    const REFUND_STATUS_FAILED = 'failed';
    const REFUND_STATUS_PENDING = 'pending';
    const REFUND_STATUS_CANCELED = 'canceled';

    const SUBSCRIPTION_STATUS_ENDED = 'ended';
    const SUBSCRIPTION_STATUS_CANCELLED = 'cancelled';

    const SELECTOR_STYLE_RADIO_BUTTONS = 'radio-buttons';
    const SELECTOR_STYLE_DROPDOWN = 'dropdown';
    const SELECTOR_STYLE_BUTTON_GROUP = 'button-group';
    const ELEMENTS_THEME_STRIPE = 'stripe';
    const ELEMENTS_THEME_NIGHT = 'night';
    const ELEMENTS_THEME_FLAT = 'flat';

    const PLAN_SELECTOR_STYLE_DROPDOWN = 'dropdown';
    const PLAN_SELECTOR_STYLE_RADIO_BUTTONS = 'radio-buttons';

    const JS_VARIABLE_WPFS_FORM_OPTIONS = 'wpfsFormSettings';
    const JS_VARIABLE_AJAX_URL = 'ajaxUrl';
    const JS_VARIABLE_STRIPE_KEY = 'stripeKey';
    const JS_VARIABLE_STRIPE_PLATFORM_KEY = 'stripePlatformKey';
    const JS_VARIABLE_STRIPE_ACCOUNT_ID = 'stripeAccountId';
    const JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY = 'googleReCaptchaSiteKey';
    const JS_VARIABLE_L10N = 'l10n';
    const JS_VARIABLE_FORM_FIELDS = 'formFields';

    const ACTION_NAME_BEFORE_SAVE_CARD = 'fullstripe_before_card_capture';
    const ACTION_NAME_AFTER_SAVE_CARD = 'fullstripe_after_card_capture';
    const ACTION_NAME_BEFORE_CHECKOUT_SAVE_CARD = 'fullstripe_before_checkout_card_capture';
    const ACTION_NAME_AFTER_CHECKOUT_SAVE_CARD = 'fullstripe_after_checkout_card_capture';

    const ACTION_NAME_BEFORE_PAYMENT_CHARGE = 'fullstripe_before_payment_charge';
    const ACTION_NAME_AFTER_PAYMENT_CHARGE = 'fullstripe_after_payment_charge';
    const ACTION_NAME_BEFORE_CHECKOUT_PAYMENT_CHARGE = 'fullstripe_before_checkout_payment_charge';
    const ACTION_NAME_AFTER_CHECKOUT_PAYMENT_CHARGE = 'fullstripe_after_checkout_payment_charge';

    const ACTION_NAME_BEFORE_DONATION_CHARGE = 'fullstripe_before_donation_charge';
    const ACTION_NAME_AFTER_DONATION_CHARGE = 'fullstripe_after_donation_charge';
    const ACTION_NAME_BEFORE_CHECKOUT_DONATION_CHARGE = 'fullstripe_before_checkout_donation_charge';
    const ACTION_NAME_AFTER_CHECKOUT_DONATION_CHARGE = 'fullstripe_after_checkout_donation_charge';

    const ACTION_NAME_BEFORE_SUBSCRIPTION_CHARGE = 'fullstripe_before_subscription_charge';
    const ACTION_NAME_AFTER_SUBSCRIPTION_CHARGE = 'fullstripe_after_subscription_charge';
    const ACTION_NAME_BEFORE_CHECKOUT_SUBSCRIPTION_CHARGE = 'fullstripe_before_checkout_subscription_charge';
    const ACTION_NAME_AFTER_CHECKOUT_SUBSCRIPTION_CHARGE = 'fullstripe_after_checkout_subscription_charge';

    const ACTION_NAME_BEFORE_SUBSCRIPTION_CANCELLATION = 'fullstripe_before_subscription_cancellation';
    const ACTION_NAME_AFTER_SUBSCRIPTION_CANCELLATION = 'fullstripe_after_subscription_cancellation';

    const ACTION_NAME_BEFORE_SUBSCRIPTION_UPDATE = 'fullstripe_before_subscription_update';
    const ACTION_NAME_AFTER_SUBSCRIPTION_UPDATE = 'fullstripe_after_subscription_update';

    const ACTION_NAME_BEFORE_SUBSCRIPTION_ACTIVATION = 'fullstripe_before_subscription_activation';
    const ACTION_NAME_AFTER_SUBSCRIPTION_ACTIVATION = 'fullstripe_after_subscription_activation';

    const FILTER_NAME_SELECT_SUBSCRIPTION_PLAN = 'fullstripe_select_subscription_plan';
    const FILTER_NAME_SET_CUSTOM_AMOUNT = 'fullstripe_set_custom_amount';
    const FILTER_NAME_ADD_TRANSACTION_METADATA = 'fullstripe_add_transaction_metadata';
    const FILTER_NAME_MODIFY_EMAIL_MESSAGE = 'fullstripe_modify_email_message';
    const FILTER_NAME_MODIFY_EMAIL_SUBJECT = 'fullstripe_modify_email_subject';
    const FILTER_NAME_GET_UPGRADE_DOWNGRADE_PLANS = 'fullstripe_get_upgrade_downgrade_plans';
    const FILTER_NAME_GET_ADDONS = 'fullstripe_get_addons';

    const FILTER_NAME_CUSTOMER_PORTAL_HEADER = 'fullstripe_customer_portal_header';
    const FILTER_NAME_CUSTOMER_PORTAL_FOOTER = 'fullstripe_customer_portal_footer';

    const FILTER_NAME_FORM_FIELD_CONFIGURATION = 'fullstripe_form_field_configuration';

    const FILTER_NAME_DETERMINE_TAX_LABEL = 'fullstripe_determine_tax_label';

    const STRIPE_OBJECT_ID_PREFIX_PAYMENT_INTENT = 'pi_';
    const STRIPE_OBJECT_ID_PREFIX_CHARGE = 'ch_';
    const PAYMENT_OBJECT_TYPE_UNKNOWN = 'Unknown';
    const PAYMENT_OBJECT_TYPE_STRIPE_PAYMENT_INTENT = '\StripeWPFS\PaymentIntent';
    const PAYMENT_OBJECT_TYPE_STRIPE_CHARGE = '\StripeWPFS\Charge';

    const SUBSCRIBER_STATUS_CANCELLED = 'cancelled';
    const SUBSCRIBER_STATUS_RUNNING = 'running';
    const SUBSCRIBER_STATUS_ENDED = 'ended';
    const SUBSCRIBER_STATUS_INCOMPLETE = 'incomplete';

    const DONATION_STATUS_UNKNOWN = 'unknown';
    const DONATION_STATUS_PAID = 'paid';
    const DONATION_STATUS_RUNNING = 'running';
    const DONATION_STATUS_REFUNDED = 'refunded';

    const HTTP_PARAM_NAME_PLAN = 'wpfsPlan';
    const HTTP_PARAM_NAME_AMOUNT = 'wpfsAmount';

    const DONATION_PLAN_ID_PREFIX = "wpfsDonationPlan";

    const EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT = 'paymentMade';
    const EMAIL_TEMPLATE_ID_PAYMENT_RECEIPT_STRIPE = 'paymentMadeStripe';
    const EMAIL_TEMPLATE_ID_CARD_SAVED = 'cardCaptured';
    const EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT = 'subscriptionStarted';
    const EMAIL_TEMPLATE_ID_SUBSCRIPTION_RECEIPT_STRIPE = 'subscriptionStartedStripe';
    const EMAIL_TEMPLATE_ID_SUBSCRIPTION_ENDED = 'subscriptionFinished';
    const EMAIL_TEMPLATE_ID_DONATION_RECEIPT = 'donationMade';
    const EMAIL_TEMPLATE_ID_DONATION_RECEIPT_STRIPE = 'donationMadeStripe';
    const EMAIL_TEMPLATE_ID_CUSTOMER_PORTAL_SECURITY_CODE = 'cardUpdateConfirmationRequest';

    const COUNTRY_CODE_UNITED_STATES = 'US';

    // These values go into the database as well, don't change them
    const FIELD_VALUE_TAX_RATE_NO_TAX = 'taxRateNoTax';
    const FIELD_VALUE_TAX_RATE_STRIPE_TAX = 'taxRateStripeTax';
    const FIELD_VALUE_TAX_RATE_FIXED = 'taxRateFixed';
    const FIELD_VALUE_TAX_RATE_DYNAMIC = 'taxRateDynamic';

    // This value is transient, used only on the Admin UI
    const FIELD_VALUE_TAX_RATE_TAX_RATES = 'taxRateTaxRates';

    const PRICE_ID_CUSTOM_AMOUNT = 'customAmount';
    const LINE_ITEM_TYPE_CUSTOM_AMOUNT = 'customAmount';
    const LINE_ITEM_TYPE_SETUP_FEE = 'setupFee';
    const LINE_ITEM_TYPE_PRODUCT = 'product';
    const LINE_ITEM_SUBTYPE_DISCOUNT = 'discount';
    const LINE_ITEM_SUBTYPE_TAX = 'tax';

    const DISCOUNT_TYPE_PROMOTION_CODE = 'promotionCode';
    const DISCOUNT_TYPE_COUPON = 'coupon';

    public static $instance;

    /** @var MM_WPFS_Options */
    private $options = null;
    /** @var MM_WPFS_Customer */
    private $customer = null;
    /** @var MM_WPFS_Admin */
    private $admin = null;
    /** @var MM_WPFS_Database */
    private $database = null;
    /** @var MM_WPFS_Stripe */
    private $stripe = null;
    /** @var MM_WPFS_Admin_Menu */
    private $adminMenu = null;
    /** @var MM_WPFS_TransactionDataService */
    private $transactionDataService = null;
    /** @var MM_WPFS_CustomerPortalService */
    private $customerPortalService = null;
    /** @var MM_WPFS_ThankYou */
    private $thankYou = null;
    /** @var MM_WPFS_CheckoutSubmissionService */
    private $checkoutSubmissionService = null;
    /** @var MM_WPFS_StaticContext */
    private $staticContext = null;
    /**
     * @var bool Choose to load scripts and styles the WordPress way. We should move this field to a wp_option later.
     */
    private $loadScriptsAndStylesWithActionHook = false;
    /**
     * @var bool Turn this off if you don't want to load the form CSS styles
     */
    private $includeDefaultStyles = true;

    private $wpfsJsHandles = [
        self::HANDLE_WP_FULL_STRIPE_JS,
        self::HANDLE_WP_FULL_STRIPE_UTILS_JS,
        self::HANDLE_SPRINTF_JS,
        self::HANDLE_STRIPE_JS_V_3,
        self::HANDLE_GOOGLE_RECAPTCHA_V_2
    ];

    public function __construct() {

        $this->includes();
        $this->setup();
        $this->hooks();

    }

    function includes() {

        include 'wpfs-logger-service.php';
        include 'wpfs-options.php';
        include 'wpfs-context.php';
        include 'wpfs-localization.php';
        include 'wp/class-wp-list-table.php';
        include 'wpfs-tables.php';
        include 'wpfs-languages.php';
        include 'wpfs-form-fields-configurable.php';
        include 'wpfs-admin.php';
        include 'wpfs-admin-menu.php';
        include 'wpfs-form-views.php';
        include 'wpfs-form-models.php';
        include 'wpfs-form-validators.php';
        include 'wpfs-admin-views.php';
        include 'wpfs-admin-models.php';
        include 'wpfs-admin-validators.php';
        include 'wpfs-assets.php';
        include 'wpfs-pricing.php';
        include 'wpfs-customer-portal-service.php';
        include 'wpfs-thank-you.php';
        include 'wpfs-customer.php';
        include 'wpfs-checkout-charge-handler.php';
        include 'wpfs-checkout-submission-service.php';
        include 'wpfs-countries.php';
        include 'wpfs-states.php';
        include 'wpfs-currencies.php';
        include 'wpfs-database.php';
        include 'wpfs-mailer.php';
        include 'wpfs-news-feed-url.php';
        include 'wpfs-patcher.php';
        include 'wpfs-payments.php';
        include 'wpfs-help.php';
        include 'wpfs-transaction-data-service.php';
        include 'wpfs-web-hook-events.php';
        include 'wpfs-api.php';
        include 'wpfs-macros.php';
        include 'wpfs-utils.php';
        include 'wpfs-view-template-utils.php';
        include 'wpfs-recaptcha.php';
        include 'wpfs-shortcode.php';

        do_action( 'fullstripe_includes_action' );
    }

    function setup() {

        $this->options = new MM_WPFS_Options();
        $this->updateOptionDefaults();
        if ( $this->options->getVersion() != self::VERSION ) {
            $this->upgradeOptions();
            MM_WPFS::setup_db( false );
        }

        MM_WPFS_LicenseManager::getInstance()->activateLicenseIfNeeded();

        $this->loggerService             = new MM_WPFS_LoggerService( $this->options->get( MM_WPFS_Options::OPTION_LOG_LEVEL ), $this->options->get( MM_WPFS_Options::OPTION_LOG_TO_WEB_SERVER ) == 1 );
        $this->logger                    = $this->loggerService->createAdminLogger(__CLASS__);
        $this->staticContext             = new MM_WPFS_StaticContext( $this->loggerService, $this->options );

        $this->setupErrorHandlers();


        $this->admin                     = new MM_WPFS_Admin( $this->loggerService );
        $this->adminMenu                 = new MM_WPFS_Admin_Menu( $this->loggerService );
        $this->customer                  = new MM_WPFS_Customer( $this->loggerService );
        $this->database                  = new MM_WPFS_Database();
        $this->stripe                    = new MM_WPFS_Stripe( MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService );
        $this->transactionDataService    = new MM_WPFS_TransactionDataService();
        $this->customerPortalService     = new MM_WPFS_CustomerPortalService( $this->loggerService );
        $this->checkoutSubmissionService = new MM_WPFS_CheckoutSubmissionService( $this->loggerService );
        $this->thankYou                  = new MM_WPFS_ThankYou( $this->loggerService );

        do_action( 'fullstripe_setup_action' );
    }

    protected function setupErrorHandlers() {
        if ( boolval( $this->options->get( MM_WPFS_Options::OPTION_CATCH_UNCAUGHT_ERRORS ))) {
            set_error_handler( array( $this, 'handleError' ) ,  E_ALL | E_STRICT );
            set_exception_handler( array( $this, 'handleException' ));
            ini_set( 'display_errors', 0 );
        }
    }

    public function handleError( int $errNo, string $errStr, string $errFile, int $errLine ) {
        $this->logger->error( __FUNCTION__, "Uncaught error in {$errFile}:{$errLine}: {$errNo} - {$errStr}" );
    }

    public function handleException( Throwable $ex ) {
        $this->logger->error( __FUNCTION__, "Uncaught exception - " . $ex->getMessage(), $ex );
    }

    protected function upgradeOptions() {
        $this->options->set( MM_WPFS_Options::OPTION_VERSION, self::VERSION );

        $emailReceipts = json_decode( $this->options->get( MM_WPFS_Options::OPTION_EMAIL_TEMPLATES ));
        MM_WPFS_Mailer::updateMissingEmailTemplatesWithDefaults($emailReceipts);
        $this->options->set( MM_WPFS_Options::OPTION_EMAIL_TEMPLATES, json_encode( $emailReceipts ));

        if ( $this->options->get( MM_WPFS_Options::OPTION_CUSTOM_INPUT_FIELD_MAX_COUNT ) != MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT ) {
            $this->options->set( MM_WPFS_Options::OPTION_CUSTOM_INPUT_FIELD_MAX_COUNT, MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT );
        }
    }
    protected function updateOptionDefaults() {
        $updateSet = [
            MM_WPFS_Options::OPTION_VERSION                                             => self::VERSION,
            MM_WPFS_Options::OPTION_API_TEST_SECRET_KEY                                 => 'YOUR_TEST_SECRET_KEY',
            MM_WPFS_Options::OPTION_API_TEST_PUBLISHABLE_KEY                            => 'YOUR_TEST_PUBLISHABLE_KEY',
            MM_WPFS_Options::OPTION_API_LIVE_SECRET_KEY                                 => 'YOUR_LIVE_SECRET_KEY',
            MM_WPFS_Options::OPTION_API_LIVE_PUBLISHABLE_KEY                            => 'YOUR_LIVE_PUBLISHABLE_KEY',
            MM_WPFS_Options::OPTION_API_MODE                                            => MM_WPFS::STRIPE_API_MODE_TEST,
            MM_WPFS_Options::OPTION_FORM_CUSTOM_CSS                                     => '',
            MM_WPFS_Options::OPTION_RECEIPT_EMAIL_TYPE                                  => MM_WPFS_Options::OPTION_VALUE_RECEIPT_EMAIL_PLUGIN,
            MM_WPFS_Options::OPTION_EMAIL_TEMPLATES                                     => json_encode( MM_WPFS_Mailer::getDefaultEmailTemplates() ),
            MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_SENDER_ADDRESS                   => get_bloginfo( 'admin_email' ),
            MM_WPFS_Options::OPTION_EMAIL_NOTIFICATION_BCC_ADDRESSES                    => json_encode( array() ),
            MM_WPFS_Options::OPTION_FILL_IN_EMAIL_FOR_LOGGED_IN_USERS                   => '1',
            MM_WPFS_Options::OPTION_WEBHOOK_TOKEN                                       => $this->createWebhookToken(),
            MM_WPFS_Options::OPTION_CUSTOM_INPUT_FIELD_MAX_COUNT                        => MM_WPFS::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT,
            MM_WPFS_Options::OPTION_SECURE_INLINE_FORMS_WITH_GOOGLE_RE_CAPTCHA          => '0',
            MM_WPFS_Options::OPTION_SECURE_CHECKOUT_FORMS_WITH_GOOGLE_RE_CAPTCHA        => '0',
            MM_WPFS_Options::OPTION_SECURE_CUSTOMER_PORTAL_WITH_GOOGLE_RE_CAPTCHA       => '0',
            MM_WPFS_Options::OPTION_GOOGLE_RE_CAPTCHA_SITE_KEY                          => 'YOUR_GOOGLE_RECAPTCHA_SITE_KEY',
            MM_WPFS_Options::OPTION_GOOGLE_RE_CAPTCHA_SECRET_KEY                        => 'YOUR_GOOGLE_RECAPTCHA_SECRET_KEY',
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_SUBSCRIPTIONS_TO_CUSTOMERS     => '1',
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_CANCEL_SUBSCRIPTIONS => '1',
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_LET_SUBSCRIBERS_UPDOWNGRADE_SUBSCRIPTIONS => '0',
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_WHEN_CANCEL_SUBSCRIPTIONS           => MM_WPFS::CANCEL_SUBSCRIPTION_IMMEDIATELY,
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_INVOICES_SECTION               => '1',
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SHOW_ALL_INVOICES                   => '0',
            MM_WPFS_Options::OPTION_CUSTOMER_PORTAL_SCROLLING_PANE_INTO_VIEW            => '1',
            MM_WPFS_Options::OPTION_DECIMAL_SEPARATOR_SYMBOL                            => MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT,
            MM_WPFS_Options::OPTION_SHOW_CURRENCY_SYMBOL_INSTEAD_OF_CODE                => '1',
            MM_WPFS_Options::OPTION_SHOW_CURRENCY_SIGN_AT_FIRST_POSITION                => '1',
            MM_WPFS_Options::OPTION_PUT_WHITESPACE_BETWEEN_CURRENCY_AND_AMOUNT          => '0',
            MM_WPFS_Options::OPTION_LAST_WEBHOOK_EVENT_TEST                             => null,
            MM_WPFS_Options::OPTION_LAST_WEBHOOK_EVENT_LIVE                             => null,
            MM_WPFS_Options::OPTION_LOG_LEVEL                                           => MM_WPFS_LoggerService::LEVEL_ERROR,
            MM_WPFS_Options::OPTION_LOG_TO_WEB_SERVER                                   => 0,
            MM_WPFS_Options::OPTION_CATCH_UNCAUGHT_ERRORS                               => 0,
            MM_WPFS_Options::OPTION_SET_FORM_FIELDS_VIA_URL_PARAMETERS                  => 0,
        ];

        $updateSet = array_merge(
            $updateSet,
            MM_WPFS_LicenseManager::getInstance()->getLicenseOptionDefaults()
        );

        $this->options->setNonExistentSeveral( $updateSet );

        $liveSecret = $this->options->get( MM_WPFS_Options::OPTION_API_LIVE_SECRET_KEY );

        $testSecret = $this->options->get( MM_WPFS_Options::OPTION_API_TEST_SECRET_KEY );

        $useLivePlatform = $liveSecret === 'YOUR_LIVE_SECRET_KEY';
        $useTestPlatform = $testSecret === 'YOUR_TEST_SECRET_KEY';

        $updateSet = [
            MM_WPFS_Options::OPTION_USE_WP_TEST_PLATFORM                                 => $useTestPlatform ? '1' : '0',
            MM_WPFS_Options::OPTION_USE_WP_LIVE_PLATFORM                                 => $useLivePlatform ? '1' : '0',
        ];

        $updateSet = array_merge(
            $updateSet,
            MM_WPFS_LicenseManager::getInstance()->getLicenseOptionDefaults()
        );

        $this->options->setNonExistentSeveral( $updateSet );

        return $updateSet;
    }

    /**
     * Generates a unique random token for authenticating webhook callbacks.
     *
     * @return string
     */
    private function createWebhookToken() {
        $siteURL           = get_site_url();
        $randomToken       = hash( 'md5', rand() );
        $generatedPassword = substr( hash( 'sha512', rand() ), 0, 6 );

        return hash( 'md5', $siteURL . '|' . $randomToken . '|' . $generatedPassword );
    }

    public static function setup_db( $network_wide ) {
        if ( $network_wide ) {
            MM_WPFS_Utils::log( "setup_db() - Activating network-wide" );
            if ( function_exists( 'get_sites' ) && function_exists( 'get_current_network_id' ) ) {
                $site_ids = get_sites( array( 'fields' => 'ids', 'network_id' => get_current_network_id() ) );
            } else {
                $site_ids = MM_WPFS_Database::get_site_ids();
            }

            foreach ( $site_ids as $site_id ) {
                switch_to_blog( $site_id );
                self::setup_db_single_site();
                restore_current_blog();
            }
        } else {
            MM_WPFS_Utils::log( "setup_db() - Activating for single site" );
            self::setup_db_single_site();
        }
    }

    public static function setup_db_single_site() {
        MM_WPFS_Database::fullstripe_setup_db();
        MM_WPFS_Patcher::applyPatches();
    }

    function hooks() {

        add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2 );
        add_filter( MM_WPFS::FILTER_NAME_FORM_FIELD_CONFIGURATION, array( $this, 'loggedInEmailConfiguration' ), 5, 2 );
        add_filter( MM_WPFS::FILTER_NAME_FORM_FIELD_CONFIGURATION, array( $this, 'enableAllFormFields' ), 9, 2 );

        add_action( 'fullstripe_update_email_template_defaults', array( $this, 'updateEmailTemplateDefaults' ), 10, 0 );
        add_action( 'wp_head', array( $this, 'fullstripe_wp_head' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'fullstripe_enqueue_scripts_and_styles' ) );

        add_shortcode( self::SHORTCODE_FULLSTRIPE_FORM, array( $this, 'fullstripe_form' ) );

        add_filter( 'script_loader_tag', array( $this, 'addAsyncDeferAttributes' ), 10, 2 );

        do_action( 'fullstripe_main_hooks_action' );
    }

    public function addAsyncDeferAttributes( $tag, $handle ) {
        if ( MM_WPFS::HANDLE_GOOGLE_RECAPTCHA_V_2 !== $handle ) {
            return $tag;
        }

        return str_replace( ' src', ' async defer src', $tag );
    }

    protected function isWpfsJsInclude( $handle ) {
        return in_array( $handle, $this->wpfsJsHandles );
    }

    public static function getInstance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new MM_WPFS();
        }

        return self::$instance;
    }

    public function updateEmailTemplateDefaults() {
        MM_WPFS_Mailer::updateDefaultEmailTemplatesInOptions( $this->options );
    }

    /**
     * @param $context MM_WPFS_StaticContext
     * @return float|int|string
     */
    public static function getCustomFieldMaxCount( $context ) {
        $customInputFieldMaxCount = $context->getOptions()->get( MM_WPFS_Options::OPTION_CUSTOM_INPUT_FIELD_MAX_COUNT );
        if ( is_numeric( $customInputFieldMaxCount ) ) {
            return $customInputFieldMaxCount;
        }

        return self::DEFAULT_CUSTOM_INPUT_FIELD_MAX_COUNT;
    }

    public function plugin_action_links( $links, $file ) {
        static $currentPlugin;

        if ( ! $currentPlugin ) {
            $currentPlugin = plugin_basename( 'wp-full-stripe/wp-full-stripe.php' );
        }

        if ( $file == $currentPlugin ) {
            $settingsLabel =
                /* translators: Link label displayed on the Plugins page in WP admin */
                __( 'Settings', 'wp-full-stripe-admin' );
            $settingsLink  = '<a href="' . menu_page_url( MM_WPFS_Admin_Menu::SLUG_SETTINGS, false ) . '">' . esc_html( $settingsLabel ) . '</a>';
            array_unshift( $links, $settingsLink );
        }

        return $links;
    }

    public function enableAllFormFields( $config, $params ) {
        $result = $config;

        $setFormFieldsViaURlParameters = $this->options->get( MM_WPFS_Options::OPTION_SET_FORM_FIELDS_VIA_URL_PARAMETERS ) == '1';
        if ( $setFormFieldsViaURlParameters ) {
            foreach( $result as $field => $config ) {
                WPFS_API_v2::setIsFormFieldConfigurable( $result, $field, true );
            }
        }

        return $result;
    }

    public function loggedInEmailConfiguration( $config, $params ) {
        $result = $config;

        $fillInEmail = $this->options->get( MM_WPFS_Options::OPTION_FILL_IN_EMAIL_FOR_LOGGED_IN_USERS ) == '1';
        if ( $fillInEmail && is_user_logged_in() ) {
            if ( empty( WPFS_API_v2::getFormFieldValue( $result, MM_WPFS_ConfigurableFormFields::FIELD_EMAIL ))) {
                WPFS_API_v2::setIsFormFieldConfigurable( $result, MM_WPFS_ConfigurableFormFields::FIELD_EMAIL, true );
                WPFS_API_v2::setFormFieldValue( $result, MM_WPFS_ConfigurableFormFields::FIELD_EMAIL, wp_get_current_user()->user_email );
            }
        }

        return $result;
    }

    public function getFormFieldConfiguration( $urlParams, $formType, $formName ) {
        $result = MM_WPFS_ConfigurableFormFields::generateFieldConfiguration(
            MM_WPFS_ConfigurableFormFieldsFactory::createConfiguration( $formType )->getFields()
        );
        $result = MM_WPFS_ConfigurableFormFields::addUrlParamsToConfiguration( $result, $urlParams );

        try {
            $params = [
                'formType'  => $formType,
                'formName'  => $formName,
            ];

            $result = apply_filters( MM_WPFS::FILTER_NAME_FORM_FIELD_CONFIGURATION, $result, $params );
        } catch ( Exception $ex ) {
            $this->logger->error(__FUNCTION__, "Cannot filter configurable form fields for form '{$formName}'", $ex );
        }

        return $result;
    }

    /**
     * Generalized function to handle the new shortcode format
     *
     * @param $atts
     *
     * @return mixed|void
     */
    function fullstripe_form( $atts ) {
        $content = '';
        ob_start();

        $this->logger->debug( __FUNCTION__, 'CALLED' );

        try {
            $formType = self::FORM_TYPE_INLINE_PAYMENT;
            $formName = 'default';
            if ( array_key_exists( 'type', $atts ) ) {
                $formType = $atts['type'];
            }
            if ( array_key_exists( 'name', $atts ) ) {
                $formName = $atts['name'];
            }
            $formType = str_replace(["\u{201c}", "\u{201d}"], '', $formType);
            $formName = str_replace(["\u{201c}", "\u{201d}"], '', $formName);
            $formType = MM_WPFS_Shortcode::normalizeShortCodeFormType($formType);
            $form = $this->getFormByTypeAndName( $formType, $formName );

            $fieldConfiguration = $this->getFormFieldConfiguration( $_GET, $formType, $formName );

            if (( self::FORM_TYPE_INLINE_PAYMENT === $formType ||
                    self::FORM_TYPE_CHECKOUT_PAYMENT === $formType ) &&
                MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $form->customAmount ) {

                $customAmountParamValue = isset( $_GET[ self::HTTP_PARAM_NAME_AMOUNT ] ) ? sanitize_text_field( $_GET[ self::HTTP_PARAM_NAME_AMOUNT ] ) : null;

                if ( ! empty( $customAmountParamValue ) ) {
                    $customAmount = apply_filters( self::FILTER_NAME_SET_CUSTOM_AMOUNT, 0, $formName, $customAmountParamValue );

                    if ( $customAmount !== 0 ) {
                        WPFS_API_v2::setFormFieldValue( $fieldConfiguration, MM_WPFS_ConfigurableFormFields::FIELD_AMOUNT, $customAmount );
                    }
                }
            }

            if ( ! is_null( $form ) ) {
                $view = null;

                if ( self::FORM_TYPE_INLINE_PAYMENT === $formType ) {
                    $view = new MM_WPFS_InlinePaymentFormView( $form, $fieldConfiguration, $this->stripe, $this->loggerService );
                } elseif ( self::FORM_TYPE_INLINE_SUBSCRIPTION === $formType ) {
                    $view         = new MM_WPFS_InlineSubscriptionFormView( $form, $fieldConfiguration, $this->stripe, $this->loggerService );
                } elseif ( self::FORM_TYPE_INLINE_SAVE_CARD === $formType ) {
                    $view = new MM_WPFS_InlineSaveCardFormView( $form, $fieldConfiguration, $this->loggerService );
                } elseif ( self::FORM_TYPE_INLINE_DONATION === $formType ) {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $view = new MM_WPFS_InlineDonationFormView( $form, $fieldConfiguration, $this->loggerService );
                } elseif ( self::FORM_TYPE_CHECKOUT_PAYMENT === $formType ) {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $view = new MM_WPFS_CheckoutPaymentFormView( $form, $fieldConfiguration, $this->stripe, $this->loggerService );
                } elseif ( self::FORM_TYPE_CHECKOUT_SUBSCRIPTION === $formType ) {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $view = new MM_WPFS_CheckoutSubscriptionFormView( $form, $fieldConfiguration, $this->stripe, $this->loggerService );
                } elseif ( self::FORM_TYPE_CHECKOUT_SAVE_CARD === $formType ) {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $view = new MM_WPFS_CheckoutSaveCardFormView( $form, $fieldConfiguration, $this->loggerService );
                } elseif ( self::FORM_TYPE_CHECKOUT_DONATION === $formType ) {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $view = new MM_WPFS_CheckoutDonationFormView( $form, $fieldConfiguration, $this->loggerService );
                }

                $selectedPlanId = null;
                if ( $view instanceof MM_WPFS_SubscriptionFormView ) {
                    $isSimpleButtonSubscription = $view instanceof MM_WPFS_CheckoutSubscriptionFormView && 1 == $form->simpleButtonLayout;
                    if ( ! $isSimpleButtonSubscription ) {
                        $selectedPlanParamValue = isset( $_GET[ self::HTTP_PARAM_NAME_PLAN ] ) ? sanitize_text_field( $_GET[ self::HTTP_PARAM_NAME_PLAN ] ) : null;
                        // $selectedPlanId is used in the view included below
                        $selectedPlanId = apply_filters( self::FILTER_NAME_SELECT_SUBSCRIPTION_PLAN, null, $view->getFormName(), $view->getSelectedStripePlanIds(), $selectedPlanParamValue );
                    }
                }

                if ( false === $this->loadScriptsAndStylesWithActionHook ) {
                    $renderedForms = self::getRenderedForms()->renderLater( $formType );
                    if ( $renderedForms->getTotal() == 1 ) {
                        $this->fullstripe_load_css();
                        $this->fullstripe_load_js();
                        $this->fullstripe_set_common_js_variables();
                    }
                }

                $popupFormSubmit = null;
                if ( isset( $_GET[ MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH ] ) ) {
                    $submitHash = $_GET[ MM_WPFS_CheckoutSubmissionService::STRIPE_CALLBACK_PARAM_WPFS_POPUP_FORM_SUBMIT_HASH ];
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $popupFormSubmit = $this->checkoutSubmissionService->retrieveSubmitEntry( $submitHash );

                    $this->logger->debug( __FUNCTION__, 'popupFormSubmit=' . print_r( $popupFormSubmit, true ) );

                    if ( isset( $popupFormSubmit ) && $popupFormSubmit->formHash === $view->getFormHash() ) {
                        if (
                            MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_CREATED === $popupFormSubmit->status
                            || MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_PENDING === $popupFormSubmit->status
                            || MM_WPFS_CheckoutSubmissionService::POPUP_FORM_SUBMIT_STATUS_COMPLETE === $popupFormSubmit->status
                        ) {
                            // tnagy we do not render messages for created/complete submissions
                            $popupFormSubmit = null;
                        } else {
                            // tnagy we set the form submission to complete, the last message will be shown when the shortcode renders
                            $this->checkoutSubmissionService->updateSubmitEntryWithComplete( $popupFormSubmit );
                        }
                    }
                }

                /** @noinspection PhpIncludeInspection */
                include MM_WPFS_Assets::templates( 'forms/wpfs-form.php' );
            } else {
                include MM_WPFS_Assets::templates( 'forms/wpfs-form-not-found.php' );
            }

        } catch ( Exception $ex ) {
            $this->logger->error( __FUNCTION__, 'Error while rendering shortcode', $ex );

            include MM_WPFS_Assets::templates( 'forms/wpfs-form-error.php' );
        }

        $content = ob_get_clean();

        return apply_filters( 'fullstripe_form_output', $content );
    }

    /**
     * Returns a form from database identified by type and name.
     *
     * @param $formType
     * @param $formName
     *
     * @return mixed|null
     */
    function getFormByTypeAndName( $formType, $formName ) {
        $form = null;

        if ( self::FORM_TYPE_INLINE_PAYMENT === $formType ) {
            $form = $this->database->getInlinePaymentFormByName( $formName );
        } elseif ( self::FORM_TYPE_INLINE_SUBSCRIPTION === $formType ) {
            $form = $this->database->getInlineSubscriptionFormByName( $formName );
        } elseif ( self::FORM_TYPE_INLINE_SAVE_CARD === $formType ) {
            $form = $this->database->getInlinePaymentFormByName( $formName );
        } elseif ( self::FORM_TYPE_INLINE_DONATION === $formType ) {
            $form = $this->database->getInlineDonationFormByName( $formName );
        } elseif ( self::FORM_TYPE_CHECKOUT_PAYMENT === $formType ) {
            $form = $this->database->getCheckoutPaymentFormByName( $formName );
        } elseif ( self::FORM_TYPE_CHECKOUT_SUBSCRIPTION === $formType ) {
            $form = $this->database->getCheckoutSubscriptionFormByName( $formName );
        } elseif ( self::FORM_TYPE_CHECKOUT_SAVE_CARD === $formType ) {
            $form = $this->database->getCheckoutPaymentFormByName( $formName );
        } elseif ( self::FORM_TYPE_CHECKOUT_DONATION === $formType ) {
            $form = $this->database->getCheckoutDonationFormByName( $formName );
        }

        return $form;
    }

    /**
     * @return WPFS_RenderedFormData
     */
    public static function getRenderedForms() {
        if ( ! array_key_exists( self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS, $_REQUEST ) ) {
            $_REQUEST[ self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS ] = new WPFS_RenderedFormData();
        }

        return $_REQUEST[ self::REQUEST_PARAM_NAME_WPFS_RENDERED_FORMS ];
    }

    /**
     * Register and enqueue WPFS styles
     */
    public function fullstripe_load_css() {
        if ( $this->includeDefaultStyles ) {

            wp_register_style( self::HANDLE_STYLE_WPFS_VARIABLES, MM_WPFS_Assets::css( 'wpfs-variables.css' ), null, MM_WPFS::VERSION );
            wp_register_style( self::HANDLE_STYLE_WPFS_FORMS, MM_WPFS_Assets::css( 'wpfs-forms.css' ), array( self::HANDLE_STYLE_WPFS_VARIABLES ), MM_WPFS::VERSION );

            wp_enqueue_style( self::HANDLE_STYLE_WPFS_FORMS );
        }

        do_action( 'fullstripe_load_css_action' );
    }

    /**
     * Register and enqueue WPFS scripts
     */
    public function fullstripe_load_js() {
        $source = add_query_arg(
            array(
                'render' => 'explicit'
            ),
            self::SOURCE_GOOGLE_RECAPTCHA_V2_API_JS
        );
        wp_register_script( self::HANDLE_GOOGLE_RECAPTCHA_V_2, $source, null, MM_WPFS::VERSION, true /* in footer */ );
        wp_register_script( self::HANDLE_SPRINTF_JS, MM_WPFS_Assets::scripts( 'sprintf.min.js' ), null, MM_WPFS::VERSION );
        wp_register_script( self::HANDLE_STRIPE_JS_V_3, 'https://js.stripe.com/v3/', array( 'jquery' ) );
        wp_register_script( self::HANDLE_WP_FULL_STRIPE_UTILS_JS, MM_WPFS_Assets::scripts( 'wpfs-utils.js' ), null, MM_WPFS::VERSION );

        wp_enqueue_script( self::HANDLE_SPRINTF_JS );
        wp_enqueue_script( self::HANDLE_STRIPE_JS_V_3 );
        wp_enqueue_script( self::HANDLE_WP_FULL_STRIPE_UTILS_JS );
        if (
            MM_WPFS_ReCaptcha::getSecureInlineForms( $this->staticContext )
            || MM_WPFS_ReCaptcha::getSecureCheckoutForms( $this->staticContext )
        ) {
            $dependencies = array(
                'jquery',
                'jquery-ui-core',
                'jquery-ui-selectmenu',
                'jquery-ui-autocomplete',
                'jquery-ui-tooltip',
                'jquery-ui-spinner',
                self::HANDLE_SPRINTF_JS,
                self::HANDLE_WP_FULL_STRIPE_UTILS_JS,
                self::HANDLE_STRIPE_JS_V_3,
                self::HANDLE_GOOGLE_RECAPTCHA_V_2
            );
        } else {
            $dependencies = array(
                'jquery',
                'jquery-ui-core',
                'jquery-ui-selectmenu',
                'jquery-ui-autocomplete',
                'jquery-ui-tooltip',
                'jquery-ui-spinner',
                self::HANDLE_SPRINTF_JS,
                self::HANDLE_WP_FULL_STRIPE_UTILS_JS,
                self::HANDLE_STRIPE_JS_V_3
            );
        }
        wp_enqueue_script( self::HANDLE_WP_FULL_STRIPE_JS, MM_WPFS_Assets::scripts( 'wpfs.js' ), $dependencies, MM_WPFS::VERSION );

        do_action( 'fullstripe_load_js_action' );
    }

    function fullstripe_set_common_js_variables() {
        $wpfsFormOptions = array(
            self::JS_VARIABLE_AJAX_URL                      => admin_url( 'admin-ajax.php' ),
            self::JS_VARIABLE_GOOGLE_RECAPTCHA_SITE_KEY     => MM_WPFS_ReCaptcha::getSiteKey( $this->staticContext ),
            self::JS_VARIABLE_FORM_FIELDS                   => array(
                'inlinePayment'         => MM_WPFS_InlinePaymentFormView::getFields(),
                'inlineSaveCard'        => MM_WPFS_InlineSaveCardFormView::getFields(),
                'inlineSubscription'    => MM_WPFS_InlineSubscriptionFormView::getFields(),
                'inlineDonation'        => MM_WPFS_InlineDonationFormView::getFields(),
                'checkoutPayment'       => MM_WPFS_CheckoutPaymentFormView::getFields(),
                'checkoutSaveCard'      => MM_WPFS_CheckoutSaveCardFormView::getFields(),
                'checkoutSubscription'  => MM_WPFS_CheckoutSubscriptionFormView::getFields(),
                'checkoutDonation'      => MM_WPFS_CheckoutDonationFormView::getFields(),
            ),
            self::JS_VARIABLE_L10N                          => array(
                'validation_errors'                      => array(
                    'internal_error'                         =>
                    /* translators: Banner message of internal error when no error message is returned by the application */
                        __( 'An internal error occurred.', 'wp-full-stripe' ),
                    'internal_error_title'                   =>
                    /* translators: Banner title of internal error */
                        __( 'Internal Error', 'wp-full-stripe' ),
                    'mandatory_field_is_empty'               =>
                    /* translators: Error message for required fields when empty.
                     * p1: custom input field label
                     */
                        __( "Please enter a value for '%s'", 'wp-full-stripe' ),
                    'custom_payment_amount_value_is_invalid' =>
                    /* translators: Field validation error message when payment amount is empty or invalid */
                        __( 'Payment amount is invalid', 'wp-full-stripe' ),
                    'invalid_payment_amount'                 =>
                    /* translators: Banner message when the payment amount cannot be determined (the form has been tampered with) */
                        __( 'Cannot determine payment amount', 'wp-full-stripe' ),
                    'invalid_payment_amount_title'           =>
                    /* translators: Banner title when the payment amount cannot be determined (the form has been tampered with) */
                        __( 'Invalid payment amount', 'wp-full-stripe' )
                ),
                'stripe_errors'                          => array(
                    MM_WPFS_Stripe::INVALID_NUMBER_ERROR               => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_NUMBER_ERROR ),
                    MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_MONTH     => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_MONTH ),
                    MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_YEAR      => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_NUMBER_ERROR_EXP_YEAR ),
                    MM_WPFS_Stripe::INVALID_EXPIRY_MONTH_ERROR         => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_EXPIRY_MONTH_ERROR ),
                    MM_WPFS_Stripe::INVALID_EXPIRY_YEAR_ERROR          => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_EXPIRY_YEAR_ERROR ),
                    MM_WPFS_Stripe::INVALID_CVC_ERROR                  => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INVALID_CVC_ERROR ),
                    MM_WPFS_Stripe::INCORRECT_NUMBER_ERROR             => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INCORRECT_NUMBER_ERROR ),
                    MM_WPFS_Stripe::EXPIRED_CARD_ERROR                 => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::EXPIRED_CARD_ERROR ),
                    MM_WPFS_Stripe::INCORRECT_CVC_ERROR                => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INCORRECT_CVC_ERROR ),
                    MM_WPFS_Stripe::INCORRECT_ZIP_ERROR                => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::INCORRECT_ZIP_ERROR ),
                    MM_WPFS_Stripe::CARD_DECLINED_ERROR                => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::CARD_DECLINED_ERROR ),
                    MM_WPFS_Stripe::MISSING_ERROR                      => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::MISSING_ERROR ),
                    MM_WPFS_Stripe::PROCESSING_ERROR                   => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::PROCESSING_ERROR ),
                    MM_WPFS_Stripe::MISSING_PAYMENT_INFORMATION        => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::MISSING_PAYMENT_INFORMATION ),
                    MM_WPFS_Stripe::COULD_NOT_FIND_PAYMENT_INFORMATION => $this->stripe->resolveErrorMessageByCode( MM_WPFS_Stripe::COULD_NOT_FIND_PAYMENT_INFORMATION )
                ),
                'subscription_charge_interval_templates' => array(
                    'daily'            => __( 'Subscription will be charged every day.', 'wp-full-stripe' ),
                    'weekly'           => __( 'Subscription will be charged every week.', 'wp-full-stripe' ),
                    'monthly'          => __( 'Subscription will be charged every month.', 'wp-full-stripe' ),
                    'yearly'           => __( 'Subscription will be charged every year.', 'wp-full-stripe' ),
                    'y_days'           => __( 'Subscription will be charged every %d days.', 'wp-full-stripe' ),
                    'y_weeks'          => __( 'Subscription will be charged every %d weeks.', 'wp-full-stripe' ),
                    'y_months'         => __( 'Subscription will be charged every %d months.', 'wp-full-stripe' ),
                    'y_years'          => __( 'Subscription will be charged every %d years.', 'wp-full-stripe' ),
                    'x_times_daily'    => __( 'Subscription will be charged every day, for %d occasions.', 'wp-full-stripe' ),
                    'x_times_weekly'   => __( 'Subscription will be charged every week, for %d occasions.', 'wp-full-stripe' ),
                    'x_times_monthly'  => __( 'Subscription will be charged every month, for %d occasions.', 'wp-full-stripe' ),
                    'x_times_yearly'   => __( 'Subscription will be charged every year, for %d occasions.', 'wp-full-stripe' ),
                    'x_times_y_days'   => __( 'Subscription will be charged every %1$d days, for %2$d occasions.', 'wp-full-stripe' ),
                    'x_times_y_weeks'  => __( 'Subscription will be charged every %1$d weeks, for %2$d occasions.', 'wp-full-stripe' ),
                    'x_times_y_months' => __( 'Subscription will be charged every %1$d months, for %2$d occasions.', 'wp-full-stripe' ),
                    'x_times_y_years'  => __( 'Subscription will be charged every %1$d years, for %2$d occasions.', 'wp-full-stripe' ),
                ),
                'subscription_pricing_templates' => array(
                    'daily'            => __( '%1$s / day', 'wp-full-stripe' ),
                    'weekly'           => __( '%1$s / week', 'wp-full-stripe' ),
                    'monthly'          => __( '%1$s / month', 'wp-full-stripe' ),
                    'yearly'           => __( '%1$s / year', 'wp-full-stripe' ),
                    'x_days'           => __( '%1$s / %2$d days', 'wp-full-stripe' ),
                    'x_weeks'          => __( '%1$s / %2$d weeks', 'wp-full-stripe' ),
                    'x_months'         => __( '%1$s / %2$d months', 'wp-full-stripe' ),
                    'x_years'          => __( '%1$s / %2$d years', 'wp-full-stripe' ),
                ),
                'products' => array(
                    'default_product_name'       => __( 'My product', 'wp-full-stripe' ),
                    'other_amount_label'         => __( 'Other amount', 'wp-full-stripe' ),
                    'the_selected_product_label' => __( 'The selected product', 'wp-full-stripe' ),
                ),
                'application_errors' => array (
                    /* translators: Banner title of application error when instantiating the Stripe object */
                    'stripe_instantiation_error_title' => __( 'Stripe error', 'wp-full-stripe' ),
                    /* translators: Error message when instantiating the Stripe object
                     * p1: the message of the exception thrown
                     */
                    'stripe_instantiation_error_message' => __( "Cannot initialize Stripe: %s", 'wp-full-stripe' )
                ),
                'product_pricing' => array(
                    /* translators: Default tax label */
                    'default_tax_label'              => __('Tax', 'wp-full-stripe' ),
                    /* translators: Default label of coupon line items */
                    'default_coupon_label'           => __('Coupon', 'wp-full-stripe' ),
                    /* translators: Tax line item label with percentage, inclusive */
                    'tax_label_inclusive_percentage' => __('%s (%s%%, inclusive)', 'wp-full-stripe' ),
                    /* translators: Tax line item label with percentage */
                    'tax_label_percentage'           => __('%s (%s%%)', 'wp-full-stripe' ),
                    /* translators: Tax line item label, inclusive */
                    'tax_label_inclusive'            => __('%s (inclusive)', 'wp-full-stripe' ),
                    /* translators: Tax line item label, no decoration (no percentage, not inclusive) */
                    'tax_label'                      => __('%s', 'wp-full-stripe' ),
                )
            )
        );

        $apiMode = $this->options->get(MM_WPFS_Options::OPTION_API_MODE);
        $isTestMode = $apiMode === MM_WPFS::STRIPE_API_MODE_TEST;
        $isTestPlatform = $this->options->get(MM_WPFS_Options::OPTION_USE_WP_TEST_PLATFORM);
        $isLivePlatform = $this->options->get(MM_WPFS_Options::OPTION_USE_WP_LIVE_PLATFORM);

        $wpfsFormOptions[self::JS_VARIABLE_STRIPE_ACCOUNT_ID] = $isTestMode
            ? $this->options->get(MM_WPFS_Options::OPTION_TEST_ACCOUNT_ID)
            : $this->options->get(MM_WPFS_Options::OPTION_LIVE_ACCOUNT_ID);

        if ($apiMode === self::STRIPE_API_MODE_TEST && $isTestPlatform) {
            $wpfsFormOptions[self::JS_VARIABLE_STRIPE_KEY] = MM_WPFS_Options::OPTION_TEST_STRIPE_PLATFORM_PK;
        } elseif ($apiMode === self::STRIPE_API_MODE_LIVE && $isLivePlatform) {
            $wpfsFormOptions[self::JS_VARIABLE_STRIPE_KEY] = MM_WPFS_Options::OPTION_LIVE_STRIPE_PLATFORM_PK;
        } else {
            $wpfsFormOptions[self::JS_VARIABLE_STRIPE_KEY] = $isTestMode
                ? $this->options->get(MM_WPFS_Options::OPTION_API_TEST_PUBLISHABLE_KEY)
                : $this->options->get(MM_WPFS_Options::OPTION_API_LIVE_PUBLISHABLE_KEY);
        }

        wp_localize_script( self::HANDLE_WP_FULL_STRIPE_JS, self::JS_VARIABLE_WPFS_FORM_OPTIONS, $wpfsFormOptions );
    }

    function fullstripe_wp_head() {
        echo '<style type="text/css" media="screen">' . $this->options->get( MM_WPFS_Options::OPTION_FORM_CUSTOM_CSS ) . '</style>';
    }

    /**
     * Register and enqueue styles and scripts to load for this addon
     */
    public function fullstripe_enqueue_scripts_and_styles() {
        if ( $this->loadScriptsAndStylesWithActionHook ) {
            global $wp;

            $this->logger->debug( __FUNCTION__, 'CALLED, wp=' . print_r( $wp, true ) );

            if ( ! is_null( $wp ) && isset( $wp->request ) ) {
                $pageByPath = get_page_by_path( $wp->request );
                if ( ! is_null( $pageByPath ) && isset( $pageByPath->post_content ) ) {
                    if (
                        has_shortcode( $pageByPath->post_content, self::SHORTCODE_FULLSTRIPE_FORM )
                    ) {
                        $this->fullstripe_load_css();
                        $this->fullstripe_load_js();
                        $this->fullstripe_set_common_js_variables();
                    }
                }
            }
        }
    }

    private function retrieveProductIdsByPriceIds( $priceIds ) {
        try {

            $productIds = $this->stripe->retrieveProductIdsByPriceIds( $priceIds );

            return $productIds;
        } catch ( Exception $ex ) {
            $this->logger->error(__FUNCTION__, "Cannot retrieve products by price ids", $ex);

            return [];
        }
    }

    /**
     * @param $priceIds
     * @param $couponProducts
     * @return bool
     */
    private function doPricesIntersectWithCouponProducts( $priceIds, $couponProducts ) : bool {
        $result = false;

        $productIds = $this->retrieveProductIdsByPriceIds( $priceIds );
        $intersect = array_intersect( $couponProducts, $productIds );

        if ( count( $intersect ) === 0 ) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * @param $form
     * @return array
     */
    private function getProductsByForm( $form ) : array {
        $result = array();

        if ( isset( $form->decoratedProducts ) ) {
            $result = MM_WPFS_Utils::decodeJsonArray( $form->decoratedProducts );
        } elseif ( isset( $form->decoratedPlans ) ) {
            $result = MM_WPFS_Utils::decodeJsonArray( $form->decoratedPlans );
        }
        return $result;
    }

    /**
     * @param $formType
     * @param $formId
     * @return array
     */
    public function getProductsByFormTypeAndId( $formType, $formId ) : array {
        $form = $this->getFormByTypeAndName( $formType, $formId );

        return $this->getProductsByForm( $form );
    }

    /**
     * @param $coupon
     * @param $formType
     * @param $formId
     * @return \StdClass
     */
    public function isCouponApplicableToForm( $coupon, $formType, $formId, $currentPriceId ) : \StdClass {
        $result = new \StdClass;
        $result->applicableToForm = true;
        $result->applicableToProduct = true;

        if ( isset( $coupon->applies_to ) && isset( $coupon->applies_to->products ) ) {
            $savedProducts = $this->getProductsByFormTypeAndId( $formType, $formId );

            if ( isset( $savedProducts ) && ! empty( $savedProducts ) ) {
                $priceIds = MM_WPFS_Pricing::extractPriceIdsFromProductsStatic( $savedProducts );

                if ( isset( $priceIds ) && ! empty( $priceIds ) ) {
                    $result->applicableToForm = $this->doPricesIntersectWithCouponProducts( $priceIds, $coupon->applies_to->products );

                    if ( $result->applicableToForm ) {
                        $result->applicableToProduct = $this->doPricesIntersectWithCouponProducts( [ $currentPriceId ], $coupon->applies_to->products );
                    } else {
                        $result->applicableToProduct = false;
                    }
                }
            }
        }

        return $result;
    }

    private function retrievePriceIdsProductIdsAssociativeArray( $priceIds ) {
        $result = array();

        foreach ( $priceIds as $priceId ) {
            try {
                $price = $this->stripe->retrievePriceWithProductExpanded( $priceId );
                $result[ $priceId ] = $price->product->id;
            } catch ( Exception $ex ) {
                $this->logger->error(__FUNCTION__, "Cannot retrieve price", $ex);
            }
        }

        return $result;
    }

    public function getDiscountedPriceIdsByCouponAndForm( $coupon, $formType, $formId ) {
        $result = [];

        $formProducts = $this->getProductsByFormTypeAndId( $formType, $formId );
        $priceIds = MM_WPFS_Pricing::extractPriceIdsFromProductsStatic( $formProducts );

        if ( isset( $coupon->applies_to ) && isset( $coupon->applies_to->products ) ) {
            $pricesProducts = $this->retrievePriceIdsProductIdsAssociativeArray( $priceIds );
            $discountedPrices = array_intersect( $pricesProducts, $coupon->applies_to->products );

            $result = array_keys( $discountedPrices );
        } else {
            $result = $priceIds;
        }

        return $result;
    }


    /**
     * @return MM_WPFS_Admin_Menu
     */
    public function getAdminMenu() {
        return $this->adminMenu;
    }

    /**
     * @return MM_WPFS_Admin
     */
    public function getAdmin() {
        return $this->admin;
    }
}

class WPFS_RenderedFormData {

    private $inlinePayments = 0;
    private $inlineSubscriptions = 0;
    private $checkoutPayments = 0;
    private $checkoutSubscriptions = 0;
    private $inlineDonations = 0;
    private $checkoutDonations = 0;

    public function renderLater($type ) {
        if ( MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION === $type ) {
            $this->checkoutSubscriptions += 1;
        } elseif ( MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION === $type ) {
            $this->inlineSubscriptions += 1;
        } elseif ( MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT === $type ) {
            $this->checkoutPayments += 1;
        } elseif ( MM_WPFS::FORM_TYPE_INLINE_PAYMENT === $type ) {
            $this->inlinePayments += 1;
        } elseif ( MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD === $type ) {
            $this->checkoutPayments += 1;
        } elseif ( MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD === $type ) {
            $this->inlinePayments += 1;
        } elseif ( MM_WPFS::FORM_TYPE_CHECKOUT_DONATION === $type ) {
            $this->checkoutDonations += 1;
        } elseif ( MM_WPFS::FORM_TYPE_INLINE_DONATION === $type ) {
            $this->inlineDonations += 1;
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getInlinePayments() {
        return $this->inlinePayments;
    }

    /**
     * @return int
     */
    public function getInlineSubscriptions() {
        return $this->inlineSubscriptions;
    }

    /**
     * @return int
     */
    public function getCheckoutPayments() {
        return $this->checkoutPayments;
    }

    /**
     * @return int
     */
    public function getCheckoutSubscriptions() {
        return $this->checkoutSubscriptions;
    }

    /**
     * @return int
     */
    public function getInlineDonations() {
        return $this->inlineDonations;
    }

    /**
     * @return int
     */
    public function getCheckoutDonations() {
        return $this->checkoutDonations;
    }

    /**
     * @return int
     */
    public function getTotal() {
        return $this->inlinePayments + $this->inlineSubscriptions + $this->checkoutPayments + $this->checkoutSubscriptions + $this->inlineDonations + $this->checkoutDonations;
    }

}

/**
 * Class WPFS_UserFriendlyException
 *
 * This exception can be thrown in action and event hooks, and it's content (title, message)
 * will be displayed as a global message above the form which has invoked it.
 */
class WPFS_UserFriendlyException extends Exception {
    protected $_title;

    /**
     * WPFS_UserFriendlyException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct( $message, $code, $previous );
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->_title = $title;
    }
}

MM_WPFS::getInstance();
