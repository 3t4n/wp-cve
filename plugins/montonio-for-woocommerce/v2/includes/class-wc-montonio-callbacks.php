<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class for handling callbacks
 */
class WC_Montonio_Callbacks extends WC_Payment_Gateway {
   
    /**
	 * API access key
	 *
	 * @var string
	 */
    public $access_key;

    /**
	 * API secret key
	 *
	 * @var string
	 */
    public $secret_key;

    /**
     * Is test mode active?
     *
     * @var bool
     */
    public $sandbox_mode;

    /**
     * Check if the response is a webhook notification
     *
     * @var bool
     */
    public $is_notification;

	public function __construct( $sandbox_mode, $is_notification ) {
		$this->sandbox_mode = $sandbox_mode;
		$this->is_notification = $is_notification;

        $api_keys = WC_Montonio_Helper::get_api_keys( $this->sandbox_mode );

        $this->access_key = $api_keys[ 'access_key' ];
        $this->secret_key = $api_keys[ 'secret_key' ];

        $this->check_order_response();
	}

    /**
     * Check response from Montonio
     * 
     * @return void
     */
    public function check_order_response() {
        // Define default return url
        $return_url = wc_get_checkout_url();
        
        // Get Payment Token
        if ( ! isset( $_REQUEST['order-token'] ) ) {
            if ( isset( $_REQUEST['error-message'] ) ) {
                wc_add_notice( rawurldecode( $_REQUEST['error-message'] ), 'error' );
                WC_Montonio_Logger::log( 'Unable to finish the payment. ' . $_REQUEST['error-message'] );
            } else {
                wc_add_notice( __( 'Unable to finish the payment. Please try again or choose a different payment method.', 'montonio-for-woocommerce' ), 'notice' );
                WC_Montonio_Logger::log( 'Unable to finish the payment. "order-token" parameter is not set in order response.' );
            }

            wp_redirect( $return_url );
            
            exit;
        }

        $token = sanitize_text_field( $_REQUEST['order-token'] );

        if ( $this->is_notification ) {
            sleep( 10 );
            WC_Montonio_Logger::log( 'WEBHOOK | Processing response: ' . $token );
        } else {
            WC_Montonio_Logger::log( 'Processing response: ' . $token );
        }

        try {
            $response = WC_Montonio_API::decode_payment_token( $token, $this->secret_key ); 
            $response = apply_filters( 'wc_montonio_decoded_payment_token', $response );
        } catch ( Throwable $exception ) {
            wc_add_notice( __( 'There was a problem with processing the order.', 'montonio-for-woocommerce' ), 'error' );
            WC_Montonio_Logger::log( 'Unable to decode payment token: ' . $exception->getMessage() );

            if ( $this->is_notification ) {
                http_response_code( 401 );
            } else {
                wp_redirect( $return_url );
            }

            exit;
        }

        $payment_status = sanitize_text_field( $response->paymentStatus );
        $uuid = sanitize_text_field( $response->uuid );
        $order_id = sanitize_text_field( $response->merchantReference );
        $grand_total = sanitize_text_field( $response->grandTotal );
        $currency = sanitize_text_field( $response->currency );
        $payment_method = sanitize_text_field( $response->paymentMethod );

        switch ( $payment_method ) {
            case 'paymentInitiation':
                $payment_provider_name = sanitize_text_field( $response->paymentProviderName );
                break;
            case 'cardPayments':
                $payment_provider_name = 'Card payment';
                break;
            case 'bnpl':
                $payment_provider_name = 'Pay in parts';
                break;
            case 'hirePurchase':
                $payment_provider_name = 'Financing';
                break;
            case 'blik':
                $payment_provider_name = 'BLIK';
                break;
            default:
                $payment_provider_name = 'N/A';
                break;    
        }

        $order = wc_get_order( $order_id );

        if ( empty( $order ) ) {
            http_response_code( 404 );
            die();
        }

        if ( $order->has_status( [ 'processing', 'completed' ] ) ) {
            $return_url = $this->get_return_url( $order );
        } else {
            switch ( $payment_status ) {
                case 'PAID':
                    $order->payment_complete();
                    $order->add_order_note( __( 'Payment via Montonio, order ID: ', 'montonio-for-woocommerce' ) . $uuid  );                    
                    $order->add_order_note( 
                        __( 'Payment method: ', 'montonio-for-woocommerce' ) . $payment_provider_name . '<br>' .
                        __( 'Paid amount: ', 'montonio-for-woocommerce' ) . $grand_total . $currency
                    );

                    WC()->cart->empty_cart();            
                    $return_url = $this->get_return_url( $order );
                    break;
                case 'AUTHORIZED':
                    $order->add_order_note( __( 'Montonio: Payment is authorized but not yet processed by the bank, order ID: ', 'montonio-for-woocommerce' ) . $uuid );
                    $order->update_status( apply_filters( 'wc_montonio_authorized_order_status', 'on-hold' ) );

                    WC()->cart->empty_cart();            
                    $return_url = $this->get_return_url( $order );
                    break;
                case 'VOIDED':
                    if ( strpos( $order->get_payment_method(), 'wc_montonio_' ) !== false ) {
                        $order->add_order_note( __( 'Montonio: Payment was rejected by the bank, order ID: ', 'montonio-for-woocommerce' ) . $uuid );
                        $order->update_status( apply_filters( 'wc_montonio_voided_order_status', 'cancelled' ) );
                    }
                    break;
                case 'ABANDONED':
                    if ( strpos( $order->get_payment_method(), 'wc_montonio_' ) !== false && $order->has_status( 'pending' ) ) {
                        $order->add_order_note( __( 'Montonio payment session abandoned, order ID: ', 'montonio-for-woocommerce' ) . $uuid );
                        $order->update_status( apply_filters( 'wc_montonio_abandoned_order_status', 'cancelled' ) );
                    }
                    break;
                default:
                    wc_add_notice( __( 'Unable to finish the payment. Please try again or choose a different payment method.', 'montonio-for-woocommerce' ), 'notice' );
                    WC_Montonio_Logger::log( 'Unable to finish the payment. Payment status: ' . $payment_status );
                    break;
            }
        }
               
        if ( $this->is_notification ) {
            http_response_code( 200 );
        } else {
            wp_redirect( $return_url );
        }

        exit;
    }
}