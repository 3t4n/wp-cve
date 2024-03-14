<?php

class MM_WPFS_PhonyTransactionData {
    protected $customerEmail;

    public function __construct( $emailAddress ) {
        $this->customerEmail = $emailAddress;
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail() {
        return $this->customerEmail;
    }
}

class MM_WPFS_PrivateAPI_v1 {
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    protected static $instance;

    /** @var MM_WPFS_Options */
    protected $options;

    protected function __construct() {
        $this->options          = new MM_WPFS_Options();
        $this->loggerService    = new MM_WPFS_LoggerService( $this->options->get( MM_WPFS_Options::OPTION_LOG_LEVEL ), $this->options->get( MM_WPFS_Options::OPTION_LOG_TO_WEB_SERVER ) == 1 );
        $this->initLogger( $this->loggerService, MM_WPFS_LoggerService::MODULE_API );

        $this->initStaticContext();
    }

    /**
     * @return MM_WPFS_PrivateAPI_v1
     */
    protected static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new MM_WPFS_PrivateAPI_v1();
        }

        return self::$instance;
    }

    /**
     * @param $message
     * @return void
     */
    static public function log( $message ) {
        MM_WPFS_Utils::log( $message );
    }

    /**
     * @param $exception
     * @return void
     */
    static public function logException($exception, $object = null ) {
        MM_WPFS_Utils::logException( $exception, $object );
    }

    /**
     * @param $slug
     *
     * @return string
     */
    static public function getAdminUrlBySlug( $slug ) : string {
        return MM_WPFS_Admin_Menu::getAdminUrlBySlug( $slug );
    }

    /**
     * @param $slug
     * @param $params
     *
     * @return string
     */
    static public function getAdminUrlBySlugAndParams( $slug, $params = array() ) : string {
        return MM_WPFS_Admin_Menu::getAdminUrlBySlugAndParams( $slug, $params );
    }

    /**
     * @param $currency
     * @param $amount
     * @param $interval
     * @param $intervalCount
     *
     * @return string
     * @throws Exception
     */
    static public function formatPriceWithAdminSettings( $currency, $amount, $interval, $intervalCount ) {
        $formattedAmount = MM_WPFS_Currencies::formatAndEscapeByAdmin(
            self::getInstance()->staticContext,
            $currency,
            $amount,
            true,
            true );

        return MM_WPFS_Localization::getPriceAndIntervalLabel( $interval, $intervalCount, $formattedAmount );
    }

    /**
     * @return string
     */
    public static function getWordpressTimezone() {
        return MM_WPFS_Utils::getWordpressTimezone();
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    public static function formatTimestampWithWordpressDateFormat( $timestamp ) {
        return MM_WPFS_Utils::formatTimestampWithWordpressDateFormat( $timestamp );
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    public static function formatTimestampWithWordpressDateTimeFormat( $timestamp ) {
        return MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat( $timestamp );
    }

    /**
     * @param $liveMode int
     *
     * @return string
     */
    public static function getApiModeLabelFromInteger( $liveMode ) : string {
        return MM_WPFS_Admin::getApiModeLabelFromInteger( $liveMode );
    }

    /**
     * @param $assetName
     *
     * @return string
     */
    public static function getScriptAssetUrl( $assetName ) {
        return MM_WPFS_Assets::scripts( $assetName );
    }

    /**
     * @param $bindingResult
     * @return array
     */
    public static function generateReturnValueFromBindings( $bindingResult ) {
        return MM_WPFS_Utils::generateReturnValueFromBindings( $bindingResult );
    }

    /**
     * @param $elementId
     * @param $formHash
     * @param $index
     * @return string|void|null
     */
    public static function generateFormElementId( $elementId, $formHash, $index = null ) {
        return MM_WPFS_Utils::generateFormElementId( $elementId, $formHash, $index );
    }
}

class MM_WPFS_PrivateAPI_v2 extends MM_WPFS_PrivateAPI_v1 {
    protected function __construct () {
        parent::__construct();
    }

    /**
     * @return MM_WPFS_PrivateAPI_v2
     */
    protected static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new MM_WPFS_PrivateAPI_v2();
        }

        return self::$instance;
    }

    /**
     * @param $module
     * @param $class
     * @return MM_WPFS_Logger
     */
    protected static function createLogger( $module, $class ) : MM_WPFS_Logger {
        return self::getInstance()->loggerService->createLogger( $module, $class );
    }

    public static function logError( $module, $class, $function, $message, $exception = null ) {
        self::createLogger( $module, $class )->error( $function, $message, $exception );
    }

    public static function logWarning( $module, $class, $function, $message ) {
        self::createLogger( $module, $class )->warning( $function, $message );
    }

    public static function logInfo( $module, $class, $function, $message ) {
        self::createLogger( $module, $class )->info( $function, $message );
    }

    public static function logDebug( $module, $class, $function, $message ) {
        self::createLogger( $module, $class )->debug( $function, $message );
    }
}


