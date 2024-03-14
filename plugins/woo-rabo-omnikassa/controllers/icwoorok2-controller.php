<?php

class Icwoorok2Controller
{
    private static $bInitiated = false;

    public static function init()
    {
        if (self::$bInitiated) {
            return;
        }

        // Load settings
        add_filter('woocommerce_payment_gateways_settings', [__CLASS__, 'addPspSettings']);

        // Set hooks for the return and webhook call
        add_action('woocommerce_api_wc_omnikassa_gateway_return', [__CLASS__, 'doReturn']);
        add_action('woocommerce_api_wc_omnikassa_gateway_notify', [__CLASS__, 'doNotify']);

        // Mark plugin initiated
        self::$bInitiated = true;
    }

    public static function addPspSettings($aSettings)
    {
        $sWebhookUrl = add_query_arg('wc-api', 'Wc_Omnikassa_Gateway_Notify', home_url('/'));

        $aAddedSettings = [];
        $aAddedSettings[] = [
            'name' => __('Rabo Smart Pay - Merchant settings', 'ic-woo-rabo-omnikassa-2'),
            'type' => 'title',
            'desc' => __('IMPORTANT: To process transactions properly, please enter the following webhook URL on your Rabo Smart Pay dashboard:', 'ic-woo-rabo-omnikassa-2').' <a href="'.$sWebhookUrl.'">'.$sWebhookUrl.'</a></p>',
        ];
        $aAddedSettings[] = [
            'name' => __('Refresh Token', 'ic-woo-rabo-omnikassa-2'),
            'type' => 'textarea',
            'css' => 'height:200px;font-size:12px;',
            'desc' => '<p>'.__('The Refresh token can be found on the Rabo Smart Pay dashboard.', 'ic-woo-rabo-omnikassa-2').'</p>',
            'id' => 'icwoorok2_refresh_token',
        ];
        $aAddedSettings[] = [
            'name' => __('Signing Key', 'ic-woo-rabo-omnikassa-2'),
            'type' => 'text',
            'css' => 'font-size:12px;',
            'desc' => '<p>'.__('The Signing Key token can be found on the Rabo Smart Pay dashboard.', 'ic-woo-rabo-omnikassa-2').'</p>',
            'id' => 'icwoorok2_signing_key',
        ];
        $aAddedSettings[] = [
            'name' => __('Sandbox environment', 'ic-woo-rabo-omnikassa-2'),
            'type' => 'checkbox',
            'label' => __('Enable the Sandbox mode', 'ic-woo-rabo-omnikassa-2'),
            'default' => 'yes',
            'desc' => '<p>'.__('Please make sure you enter the sandbox credentials if checked.', 'ic-woo-rabo-omnikassa-2').'</p>',
            'id' => 'icwoorok2_sandbox',
        ];

        $aAddedSettings[] = [
            'type' => 'sectionend',
            'id' => 'icwoorok2_settings',
        ];

        return array_merge($aAddedSettings, $aSettings);
    }

    public static function doReturn()
    {
        global $woocommerce;

        if (empty($_GET['status']) && empty($_GET['order_id']) && empty($_GET['signature'])) {
            wp_redirect($woocommerce->cart->get_cart_url());
        } else {
            $sMerchantOrderId = sanitize_text_field($_GET['order_id']);
            $sOmniKassaStatus = sanitize_text_field($_GET['status']);
            $sOmniKassaSignature = sanitize_text_field($_GET['signature']);
            $bUtmOverride = array_key_exists('utm_nooverride', $_GET);

            $oOrder = wc_get_order($sMerchantOrderId);

            $sPaymentMethod = $oOrder->get_payment_method_title();

            $sHashString = $sMerchantOrderId.','.$sOmniKassaStatus;
            $sHash = hash_hmac('sha512', $sHashString, base64_decode(get_option('icwoorok2_signing_key')));

            if (hash_equals($sOmniKassaSignature, $sHash)) {
                if (strcmp($sOmniKassaStatus, 'COMPLETED') === 0) {
                    $sReturnUrl = $oOrder->get_checkout_order_received_url();

                    $aPaymentStatuses = wc_get_is_paid_statuses();
                    $aPaymentStatuses[] = 'refunded';

                    if (!in_array($oOrder->get_status(), $aPaymentStatuses)) {
                        $oOrder->add_order_note(__('Status received from Customer Return: ', 'ic-woo-rabo-omnikassa-2').$sOmniKassaStatus.'. '.__('Order updated, check Smart Pay dashboard for status before sending products', 'ic-woo-rabo-omnikassa-2').'. '.__('Payment-method: ', 'ic-woo-rabo-omnikassa-2').$sPaymentMethod);

                        $oOrder->payment_complete();
                    } else {
                        $oOrder->add_order_note(__('Status received from Customer Return:', 'ic-woo-rabo-omnikassa-2').$sOmniKassaStatus.'. '.__('Payment status already received: Order status not updated, check Smart Pay dashboard for status', 'ic-woo-rabo-omnikassa-2').'. '.__('Payment-method: ', 'ic-woo-rabo-omnikassa-2').$sPaymentMethod);
                    }

                    wp_redirect($sReturnUrl.($bUtmOverride ? '&utm_nooverride=1' : ''));
                } else {
                    $oOrder->add_order_note(__('Status received from Customer Return:', 'ic-woo-rabo-omnikassa-2').$sOmniKassaStatus.'. '.__('Order not updated, check Smart Pay dashboard for status', 'ic-woo-rabo-omnikassa-2').'. '.__('Payment-method: ', 'ic-woo-rabo-omnikassa-2').$sPaymentMethod);
                    wp_redirect(wc_get_cart_url().($bUtmOverride ? '&utm_nooverride=1' : ''));
                }
            } else {
                wp_redirect(wc_get_cart_url().($bUtmOverride ? '&utm_nooverride=1' : ''));
            }
        }
    }

