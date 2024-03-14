<?php
if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

class MeprPaystackAPI
{
    public $plugin_name;
    protected $public_key;
    protected $secret_key;

    public function __construct($settings)
    {
        $this->plugin_name = 'Memberpress Paystack Gateway Addon';
        $this->secret_key = isset($settings->secret_key) ? $settings->secret_key : '';
        $this->public_key = isset($settings->public_key) ? $settings->public_key : '';
    }

    /**
     * Track Payment Transactions from this Plugin
     *
     * @param string $trx_ref
     * @return void
     */
    public function log_transaction_success($reference)
    {
        //send reference to logger along with plugin name and public key
        $params = [
            'plugin_name'  => $this->plugin_name,
            'transaction_reference' => $reference,
            'public_key' => $this->public_key
        ];
        $this->send_request(
            'log/charge_success',
            $params,
            'post',
            'https://plugin-tracker.paystackintegrations.com/',
            false
        );
    }

    /**
     * Send request to the Paystack Api
     * @param string $endpoint API request path
     * @param array $args API request arguments
     * @param string $method API request method
     * @param string $domain API request uri
     * @param boolean $blocking Block response for irrelevant requests
     * @param boolean $idempotency_key
     * @return object|null JSON decoded transaction object. NULL on API error.
     */
    public function send_request(
        $endpoint,
        $args = array(),
        $method = 'post',
        $domain = 'https://api.paystack.co/',
        $blocking = true,
        $idempotency_key = false
    ) {
        $mepr_options = MeprOptions::fetch();
        $uri = "{$domain}{$endpoint}";

        $args = MeprHooks::apply_filters('mepr_paystack_request_args', $args);

        $arg_array = array(
            'method'    => strtoupper($method),
            'body'      => $args,
            'timeout'   => 15,
            'blocking'  => $blocking,
            'sslverify' => $mepr_options->sslverify,
            'headers'   => $this->get_headers()
        );

        if (false !== $idempotency_key) {
            $arg_array['headers']['Idempotency-Key'] = $idempotency_key;
        }

        $arg_array = MeprHooks::apply_filters('mepr_paystack_request', $arg_array);

        // $uid = uniqid();
        // $this->email_status("###{$uid} Paystack Call to {$uri} API Key: {$this->settings->secret_key}\n" . MeprUtils::object_to_string($arg_array, true) . "\n", $this->settings->debug);

        $resp = wp_remote_request($uri, $arg_array);

        // If we're not blocking then the response is irrelevant
        // So we'll just return true.
        if ($blocking == false)
            return true;

        if (is_wp_error($resp)) {
            throw new MeprHttpException(sprintf(__('You had an HTTP error connecting to %s', 'memberpress'), $this->name));
        } else {
            if (null !== ($json_res = json_decode($resp['body'], true))) {
                //$this->email_status("###{$uid} Paystack Response from {$uri}\n" . MeprUtils::object_to_string($json_res, true) . "\n", $this->settings->debug);
                if (isset($json_res['error']) || $json_res['status'] == false)
                    throw new MeprRemoteException("{$json_res['message']}");
                else
                    return $json_res;
            } else // Un-decipherable message
                throw new MeprRemoteException(sprintf(__('There was an issue with the payment processor. Try again later.', 'memberpress'), $this->name));
        }

        return false;
    }

    /**
     * Validate Webhook Signature
     *
     * @param $input
     * @return boolean
     */
    public function validate_webhook($input)
    {
        return $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] == hash_hmac('sha512', $input, $this->secret_key);
    }

    /**
     * Generates the headers to pass to API request.
     */
    public function get_headers()
    {
        return apply_filters(
            'mepr_paystack_request_headers',
            [
                'Authorization' => "Bearer {$this->secret_key}",
            ]
        );
    }
}
