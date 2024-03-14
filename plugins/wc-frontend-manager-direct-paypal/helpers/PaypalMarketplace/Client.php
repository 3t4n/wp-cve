<?php

namespace WCFM\PaypalMarketplace;

use WP_Error;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\PayPalEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\AccessTokenRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;

/**
 * PayPal SDK Client
 */
class Client {
    protected $sandbox_mode = false;
    protected static $instance = null;
    protected $additional_request_header = [];
    protected $client_id;
    protected $client_secret;
    protected $environment;
 
    /**
     * Initialize Client() class
     *
     * @since 2.0.0
     *
     * @return Client
     */
    public static function init() {
        if ( static::$instance === null ) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function __construct() {
        if( Helper::is_sandbox_mode() ) {
            $this->sandbox_mode = true;
        }
        
        $this->environment = $this->get_paypal_environment( Helper::get_client_id(), Helper::get_client_secret() );
    }

    public function build_request_url( $endpoint ) {
        return $this->base_url . $endpoint;
    }

    public function get_access_token() {
        $access_token = get_transient( '_wcfm_paypal_marketplace_access_token' );
        if ( $access_token ) {
            return $access_token;
        }

        $access_token = $this->create_access_token();

        if ( is_wp_error( $access_token ) ) {
            return $access_token;
        }

        return $access_token;
    }

    public function create_access_token() {
        $response = $this->do_api_request( new AccessTokenRequest( $this->environment ) );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( isset( $response->access_token ) && isset( $response->expires_in ) ) {
            set_transient( '_wcfm_paypal_marketplace_access_token', $response->access_token, $response->expires_in );

            return $response->access_token;
        }
    }

    public function execute( $data ) {
        $defaults = [
            'url'                => '',
            'data'               => [],
            'method'             => 'post',
            'header'             => true,
            'content_type_json'  => true,
            'request_with_token' => true,
        ];

        $parsed_args = wp_parse_args( $data, $defaults );

        $header = $parsed_args['header'] === true ? $this->get_header( $parsed_args['content_type_json'], $parsed_args['request_with_token'] ) : [];

        if ( is_wp_error( $header ) ) {
            return $header;
        }

        $args = [
            'timeout'     => '120',
            'redirection' => '120',
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => $header,
            'cookies'     => [],
        ];

        if ( ! empty( $parsed_args['data'] ) ) {
            $args['body'] = $parsed_args['data'];
        }

        switch ( strtolower( $parsed_args['method'] ) ) {
            case 'get':
                $args['method'] = 'GET';
                break;
            case 'post':
                $args['method'] = 'POST';
                break;
            case 'delete':
                $args['method'] = 'DELETE';
                break;
            case 'patch':
                $args['method'] = 'PATCH';
                break;
            default:
                $args['method'] = 'POST';
        }

        $response = wp_remote_request( esc_url_raw( $parsed_args['url'] ), $args );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $body            = json_decode( wp_remote_retrieve_body( $response ), true );
        $paypal_debug_id = wp_remote_retrieve_header( $response, 'paypal-debug-id' );

        if (
            200 !== wp_remote_retrieve_response_code( $response ) &&
            201 !== wp_remote_retrieve_response_code( $response ) &&
            202 !== wp_remote_retrieve_response_code( $response ) &&
            204 !== wp_remote_retrieve_response_code( $response )
        ) {
            return new WP_Error( 'wcfm_paypal_request_error', $body, [ 'paypal_debug_id' => $paypal_debug_id ] );
        }

        if ( $paypal_debug_id ) {
            $body['paypal_debug_id'] = $paypal_debug_id;
        }

        return $body;
    }

    public function get_header( $content_type_json = true, $request_with_token = true ) {
        $content_type = $content_type_json ? 'json' : 'x-www-form-urlencoded';

        $headers = [
            'Content-Type' => 'application/' . $content_type,
        ];

        if ( ! $request_with_token ) {
            $client_id     = Helper::get_client_id();
            $client_secret = Helper::get_client_secret();

            $headers['Authorization'] = 'Basic ' . base64_encode( $client_id . ':' . $client_secret );
            $headers['Ignorecache']   = true;

            return $headers;
        }

        $access_token = $this->get_access_token();

        if ( is_wp_error( $access_token ) ) {
            return $access_token;
        }

        $headers['Authorization'] = 'Bearer ' . $access_token;

        //merge array if there is any additional data
        $headers = array_merge( $headers, $this->additional_request_header );

        return $headers;
    }

    /**
     * @param $request HttpRequest
     * @return mixed WP_Error|Array
     */
    public function do_api_request( $request ) {
        $client = $this->get_api_http_client();
        try {
			$response = $client->execute($request);
            if( 
                200 === $response->statusCode ||
                201 === $response->statusCode ||
                202 === $response->statusCode ||
                204 === $response->statusCode
            ) {
                $response = $response->result;
            }
		} catch ( HttpException $ex ) {
            $error = json_decode( $ex->getMessage(), true );
            $message = isset( $error['error_description'] ) ? $error['error_description'] : $error['message'];
            $response = new WP_Error( "Wcfm-" . basename(str_replace('\\', '/', get_class($request))) . "-Error", $message, $error );
            // wcfm_paypal_log( '[WCFM Paypal Marketplace] API Exception: ' . print_r( $message, true ), 'error' );
            // wcfm_paypal_log( '[WCFM Paypal Marketplace] Error Details: ' . print_r( $error, true ), 'debug' );
            // wcfm_paypal_log( '[WCFM Paypal Marketplace] Exception: ' . print_r( $ex, true ), 'debug' );
		}

        return $response;
    }

    public function generate_sign_up_link( $email, $tracking_id, $products = [ 'PPCP' ] ) {
        $access_token = $this->get_access_token();

        if ( is_wp_error( $access_token ) ) {
            return $access_token;
        }

        $request = new PartnerReferralCreateRequest();
		$request->headers["Authorization"] = 'Bearer ' . $access_token;
		$request->body = apply_filters( 'wcfm_paypal_marketplace_partner_referral_request_body', [
            'email'                   => $email,
            'preferred_language_code' => 'en-US',
            'tracking_id'             => $tracking_id,
            'partner_config_override' => [
                'return_url'        => add_query_arg(
                    [
                        'action'   => 'wcfm_paypal_marketplace_connect_success',
                        'status'   => 'success',
                        '_wpnonce' => wp_create_nonce( 'wcfm-paypal-marketplace-connect-success' ),
                    ],
					admin_url( 'admin-ajax.php' )
                ),
                'return_url_description' => 'the url to return the merchant after the paypal onboarding process.',
            ],
            'legal_consents'          => [
                [
                    'type'    => 'SHARE_DATA_CONSENT',
                    'granted' => true,
                ],
            ],
            'operations'              => [
                [
                    'operation'                  => 'API_INTEGRATION',
                    'api_integration_preference' => [
                        'rest_api_integration' => [
                            'integration_method'  => 'PAYPAL',
                            'integration_type'    => 'THIRD_PARTY',
                            'third_party_details' => [
                                'features' => [
                                    'PAYMENT',
                                    'REFUND',
                                    'DELAY_FUNDS_DISBURSEMENT',
                                    'PARTNER_FEE',
                                    'ACCESS_MERCHANT_INFORMATION'
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'products'                => $products,
        ] );

        if( !empty( Helper::get_settings( 'marketplace_logo' ) ) ) {
            $marketplace_logo = Helper::get_settings( 'marketplace_logo' );
            
            // Minimum length: 1
            // Maximum length: 127
            if( strlen( $marketplace_logo ) >= 1 && strlen( $marketplace_logo ) <= 127 ) {
                $request->body['partner_config_override']['partner_logo_url'] = $marketplace_logo;
            }
        }

        $response = $this->do_api_request( $request );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        if ( isset( $response->links[1] ) && 'action_url' === $response->links[1]->rel ) {
            return $response;
        }

        return new WP_Error( 'wcfm_paypal_create_partner_referral_error', $response );
    }

    /**
     * @param array
     * 
     * @return mixed WP_Error|Array
     */
    public function create_paypal_order( $payload ) {

        $access_token = $this->get_access_token();

        if ( is_wp_error( $access_token ) ) {
            return $access_token;
        }
        
        $request = new OrdersCreateRequest();
        
        $request->headers["Authorization"] = 'Bearer ' . $access_token;
        $request->payPalPartnerAttributionId(Helper::get_settings('bn_code'));
        $request->prefer('return=representation');
        $request->body = $payload;

        $response = $this->do_api_request( $request );

        return $response;
    }

    public static function get_shipping_address( \WC_Order $order, $payer = false ) {
        $address = [
            'address' => [
                'name'           => [
                    'given_name' => $order->get_billing_first_name(),
                    'surname'    => $order->get_billing_last_name(),
                ],
                'address_line_1' => $order->get_billing_address_1(),
                'address_line_2' => $order->get_billing_address_2(),
                'admin_area_2'   => $order->get_billing_city(),
                'admin_area_1'   => $order->get_billing_state(),
                'postal_code'    => $order->get_billing_postcode(),
                'country_code'   => $order->get_billing_country(),
            ],
        ];

        if ( $payer ) {
            $address['name'] = [
                'given_name' => $order->get_billing_first_name(),
                'surname'    => $order->get_billing_last_name(),
            ];
        }

        return $address;
    }

    public function get_paypal_environment( $client_id, $client_secret ) {
        if( isset( $this->environment ) && $this->environment instanceof PayPalEnvironment ) {
            return $this->environment;
        }

        // Creating an environment
        return $this->sandbox_mode ? new SandboxEnvironment( $client_id, $client_secret ) : new ProductionEnvironment( $client_id, $client_secret );
    }

    /**
     * Get Api http client
     * 
     * @param string $client_id
     * @param string $client_secret
     * @return PayPalHttpClient
     */
    public function get_api_http_client( $client_id = '', $client_secret = '' ) {
        // Creating an environment
		$client_id = $client_id ? $client_id : Helper::get_client_id();
		$client_secret = $client_secret ? $client_secret : Helper::get_client_secret();

		$environment = Helper::is_sandbox_mode() ? new SandboxEnvironment( $client_id, $client_secret ) : new ProductionEnvironment( $client_id, $client_secret );

        return new PayPalHttpClient($environment);
    }

    public function capture_payment( $id ) {
        $request = new OrdersCaptureRequest($id);
        $request->prefer('return=representation');
        $request->payPalRequestId($id);
        
        $response = $this->do_api_request($request);

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        // check charged captured successfully
        if (
            isset( $response->intent, $response->status ) &&
            'CAPTURE' === $response->intent &&
            'COMPLETED' === $response->status
        ) {
            return $response;
        }
    }

    public function get_merchant_info( $merchant_id ) {
        $access_token = $this->get_access_token();

        if ( is_wp_error( $access_token ) ) {
            return $access_token;
        }

        $request = new PartnerReferralMerchantInfoRequest( Helper::get_partner_id(), $merchant_id );
        $request->headers["Authorization"] = 'Bearer ' . $access_token;

        return $this->do_api_request( $request );
    }

    public function create_paypal_auth_assertion( $vendor_id ) {
        $header     = [
            'alg' => 'none'
        ];

        $payload    = [
            'iss'       => Helper::get_client_id(),
            'payer_id'  => Helper::get_paypal_merchant_id( $vendor_id )
        ];

        return base64_encode( wp_json_encode( $header ) ) . '.' . base64_encode( wp_json_encode( $payload ) ) . '.';
    }
}