    public static function doNotify()
    {
        global $woocommerce;

        // Build the notify from the Rabo Smart Pay
        $sJsonData = @file_get_contents('php://input');

        // Log the data
        // $sLogMessage = 'Rabo Smart Pay Notify: '.$sJsonData;
        // error_log($sLogMessage);

        if (empty($sJsonData)) {
            header('HTTP/1.1 400 Bad Request');
            exit;
        } else {
            $aPostData = json_decode($sJsonData, true);

            if (!empty($aPostData['authentication']) && !empty($aPostData['expiry']) && !empty($aPostData['eventName']) && !empty($aPostData['poiId']) && !empty($aPostData['signature'])) {
                $sAuthToken = sanitize_text_field($aPostData['authentication']);
                $sExpiryString = sanitize_text_field($aPostData['expiry']);
                $sEventnameString = sanitize_text_field($aPostData['eventName']);
                $sPoiIdString = sanitize_text_field($aPostData['poiId']);
                $sSignatureString = sanitize_text_field($aPostData['signature']);

                // Check the signature that was sent with the data
                $sHashString = $sAuthToken.','.$sExpiryString.','.$sEventnameString.','.$sPoiIdString;

                $sHash = hash_hmac('sha512', $sHashString, base64_decode(get_option('icwoorok2_signing_key')));

                if (hash_equals($sSignatureString, $sHash)) {
                    $sTestMode = get_option('icwoorok2_sandbox');
                    $bSandboxMode = (strcmp($sTestMode, 'yes') === 0);

                    $sApiUrl = 'https://betalen.rabobank.nl/omnikassa-api'.($bSandboxMode ? '-sandbox' : '').'/order/server/api/v2/events/results/merchant.order.status.changed';

                    $aResult = [];

                    do {
                        $aArguments = [];
                        $aArguments['body'] = '';
                        $aArguments['timeout'] = '30';
                        $aArguments['redirection'] = '5';
                        $aArguments['httpversion'] = '1.1';
                        $aArguments['blocking'] = true;
                        $aArguments['headers'] = ['Expect' => '', 'Authorization' => 'Bearer '.$sAuthToken];
                        $aArguments['cookies'] = [];

                        $oResponse = wp_remote_get($sApiUrl, $aArguments);
                        $sResponse = wp_remote_retrieve_body($oResponse);

                        // Log data
                        // $sLogMessage = 'Rabo Smart Pay Notify pull: '.$sResponse;
                        // error_log($sLogMessage);

                        $aResult = json_decode($sResponse, true);
                        if (!empty($aResult) && sizeof($aResult)) {
                            $sSignature = $aResult['signature'];
                            $bMoreOrders = $aResult['moreOrderResultsAvailable'];

                            if ($bMoreOrders) {
                                $sMoreOrdersAvailable = 'true';
                            } else {
                                $sMoreOrdersAvailable = 'false';
                            }

                            $sHashString = $sMoreOrdersAvailable.',';

                            // Validate total signature
                            foreach ($aResult['orderResults'] as $aRokResult) {
                                // Setup total hash string
                                $sTransactionString = $aRokResult['merchantOrderId'].','.$aRokResult['omnikassaOrderId'].','.$aRokResult['poiId'].','.$aRokResult['orderStatus'].','.$aRokResult['orderStatusDateTime'].','.$aRokResult['errorCode'].','.$aRokResult['paidAmount']['currency'].','.$aRokResult['paidAmount']['amount'].','.$aRokResult['totalAmount']['currency'].','.$aRokResult['totalAmount']['amount'];

                                // Add all transactions
                                foreach ($aRokResult['transactions'] as $aTransaction) {
                                    if (isset($aTransaction['confirmedAmount']) && isset($aTransaction['confirmedAmount']['currency']) && isset($aTransaction['confirmedAmount']['amount'])) {
                                        $sTransactionString .= ','.$aTransaction['id'].','.$aTransaction['paymentBrand'].','.$aTransaction['type'].','.$aTransaction['status'].','.$aTransaction['amount']['currency'].','.$aTransaction['amount']['amount'].','.$aTransaction['confirmedAmount']['currency'].','.$aTransaction['confirmedAmount']['amount'].','.$aTransaction['startTime'].','.$aTransaction['lastUpdateTime'];
                                    } else {
                                        $sTransactionString .= ','.$aTransaction['id'].','.$aTransaction['paymentBrand'].','.$aTransaction['type'].','.$aTransaction['status'].','.$aTransaction['amount']['currency'].','.$aTransaction['amount']['amount'].',,,'.$aTransaction['startTime'].','.$aTransaction['lastUpdateTime'];
                                    }
                                }

                                $sHashString .= $sTransactionString.',';
                            }

                            // Cut off last comma
                            $sHashString = substr($sHashString, 0, -1);
                            $sHash = hash_hmac('sha512', $sHashString, base64_decode(get_option('icwoorok2_signing_key')));

                            if (strcmp($sSignature, $sHash) === 0) {
                                foreach ($aResult['orderResults'] as $aRokResult) {
                                    $sMerchantOrderId = $aRokResult['merchantOrderId'];
                                    $sOmniKassaStatus = $aRokResult['orderStatus'];
                                    $aTransactionData = icwoorok2_getTransaction($aRokResult['transactions']);

                                    $sTransactionId = $aTransactionData['id'];
                                    $sTransactionMethod = $aTransactionData['paymentBrand'];

                                    // Get order by Merchant Order ID
                                    $oOrder = wc_get_order($sMerchantOrderId);
                                    $sOrderStatus = $oOrder->get_status();

                                    $sMessage = __('Status received from Rabo Smart Pay Webhook: ', 'ic-woo-rabo-omnikassa-2').$sOmniKassaStatus.__(' for transaction ID:', 'ic-woo-rabo-omnikassa-2', 'ic-woo-rabo-omnikassa-2').'<br>'.$sTransactionId;

                                    if (in_array($sOrderStatus, ['pending', 'failed', 'cancelled'])) {
                                        if (strcmp($sOmniKassaStatus, 'COMPLETED') === 0) {
                                            $aPaymentStatuses = wc_get_is_paid_statuses();
                                            $aPaymentStatuses[] = 'refunded';

                                            if (!in_array($sOrderStatus, $aPaymentStatuses)) {
                                                $sMessage .= '<br>'.__('Update order status: Payment Completed', 'ic-woo-rabo-omnikassa-2');
                                                $oOrder->add_order_note($sMessage);
                                                $oOrder->set_payment_method($sTransactionMethod);
                                                $oOrder->payment_complete($sTransactionId);
                                            } else {
                                                $sMessage .= '<br>'.__('Order status not updated: Payment status already received', 'ic-woo-rabo-omnikassa-2');
                                                $oOrder->add_order_note($sMessage);
                                            }
                                        } elseif (in_array($sOmniKassaStatus, ['CANCELLED', 'EXPIRED'])) {
                                            // Check if WooCommerce cancels automaticly for the stock management
                                            $iHoldStockMinutes = get_option('woocommerce_hold_stock_minutes');

                                            if (!empty($iHoldStockMinutes) && ($iHoldStockMinutes > 0)) {
                                                // Happens automaticly, we dont need to do anything
                                                $sMessage .= __('<br>Payment is cancelled or expired, but will be cancelled automaticly by WooCommerce.', 'ic-woo-rabo-omnikassa-2');
                                                $oOrder->add_order_note($sMessage);
                                            } else {
                                                if (strcmp($sOmniKassaStatus, 'EXPIRED') === 0) {
                                                    $sMessage .= __('<br>Update order status: Failed.', 'ic-woo-rabo-omnikassa-2');
                                                    $oOrder->add_order_note($sMessage);

                                                    $oOrder->update_status('failed');
                                                } elseif (strcmp($sOmniKassaStatus, 'CANCELLED') === 0) {
                                                    $sMessage .= __('<br>Update order status: Cancelled', 'ic-woo-rabo-omnikassa-2');
                                                    $oOrder->add_order_note($sMessage);

                                                    $oOrder->update_status('cancelled');
                                                } else {
                                                    // Possibly another status to be implemented?
                                                    $oOrder->add_order_note($sMessage);
                                                }
                                            }
                                        } else {
                                            // No final status
                                            $sMessage .= __('<br>No Final status has been found.', 'ic-woo-rabo-omnikassa-2');
                                            $oOrder->add_order_note($sMessage);
                                        }
                                    } else { // pending
                                        if ($sTransactionId && (strcmp($sOmniKassaStatus, 'COMPLETED') === 0)) {
                                            $sMessage .= __('<br>Transaction ID updated.', 'ic-woo-rabo-omnikassa-2');
                                            $oOrder->set_transaction_id($sTransactionId);
                                            $oOrder->save();
                                        } else {
                                            $sMessage .= __('<br>Order doesnt have the correct status to be changed by the payment method.', 'ic-woo-rabo-omnikassa-2');
                                        }

                                        $oOrder->add_order_note($sMessage);
                                        // This shouldn't happen
                                    }
                                }
                            }
                        }
                    } while ($bMoreOrders);
                } else {
                    header('HTTP/1.1 400 Bad Request');
                    exit;
                }
            } else {
                header('HTTP/1.1 400 Bad Request');
                exit;
            }
        }

        header('HTTP/1.1 200 OK');
        exit;
    }
}
