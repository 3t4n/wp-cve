<?php

if (!defined('ABSPATH'))
    exit;

class Express_Checkout_API_Response {

    public function ex_get_shipping_details($response) {
        $details = array();
        if (isset($response['FIRSTNAME'])) {
            $details = array(
                'first_name' => $response['FIRSTNAME'],
                'last_name' => isset($response['LASTNAME']) ? $response['LASTNAME'] : '',
                'company' => isset($response['BUSINESS']) ? $response['BUSINESS'] : '',
                'email' => isset($response['EMAIL']) ? $response['EMAIL'] : '',
                'phone' => isset($response['PHONENUM']) ? $response['PHONENUM'] : '',
                'address_1' => isset($response['SHIPTOSTREET']) ? $response['SHIPTOSTREET'] : '',
                'address_2' => isset($response['SHIPTOSTREET2']) ? $response['SHIPTOSTREET2'] : '',
                'city' => isset($response['SHIPTOCITY']) ? $response['SHIPTOCITY'] : '',
                'postcode' => isset($response['SHIPTOZIP']) ? $response['SHIPTOZIP'] : '',
                'country' => isset($response['SHIPTOCOUNTRYCODE']) ? $response['SHIPTOCOUNTRYCODE'] : '',
                'state' => (isset($response['SHIPTOCOUNTRYCODE']) && isset($response['SHIPTOSTATE'])) ? $this->ex_get_state_code($response['SHIPTOCOUNTRYCODE'], $response['SHIPTOSTATE']) : ''
            );
        }
        return $details;
    }

    public function ex_get_state_code($country_code, $state) {
        if ($country_code !== 'US' && isset(WC()->countries->states[$country_code])) {
            $local_states = WC()->countries->states[$country_code];
            if (!empty($local_states) && in_array($state, $local_states)) {
                foreach ($local_states as $key => $val) {
                    if ($val === $state) {
                        return $key;
                    }
                }
            }
        }
        return $state;
    }

    public function ex_get_note_text($response) {
        return isset($response['PAYMENTREQUEST_0_NOTETEXT']) ? $response['PAYMENTREQUEST_0_NOTETEXT'] : '';
    }

    public function ex_get_payer_id($response) {
        return isset($response['PAYERID']) ? $response['PAYERID'] : '';
    }

}
