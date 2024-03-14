<?php

namespace MailOptin\WebHookConnect;

use MailOptin\Core\Connections\AbstractConnect;
use function MailOptin\Core\moVar;

class SendWebhookRequest extends AbstractConnect
{
    public $email;
    public $name;
    public $request_method;
    public $extras;

    public function __construct($email, $name, $request_method, $extras)
    {
        $this->email          = $email;
        $this->name           = $name;
        $this->request_method = $request_method;
        $this->extras         = $extras;

        parent::__construct();
    }

    public function prepare_header()
    {
        $request_headers = $this->get_integration_data('WebHookConnect_request_header_fields', [], []);
        $request_headers = ! empty($request_headers) ? json_decode($request_headers, true) : [];

        $request_headers = array_reduce($request_headers, function ($carry, $item) {

            $key = moVar($item, 'dropdown_key', '');

            if ('mo_custom_header' == $key) {
                $key = moVar($item, 'key', '');
            }

            $carry[$key] = moVar($item, 'value', '');

            return $carry;
        }, []);

        return array_filter($request_headers, [$this, 'data_filter']);
    }

    public function prepare_body()
    {
        $request_data = $this->get_integration_data('WebHookConnect_request_body_fields', [], []);
        $request_data = ! empty($request_data) ? json_decode($request_data, true) : [];

        $request_data = array_reduce($request_data, function ($carry, $item) {

            $key   = moVar($item, 'key', '');
            $value = moVar($item, 'value', '');

            if ( ! empty($value)) {

                switch ($value) {
                    case 'mo_name':
                        $value = $this->name;
                        break;
                    case 'mo_fname':
                        $value = $this->get_first_name();
                        break;
                    case 'mo_lname':
                        $value = $this->get_last_name();
                        break;
                    case 'mo_email':
                        $value = $this->email;
                        break;
                    default:
                        $value = $this->extras[$value];
                }
            }

            $carry[$key] = $value;

            return $carry;

        }, []);

        return array_filter($request_data, [$this, 'data_filter']);
    }

    public function trigger()
    {
        try {

            $request_method = sanitize_text_field(strtoupper(empty($this->request_method) ? 'GET' : $this->request_method));

            $request_url = esc_url_raw($this->get_integration_data('WebHookConnect_request_url', [], ''));

            if (empty($request_url)) throw new \Exception('Request URL is missing.');

            $request_format = sanitize_text_field($this->get_integration_data('WebHookConnect_request_format', [], 'json'));

            $request_headers = $this->prepare_header();

            $request_data = $this->prepare_body();

            if (in_array($request_method, ['GET', 'DELETE']) && ! empty($request_data)) {
                $request_url = add_query_arg(urlencode_deep($request_data), $request_url);
            }

            if (in_array($request_method, array('POST', 'PUT')) && 'json' == $request_format) {

                $request_headers['Content-Type'] = 'application/json';

                $request_data = json_encode($request_data);
            }

            $request_args = [
                'body'      => ! in_array($request_method, ['GET', 'DELETE']) ? $request_data : null,
                'method'    => $request_method,
                'headers'   => $request_headers,
                'sslverify' => apply_filters('https_local_ssl_verify', true, $request_url),
            ];

            $response = wp_remote_request($request_url, $request_args);

            if (is_wp_error($response)) {
                throw new \Exception($response->get_error_message());
            }

            return parent::ajax_success();

        } catch (\Exception $e) {

            self::save_optin_error_log(sprintf('%s (%s)', $e->getMessage(), $e->getCode()), 'webhook', $this->extras['optin_campaign_id'], $this->extras['optin_campaign_type']);

            return parent::ajax_failure(__('Webhook was not successfully executed.', 'mailoptin'));
        }
    }
}