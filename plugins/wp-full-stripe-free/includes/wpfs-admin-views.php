<?php
/** @noinspection PhpMultipleClassesDeclarationsInOneFile */

interface MM_WPFS_AdminViewConstants {
    const FORM_HASH_ADMIN_CREATE_FORM = 'wpfsAdminCreateForm';
    const FORM_HASH_ADMIN_SAVE_STRIPE_ACCOUNT = 'wpfsAdminSaveStripeAccount';
    const FORM_HASH_ADMIN_CUSTOMER_PORTAL = 'wpfsAdminCustomerPortal';
    const FORM_HASH_ADMIN_SECURITY = 'wpfsAdminSecurity';
    const FORM_HASH_ADMIN_EMAIL_OPTIONS = 'wpfsAdminEmailOptions';
    const FORM_HASH_ADMIN_EMAIL_TEMPLATES = 'wpfsAdminEmailTemplates';
    const FORM_HASH_ADMIN_FORMS_OPTIONS = 'wpfsAdminFormsOptions';
    const FORM_HASH_ADMIN_FORMS_APPEARANCE = 'wpfsAdminFormsAppearance';
    const FORM_HASH_ADMIN_WP_DASHBOARD = 'wpfsAdminWpDashboard';
    const FORM_HASH_ADMIN_ADDONS = 'wpfsAdminAddOns';
    const FORM_HASH_ADMIN_LOG_LEVELS = 'wpfsAdminLogLevels';
    const FORM_HASH_ADMIN_LOG_EMPTY = 'wpfsAdminLogEmpty';
    const FORM_HASH_ADMIN_INLINE_SAVE_CARD_FORM = 'wpfsAdminInlineSaveCardForm';
    const FORM_HASH_ADMIN_CHECKOUT_SAVE_CARD_FORM = 'wpfsAdminCheckoutSaveCardForm';
    const FORM_HASH_ADMIN_INLINE_DONATION_FORM = 'wpfsAdminInlineDonationForm';
    const FORM_HASH_ADMIN_CHECKOUT_DONATION_FORM = 'wpfsAdminCheckoutDonationForm';
    const FORM_HASH_ADMIN_INLINE_PAYMENT_FORM = 'wpfsAdminInlinePaymentForm';
    const FORM_HASH_ADMIN_CHECKOUT_PAYMENT_FORM = 'wpfsAdminCheckoutPaymentForm';
    const FORM_HASH_ADMIN_INLINE_SUBSCRIPTION_FORM = 'wpfsAdminInlineSubscriptionForm';
    const FORM_HASH_ADMIN_CHECKOUT_SUBSCRIPTION_FORM = 'wpfsAdminCheckoutSubscriptionForm';
}

abstract class MM_WPFS_AdminView implements MM_WPFS_AdminViewConstants, MM_WPFS_FormViewConstants {

    /** @var MM_WPFS_Control */
    protected $action;
    /** @var MM_WPFS_Control */
    protected $formGetParameters;
    /** @var MM_WPFS_Control */
    protected $submitButton;

    protected $formHash;
    protected $attributes = array();

	/**
	 * MM_WPFS_FormView constructor.
	 *
	 */
    public function __construct() {
	    $this->attributes        = $this->getFormAttributes();
	    $this->action            = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_ACTION, null, null, null, null );
	    $this->formGetParameters = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_GET_PARAMETERS, null, null, null, null );
	    $this->submitButton      = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_FormViewConstants::BUTTON_SUBMIT, null, __( 'Create and Edit form', 'wp-full-stripe-admin' ), null, null );

        $this->action->setAttributes(
            array(
                'type' => 'hidden'
            )
        );
        $this->formGetParameters->setAttributes(
            array(
                'type' => 'hidden'
            )
        );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ACTION ]              = '';
        $attributes[ self::ATTR_METHOD ]              = self::ATTR_METHOD_VALUE_POST;
        $attributes[ self::ATTR_CLASS ]               = self::ATTR_CLASS_VALUE_WPFS_FORM;

        return $attributes;
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            self::FIELD_ACTION                    => MM_WPFS_ControlUtils::inputHidden( self::FIELD_ACTION ),
        );

        return $fields;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function submitButton() {
        return $this->submitButton;
    }

    public function formAttributes() {
        $attributesAsString = MM_WPFS_Control::attributesAsString( $this->attributes );

        echo( $attributesAsString );
    }

    /**
     * @return MM_WPFS_Control
     */
    public function action() {
        return $this->action;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function formGetParameters() {
        return $this->formGetParameters;
    }

}

interface MM_WPFS_Admin_CreateFormViewConstants {

    const FIELD_FORM_DISPLAY_NAME = 'wpfs-form-display-name';
    const FIELD_FORM_NAME = 'wpfs-form-name';
    const FIELD_FORM_TYPE = 'wpfs-form-type';
    const FIELD_FORM_LAYOUT = 'wpfs-form-layout';

    const FIELD_ACTION_VALUE_CREATE_FORM = 'wpfs-create-form';
}


class MM_WPFS_Admin_CreateFormView extends MM_WPFS_AdminView {
    /** @var MM_WPFS_Control */
    protected $displayName;
    /** @var MM_WPFS_Control */
    protected $name;
    /** @var MM_WPFS_Control */
    protected $type;
    /** @var MM_WPFS_Control */
    protected $layout;