class MM_WPFS_API_v1 {
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    protected static $instance;

    /** @var MM_WPFS_Database */
    private $db = null;
    /** @var MM_WPFS_Stripe */
    protected $stripe;
    /** @var MM_WPFS_Options */
    protected $options;

    const STRIPE_API_MODE_CURRENT = 'current';
    const STRIPE_API_MODE_LIVE = 'live';
    const STRIPE_API_MODE_TEST = 'test';

    protected function __construct() {
        $this->options          = new MM_WPFS_Options();
        $this->loggerService    = new MM_WPFS_LoggerService( $this->options->get( MM_WPFS_Options::OPTION_LOG_LEVEL ), $this->options->get( MM_WPFS_Options::OPTION_LOG_TO_WEB_SERVER ) == 1 );
        $this->initLogger( $this->loggerService, MM_WPFS_LoggerService::MODULE_API );

        $this->initStaticContext();

        $this->db               = new MM_WPFS_Database();
        $this->stripe           = new MM_WPFS_Stripe( MM_WPFS_Stripe::getStripeAuthenticationToken($this->staticContext), $this->loggerService );

    }

    /**
     * @return MM_WPFS_API_v1
     */
    protected static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new MM_WPFS_API_v1();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public static function getPluginVersion() {
        return MM_WPFS::VERSION;
    }

    /**
     * @param $apiMode
     * @return \StripeWPFS\StripeClient
     * @throws Exception
     */
    public static function getStripeClient( $apiMode = self::STRIPE_API_MODE_CURRENT ) {
        switch( $apiMode ) {
            case self::STRIPE_API_MODE_CURRENT:
                return self::getInstance()->stripe->getStripeClient();

            case self::STRIPE_API_MODE_LIVE:
            case self::STRIPE_API_MODE_TEST:
                return MM_WPFS_Stripe::createStripeClient(MM_WPFS_Stripe::getStripeAuthenticationTokenByMode(self::getInstance()->staticContext, $apiMode === self::STRIPE_API_MODE_LIVE));

            default:
                throw new Exception( __CLASS__ . "." . __FUNCTION__ . "() - Invalid API mode: " . $apiMode );
        }
    }

    /**
     * @return array
     */
    public static function getStripeSubscriptionPlans() {
        return self::getInstance()->stripe->getSubscriptionPlans();
    }

    /**
     * @param $subscriptionId string
     * @return \StripeWPFS\Subscription
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public static function getStripeSubscription($subscriptionId ) {
        return self::getInstance()->stripe->retrieveSubscription($subscriptionId );
    }

    /**
     * @param $subscriptionId
     * @param $params
     * @return \StripeWPFS\Subscription
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public static function getStripeSubscriptionWithParams( $subscriptionId, $params ) {
        return self::getInstance()->stripe->retrieveSubscriptionWithParams( $subscriptionId, $params );
    }

    /**
     * @param $customerId
     * @return \StripeWPFS\Customer
     */
    public static function getStripeCustomer( $customerId ) {
        return self::getInstance()->stripe->retrieveCustomer( $customerId );
    }

    /**
     * @param $customerId
     * @param $params
     * @return \StripeWPFS\Customer
     */
    public static function getStripeCustomerWithParams( $customerId, $params ) {
        return self::getInstance()->stripe->retrieveCustomerWithParams( $customerId, $params );
    }

    /**
     * @param $params
     * @return \StripeWPFS\Collection
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public static function getStripeCustomersWithParams( $params )  {
        return self::getInstance()->stripe->getCustomersWithParams( $params );
    }

    /**
     * @param $planId
     * @return \StripeWPFS\Price|null
     */
    public static function getStripePlan( $planId ) {
        return self::getInstance()->stripe->retrievePlan( $planId );
    }

    /**
     * @param $stripeCustomerId
     * @param $stripeSubscriptionId
     * @param $stripePlanId
     *
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public static function changeSubscriptionPlan( $stripeCustomerId, $stripeSubscriptionId, $stripePlanId ) {
        $success = self::getInstance()->stripe->updateSubscriptionPlanAndQuantity( $stripeCustomerId, $stripeSubscriptionId, $stripePlanId );
        if ( $success ) {
            self::getInstance()->db->updateSubscriptionPlanByStripeSubscriptionId( $stripeSubscriptionId, $stripePlanId );
        }
    }

    /**
     * @param $stripeCustomerId
     * @param $stripeSubscriptionId
     * @return bool
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public static function cancelSubscription( $stripeCustomerId, $stripeSubscriptionId ) {
        return self::getInstance()->stripe->cancelSubscription(
            $stripeCustomerId,
            $stripeSubscriptionId,
            MM_WPFS_Utils::getCancelSubscriptionsAtPeriodEnd( self::getInstance()->staticContext )
        );
    }

    /**
     * @param $currency string
     * @return string|null
     */
    public static function getCurrencySymbolFor( $currency ) {
        return MM_WPFS_Currencies::getCurrencySymbolFor( $currency );
    }

