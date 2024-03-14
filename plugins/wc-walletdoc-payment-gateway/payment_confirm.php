<?php

if ( !defined( 'ABSPATH' ) ) {

    exit;

}

// print_r( $_REQUEST );
// die;

if ( !isset( $payment_id ) ) {

    wp_safe_redirect( get_site_url() );

}

include_once 'lib/Walletdoc.php';

try {

    $pgwalletdoc = new WP_Gateway_Walletdoc();

    $testmode = $pgwalletdoc->get_option( 'testmode' );

    $client_id = '';

    $client_secret = ( $pgwalletdoc->get_option( 'testmode' ) == 'yes' ) ? $pgwalletdoc->get_option( 'client_secret' ) : $pgwalletdoc->get_option( 'production_secret' );

    $api = new Walletdoc( $client_id, $client_secret, $testmode );

    $response = $api->getOrderById( $payment_id );

    if ( isset( $response->error ) && isset( $response->error->message ) ) {

        global $woocommerce;

        wc_add_notice( esc_html( $response->error->message, 'gateway' ), 'error' );

        wp_safe_redirect( wc_get_cart_url() );

        exit();

    } else if($response) {

        $paymentMethod = $response->payment_method == 'card' ? 'walletdoc' : 'bank2bank';

        $payment_array[ 'order_id' ] = ( isset( $response->metadata->order_id ) && $response->metadata->order_id != '' ) ? $response->metadata->order_id : $response->reference;

    }else{

        WC_Walletdoc_log('Response not found by transaction api  transaction id :- '.print_r($payment_id,true));
        WC_Walletdoc_log(print_r($response,true));
        wp_safe_redirect( wc_get_cart_url() );

        exit();
    }

    $payment_status = $api->getPaymentStatus( $payment_id, $response );

    global $woocommerce;

    $orderinfo = $payment_array;

    $order_id = $orderinfo[ 'order_id' ];

    $order = wc_get_order( $order_id );
    if($order){
        $order_status  = $order->get_status();
    }else{

        WC_Walletdoc_log('order information not found');
        wp_safe_redirect( wc_get_cart_url() );

        exit();
    }
   

    if ($order_status != 'pending' && $order_status != 'processing') {
        

        wp_safe_redirect( wc_get_cart_url() );

        exit();

    }

    // if webhook is processing the order wait for it to finish
    $attempts = 0; 
    $maxAttempts = 30;
    while( get_transient( 'walletdoc_order_lock_' . $order_id ) && $attempts < $maxAttempts){
        sleep(1);
        $attempts++;
    }

    $order = wc_get_order( $order_id );

    $order_status  = $order->get_status();

   
    if($order_status == "processing"){
        global $woocommerce;
        $woocommerce->cart->empty_cart();
        wp_safe_redirect( $pgwalletdoc->get_return_url( $order ) );
        exit();
    }

    delete_transient( 'walletdoc_order_lock_' . $order_id );

    set_transient( 'walletdoc_order_lock_' . $order_id, true, 10 );



    $items = $order->get_items();

    foreach ( $items as $item ) {

        $product_id = $item[ 'product_id' ];

    }

    if ( $payment_status === 'successful' OR $payment_status == 'failed' ) {

        if ( $order ) {

            if ( $payment_status == 'successful' ) {

                if ( function_exists( 'wcs_order_contains_subscription' ) ) {

                    $checkSubscription = wcs_order_contains_subscription( $order );

                    if ( $checkSubscription ) {

                        $subscriptions = array_merge(

                            wcs_get_subscriptions_for_renewal_order( $order_id ), wcs_get_subscriptions_for_order( $order_id )

                        );

                        foreach ( $response->subscriptions as $sub ) {

                            $OrderSubscription[] = $sub->subscription_id;

                        }

                        if ( count( $subscriptions ) > 0 ) {

                            foreach ( $subscriptions as $subscription ) {

                                update_post_meta( $subscription->get_id(), '_walletdoc_customer_id', $response->customer_id );

                                update_post_meta( $subscription->get_id(), '_walletdoc_payment_method_id', $response->payment_method_id );

                                update_post_meta( $order_id, '_order_subscription_ids', $OrderSubscription );

                            }

                        }

                        // Build the token

                    }

                }
               
                if ( $response->payment_method_id ) {

                    $payment_method_array = $api->getCustomerPaymentMethod( $response->customer_id, $response->payment_method_id );

                    WP_Gateway_Walletdoc::addToken( $payment_method_array, $order->get_customer_id() );

                }

                $order->set_transaction_id( $payment_id );

                $order->payment_complete( $payment_id );

                $order->update_meta_data( '_walletdoc_payment_method_id', $response->payment_method_id );

                $order->add_order_note( 'Response from '.$paymentMethod.'  <br/> Payment Successful <br/> Transaction ID: ' . $payment_id . ' ' );

                $order->save();

                WC_Walletdoc_log('Payment processed successfully');

                global $woocommerce;

                $woocommerce->cart->empty_cart();

                wp_safe_redirect( $pgwalletdoc->get_return_url( $order ) );

                delete_transient( 'walletdoc_order_lock_' . $order_id );

                exit();


            } else if ( $payment_status == 'failed' ) {

                // responce code for customer

                switch ( $response->response_code ) {

                    case '51':

                    $error_msg = 'Payment declined. Insufficient Funds.';

                    break;

                    case '1000':

                    $error_msg = 'Payment Failed. A connection error occurred. Please try again.';

                    break;

                    case '1011':

                    case '1013':

                    $error_msg = '3D Secure authentication error. Please contact your bank for resolution.';

                    break;

                    case '1012':

                    $error_msg = 'Payment declined. 3D Secure verification failed. Please try again.';

                    break;

                    default:

                    $error_msg = 'Payment declined. Please contact your bank for resolution.';

                }

                // responce code for merchant

                switch ( $response->response_code ) {

                    case '1':

                    case '2':

                    $merchant_error_msg = 'Refer to card issuer. Customer should contact his bank for resolution.';

                    break;

                    case '5':

                    $merchant_error_msg = 'Do not honour. Customer should contact his bank for resolution.';

                    break;

                    case '34':

                    case '41':

                    case '43':

                    case '56':

                    case '59':

                    $merchant_error_msg = 'Suspected fraud.';

                    break;

                    case '36':

                    case '62':

                    $merchant_error_msg = 'Restricted card. Customer should contact his bank for resolution.';

                    break;

                    case '51':

                    $merchant_error_msg = 'Customer has insufficient funds.';

                    break;

                    case '57':

                    $merchant_error_msg = 'Transaction not permitted to cardholder. Customer should contact his bank for resolution.';

                    break;

                    case '90':

                    $merchant_error_msg = 'Cut off in progress.';

                    break;

                    case '91':

                    $merchant_error_msg = 'Issuer or switch inoperative.';

                    break;

                    case '1000':

                    $merchant_error_msg = 'Connection error.';

                    break;

                    case '1010':

                    $merchant_error_msg = 'Customer did not complete 3D Secure authentication.';

                    break;

                    case '1011':

                    case '1013':

                    $merchant_error_msg = 'Unable to complete 3D Secure authentication.';

                    break;

                    case '1012':

                    $merchant_error_msg = 'Customer failed 3D Secure authentication.';

                    break;

                    default:

                    $merchant_error_msg = '';

                }

                if ( $merchant_error_msg != '' ) {

                    $order->add_order_note( 'Response from '.$paymentMethod.'  <br/> Payment failed <br/> Response code : ' . $response->response_code . '<br/> Description : ' . $merchant_error_msg . '<br/>' );

                } else {

                    $order->add_order_note( 'Response from '.$paymentMethod.'  <br/> Payment failed <br/> Response code :  ' . $response->response_code . ' <br/> ' );

                }

                $order->save();

                global $woocommerce;

                wc_add_notice( esc_html( $error_msg, 'gateway' ), 'error' );

                wp_safe_redirect( wc_get_cart_url() );
                delete_transient( 'walletdoc_order_lock_' . $order_id );

                exit();

            }

        } else {

            $order->save();

            global $woocommerce;

            wc_add_notice( esc_html( 'Order id not found .', 'gateway' ), 'error' );

            wp_safe_redirect( wc_get_cart_url() );
            delete_transient( 'walletdoc_order_lock_' . $order_id );


        }

    } else if ( $payment_status == 'awaiting_capture' ) {

        $order->set_transaction_id( $response->id );
        $order->update_meta_data('_walletdoc_capture_payment_status',"on-hold");

        $order->add_order_note( 'Response from '.$paymentMethod.' <br/> Successful authorization of  R' . $order->get_total() . ' <br/> Transaction ID: ' . $response->id . ' ' );

        /* translators: transaction id */

        $order->update_status( 'on-hold' );

        $order->save();

        global $woocommerce;

        $woocommerce->cart->empty_cart();

        wp_safe_redirect( $pgwalletdoc->get_return_url( $order ) );
        delete_transient( 'walletdoc_order_lock_' . $order_id );

        exit();

    } else if ( $payment_status == 'cancelled' ) {

        $order->update_status( 'cancelled' );

        $order->add_order_note( 'Response from '.$paymentMethod.' <br/> Payment cancelled ' );

        $order->save();

        global $woocommerce;

        wc_add_notice( esc_html( 'Payment cancelled.', 'gateway' ), 'error' );

        wc_print_notices();

        wp_safe_redirect( wc_get_cart_url() );
        delete_transient( 'walletdoc_order_lock_' . $order_id );

        exit();

    } else {

        $order->save();

        global $woocommerce;

        wc_add_notice( esc_html( 'Error occurred. Please try again.', 'woocommerce' ), 'error' );

        wp_safe_redirect( wc_get_cart_url() );

        $transaction_id = $response->id;

        $data[ 'amount' ] = $response->amount;

        $data[ 'reason' ] = 'customer_request';
        $data[ 'reference' ] =  $order_id;

       

        $refundRsponse = $api->createRefund( $transaction_id, $data );

      
        $subject = 'Woocommerce plugin redirect status issue';

        $body =

        '\n\n'

        . 'A woocommerce transaction has an unknown status\n'

        . '------------------------------------------------------------\n'

        . 'Site: (' .get_site_url(). ')\n'

        . ''.$paymentMethod.' Transaction object: ' .  print_r( $response, true ) . '\n'

        . 'Payment status: ' .  print_r( $payment_status, true ) . '\n'

        . 'Payment id: ' .  print_r( $payment_id, true ) . '\n';

        wp_mail( 'support@walletdoc.com', $subject, $body );
        delete_transient( 'walletdoc_order_lock_' . $order_id );

        exit();

    }
 

} catch ( WalletdocWcValidationException $e ) {

    WC_Walletdoc_log( 'Validation Exception Occured with response ' . print_r( $e->getResponse(), true ) );

} catch ( Exception $e ) {

    WC_Walletdoc_log( $e->getMessage() );

}