    /**
     * MM_WPFS_Admin_CreateFormView constructor.
     * @param $params array
     */
    public function __construct( $params ) {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_CREATE_FORM;

        parent::__construct();

        $formType = $this->getFormTypeFromParams( $params );

        $this->action->setValue( MM_WPFS_Admin_CreateFormViewConstants::FIELD_ACTION_VALUE_CREATE_FORM );

        $this->displayName = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_DISPLAY_NAME, null, null,
            /* translators: Form field label for the display name of the form */
            __( 'Display name', 'wp-full-stripe-admin' ), null );
        $this->name = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_NAME, null, null,
            /* translators: Form field label for the identifier of the form */
            __( 'Identifier', 'wp-full-stripe-admin' ), null );


        $this->type = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_TYPE, null, null,
            /* translators: Form field label for the type of the form */
            __( 'Form type', 'wp-full-stripe-admin' ), null );

        $typeOptionIndex = 0;
        $typeOptions = array();

        $paymentTypeOption = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_TYPE, null, null,
            /* translators: One-time payment form type */
            __( 'One-time payment', 'wp-full-stripe-admin' ), $typeOptionIndex );
        $paymentTypeAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input',
            'value'     => MM_WPFS::FORM_TYPE_PAYMENT,
        );
        if ( $formType === MM_WPFS::FORM_TYPE_PAYMENT ) {
            $paymentTypeAttributes[ 'checked' ] = 'checked';
        }
        $paymentTypeOption->setAttributes( $paymentTypeAttributes );
        $paymentTypeOption->setMetadata( array(
            'description'   =>
                /* translators: Description of the one-time payment form type */
                __( 'Software, ticket, physical goods, and settle invoices', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-book-close-bookmark'
        ));
        array_push( $typeOptions, $paymentTypeOption );
        $typeOptionIndex++;

        $subscriptionTypeOption = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_TYPE, null, null,
            /* translators: Subscription form type */
            __( 'Subscription', 'wp-full-stripe-admin' ), $typeOptionIndex );
        $paymentTypeAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input',
            'value'     => MM_WPFS::FORM_TYPE_SUBSCRIPTION,
        );
        if ( $formType === MM_WPFS::FORM_TYPE_SUBSCRIPTION ) {
            $paymentTypeAttributes[ 'checked' ] = 'checked';
        }
        $subscriptionTypeOption->setAttributes( $paymentTypeAttributes );
        $subscriptionTypeOption->setMetadata( array(
            'description'   =>
                /* translators: Description of the subscription form type */
                __( 'Online services, consulting, and professional services', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-subscription'
        ));
        array_push( $typeOptions, $subscriptionTypeOption );
        $typeOptionIndex++;

        $donationTypeOption = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_TYPE, null, null,
            /* translators: Donation form type */
            __( 'Donation', 'wp-full-stripe-admin' ), $typeOptionIndex );
        $paymentTypeAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input',
            'value'     => MM_WPFS::FORM_TYPE_DONATION,
        );
        if ( $formType === MM_WPFS::FORM_TYPE_DONATION ) {
            $paymentTypeAttributes[ 'checked' ] = 'checked';
        }
        $donationTypeOption->setAttributes( $paymentTypeAttributes );
        $donationTypeOption->setMetadata( array(
            'description'   =>
                /* translators: Description of the donation form type */
                __( 'Donor platform for your fundraising efforts', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-donation'
        ));
        array_push( $typeOptions, $donationTypeOption );
        $typeOptionIndex++;

        $saveCardTypeOption = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_TYPE, null, null,
            /* translators: Save card form type */
            __( 'Save card', 'wp-full-stripe-admin' ), $typeOptionIndex );
        $paymentTypeAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input',
            'value'     => MM_WPFS::FORM_TYPE_SAVE_CARD,
        );
        if ( $formType === MM_WPFS::FORM_TYPE_SAVE_CARD ) {
            $paymentTypeAttributes[ 'checked' ] = 'checked';
        }
        $saveCardTypeOption->setAttributes( $paymentTypeAttributes );
        $saveCardTypeOption->setMetadata( array(
            'description'   =>
                /* translators: Description of the save card form type */
                __( 'Customer\'s payment information', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-credit-card'
        ));
        array_push( $typeOptions, $saveCardTypeOption );

        $this->type->setOptions( $typeOptions );


        $this->layout = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_LAYOUT, null, null,
            /* translators: Form field label for the layout of the form */
            __( 'Form layout', 'wp-full-stripe-admin' ), null );

        $layoutOptionIndex = 0;
        $layoutOptions = array();

        $inlineLayoutOption = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_LAYOUT, null, null,
            /* translators: Inline layout type on the Create form page  */
            __( 'Inline', 'wp-full-stripe-admin' ), $layoutOptionIndex );
        $inlineLayoutOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input',
            'value'     => MM_WPFS::FORM_LAYOUT_INLINE,
            'checked'   => 'checked'
        ));
        $inlineLayoutOption->setMetadata( array(
            'description'   =>
                /* translators: Description of the inline form layout */
                __( 'Enough screen estate to display the entire form', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-layout-inline'
        ));
        array_push( $layoutOptions, $inlineLayoutOption );
        $layoutOptionIndex++;

        $checkoutLayoutOption = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_LAYOUT, null, null,
            /* translators: Checkout layout type on the Create form page */
            __( 'Checkout', 'wp-full-stripe-admin' ), $layoutOptionIndex );
        $checkoutLayoutOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input',
            'value'     => MM_WPFS::FORM_LAYOUT_CHECKOUT,
        ));
        $checkoutLayoutOption->setMetadata( array(
            'description'   =>
                /* translators: Description of the checkout form layout */
                __( 'When screen estate is constrained, or using pricing tables', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-layout-checkout'
        ));
        array_push( $layoutOptions, $checkoutLayoutOption );

        $this->layout->setOptions( $layoutOptions );
    }

    /**
     * @param $params
     *
     * @return string
     */
    private function getFormTypeFromParams( $params ) : string {
        $types = array(
            MM_WPFS::FORM_TYPE_PAYMENT,
            MM_WPFS::FORM_TYPE_SUBSCRIPTION,
            MM_WPFS::FORM_TYPE_DONATION,
            MM_WPFS::FORM_TYPE_SAVE_CARD
        );

        $type = array_key_exists( MM_WPFS_Admin_Menu::PARAM_NAME_TYPE, $params ) ? $params[ MM_WPFS_Admin_Menu::PARAM_NAME_TYPE ] : null;
        $type = array_search( $type, $types ) !== false ? $type : MM_WPFS::FORM_TYPE_PAYMENT;

        return $type;
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_DISPLAY_NAME => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_DISPLAY_NAME ),
            MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_NAME         => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_NAME ),
            MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_TYPE         => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_TYPE ),
            MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_LAYOUT       => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CreateFormViewConstants::FIELD_FORM_LAYOUT )
        );

        return array_merge( $fields, parent::getFields() );
    }


    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_CreateFormViewConstants::FIELD_ACTION_VALUE_CREATE_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CREATE_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function displayName() {
        return $this->displayName;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function name() {
        return $this->name;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function type() {
        return $this->type;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function layout() {
        return $this->layout;
    }
}

interface MM_WPFS_Admin_ConfigureStripeAccountViewConstants {

    const FIELD_API_TEST_SECRET_KEY = 'wpfs-api-test-secret-key';
    const FIELD_API_TEST_PUBLISHABLE_KEY = 'wpfs-api-test-publishable-key';
    const FIELD_API_LIVE_SECRET_KEY = 'wpfs-api-live-secret-key';
    const FIELD_API_LIVE_PUBLISHABLE_KEY = 'wpfs-api-live-publishable-key';
    const FIELD_API_MODE = 'wpfs-api-mode';
    const FIELD_API_TEST_ACCOUNT_ID = 'wpfs-api-test-account-id';
    const FIELD_API_LIVE_ACCOUNT_ID = 'wpfs-api-live-account-id';
    const CONNECT_ACCOUNT_ONBOARDING = 'wpfs-connect-account-onboarding';
    const FIELD_USE_WP_TEST_PLATFORM = 'wpfs-use-wp-test-platform';
    const FIELD_USE_WP_LIVE_PLATFORM = 'wpfs-use-wp-live-platform';

    const FIELD_ACTION_VALUE_SAVE_STRIPE_ACCOUNT = 'wpfs-save-stripe-account';
}

class MM_WPFS_Admin_ConfigureStripeAccountView extends MM_WPFS_AdminView {
    /** @var MM_WPFS_Control */
    protected $testSecretKey;
    /** @var MM_WPFS_Control */
    protected $testPublishableKey;
    /** @var MM_WPFS_Control */
    protected $liveSecretKey;
    /** @var MM_WPFS_Control */
    protected $livePublishableKey;
    /** @var MM_WPFS_Control */
    protected $apiMode;
    /** @var MM_WPFS_Control */
    protected $connectAccountOnboarding;

    /**
     * MM_WPFS_Admin_ConfigureStripeAccountView constructor.
     */
    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_SAVE_STRIPE_ACCOUNT;

        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_ACTION_VALUE_SAVE_STRIPE_ACCOUNT );

        $this->testSecretKey = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_SECRET_KEY, null, null,
            /* translators: Form field label for API test secret key   */
            __( 'Secret key', 'wp-full-stripe-admin' ), null );
        $this->testPublishableKey = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_PUBLISHABLE_KEY, null, null,
            /* translators: Form field label for API test publishable key   */
            __( 'Publishable key', 'wp-full-stripe-admin' ), null );

        $this->liveSecretKey = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_SECRET_KEY, null, null,
            /* translators: Form field label for API live secret key   */
            __( 'Secret key', 'wp-full-stripe-admin' ), null );
        $this->livePublishableKey = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_PUBLISHABLE_KEY, null, null,
            /* translators: Form field label for API live publishable key   */
            __( 'Publishable key', 'wp-full-stripe-admin' ), null );

        $this->apiMode = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_MODE, null, null,
            /* translators: Label for a toggle which controls the Stripe API mode (test or live)  */
            __( 'Stripe API mode', 'wp-full-stripe-admin' ), null );
        $this->testAccountId = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_ACCOUNT_ID, null, null,
            /* translators: Form field label for API test account ID   */
            __( 'Account ID', 'wp-full-stripe-admin' ), null );
        $this->liveAccountId = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_ACCOUNT_ID, null, null,
            /* translators: Form field label for API live account ID   */
            __( 'Account ID', 'wp-full-stripe-admin' ), null );

        $this->connectAccountOnboarding = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_ADMIN_ConfigureStripeAccountViewConstants::CONNECT_ACCOUNT_ONBOARDING, null, null,
            __( 'Connect Account Onboarding', 'wp-full-stripe-admin'), null );
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_SECRET_KEY        => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_SECRET_KEY ),
            MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_PUBLISHABLE_KEY   => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_TEST_PUBLISHABLE_KEY ),
            MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_SECRET_KEY        => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_SECRET_KEY ),
            MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_PUBLISHABLE_KEY   => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_LIVE_PUBLISHABLE_KEY ),
            MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_MODE                   => MM_WPFS_ControlUtils::checkbox( MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_API_MODE ),
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_ConfigureStripeAccountViewConstants::FIELD_ACTION_VALUE_SAVE_STRIPE_ACCOUNT;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_STRIPE_ACCOUNT;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function testSecretKey(): MM_WPFS_Control {
        return $this->testSecretKey;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function testPublishableKey(): MM_WPFS_Control {
        return $this->testPublishableKey;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function liveSecretKey(): MM_WPFS_Control {
        return $this->liveSecretKey;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function livePublishableKey(): MM_WPFS_Control {
        return $this->livePublishableKey;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function apiMode(): MM_WPFS_Control {
        return $this->apiMode;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function connectAccountOnboarding(): MM_WPFS_Control {
        return $this->connectAccountOnboarding;
    }
}

interface MM_WPFS_Admin_CustomerPortalViewConstants {
    const FIELD_MY_ACCOUNT_CUSTOMERS_CAN_VIEW = 'wpfs-my-account-customers-can-view';
    const FIELD_MY_ACCOUNT_SUBSCRIPTION_SETTINGS = 'wpfs-my-account-subscription-settings';
    const FIELD_MY_ACCOUNT_DISPLAY_SETTINGS = 'wpfs-my-account-display-settings';

    const FIELD_MY_ACCOUNT_SHOW_SUBSCRIPTIONS = 'wpfs-my-account-show-subscriptions';
    const FIELD_MY_ACCOUNT_CANCEL_SUBSCRIPTIONS = 'wpfs-my-account-cancel-subscriptions';
    const FIELD_MY_ACCOUNT_WHEN_CANCEL_SUBSCRIPTONS = 'wpfs-my-account-when-cancel-subscriptions';
    const FIELD_MY_ACCOUNT_UPDOWNGRADE_SUBSCRIPTIONS = 'wpfs-my-account-updowngrade-subscriptions';
    const FIELD_MY_ACCOUNT_SHOW_INVOICES = 'wpfs-my-account-show-invoices';
    const FIELD_MY_ACCOUNT_SCROLLING_PANE_INTO_VIEW = 'wpfs-my-account-scrolling-pane-into-view';

    const FIELD_ACTION_VALUE_SAVE_CUSTOMER_PORTAL = 'wpfs-save-my-account';
}

class MM_WPFS_Admin_CustomerPortalView extends MM_WPFS_AdminView implements MM_WPFS_Admin_CustomerPortalViewConstants {
    /** @var MM_WPFS_Control */
    protected $showSubscriptions;
    /** @var MM_WPFS_Control */
    protected $cancelSubscriptions;
    /** @var MM_WPFS_Control */
    protected $whenCancelSubscriptions;
    /** @var MM_WPFS_Control */
    protected $updowngradeSubscriptions;
    /** @var MM_WPFS_Control */
    protected $showInvoices;
    /** @var MM_WPFS_Control */
    protected $scrollingPaneIntoView;


    /** @var MM_WPFS_Control */
    protected $customersCanManage;
    /** @var MM_WPFS_Control */
    protected $subscriptionSettings;
    /** @var MM_WPFS_Control */
    protected $displaySettings;

    /**
     * MM_WPFS_Admin_MyAccountView constructor.
     */
    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_CUSTOMER_PORTAL;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_ACTION_VALUE_SAVE_CUSTOMER_PORTAL );

        $this->initCustomersCanManage();
        $this->initSubscriptionSettings();
        $this->initDisplaySettings();
    }

    protected function initDisplaySettings() {
        $this->displaySettings = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_MY_ACCOUNT_DISPLAY_SETTINGS, null, null,
            __( 'Display settings', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_MY_ACCOUNT_SCROLLING_PANE_INTO_VIEW, null, null,
            __('Scroll pane into view', 'wp-full-stripe-admin'), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->displaySettings->setOptions( $options );
    }

    protected function initSubscriptionSettings() {
        $this->subscriptionSettings = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_MY_ACCOUNT_SUBSCRIPTION_SETTINGS, null, null,
            __( 'Subscription settings', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_MY_ACCOUNT_UPDOWNGRADE_SUBSCRIPTIONS, null, null,
            __('Customers can upgrade/downgrade subscriptions', 'wp-full-stripe-admin'), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_MY_ACCOUNT_CANCEL_SUBSCRIPTIONS, null, null,
            __('Customers can cancel subscriptions', 'wp-full-stripe-admin'), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input js-cancel-subscriptions',
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->subscriptionSettings->setOptions( $options );


        $this->whenCancelSubscriptions = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_WHEN_CANCEL_SUBSCRIPTONS, null, null,
            /* translators: Form field label for the 'When to cancel subscriptions' option of the form */
            __( 'When to cancel subscriptions', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_WHEN_CANCEL_SUBSCRIPTONS, null, null,
            /* translators: Subscription cancellation type when the subscription is canceled immediately */
            __( 'Immediately', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::CANCEL_SUBSCRIPTION_IMMEDIATELY );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_WHEN_CANCEL_SUBSCRIPTONS, null, null,
            /* translators: Subscription cancellation type when the subscription is canceled at the end of the current recurring period */
            __( 'At period end', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::CANCEL_SUBSCRIPTION_AT_PERIOD_END );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->whenCancelSubscriptions->setOptions( $options );
    }

    protected function initCustomersCanManage() {
        $this->customersCanManage = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_MY_ACCOUNT_CUSTOMERS_CAN_VIEW, null, null,
            __( 'Customers can manage', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_MY_ACCOUNT_SHOW_SUBSCRIPTIONS, null, null,
            __('Subscriptions', 'wp-full-stripe-admin'), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input js-show-subscriptions',
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_MY_ACCOUNT_SHOW_INVOICES, null, null,
            __('Invoices', 'wp-full-stripe-admin'), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->customersCanManage->setOptions( $options );
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_SHOW_SUBSCRIPTIONS           => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_SHOW_SUBSCRIPTIONS ),
            MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_CANCEL_SUBSCRIPTIONS         => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_CANCEL_SUBSCRIPTIONS ),
            MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_WHEN_CANCEL_SUBSCRIPTONS     => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_WHEN_CANCEL_SUBSCRIPTONS ),
            MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_UPDOWNGRADE_SUBSCRIPTIONS    => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_UPDOWNGRADE_SUBSCRIPTIONS ),
            MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_SHOW_INVOICES                => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_SHOW_INVOICES ),
            MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_SCROLLING_PANE_INTO_VIEW     => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_MY_ACCOUNT_SCROLLING_PANE_INTO_VIEW )
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_CustomerPortalViewConstants::FIELD_ACTION_VALUE_SAVE_CUSTOMER_PORTAL;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_CUSTOMER_PORTAL;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function showSubscriptions(): MM_WPFS_Control {
        return $this->showSubscriptions;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function cancelSubscriptions(): MM_WPFS_Control {
        return $this->cancelSubscriptions;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function whenCancelSubscriptions(): MM_WPFS_Control {
        return $this->whenCancelSubscriptions;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function updowngradeSubscriptions(): MM_WPFS_Control {
        return $this->updowngradeSubscriptions;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function showInvoices(): MM_WPFS_Control {
        return $this->showInvoices;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function scrollingPaneIntoView(): MM_WPFS_Control {
        return $this->scrollingPaneIntoView;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function customersCanManage(): MM_WPFS_Control {
        return $this->customersCanManage;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function subscriptionSettings(): MM_WPFS_Control {
        return $this->subscriptionSettings;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function displaySettings(): MM_WPFS_Control {
        return $this->displaySettings;
    }


}

interface MM_WPFS_Admin_SecurityViewConstants {

    const FIELD_SECURITY_SECURE_INLINE_FORMS    = 'wpfs-security-secure-inline-forms';
    const FIELD_SECURITY_SECURE_CHECKOUT_FORMS  = 'wpfs-security-secure-checkout-forms';
    const FIELD_SECURITY_SECURE_CUSTOMER_PORTAL = 'wpfs-security-secure-customer-portal';
    const FIELD_SECURITY_RECAPTCHA_SECRET_KEY   = 'wpfs-security-recaptcha-secret-key';
    const FIELD_SECURITY_RECAPTCHA_SITE_KEY     = 'wpfs-security-recaptcha-site-key';

    const FIELD_ACTION_VALUE_SAVE_SECURITY = 'wpfs-save-security';
}

class MM_WPFS_Admin_SecurityView extends MM_WPFS_AdminView {
    /** @var MM_WPFS_Control */
    protected $secureInlineForms;
    /** @var MM_WPFS_Control */
    protected $secureCheckoutForms;
    /** @var MM_WPFS_Control */
    protected $secureCustomerPortal;
    /** @var MM_WPFS_Control */
    protected $reCaptchaSecretKey;
    /** @var MM_WPFS_Control */
    protected $reCaptchaSiteKey;
    /** @var MM_WPFS_Control */
    protected $fillInEmailForLoggedInUsers;

    /**
     * MM_WPFS_Admin_SecurityView constructor.
     */
    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_SECURITY;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_SecurityViewConstants::FIELD_ACTION_VALUE_SAVE_SECURITY );

        $this->secureInlineForms = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_INLINE_FORMS, null, null,
            /* translators: Form field label for the 'Secure inline forms' checkbox of the form */
            __( 'Inline forms', 'wp-full-stripe-admin' ), null );
        $this->secureInlineForms->setValue('1' );
        $this->secureInlineForms->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input js-google-recaptcha-toggle'
        ));

        $this->secureCheckoutForms = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_CHECKOUT_FORMS, null, null,
            /* translators: Form field label for the 'Secure checkout forms' checkbox of the form */
            __( 'Checkout forms', 'wp-full-stripe-admin' ), null );
        $this->secureCheckoutForms->setValue('1' );
        $this->secureCheckoutForms->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input js-google-recaptcha-toggle'
        ));

        $this->secureCustomerPortal = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_CUSTOMER_PORTAL, null, null,
            /* translators: Form field label for the 'Secure Customer portal' checkbox of the form */
            __( 'Customer portal', 'wp-full-stripe-admin' ), null );
        $this->secureCustomerPortal->setValue('1' );
        $this->secureCustomerPortal->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input js-google-recaptcha-toggle'
        ));

        $this->reCaptchaSecretKey = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_RECAPTCHA_SECRET_KEY, null, null,
            /* translators: Form field label for the 'Google reCaptcha secret key' field of the form */
            __( 'Google reCAPTCHA secret key', 'wp-full-stripe-admin' ), null );
        $this->reCaptchaSecretKey->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-form-control'
        ));

        $this->reCaptchaSiteKey = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_RECAPTCHA_SITE_KEY, null, null,
            /* translators: Form field label for the 'Google reCaptcha site key' field of the form */
            __( 'Google reCAPTCHA site key', 'wp-full-stripe-admin' ), null );
        $this->reCaptchaSiteKey->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-form-control'
        ));
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_INLINE_FORMS    => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_INLINE_FORMS ),
            MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_CHECKOUT_FORMS  => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_CHECKOUT_FORMS ),
            MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_CUSTOMER_PORTAL      => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_SECURE_CUSTOMER_PORTAL ),
            MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_RECAPTCHA_SECRET_KEY   => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_RECAPTCHA_SECRET_KEY ),
            MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_RECAPTCHA_SITE_KEY     => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_SecurityViewConstants::FIELD_SECURITY_RECAPTCHA_SITE_KEY ),
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_SecurityViewConstants::FIELD_ACTION_VALUE_SAVE_SECURITY;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_SECURITY;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function secureInlineForms(): MM_WPFS_Control {
        return $this->secureInlineForms;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function secureCheckoutForms(): MM_WPFS_Control {
        return $this->secureCheckoutForms;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function secureCustomerPortal(): MM_WPFS_Control {
        return $this->secureCustomerPortal;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function reCaptchaSecretKey(): MM_WPFS_Control {
        return $this->reCaptchaSecretKey;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function reCaptchaSiteKey(): MM_WPFS_Control {
        return $this->reCaptchaSiteKey;
    }
}

interface MM_WPFS_Admin_EmailOptionsViewConstants {

    const FIELD_EMAIL_OPTIONS_FROM_ADDRESS = 'wpfs-email-options-from-address';
    const FIELD_EMAIL_OPTIONS_FROM_ADDRESS_CUSTOM = 'wpfs-email-options-from-address-custom';
    const FIELD_EMAIL_OPTIONS_SEND_COPY_TO_ADMIN = 'wpfs-email-options-send-copy-to-admin';
    const FIELD_EMAIL_OPTIONS_SEND_COPY_TO_LIST = 'wpfs-email-options-send-copy-to-list';
    const FIELD_EMAIL_OPTIONS_SEND_COPY_TO_LIST_HIDDEN = 'wpfs-email-options-send-copy-to-list-hidden';

    const FIELD_VALUE_FROM_ADDRESS_ADMIN = 'siteAdmin';
    const FIELD_VALUE_FROM_ADDRESS_CUSTOM = 'custom';

    const FIELD_ACTION_VALUE_SAVE_EMAIL_OPTIONS = 'wpfs-save-email-options';
}

class MM_WPFS_Admin_EmailOptionsView extends MM_WPFS_AdminView {
    /** @var MM_WPFS_Control */
    protected $fromAddress;
    /** @var MM_WPFS_Control */
    protected $fromAddressCustom;
    /** @var MM_WPFS_Control */
    protected $sendCopyToAdmin;
    /** @var MM_WPFS_Control */
    protected $sendCopyToList;
    /** @var MM_WPFS_Control */
    protected $sendCopyToListHidden;

    /**
     * @param $name
     * @param $email
     *
     * @return string
     */
    protected function createSiteAdminLabel( $name, $email ) {
        return __( 'Website Admin', 'wp-full-stripe-admin' ) . ': ' . MM_WPFS_Mailer::generateSenderStringFromNameAndEmail( $name, $email );
    }

    /**
     * MM_WPFS_Admin_MyAccountView constructor.
     */
    public function __construct( $siteAdminName, $siteAdminEmail ) {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_EMAIL_OPTIONS;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_ACTION_VALUE_SAVE_EMAIL_OPTIONS );


        $this->fromAddress = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS, null, null,
            /* translators: Form field label for the 'Email From address' option of the form */
            __( "Email 'From' address", 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS, null, null,
            $this->createSiteAdminLabel( $siteAdminName, $siteAdminEmail ), $optionIndex );
        $option->setValue( MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_VALUE_FROM_ADDRESS_ADMIN );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input js-email-from-address'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS, null, null,
            /* translators: Option chosen when the administrator enters a custom email 'From' address */
            __( 'Custom email address', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_VALUE_FROM_ADDRESS_CUSTOM );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input js-email-from-address'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->fromAddress->setOptions( $options );


        $this->fromAddressCustom = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS_CUSTOM, __( 'Enter email address', 'wp-full-stripe-admin' ), null,
            null, null );
        $this->fromAddressCustom->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-form-control wpfs-from-address-custom-js'
        ));


        $this->sendCopyToAdmin = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_ADMIN, null, null,
            $this->createSiteAdminLabel( $siteAdminName, $siteAdminEmail ), null );
        $this->sendCopyToAdmin->setValue( $siteAdminEmail );
        $this->sendCopyToAdmin->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        ));


        $this->sendCopyToList = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_LIST, __( 'Add more email addresses here', 'wp-full-stripe-admin' ), null,
            null, null );
        $this->sendCopyToList->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-tags-input'
        ));


        $this->sendCopyToListHidden = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_LIST_HIDDEN, null, null,
            null, null );
        $this->sendCopyToListHidden->setAttributes( array(
            'type'      => 'hidden'
        ));

    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS          => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS ),
            MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS_CUSTOM   => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_FROM_ADDRESS_CUSTOM ),
            MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_ADMIN    => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_ADMIN ),
            MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_LIST     => MM_WPFS_ControlUtils::inputTags( MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_EMAIL_OPTIONS_SEND_COPY_TO_LIST )
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_EmailOptionsViewConstants::FIELD_ACTION_VALUE_SAVE_EMAIL_OPTIONS;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_EMAIL_OPTIONS;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function fromAddress(): MM_WPFS_Control {
        return $this->fromAddress;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function fromAddressCustom(): MM_WPFS_Control {
        return $this->fromAddressCustom;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function sendCopyToAdmin(): MM_WPFS_Control {
        return $this->sendCopyToAdmin;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function sendCopyToList(): MM_WPFS_Control {
        return $this->sendCopyToList;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function sendCopyToListHidden(): MM_WPFS_Control {
        return $this->sendCopyToListHidden;
    }
}

interface MM_WPFS_Admin_EmailTemplatesViewConstants {
    const FIELD_EMAIL_TEMPLATES_HIDDEN = 'wpfs-email-templates-hidden';

    const FIELD_ACTION_VALUE_SAVE_EMAIL_TEMPLATES = 'wpfs-save-email-templates';
}

class MM_WPFS_Admin_EmailTemplatesView extends MM_WPFS_AdminView {
    /** @var MM_WPFS_Control */
    protected $emailTemplatesHidden;

    /**
     * MM_WPFS_Admin_EmailTemplatesView constructor.
     */
    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_EMAIL_TEMPLATES;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_EmailTemplatesViewConstants::FIELD_ACTION_VALUE_SAVE_EMAIL_TEMPLATES );

        $this->emailTemplatesHidden = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_EmailTemplatesViewConstants::FIELD_EMAIL_TEMPLATES_HIDDEN, null, null,
            null, null );
        $this->emailTemplatesHidden->setAttributes( array(
            'type'      => 'hidden',
            'class'     => 'wpfs-email-templates-hidden'
        ));
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array();

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_EmailTemplatesViewConstants::FIELD_ACTION_VALUE_SAVE_EMAIL_TEMPLATES;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_EMAIL_TEMPLATES;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function emailTemplatesHidden(): MM_WPFS_Control {
        return $this->emailTemplatesHidden;
    }
}

interface MM_WPFS_Admin_FormsOptionsViewConstants {
    const FIELD_FORMS_OPTIONS_FILL_IN_EMAIL                  = 'wpfs-forms-options-fill-in-email';
    const FIELD_FORMS_OPTIONS_SET_FIELDS_VIA_URL_PARAMETERS   = 'wpfs-forms-options-set-fields-via-url-parameters';

    const FIELD_ACTION_VALUE_SAVE_FORMS_OPTIONS = 'wpfs-save-forms-options';
}

class MM_WPFS_Admin_FormsOptionsView extends MM_WPFS_AdminView {
    /** @var MM_WPFS_Control */
    protected $fillInEmailForLoggedInUsers;
    /** @var MM_WPFS_Control */
    protected $setFormFieldsViaUrlParameters;

    /**
     * MM_WPFS_Admin_FormsOptionsView constructor.
     */
    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_FORMS_OPTIONS;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_FormsOptionsViewConstants::FIELD_ACTION_VALUE_SAVE_FORMS_OPTIONS );

        $this->fillInEmailForLoggedInUsers = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_FormsOptionsViewConstants::FIELD_FORMS_OPTIONS_FILL_IN_EMAIL, null, null,
            /* translators: Form field label for the 'Fill in email for logged in users?' option of the form */
            __( 'Fill in email field for logged in users', 'wp-full-stripe-admin' ), null );
        $this->fillInEmailForLoggedInUsers->setValue('1' );
        $this->fillInEmailForLoggedInUsers->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        ));

        $this->setFormFieldsViaUrlParameters = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_FormsOptionsViewConstants::FIELD_FORMS_OPTIONS_SET_FIELDS_VIA_URL_PARAMETERS, null, null,
            /* translators: Form field label for the 'Enable setting form fields via URL parameters' option of the form */
            __( 'Allow setting form fields via URL parameters', 'wp-full-stripe-admin' ), null );
        $this->setFormFieldsViaUrlParameters->setValue('1' );
        $this->setFormFieldsViaUrlParameters->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        ));
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            MM_WPFS_Admin_FormsOptionsViewConstants::FIELD_FORMS_OPTIONS_FILL_IN_EMAIL => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_FormsOptionsViewConstants::FIELD_FORMS_OPTIONS_FILL_IN_EMAIL )
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_FormsOptionsViewConstants::FIELD_ACTION_VALUE_SAVE_FORMS_OPTIONS;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_FORMS_OPTIONS;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function fillInEmailForLoggedInUsers(): MM_WPFS_Control {
        return $this->fillInEmailForLoggedInUsers;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function setFormFieldsViaUrlParameters(): MM_WPFS_Control {
        return $this->setFormFieldsViaUrlParameters;
    }
}

