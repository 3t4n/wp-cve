<?php


/**
 * Class that creates the request the URL with request params
 * that will be redirect the user to ESTO
 *
 * @author Mikk Mihkel Nurges
 */
class EstoRequest {

    protected $_plugin_text_domain = 'wc_esto_payment';

    /** @var WC_Settings_API */
    protected $wcSettings;

    /** @var WC_Order */
    protected $order;

    protected $mac;
    protected $json;

    public function __construct(WC_Settings_API $settings)
    {
        $this->settings = $settings;
        add_action( 'woocommerce_checkout_order_processed', array( $this, 'checkout_save_selected_bank' ), 10, 3);
    }

    public function setOrder(WC_Order $order)
    {
        $this->order = $order;
    }

    /**
     * Use the API v2 call, if CURL is enabled, as that will
     * create the purchase link and redirect the customer.
     * Otherwise use v1, which will use MAC for verifying
     * and is not as reliable
     */
    public function getRedirectBlock()
    {
        $title = $this->settings->get_option('title');

        if(function_exists('curl_version'))
        {
            $data = $this->getPurchaseData();
            $redirectUrl = $this->getPurchaseRedirect($data);

            $get_params_html = '';
            if ( $redirectUrl ) {
                woo_esto_log( 'redirectUrl: ' . $redirectUrl );

                $query_string = parse_url( $redirectUrl, PHP_URL_QUERY );

                if ( $query_string ) {
                    $get_params = [];
                    parse_str( $query_string, $get_params );

                    if ( ! empty( $get_params ) ) {
                        foreach ( $get_params as $key => $value ) {
                            if ( is_array( $value ) ) {
                                foreach ( $value as $value_child ) {
                                    $get_params_html .= '<input type="hidden" name="' . $key . '[]" value="' . $value_child . '">';
                                }
                            }
                            else {
                                $get_params_html .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
                            }
                        }
                    }
                }

                // woo_esto_log( 'get_params_html: ' . $get_params_html );
            }

            return '<form action="'.$redirectUrl.'" method="GET" id="esto_payment_form">'
                    . $get_params_html
                    . '<input type="submit" class="button-alt" id="esto_payment_submit" value="' . __($title, 'woo-esto' ) . '" />
                    </form>';
        }
        else
        {
            $destinationUrl = esto_get_api_url() . 'v1/' . 'purchase';

            return '<form action="' . htmlspecialchars($destinationUrl) . '" method="POST" id="esto_payment_form">
                    <input type="submit" class="button-alt" id="esto_payment_submit" value="' . __($title, 'woo-esto' ) . '" />
                    <input type="hidden" name="json" value="' . esc_attr($this->getJson()) . '" />
                    <input type="hidden" name="mac" value="' . esc_attr($this->getMAC()) . '" />
                    </form>';
        }
    }

