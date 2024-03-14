<?php

namespace WCFM\PaypalMarketplace;

use PayPal\Api\Webhook;
use PayPal\Api\WebhookList;
use PayPal\Api\WebhookEventType;
use PayPal\Api\VerifyWebhookSignature;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use WCFM\PaypalMarketplace\Helper;
use PayPal\Exception\PayPalConnectionException;

if (! defined('ABSPATH') ) {
    exit;
}

class WebhookHandler {
    protected $apiWebhook;
    protected $apiContext;
    protected $webhookUrl;

    public function __construct() {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                Helper::get_client_id(),
                Helper::get_client_secret()
            )
        );

        $this->apiContext->setConfig(
            array(
                'mode' => Helper::is_sandbox_mode() ? 'sandbox' : 'live',
                'log.LogEnabled' => true,
                'log.FileName' => '../PayPal.log',
                'log.LogLevel' => Helper::is_sandbox_mode() ? 'DEBUG' : 'INFO',
                'cache.enabled' => true,
            )
        );

        $this->apiWebhook = new Webhook();
        $this->webhookUrl = Helper::get_webhook_url();

        add_action( 'woocommerce_api_wcfm-paypal-webhook', [ $this, 'handle_events' ] );
    }

    /**
     * Register paypal webhooks
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function register_webhooks() {
        $events = Helper::get_supported_webhook_events();
        $supportedEvents = array_keys( $events );
        $webhooks = [];
        $response = '';

        // get all registered webhooks
        try {
            $response = $this->apiWebhook::getAllWithParams( [], $this->apiContext );
            $webhooks = $response->getWebhooks();
        } catch ( PayPalConnectionException $e ) {
            wcfm_paypal_log( '[WCFM Paypal Marketplace] Unable to list Webhooks: ' . print_r( $e->getMessage(), true ), 'error' );
            wcfm_paypal_log( '[WCFM Paypal Marketplace] Error details: ' . print_r( $e->getData(), true ), 'debug' );
        }

        if( ! $response instanceof WebhookList ) {
            return false;
        }

        // already registered webhook id
        $webhook_id = get_option( Helper::get_webhook_id_key() );

        if( $webhooks ) {
            foreach ( $webhooks as $webhook ) {
                if(
                    ! empty( $webhook_id ) &&
                    $webhook->getId() == $webhook_id &&
                    false !== strpos( $webhook->getUrl(), $this->webhookUrl )
                ) {
                    // check if all events are registered
                    $eventNames = array_map( function( $e ) {
                        return $e->getName();
                    }, $webhook->getEventTypes() );

                    if( !empty( array_diff( $supportedEvents, $eventNames ) ) ) {
                        // all event not registered
                        $webhook->delete( $this->apiContext );
                    } else {
                        return true;
                    }
                } else if ( false !== strpos( $webhook->getUrl(), $this->webhookUrl ) ) {
                    // other webhook registerd for this site
                    $webhook->delete( $this->apiContext );
                }
            }
        }

        delete_option( Helper::get_webhook_id_key() );

        // register necessary webhooks
        $eventTypes = [];

        foreach ( $supportedEvents as $event ) {
            $webhookEventType = new WebhookEventType();
            $webhookEventType->setName( $event );
            $eventTypes[] = $webhookEventType;
        }

        $this->apiWebhook->setEventTypes( $eventTypes );
        $this->apiWebhook->setUrl( $this->webhookUrl );

        try {
            $response = $this->apiWebhook->create( $this->apiContext );
            update_option( Helper::get_webhook_id_key(), $response->getId() );
            wcfm_paypal_log( '[WCFM Paypal Marketplace] Webhook Registered sucessfully', 'info' );
        } catch( PayPalConnectionException $e ) {
            wcfm_paypal_log( '[WCFM Paypal Marketplace] Unable to create Webhook: ' . print_r( $e->getMessage(), true ), 'error' );
            wcfm_paypal_log( '[WCFM Paypal Marketplace] Error details: ' . print_r( json_decode( $e->getData(), true ), true ), 'debug' );
        }
    }

    /**
     * De-register paypal webhooks
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function deregister_webhooks() {
        $response = '';
        $webhooks = [];

        // get all registered webhooks
        try {
            $response = $this->apiWebhook::getAllWithParams( [], $this->apiContext );
            $webhooks = $response->getWebhooks();
        } catch ( PayPalConnectionException $e ) {
            wcfm_paypal_log( '[WCFM Paypal Marketplace] Unable to list Webhooks: ' . print_r( $e->getMessage(), true ), 'error' );
            wcfm_paypal_log( '[WCFM Paypal Marketplace] Error details: ' . print_r( $e->getData(), true ), 'debug' );
        }

        if( ! $response instanceof WebhookList ) {
            return false;
        }

        if( $webhooks ) {
            foreach ( $webhooks as $webhook ) {
                $registeredUrl = $webhook->getUrl();

                if( false !== strpos( $registeredUrl, $this->webhookUrl ) ) {
                    try {
                        $response = $webhook->delete($this->apiContext);
                        delete_option( Helper::get_webhook_id_key() );
                        wcfm_paypal_log( '[WCFM Paypal Marketplace] Webhook Deregistered sucessfully', 'info' );
                    } catch( PayPalConnectionException $e ) {
                        wcfm_paypal_log( '[WCFM Paypal Marketplace] Unable to delete Webhook: ' . print_r( $e->getMessage(), true ), 'error' );
                        wcfm_paypal_log( '[WCFM Paypal Marketplace] Error details: ' . print_r( json_decode( $e->getData(), true ), true ), 'debug' );
                    }
                }
            }
        }
    }

    /**
     * Handle events which are coming from PayPal
     *
     * @since 2.0.0
     *
     * @return void
     */
    public function handle_events() {
        //if the gateway is disabled then we are not processing further execution
        if ( ! Helper::is_ready() ) {
            status_header( 200 );
            exit();
        }

        $requestBody    = file_get_contents( 'php://input' );
        $event          = json_decode( $requestBody );

        if ( ! $event ) {
            status_header( 400 );
            exit();
        }

        try {
            // get request header
            $headers = array_change_key_case( getallheaders(), CASE_UPPER );

            $signatureVerification = new VerifyWebhookSignature();
            $signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
            $signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
            $signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);
            $signatureVerification->setWebhookId(get_option( Helper::get_webhook_id_key() ));
            $signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
            $signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);
            $signatureVerification->setRequestBody($requestBody);

            $response = $signatureVerification->post($this->apiContext);

            if( 'SUCCESS' == $response->getVerificationStatus() ) {
                $eventHandler = static::handle_webhook( $event );
                $eventHandler->handle();
            } else {
                wcfm_paypal_log( '[WCFM Paypal Marketplace] Webhook vetification failed', 'error' );
            }
        } catch( \Exception $ex ) {
            wcfm_paypal_log( '[WCFM Paypal Marketplace] WebhookHandler Exception: ' . print_r( $ex->getMessage(), true ), 'error' );
            wcfm_paypal_log( '[WCFM Paypal Marketplace] WebhookHandler Error Details: ' . print_r( $ex, true ), 'debug' );
        }
    }

    /**
     * load webhook handler class
     *
     * @since 2.0.0
     *
     * @return object
     */
    public static function handle_webhook( $event ) {
        $events = Helper::get_supported_webhook_events();
        $class  = null;

        if ( ! array_key_exists( $event->event_type, $events ) ) {
            return;
        }

        $class = $events[ $event->event_type ];
        $class = "\\WCFM\\PaypalMarketplace\\WebhookEvents\\{$class}";

        if ( ! class_exists( $class ) ) {
            throw new \Exception( sprintf( 'This %s is not supported yet', $class ) );
        }

        return new $class( $event );
    }
}
