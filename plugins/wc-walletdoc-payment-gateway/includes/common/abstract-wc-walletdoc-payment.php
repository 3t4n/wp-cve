<?php

if ( !defined( 'ABSPATH' ) ) {

    exit;
}

Class WC_Walletdoc_Payment_Gateway extends WC_Payment_Gateway {
    const SETTINGS_OPTION = 'woocommerce_walletdoc_settings';
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

        $options[ 'description' ] =  $this->description;
        $options[ 'title' ] =  $this->title;
        $options[ 'testmode' ] =  $this->testmode;
        $options[ 'api_details' ] =  $this->api_details;
        $options[ 'client_secret' ] =  $this->client_secret;
        update_option( self::SETTINGS_OPTION, $options );
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
        // print_r( $_REQUEST );
        // die;
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

    public function elements_form() {

        ?>
        <div id = 'cardDetailForm' class = 'hide'>
        <fieldset id = "wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class = 'wc-credit-card-form wc-payment-form' style = 'background:transparent;'>
        <?php do_action( 'woocommerce_credit_card_form_start', $this->id );
        ?>

        <div >
        <label for = 'walletdoc-name-element'><?php esc_html_e( 'Card Name', 'woocommerce-gateway-walletdoc' );
        ?> <span class = 'required'>*</span></label>
        <div class = 'walletdoc-card-group'>
        <div id = 'walletdoc-name-element' class = 'wc-walletdoc-elements-field'>
        <!-- a walletdoc Element will be inserted here. -->
        <!-- <input type = 'text' name = 'cardNumbert' /> -->

        </div>

        <i class = 'walletdoc-credit-card-brand walletdoc-card-brand' alt = 'Credit Card'></i>
        </div>
        </div>
        <div >
        <label for = 'walletdoc-card-element'><?php esc_html_e( 'Card Number', 'woocommerce-gateway-walletdoc' );
        ?> <span class = 'required'>*</span></label>
        <div class = 'walletdoc-card-group'>
        <div id = 'walletdoc-card-element' class = 'wc-walletdoc-elements-field'>
        <!-- a walletdoc Element will be inserted here. -->
        <!-- <input type = 'text' name = 'cardNumbert' /> -->

        </div>

        <i class = 'walletdoc-credit-card-brand walletdoc-card-brand' alt = 'Credit Card'></i>
        </div>
        </div>

        <div class = ' form-row-first'>
        <label for = 'walletdoc-exp-element'><?php esc_html_e( 'Expiry Date', 'woocommerce-gateway-walletdoc' );
        ?> <span class = 'required'>*</span></label>

        <div id = 'walletdoc-exp-element' class = 'wc-walletdoc-elements-field'>
        <!-- a walletdoc Element will be inserted here. -->
        <!-- <input type = 'text' name = 'cardExpiry' /> -->
        </div>
        </div>

        <div class = ' form-row-last'>
        <label for = 'walletdoc-cvc-element'><?php esc_html_e( 'Card Code (CVC)', 'woocommerce-gateway-walletdoc' );
        ?> <span class = 'required'>*</span></label>
        <div id = 'walletdoc-cvc-element' class = 'wc-walletdoc-elements-field'>
        <!-- a walletdoc Element will be inserted here. -->
        <!-- <input type = 'text' name = 'cardCvc' /> -->
        </div>
        </div>
        <div class = 'clear'></div>

        <!-- Used to display form errors -->
        <div class = 'walletdoc-source-errors' style = 'color:red' role = 'alert'></div>
        <input type = 'hidden' name = 'token' value = '' id = 'token' />
        <br />
        <?php do_action( 'woocommerce_credit_card_form_end', $this->id );
        ?>

        <div class = 'clear'></div>

        </fieldset>
        </div>
        <?php

    }

    // checkout initiate here

    public function process_payment( $orderId ) {

        include_once 'lib/Walletdoc.php';

        // subscription change payment method
        if ( $this->is_subs_change_payment() ) {

            return $this->change_subs_payment_method( $orderId );
        }

        $order = wc_get_order( $orderId );

        $shop = get_option( 'woocommerce_shop_page_id' );

        $user_info = get_userdata( $order->get_customer_id() );

        $customer = new WC_Customer( $order->get_customer_id() );

        $order_number = trim( str_replace( '#', '', $order->get_order_number() ) );
        // WC_Walletdoc_log( ' coming in customer condition   '.print_r( $genrated_customer_id, true ) );

        if ( function_exists( 'wcs_order_contains_subscription' ) ) {
            $checkSubscription = wcs_order_contains_subscription( $order );

            if ( $checkSubscription ) {
                $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );

                $subscriptions = array_merge(
                    wcs_get_subscriptions_for_renewal_order( $orderId ), wcs_get_subscriptions_for_order( $orderId )
                );

                if ( count( $subscriptions ) > 0 ) {
                    $subscriptionPlans = [];
                    $p = [];
                    $v = [];
                    $q = [];

                    $totalSubscription = count( $subscriptions );

                    foreach ( $subscriptions as $subscription ) {
                        // store the customer id payment method in metadata
                        if ( sizeof( $subscription_items = $subscription->get_items() ) > 0 ) {

                            $i = 0;
                            foreach ( $subscription_items as $item_id => $item ) {

                                $variationId = $item->get_variation_id();
                                $product = $item->get_product();

                                $productName = $product->get_title();
                                $product_id = wcs_get_canonical_product_id( $item );
                                // get product id directly from item

                                $p[ $subscription->get_id() ] = $product_id;
                                $v[ $subscription->get_id() ] = $variationId;

                                if ( $variationId ) {
                                    $qty = $item->get_quantity();

                                    $d1 = date( 'Y-m-d', $subscription->get_time( 'schedule_start' ) );

                                    $Sub_end_d2 = ( WC_Subscriptions_Product::get_expiration_date( $variationId ) ) == 0 ? 0 : WC_Subscriptions_Product::get_expiration_date( $variationId );
                                    $d2 = substr( $Sub_end_d2, 0, 10 );
                                    $trial_interval = get_post_meta( $variationId, '_subscription_trial_length', true );

                                    $planData[ 'quantity' ] = $qty;
                                    $planData[ 'plan_id' ] = $variationId . '.p' . $shop;
                                    $planData[ 'name' ] = $productName;
                                    $planData[ 'reference' ] = $subscription->get_id();
                                    $planData[ 'price' ] = get_post_meta( $variationId, '_subscription_price', true ) * 100;

                                    $planData[ 'currency' ] = $order->get_currency();

                                    $planData[ 'billing_period' ] = get_post_meta( $variationId, '_subscription_period', true );
                                    $planData[ 'billing_interval' ] = get_post_meta( $variationId, '_subscription_period_interval', true );
                                    $planData[ 'setup_fee' ] = get_post_meta( $variationId, '_subscription_sign_up_fee', true ) * 100 * $planData[ 'quantity' ];
                                    if ( $trial_interval != 0 ) {
                                        $planData[ 'trial_interval' ] = get_post_meta( $variationId, '_subscription_trial_length', true );
                                        $planData[ 'trial_period' ] = get_post_meta( $variationId, '_subscription_trial_period', true );
                                    } else {
                                        $planData[ 'trial_interval' ] = null;
                                        $planData[ 'trial_period' ] = null;
                                    }
                                    if ( $Sub_end_d2 != 0 ) {
                                        $planData[ 'expiry_interval' ] = self::getDateDiff( $d1, $d2, get_post_meta( $variationId, '_subscription_period', true ) );
                                    }
                                    $plan_response = $api->createPlan( $planData );
                                    $planData[ 'price' ] = number_format( $subscription->get_total()  * 100, 0, '.', '' );
                                    $subscriptionPlans[] = $planData;

                                } else {
                                    $qty = $item->get_quantity();
                                    $q[ $subscription->get_id() ] = $qty;

                                    $d1 = date( 'Y-m-d', $subscription->get_time( 'schedule_start' ) );
                                    $Sub_end_d2 = ( $subscription->get_time( 'schedule_end' ) ) == 0 ? 0 : $subscription->get_time( 'schedule_end' );
                                    $d2 = date( 'Y-m-d', $Sub_end_d2 );
                                    $trial_interval = get_post_meta( $p[ $subscription->get_id() ], '_subscription_trial_length', true );

                                    $planData[ 'quantity' ] = $q[ $subscription->get_id() ];
                                    $planData[ 'plan_id' ] = $p[ $subscription->get_id() ] . '.p' . $shop;
                                    $planData[ 'name' ] = $productName;
                                    $planData[ 'reference' ] = $subscription->get_id();
                                    $planData[ 'price' ] = ( get_post_meta( $p[ $subscription->get_id() ], '_subscription_price', true ) * 100 )?get_post_meta( $p[ $subscription->get_id() ], '_subscription_price', true ) * 100:WC_Subscriptions_Product::get_price( $product_id )*100;
                                    $planData[ 'currency' ] = $order->get_currency();

                                    $planData[ 'billing_period' ] = ( get_post_meta( $p[ $subscription->get_id() ], '_subscription_period', true ) )?get_post_meta( $p[ $subscription->get_id() ], '_subscription_period', true ):WC_Subscriptions_Product::get_period( $product );
                                    $planData[ 'billing_interval' ] = ( get_post_meta( $p[ $subscription->get_id() ], '_subscription_period_interval', true ) )?get_post_meta( $p[ $subscription->get_id() ], '_subscription_period_interval', true ):WC_Subscriptions_Product::get_interval( $product );
                                    $subSignFee = get_post_meta( $p[ $subscription->get_id() ], '_subscription_sign_up_fee', true ) ? get_post_meta( $p[ $subscription->get_id() ], '_subscription_sign_up_fee', true ) : 0;

                                    $planData[ 'setup_fee' ] = number_format( $subSignFee * 100, 0, '.', '' ) * $planData[ 'quantity' ];

                                    if ( $trial_interval != 0 ) {
                                        $planData[ 'trial_interval' ] = get_post_meta( $p[ $subscription->get_id() ], '_subscription_trial_length', true );
                                        $planData[ 'trial_period' ] = get_post_meta( $p[ $subscription->get_id() ], '_subscription_trial_period', true );
                                    } else {
                                        $planData[ 'trial_interval' ] = null;
                                        $planData[ 'trial_period' ] = null;
                                    }
                                    if ( $Sub_end_d2 != 0 ) {
                                        $planData[ 'expiry_interval' ] = self::getDateDiff( $d1, $d2, get_post_meta( $p[ $subscription->get_id() ], '_subscription_period', true ) );
                                    }

                                    $plan_response = $api->createPlan( $planData );

                                    $planData[ 'price' ] =  number_format( $subscription->get_total() * 100, 0, '.', '' );

                                    $planData[ 'billing_period' ] = WC_Subscriptions_Product::get_period( $product );
                                    $planData[ 'billing_interval' ] = WC_Subscriptions_Product::get_interval( $product );

                                    if ( $subscription_items > 1 ) {
                                        $subscriptionPlans[] = self::CombineSubscription( $planData, $subscription_items, $shop, $i, $order );
                                    } else {

                                        $subscriptionPlans[] = $planData;
                                    }
                                }
                                $i++;
                            }
                        }
                    }
                }
            }
        }

        try {
            // WC_Walletdoc_log( ' customer id  '.print_r( $order->get_customer_id(), true ) );
            $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );
            if ( $order->get_customer_id() != 0 ) {

                $genrated_customer_id = '0001234' . $order->get_customer_id() . "$shop";
                // WC_Walletdoc_log( ' coming in customer condition   '.print_r( $genrated_customer_id, true ) );
                $order->update_meta_data( '_walletdoc_customer_id', strval( $genrated_customer_id ) );

                $user_details = $api->get_user_by( 'id', $order->get_customer_id() );

                $api2_data[ 'first_name' ] = sanitize_text_field( html_entity_decode( $customer->get_first_name() ) );
                $api2_data[ 'last_name' ] = sanitize_text_field( html_entity_decode( $customer->get_last_name() ) );
                $api2_data[ 'email' ] = sanitize_email( $user_info->user_email );
                $api2_data[ 'mobile_number' ] = '';
                $api2_data[ 'customer_id' ] = sanitize_text_field( $genrated_customer_id );

                update_user_option( get_current_user_id(), '$this->id', $this->id, false );
                $response = $api->createCustomer( $api2_data );

                if ( $this->savedCards == 'yes' || $this->id == 'bank2bank' ) {
                    $api_data[ 'customer_id' ] = sanitize_text_field( $genrated_customer_id );
                    // WC_Walletdoc_log( 'api_data'.print_r( $api_data, true ) );
                }

                $f_name = sanitize_text_field( html_entity_decode( $api2_data[ 'first_name' ] ) );
                $l_name = sanitize_text_field( html_entity_decode( $api2_data[ 'last_name' ] ) );

            } else {
                $f_name = sanitize_text_field( html_entity_decode( $order->get_billing_first_name() ) );
                $l_name = sanitize_text_field( html_entity_decode( $order->get_billing_last_name() ) );
            }

            if ( $this->capture == 'yes' ) {
                $capt = true;
                $order->update_meta_data( '_walletdoc_charge_captured', 'true' );
            } else {
                $capt = false;
                $order->update_meta_data( '_walletdoc_charge_captured', 'false' );
            }

            $meta_data = array(
                'order_id' => $orderId
            );

            if ( empty( $subscriptionPlans ) ) {
                $subsciption_checkout = null;
            } else {
                $subsciption_checkout =  array_values( array_filter( $subscriptionPlans ) );
            }

            $amt = $this->get_order_total();

            $api_data[ 'amount' ] = number_format( $amt * 100, 0, '.', '' );

            $api_data[ 'currency' ] = $order->get_currency();
            $api_data[ 'reference' ] = $order_number;
            $api_data[ 'capture' ] = $capt;
            $api_data[ 'metadata' ] = $meta_data;

            if ( $this->reference_setting == 'yes' &&  $f_name  && $l_name ) {
                $api_data[ 'reference' ] = $order_number.' '.$f_name.' '.$l_name;
            }
            // WC_Walletdoc_log( "reference-data'".print_r( $api_data, true ) );

            // if ( $_REQUEST[ 'token' ] || $_REQUEST[ 'wc-walletdoc-payment-token' ] ) {

            //     $tokenID =  $_REQUEST[ 'wc-walletdoc-payment-token' ];
            //     if ( $tokenID != 'new' ) {
            //         global $wpdb;

            //         $wpdb_prefix = $wpdb->prefix;
            //         $wpdb_tablename = $wpdb_prefix.'woocommerce_payment_tokens';

            //         $sql = "SELECT * FROM $wpdb_tablename WHERE token_id LIKE  '$tokenID'";
            //         $results = $wpdb->get_results( $sql ) or die( mysql_error() );

            //         foreach ( $results as $result ) {
            //             $api_data[ 'payment_method_id' ] = $result->token;
            //         }

            //     } else {
            //         $api_data[ 'payment_method_id' ] = $_REQUEST[ 'token' ];
            //     }

            //     WC_Walletdoc_log( 'api_data'.print_r( $api_data, true ) );
            //     $response = $api->createTransaction( $api_data );

            //     WC_Walletdoc_log( 'create transaction response'.print_r( $response, true ) );

            //     $walletdoc_params = array(
            //         'key'  =>$this->client_secret,
            //         'publicKey'=>$publicData->id,

            // );
            //     if ( !is_wp_error( $response->id ) && !empty( $response->id ) ) {
            //         $passValueArr = array(
            //             'payment_method_id'  => $response->payment_method_id,
            //             'transaction_id'  => $response->id,
            // );
            //         $walletdoc_params = array_merge( $walletdoc_params, $passValueArr );
            //         WC_Walletdoc_log( 'calling and pasing value in js'.print_r( $walletdoc_params, true ) );
            //         wp_localize_script( 'woocommerce_walletdoc', 'wc_walletdoc_params', $walletdoc_params );

            //     }

            // if ( !is_wp_error( $response->id ) && !empty( $response->id ) ) {
            //     $requestData[ 'amount' ] = $amt * 100;
            //     $requestData[ 'currency' ] = $order->get_currency();
            //     $requestData[ 'payment_method_id' ] =  $response->payment_method_id;

            //     WC_Walletdoc_log( 'create  process_request'.print_r( $requestData, true ) );
            //     $process_response = $api->processTransaction( $response->id, $requestData );
            // }
            // WC_Walletdoc_log( 'create  process_response'.print_r( $process_response, true ) );

            // $order->update_meta_data( '_walletdoc_transactionid', $process_response->id );
            // $order->save();

            // $payment_id = $process_response->id;
            // $payment_array =  array( 'payment_request_id' => $process_response->id );
            // include_once 'payment_confirm.php';
            // $pgwalletdoc = new WP_Gateway_Walletdoc();
            // return array(
            //     'result' => 'success',
            //     'redirect' => $pgwalletdoc->get_return_url( $order ),

            // );

            // } else {

            $api_data[ 'subscription_plans' ] = $subsciption_checkout;
            $api_data[ 'return_url' ] = $this->get_return_url( $order );
            $api_data[ 'payment_method_types' ] =  $this->id == 'walletdoc' ? [ 'card' ] : [ 'bank2bank' ];

            // WC_Walletdoc_log( 'api_data'.print_r( $api_data, true ) );
            $response = $api->createOrderPayment( $api_data );

            if ( isset( $response->redirect ) ) {

                $url = $response->redirect->redirect_url;
                $order->update_meta_data( '_walletdoc_transactionid', $response->redirect->id );
                $order->save();

                return array(
                    'result' => 'success',
                    'redirect' => "$url?id=" . $response->redirect->id . ''
                );
                $this->callback_handler();
            }

            // }

        } catch ( WalletdocWcValidationException $e ) {
            $this->log( 'Validation Exception Occured with response  ' . print_r( $e->getResponse(), true ) );

            $errors_html = '<ul class=\'woocommerce-error\'>';
            if ( isset( $e->getResponse()->error->code ) ) {
                foreach ( $e->getErrors() as $error ) {
                    if ( isset( $e->getResponse()->error->code ) && $e->getResponse()->error->code == '2000' ) {
                        $errors_html .= '<li>Saved cards must be enabled for subscription payments</li>';
                    } else {

                        $errors_html .= '<li>' . $error . '</li>';
                    }

                }
            } else {
                $errors_html .= '<li> Something went wrong </li>';
            }

            $errors_html .= '</ul>';
            $json = array(
                'result' => 'failure',
                'messages' => $errors_html,
                'refresh' => 'false',
                'reload' => 'false'
            );
            die( json_encode( $json ) );
        } catch ( Exception $e ) {

            $this->log( 'An error occurred on line ' . $e->getLine() . ' with message ' . $e->getMessage() );
            $this->log( 'Traceback: ' . $e->getTraceAsString() );
            $json = array(
                'result' => 'failure',
                'messages' => '<ul class=\'woocommerce-error\'>\n\t\t\t<li>' . $e->getMessage() . '</li>\n\t</ul>\n',
                'refresh' => 'false',
                'reload' => 'false'
            );
            die( json_encode( $json ) );
        }
    }

    /**
    * Capture payment when the order is changed from on-hold to complete or processing.
    *
    * @param  int $order_id
    */

    public function capture_payment( $order_id ) {

        include_once 'lib/Walletdoc.php';

        $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );
        $order = wc_get_order( $order_id );

        if ( isset( $order ) && $order != '' &&  $order->get_payment_method() == 'walletdoc' ) {

            $captured = $order->get_meta( '_walletdoc_charge_captured' );

            if ( 'false' === $captured ) {

                $charge = $order->get_transaction_id();

                $is_walletdoc_captured = false;

                $response = $api->getOrderById( $charge );

                if ( isset( $response->status ) && $response->status == 'awaiting_capture' ) {

                    if ( $charge ) {

                        $order_total = $order->get_total();
                        $capture_data[ 'capture_amount' ] =  number_format( $order_total * 100, 0, '.', '' );
                        $capture_txn_response = $api->captureTransactionProcess( $charge, $capture_data );

                        if ( !empty( $capture_txn_response->error ) ) {
                            /* translators: error message */
                            $order->update_status( 'failed' );
                            $order->add_order_note( sprintf( __( 'Response from Walletdoc <br/> Unable to capture charge! %s', 'woocommerce-walletdoc' ), $capture_txn_response->error->message ) );
                        } elseif ( false === $capture_txn_response->capture ) {
                            $is_walletdoc_captured = false;
                        } elseif ( true === $capture_txn_response->capture ) {
                            $is_walletdoc_captured = true;
                        }

                        if ( $capture_txn_response->status == 'successful' ) {
                            $is_walletdoc_captured = true;
                        }

                        if ( $is_walletdoc_captured ) {
                            /* translators: transaction id */
                            $order->add_order_note( 'Response from Walletdoc <br/> Successful capture of R' . $order_total . '  <br/> Transaction ID: ' . $capture_txn_response->id . ' ' );
                            $order->update_meta_data( '_walletdoc_charge_captured', 'true' );
                            // Store other data such as fees
                            $order->set_transaction_id( $capture_txn_response->id );
                            if ( is_callable( array( $order, 'save' ) ) ) {

                                $order->save();
                            }
                        }
                    } 
                }
            }

        }
    }

    public function webhook() {

        include_once 'lib/Walletdoc.php';

        $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );
        // $order = wc_get_order( $order_id );
        $s_array = [];
        $secret_key = $this->client_secret;
        $jsonPayload = file_get_contents( 'php://input' );
        $sign_array = isset( $_SERVER[ 'HTTP_WALLETDOC_SIGNATURE' ] ) ? $_SERVER[ 'HTTP_WALLETDOC_SIGNATURE' ] : null;
        $signature_valid = self::validate_walletdoc_signature( $sign_array, $jsonPayload, $secret_key );

        if ( $signature_valid == true ) {

            $raw_post = file_get_contents( 'php://input' );
            $decoded = json_decode( $raw_post );

            // code for cancel transaction
            // transaction failed
            // transaction success

            if ( $decoded[ 0 ]->event == 'transaction.cancelled' || $decoded[ 0 ]->event == 'transaction.succeeded' || $decoded[ 0 ]->event == 'transaction.failed' ) {

                if ( isset( $decoded[ 0 ]->transaction->metadata->order_id ) && $decoded[ 0 ]->transaction->metadata->order_id != '' ) {
                    $order_id = isset( $decoded[ 0 ]->transaction->metadata->order_id ) ? $decoded[ 0 ]->transaction->metadata->order_id : null;

                } else {

                    $order_id = isset( $decoded[ 0 ]->transaction->reference ) ? $decoded[ 0 ]->transaction->reference : null;
                }

                $payment_id = isset( $decoded[ 0 ]->transaction->id ) ? $decoded[ 0 ]->transaction->id : null;

                if ( is_null( $order_id ) )
                return;

                if ( isset( $decoded[ 0 ]->transaction->status ) ) {

                    // if page redirect is processing the order wait for it to finish
                    $attempts = 0;

                    $maxAttempts = 30;
                    while( get_transient( 'walletdoc_order_lock_' . $order_id ) && $attempts < $maxAttempts ) {
                        sleep( 1 );
                        $attempts++;
                    }
                    $order = wc_get_order( $order_id );
                    if ( is_a( $order, 'WC_Order' ) &&  $order->has_status( [ 'processing', 'completed', 'on-hold' ] ) ) {
                        return;
                    }
                    set_transient( 'walletdoc_order_lock_' . $order_id, true, 10 );

                    if ( $decoded[ 0 ]->transaction->status == 'failed' || $decoded[ 0 ]->event == 'transaction.cancelled' ) {
                        if ( $order->status == 'pending' ) {
                            $order->cancel_order( __( 'Unpaid order cancelled - Walletdoc Returned Failed Status for payment Id '.$payment_id, 'woocommerce' ) );

                        }
                    } else if ( $decoded[ 0 ]->transaction->status == 'successful' ) {

                        if ( function_exists( 'wcs_order_contains_subscription' ) ) {

                            $checkSubscription = wcs_order_contains_subscription( $order );

                            if ( $checkSubscription ) {

                                $subscriptions = array_merge(

                                    wcs_get_subscriptions_for_renewal_order( $order_id ), wcs_get_subscriptions_for_order( $order_id )

                                );

                                foreach ( $decoded[ 0 ]->transaction->subscriptions as $sub ) {

                                    $OrderSubscription[] = $sub->subscription_id;

                                }

                                if ( count( $subscriptions ) > 0 ) {

                                    foreach ( $subscriptions as $subscription ) {

                                        update_post_meta( $subscription->get_id(), '_walletdoc_customer_id', $decoded[ 0 ]->transaction->customer_id );

                                        update_post_meta( $subscription->get_id(), '_walletdoc_payment_method_id', $decoded[ 0 ]->transaction->payment_method_id );

                                        update_post_meta( $order_id, '_order_subscription_ids', $OrderSubscription );

                                    }

                                }

                                // Build the token

                            }

                        }

                        if ( $decoded[ 0 ]->transaction->payment_method_id ) {

                            $payment_method_array = $api->getCustomerPaymentMethod( $decoded[ 0 ]->transaction->customer_id, $decoded[ 0 ]->transaction->payment_method_id );

                            WP_Gateway_Walletdoc::addToken( $payment_method_array, $order->get_customer_id() );

                        }

                        $paymentMethod = $decoded[ 0 ]->transaction->payment_method == 'card' ? 'walletdoc' : 'bank2bank';
                        $order->set_transaction_id( $payment_id );
                        $order->payment_complete( $payment_id );

                        $order->update_meta_data( '_walletdoc_payment_method_id', $decoded[ 0 ]->transaction->payment_method_id );

                        $order->add_order_note( 'Response from '.$paymentMethod.'  <br/>webhook  Payment Successful <br/> Transaction ID: ' . $payment_id . ' ' );

                        $order->save();

                        WC_Walletdoc_log( 'Payment processed successfully via webhook' );

                    } else {
                        WC_Walletdoc_log( 'Ignored Walletdoc transaction status ' . $decoded[ 0 ]->transaction->status );

                    }

                    delete_transient( 'walletdoc_order_lock_' . $order_id );

                } else {
                    WC_Walletdoc_log( 'No transaction status found in Walletdoc webhook.' );

                }
            }
            // refund webhook initiate here
            if ( $decoded[ 0 ]->event == 'refund.succeeded' ) {

                $username = '';
                $order_id = '';
                $args = array(
                    'transaction_id' => $decoded[ 0 ]->refund->transaction_id,
                );
                $amt = $decoded[ 0 ]->refund->amount / 100;
                $ord = wc_get_orders( $args );
                $order = wc_get_order( $ord[ 0 ]->id );
                if ( $order->total == $amt ) {
                    $order->update_status( 'refunded', 'Refunded via Walletdoc <br/>' );
                }
                $order->add_order_note( 'Response from Walletdoc <br/> R' . $amt . '  refunded successfully  <br/> ' );

                $order->save();

            }
            // paymethod delete from checkout webhook initiate here
            if ( $decoded[ 0 ]->event == 'customer.payment_method.deleted' ) {

                $tokenId =  $decoded[ 0 ]->payment_method->id;

                // $deleted = wc_delete_payment_token( $token_id );

                global $wpdb;

                $wpdb_prefix = $wpdb->prefix;
                $wpdb_tablename = $wpdb_prefix.'woocommerce_payment_tokens';

                // $result = $wpdb->get_results( sprintf( 'SELECT `token_id` FROM `%2$s` WHERE `token` = %d ', $tokenId, $wpdb_tablename ) );

                $sql = "SELECT * FROM $wpdb_tablename WHERE token LIKE  '$tokenId'";

                $results = $wpdb->get_results( $sql );

                if ( $wpdb->last_error ) {
                    $errorMessage = $wpdb->last_error;
                    // Use the $errorMessage variable as needed
                    die( $errorMessage );
                }
                if ( $results ) {

                    foreach ( $results as $result ) {
                        // Query the payment tokens table

                        $query = $wpdb->prepare( "
                                SELECT user_login
                                FROM {$wpdb->prefix}users
                                WHERE ID = %d
                                ", $result->user_id );

                        // Execute the query
                        $resData = $wpdb->get_row( $query );

                        // Extract the username
                        $username = $resData->user_login;

                        $wpdb->delete( $wpdb->prefix . 'woocommerce_payment_tokens', array( 'token_id' => $result->token_id ), array( '%d' ) );
                        $wpdb->delete( $wpdb->prefix . 'woocommerce_payment_tokenmeta', array( 'payment_token_id' =>  $result->token_id ), array( '%d' ) );
                    }

                    WC_Walletdoc_log( 'Card deleted via webhook' );
                    if ( $username ) {
                        WC_Walletdoc_log( 'Card ending in '.print_r( $decoded[ 0 ]->payment_method->card->last4.' deleted by '. $username, true ) );
                    }
                }

            }
        } else {
            WC_Walletdoc_log( 'the signature is invalid .' );
        }
    }

    function validate_walletdoc_signature( $signature_header, $payload, $secret_key ) {

        $signature_elements = explode( ',', $signature_header );
        $t = str_replace( 't=', '', $signature_elements[ 0 ] );
        $calculated_signature = 's=' . base64_encode( hash_hmac( 'sha256', $t . $payload, $secret_key, true ) );

        for ( $i = 1; $i < count( $signature_elements );
        $i++ ) {
            if ( $signature_elements[ $i ] == $calculated_signature ) {
                return true;
            }
        }
        return false;
    }

    public static function log( $message ) {
        WC_Walletdoc_log( $message );
    }

    //refund initiate here

    public function process_refund( $order_id, $amount = null, $reason = '' ) {

        include_once 'lib/Walletdoc.php';

        $order = wc_get_order( $order_id );

        if ( !$order ) {
            return false;
        }
        try {
            if ( $order->status == 'processing' || $order->status == 'completed' ) {
                $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );
                $transaction_id = $order->transaction_id;
                $order_number = trim( str_replace( '#', '', $order->get_order_number() ) );

                $data[ 'amount' ] =  number_format( $amount * 100, 0, '.', '' );

                if ( self::stringMatchMethod( 'duplicate', $reason ) ) {
                    $data[ 'reason' ] = 'duplicate';
                } elseif ( self::stringMatchMethod( 'fraud', $reason ) ) {
                    $data[ 'reason' ] = 'fraud';
                } else {
                    $data[ 'reason' ] = 'customer_request';
                }
                $data[ 'reference' ] = $order_number;

                $response = $api->createRefund( $transaction_id, $data );

                if ( !$response ) {
                    throw new Exception( __( 'An error occurred while attempting to create the refund using the payment gateway API.', 'woocommerce' ) );
                }
                if ( isset( $response->error->message ) ) {
                    $order->add_order_note( 'Response from Walletdoc  <br/> ' . $response->error->message );
                    $order->save();
                    throw new Exception( $response->error->message );
                } else {
                    $order->add_order_note( 'Response from Walletdoc  <br/> Refund request submitted' );
                    $order->save();
                }
                return true;
            } else {
                return new WP_Error( 'error', 'Order has not been paid and therefore cannot be refunded' );

            }

        } catch ( Exception $e ) {
            return new WP_Error( 'error', $e->getMessage() );
        }
    }

    //method for string match

    function stringMatchMethod( $word, $string ) {
        if ( strpos( $string, $word ) !== false ) {
            return true;
        } else {
            return false;
        }
    }

    function validate_production_secret_field( $key, $value ) {

        $value = $this->validate_text_field( $key, $value );
        if ( !empty( $value ) && !preg_match( '/^sk_production/', $value ) ) {
            if ( $this->get_option( 'testmode' ) != 'yes' ) {
                echo '<div class="updated error notice"><p>';
                echo _e( '<b>Walletdoc</b> The production secret key should start with "sk_production". Please enter the correct key.', 'my-text-domain' );
                echo '</p></div>';
                $value = '';
            }
        }
        return $value;
    }

    function validate_client_secret_field( $key, $value ) {
        $value = $this->validate_text_field( $key, $value );

        if ( !empty( $value ) && !preg_match( '/^sk_sandbox/', $value ) ) {
            if ( $this->get_option( 'testmode' ) == 'yes' ) {
                echo '<div class="updated error notice"><p>';
                echo _e( '<b>Walletdoc</b>  The sandbox secret key should start with "sk_sandbox". Please enter the correct key.', 'my-text-domain' );
                echo '</p></div>';
                $value = '';
            }
        }
        return $value;
    }

    /**
    * Check if we need to make gateways available.
    */

    function is_available() {
        if ( 'yes' === $this->enabled ) {
            return self::are_keys_set();
        }
        return parent::is_available();
    }

    function are_keys_set() {

        if ( $this->get_option( 'testmode' ) == 'yes' ) {
            return preg_match( '/^sk_sandbox/', $this->client_secret );
        } else {
            return preg_match( '/^sk_production/', $this->client_secret );
        }
    }

    // * Checks if page is pay for order and change subs payment page.
    // *
    // * @return bool
    // */

    function is_subs_change_payment() {
        return ( isset( $_GET[ 'pay_for_order' ] ) && isset( $_GET[ 'change_payment_method' ] ) );
    }

    public static function addToken( $payment_method_array, $userid ) {

        $tokens = WC_Payment_Tokens::get_customer_tokens( $userid, 'walletdoc' );

        if ( self::isEmpty( $tokens ) ) {
            $token = new WC_Payment_Token_CC();
            $token->set_token( $payment_method_array->id );
            // Token comes from payment processor
            $token->set_gateway_id( 'walletdoc' );
            $token->set_last4( $payment_method_array->card->last4 );
            $token->set_expiry_year( $payment_method_array->card->expiry_year );
            $token->set_expiry_month( $payment_method_array->card->expiry_month );
            $token->set_card_type( $payment_method_array->card->brand );
            $token->set_user_id( $userid );
            // Save the new token to the database
            $token->save();

            WC_Payment_Tokens::set_users_default( $userid, $token->get_id() );

        } else {

            foreach ( $tokens as $token ) {

                $Existtoken[] = $token->get_token();
            }

            if ( !in_array( $payment_method_array->id, $Existtoken ) ) {
                $token = new WC_Payment_Token_CC();
                $token->set_token( $payment_method_array->id );
                // Token comes from payment processor
                $token->set_gateway_id( 'walletdoc' );
                $token->set_last4( $payment_method_array->card->last4 );
                $token->set_expiry_year( $payment_method_array->card->expiry_year );
                $token->set_expiry_month( $payment_method_array->card->expiry_month );
                $token->set_card_type( $payment_method_array->card->brand );
                $token->set_user_id( $userid );
                // Save the new token to the database
                $token->save();
                WC_Payment_Tokens::set_users_default( $userid, $token->get_id() );

            }
        }
    }

    function getDateDiff( $date1, $date2, $type = '' ) {

        if ( strtoupper( $type ) == 'MONTH' ) {

            $ts1 = strtotime( $date1 );
            $ts2 = strtotime( $date2 );
            $year1 = date( 'Y', $ts1 );
            $year2 = date( 'Y', $ts2 );
            $month1 = date( 'm', $ts1 );
            $month2 = date( 'm', $ts2 );
            $diff = ( ( $year2 - $year1 ) * 12 ) + ( $month2 - $month1 );
            return $diff;
        } else if ( strtoupper( $type ) == 'DAY' ) {

            $earlier = new DateTime( $date1 );
            $later = new DateTime( $date2 );
            $diff = $later->diff( $earlier )->format( '%a' );
            return $diff;
        } else if ( strtoupper( $type ) == 'YEAR' ) {
            $date1 = $date1;
            $date2 = $date2;
            $diff = abs( strtotime( $date2 ) - strtotime( $date1 ) );
            $years = floor( $diff / ( 365 * 60 * 60 * 24 ) );
            return $years;
        } else {
            $date1 = new DateTime( $date1 );
            // Specify the end date
            $date2 = new DateTime( $date2 );
            // Calculate the number of weeks between two dates
            $difference_in_weeks = $date1->diff( $date2 )->days / 7;
            // Show the number of weeks between two dates
            return round( $difference_in_weeks );
        }
    }

    function CombineSubscription( $plan_Array, $subscription_items, $shop, $i, $order ) {
        $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );
        $setupFee = 0 ;
        $quantity = 0;
        if ( $i == 0 ) {

            foreach ( $subscription_items as $item_id => $item ) {
                $product_id = wcs_get_canonical_product_id( $item );
                $pro_id_array[] = $product_id;
                $subSetupFee = get_post_meta( $product_id, '_subscription_sign_up_fee', true ) ? get_post_meta( $product_id, '_subscription_sign_up_fee', true ) : 0;

                $setupFee +=  number_format( $subSetupFee * 100, 0, '.', '' ) * $item->get_quantity();
                $quantity += $item->get_quantity();
            }
            $plan_id = substr( implode( '.', $pro_id_array ), 0, 60 );

            if ( $plan_Array[ 'billing_period' ] == 'day' ) {
                $plan_name = 'Daily';
            } else if ( $plan_Array[ 'billing_period' ] == 'month' ) {
                $plan_name = 'Monthly';
            } else if ( $plan_Array[ 'billing_period' ] == 'week' ) {
                $plan_name = 'Weekly';
            } else {
                $plan_name = 'Yearly';
            }

            $planData[ 'quantity' ] = 1;
            $planData[ 'plan_id' ] = $plan_id . '.p' . $shop;
            $planData[ 'name' ] = $plan_name . ' Subscription';
            $planData[ 'reference' ] = $plan_Array[ 'reference' ];
            $planData[ 'price' ] = $plan_Array[ 'price' ];
            $planData[ 'currency' ] = $plan_Array[ 'currency' ];
            $planData[ 'billing_period' ] = $plan_Array[ 'billing_period' ];
            $planData[ 'billing_interval' ] = $plan_Array[ 'billing_interval' ];
            $planData[ 'setup_fee' ] = $setupFee;
            $planData[ 'trial_interval' ] = null;
            $planData[ 'trial_period' ] = null;

            $plan_response = $api->createPlan( $planData );

            return $planData;
        }
    }

    public function add_payment_method( $check = '' ) {

        include_once 'lib/Walletdoc.php';
        $api = new Walletdoc( $this->client_id, $this->client_secret, $this->testmode );

        $userId = get_user_option( '_walletdoc_customer_id', get_current_user_id() );
        if ( !$userId ) {
            $shop = get_option( 'woocommerce_shop_page_id' );
            $customer = new WC_Customer( get_current_user_id() );
            $user_details = $api->get_user_by( 'id', get_current_user_id() );
            $user_info = get_userdata( get_current_user_id() );
            $genrated_customer_id = '0001234' . get_current_user_id() . "$shop";

            $api2_data[ 'first_name' ] = sanitize_text_field( html_entity_decode( $customer->get_first_name() ) );
            $api2_data[ 'last_name' ] = sanitize_text_field( html_entity_decode( $customer->get_last_name() ) );
            $api2_data[ 'email' ] = sanitize_email( $user_info->user_email );
            $api2_data[ 'mobile_number' ] = '';
            $api2_data[ 'customer_id' ] = sanitize_text_field( $genrated_customer_id );
            $response = $api->createCustomer( $api2_data );
            update_user_option( get_current_user_id(), '_walletdoc_customer_id', $genrated_customer_id, false );
        }

        $userId = get_user_option( '_walletdoc_customer_id', get_current_user_id() );

        $error_msg = __( 'There was a problem adding the payment method.', 'woocommerce-gateway-walletdoc' );

        if ( isset( $_POST[ 'token' ] ) && $_POST[ 'token' ] != '' ) {
            $data[ 'token_id' ] = $_POST[ 'token' ];
            $tokenResponse  =  $api->getCustomerToken( $userId, $data );

        } else {

            wc_add_notice( $error_msg, 'error' );
            return array(
                'result'   => 'error',
                'redirect' => wc_get_endpoint_url( 'payment-methods' ),
            );
        }

        if ( isset( $tokenResponse->error ) ) {
            if ( isset( $tokenResponse->error->code ) ) {

                $tokenList  =  $api->getCustomerTokenList( $userId );
                $oldList = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), 'walletdoc' );

                if ( isset( $tokenList->payment_methods ) ) {
                    foreach ( $tokenList->payment_methods as $tkn ) {
                        self::addToken( $tkn, get_current_user_id() );
                    }
                    $newList = WC_Payment_Tokens::get_customer_tokens( get_current_user_id(), 'walletdoc' );

                    if ( count( $newList ) > count( $oldList ) ) {

                        do_action( 'wc_walletdoc_add_payment_method_' . $_POST[ 'payment_method' ] . '_success', $_POST[ 'token' ], $tokenResponse );

                        return array(
                            'result'   => 'success',
                            'redirect' => wc_get_endpoint_url( 'payment-methods' ),
                        );
                    } else {

                        wc_add_notice( 'Card already exists', 'error' );
                        return array(
                            'result'   => 'error',
                            'redirect' => wc_get_endpoint_url( 'payment-methods' ),
                        );
                    }
                }
            } else {

                // wc_add_notice( $error_msg, 'error' );

                // return;
                wc_add_notice( $error_msg, 'error' );
                return array(
                    'result'   => 'error',
                    'redirect' => wc_get_endpoint_url( 'payment-methods' ),
                );
            }

        } else {

            if ( isset( $tokenResponse->card ) ) {
                self::addToken( $tokenResponse, get_current_user_id() );
            }
            do_action( 'wc_walletdoc_add_payment_method_' . $_POST[ 'payment_method' ] . '_success', $_POST[ 'token' ], $tokenResponse );

            return array(
                'result'   => 'success',
                'redirect' => wc_get_endpoint_url( 'payment-methods' ),
            );
        }

    }

    public static function isEmpty( $value ) {

        return ( !$value || $value == null || $value == 'undefined' || $value == '' );
    }

}