    /**
     * @param $emailType string
     * @param $decoratedKeyValuePairs array
     */
    public static function sendEmailByTemplate( $recipientEmail, $templateType, $decoratedKeyValuePairs ) {
        if ( MM_WPFS_Utils::isDemoMode() ) {
            return;
        }

        $sender = new MM_WPFS_GenericEmailNotificationSender( new MM_WPFS_PhonyTransactionData( $recipientEmail ), self::getInstance()->staticContext->getLoggerService() );
        $sender->setTemplateType( $templateType );
        $sender->setDecoratedKeyValuePairs( $decoratedKeyValuePairs );
        $sender->sendEmail();
    }

    /**
     * @return bool
     */
    public static function isDemoMode() {
        return MM_WPFS_Utils::isDemoMode();
    }
}

class WPFS_API_v2 extends MM_WPFS_API_v1 {
    protected function __construct () {
        parent::__construct();
    }

    /**
     * @return MM_WPFS_API_v1
     */
    protected static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new WPFS_API_v2();
        }

        return self::$instance;
    }

    /**
     * @param $config
     * @param $fieldName
     * @param $isConfigurable
     * @return array
     */
    public static function setIsFormFieldConfigurable( & $config, $fieldName, $isConfigurable ) {
        if ( is_null( $config ) ||
            ! is_array( $config ) ||
            ! array_key_exists( $fieldName, $config ) ||
            ! is_bool( $isConfigurable )) {
            return $config;
        }

        $fieldConfig = $config[ $fieldName ];
        /** @var $fieldConfig MM_WPFS_FormFieldConfiguration  */
        $fieldConfig->setIsConfigurable( $isConfigurable );

        return $config;
    }

    public static function isFormFieldConfigurable( & $config, $fieldName ) {
        if ( is_null( $config ) ||
            ! is_array( $config ) ||
            ! array_key_exists( $fieldName, $config )) {
            return false;
        }

        $fieldConfig = $config[ $fieldName ];
        /** @var $fieldConfig MM_WPFS_FormFieldConfiguration  */

        return $fieldConfig->isConfigurable();
    }

    public static function setFormFieldValue( & $config, $fieldName, $value ) {
        if ( is_null( $config ) ||
            ! is_array( $config ) ||
            ! array_key_exists( $fieldName, $config )) {
            return $config;
        }

        $fieldConfig = $config[ $fieldName ];
        /** @var $fieldConfig MM_WPFS_FormFieldConfiguration  */
        $fieldConfig->setValue( $value );

        return $config;
    }

    public static function getFormFieldValue( & $config, $fieldName ) {
        if ( is_null( $config ) ||
            ! is_array( $config ) ||
            ! array_key_exists( $fieldName, $config )) {
            return false;
        }

        $fieldConfig = $config[ $fieldName ];
        /** @var $fieldConfig MM_WPFS_FormFieldConfiguration  */

        return $fieldConfig->getValue();
    }

    public static function setIsFormFieldReadonly( & $config, $fieldName, $isReadonly ) {
        if ( is_null( $config ) ||
            ! is_array( $config ) ||
            ! array_key_exists( $fieldName, $config ) ||
            ! is_bool( $isReadonly )) {
            return $config;
        }

        $fieldConfig = $config[ $fieldName ];
        /** @var $fieldConfig MM_WPFS_FormFieldConfiguration  */
        $fieldConfig->setIsReadonly( $isReadonly );

        return $config;
    }

    public static function isFormFieldReadonly( & $config, $fieldName ) {
        if ( is_null( $config ) ||
            ! is_array( $config ) ||
            ! array_key_exists( $fieldName, $config )) {
            return false;
        }

        $fieldConfig = $config[ $fieldName ];
        /** @var $fieldConfig MM_WPFS_FormFieldConfiguration  */

        return $fieldConfig->isReadonly();
    }

    /**
     * @param $stripeSubscriptionId
     * @return bool
     * @throws \StripeWPFS\Exception\ApiErrorException
     */
    public static function cancelSubscriptionById( $stripeSubscriptionId ) {
        return self::getInstance()->stripe->cancelSubscription(
            null,
            $stripeSubscriptionId,
            MM_WPFS_Utils::getCancelSubscriptionsAtPeriodEnd( self::getInstance()->staticContext )
        );
    }

}