    public function getPurchaseRedirect($data)
    {
        if ( is_callable( [ $this->order, 'get_meta' ] ) ) {
            $existing_purchase_url_timeout = $this->order->get_meta( 'woo_esto_purchase_url_timeout', true );
            if ( $existing_purchase_url_timeout && $existing_purchase_url_timeout > time() ) {
                $existing_purchase_url = $this->order->get_meta( 'woo_esto_purchase_url', true );
                $existing_purchase_url_amount = $this->order->get_meta( 'woo_esto_purchase_url_amount', true );
                $existing_purchase_url_id = $this->order->get_meta( 'woo_esto_purchase_url_id', true );
                if ( $existing_purchase_url && $existing_purchase_url_amount == $data['amount'] && $existing_purchase_url_id == $this->settings->id ) {
                    if ( isset( $data['payment_method_key'] ) ) {
                        $existing_payment_method_key = $this->order->get_meta( 'woo_esto_purchase_url_payment_method_key', true );
                        if ( $existing_payment_method_key == $data['payment_method_key'] ) {
                            return $existing_purchase_url;
                        }
                    }
                    else {
                        return $existing_purchase_url;
                    }
                }
            }
        }
        else {
            if ( is_callable( array( $this->order, 'get_id' ) ) ) {
                $order_post_id = $this->order->get_id();
            }
            else {
                $order_post_id = $this->order->id;
            }

            $existing_purchase_url_timeout = get_post_meta( $order_post_id, 'woo_esto_purchase_url_timeout', true );
            if ( $existing_purchase_url_timeout && $existing_purchase_url_timeout > time() ) {
                $existing_purchase_url = get_post_meta( $order_post_id, 'woo_esto_purchase_url', true );
                $existing_purchase_url_amount = get_post_meta( $order_post_id, 'woo_esto_purchase_url_amount', true );
                $existing_purchase_url_id = get_post_meta( $order_post_id, 'woo_esto_purchase_url_id', true );
                if ( $existing_purchase_url && $existing_purchase_url_amount == $data['amount'] && $existing_purchase_url_id == $this->settings->id ) {
                    if ( isset( $data['payment_method_key'] ) ) {
                        $existing_payment_method_key = get_post_meta( $order_post_id, 'woo_esto_purchase_url_payment_method_key', true );
                        if ( $existing_payment_method_key == $data['payment_method_key'] ) {
                            return $existing_purchase_url;
                        }
                    }
                    else {
                        return $existing_purchase_url;
                    }
                }
            }
        }

        $service = 'purchase';
        $method = 'POST';

        if ( isset( $data['payment_method_key'] ) && $this->settings->get_option( 'disable_bank_preselect_redirect' ) != 'yes' ) {
            $service .= '/redirect';
        }

        $response = $this->makeCall($service, $data, $method);

        woo_esto_log( 'purchase request: ' . $service . ' ' . print_r( $data, true ) );
        if ( ! isset( $response->purchase_url ) ) {
            woo_esto_log( 'purchase request response: ' . print_r( $response, true ) );
            // woo_esto_log( $this->settings->shop_id . ' : ' . $this->settings->secret_key );
        }

        if ( ! empty( $response->purchase_url ) ) {
            if ( is_callable( [ $this->order, 'update_meta_data' ] ) ) {
                $this->order->update_meta_data( 'woo_esto_purchase_url', $response->purchase_url );
                $this->order->update_meta_data( 'woo_esto_purchase_url_amount', $data['amount'] );
                $this->order->update_meta_data( 'woo_esto_purchase_url_id', $this->settings->id );
                $this->order->update_meta_data( 'woo_esto_purchase_url_timeout', time() + 60 );

                if ( isset( $data['payment_method_key'] ) ) {
                    $this->order->update_meta_data( 'woo_esto_purchase_url_payment_method_key', $data['payment_method_key'] );
                }

                $this->order->save();
            }
            else {
                update_post_meta( $order_post_id, 'woo_esto_purchase_url', $response->purchase_url );
                update_post_meta( $order_post_id, 'woo_esto_purchase_url_amount', $data['amount'] );
                update_post_meta( $order_post_id, 'woo_esto_purchase_url_id', $this->settings->id );
                update_post_meta( $order_post_id, 'woo_esto_purchase_url_timeout', time() + 60 );

                if ( isset( $data['payment_method_key'] ) ) {
                    update_post_meta( $order_post_id, 'woo_esto_purchase_url_payment_method_key', $data['payment_method_key'] );
                }
            }
        }

        return isset( $response->purchase_url ) ? $response->purchase_url : false;
    }