interface MM_WPFS_Admin_FormsAppearanceViewConstants {
    const FIELD_FORMS_APPEARANCE_CUSTOM_CSS = 'wpfs-forms-appearance-custom-css';
    const FIELD_FORMS_APPEARANCE_CUSTOM_CSS_HIDDEN = 'wpfs-forms-appearance-custom-css-hidden';

    const FIELD_ACTION_VALUE_SAVE_FORMS_APPEARANCE = 'wpfs-save-forms-appearance';
}

class MM_WPFS_Admin_FormsAppearanceView extends MM_WPFS_AdminView {
    /** @var MM_WPFS_Control */
    protected $customCss;
    /** @var MM_WPFS_Control */
    protected $customCssHidden;

    /**
     * MM_WPFS_Admin_EmailTemplatesView constructor.
     */
    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_FORMS_APPEARANCE;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_FormsAppearanceViewConstants::FIELD_ACTION_VALUE_SAVE_FORMS_APPEARANCE );

        $this->customCss = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_FormsAppearanceViewConstants::FIELD_FORMS_APPEARANCE_CUSTOM_CSS, null, null,
            null, null );
        $this->customCss->setAttributes( array(
            'class'             => 'wpfs-code-editor js-code-editor',
            'data-editor-mode'  => 'css'
        ));

        $this->customCssHidden = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_FormsAppearanceViewConstants::FIELD_FORMS_APPEARANCE_CUSTOM_CSS_HIDDEN, null, null,
            null, null );
        $this->customCssHidden->setAttributes( array(
            'type'      => 'hidden',
            'class'     => 'wpfs-custom-css-hidden'
        ));
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            MM_WPFS_Admin_FormsAppearanceViewConstants::FIELD_FORMS_APPEARANCE_CUSTOM_CSS        => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_FormsAppearanceViewConstants::FIELD_FORMS_APPEARANCE_CUSTOM_CSS ),
            MM_WPFS_Admin_FormsAppearanceViewConstants::FIELD_FORMS_APPEARANCE_CUSTOM_CSS_HIDDEN => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_FormsAppearanceViewConstants::FIELD_FORMS_APPEARANCE_CUSTOM_CSS_HIDDEN )
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_FormsAppearanceViewConstants::FIELD_ACTION_VALUE_SAVE_FORMS_APPEARANCE;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_FORMS_APPEARANCE;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function customCss(): MM_WPFS_Control {
        return $this->customCss;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function customCssHidden(): MM_WPFS_Control {
        return $this->customCssHidden;
    }
}

interface MM_WPFS_Admin_WordpressDashboardViewConstants {

    const FIELD_WP_DASHBOARD_DECIMAL_SEPARATOR = 'wpfs-wp-dashboard-decimal-separator';
    const FIELD_WP_DASHBOARD_SYMBOL_NOT_CODE = 'wpfs-wp-dashboard-symbol-not-code';
    const FIELD_WP_DASHBOARD_SYMBOL_AT_FIRST_POSITION = 'wpfs-wp-dashboard-symbol-first-position';
    const FIELD_WP_DASHBOARD_SPACE_BETWEEN_SYMBOL_AND_AMOUNT = 'wpfs-wp-dashboard-space-between-symbol-amount';

    const FIELD_ACTION_VALUE_SAVE_WP_DASHBOARD = 'wpfs-save-wp-dashboard';
}

