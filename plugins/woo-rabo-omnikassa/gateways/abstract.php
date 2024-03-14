<?php

use Automattic\WooCommerce\StoreApi\Exceptions\RouteException;

class icwoorok2_abstract extends WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id = $this->getPaymentCode();
        $this->icon = $this->getIcon();
        $this->has_fields = true;
        $this->method_title = $this->getPaymentName().$this->getLabel();
        $this->method_description = sprintf(__('Enable this method to receive transactions with Rabo Smart Pay - %s ', 'ic-woo-rabo-omnikassa-2'), $this->getPaymentName());

        $this->supports = ['products'];

        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');

        add_action('woocommerce_update_options_payment_gateways_'.$this->id, [$this, 'process_admin_options']);
    }

    public function getAccessToken()
    {
        $sCacheFile = false;
        $sCachePath = ICWOOROK_ROOT_PATH.'temp'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR;

        // Used cached access token?
        if ($sCachePath) {
            $sStoreHost = md5($_SERVER['SERVER_NAME']);
            $sCacheFile = $sCachePath.'token.'.$sStoreHost.'.cache';

            if (!file_exists($sCacheFile)) {
                // Attempt to create cache file
                if (@touch($sCacheFile)) {
                    @chmod($sCacheFile, 0600);
                }
            } elseif (is_readable($sCacheFile) && is_writable($sCacheFile)) {
                // Read data from cache file
                if ($sData = file_get_contents($sCacheFile)) {
                    $aToken = json_decode($sData, true);

                    // Get current time to compare expiration of the access token
                    $sCurrentTimestamp = time();

                    if (isset($aToken['validUntil'])) {
                        // Change the valid until ISO notation to UNIX timestamp
                        $sExpirationTimestamp = strtotime($aToken['validUntil']);

                        if ($sCurrentTimestamp <= $sExpirationTimestamp) {
                            return $aToken['token'];
                        }
                    }
                }
            } else {
                $sCacheFile = false;
            }
        }

        $sApiUrl = 'https://betalen.rabobank.nl/omnikassa-api'.($this->getSandboxMode() ? '-sandbox' : '').'/gatekeeper/refresh';

        $aArguments = [];
        $aArguments['body'] = '';
        $aArguments['timeout'] = '30';
        $aArguments['redirection'] = '5';
        $aArguments['httpversion'] = '1.1';
        $aArguments['blocking'] = true;
        $aArguments['headers'] = ['Expect' => '', 'Authorization' => 'Bearer '.$this->getRefreshToken()];
        $aArguments['cookies'] = [];

        $oResponse = wp_remote_get($sApiUrl, $aArguments);
        $sResponse = wp_remote_retrieve_body($oResponse);

        if (!empty($sResponse)) {
            $aToken = json_decode($sResponse, true);

            if (is_array($aToken) && sizeof($aToken)) {
                if (!empty($aToken['errorCode']) && !empty($aToken['errorMessage'])) {
                    return '';
                } else {
                    // Save data in cache?
                    if ($sCacheFile) {
                        file_put_contents($sCacheFile, json_encode($aToken, JSON_PRETTY_PRINT));
                    }

                    return $aToken['token'];
                }
            } else {
                if ($this->getSandboxMode()) {
                    throw new RouteException('woocommerce_rest_checkout_process_payment_error', __('Invalid response received from Rabo Smart Pay!', 'ic-woo-rabo-omnikassa-2'), 402 );
                }
            }
        } else {
            throw new RouteException('woocommerce_rest_checkout_process_payment_error', __('Accesstoken could not be generated, please check the configuration.', 'ic-woo-rabo-omnikassa-2'), 402 );
        }
        
        throw new RouteException('woocommerce_rest_checkout_process_payment_error', __('Accesstoken could not be generated, please check the configuration.', 'ic-woo-rabo-omnikassa-2'), 402 );
    }

    public function getIcon()
    {
        $sGatewayCode = $this->getPaymentCode();

        if (file_exists(ICWOOROK_ROOT_PATH.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$sGatewayCode.'.svg')) {
            return plugins_url('assets/images/'.$sGatewayCode.'.svg', dirname(__FILE__));
        } elseif (file_exists(ICWOOROK_ROOT_PATH.'assets'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$sGatewayCode.'.png')) {
            return plugins_url('assets/images/'.$sGatewayCode.'.png', dirname(__FILE__));
        } else {
            return '';
        }
    }

    public function getLabel()
    {
        return __(' via Rabo Smart Pay', 'ic-woo-rabo-omnikassa-2');
    }

    public function getPaymentCode()
    {
        throw new Exception('Forgot the getPaymentCode method for this payment method?');
    }

    public function getPaymentName()
    {
        throw new Exception('Forgot the getPaymentName method for this payment method?');
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRefreshToken()
    {
        return get_option('icwoorok2_refresh_token');
    }

    public function getSandboxMode()
    {
        $sSandboxMode = get_option('icwoorok2_sandbox');

        if (strcmp($sSandboxMode, 'yes') === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getSigningKey()
    {
        return get_option('icwoorok2_signing_key');
    }

    /* Class functions */
    public function init_form_fields()
    {
        $this->form_fields = [];

        $this->form_fields['docs'] = [
            'title' => __('Rabo Smart Pay Documentation', 'ic-woo-rabo-omnikassa-2'),
            'type' => 'title',
            'description' => '<a href="https://www.rabobank.nl/bedrijven/betalen/klanten-laten-betalen/rabo-omnikassa-2-0/support-overzicht/rabo-onlinekassa-support/" target="_blank">'.__('Go to documentation', 'ic-woo-rabo-omnikassa-2').'</a>.',
            ];

        $this->form_fields['enabled'] = [
                'title' => __('Enable/Disable', 'ic-woo-rabo-omnikassa-2'),
                'type' => 'checkbox',
                'label' => __('Enable Rabo Smart Pay', 'ic-woo-rabo-omnikassa-2').' - '.$this->getPaymentName(),
                'default' => 'no',
            ];

        $this->form_fields['title'] = [
                'title' => __('Title', 'ic-woo-rabo-omnikassa-2'),
                'type' => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'ic-woo-rabo-omnikassa-2'),
                'default' => $this->getPaymentName(),
                'desc_tip' => true,
            ];

        $this->form_fields['description'] = [
                'title' => __('Customer Message after payment select', 'ic-woo-rabo-omnikassa-2'),
                'type' => 'textarea',
                'default' => 'Pay with '.$this->getPaymentName(),
            ];

        $this->form_fields['description_bankaccount'] = [
                'title' => __('Description on receipt', 'ic-woo-rabo-omnikassa-2'),
                'type' => 'textarea',
                'default' => '',
            ];
    }

    public function payment_fields()
    {
        $sPaymentDescription = $this->get_option('description');

        echo $sPaymentDescription;
    }

    public function process_payment($sOrderId)
    {
        global $wpdb;
        global $woocommerce;

        $oOrder = wc_get_order($sOrderId);

        if (version_compare($woocommerce->version, '3.0', '>=')) {
            // Get all order related data
            $aOrderData = $oOrder->get_data();

            $sOrderNumber = $aOrderData['number'];
            $sDescription = $this->get_option('description_bankaccount');

            // Bank charge description empty, fallback
            if (empty($sDescription)) {
                $sDescription = 'Bestelling '.$sOrderNumber;
            }

            // Order amount in Cents
            $fOrderAmount = round($aOrderData['total'] * 100);
            $sCurrencyCode = $aOrderData['currency'];

            if (!in_array($sCurrencyCode, ['EUR'])) {
                // Currency is not EUR, fallback to EUR and log?
                $sCurrencyCode = 'EUR';
            }

            $sLanguageCode = get_bloginfo('language');

            if (!in_array($sLanguageCode, ['nl', 'en', 'fr', 'de'])) {
                $sLanguageCode = 'en';
            }
        } elseif (version_compare($woocommerce->version, '2.0', '>=')) {
            $sOrderNumber = $oOrder->get_order_number();
            $sDescription = $this->get_option('description_bankaccount');

            // Bank charge description empty, fallback
            if (empty($sDescription)) {
                $sDescription = 'Bestelling '.$sOrderNumber;
            }

            // Order amount in Cents
            $fOrderAmount = round($oOrder->get_total() * 100);
            $sCurrencyCode = $oOrder->order_currency;

            if (!in_array($sCurrencyCode, ['EUR'])) {
                // Currency is not EUR, fallback to EUR and log?
                $sCurrencyCode = 'EUR';
            }

            $sLanguageCode = get_bloginfo('language');

            if (!in_array($sLanguageCode, ['nl', 'en', 'fr', 'de'])) {
                $sLanguageCode = 'en';
            }
        }

        $sAccessToken = $this->getAccessToken();

        $sReturnUrl = add_query_arg('wc-api', 'Wc_Omnikassa_Gateway_Return', home_url('/'));

        $sCancelUrl = $oOrder->get_cancel_order_url();
        $sNotifyUrl = add_query_arg('wc-api', 'Wc_Omnikassa_Gateway_Notify', home_url('/'));

        // Setup message for the order announcement
        $aRequest['timestamp'] = date('c', time());
        $aRequest['merchantOrderId'] = $oOrder->get_id();
        $aRequest['amount'] = [];
        $aRequest['amount']['currency'] = $sCurrencyCode;
        $aRequest['amount']['amount'] = $fOrderAmount;
        $aRequest['language'] = strtoupper($sLanguageCode);
        $aRequest['description'] = $sDescription;
        $aRequest['merchantReturnURL'] = $sReturnUrl;
        $aRequest['paymentBrand'] = strtoupper(substr($this->getPaymentCode(), 10));
        $aRequest['paymentBrandForce'] = 'FORCE_ONCE';

        // Customer Name
        $aRequest['customerInformation']['fullName'] = $aOrderData['billing']['first_name'].' '.$aOrderData['billing']['last_name'];

        if (!$this->getSandboxMode()) {
            $aRequest['skipHppResultPage'] = 'true';
        }

        $sApiUrl = 'https://betalen.rabobank.nl/omnikassa-api'.($this->getSandboxMode() ? '-sandbox' : '').'/order/server/api/v2/order';

        $aArguments = [];
        $aArguments['body'] = json_encode($aRequest);
        $aArguments['timeout'] = '10';
        $aArguments['redirection'] = '5';
        $aArguments['httpversion'] = '1.1';
        $aArguments['blocking'] = true;
        $aArguments['headers'] = ['Expect' => '', 'Content-Type' => 'application/json', 'Authorization' => 'Bearer '.$sAccessToken];
        $aArguments['cookies'] = [];

        $oResponse = wp_remote_post($sApiUrl, $aArguments);
        $sResponse = wp_remote_retrieve_body($oResponse);


        if (!empty($sResponse)) {
            $aTransaction = json_decode($sResponse, true);

            if (is_array($aTransaction) && !empty($aTransaction)) {
                if (isset($aTransaction['omnikassaOrderId']) && isset($aTransaction['redirectUrl'])) {
                    // Add note for chosen method:
                    $oOrder->add_order_note(__('Rabo Smart Pay payment started with:', 'ic-woo-rabo-omnikassa-2').$this->getPaymentName().'<br>'.__('For amount: ', 'ic-woo-rabo-omnikassa-2').$oOrder->get_total());
                    $oOrder->save();

                    return ['result' => 'success', 'redirect' => $aTransaction['redirectUrl']];
                    
                } elseif (isset($aTransaction['errorCode'])) {
                    $sErrorCode = $aTransaction['errorCode'];
                    $sErrorMessage = $aTransaction['errorMessage'];

                    // Check if we have a consumer message
                    if (isset($aTransaction['consumerMessage'])) {
                        $sConsumerMessage = $aTransaction['consumerMessage'];
                    } else {
                        $sConsumerMessage = $sErrorMessage;
                    }

                    // $oOrder->cancel_order('Rabo Smart Pay fout: ' . $sErrorCode. ' : ' . $sErrorMessage);
                    $oOrder->add_order_note(__('Smart Pay returned an error! Code:', 'ic-woo-rabo-omnikassa-2').$sErrorCode.' with message: <br>'.$sErrorMessage.'<br>'.__('Check our FAQ:', 'ic-woo-rabo-omnikassa-2').' <a href="https://www.ideal-checkout.nl/faq-ic/payment-providers/rabo-omnikassa-2-0/rok-2-error-codes" target="_blank">'.__('Rabo Smart Pay Error codes', 'ic-woo-rabo-omnikassa-2').'</a>');

                    
                    throw new RouteException( 'woocommerce_rest_checkout_process_payment_error', $sConsumerMessage, 402 );
                }
            } else {
                
                $oOrder->add_order_note('Order announcement could not be decoded, something wrong with the data received?');

                throw new RouteException( 'woocommerce_rest_checkout_process_payment_error', __('Something went wrong, please try again!', 'ic-woo-rabo-omnikassa-2'), 402 );
            
            }
        } else {
            $oOrder->add_order_note(__('No response received from the Rabobank.', 'ic-woo-rabo-omnikassa-2'));
            
            throw new RouteException('woocommerce_rest_checkout_process_payment_error', __('No response received from the Rabobank.', 'ic-woo-rabo-omnikassa-2'), 402 );
        }
    }
}