    private function makeCall($service, $data = [], $method = 'GET')
    {
        $url = esto_get_api_url() . 'v2/' . $service;

        if($method == 'GET') {
            $url .= '?' . http_build_query($data);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Basic auth
        $user = $this->settings->shop_id;
        $password = $this->settings->secret_key;
        
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $password);

        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $data = json_encode($data);

        switch ($method) {
            case 'GET':
                break;

            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;

            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        $response = curl_exec($ch);
        $response = json_decode($response);

        if(isset($response->data)) {
            return json_decode($response->data);
        } else {
            return $response;
        }
    }

    public function getMAC()
    {
        if( ! $this->mac)
        {
            $this->setJsonAndMAC();
        }
        return $this->mac;
    }

    public function getJson()
    {
        if( ! $this->json)
        {
            $this->setJsonAndMAC();
        }
        return $this->json;
    }

    /**
     * Callback from ESTO with the result
     */
    public function validateCallback($request)
    {
        $result = array(
            'amount'    => 0,
            'reference' => '',
            'status'    => WC_Esto_Payment::TRX_FAILED,
        );

        $paymentMessage = $request['json'];
        $mac = $request['mac'];

        if( ! $this->verifyMessage($paymentMessage, $mac))
        {
            return $result;
        }
        else
        {
            $paymentMessage = json_decode($paymentMessage);
            if($paymentMessage->shop_id != $this->settings->shop_id)
            {
                return $result;
            }

            if($paymentMessage->status == WC_Esto_Payment::TRX_APPROVED)
            {
                $result['status'] = WC_Esto_Payment::TRX_APPROVED;
                $result['reference'] = sanitize_text_field( $paymentMessage->reference );
                $result['amount']   = $paymentMessage->amount;
            }

            if ( $paymentMessage->status == WC_Esto_Payment::TRX_REJECTED ) {
                $result['status'] = WC_Esto_Payment::TRX_REJECTED;
            }

            $result['is_test'] = $paymentMessage->connection_mode == WC_Esto_Payment::MODE_TEST ? true : false;
            $result['auto'] = (bool)$paymentMessage->auto;
        }

        return $result;
    }

    protected function getPurchaseData()
    {
        if ( is_callable( array( $this->order, 'get_id' ) ) ) {
            $order_id = $this->order->get_id();
        }
        else {
            $order_id = $this->order->id;
        }

        $order_post_id = $order_id;

        if ( is_callable( array( $this->order, 'get_order_number' ) ) ) {
            $order_number = $this->order->get_order_number();

            if ( $order_number != $order_id ) {
                if ( is_callable( [ $this->order, 'update_meta_data'] ) ) {
                    $this->order->update_meta_data( 'esto_order_nr', $order_number );
                    $this->order->save();
                }
                else {
                    update_post_meta( $order_id, 'esto_order_nr', $order_number );
                }
                $order_id = $order_number;
            }
        }

        $order_prefix = $this->settings->get_option( 'order_prefix' );
        if ( $order_prefix ) {
            $order_id = $order_prefix . '-' . $order_id;
            if ( is_callable( [ $this->order, 'update_meta_data'] ) ) {
                $this->order->update_meta_data( 'esto_prefixed_order_id', $order_id );
                $this->order->save();
            }
            else {
                update_post_meta( $order_post_id, 'esto_prefixed_order_id', $order_id );
            }
        }

        $api_request_url = WC()->api_request_url( strtolower( get_class( $this->settings ) ) );
        // add country parameter, we need it for selecting the correct shop_id / secret_key when data returns
        $api_request_url = add_query_arg( 'esto_api_country_code', esto_get_country(), $api_request_url );

        // Polylang compatibility if they have default language use a directory in the permalink structure
        // the option "Hide URL language information for default language" is unchecked in Polylang settings
        if ( function_exists( 'pll_current_language' ) ) {
            $current_pll_lang = pll_current_language();
            if ( $current_pll_lang ) {
                if ( ! get_option( 'permalink_structure' ) ) {
                    if ( ! stristr( $api_request_url, 'language=' ) ) {
                        $api_request_url = add_query_arg( 'language', $current_pll_lang, $api_request_url );
                    }
                }
                else {
                    $lang_string = '/language/' . $current_pll_lang . '/wc-api/';
                    if ( ! stristr( $api_request_url,  $lang_string ) ) {
                        $api_request_url = str_replace( '/wc-api/', $lang_string, $api_request_url );
                    }
                }
            }
        }

        $data = [
            'shop_id'           => $this->settings->shop_id,
            'amount'            => $this->order->get_total(),
            'reference'         => (string) $order_id,
            'return_url'        => $api_request_url,
            'notification_url'  => $api_request_url,
            'connection_mode'   => $this->settings->connection_mode,
        ];

        if(method_exists($this->order, 'get_cancel_order_url_raw'))
        {
            $data['cancel_url'] = $this->order->get_cancel_order_url_raw();
        }

        if ( is_callable( [ $this->order, 'get_meta'] ) ) {
            $selected_bank = $this->order->get_meta( 'esto_preferred_bank', true );
        }
        else {
            $selected_bank = get_post_meta( $order_post_id, 'esto_preferred_bank', true );
        }

        if ( $selected_bank && $this->settings->get_option( 'disable_bank_preselect_redirect' ) != 'yes' ) {
            $data['payment_method_key'] = $selected_bank;
        }

        // card
        if ( ! empty( $this->settings->payment_method_key ) ) {
            $data['payment_method_key'] = $this->settings->payment_method_key;
        }

        // Get customer info
        if($this->_isWoo30())
        {
            $data['customer'] = [
                'first_name'    => $this->order->get_billing_first_name(),
                'last_name'     => $this->order->get_billing_last_name(),
                'email'         => $this->order->get_billing_email(),
                'phone'         => $this->order->get_billing_phone(),
                'address'       => $this->order->get_billing_address_1(),
                'city'          => $this->order->get_billing_city(),
                'post_code'     => $this->order->get_billing_postcode(),
            ];
        }
        else
        {
            $data['customer'] = [
                'first_name'    => $this->order->billing_first_name,
                'last_name'     => $this->order->billing_last_name,
                'email'         => $this->order->billing_email,
                'phone'         => $this->order->billing_phone,
                'address'       => $this->order->billing_address_1,
                'city'          => $this->order->billing_city,
                'post_code'     => $this->order->billing_postcode,
            ];
        }

        foreach($data['customer'] as $key => $val)
        {
            if(is_null($val) || $val === '')
            {
                unset($data['customer'][$key]);
            }
        }

        // Get basket items
        $items = [];
        foreach(WC()->cart->get_cart() as $cartItem)
        {
            $items[] = [
                'name'          => strlen($cartItem['data']->get_title()) > 0 ? $cartItem['data']->get_title() : "No title",
                'unit_price'    => ! empty( $cartItem['line_total'] ) ? ( $cartItem['line_total'] / $cartItem['quantity'] ) : $cartItem['data']->get_price(),
                'quantity'      => $cartItem['quantity'],
            ];
        }
        if(count($items))
        {
            $data['items'] = $items;
        }

        if ( ! empty( $this->settings->schedule_type ) ) {
            $data['schedule_type'] = $this->settings->schedule_type;
        }

        return $data;
    }

    /**
     * Compose the required MAC message from the secret
     * key and other payment attributes
     */
    protected function setJsonAndMAC()
    {
        $data = $this->getPurchaseData();
        $this->composeMAC($data);
    }

    protected function composeMAC(array $messageArray)
    {
        // Convert message to json
        $this->json = trim(json_encode($messageArray));
        $str = $this->json . $this->settings->secret_key;
        // Compose MAC
        $this->mac = strtoupper(hash('sha512', $str));
    }

    protected function verifyMessage($json, $mac)
    {
        if(is_array($json))
        {
            $json = trim(json_encode($json));
        }

        $json = $json . $this->settings->secret_key;

        return $mac === strtoupper(hash('sha512', $json));
    }

    function checkout_save_selected_bank( $order_id, $posted_data, $order ) {
        if ( isset( $_REQUEST['esto_pay_bank_selection'] ) ) {
            if ( is_callable( [ $order, 'update_meta_data'] ) ) {
                $order->update_meta_data( 'esto_preferred_bank', sanitize_text_field( $_REQUEST['esto_pay_bank_selection'] ) );
                $order->save();
            }
            else {
                update_post_meta( $order_id, 'esto_preferred_bank', sanitize_text_field( $_REQUEST['esto_pay_bank_selection'] ) );
            }
        }
    }

    /**
     * <p>Returns true, if WooCommerce version is 3.0 or greater</p>
     * @return bool
     */
    protected function _isWoo30() {
        if (defined('WOOCOMMERCE_VERSION')) {
            return version_compare(WOOCOMMERCE_VERSION, '3.0', '>=');
        }
        return version_compare($this->_getWooCommerce()->version, '3.0', '>=');
    }

    protected function _getWooCommerce() {
        global $woocommerce;
        return $woocommerce;
    }

}

