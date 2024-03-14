<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Extends the payment gateway base class for PayPal Standard
 *
 */
Class PMS_Payment_Gateway_PayPal_Standard extends PMS_Payment_Gateway {


    /**
     * The features supported by the payment gateway
     *
     * @access public
     * @var array
     *
     */
    public $supports;


    /**
     * Fires just after constructor
     *
     */
    public function init() {

        $this->supports = apply_filters( 'pms_payment_gateway_paypal_standard_supports', array( 'gateway_scheduled_payments' ) );

    }


    /*
     * Process for all register payments that are not free
     *
     */
    public function process_sign_up() {

        // Do nothing if the payment id wasn't sent
        if( ! $this->payment_id )
            return;

        $settings = get_option( 'pms_payments_settings' );

        //Update payment type
        $payment = pms_get_payment( $this->payment_id );
        $payment->update( array( 'type' => apply_filters( 'pms_paypal_standard_payment_type', 'web_accept_paypal_standard', $this, $settings ) ) );

        if( isset( $_GET['subscription_plan'] ) ){

            $change_subscription_context = pms_get_change_subscription_plan_context( absint( $_GET['subscription_plan'] ), $this->subscription_plan->id );

            // Update form location to match current action in case of change_subscription so
            // we can add relevant custom success messages
            if( $this->form_location == 'change_subscription' && !empty( $change_subscription_context ) ){

                if( $change_subscription_context == 'upgrade' )
                    $this->form_location = 'upgrade_subscription';

                elseif( $change_subscription_context == 'downgrade' )
                    $this->form_location = 'downgrade_subscription';

            }

        }
        
        add_filter( 'trp_home_url', 'pms_trp_paypal_return_absolute_home', 20, 2 );

        // Set the notify URL
        $notify_url = home_url() . '/?pay_gate_listener=paypal_ipn';

        remove_filter( 'trp_home_url', 'pms_trp_paypal_return_absolute_home', 20 );

        if( pms_is_payment_test_mode() )
            $paypal_link = 'https://www.sandbox.paypal.com/cgi-bin/webscr/?';
        else
            $paypal_link = 'https://www.paypal.com/cgi-bin/webscr/?';

        $paypal_args = array(
            'cmd'           => '_xclick',
            'business'      => trim( pms_get_paypal_email() ),
            'email'         => $this->user_email,
            'item_name'     => $this->subscription_plan->name,
            'item_number'   => $this->subscription_plan->id,
            'currency_code' => $this->currency,
            'amount'        => $this->amount,
            'tax'           => 0,
            'custom'        => $this->payment_id,
            'notify_url'    => $notify_url,
            'return'        => add_query_arg( array( 'pms_gateway_payment_id' => base64_encode( $this->payment_id ), 'pmsscscd' => base64_encode( 'subscription_plans' ), 'pms_gateway_payment_action' => base64_encode( $this->form_location ) ), $this->redirect_url ),
            'bn'            => 'Cozmoslabs_SP',
            'charset'       => 'UTF-8',
            'no_shipping'   => 1,
            'lc'            => get_locale(),
        );

        $paypal_link .= http_build_query( apply_filters( 'pms_paypal_standard_args', $paypal_args, $this, $settings ) );

        do_action( 'pms_before_paypal_redirect', $paypal_link, $this, $settings );

        $payment->log_data( 'paypal_to_checkout' );

        $used_trial = get_option( 'pms_used_trial_' . $this->subscription_plan->id, false );

        if( in_array( $this->form_location, array( 'register', 'new_subscription', 'retry_payment', 'register_email_confirmation', 'wppb_register' ) ) && !empty( $used_trial ) && in_array( $this->user_email, $used_trial ) && function_exists( 'pms_add_member_subscription_log' ) )
            pms_add_member_subscription_log( $payment->member_subscription_id, 'subscription_trial_period_already_used' );

        if ( $payment->status != 'completed' && $payment->amount != 0 )
            $payment->log_data( 'paypal_ipn_waiting' );

        // Redirect only if tkn is set
        if( isset( $_POST['pmstkn'] ) ) {
            wp_redirect( $paypal_link );
            exit;
        }

    }


    /*
     * Process IPN sent by PayPal
     *
     */
    public function process_webhooks() {

        if( !isset( $_GET['pay_gate_listener'] ) || $_GET['pay_gate_listener'] !== 'paypal_ipn' )
            return;

        // Init IPN Verifier
        $ipn_verifier = new PMS_IPN_Verifier();

        if( pms_is_payment_test_mode() )
            $ipn_verifier->is_sandbox = true;


        $verified = false;

        // Process the IPN
        try {
            if( $ipn_verifier->checkRequestPost() )
                $verified = $ipn_verifier->validate();
        } catch ( Exception $e ) {

        }


        if( $verified ) {

            $post_data = $_POST;

            // Get payment id from custom variable sent by IPN
            $payment_id = isset( $post_data['custom'] ) ? $post_data['custom'] : 0;

            // Get the payment
            $payment = pms_get_payment( $payment_id );

            // Get user id from the payment
            $user_id = $payment->user_id;

            $payment_data = apply_filters( 'pms_paypal_ipn_payment_data', array(
                'payment_id'      => $payment_id,
                'user_id'         => $user_id,
                'type'            => isset( $post_data['txn_type'] ) ? $post_data['txn_type'] : '',
                'status'          => isset( $post_data['payment_status'] ) ? strtolower( $post_data['payment_status'] ) : '',
                'transaction_id'  => isset( $post_data['txn_id'] ) ? $post_data['txn_id'] : '',
                'amount'          => isset( $post_data['mc_gross'] ) ? $post_data['mc_gross'] : '',
                'date'            => isset( $post_data['payment_date'] ) ? $post_data['payment_date'] : '',
                'subscription_id' => isset( $post_data['item_number'] ) ? $post_data['item_number'] : '',
            ), $post_data );


            // web_accept is returned for A Direct Credit Card (Pro) transaction,
            // A Buy Now, Donation or Smart Logo for eBay auctions button
            if( $payment_data['type'] == 'web_accept' ) {

                // for web_accept we expect a `payment_id` in the `custom` parameter and the subscription plan id under `item_number`
                // if these are empty, do nothing
                if( empty( $payment_data['payment_id'] ) || empty( $payment_data['subscription_id'] ) )
                    return;

                // If the payment has already been completed do nothing
                if( $payment->status == 'completed' )
                    return;

                // New subscription + Renewal workflow
                // If the status is completed update the payment and also activate the member subscriptions
                if( $payment_data['status'] == 'completed' ) {

                    $payment->log_data( 'paypal_ipn_received', array( 'data' => $post_data, 'desc' => 'paypal IPN' ) );

                    // Complete payment
                    $payment->update( array( 'status' => $payment_data['status'], 'transaction_id' => $payment_data['transaction_id'] ) );

                    // Get member subscription
                    $member_subscriptions = pms_get_member_subscriptions( array( 'user_id' => $payment_data['user_id'], 'subscription_plan_id' => $payment_data['subscription_id'], 'number' => 1 ) );

                    foreach( $member_subscriptions as $member_subscription ) {

                        $subscription_plan = pms_get_subscription_plan( $member_subscription->subscription_plan_id );

                        // If subscription is pending it is a new one
                        if( $member_subscription->status == 'pending' ) {

                            $member_subscription_expiration_date = $subscription_plan->get_expiration_date();

                            pms_add_member_subscription_log( $member_subscription->id, 'subscription_activated', array( 'until' => $member_subscription_expiration_date ) );

                        // This is an old subscription
                        } else {

                            if( strtotime( $member_subscription->expiration_date ) < time() || ( !$subscription_plan->is_fixed_period_membership() && $subscription_plan->duration === 0 ) || ( $subscription_plan->is_fixed_period_membership() && !$subscription_plan->fixed_period_renewal_allowed() ) )
                                $member_subscription_expiration_date = $subscription_plan->get_expiration_date();
                            else{
                                if( $subscription_plan->is_fixed_period_membership() )
                                    $member_subscription_expiration_date = date( 'Y-m-d 23:59:59', strtotime( $member_subscription->expiration_date . '+ 1 year' ) );
                                else
                                    $member_subscription_expiration_date = date( 'Y-m-d 23:59:59', strtotime( $member_subscription->expiration_date . '+' . $subscription_plan->duration . ' ' . $subscription_plan->duration_unit ) );
                            }

                            pms_add_member_subscription_log( $member_subscription->id, 'subscription_renewed_manually', array( 'until' => $member_subscription_expiration_date ) );

                        }

                        // Update subscription
                        $member_subscription->update( array(
                            'expiration_date'    => $member_subscription_expiration_date,
                            'status'             => 'active',
                            'payment_gateway'    => 'paypal_standard',
                            'payment_profile_id' => '',
                            // reset custom schedule
                            'billing_amount'        => '',
                            'billing_duration'      => '',
                            'billing_duration_unit' => '',
                            'billing_next_payment'  => ''
                        ) );

                        // Can be a renewal payment or a new payment
                        do_action( 'pms_paypal_web_accept_after_subscription_activation', $member_subscription, $payment_data, $post_data );

                        pms_delete_member_subscription_meta( $member_subscription->id, 'pms_retry_payment' );

                    }

                    /*
                     * Change Subscription, upgrade, downgrade flow
                     *
                     * To grab the relevant subscription, we use the $member_subscription_id property from the payment
                     */

                     $current_subscription = pms_get_member_subscription( $payment->member_subscription_id );

                     if( !empty( $current_subscription ) && $current_subscription->subscription_plan_id != $payment_data['subscription_id'] ) {

                         $old_plan_id = $current_subscription->subscription_plan_id;

                         $new_subscription_plan = pms_get_subscription_plan( $payment_data['subscription_id'] );

                         $subscription_data = array(
                             'user_id'              => $payment_data['user_id'],
                             'subscription_plan_id' => $new_subscription_plan->id,
                             'start_date'           => date( 'Y-m-d H:i:s' ),
                             'expiration_date'      => $new_subscription_plan->get_expiration_date(),
                             'status'               => 'active',
                             'payment_gateway'      => 'paypal_standard',
                             'payment_profile_id'   => '',
                             // reset custom schedule
                             'billing_amount'        => '',
                             'billing_duration'      => '',
                             'billing_duration_unit' => '',
                             'billing_next_payment'  => ''
                         );

                         $current_subscription->update( $subscription_data );

                         $context = pms_get_change_subscription_plan_context( $old_plan_id, $subscription_data['subscription_plan_id'] );

                         pms_add_member_subscription_log( $current_subscription->id, 'subscription_'. $context .'_success', array( 'old_plan' => $old_plan_id, 'new_plan' => $new_subscription_plan->id ) );

                         do_action( 'pms_paypal_web_accept_after_upgrade_subscription', $new_subscription_plan->id, $payment_data, $post_data );

                         pms_delete_member_subscription_meta( $current_subscription->id, 'pms_retry_payment' );

                     }

                // If payment status is not complete, something happened, so log it in the payment
                } else {

                    $payment->log_data( 'payment_failed', array( 'data' => $post_data, 'desc' => 'ipn response') );

                    // Add the transaction ID
                    $payment->update( array( 'transaction_id' => $payment_data['transaction_id'], 'status' => 'failed' ) );

                }

            }

            do_action( 'pms_paypal_ipn_listener_verified', $payment_data, $post_data );

        }

    }


    /*
     * Verify that the payment gateway is setup correctly
     *
     */
    public function validate_credentials() {

        if ( pms_get_paypal_email() === false )
            pms_errors()->add( 'form_general', __( 'The selected gateway is not configured correctly: <strong>PayPal Address is missing</strong>. Contact the system administrator.', 'paid-member-subscriptions' ) );

    }

}
