<?php

if ( !defined( 'ABSPATH' ) ) {

    exit;
}

Class WP_Gateway_Walletdoc extends WC_Walletdoc_Payment_Gateway {

    public $testmode;
    public $client_id;
    public $client_secret;

    public function __construct() {
        $this->id = 'walletdoc';
        $this->icon = apply_filters( 'woocommerce_walletdoc_icon', plugins_url( '/assets/icon.png', __FILE__ ) );
        $this->has_fields = false;
        $this->method_title = 'Walletdoc';
        $this->method_description = 'Online Payment Gateway';

        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option( 'title' );
        $this->description = $this->get_option( 'description' );
        $this->testmode = $this->get_option( 'testmode' );
        $this->savedCards = $this->get_option( 'saved_cards' );
        $this->reference_setting = $this->get_option( 'reference_setting' );
        $this->capture = $this->get_option( 'capture' );
        $this->api_details = $this->get_option( 'api_details' );

        $this->client_secret = ( $this->testmode == 'yes' ) ? $this->get_option( 'client_secret' ) : $this->get_option( 'production_secret' );

        $this->client_id = '';

        global $walletdoc_params;
        $walletdoc_params = array(
            'key'  =>$this->client_secret,
            'checkout'=>0,
            'transaction_id'=>'',

        );
        $this->supports = array(
            'refunds',
            'subscriptions',
            'subscription_cancellation',
            'subscription_suspension',
            'subscription_reactivation',
            'subscription_amount_changes',
            'subscription_date_changes',
            'subscription_payment_method_change',
            'subscription_payment_method_change_customer',
            'subscription_payment_method_change_admin',
            'add_payment_method',
            'multiple_subscriptions',

        );

        wp_register_script( 'walletdoc', 'https://js.walletdoc.com/v1/walletdoc.js', '', '', true );
        wp_enqueue_script( 'walletdoc' );

        wp_enqueue_script( 'woocommerce_walletdoc', plugins_url( 'assets/js/front-setting.js', __FILE__ ), array(), '1', true );
        // add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
        wp_enqueue_script( 'woocommerce_walletdoc' );

    

        wp_localize_script( 'woocommerce_walletdoc', 'wc_walletdoc_params', $walletdoc_params );

        wp_register_style( 'walletdocCss',  plugins_url( 'assets/css/walletdoc.css', __FILE__ ) );
        wp_enqueue_style( 'walletdocCss' );
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        //capture_payment
        add_action( 'woocommerce_order_status_processing', array( $this, 'capture_payment' ) );
        add_action( 'woocommerce_order_status_completed', array( $this, 'capture_payment' ) );
        // add wehbhook
        add_action( 'woocommerce_api_' . $this->id, array( $this, 'webhook' ) );
        // do_action( 'woocommerce_set_cart_cookies', true );
        // display the credit card used for a subscription in the 'My Subscriptions' table
        add_filter( 'woocommerce_my_subscriptions_payment_method', array( $this, 'maybe_render_subscription_payment_method' ), 10, 2 );

        add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );

    }

    public function init_form_fields() {
        include_once 'lib/Walletdoc.php';

        $this->form_fields = include( 'walletdoc-settings.php' );

    }
    // public function payment_scripts()
    // {

    // }

    public function payment_fields() {

        // ||  wc_get_page_id( 'checkout' ) == get_option( 'woocommerce_checkout_page_id' )
        if ( isset( $_GET[ 'change_payment_method' ] ) ) {

            $tokens = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), 'walletdoc' );

            $html = '<ul class="woocommerce-SavedPaymentMethods wc-saved-payment-methods" data-count="' . esc_attr( count( $this->get_tokens() ) ) . '">';

            foreach ( $tokens as $token ) {

                $html .= $this->get_saved_payment_method_option_html( $token );
            }

            $html .= $this->get_new_payment_method_option_html();
            $html .= '</ul>';

            echo apply_filters( 'wc_payment_gateway_form_saved_payment_methods_html', $html, $this );

        }
        if ( $this->description ) {
            echo wpautop( wp_kses_post( apply_filters( 'wc_paylike_description', $this->description ) ) );
        }

        $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );
        $publicData =   $api->getPublicKey();

        if ( !isset( $publicData->id ) ) {
            if ( wc_get_page_id( 'checkout' ) != get_option( 'woocommerce_checkout_page_id' ) ) {
                wc_add_notice( __( 'Something went wrong.', 'woocommerce-gateway-walletdoc' ), 'error' );
            }
            return;
        }

        $walletdoc_params = array(
            'key'  =>$this->client_secret,
            'publicKey'=>$publicData->id,

        );
        if ( wc_get_page_id( 'checkout' ) == get_option( 'woocommerce_checkout_page_id' ) ) {
            $dataArr = array(
                'checkout'  => true,

            );
            $walletdoc_params = array_merge( $walletdoc_params, $dataArr );
        } else {
            $dataArr = array(
                'checkout'  => false,
            );
            $walletdoc_params = array_merge( $walletdoc_params, $dataArr );
        }

        wp_localize_script( 'woocommerce_walletdoc', 'wc_walletdoc_params', $walletdoc_params );

        $this->elements_form();

    }

    function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {

        include_once 'lib/Walletdoc.php';
        $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );

        $order_id = $renewal_order->get_id();

        $order = wc_get_order( $order_id );

        // get a subscription for the order
        $subscription = self::wd_get_subscription_for_order( $order_id );
        $parent_id =  $subscription->get_parent_id();

        if ( empty( $subscription ) ) {
            WC_Walletdoc_log( 'Subscription from renewal order was not found.' );
            return;
        }

        // TODO: Make a sanity check to ensure customer_id and payment_method_id is not null
        $customer_id = get_post_meta( $subscription->get_id(), '_walletdoc_customer_id', true );
        $payment_method_id = get_post_meta( $subscription->get_id(), '_walletdoc_payment_method_id', true );

        $orderSubscriptionArray = get_post_meta( $parent_id, '_order_subscription_ids', true );

        self::ensure_subscription_has_customer_id( $amount_to_charge, $customer_id, $payment_method_id, $order, $subscription, $orderSubscriptionArray );
    }

    function wd_get_subscription_for_order( $order_id ) {
        $subscriptions_ids = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => 'any' ) );

        //TO DO: just pull out the first element rather then doing a foreach
        foreach ( $subscriptions_ids as $subscription_id => $subscription ) {
            return $subscription;
        }

        return null;
    }

    function ensure_subscription_has_customer_id( $amount_to_charge, $customer_id, $payment_method_id, $order, $subscription, $orderSubscriptionArray ) {
        include_once 'lib/Walletdoc.php';
        $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );

        $api_data[ 'currency' ] = $order->get_currency();
        $api_data[ 'amount' ] =  number_format($amount_to_charge*100, 0, '.', '');
        $api_data[ 'customer_id' ] = sanitize_text_field( $customer_id );
        $api_data[ 'payment_method_id' ] = sanitize_text_field($payment_method_id);

      
        try{
            // WC_Walletdoc_log( 'Process request  $api_data ' . print_r( $api_data, true ) );
            $transaction_response = $api->createTransaction( $api_data );

            // WC_Walletdoc_log( 'transaction_response   data ' . print_r( $transaction_response, true ) );
    
        
        if ( !is_wp_error( $transaction_response->id ) && !empty( $transaction_response->id ) ) {
            $api2_data[ 'authenticate' ] = 'false';
            $api2_data[ 'capture' ] = 'true';
            $api2_data[ 'payment_method_id' ] = sanitize_text_field( $payment_method_id );
            $api2_data[ 'subscription_ids' ] = $orderSubscriptionArray;

          
            // WC_Walletdoc_log( 'processTransaction  request    data ' . print_r( $api2_data, true ) );
            $process_response = $api->processTransaction( $transaction_response->id, $api2_data );
       
            // WC_Walletdoc_log( 'process_response     data ' . print_r( $process_response, true ) );
            // there is a error payment reverse will be there
            if ( is_wp_error( $process_response ) || empty( $process_response->status ) ) {
                $order_number = trim( str_replace( '#', '', $order->get_order_number() ) );
                $transaction_id = $transaction_response->id;
                $data[ 'amount' ] = number_format($amount_to_charge * 100 ,0, '.', '');
                
                
                $data[ 'reason' ] = 'duplicate';
                $data[ 'reference' ] = $order_number;
                $response = $api->createRefund( $transaction_id, $data );
                $order->update_status( 'failed' );
                $order->add_order_note( sprintf( __( 'Response not recieved <br/> Subscription  transaction failed %s', 'woocommerce-walletdoc' ), $process_response->error->message ) );
                $order->save();

            } else if ( $process_response->status == 'successful' ) {

                $subscription->update_status( 'active' );
                $order->set_transaction_id( $transaction_response->id );
                $order->update_status( 'processing' );
                $order->add_order_note( 'Response from Walletdoc <br/> Subscription  transaction submitted of  R  ' . $process_response->amount / 100 . '<br/> Transaction ID:"' . $process_response->id );
                $order->save();
            } else {

                // WC_Walletdoc_log( 'Process transaction failed  ' . print_r( $transaction_response, true ) );
                $order->update_status( 'failed' );
                $order->add_order_note( sprintf( __( 'Response from Walletdoc <br/> Subscription  transaction failed %s', 'woocommerce-walletdoc' ), $process_response->error->message ) );
                $order->save();
            }
        } else {

            // WC_Walletdoc_log( 'Create transaction failed ' . print_r( $transaction_response, true ) );
            $order->update_status( 'failed' );
            if ( empty( $transaction_response ) ) {

                $order->add_order_note( sprintf( __( 'Response not recieved  <br/> Unable to create transaction ', 'woocommerce-walletdoc' ) ) );

            } else {

                $order->add_order_note( sprintf( __( 'Create transaction failed <br/> Please check error log for more details ', 'woocommerce-walletdoc' ) ) );

            }

            $order->save();
        }

    }catch ( Exception $e ) {
           
      
            if($e->getMessage()){
                global $wpdb;

                $wpdb_prefix = $wpdb->prefix;
                $wpdb_tablename = $wpdb_prefix.'woocommerce_payment_tokens';

                // $result = $wpdb->get_results( sprintf( 'SELECT `token_id` FROM `%2$s` WHERE `token` = %d ', $tokenId, $wpdb_tablename ) );

            
                $sql = "SELECT * FROM $wpdb_tablename WHERE token LIKE  '$payment_method_id'";

                $results = $wpdb->get_results($sql);

                if ($wpdb->last_error) {
                    $errorMessage = $wpdb->last_error;
                    // Use the $errorMessage variable as needed
                    die($errorMessage);
                }
                if($results){

                foreach ( $results as $result ) {

                    $wpdb->delete( $wpdb->prefix . 'woocommerce_payment_tokens', array( 'token_id' => $result->token_id ), array( '%d' ) );
                    $wpdb->delete( $wpdb->prefix . 'woocommerce_payment_tokenmeta', array( 'payment_token_id' =>  $result->token_id ), array( '%d' ) );

                }
                WC_Walletdoc_log( 'Renewal order'.$order->get_order_number().' failed due to payment method not found. The payment method may have been deleted by the customer or the bank. ' );
            }


            $order->add_order_note( sprintf( __('Renewal order'.$order->get_order_number().' failed due to payment method not found. The payment method may have been deleted by the customer or the bank. ') ) );


            $order->update_status( 'failed' );
            $order->save();
            }
       
    }
    }

    /**
    * Is $order_id a subscription?
    * @param  int  $order_id
    * @return boolean
    */

    function has_subscription( $order_id ) {
        return ( function_exists( 'wcs_order_contains_subscription' ) && ( wcs_order_contains_subscription( $order_id ) || wcs_is_subscription( $order_id ) || wcs_order_contains_renewal( $order_id ) ) );
    }

    /**
    * Process the payment method change for subscriptions.
    *
    * @param int $order_id
    */

    function change_subs_payment_method( $orderId ) {

        try {
            $subscription = wc_get_order( $orderId );

            // echo $subscription;

            $prepared_source = $this->prepare_source( get_current_user_id(), $subscription->get_id() );
            $this->save_source_to_order( $subscription, $prepared_source );

            if ( !empty( $prepared_source->source ) ) {

                return array(
                    'result' => 'success',
                    'redirect' => $this->get_return_url( $subscription ),
                );
            } else {
                return array(
                    'result' => 'failed',
                    'redirect' => $this->get_return_url( $subscription ),
                );
            }
        } catch ( Exception $e ) {
            wc_add_notice( $e->getLocalizedMessage(), 'error' );
        }
    }

    function prepare_source( $user_id, $subscription_id ) {

        // this is also for handle change payment method for subscription
        $u = get_user_option( '_walletdoc_customer_id', $user_id );

        if ( !isset( $_POST[ 'wc-walletdoc-payment-token' ] ) ) {
            wc_add_notice( __( 'Please Select any one card.', 'woocommerce-gateway-walletdoc' ), 'error' );
        } else {
            $wc_token_id = wc_clean( $_POST[ 'wc-walletdoc-payment-token' ] );

            if ( $wc_token_id == 'new' ) {

                $this->add_payment_method( 'changePaymentMethod' );
                $list = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), 'walletdoc' );

                $source_object = array_slice( $list, -1 )[ 0 ];

                $wc_token_id = $source_object->get_id();

                $wc_token = WC_Payment_Tokens::get( $wc_token_id );

            } else {
                $wc_token = WC_Payment_Tokens::get( $wc_token_id );
            }

            $source_id = $wc_token->get_token();
            $source_object = $wc_token;
            update_post_meta( $subscription_id, '_walletdoc_payment_method_id', $source_id );

            return ( object ) array(
                'token_id' => false,
                'customer' => $user_id,
                'source' => $source_id,
                'source_object' => $source_object,
            );
        }

    }

    public function save_source_to_order( $order, $source ) {

        $order_id = $order->get_id();
        // Also store it on the subscriptions being purchased or paid for in the order
        if ( function_exists( 'wcs_order_contains_subscription' ) && wcs_order_contains_subscription( $order_id ) ) {
            $subscriptions = wcs_get_subscriptions_for_order( $order_id );
        } elseif ( function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order_id ) ) {
            $subscriptions = wcs_get_subscriptions_for_renewal_order( $order_id );
        } else {
            $subscriptions = array();
        }

        foreach ( $subscriptions as $subscription ) {
            $subscription_id = $subscription->get_id();
            update_post_meta( $subscription_id, '_walletdoc_customer_id', $source->customer );
            update_post_meta( $subscription_id, '_walletdoc_payment_method_id', $source->source );
        }
    }

    public function maybe_render_subscription_payment_method( $payment_method_to_display, $subscription ) {

        $wc_token_id = get_post_meta( $subscription->get_id(), '_walletdoc_payment_method_id', true );

        update_user_option( get_current_user_id(), '_walletdoc_payment_method_id', $wc_token_id, false );
        $wc_token = WC_Payment_Tokens::get( $wc_token_id );

        $tokens = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), $this->id );

        $card = [];
        $payment_method_to_display = __( 'pay via card (Walletdoc)', 'woocommerce-gateway-walletdoc' );

        foreach ( $tokens as $token ) {
            if ( $token->get_token() == $wc_token_id ) {

                $card[ 'last4' ] = $token->get_meta( 'last4' );
                $card[ 'card_type' ] = $token->get_meta( 'card_type' );
                $card[ 'brand' ] = $token->get_meta( 'brand' );
                // Set this token as the users new default token
                //   WC_Payment_Tokens::set_users_default( get_current_user_id(), $token->get_id() );
                $data_store = WC_Data_Store::load( 'payment-token' );
                $data_store->set_default_status( $token->get_id(), true );
            } else {
                $data_store = WC_Data_Store::load( 'payment-token' );
                $data_store->set_default_status( $token->get_id(), false );
            }

            if ( $card ) {
                $payment_method_to_display = sprintf( __( 'Via %1$s card ending in %2$s', 'woocommerce-gateway-walletdoc' ), ( isset( $card[ 'card_type' ] ) ? $card[ 'card_type' ] : __( 'pay via card (Walletdoc)', 'woocommerce-gateway-walletdoc' ) ), $card[ 'last4' ] );
            }
        }

        return $payment_method_to_display;
    }

}