class MM_WPFS_Admin_WordpressDashboardView extends MM_WPFS_AdminView {
    /** @var MM_WPFS_Control */
    protected $decimalSeparator;
    /** @var MM_WPFS_Control */
    protected $useSymbolNotCode;
    /** @var MM_WPFS_Control */
    protected $currencySymbolAtFirstPosition;
    /** @var MM_WPFS_Control */
    protected $putSpaceBetweenSymbolAndAmount;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_WP_DASHBOARD;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_ACTION_VALUE_SAVE_WP_DASHBOARD );


        $this->decimalSeparator = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_DECIMAL_SEPARATOR, null, null,
            /* translators: Form field label for the 'Format decimals with' option of the form */
            __( 'Format decimals with', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_DECIMAL_SEPARATOR, null, null,
            /* translators: It's a example showing that the decimal separator is a dot */
            __( '$10.99 (dot)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_DECIMAL_SEPARATOR, null, null,
            /* translators: It's a example showing that the decimal separator is a comma */
            __( '$10,99 (comma)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_COMMA );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->decimalSeparator->setOptions( $options );


        $this->useSymbolNotCode = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_NOT_CODE, null, null,
            /* translators: Form field label for the 'Use currency symbol or code?' option of the form */
            __( 'Use currency symbol or code?', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_NOT_CODE, null, null,
            /* translators: It's a example showing that the currency symbol ($) is used */
            __( '$10.99 (symbol)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_NOT_CODE, null, null,
            /* translators: It's a example showing that the currency code (USD) is used */
            __( 'USD 10.99 (code)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->useSymbolNotCode->setOptions( $options );


        $this->currencySymbolAtFirstPosition = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_AT_FIRST_POSITION, null, null,
            /* translators: Form field label for the 'Put currency identifier on' option of the form */
            __( 'Put currency identifier on', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_AT_FIRST_POSITION, null, null,
            /* translators: It's a example showing that the currency symbol is on the left */
            __( '€10.99 (left)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_AT_FIRST_POSITION, null, null,
            /* translators: It's a example showing that the currency symbol is on the right */
            __( '10.99€ (right)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->currencySymbolAtFirstPosition->setOptions( $options );


        $this->putSpaceBetweenSymbolAndAmount = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SPACE_BETWEEN_SYMBOL_AND_AMOUNT, null, null,
            /* translators: Form field label for the 'Insert space between amount and currency?' option of the form */
            __( 'Insert space between amount and currency?', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SPACE_BETWEEN_SYMBOL_AND_AMOUNT, null, null,
            /* translators: It's a example showing that there is no space between the number and the currency code */
            __( '10.99EUR (no)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SPACE_BETWEEN_SYMBOL_AND_AMOUNT, null, null,
            /* translators: It's a example showing that there is space between the number and the currency code */
            __( '10.99 EUR (yes)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->putSpaceBetweenSymbolAndAmount->setOptions( $options );
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_DECIMAL_SEPARATOR                 => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_DECIMAL_SEPARATOR ),
            MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_NOT_CODE                   => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_NOT_CODE ),
            MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_AT_FIRST_POSITION          => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SYMBOL_AT_FIRST_POSITION ),
            MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SPACE_BETWEEN_SYMBOL_AND_AMOUNT   => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_WP_DASHBOARD_SPACE_BETWEEN_SYMBOL_AND_AMOUNT )
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_WordpressDashboardViewConstants::FIELD_ACTION_VALUE_SAVE_WP_DASHBOARD;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_WP_DASHBOARD;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function decimalSeparator(): MM_WPFS_Control {
        return $this->decimalSeparator;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function useSymbolNotCode(): MM_WPFS_Control {
        return $this->useSymbolNotCode;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function currencySymbolAtFirstPosition(): MM_WPFS_Control {
        return $this->currencySymbolAtFirstPosition;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function putSpaceBetweenSymbolAndAmount(): MM_WPFS_Control {
        return $this->putSpaceBetweenSymbolAndAmount;
    }
}

interface MM_WPFS_Admin_LogsViewConstants {
    const FIELD_FORM_LOG_LEVEL    = 'wpfs-logs-log-level';
    const FIELD_FORM_LOG_CHANNEL    = 'wpfs-logs-log-channel';
    const FIELD_FORM_BEHAVIOR = 'wpfs-logs-behavior';

    const FIELD_FORM_LOG_TO_WORDPRESS_DATABASE    = 'wpfs-log-to-wordpress-database';
    const FIELD_FORM_LOG_TO_WEB_SERVER    = 'wpfs-log-to-web-server';
    const FIELD_FORM_CATCH_UNCAUGHT_ERRORS    = 'wpfs-catch-uncaught-errors';

    const FIELD_ACTION_VALUE_SAVE_LOGS = 'wpfs-save-logs';
    const FIELD_ACTION_VALUE_EMPTY_LOGS = 'wpfs-empty-logs';
}

class MM_WPFS_Admin_LogLevel_View extends MM_WPFS_AdminView implements MM_WPFS_Admin_LogsViewConstants {
    /** @var MM_WPFS_Control */
    protected $logLevel;
    /** @var MM_WPFS_Control */
    protected $logChannel;
    /** @var MM_WPFS_Control */
    protected $logBehavior;

    /**
     * MM_WPFS_Admin_SecurityView constructor.
     */
    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_LOG_LEVELS;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_LogsViewConstants::FIELD_ACTION_VALUE_SAVE_LOGS );

        $this->initLogLevel();
        $this->initLogChannels();
        $this->initLogBehavior();
    }

    protected function initLogBehavior() {
        $this->logBehavior = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_BEHAVIOR, null, null,
            __( 'Behavior', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CATCH_UNCAUGHT_ERRORS, null, null,
            __('Catch all errors not caught by plugins', 'wp-full-stripe-admin'), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->logBehavior->setOptions( $options );
    }

    protected function initLogChannels() {
        $this->logChannel = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOG_CHANNEL, null, null,
            __( 'Logging channels', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOG_TO_WORDPRESS_DATABASE, null, null,
            __('WordPress database', 'wp-full-stripe-admin'), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
            'disabled'  => 'disabled'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOG_TO_WEB_SERVER, null, null,
            __('Webserver error log', 'wp-full-stripe-admin'), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->logChannel->setOptions( $options );
    }

    protected function initLogLevel() {
        $this->logLevel = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOG_LEVEL, null, null,
            __( 'Logging level', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOG_LEVEL, null, null,
            MM_WPFS_LoggerService::localizeLogLevel( MM_WPFS_LoggerService::LEVEL_ERROR ), $optionIndex );
        $option->setValue( MM_WPFS_LoggerService::LEVEL_ERROR );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOG_LEVEL, null, null,
            MM_WPFS_LoggerService::localizeLogLevel( MM_WPFS_LoggerService::LEVEL_WARNING ), $optionIndex );
        $option->setValue( MM_WPFS_LoggerService::LEVEL_WARNING );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOG_LEVEL, null, null,
            MM_WPFS_LoggerService::localizeLogLevel( MM_WPFS_LoggerService::LEVEL_INFO ), $optionIndex );
        $option->setValue( MM_WPFS_LoggerService::LEVEL_INFO );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOG_LEVEL, null, null,
            MM_WPFS_LoggerService::localizeLogLevel( MM_WPFS_LoggerService::LEVEL_DEBUG ), $optionIndex );
        $option->setValue( MM_WPFS_LoggerService::LEVEL_DEBUG );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->logLevel->setOptions( $options );
    }


    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            self::FIELD_FORM_LOG_LEVEL => MM_WPFS_ControlUtils::input( self::FIELD_FORM_LOG_LEVEL ),
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_LogsViewConstants::FIELD_ACTION_VALUE_SAVE_LOGS;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CONFIGURE_LOGGING;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function logLevel(): MM_WPFS_Control {
        return $this->logLevel;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function logChannel(): MM_WPFS_Control {
        return $this->logChannel;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function behavior(): MM_WPFS_Control {
        return $this->logBehavior;
    }
}

class MM_WPFS_Admin_LogEmpty_View extends MM_WPFS_AdminView implements MM_WPFS_Admin_LogsViewConstants {
    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_LOG_EMPTY;
        parent::__construct();

        $this->action->setValue( MM_WPFS_Admin_LogsViewConstants::FIELD_ACTION_VALUE_EMPTY_LOGS );
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = MM_WPFS_Admin_LogsViewConstants::FIELD_ACTION_VALUE_EMPTY_LOGS;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_EMPTY_LOG;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }
}


interface MM_WPFS_Admin_FormViewConstants {
    const FIELD_FORM_ID = 'wpfs-form-id';
    const FIELD_FORM_DISPLAY_NAME = 'wpfs-form-display-name';
    const FIELD_FORM_LOCALE_DECIMAL_SEPARATOR = 'wpfs-form-locale-decimal-separator';
    const FIELD_FORM_LOCALE_SYMBOL_NOT_CODE = 'wpfs-form-locale-symbol-not-code';
    const FIELD_FORM_LOCALE_SYMBOL_AT_FIRST_POSITION = 'wpfs-form-locale-symbol-first-position';
    const FIELD_FORM_LOCALE_SPACE_BETWEEN_SYMBOL_AND_AMOUNT = 'wpfs-form-locale-space-between-symbol-amount';
    const FIELD_FORM_REDIRECT_TYPE = 'wpfs-form-redirect-type';
    const FIELD_FORM_REDIRECT_CUSTOM_URL = 'wpfs-form-redirect-custom-url';
    const FIELD_FORM_REDIRECT_PAGE_POST_ID = 'wpfs-form-redirect-page-post-id';
    const FIELD_FORM_BUTTON_LABEL = 'wpfs-form-button-label';
    const FIELD_FORM_SHOW_COUPON_FIELD = 'wpfs-form-show-coupon';
    const FIELD_FORM_COLLECT_BILLING_ADDRESS = 'wpfs-form-collect-billing-address';
    const FIELD_FORM_DEFAULT_BILLING_COUNTRY = 'wpfs-form-default-billing-country';
    const FIELD_FORM_COLLECT_SHIPPING_ADDRESS = 'wpfs-form-collect-shipping-address';
    const FIELD_FORM_SHOW_TERMS_OF_SERVICE = 'wpfs-form-show-terms-of-service';
    const FIELD_FORM_TERMS_OF_SERVICE_LABEL = 'wpfs-form-terms-of-service-label';
    const FIELD_FORM_TERMS_OF_SERVICE_ERROR = 'wpfs-form-terms-of-service-error';
    const FIELD_FORM_COLLECT_PHONE_NUMBER = 'wpfs-form-collect-phone-number';
    const FIELD_FORM_CUSTOM_FIELDS = 'wpfs-form-custom-fields';
    const FIELD_FORM_MAKE_CUSTOM_FIELDS_REQUIRED = 'wpfs-form-make-custom-fields-required';
    const FIELD_FORM_EMAIL_TEMPLATES = 'wpfs-form-email-templates';
    const FIELD_FORM_STRIPE_ELEMENTS_THEME_SELECTOR = 'wpfs-form-stripe-elements-theme-selector';
    const FIELD_FORM_STRIPE_ELEMENTS_FONT_SELECTOR = 'wpfs-form-stripe-elements-font-selector';
}

interface MM_WPFS_Admin_View_CouponConstants {
    const FIELD_FORM_SHOW_COUPON = 'wpfs-form-show-coupon';
}

trait MM_WPFS_Admin_FormView_CouponAddOn {
    /** @var MM_WPFS_Control */
    protected $showCouponField;

    /**
     * @return MM_WPFS_Control
     */
    public function showCouponField(): MM_WPFS_Control {
        return $this->showCouponField;
    }

    protected function initCoupon() {
        $this->showCouponField = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_SHOW_COUPON_FIELD, null, null,
            __( 'Coupon', 'wp-full-stripe-admin' ), null );
        $this->showCouponField->setValue('1' );
        $this->showCouponField->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        ));
    }

    public static function getCouponFields() {
        return array(
            MM_WPFS_Admin_View_CouponConstants::FIELD_FORM_SHOW_COUPON => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_View_CouponConstants::FIELD_FORM_SHOW_COUPON ),
        );
    }
}

interface MM_WPFS_Admin_View_TaxConstants {
    const FIELD_FORM_TAX_RATES = 'wpfs-form-tax-rates';
    const FIELD_FORM_TAX_TYPE = 'wpfs-form-tax-type';
    const FIELD_FORM_TAX_RATE_TYPE = 'wpfs-form-tax-rate-type';
    const FIELD_FORM_COLLECT_CUSTOMER_TAX_ID = 'wpfs-form-collect-customer-tax-id';
    const FIELD_FORM_TAX_RATES_ERROR = 'wpfs-form-tax-rates-error';
}

trait MM_WPFS_Admin_FormView_TaxAddOn {
    protected $taxType;
    /** @var MM_WPFS_Control */
    protected $taxRateType;
    /** @var MM_WPFS_Control */
    protected $taxRates;
    /** @var MM_WPFS_Control */
    protected $collectCustomerTaxId;

    protected function initTaxFields() {
        $this->initTaxRates();
        $this->initTaxType();
        $this->initTaxRateType();
    }

    protected function initTaxRates() {
        $this->taxRates = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATES, null, null,
            __( 'Tax rates', 'wp-full-stripe-admin' ), null );
        $this->taxRates->setAttributes( array(
            'type'      => 'hidden',
        ));
    }

    protected function initTaxType() {
        $this->taxType = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_TYPE, null, null,
            __( 'Tax calculation method', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_TYPE, null, null,
            /* translators: It means no tax is applied to the purchased goods */
            __( 'No tax', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue(MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX);
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_TYPE, null, null,
            /* translators: It means that the tax rates are automatically calculated by Stripe Tax */
            __( 'Stripe auto tax', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue(MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX);
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_TYPE, null, null,
            /* translators: It means that the applied tax rates are based on tax rates added manually by the user */
            __( 'Stripe tax rates', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue(MM_WPFS::FIELD_VALUE_TAX_RATE_TAX_RATES);
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->taxType->setOptions( $options );
    }

    protected function initTaxRateType() {
        $this->taxRateType = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATE_TYPE, null, null,
            __( 'Tax rate type', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATE_TYPE, null, null,
            /* translators: It means that the applied tax rates are preselected, and they don't vary by country and state */
            __( 'Fixed rate', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue(MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED);
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATE_TYPE, null, null,
            /* translators: It means that the applied tax rates are selected dynamically depending on the country and state */
            __( 'Dynamic rate', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue(MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC);
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->taxRateType->setOptions( $options );
    }

    protected function initCollectCustomerTaxId() {
        $this->collectCustomerTaxId = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_COLLECT_CUSTOMER_TAX_ID, null, null,
            /* translators: Is there a dedicated field on the form to edit the tax id or not? */
            __( "Collect customer's tax id?", 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_COLLECT_CUSTOMER_TAX_ID, null, null,
            __( 'No', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( 0 );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_COLLECT_CUSTOMER_TAX_ID, null, null,
            __( 'Yes', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->collectCustomerTaxId->setOptions( $options );
    }

    public function doesFormUseTaxRates( $form ) {
        return $form->vatRateType == MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED || $form->vatRateType == MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC;
    }

    public function doesFormCalculateTax( $form ) {
        return $form->vatRateType !== MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX;
    }

    public static function getTaxFields() {
        return array(
            MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_TYPE                       => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_TYPE ),
            MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATE_TYPE                  => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATE_TYPE ),
            MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_COLLECT_CUSTOMER_TAX_ID        => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_COLLECT_CUSTOMER_TAX_ID ),
            MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATES_ERROR                => MM_WPFS_ControlUtils::products( MM_WPFS_Admin_View_TaxConstants::FIELD_FORM_TAX_RATES_ERROR ),
        );
    }

    /**
     * @return MM_WPFS_Control
     */
    public function taxType(): MM_WPFS_Control {
        return $this->taxType;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function taxRateType(): MM_WPFS_Control {
        return $this->taxRateType;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function taxRates(): MM_WPFS_Control {
        return $this->taxRates;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function collectCustomerTaxId(): MM_WPFS_Control {
        return $this->collectCustomerTaxId;
    }
}

class MM_WPFS_Admin_FormView extends MM_WPFS_AdminView implements MM_WPFS_Admin_FormViewConstants {
    /** @var MM_WPFS_Control */
    protected $name;
    /** @var MM_WPFS_Control */
    protected $displayName;
    /** @var MM_WPFS_Control */
    protected $redirectType;
    /** @var MM_WPFS_Control */
    protected $redirectPagePostId;
    /** @var MM_WPFS_Control */
    protected $redirectUrl;
    /** @var MM_WPFS_Control */
    protected $localeDecimalSeparator;
    /** @var MM_WPFS_Control */
    protected $localeUseSymbolNotCode;
    /** @var MM_WPFS_Control */
    protected $localeCurrencySymbolAtFirstPosition;
    /** @var MM_WPFS_Control */
    protected $localePutSpaceBetweenSymbolAndAmount;
    /** @var MM_WPFS_Control */
    protected $buttonLabel;
    /** @var MM_WPFS_Control */
    protected $collectBillingAddress;
    /** @var MM_WPFS_Control */
    protected $defaultBillingCountry;
    /** @var MM_WPFS_Control */
    protected $collectShippingAddress;
    /** @var MM_WPFS_Control */
    protected $showTermsOfService;
    /** @var MM_WPFS_Control */
    protected $termsOfServiceLabel;
    /** @var MM_WPFS_Control */
    protected $termsOfServiceErrorMessage;
    /** @var MM_WPFS_Control */
    protected $customFields;
    /** @var MM_WPFS_Control */
    protected $makeCustomFieldsRequired;
    /** @var MM_WPFS_Control */
    protected $emailTemplates;
    /** @var MM_WPFS_Control */
    protected $stripeElementsThemeSelector;
    /** @var MM_WPFS_Control */
    protected $stripeElementsFontSelector;

    public function __construct() {
        parent::__construct();

        $this->createNameFields();
        $this->createCurrencyFields();
        $this->createRedirectFields();
        $this->createAppearanceFields();
        $this->createOptionalFormFields();
        $this->createCustomFields();
        $this->createEmailTemplates();
        $this->initStripeElementsThemeSelector();
        $this->initStripeElementsFontSelector();
    }

    protected function initStripeElementsThemeSelector() {

        $this->stripeElementsThemeSelector = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_STRIPE_ELEMENTS_THEME_SELECTOR, null, null,
            __( 'Stripe Elements Appearance Theme', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $selectorStyleOptions = array();

        $stripeOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_STRIPE_ELEMENTS_THEME_SELECTOR, null, null,
            /* translators: This selector style is a list of radio buttons  */
            __( 'Stripe', 'wp-full-stripe-admin' ), $optionIndex );
        $stripeOption->setValue( MM_WPFS::ELEMENTS_THEME_STRIPE );
        $stripeOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        ));
        $stripeOption->setMetadata( array(
            'iconClass'     => 'wpfs-illu-element-style-stripe'
        ));
        array_push( $selectorStyleOptions, $stripeOption );
        $optionIndex++;

        $nightOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_STRIPE_ELEMENTS_THEME_SELECTOR, null, null,
            /* translators: This selector style is a list of radio buttons  */
            __( 'Night', 'wp-full-stripe-admin' ), $optionIndex );
        $nightOption->setValue( MM_WPFS::ELEMENTS_THEME_NIGHT );
        $nightOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        ));
        $nightOption->setMetadata( array(
            'iconClass'     => 'wpfs-illu-element-style-night'
        ));
        array_push( $selectorStyleOptions, $nightOption );
        $optionIndex++;

        $flatOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_STRIPE_ELEMENTS_THEME_SELECTOR, null, null,
            /* translators: This selector style is a list of radio buttons  */
            __( 'Flat', 'wp-full-stripe-admin' ), $optionIndex );
        $flatOption->setValue( MM_WPFS::ELEMENTS_THEME_FLAT );
        $flatOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        ));
        $flatOption->setMetadata( array(
            'iconClass'     => 'wpfs-illu-element-style-flat'
        ));
        array_push( $selectorStyleOptions, $flatOption );

        $this->stripeElementsThemeSelector->setOptions( $selectorStyleOptions );
    }

    protected function initStripeElementsFontSelector() {
        $this->stripeElementsFontSelector = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_STRIPE_ELEMENTS_FONT_SELECTOR, null, null,
            /* translators: Form field label for the display name of the form */
            __( 'Stripe Elements Appearance Font', 'wp-full-stripe-admin' ), null );
    }

    protected function createEmailTemplates() {
        $this->emailTemplates = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_EMAIL_TEMPLATES, null, null,
            __( 'Email templates', 'wp-full-stripe-admin' ), null );
        $this->emailTemplates->setAttributes( array(
            'type' => 'hidden'
        ));
    }

    protected function createCustomFields() {
        $this->customFields = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CUSTOM_FIELDS, null, null,
            __( 'Custom fields', 'wp-full-stripe-admin' ), null );
        $this->customFields->setAttributes( array(
            'type' => 'hidden'
        ));

        $this->makeCustomFieldsRequired = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_MAKE_CUSTOM_FIELDS_REQUIRED, null, null,
            __( 'Make custom fields required?', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_MAKE_CUSTOM_FIELDS_REQUIRED, null, null,
            __( 'No', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_MAKE_CUSTOM_FIELDS_REQUIRED, null, null,
            __( 'Yes', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->makeCustomFieldsRequired->setOptions( $options );
    }

    protected function createOptionalFormFields() {
        $this->collectBillingAddress = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_COLLECT_BILLING_ADDRESS, null, null,
            __( 'Billing address', 'wp-full-stripe-admin' ), null );
        $this->collectBillingAddress->setValue('1' );
        $this->collectBillingAddress->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        ));


        $defaultBillingCountryLabel = $this instanceof MM_WPFS_Admin_DonationFormView ?
            __( "Nonprofit's seat country", 'wp-full-stripe-admin' ) :
            __( "Seller's seat country", 'wp-full-stripe-admin' );
        $this->defaultBillingCountry = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DEFAULT_BILLING_COUNTRY, null, null,
            $defaultBillingCountryLabel, null );
        $this->defaultBillingCountry->setAttributes( array(
            'class'     => 'js-combobox'
        ));


        $this->collectShippingAddress = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_COLLECT_SHIPPING_ADDRESS, null, null,
            __( 'Shipping address', 'wp-full-stripe-admin' ), null );
        $this->collectShippingAddress->setValue('1' );
        $this->collectShippingAddress->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        ));


        $this->showTermsOfService = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_SHOW_TERMS_OF_SERVICE, null, null,
            __( 'Terms of Service checkbox', 'wp-full-stripe-admin' ), null );
        $this->showTermsOfService->setValue('1' );
        $this->showTermsOfService->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        ));

        $this->termsOfServiceLabel = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_TERMS_OF_SERVICE_LABEL, null, null,
            /* translators: Label of the Terms of Service checkbox displayed to the customer */
            __( 'Terms of service label', 'wp-full-stripe-admin' ), null );
        $this->termsOfServiceErrorMessage = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_TERMS_OF_SERVICE_ERROR, null, null,
            /* translators: Validation error message displayed when the custom doesn't accept the Terms of Service */
            __( 'Terms of service error message', 'wp-full-stripe-admin' ), null );
    }

    protected function createAppearanceFields() {
        $this->buttonLabel = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_BUTTON_LABEL, null, null,
            __( 'Form submit button label', 'wp-full-stripe-admin' ), null );
        $this->buttonLabel->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-form-control'
        ));
    }

    protected function createRedirectFields() {
        $this->redirectType = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_REDIRECT_TYPE, null, null,
            /* translators: Title of the section where the administrator can decide what should happen after payment, eg. display success banner, redirect to Thank you page, etc */
            __( 'After successful payment', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_REDIRECT_TYPE, null, null,
            __( 'Show confirmation message', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::REDIRECT_TYPE_SHOW_CONFIRMATION_MESSAGE );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_REDIRECT_TYPE, null, null,
            __( 'Redirect to a Thank you page', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::REDIRECT_TYPE_TO_PAGE_OR_POST );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_REDIRECT_TYPE, null, null,
            __( 'Redirect to a custom URL', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::REDIRECT_TYPE_TO_CUSTOM_URL );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->redirectType->setOptions( $options );


        $this->redirectPagePostId = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_REDIRECT_PAGE_POST_ID, null, null,
            __( 'Redirect to a Thank you page', 'wp-full-stripe-admin' ), null );
        $this->redirectPagePostId->setAttributes( array(
            'class'     => 'js-combobox'
        ));


        $this->redirectUrl = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_REDIRECT_CUSTOM_URL, __( 'Enter an URL starting with http:// or https://', 'wp-full-stripe-admin' ), null,
            __( 'Redirect to custom URL', 'wp-full-stripe-admin' ), null );
        $this->redirectUrl->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-form-control'
        ));

    }

    protected function createNameFields() {
        $this->displayName = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DISPLAY_NAME, null, null,
            /* translators: Form field label for the display name of the form */
            __( 'Display name', 'wp-full-stripe-admin' ), null );
        $this->name = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_NAME, null, null,
            /* translators: Form field label for the identifier of the form */
            __( 'Identifier', 'wp-full-stripe-admin' ), null );
    }

    protected function createCurrencyFields() {
        $this->localeDecimalSeparator = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_DECIMAL_SEPARATOR, null, null,
            /* translators: Form field label for the 'Format decimals with' option of the form */
            __( 'Format decimals with', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_DECIMAL_SEPARATOR, null, null,
            /* translators: Example of using dot for the decimal point */
            __( '$10.99 (dot)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_DOT );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_DECIMAL_SEPARATOR, null, null,
            /* translators: Example of using comma for the decimal point */
            __( '$10,99 (comma)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::DECIMAL_SEPARATOR_SYMBOL_COMMA );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->localeDecimalSeparator->setOptions( $options );


        $this->localeUseSymbolNotCode = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SYMBOL_NOT_CODE, null, null,
            /* translators: Form field label for the 'Use currency symbol or code?' option of the form */
            __( 'Use currency symbol or code?', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SYMBOL_NOT_CODE, null, null,
            /* translators: Example of displaying the currency symbol ($)  */
            __( '$10.99 (symbol)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SYMBOL_NOT_CODE, null, null,
            /* translators: Example of displaying the currency code (USD)  */
            __( 'USD 10.99 (code)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->localeUseSymbolNotCode->setOptions( $options );


        $this->localeCurrencySymbolAtFirstPosition = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SYMBOL_AT_FIRST_POSITION, null, null,
            /* translators: Form field label for the 'Put currency identifier on' option of the form */
            __( 'Put currency identifier on', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SYMBOL_AT_FIRST_POSITION, null, null,
            /* translators: Example of displaying the currency symbol on the left  */
            __( '€10.99 (left)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SYMBOL_AT_FIRST_POSITION, null, null,
            /* translators: Example of displaying the currency symbol on the right  */
            __( '10.99€ (right)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->localeCurrencySymbolAtFirstPosition->setOptions( $options );


        $this->localePutSpaceBetweenSymbolAndAmount = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SPACE_BETWEEN_SYMBOL_AND_AMOUNT, null, null,
            /* translators: Form field label for the 'Insert space between amount and currency?' option of the form */
            __( 'Insert space between amount and currency?', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SPACE_BETWEEN_SYMBOL_AND_AMOUNT, null, null,
            /* translators: Example of not having a space between the amount and the currency symbol */
            __( '10.99EUR (no)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_LOCALE_SPACE_BETWEEN_SYMBOL_AND_AMOUNT, null, null,
            /* translators: Example of inserting a space between the amount and the currency symbol */
            __( '10.99 EUR (yes)', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->localePutSpaceBetweenSymbolAndAmount->setOptions( $options );
    }

    /**
     * @return array
     */
    public static function getFields() {
        $fields = array(
            self::FIELD_FORM_NAME                                     => MM_WPFS_ControlUtils::input( self::FIELD_FORM_NAME ),
            self::FIELD_FORM_DISPLAY_NAME                             => MM_WPFS_ControlUtils::input( self::FIELD_FORM_DISPLAY_NAME ),
            self::FIELD_FORM_REDIRECT_CUSTOM_URL                      => MM_WPFS_ControlUtils::input( self::FIELD_FORM_REDIRECT_CUSTOM_URL ),
            self::FIELD_FORM_BUTTON_LABEL                             => MM_WPFS_ControlUtils::input( self::FIELD_FORM_BUTTON_LABEL ),
            self::FIELD_FORM_LOCALE_DECIMAL_SEPARATOR                 => MM_WPFS_ControlUtils::input( self::FIELD_FORM_LOCALE_DECIMAL_SEPARATOR ),
            self::FIELD_FORM_LOCALE_SYMBOL_NOT_CODE                   => MM_WPFS_ControlUtils::input( self::FIELD_FORM_LOCALE_SYMBOL_NOT_CODE ),
            self::FIELD_FORM_LOCALE_SYMBOL_AT_FIRST_POSITION          => MM_WPFS_ControlUtils::input( self::FIELD_FORM_LOCALE_SYMBOL_AT_FIRST_POSITION ),
            self::FIELD_FORM_LOCALE_SPACE_BETWEEN_SYMBOL_AND_AMOUNT   => MM_WPFS_ControlUtils::input( self::FIELD_FORM_LOCALE_SPACE_BETWEEN_SYMBOL_AND_AMOUNT ),
            self::FIELD_FORM_TERMS_OF_SERVICE_LABEL                   => MM_WPFS_ControlUtils::input( self::FIELD_FORM_TERMS_OF_SERVICE_LABEL ),
            self::FIELD_FORM_TERMS_OF_SERVICE_ERROR                   => MM_WPFS_ControlUtils::input( self::FIELD_FORM_TERMS_OF_SERVICE_ERROR ),
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return array
     */
    protected function getFormAttributes() {
        $attributes = array();
        $attributes[self::ATTR_DATA_WPFS_ELEMENTS_THEME] = $this->stripeElementsThemeSelector;
        $attributes[self::ATTR_DATA_WPFS_ELEMENTS_FONT] = $this->stripeElementsFontSelector;

        $attributes = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function name(): MM_WPFS_Control {
        return $this->name;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function displayName(): MM_WPFS_Control {
        return $this->displayName;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function redirectType(): MM_WPFS_Control {
        return $this->redirectType;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function redirectPagePostId(): MM_WPFS_Control {
        return $this->redirectPagePostId;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function redirectUrl(): MM_WPFS_Control {
        return $this->redirectUrl;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function localeDecimalSeparator(): MM_WPFS_Control {
        return $this->localeDecimalSeparator;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function localeUseSymbolNotCode(): MM_WPFS_Control {
        return $this->localeUseSymbolNotCode;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function localeCurrencySymbolAtFirstPosition(): MM_WPFS_Control {
        return $this->localeCurrencySymbolAtFirstPosition;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function localePutSpaceBetweenSymbolAndAmount(): MM_WPFS_Control {
        return $this->localePutSpaceBetweenSymbolAndAmount;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function buttonLabel(): MM_WPFS_Control {
        return $this->buttonLabel;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function collectBillingAddress(): MM_WPFS_Control {
        return $this->collectBillingAddress;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function defaultBillingCountry(): MM_WPFS_Control {
        return $this->defaultBillingCountry;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function collectShippingAddress(): MM_WPFS_Control {
        return $this->collectShippingAddress;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function showTermsOfService(): MM_WPFS_Control {
        return $this->showTermsOfService;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function termsOfServiceLabel(): MM_WPFS_Control {
        return $this->termsOfServiceLabel;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function termsOfServiceErrorMessage(): MM_WPFS_Control {
        return $this->termsOfServiceErrorMessage;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function customFields(): MM_WPFS_Control {
        return $this->customFields;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function makeCustomFieldsRequired(): MM_WPFS_Control {
        return $this->makeCustomFieldsRequired;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function emailTemplates(): MM_WPFS_Control {
        return $this->emailTemplates;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function stripeElementsThemeSelector(): MM_WPFS_Control {
        return $this->stripeElementsThemeSelector;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function stripeElementsFont(): MM_WPFS_Control {
        return $this->stripeElementsFontSelector;
    }
}

interface MM_WPFS_Admin_InlineFormViewConstants {
    const FIELD_FORM_CARD_FIELD_LANGUAGE = 'wpfs-form-card-field-language';
}

trait MM_WPFS_Admin_InlineFormView {
    /** @var MM_WPFS_Control */
    protected $cardFieldLanguage;

    protected function initInlineFields( $formHash ) {
        $this->cardFieldLanguage = MM_WPFS_ControlUtils::createControl( $formHash, MM_WPFS_Admin_InlineFormViewConstants::FIELD_FORM_CARD_FIELD_LANGUAGE, null, null,
            __( 'Card input field language', 'wp-full-stripe-admin' ), null );
        $this->cardFieldLanguage->setAttributes( array(
            'class'     => 'js-combobox'
        ));
    }

    public static function getInlineFields() {
        $fields = array(
            MM_WPFS_Admin_InlineFormViewConstants::FIELD_FORM_CARD_FIELD_LANGUAGE => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_InlineFormViewConstants::FIELD_FORM_CARD_FIELD_LANGUAGE )
        );

        return $fields;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function cardFieldLanguage(): MM_WPFS_Control {
        return $this->cardFieldLanguage;
    }
}

interface MM_WPFS_Admin_CheckoutFormViewConstants {
    const FIELD_FORM_OPEN_BUTTON_LABEL = 'wpfs-form-open-button-label';
    const FIELD_FORM_CHECKOUT_LANGUAGE = 'wpfs-form-checkout-language';
}

trait MM_WPFS_Admin_CheckoutFormView {
    /** @var MM_WPFS_Control */
    protected $checkoutLanguage;
    protected $openButtonLabel;

    protected function initCheckoutFields( $formHash ) {
        $this->openButtonLabel = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CheckoutFormViewConstants::FIELD_FORM_OPEN_BUTTON_LABEL, null, null,
            __( 'Open form button label', 'wp-full-stripe-admin' ), null );
        $this->openButtonLabel->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-form-control'
        ));

        $this->checkoutLanguage = MM_WPFS_ControlUtils::createControl( $formHash, MM_WPFS_Admin_CheckoutFormViewConstants::FIELD_FORM_CHECKOUT_LANGUAGE, null, null,
            __( 'Checkout form language', 'wp-full-stripe-admin' ), null );
        $this->checkoutLanguage->setAttributes( array(
            'class'     => 'js-combobox'
        ));
    }

    /**
     * @return array
     */
    public static function getCheckoutFields() {
        $fields = array(
            MM_WPFS_Admin_CheckoutFormViewConstants::FIELD_FORM_OPEN_BUTTON_LABEL => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CheckoutFormViewConstants::FIELD_FORM_OPEN_BUTTON_LABEL ),
            MM_WPFS_Admin_CheckoutFormViewConstants::FIELD_FORM_CHECKOUT_LANGUAGE => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_CheckoutFormViewConstants::FIELD_FORM_CHECKOUT_LANGUAGE )
        );

        return $fields;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function checkoutLanguage(): MM_WPFS_Control {
        return $this->checkoutLanguage;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function openButtonLabel(): MM_WPFS_Control {
        return $this->openButtonLabel;
    }
}

interface MM_WPFS_Admin_CheckoutPhoneViewConstants {
    const FIELD_FORM_COLLECT_PHONE_NUMBER = 'wpfs-form-collect-phone-number';
}

trait MM_WPFS_Admin_CheckoutPhoneView_AddOn {
    /** @var MM_WPFS_Control */
    protected $collectPhoneNumber;

    protected function initCheckoutPhoneFields( $formHash ) {
        $this->collectPhoneNumber = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CheckoutPhoneViewConstants::FIELD_FORM_COLLECT_PHONE_NUMBER, null, null,
            __( 'Phone number', 'wp-full-stripe-admin' ), null );
        $this->collectPhoneNumber->setValue('1' );
        $this->collectPhoneNumber->setAttributes( array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input'
        ));
    }

    /**
     * @return array
     */
    public static function getCheckoutPhoneNumberFields() {

        $fields = array(
            // These fields are checkout form fields, there is no need to return them
        );

        return $fields;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function collectPhoneNumber(): MM_WPFS_Control {
        return $this->collectPhoneNumber;
    }
}

trait MM_WPFS_Admin_CheckoutProductImageView_AddOn {
    /** @var MM_WPFS_Control */
    protected $checkoutProductImage;

    protected function initCheckoutProductImageFields( $formHash ) {
        $this->checkoutProductImage = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_CheckoutProductImageViewConstants::FIELD_FORM_PRODUCT_IMAGE, null, null,
            __( 'Product image', 'wp-full-stripe-admin' ), null );
        $this->checkoutProductImage->setAttributes( array(
            'type'      => 'hidden',
        ));
    }

    /**
     * @return array
     */
    public static function getCheckoutProductImageFields() {

        $fields = array(
            // These fields are checkout form fields, there is no need to return them
        );

        return $fields;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function checkoutProductImage(): MM_WPFS_Control {
        return $this->checkoutProductImage;
    }
}

interface MM_WPFS_Admin_SaveCardFormViewConstants {
    const FIELD_FORM_TRANSACTION_DESCRIPTION = 'wpfs-form-transaction-description';
}

class MM_WPFS_Admin_SaveCardFormView extends MM_WPFS_Admin_FormView implements MM_WPFS_Admin_SaveCardFormViewConstants {
    /** @var MM_WPFS_Control */
    protected $transactionDescription;

    public function __construct() {
        parent::__construct();
        $this->initFields();
    }

    protected function initFields() {
        $this->transactionDescription = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_TRANSACTION_DESCRIPTION, null, null,
            __( 'Transaction description', 'wp-full-stripe-admin' ), null );
        $this->transactionDescription->setAttributes( array(
            'class'     => 'wpfs-form-control js-token-target-transaction-description js-position-tracking-transaction-description',
            'rows'      => '3'
        ));
    }

    public static function getFields() {
        $fields = array(
            self::FIELD_FORM_TRANSACTION_DESCRIPTION => MM_WPFS_ControlUtils::input( self::FIELD_FORM_TRANSACTION_DESCRIPTION )
        );

        return array_merge( $fields, parent::getFields() );
    }

    /**
     * @return MM_WPFS_Control
     */
    public function transactionDescription(): MM_WPFS_Control {
        return $this->transactionDescription;
    }
}

interface MM_WPFS_Admin_InlineSaveCardFormViewConstants {
    const FIELD_ACTION_VALUE_SAVE_INLINE_SAVE_CARD_FORM = 'wpfs-save-inline-save-card-form';
}

class MM_WPFS_Admin_InlineSaveCardFormView extends MM_WPFS_Admin_SaveCardFormView implements MM_WPFS_Admin_InlineSaveCardFormViewConstants  {
    use MM_WPFS_Admin_InlineFormView;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_INLINE_SAVE_CARD_FORM;
        parent::__construct();
        $this->action->setValue( self::FIELD_ACTION_VALUE_SAVE_INLINE_SAVE_CARD_FORM );

        $this->initInlineFields( $this->formHash );
    }

    public static function getFields() {
        $inlineFields = self::getInlineFields();

        return array_merge( $inlineFields, parent::getFields() );
    }

    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = self::FIELD_ACTION_VALUE_SAVE_INLINE_SAVE_CARD_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_INLINE_SAVE_CARD_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }
}

interface MM_WPFS_Admin_CheckoutSaveCardFormViewConstants {
    const FIELD_ACTION_VALUE_SAVE_CHECKOUT_SAVE_CARD_FORM = 'wpfs-save-checkout-save-card-form';
}

class MM_WPFS_Admin_CheckoutSaveCardFormView extends MM_WPFS_Admin_SaveCardFormView implements MM_WPFS_Admin_CheckoutSaveCardFormViewConstants  {
    use MM_WPFS_Admin_CheckoutFormView;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_CHECKOUT_SAVE_CARD_FORM;
        parent::__construct();
        $this->action->setValue( self::FIELD_ACTION_VALUE_SAVE_CHECKOUT_SAVE_CARD_FORM );

        $this->initCheckoutFields( $this->formHash );
    }

    public static function getFields() {
        $checkoutFields = self::getCheckoutFields();

        return array_merge( $checkoutFields, parent::getFields() );
    }

    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = self::FIELD_ACTION_VALUE_SAVE_CHECKOUT_SAVE_CARD_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CHECKOUT_SAVE_CARD_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }
}

interface MM_WPFS_Admin_DonationFormViewConstants {
    const FIELD_FORM_TRANSACTION_DESCRIPTION = 'wpfs-form-transaction-description';
    const FIELD_FORM_CURRENCY = 'wpfs-form-currency';
    const FIELD_FORM_DONATION_AMOUNTS = 'wpfs-form-donation-amounts';
    const FIELD_FORM_ADD_AMOUNT = 'wpfs-form-add-amount';
    const FIELD_FORM_ALLOW_CUSTOM_DONATION_AMOUNT = 'wpfs-form-allow-custom-donation-amount';
    const FIELD_FORM_DONATION_FREQUENCIES = 'wpfs-form-donation-frequencies';
    const FIELD_FORM_DONATION_FREQUENCY_ONETIME = 'wpfs-form-donation-frequency-onetime';
    const FIELD_FORM_DONATION_FREQUENCY_DAILY = 'wpfs-form-donation-frequency-daily';
    const FIELD_FORM_DONATION_FREQUENCY_WEEKLY = 'wpfs-form-donation-frequency-weekly';
    const FIELD_FORM_DONATION_FREQUENCY_MONTHLY = 'wpfs-form-donation-frequency-monthly';
    const FIELD_FORM_DONATION_FREQUENCY_ANNUAL = 'wpfs-form-donation-frequency-annual';
    const FIELD_FORM_MINIMUM_DONATION_AMOUNT = 'wpfs-form-minimum-donation-amount';
    const FIELD_FORM_MINIMUM_DONATION_AMOUNT_HIDDEN = 'wpfs-form-minimum-donation-amount-hidden';
}

class MM_WPFS_Admin_DonationFormView extends MM_WPFS_Admin_FormView implements MM_WPFS_Admin_DonationFormViewConstants, MM_WPFS_Admin_View_InvoiceConstants {
    use MM_WPFS_Admin_FormView_InvoiceAddOn;

    /** @var MM_WPFS_Control */
    protected $transactionDescription;
    /** @var MM_WPFS_Control */
    protected $currency;
    /** @var MM_WPFS_Control */
    protected $donationAmounts;
    /** @var MM_WPFS_Control */
    protected $donationFrequencies;
    /** @var MM_WPFS_Control */
    protected $donationFrequencyOnetime;
    /** @var MM_WPFS_Control */
    protected $donationFrequencyDaily;
    /** @var MM_WPFS_Control */
    protected $donationFrequencyWeekly;
    /** @var MM_WPFS_Control */
    protected $donationFrequencyMonthly;
    /** @var MM_WPFS_Control */
    protected $donationFrequencyAnnual;
    /** @var MM_WPFS_Control */
    protected $addAmountButton;
    /** @var MM_WPFS_Control */
    protected $allowCustomDonationAmount;
    /** @var MM_WPFS_Control */
    protected $minimumDonationAmount;
    /** @var MM_WPFS_Control */
    protected $minimumDonationAmountHidden;

    public function __construct() {
        parent::__construct();
        $this->initFields();
    }

    protected function createDonationFrequencies() {
        $this->donationFrequencies = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DONATION_FREQUENCIES, null, null,
            __( 'Donation frequencies', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;

        $this->donationFrequencyOnetime = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DONATION_FREQUENCY_ONETIME, null, null,
            /* translators: The 'one-time' donation frequency label */
            __( 'One-time', 'wp-full-stripe-admin' ), $optionIndex );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
            'value'     => 1
        );
        $this->donationFrequencyOnetime->setAttributes( $optionAttributes );
        $optionIndex++;

        $this->donationFrequencyDaily = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DONATION_FREQUENCY_DAILY, null, null,
            /* translators: The 'daily' donation frequency label */
            __( 'Daily', 'wp-full-stripe-admin' ), $optionIndex );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
            'value'     => 1
        );
        $this->donationFrequencyDaily->setAttributes( $optionAttributes );
        $optionIndex++;

        $this->donationFrequencyWeekly = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DONATION_FREQUENCY_WEEKLY, null, null,
            /* translators: The 'weekly' donation frequency label */
            __( 'Weekly', 'wp-full-stripe-admin' ), $optionIndex );
        $this->donationFrequencyWeekly->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
            'value'     => 1
        );
        $this->donationFrequencyWeekly->setAttributes( $optionAttributes );
        $optionIndex++;

        $this->donationFrequencyMonthly = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DONATION_FREQUENCY_MONTHLY, null, null,
            /* translators: The 'monthly' donation frequency label */
            __( 'Monthly', 'wp-full-stripe-admin' ), $optionIndex );
        $this->donationFrequencyMonthly->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
            'value'     => 1
        );
        $this->donationFrequencyMonthly->setAttributes( $optionAttributes );
        $optionIndex++;

        $this->donationFrequencyAnnual = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DONATION_FREQUENCY_ANNUAL, null, null,
            /* translators: The 'annual' donation frequency label */
            __( 'Annual', 'wp-full-stripe-admin' ), $optionIndex );
        $this->donationFrequencyAnnual->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
            'value'     => 1
        );
        $this->donationFrequencyAnnual->setAttributes( $optionAttributes );
    }

    protected function initFields() {
        $this->transactionDescription = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_TRANSACTION_DESCRIPTION, null, null,
            __( 'Transaction description', 'wp-full-stripe-admin' ), null );
        $this->transactionDescription->setAttributes( array(
            'class'     => 'wpfs-form-control js-token-target-transaction-description js-position-tracking-transaction-description',
            'rows'      => '3'
        ));

        $this->currency = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CURRENCY, null, null,
            __( 'Currency', 'wp-full-stripe-admin' ), null );
        $this->currency->setAttributes( array(
            'class'     => 'js-combobox'
        ));

        $this->donationAmounts = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_DONATION_AMOUNTS, null, null,
            __( 'Suggested donation amounts', 'wp-full-stripe-admin' ), null );
        $this->donationAmounts->setAttributes( array(
            'type'      => 'hidden',
        ));

        $this->addAmountButton = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_ADD_AMOUNT, null, null,
            /* translators: Label for the button which opens the "Add amount" dialog */
            __( 'Add suggested donation amount', 'wp-full-stripe-admin' ), null );


        $this->allowCustomDonationAmount = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_ALLOW_CUSTOM_DONATION_AMOUNT, null, null,
            __( 'Allow custom amount to be entered', 'wp-full-stripe-admin' ), null );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
            'value'     => 1
        );
        $this->allowCustomDonationAmount->setAttributes( $optionAttributes );


        $this->minimumDonationAmount = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_MINIMUM_DONATION_AMOUNT, null, null,
            /* translators: Form field label for the minimum donation amount */
            __( 'Minimum donation amount', 'wp-full-stripe-admin' ), null );

        $this->minimumDonationAmountHidden = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_MINIMUM_DONATION_AMOUNT_HIDDEN, null, null,
            __( 'One-time products', 'wp-full-stripe-admin' ), null );
        $this->minimumDonationAmountHidden->setAttributes( array(
            'type'      => 'hidden',
        ));

        $this->initGenerateInvoice();
        $this->createDonationFrequencies();
    }

    public static function getFields() {
        $fields = array(
            self::FIELD_FORM_TRANSACTION_DESCRIPTION        => MM_WPFS_ControlUtils::input( self::FIELD_FORM_TRANSACTION_DESCRIPTION ),
            self::FIELD_FORM_CURRENCY                       => MM_WPFS_ControlUtils::input( self::FIELD_FORM_CURRENCY ),
            self::FIELD_FORM_MINIMUM_DONATION_AMOUNT        => MM_WPFS_ControlUtils::inputGroup( self::FIELD_FORM_MINIMUM_DONATION_AMOUNT ),
            self::FIELD_FORM_ALLOW_CUSTOM_DONATION_AMOUNT   => MM_WPFS_ControlUtils::checkbox( self::FIELD_FORM_ALLOW_CUSTOM_DONATION_AMOUNT ),
            self::FIELD_FORM_DONATION_FREQUENCIES           => MM_WPFS_ControlUtils::checklist( self::FIELD_FORM_DONATION_FREQUENCIES ),
            self::FIELD_FORM_DONATION_FREQUENCY_ONETIME     => MM_WPFS_ControlUtils::checkbox( self::FIELD_FORM_DONATION_FREQUENCY_ONETIME ),
            self::FIELD_FORM_DONATION_FREQUENCY_DAILY       => MM_WPFS_ControlUtils::checkbox( self::FIELD_FORM_DONATION_FREQUENCY_DAILY ),
            self::FIELD_FORM_DONATION_FREQUENCY_WEEKLY      => MM_WPFS_ControlUtils::checkbox( self::FIELD_FORM_DONATION_FREQUENCY_WEEKLY ),
            self::FIELD_FORM_DONATION_FREQUENCY_MONTHLY     => MM_WPFS_ControlUtils::checkbox( self::FIELD_FORM_DONATION_FREQUENCY_MONTHLY ),
            self::FIELD_FORM_DONATION_FREQUENCY_ANNUAL      => MM_WPFS_ControlUtils::checkbox( self::FIELD_FORM_DONATION_FREQUENCY_ANNUAL ),
        );

        return array_merge( $fields,
            parent::getFields(),
            MM_WPFS_Admin_FormView_InvoiceAddOn::getInvoiceFields()
        );
    }

    /**
     * @return MM_WPFS_Control
     */
    public function transactionDescription(): MM_WPFS_Control {
        return $this->transactionDescription;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function currency(): MM_WPFS_Control {
        return $this->currency;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function donationAmounts(): MM_WPFS_Control {
        return $this->donationAmounts;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function donationFrequencies(): MM_WPFS_Control {
        return $this->donationFrequencies;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function donationFrequencyOnetime(): MM_WPFS_Control {
        return $this->donationFrequencyOnetime;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function donationFrequencyDaily(): MM_WPFS_Control {
        return $this->donationFrequencyDaily;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function donationFrequencyWeekly(): MM_WPFS_Control {
        return $this->donationFrequencyWeekly;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function donationFrequencyMonthly(): MM_WPFS_Control {
        return $this->donationFrequencyMonthly;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function donationFrequencyAnnual(): MM_WPFS_Control {
        return $this->donationFrequencyAnnual;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function addAmountButton(): MM_WPFS_Control {
        return $this->addAmountButton;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function allowCustomDonationAmount(): MM_WPFS_Control {
        return $this->allowCustomDonationAmount;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function minimumDonationAmount(): MM_WPFS_Control {
        return $this->minimumDonationAmount;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function minimumDonationAmountHidden(): MM_WPFS_Control {
        return $this->minimumDonationAmountHidden;
    }
}

interface MM_WPFS_Admin_InlineDonationFormViewConstants {
    const FIELD_ACTION_VALUE_SAVE_INLINE_DONATION_FORM = 'wpfs-save-inline-donation-form';

    const FIELD_FORM_DEFAULT_PRODUCT_NAME = 'wpfs-form-custom-product-name';
}

class MM_WPFS_Admin_InlineDonationFormView extends MM_WPFS_Admin_DonationFormView implements MM_WPFS_Admin_InlineDonationFormViewConstants  {
    use MM_WPFS_Admin_InlineFormView;

    /** @var MM_WPFS_Control */
    protected $defaultProductName;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_INLINE_DONATION_FORM;
        parent::__construct();
        $this->action->setValue( self::FIELD_ACTION_VALUE_SAVE_INLINE_DONATION_FORM );

        $this->initInlineFields( $this->formHash );
        $this->initInlineDonationFields( $this->formHash );
    }

    protected function initInlineDonationFields( $formHash ) {
        $this->defaultProductName = MM_WPFS_ControlUtils::createControl( $formHash, self::FIELD_FORM_DEFAULT_PRODUCT_NAME, null, null,
            __( 'Donation product name', 'wp-full-stripe-admin' ), null );
        $this->defaultProductName->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-form-control'
        ));
    }

    public static function getFields() {
        $inlineFields = self::getInlineFields();

        $inlineDonationFields = array(
            self::FIELD_FORM_DEFAULT_PRODUCT_NAME        => MM_WPFS_ControlUtils::input( self::FIELD_FORM_DEFAULT_PRODUCT_NAME ),
        );

        return array_merge( $inlineFields,
            parent::getFields(),
            $inlineDonationFields
        );
    }

    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = self::FIELD_ACTION_VALUE_SAVE_INLINE_DONATION_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_INLINE_DONATION_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function defaultProductName(): MM_WPFS_Control  {
        return $this->defaultProductName;
    }
}

interface MM_WPFS_Admin_CheckoutDonationFormViewConstants {
    const FIELD_FORM_CHECKOUT_PRODUCT_NAME = 'wpfs-form-checkout-product-name';
    const FIELD_FORM_CHECKOUT_PRODUCT_DESCRIPTION = 'wpfs-form-checkout-product-description';

    const FIELD_ACTION_VALUE_SAVE_CHECKOUT_DONATION_FORM = 'wpfs-save-checkout-donation-form';
}

class MM_WPFS_Admin_CheckoutDonationFormView extends MM_WPFS_Admin_DonationFormView implements MM_WPFS_Admin_CheckoutDonationFormViewConstants  {
    use MM_WPFS_Admin_CheckoutFormView;
    use MM_WPFS_Admin_CheckoutPhoneView_AddOn;
    use MM_WPFS_Admin_CheckoutProductImageView_AddOn;

    /** @var MM_WPFS_Control */
    protected $checkoutProductName;
    /** @var MM_WPFS_Control */
    protected $checkoutProductDescription;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_CHECKOUT_DONATION_FORM;
        parent::__construct();
        $this->action->setValue( self::FIELD_ACTION_VALUE_SAVE_CHECKOUT_DONATION_FORM );

        $this->initCheckoutFields( $this->formHash );
        $this->initCheckoutDonationFields();
        $this->initCheckoutPhoneFields( $this->formHash );
        $this->initCheckoutProductImageFields( $this->formHash );
    }

    protected function initCheckoutDonationFields() {
        $this->checkoutProductName = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CHECKOUT_PRODUCT_NAME, null, null,
            /* translators: Name of the donation product displayed as a line item */
            __( 'Donation product name', 'wp-full-stripe-admin' ), null );
        $this->checkoutProductName->setAttributes( array(
            'class'     => 'wpfs-form-control',
            'type'      => 'text'
        ));

        $this->checkoutProductDescription = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CHECKOUT_PRODUCT_DESCRIPTION, null, null,
            /* translators: Description of the donation product displayed as descripition of the line item */
            __( 'Donation product description', 'wp-full-stripe-admin' ), null );
        $this->checkoutProductDescription->setAttributes( array(
            'class'     => 'wpfs-form-control',
            'type'      => 'text'
        ));
    }

    public static function getFields() {
        $checkoutFields = self::getCheckoutFields();

        $fields = array(
            self::FIELD_FORM_CHECKOUT_PRODUCT_NAME        => MM_WPFS_ControlUtils::input( self::FIELD_FORM_CHECKOUT_PRODUCT_NAME ),
            self::FIELD_FORM_CHECKOUT_PRODUCT_DESCRIPTION  => MM_WPFS_ControlUtils::input( self::FIELD_FORM_CHECKOUT_PRODUCT_DESCRIPTION ),
        );

        return array_merge( $fields, $checkoutFields, parent::getFields() );
    }

    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = self::FIELD_ACTION_VALUE_SAVE_CHECKOUT_DONATION_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CHECKOUT_DONATION_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function checkoutProductName(): MM_WPFS_Control {
        return $this->checkoutProductName;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function checkoutProductDescription(): MM_WPFS_Control
    {
        return $this->checkoutProductDescription;
    }
}

interface MM_WPFS_Admin_PaymentFormViewConstants {
    const FIELD_FORM_TRANSACTION_DESCRIPTION = 'wpfs-form-transaction-description';
    const FIELD_FORM_CURRENCY = 'wpfs-form-currency';
    const FIELD_FORM_PAYMENT_TYPE = 'wpfs-form-payment-type';
    const FIELD_FORM_CHARGE_TYPE = 'wpfs-form-charge-type';
    const FIELD_FORM_ALLOW_CUSTOM_PAYMENT_AMOUNT = 'wpfs-form-allow-custom-amount';
    const FIELD_FORM_PRODUCT_SELECTOR_STYLE = 'wpfs-form-product-selector-style';
    const FIELD_FORM_ONETIME_PRODUCTS = 'wpfs-form-onetime-products';
    const FIELD_FORM_ONETIME_PRODUCTS_ERROR = 'wpfs-form-onetime-products-error';
    const FIELD_FORM_MINIMUM_PAYMENT_AMOUNT = 'wpfs-form-minimum-payment-amount';
    const FIELD_FORM_MINIMUM_PAYMENT_AMOUNT_HIDDEN = 'wpfs-form-minimum-payment-amount-hidden';
}

interface MM_WPFS_Admin_View_InvoiceConstants {
    const FIELD_FORM_GENERATE_INVOICE = 'wpfs-form-generate-invoice';
}

trait MM_WPFS_Admin_FormView_InvoiceAddOn {
    protected $generateInvoice;
    /** @var MM_WPFS_Control */

    protected function initGenerateInvoice() {
        $this->generateInvoice = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_InvoiceConstants::FIELD_FORM_GENERATE_INVOICE, null, null,
            __( 'Generate invoice', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_InvoiceConstants::FIELD_FORM_GENERATE_INVOICE, null, null,
            __( 'No', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, MM_WPFS_Admin_View_InvoiceConstants::FIELD_FORM_GENERATE_INVOICE, null, null,
            __( 'Yes', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->generateInvoice->setOptions( $options );
    }

    public static function getInvoiceFields() {
        return array(
            MM_WPFS_Admin_View_InvoiceConstants::FIELD_FORM_GENERATE_INVOICE => MM_WPFS_ControlUtils::input( MM_WPFS_Admin_View_InvoiceConstants::FIELD_FORM_GENERATE_INVOICE ),
        );
    }

    /**
     * @return MM_WPFS_Control
     */
    public function generateInvoice(): MM_WPFS_Control {
        return $this->generateInvoice;
    }
}

class MM_WPFS_Admin_PaymentFormView extends MM_WPFS_Admin_FormView implements MM_WPFS_Admin_PaymentFormViewConstants, MM_WPFS_Admin_View_TaxConstants, MM_WPFS_Admin_View_CouponConstants, MM_WPFS_Admin_View_InvoiceConstants {
    use MM_WPFS_Admin_FormView_CouponAddOn;
    use MM_WPFS_Admin_FormView_TaxAddOn;
    use MM_WPFS_Admin_FormView_InvoiceAddon;

    /** @var MM_WPFS_Control */
    protected $transactionDescription;
    /** @var MM_WPFS_Control */
    protected $currency;
    /** @var MM_WPFS_Control */
    protected $paymentType;
    /** @var MM_WPFS_Control */
    protected $chargeType;
    /** @var MM_WPFS_Control */
    protected $allowCustomPaymentAmount;
    /** @var MM_WPFS_Control */
    protected $productSelectorStyle;
    /** @var MM_WPFS_Control */
    protected $onetimeProducts;
    /** @var MM_WPFS_Control */
    protected $minimumPaymentAmount;
    /** @var MM_WPFS_Control */
    protected $minimumPaymentAmountHidden;

    public function __construct() {
        parent::__construct();
        $this->initFields();
    }

    protected function initPaymentType() {
        $this->paymentType = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PAYMENT_TYPE, null, null,
            __( 'Payment type', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PAYMENT_TYPE, null, null,
            /* translators: Payment type where customers can pick a product offered by the payment form  */
            __( 'List of products', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PAYMENT_TYPE, null, null,
            /* translators: Payment type where customers can enter the amount to be paid, eg. paying invoices  */
            __( 'User defined amount', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->paymentType->setOptions( $options );
    }

    protected function initChargeType() {
        $this->chargeType = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CHARGE_TYPE, null, null,
            __( 'Charge type', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CHARGE_TYPE, null, null,
            /* translators: Immediate charge type, the card is charged when the form is submitted  */
            __( 'Immediate', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::CHARGE_TYPE_IMMEDIATE );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CHARGE_TYPE, null, null,
            /* translators: Authorize and capture charge type, the payment is in a pending state when the form is submitted, it can be captured later */
            __( 'Authorize and capture', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( MM_WPFS::CHARGE_TYPE_AUTHORIZE_AND_CAPTURE );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->chargeType->setOptions( $options );
    }

    protected function initProductSelectorStyle() {
        $this->productSelectorStyle = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PRODUCT_SELECTOR_STYLE, null, null,
            __( 'Product selector style', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $selectorStyleOptions = array();

        $listOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PRODUCT_SELECTOR_STYLE, null, null,
            /* translators: This selector style is a list of radio buttons  */
            __( 'List of products', 'wp-full-stripe-admin' ), $optionIndex );
        $listOption->setValue( MM_WPFS::SELECTOR_STYLE_RADIO_BUTTONS );
        $listOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        ));
        $listOption->setMetadata( array(
            'description'   =>
                __( 'Recommended if there are less than 5 options', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-layout-bullets'
        ));
        array_push( $selectorStyleOptions, $listOption );
        $optionIndex++;

        $dropdownOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PRODUCT_SELECTOR_STYLE, null, null,
            /* translators: This selector style is a dropdown  */
            __( 'Product selector dropdown', 'wp-full-stripe-admin' ), $optionIndex );
        $dropdownOption->setValue( MM_WPFS::SELECTOR_STYLE_DROPDOWN );
        $dropdownOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        ));
        $dropdownOption->setMetadata( array(
            'description'   =>
                __( 'Recommended if there are 5 or more options', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-layout-top'
        ));
        array_push( $selectorStyleOptions, $dropdownOption );
        $optionIndex++;

        $buttonGroupOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PRODUCT_SELECTOR_STYLE, null, null,
            /* translators: This selector style is a group of buttons showing only payment amounts, not product names */
            __( 'Product buttons with amount (deprecated)', 'wp-full-stripe-admin' ), $optionIndex );
        $buttonGroupOption->setValue( MM_WPFS::SELECTOR_STYLE_BUTTON_GROUP );
        $buttonGroupOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        ));
        $buttonGroupOption->setMetadata( array(
            'description'   =>
                __( 'Use donation forms instead', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-amount'
        ));
        array_push( $selectorStyleOptions, $buttonGroupOption );

        $this->productSelectorStyle->setOptions( $selectorStyleOptions );
    }

    protected function initMinimumPaymentAmount() {
        $this->minimumPaymentAmount = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_MINIMUM_PAYMENT_AMOUNT, null, null,
            /* translators: Form field label for the minimum payment amount */
            __( 'Minimum payment amount', 'wp-full-stripe-admin' ), null );

        $this->minimumPaymentAmountHidden = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_MINIMUM_PAYMENT_AMOUNT_HIDDEN, null, null,
            __( 'Minimum payment amount hidden', 'wp-full-stripe-admin' ), null );
        $this->minimumPaymentAmountHidden->setAttributes( array(
            'type'      => 'hidden',
        ));
    }

    protected function initFields() {
        $this->transactionDescription = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_TRANSACTION_DESCRIPTION, null, null,
            __( 'Transaction description', 'wp-full-stripe-admin' ), null );
        $this->transactionDescription->setAttributes( array(
            'class'     => 'wpfs-form-control js-token-target-transaction-description js-position-tracking-transaction-description',
            'rows'      => '3'
        ));

        $this->currency = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CURRENCY, null, null,
            __( 'Currency', 'wp-full-stripe-admin' ), null );
        $this->currency->setAttributes( array(
            'class'     => 'js-combobox'
        ));

        $this->allowCustomPaymentAmount = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_ALLOW_CUSTOM_PAYMENT_AMOUNT, null, null,
            __( 'Allow custom amount to be entered', 'wp-full-stripe-admin' ), null );
        $optionAttributes = array(
            'type'      => 'checkbox',
            'class'     => 'wpfs-form-check-input',
            'value'     => 1
        );
        $this->allowCustomPaymentAmount->setAttributes( $optionAttributes );

        $this->onetimeProducts = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_ONETIME_PRODUCTS, null, null,
            __( 'One-time products', 'wp-full-stripe-admin' ), null );
        $this->onetimeProducts->setAttributes( array(
            'type'      => 'hidden',
        ));

        $this->initPaymentType();
        $this->initChargeType();
        $this->initMinimumPaymentAmount();
        $this->initGenerateInvoice();
        $this->initTaxFields();
        $this->initCollectCustomerTaxId();
        $this->initProductSelectorStyle();
        $this->initCoupon();
    }

    public static function getFields() {
        $fields = array(
            self::FIELD_FORM_TRANSACTION_DESCRIPTION        => MM_WPFS_ControlUtils::input( self::FIELD_FORM_TRANSACTION_DESCRIPTION ),
            self::FIELD_FORM_CURRENCY                       => MM_WPFS_ControlUtils::input( self::FIELD_FORM_CURRENCY ),
            self::FIELD_FORM_MINIMUM_PAYMENT_AMOUNT         => MM_WPFS_ControlUtils::inputGroup( self::FIELD_FORM_MINIMUM_PAYMENT_AMOUNT ),
            self::FIELD_FORM_PAYMENT_TYPE                   => MM_WPFS_ControlUtils::input( self::FIELD_FORM_PAYMENT_TYPE ),
            self::FIELD_FORM_CHARGE_TYPE                    => MM_WPFS_ControlUtils::input( self::FIELD_FORM_CHARGE_TYPE ),
            self::FIELD_FORM_ALLOW_CUSTOM_PAYMENT_AMOUNT    => MM_WPFS_ControlUtils::input( self::FIELD_FORM_ALLOW_CUSTOM_PAYMENT_AMOUNT ),
            self::FIELD_FORM_PRODUCT_SELECTOR_STYLE         => MM_WPFS_ControlUtils::input( self::FIELD_FORM_PRODUCT_SELECTOR_STYLE ),
            self::FIELD_FORM_ONETIME_PRODUCTS_ERROR         => MM_WPFS_ControlUtils::products( self::FIELD_FORM_ONETIME_PRODUCTS_ERROR ),
        );

        return array_merge( $fields,
            parent::getFields(),
            MM_WPFS_Admin_FormView_TaxAddOn::getTaxFields(),
            MM_WPFS_Admin_FormView_InvoiceAddOn::getInvoiceFields(),
            MM_WPFS_Admin_FormView_CouponAddOn::getCouponFields()
        );
    }

    /**
     * @return MM_WPFS_Control
     */
    public function transactionDescription(): MM_WPFS_Control {
        return $this->transactionDescription;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function currency(): MM_WPFS_Control {
        return $this->currency;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function paymentType(): MM_WPFS_Control {
        return $this->paymentType;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function chargeType(): MM_WPFS_Control {
        return $this->chargeType;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function allowCustomPaymentAmount(): MM_WPFS_Control {
        return $this->allowCustomPaymentAmount;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function productSelectorStyle(): MM_WPFS_Control {
        return $this->productSelectorStyle;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function onetimeProducts(): MM_WPFS_Control {
        return $this->onetimeProducts;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function minimumPaymentAmount(): MM_WPFS_Control {
        return $this->minimumPaymentAmount;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function minimumPaymentAmountHidden(): MM_WPFS_Control {
        return $this->minimumPaymentAmountHidden;
    }
}

interface MM_WPFS_Admin_InlinePaymentFormViewConstants {
    const FIELD_ACTION_VALUE_SAVE_INLINE_PAYMENT_FORM = 'wpfs-save-inline-payment-form';
}

class MM_WPFS_Admin_InlinePaymentFormView extends MM_WPFS_Admin_PaymentFormView implements MM_WPFS_Admin_InlinePaymentFormViewConstants  {
    use MM_WPFS_Admin_InlineFormView;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_INLINE_PAYMENT_FORM;
        parent::__construct();
        $this->action->setValue( self::FIELD_ACTION_VALUE_SAVE_INLINE_PAYMENT_FORM );

        $this->initInlineFields( $this->formHash );
    }

    public static function getFields() {
        $inlineFields = self::getInlineFields();

        return array_merge( $inlineFields, parent::getFields() );
    }

    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = self::FIELD_ACTION_VALUE_SAVE_INLINE_PAYMENT_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_INLINE_PAYMENT_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }
}

interface MM_WPFS_Admin_CheckoutPaymentFormViewConstants {
    const FIELD_FORM_CHECKOUT_PRODUCT_NAME = 'wpfs-form-checkout-product-name';
    const FIELD_FORM_CHECKOUT_PRODUCT_DESCRIPTION = 'wpfs-form-checkout-product-description';

    const FIELD_ACTION_VALUE_SAVE_CHECKOUT_PAYMENT_FORM = 'wpfs-save-checkout-payment-form';
}

class MM_WPFS_Admin_CheckoutPaymentFormView extends MM_WPFS_Admin_PaymentFormView implements MM_WPFS_Admin_CheckoutPaymentFormViewConstants  {
    use MM_WPFS_Admin_CheckoutFormView;
    use MM_WPFS_Admin_CheckoutPhoneView_AddOn;

    /** @var MM_WPFS_Control */
    protected $checkoutProductName;
    /** @var MM_WPFS_Control */
    protected $checkoutProductDescription;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_CHECKOUT_PAYMENT_FORM;
        parent::__construct();
        $this->action->setValue( self::FIELD_ACTION_VALUE_SAVE_CHECKOUT_PAYMENT_FORM );

        $this->initCheckoutFields( $this->formHash );
        $this->initCheckoutPaymentFields( );
        $this->initCheckoutPhoneFields( $this->formHash );
    }

    protected function initCheckoutPaymentFields() {
        $this->checkoutProductName = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CHECKOUT_PRODUCT_NAME, null, null,
            /* translators: When a custom amount is entered on the form, this is the name which is used for the line item  */
            __( 'Custom product name', 'wp-full-stripe-admin' ), null );
        $this->checkoutProductName->setAttributes( array(
            'class'     => 'wpfs-form-control',
            'type'      => 'text'
        ));

        $this->checkoutProductDescription = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_CHECKOUT_PRODUCT_DESCRIPTION, null, null,
            /* translators: When a custom amount is entered on the form, this is the description which is used for the line item  */
            __( 'Custom product description', 'wp-full-stripe-admin' ), null );
        $this->checkoutProductDescription->setAttributes( array(
            'class'     => 'wpfs-form-control',
            'type'      => 'text'
        ));
    }

    public static function getFields() {
        $checkoutFields = self::getCheckoutFields();

        $fields = array(
            self::FIELD_FORM_CHECKOUT_PRODUCT_NAME        => MM_WPFS_ControlUtils::input( self::FIELD_FORM_CHECKOUT_PRODUCT_NAME ),
            self::FIELD_FORM_CHECKOUT_PRODUCT_DESCRIPTION  => MM_WPFS_ControlUtils::input( self::FIELD_FORM_CHECKOUT_PRODUCT_DESCRIPTION ),
        );

        return array_merge( $fields, $checkoutFields, parent::getFields() );
    }

    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = self::FIELD_ACTION_VALUE_SAVE_CHECKOUT_PAYMENT_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CHECKOUT_PAYMENT_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function checkoutProductName(): MM_WPFS_Control {
        return $this->checkoutProductName;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function checkoutProductDescription(): MM_WPFS_Control {
        return $this->checkoutProductDescription;
    }
}

interface MM_WPFS_Admin_SubscriptionFormViewConstants {
    const FIELD_FORM_PLAN_SELECTOR_STYLE = 'wpfs-form-plan-selector-style';
    const FIELD_FORM_RECURRING_PRODUCTS = 'wpfs-form-recurring-products';
    const FIELD_FORM_ALLOW_SUBSCRIPTION_QUANTITY = 'wpfs-form-subscription-quantity';
    const FIELD_FORM_SUBSCRIPTION_MINIMUM_QUANTITY = 'wpfs-form-subscription-minimum-quantity';
    const FIELD_FORM_SUBSCRIPTION_MAXIMUM_QUANTITY = 'wpfs-form-subscription-maximum-quantity';
    const FIELD_FORM_SHOW_COUPON = 'wpfs-form-show-coupon';
    const FIELD_FORM_RECURRING_PRODUCTS_ERROR = 'wpfs-form-recurring-products-error';
    const FIELD_FORM_TAX_RATE_TYPE = 'wpfs-form-tax-rate-type';
    const FIELD_FORM_COLLECT_CUSTOMER_TAX_ID = 'wpfs-form-collect-customer-tax-id';
    const FIELD_FORM_TAX_RATES = 'wpfs-form-tax-rates';
    const FIELD_FORM_TAX_RATES_ERROR = 'wpfs-form-tax-rates-error';
}

class MM_WPFS_Admin_SubscriptionFormView extends MM_WPFS_Admin_FormView implements MM_WPFS_Admin_SubscriptionFormViewConstants {
    use MM_WPFS_Admin_FormView_CouponAddOn;
    use MM_WPFS_Admin_FormView_TaxAddOn;

    /** @var MM_WPFS_Control */
    protected $allowSubscriptionQuantity;
    /** @var MM_WPFS_Control */
    protected $subscriptionMinimumQuantity;
    /** @var MM_WPFS_Control */
    protected $subscriptionMaximumQuantity;
    /** @var MM_WPFS_Control */
    protected $productSelectorStyle;
    /** @var MM_WPFS_Control */
    protected $recurringProducts;

    public function __construct() {
        parent::__construct();
        $this->initFields();
    }

    protected function initAllowSubscriptionQuantity() {
        $this->allowSubscriptionQuantity = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_ALLOW_SUBSCRIPTION_QUANTITY, null, null,
            __( 'Allow subscribing to plans in bulk', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_ALLOW_SUBSCRIPTION_QUANTITY, null, null,
            __( 'No', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '0' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_ALLOW_SUBSCRIPTION_QUANTITY, null, null,
            __( 'Yes', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( '1' );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->allowSubscriptionQuantity->setOptions( $options );
    }

    protected function initPlanSelectorStyle() {
        $this->productSelectorStyle = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PLAN_SELECTOR_STYLE, null, null,
            __( 'Plan selector style', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $selectorStyleOptions = array();

        $listOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PLAN_SELECTOR_STYLE, null, null,
            /* translators: This style is a list of radio buttons  */
            __( 'List of products', 'wp-full-stripe-admin' ), $optionIndex );
        $listOption->setValue( MM_WPFS::SELECTOR_STYLE_RADIO_BUTTONS );
        $listOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        ));
        $listOption->setMetadata( array(
            'description'   =>
                __( 'Recommended if there are less than 5 options', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-layout-bullets'
        ));
        array_push( $selectorStyleOptions, $listOption );
        $optionIndex++;

        $dropdownOption = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_PLAN_SELECTOR_STYLE, null, null,
            /* translators: This style is a dropdown */
            __( 'Product selector dropdown', 'wp-full-stripe-admin' ), $optionIndex );
        $dropdownOption->setValue( MM_WPFS::SELECTOR_STYLE_DROPDOWN );
        $dropdownOption->setAttributes( array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        ));
        $dropdownOption->setMetadata( array(
            'description'   =>
                __( 'Recommended if there are 5 or more options', 'wp-full-stripe-admin' ),
            'iconClass'     => 'wpfs-illu-layout-top'
        ));
        array_push( $selectorStyleOptions, $dropdownOption );

        $this->productSelectorStyle->setOptions( $selectorStyleOptions );
    }

    protected function initFields() {
        $this->recurringProducts = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_RECURRING_PRODUCTS, null, null,
            __( 'Recurring products', 'wp-full-stripe-admin' ), null );
        $this->recurringProducts->setAttributes( array(
            'type'      => 'hidden',
        ));

        $this->subscriptionMinimumQuantity = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_SUBSCRIPTION_MINIMUM_QUANTITY, null, null,
            __( 'MIN', 'wp-full-stripe-admin' ), null );
        $this->subscriptionMinimumQuantity->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-input-group-form-control wpfs-input-group-form-control--center'
        ));

        $this->subscriptionMaximumQuantity = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_SUBSCRIPTION_MAXIMUM_QUANTITY, null, null,
            __( 'MAX', 'wp-full-stripe-admin' ), null );
        $this->subscriptionMaximumQuantity->setAttributes( array(
            'type'      => 'text',
            'class'     => 'wpfs-input-group-form-control wpfs-input-group-form-control--center'
        ));

        $this->initAllowSubscriptionQuantity();
        $this->initTaxFields();
        $this->initCollectCustomerTaxId();
        $this->initPlanSelectorStyle();
        $this->initCoupon();
    }

    public static function getFields() {
        $fields = array(
            self::FIELD_FORM_ALLOW_SUBSCRIPTION_QUANTITY      => MM_WPFS_ControlUtils::input( self::FIELD_FORM_ALLOW_SUBSCRIPTION_QUANTITY ),
            self::FIELD_FORM_SUBSCRIPTION_MINIMUM_QUANTITY    => MM_WPFS_ControlUtils::inputGroupMinMax( self::FIELD_FORM_SUBSCRIPTION_MINIMUM_QUANTITY ),
            self::FIELD_FORM_SUBSCRIPTION_MAXIMUM_QUANTITY    => MM_WPFS_ControlUtils::inputGroupMinMax( self::FIELD_FORM_SUBSCRIPTION_MAXIMUM_QUANTITY ),
            self::FIELD_FORM_PLAN_SELECTOR_STYLE              => MM_WPFS_ControlUtils::input( self::FIELD_FORM_PLAN_SELECTOR_STYLE ),
            self::FIELD_FORM_RECURRING_PRODUCTS_ERROR         => MM_WPFS_ControlUtils::products( self::FIELD_FORM_RECURRING_PRODUCTS_ERROR ),
        );

        return array_merge( $fields,
            parent::getFields(),
            MM_WPFS_Admin_FormView_TaxAddOn::getTaxFields(),
            MM_WPFS_Admin_FormView_CouponAddOn::getCouponFields()
        );
    }

    /**
     * @return MM_WPFS_Control
     */
    public function allowSubscriptionQuantity(): MM_WPFS_Control {
        return $this->allowSubscriptionQuantity;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function subscriptionMinimumQuantity(): MM_WPFS_Control {
        return $this->subscriptionMinimumQuantity;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function subscriptionMaximumQuantity(): MM_WPFS_Control {
        return $this->subscriptionMaximumQuantity;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function productSelectorStyle(): MM_WPFS_Control {
        return $this->productSelectorStyle;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function recurringProducts(): MM_WPFS_Control {
        return $this->recurringProducts;
    }
}

interface MM_WPFS_Admin_InlineSubscriptionFormViewConstants {
    const FIELD_ACTION_VALUE_SAVE_INLINE_SUBSCRIPTION_FORM = 'wpfs-save-inline-subscription-form';
}

class MM_WPFS_Admin_InlineSubscriptionFormView extends MM_WPFS_Admin_SubscriptionFormView implements MM_WPFS_Admin_InlineSubscriptionFormViewConstants  {
    use MM_WPFS_Admin_InlineFormView;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_INLINE_SUBSCRIPTION_FORM;
        parent::__construct();
        $this->action->setValue( self::FIELD_ACTION_VALUE_SAVE_INLINE_SUBSCRIPTION_FORM );

        $this->initInlineFields( $this->formHash );
    }

    public static function getFields() {
        $inlineFields = self::getInlineFields();

        return array_merge( $inlineFields, parent::getFields() );
    }

    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = self::FIELD_ACTION_VALUE_SAVE_INLINE_SUBSCRIPTION_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_INLINE_SUBSCRIPTION_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }
}

interface MM_WPFS_Admin_CheckoutSubscriptionFormViewConstants {
    const FIELD_FORM_SIMPLE_BUTTON_LAYOUT = 'wpfs-form-simple-button-layout';

    const FIELD_ACTION_VALUE_SAVE_CHECKOUT_SUBSCRIPTION_FORM = 'wpfs-save-checkout-subscription-form';
}

class MM_WPFS_Admin_CheckoutSubscriptionFormView extends MM_WPFS_Admin_SubscriptionFormView implements MM_WPFS_Admin_CheckoutSubscriptionFormViewConstants  {
    use MM_WPFS_Admin_CheckoutFormView;
    use MM_WPFS_Admin_CheckoutPhoneView_AddOn;

    /** @var MM_WPFS_Control */
    protected $simpleButtonLayout;

    public function __construct() {
        $this->formHash = MM_WPFS_AdminViewConstants::FORM_HASH_ADMIN_CHECKOUT_SUBSCRIPTION_FORM;
        parent::__construct();
        $this->action->setValue( self::FIELD_ACTION_VALUE_SAVE_CHECKOUT_SUBSCRIPTION_FORM );

        $this->initCheckoutFields( $this->formHash );
        $this->initCheckoutSubscriptionFields( $this->formHash );
        $this->initCheckoutPhoneFields( $this->formHash );
    }

    protected function initCheckoutSubscriptionFields( $formHash ) {
        $this->simpleButtonLayout = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_SIMPLE_BUTTON_LAYOUT, null, null,
            /* translators: When turned on, only the button to open the form is displayed, all other fields are hidden */
            __( 'Simple button layout', 'wp-full-stripe-admin' ), null );

        $optionIndex = 0;
        $options = array();

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_SIMPLE_BUTTON_LAYOUT, null, null,
            __( 'Disable', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( 0 );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );
        $optionIndex++;

        $option = MM_WPFS_ControlUtils::createControl( $this->formHash, self::FIELD_FORM_SIMPLE_BUTTON_LAYOUT, null, null,
            __( 'Enable', 'wp-full-stripe-admin' ), $optionIndex );
        $option->setValue( 1 );
        $optionAttributes = array(
            'type'      => 'radio',
            'class'     => 'wpfs-form-check-input'
        );
        $option->setAttributes( $optionAttributes );
        array_push( $options, $option );

        $this->simpleButtonLayout->setOptions( $options );
    }

    public static function getFields() {
        $checkoutFields = self::getCheckoutFields();

        $fields = array(
            self::FIELD_FORM_SIMPLE_BUTTON_LAYOUT => MM_WPFS_ControlUtils::input( self::FIELD_FORM_SIMPLE_BUTTON_LAYOUT ),
        );

        return array_merge( $fields, $checkoutFields, parent::getFields() );
    }

    protected function getFormAttributes() {
        $attributes = array();

        $attributes[ self::ATTR_ID ]                  = self::FIELD_ACTION_VALUE_SAVE_CHECKOUT_SUBSCRIPTION_FORM;
        $attributes[ self::ATTR_DATA_WPFS_FORM_TYPE ] = MM_WPFS::FORM_TYPE_ADMIN_CHECKOUT_SUBSCRIPTION_FORM;
        $attributes                                   = array_merge( $attributes, parent::getFormAttributes() );

        return $attributes;
    }

    /**
     * @return MM_WPFS_Control
     */
    public function simpleButtonLayout(): MM_WPFS_Control {
        return $this->simpleButtonLayout;
    }
}
