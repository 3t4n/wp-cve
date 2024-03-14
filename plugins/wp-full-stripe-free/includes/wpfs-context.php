<?php

class MM_WPFS_StaticContext {
    /** @var MM_WPFS_LoggerService */
    protected $loggerService;
    /** @var MM_WPFS_Options */
    protected $options;

    public function __construct( $loggerService, $options ) {
        $this->loggerService = $loggerService;
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getLoggerService() {
        return $this->loggerService;
    }

    /**
     * @return mixed
     */
    public function getOptions() {
        return $this->options;
    }
}

trait MM_WPFS_StaticContext_AddOn {
    /** @var MM_WPFS_StaticContext */
    protected $staticContext = null;

    /** @noinspection PhpUndefinedFieldInspection */
    protected function initStaticContext() {
        $this->staticContext = new MM_WPFS_StaticContext( $this->loggerService, $this->options );
    }
}

trait MM_WPFS_FindStripeCustomer_AddOn {
    /** @var MM_WPFS_Database */
    protected $db;
    /** @var MM_WPFS_Stripe */
    protected $stripe;
    /** @var MM_WPFS_Options */
    protected $options;
    /** @var MM_WPFS_Logger */
    protected $logger;

    /** @noinspection PhpUndefinedFieldInspection */
    protected function findExistingStripeCustomerAnywhereByEmail( $stripeCustomerEmail ) {
        $liveMode = $this->options->get( MM_WPFS_Options::OPTION_API_MODE ) === MM_WPFS::STRIPE_API_MODE_LIVE;
        $customers = $this->db->getExistingStripeCustomersByEmail( $stripeCustomerEmail, $liveMode );

        $result = null;
        foreach ( $customers as $customer ) {
            $stripeCustomer = null;
            try {
                $stripeCustomer = $this->stripe->retrieveCustomer( $customer['stripeCustomerID'] );
            } catch ( Exception $ex ) {
                $this->logger->error( __FUNCTION__, "Stripe customer doesn't exist", $ex );
            }

            if ( isset( $stripeCustomer ) && ( ! isset( $stripeCustomer->deleted ) || ! $stripeCustomer->deleted ) ) {
                $result = $stripeCustomer;
                break;
            }
        }

        if ( is_null( $result ) ) {
            $stripeCustomers = $this->stripe->getCustomersByEmail( $stripeCustomerEmail );

            foreach ( $stripeCustomers as $stripeCustomer ) {
                if ( isset( $stripeCustomer ) && ( ! isset( $stripeCustomer->deleted ) || ! $stripeCustomer->deleted ) ) {
                    $result = $stripeCustomer;
                    break;
                }
            }
        }

        return $result;
    }
}
