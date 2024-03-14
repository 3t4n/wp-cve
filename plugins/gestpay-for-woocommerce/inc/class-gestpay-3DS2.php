<?php

/**
 * Gestpay for WooCommerce
 *
 * Copyright: © 2019 Axerve S.p.A. - Gruppo Banca Sella (https://www.axerve.com - ecommerce@sella.it)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * Whereas with 3D Secure 1.0 every transaction undergoes an authentication which always requires an action from the buyer,
 * the application of 3D Secure 2 may result in two different outcomes: challenge flow or frictionless flow. When a challenge
 * flow occurs, the issuing bank requires a Strong Customer Authentication (SCA). While PSD2 requires the strong customer
 * authentication of the buyer for remote transaction, in some cases an exemption is allowed and in this case a frictionless
 * flow occurs. The more informations are passed to the issuing bank, the most likely it is for the transaction to result
 * in a frictionless flow. In this scenario the authentication does not require any involvement of the buyer.
 *
 * @read more at https://docs.gestpay.it/soap/3ds-2.0/how-change-integration/
 *
 * Check for:
 * - @N/A - for fields that are unavailable
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Default value for unknown bank trans. id of the parent order of a recurring transaction
define( 'GESTPAY_BANK_TRANSACTION_ID_01N_UNKNOWN', -100 );

class Gestpay_3DS2 {

    private static function get_trans_type( $params, $order, $ws_type = 'WsS2S', $scheduled_payment = false ) {

        $is_request_to_change_payment = function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order );
        $is_parent_order = function_exists( 'wcs_order_contains_subscription' ) && wcs_order_contains_subscription( $order, array( 'order_type' => 'parent' ) );
        $is_renewal_order = function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order );

        $is_subs_parent = $is_parent_order || $is_request_to_change_payment ? true : false;
        $is_subs_renewal = $is_renewal_order ? true : false;

        if ( $is_subs_parent ) {
            //    1 - First subscription payment (parent order)
            // OR 2 - manual payment after a failed recurring payment (handled on the next elseif section)
            // OR 3 - changed card (subscription_payment_method_change_customer)
            return '01F';
        }
        elseif ( $ws_type == 'WsS2S' && $is_subs_renewal ) {
            // Renewal payments - Only for WsS2S API
            // 01N must be used only if is automatic renewal payment. A manual renewal must be 01F.
            return !empty( $scheduled_payment ) ? '01N' : '01F';
        }
        else {
            // Standard payments.
            return 'EC';
        }

        // - @N/A - Other allowed values currently not used are:
        // - NPA - only used on authentication calls
        // - 02F and 02N - dilated payments
        // - 08 and 09 Mail/Telephone Orders
    }

    private static function get_profileDetails() {
        $profileDetails = array();
        $cardholderID = get_current_user_id();
        if ( !empty( $cardholderID ) ) {
            // Add user ID only if authenticated (otherwise it will be 0 for all anonymous)
            $profileDetails['cardholderID'] = substr( (string)$cardholderID, 0, 64 );

            // Try to get last login timestamp from the session
            $sessions = get_user_meta( $cardholderID, 'session_tokens', true );
            if ( is_array( $sessions ) ) {
                $last_login = reset( $sessions );
                if ( !empty( $last_login['login'] ) ) {
                    // Fix: format timestamp to YYYYMMDDHHMM
                    $profileDetails['authTimestamp'] = date( 'YmdHi', $last_login['login'] );
                }
            }

            $profileDetails['authData'] = ''; // Fix: removed ua
        }

        // Other values (03, 04, ...) are not applicable. 01 if anonymous, 02 if logged in
        $profileDetails['authMethod'] = empty( $cardholderID ) ? '01' : '02';

        return $profileDetails;
    }

    /**
     * Billing address with available informations.
     */
    private static function get_billingAddress( $order ) {
        $addr = array(
            'city'     => $order->get_billing_city(),
            'country'  => $order->get_billing_country(),
            'line1'    => substr($order->get_billing_address_1(), 0, 50),
            'line2'    => substr($order->get_billing_address_2(), 0, 50),
            // - @N/A - 'line3' => UNAVAILABLE
            'postCode' => $order->get_billing_postcode(),
            'state'    => $order->get_billing_state()
        );

        foreach ( $addr as $k => $v ) {
            if ( empty( $v ) ) {
                unset( $addr[$k] );
            }
        }

        return $addr;
    }

    /**
     * Shipping address with available informations.
     */
    private static function get_shippingAddress( $order ) {
        $addr = array(
            'city'     => $order->get_shipping_city(),
            'country'  => $order->get_shipping_country(),
            'line1'    => substr($order->get_shipping_address_1(), 0, 50),
            'line2'    => substr($order->get_shipping_address_2(), 0, 50),
            // - @N/A - 'line3' => UNAVAILABLE
            'postCode' => $order->get_shipping_postcode(),
            'state'    => $order->get_shipping_state()
        );

        foreach ( $addr as $k => $v ) {
            if ( empty( $v ) ) {
                unset( $addr[$k] );
            }
        }

        return $addr;
    }

    /**
     * Number of purchases (all or only successful) with this cardholder account during the previous $minus_date.
     */
    private static function get_nbPurchaseAccount( $only_successful = false, $minus_date = 0, $limit = -1, $return = 'count', $order = 'ASC' ) {
        // https://github.com/woocommerce/woocommerce/wiki/wc_get_orders-and-WC_Order_Query

        $args = array(
            'customer_id' => get_current_user_id(),
            'limit' => $limit,
            'return' => 'ids',
            'order' => $order,
        );

        if ( $only_successful ) {
            $args['status'] = array( 'processing', 'completed' );
        }

        if ( $minus_date > 0 ) {
            $args['date_created'] = '>' . ( time() - $minus_date );
        }

        $customer_orders = wc_get_orders( $args );

        return $return == 'count' ? count( $customer_orders ) : $customer_orders;
    }

    private static function get_indicative_date( $in_date ) {
        if ( $in_date < strtotime( '-60 days' ) ) {
            return '05'; // More than 60 days
        }
        elseif ( $in_date < strtotime( '-30 days' ) ) {
            return '04'; // 30−60 days
        }
        elseif ( $in_date < strtotime( '-1 days' ) ) {
            return '03'; // Less than 30 days
        }
        else {
            return '02'; // Created during this transaction
        }
    }

    private static function get_acctInfo( $order, $params ) {
        $acctInfo = array();
        $chAccAgeInd = '01'; // default: No account (guest check-out)
        $paymentAccInd = '01'; // default: No account (guest check-out)
        $cardholderID = get_current_user_id();

        if ( !empty( $cardholderID ) ) {

            $user_data = get_userdata( $cardholderID );
            if ( !empty( $user_data->user_registered ) ) {
                $registered_date = strtotime( $user_data->user_registered );
                $chAccAgeInd = self::get_indicative_date( $registered_date );

                // Date that the cardholder opened the account with the 3DS Requestor
                $acctInfo['chAccDate'] = date( 'Ymd', $registered_date );
            }

            // Number of purchases during the previous six months
            $acctInfo['nbPurchaseAccount'] = self::get_nbPurchaseAccount( true, MONTH_IN_SECONDS*6 );

            // Number of transactions (successful and abandoned) in the previous 24 hours
            $acctInfo['txnActivityDay'] = self::get_nbPurchaseAccount( false, DAY_IN_SECONDS );

            // Number of transactions (successful and abandoned) in the previous year
            $acctInfo['txnActivityYear'] = self::get_nbPurchaseAccount( false, YEAR_IN_SECONDS );

            // We added a timestamp alongside a user saved card to determinate when the card was first enrolled.
            if ( !empty( $params->tokenValue ) ) {
                $cards = get_user_meta( $cardholderID, GESTPAY_META_TOKEN, true );

                if ( !empty( $cards ) && !empty( $cards[$params->tokenValue] ) ) {
                    $card = $cards[$params->tokenValue];

                    if ( !empty( $card['timestamp'] ) ) {
                        // Overwrite the default (no account) with the indicative value of time
                        $paymentAccInd = self::get_indicative_date( $card['timestamp'] );

                        // Here we can set the right date
                        $acctInfo['paymentAccAge'] = date( 'Ymd', $card['timestamp'] );
                    }
                }
            }
        }

        // Date that the cardholder's account was last changed
        $acctInfo['chAccAgeInd'] = $chAccAgeInd;

        // Date that the payment account was enrolled in the cardholder's account.
        $acctInfo['paymentAccInd'] = $paymentAccInd;

        $last_update = get_user_meta( $cardholderID, 'last_update', true );
        if ( !empty( $last_update ) ) {
            // Length of time since the cardholder's account information was last changed
            $acctInfo['chAccChange'] = date( 'Ymd', $last_update );
        }

        // Indicates if the Cardholder Name on the account is identical to the shipping Name used for this transaction
        $acctInfo['shipNameIndicator'] = '02'; // default "different"
        if ( !empty( $params->buyerName ) ) {
            $buyer_name = strtolower( $params->buyerName );
            $slname = strtolower( $order->get_shipping_last_name() );
            $sfname = strtolower( $order->get_shipping_first_name() );

            if ( !empty( $slname ) && strpos( $buyer_name, $slname ) !== false
                    && !empty( $sfname ) && strpos( $buyer_name, $sfname ) !== false ) {
                $acctInfo['shipNameIndicator'] = '01'; // identical
            }
        }

        // - @N/A - chAccPwChange // Date that cardholder's account had a password change or account reset
        // - @N/A - chAccPwChangeInd // Length of time for chAccPwChange
        // - @N/A - provisionAttemptsDay // Number of Add Card attempts in the last 24 hours.
        // - @N/A - shipAddressUsage // Date when the shipping address used for this transaction was first used
        // - @N/A - shipAddressUsageInd // Length of time for shipAddressUsage
        // - @N/A - suspiciousAccActivity // Indicates whether the 3DS Requestor has experienced suspicious activity (including previous fraud) on the cardholder account

        return $acctInfo;
    }

    private static function get_threeDsContainer( $order, &$params, $ws_type ) {
        $baddr = self::get_billingAddress( $order );
        $saddr = self::get_shippingAddress( $order );

        if ( empty( $baddr ) || empty( $saddr ) ) {
            $addrMatch = '';
        }
        else {
            $addrMatch = $baddr == $saddr ? 'Y' : 'N';
        }

        $threeDsContainer = array(
            // - @N/A - transTypeReq // used in case of third party Acquirer: P for Payment (default); A for authentication only
            // - @N/A - acquirerBIN // required if transTypeReq value is A. Acquiring institution identification code as assigned by the DS receiving the AReq message
            // - @N/A - acquirerMerchantID // required if transTypeReq value is A. Acquirer-assigned Merchant identifier
            // - @N/A - exemption // Challenge management exemption. Values: SKIP | FORCE

            'buyerDetails' => array(
                // Contains optional information about the merchant authenticated the cardholder before or during the transaction.
                'profileDetails' => self::get_profileDetails(),

                // Indicates whether the Cardholder Shipping Address and Cardholder Billing Address are the same
                'addrMatch' => $addrMatch,

                // Additional information about the Cardholder's account provided by the 3DS Requestor
                'acctInfo' => self::get_acctInfo( $order, $params ),
            ),

            // Merchant's assessment of the level of fraud risk for the specific authentication
            // for both the cardholder and the authentication being conducted.
            // 'merchantRiskIndicator' => array(
                // - @N/A - giftCardAmount
                // - @N/A - giftCardCount
                // - @N/A - giftCardCurr
                // - @N/A - preOrderDate
                // - @N/A - preOrderPurchaseInd
                // - @N/A - reorderItemsInd
                // - @N/A - shipIndicator
            // )
        );

        // Cardholder informations
        $threeDsContainer['buyerDetails']['cardholder'] = self::get_cardholder( $params, $order );

        if ( !$order->needs_processing() ) { // Electronic Delivery
            $threeDsContainer['merchantRiskIndicator']['deliveryEmailAddress'] = substr( $order->get_billing_email() , 0, 254 );
            $threeDsContainer['merchantRiskIndicator']['deliveryTimeframe'] = '01';
        }

        if ( !empty( $baddr ) ) { // Add if there are some values
            $threeDsContainer['buyerDetails']['billingAddress'] = $baddr;
        }

        if ( !empty( $saddr ) ) { // Add if there are some values
            $threeDsContainer['buyerDetails']['shippingAddress'] = $saddr;
        }

        return $threeDsContainer;
    }

    private static function get_OrderDetails( $order ) {
        $OrderDetails = array(
            'CustomerDetail' => array(
                'FirstName' => $order->get_billing_first_name(),
                'LastName' => $order->get_billing_last_name(),
                'PrimaryEmail' => $order->get_billing_email()
            ),
            'ShippingAddress' => array(
                'FirstName' => $order->get_shipping_first_name(),
                'LastName' => $order->get_shipping_last_name(),
                'StreetName' => $order->get_shipping_address_1(),
                'City' => $order->get_shipping_city(),
                'ZipCode' => $order->get_shipping_postcode(),
                'StateCode' => $order->get_shipping_state(),
                'CountryCode' => $order->get_shipping_country(),
            ),
            'BillingAddress' => array(
                'FirstName' => $order->get_billing_first_name(),
                'LastName' => $order->get_billing_last_name(),
                'StreetName' => $order->get_billing_address_1(),
                'City' => $order->get_billing_city(),
                'ZipCode' => $order->get_billing_postcode(),
                'StateCode' => $order->get_billing_state(),
                'CountryCode' => $order->get_billing_country(),
            )
        );

        return $OrderDetails;
    }

    private static function get_ShippingDetails( $order ) {
        $shippingDetails = array(
            'shipToName' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
            'shipToStreet' => $order->get_shipping_address_1(),
            'shipToCity' => $order->get_shipping_city(),
            'shipToState' => $order->get_shipping_state(),
            'shipToCountryCode' => $order->get_shipping_country(),
            'shipToZip' => $order->get_shipping_postcode(),
        );
        return $shippingDetails;       
    }


    private static function get_cardholder( &$params, $order ) {
        $chname = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();

        // $params->buyerName can be altered with the credit card field name when using S2S payment
        // In this case switch the value.
        if ( !empty( $params->buyerName ) && $chname != $params->buyerName ) {
            $buyer_name = $chname;
            $chname = $params->buyerName;
            $params->buyerName = $buyer_name;
        }

        return array(
            'Name' => substr( $chname, 0, 45 ),
            'Email' => substr( $order->get_billing_email(), 0, 254 ),
            // - @N/A - homePhone_cc
            // - @N/A - homePhone_num
            // - @N/A - mobilePhone_cc
            // - @N/A - mobilePhone_num
            // - @N/A - workPhone_cc
            // - @N/A - workPhone_num
        );
    }


    /**
     * Try to retrieve the bankTransactionID from the last successful order of the current customer.
     * If the order is 01N (scheduled recurring payment) it must refer to the one of the parent order (01F)
     */
    private static function get_bankTransactionID( $order, $scheduled_payment ) {
        if ( $scheduled_payment && function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order ) ) {
            $parent_order_id = WC_Subscriptions_Renewal_Order::get_parent_order_id( $order );

            $bt_id = false;
            if ( !empty( $parent_order_id ) ) {
                $bt_id = get_post_meta( $parent_order_id, GESTPAY_ORDER_META_BANK_TID, true );
            }

            if ( empty( $bt_id ) ) {
                $bt_id = GESTPAY_BANK_TRANSACTION_ID_01N_UNKNOWN; // unknown parent
            }

            return $bt_id;
        }

        // Try to retrieve the last BankTransactionID from the user meta
        $cardholderID = get_current_user_id();
        if ( !empty( $cardholderID ) ) {
            $BankTransactionID = get_user_meta( $cardholderID, '_gesp3ds2_' . strtolower( 'BankTransactionID' ), true );
            if ( !empty( $BankTransactionID ) ) {
                return $BankTransactionID;
            }
        }

        // if not present the user meta, try to retrieve from the last order
        $last_order_id = self::get_nbPurchaseAccount( true, 0, 1, 'ids', 'DESC' );
        if ( !empty( $last_order_id ) && !empty( $last_order_id[0] ) ) {
            return get_post_meta( $last_order_id[0], GESTPAY_ORDER_META_BANK_TID, true );
        }

        return false;
    }


    private static function get_subscriptionInfo( $order ) {
        // Check if WC Subscriptions exists and the current order is a subscription order (parent, renewal or card change)
        $is_request_to_change_payment = function_exists( 'wcs_is_subscription' ) && wcs_is_subscription( $order );
        $order_contains_subscription = function_exists( 'wcs_order_contains_subscription' ) && wcs_order_contains_subscription( $order, array( 'order_type' => 'any' ) );
        if ( !$order_contains_subscription && !$is_request_to_change_payment ) {
            return false;
        }

        $expiries = array();
        $max_int = 2147483647;
        $min_frequency = $max_int;

        if ( wcs_order_contains_renewal( $order ) ) {
            $parent_order_id = WC_Subscriptions_Renewal_Order::get_parent_order_id( $order );
            $order = wc_get_order( $parent_order_id );
        }

        if ( wcs_is_subscription( $order ) ) {
            $subscription = wcs_get_subscription( $order );
            if ( !empty( $subscription ) ) {
                $subscriptions = array( $subscription );
            }
        }
        else {
            $subscriptions = wcs_get_subscriptions_for_order( $order, array( 'order_type' => 'parent' ) );
        }

        // An order can have one or more subscriptions
        if ( !empty( $subscriptions ) ) {
            foreach ( $subscriptions as $sub ) {
                // For each of them get the end time (if any). Get the maximun time in the return.
                $end_time = $sub->get_time( 'end' );
                if ( !empty( $end_time ) ) {
                    $expiries[$end_time] = gmdate( 'Ymd', $end_time );
                }
                else {
                    // If there is no end (infinite subs) set it to the future because it is mandatory
                    // to have an end date of a subscription.
                    $expiries[$max_int] = '20380119';
                }

                // Next we need the minumum frequency of payment (in days): to do that we have to retrieve
                // the billing period and interval defined on WCSubscriptions
                $period = $sub->get_billing_period();
                $interval = $sub->get_billing_interval();
                $days_in_cycle = 0;

                switch ( $period ) {
                    case 'day':
                        $days_in_cycle = $interval;
                        break;
                    case 'week':
                        $days_in_cycle = $interval * 7;
                        break;
                    case 'month':
                        $days_in_cycle = $interval * 30;
                        break;
                    case 'year':
                        $days_in_cycle = $interval * 365;
                        break;
                }

                if ( $days_in_cycle > 0 && $days_in_cycle < $min_frequency ) {
                    $min_frequency = $days_in_cycle;
                }
            }
        }

        // Return the maximum exp date and the minimum frequency
        return array(
            'expiry' => !empty( $expiries ) ? max( $expiries ) : false,
            'frequency' => $min_frequency < $max_int ? $min_frequency : false
        );
    }

    private static function maybe_add_user_transDetails( &$in_arr, $user_id, $user_meta ) {
        if ( !empty( $user_id ) ) { // user can be anonymous
            $meta_value = get_user_meta( $user_id, '_gesp3ds2_' . strtolower( $user_meta ), true );
            if ( !empty( $meta_value ) ) {
                $in_arr[$user_meta] = $meta_value;
            }
        }
    }

    private static function maybe_set_user_meta( $xml_response, $user_id, $meta_key ) {
        if ( !empty( $xml_response->{$meta_key} ) ) {
            update_user_meta( $user_id, '_gesp3ds2_' . strtolower( $meta_key ), (string)$xml_response->{$meta_key} );
        }
    }

    public static function maybe_set_3DS2_metas( $xml_response, $order_id ) {
        if ( empty( $xml_response->ThreeDS ) ) {
            return;
        }

        $user_id = get_current_user_id();
        if ( !empty( $user_id ) && !empty( $xml_response->ThreeDS->transDetails ) ) {
            self::maybe_set_user_meta( $xml_response->ThreeDS->transDetails, $user_id, 'authData' );
            self::maybe_set_user_meta( $xml_response->ThreeDS->transDetails, $user_id, 'authMethod' );
            self::maybe_set_user_meta( $xml_response->ThreeDS->transDetails, $user_id, 'authTimestamp' );
            self::maybe_set_user_meta( $xml_response->ThreeDS->transDetails, $user_id, 'acsID' );

            // Also store the last bank transaction ID to speed up retrieving.
            self::maybe_set_user_meta( $xml_response, $user_id, 'BankTransactionID' );
        }
    }

    /**
     * Add 3DS 2.0 parameters to the request.
     *
     * @param $params- Array of request informations.
     * @param $order_id - The ID of the order currently processed.
     * @param $ws_type - String 'WsS2S' | 'WSCryptDecrypt'
     */
    public static function add_3ds2_params( &$params, $order_id, $ws_type = 'WsS2S', $scheduled_payment = false ) {

        $order = wc_get_order( $order_id );

        // Base informations for 3DS 2.0 authentication
        $params->transDetails = array();
        $params->OrderDetails = array();
        // Paypal informations, they can always be added even if not paypal
        $params->shippingDetails = array();

        // --- #threeDsContainer ---
        // Container of additional data defined in 3DS2 protocol, that better define the transaction.
        // The data here included will be used for assessing the risk level of the transaction with the purpose
        // of ensuring security without affecting the buyer's experience. In particular these fields are used
        // from 3ds server to establish if the transaction could be a fraud and require or not the SCA.
        $params->transDetails['threeDsContainer'] = self::get_threeDsContainer( $order, $params, $ws_type );
        
        // --- #type ---
        // Transaction type. If not evaluated, the Axerve E-commerce Solutions rule is applied.
        $type = self::get_trans_type( $params, $order, $ws_type, $scheduled_payment );
        if ( !empty( $type ) ) {
            $params->transDetails['type'] = $type;
        }

        // --- #recurringTransaction ---
        // Maybe add informations necessary in case of recurring payment
        $sinfo = self::get_subscriptionInfo( $order );
        if ( !empty( $sinfo['expiry'] ) && !empty( $sinfo['frequency'] ) ) {
            // Required for Recurring Payments
            $params->transDetails['recurringTransaction'] = array(
                // Date after which no further transaction shall be performed
                'expiry' => $sinfo['expiry'],

                // It indicates the minimum number of days between two transactions of the same plan
                'frequency' => $sinfo['frequency'],

                // installNo - Required in case of installment payments. It indicates the maximum number of allowed transactions
                // there are no default installments under WooCommerce, just recurring payments with WC Subscriptions. Maybe using Consel?
                // - @N/A -
            );
        }

        // --- #previousTransDetails ---
        // Contains the details of a previous authenticated transaction for the same cardholder on the same merchant.
        // If present, it can help to increase the chances of a frictionless authentication.
        $previousTransDetails = array();

        $bankTransactionID = self::get_bankTransactionID( $order, $scheduled_payment );
        if ( !empty( $bankTransactionID ) ) {
            $previousTransDetails['bankTransactionID'] = $bankTransactionID;
        }

        if ( $ws_type == 'WsS2S' ) { // These are only for WsS2S API
            $cardholderID = get_current_user_id();
            self::maybe_add_user_transDetails( $previousTransDetails, $cardholderID, 'authData' );
            self::maybe_add_user_transDetails( $previousTransDetails, $cardholderID, 'authMethod' );
            self::maybe_add_user_transDetails( $previousTransDetails, $cardholderID, 'authTimestamp' );
            self::maybe_add_user_transDetails( $previousTransDetails, $cardholderID, 'acsID' );
            // - @N/A - XID
        }

        if ( !empty( $previousTransDetails ) ) {
            $params->transDetails['previousTransDetails'] = $previousTransDetails;
        }

        $params->OrderDetails = self::get_OrderDetails( $order );

        if ("yes" === get_option( 'wc_gestpay_param_seller_protection' )){
            $params->ppSellerProtection = 1;
            $params->shippingDetails = self::get_ShippingDetails( $order );
        }
    }
}