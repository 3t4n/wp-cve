<?php

declare(strict_types=1);

/**
 * Fired during plugin activation.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.2.6
 *
 * @author     Your Name <email@example.com>
 */
class Mailup_Requests
{
    public const HEADER_FORM = ['Content-Type' => 'application/x-www-form-urlencoded '];

    public const HEADER_JSON = [
        'Content-Type' => 'application/json; charset=utf-8',
    ];

    protected $mailup;

    protected $config;

    protected $tokens;

    protected $urlLogon;

    protected $headers;

    public function __construct($mailup = 'mailup')
    {
        $this->mailup = $mailup;
        $this->headers = [];
        $this->tokens = new Mailup_Tokens();
        $this->load_dependencies();
    }

    // public function getTokens()
    // {
    //     return $this->tokens;
    // }

    public static function getUrlLogon()
    {
        $args = [
            'response_type' => 'code',
        ];

        return (new self())->build_service_request('logon', [], (new self())->config->auth, $args);
    }

    public static function tokenFromCode($code, $options)
    {
        $grants = [
            'grant_type' => 'authorization_code',
            'code' => $code,
        ];

        $mup_request = new self();

        $url_token = $mup_request->build_service_request('token', [], $grants);

        $resp = $mup_request->make_request($url_token);
        $code = wp_remote_retrieve_response_code($resp);
        $body = wp_remote_retrieve_body($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            error_log(print_r(json_decode($body), true));
            $error = (object) json_decode($body);

            throw new \Exception($error->error_description, $code);
        }

        Mailup_Tokens::setTokens($body, $options);

        return new self();
    }

    public function listsUser()
    {
        $params = [
            'PageNumber' => 0,
            'PageSize' => 1000,
        ];
        

        $url_service = $this->build_service_request('lists', [], $params);

        $this->set_headers($this->get_header_authorization());
        $resp = $this->make_request($url_service, $this->tokens);

        $code = wp_remote_retrieve_response_code($resp);
        $body = wp_remote_retrieve_body($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            $error = json_decode($body);
            error_log(print_r($resp, true));

            throw new \Exception($code);
        }

        return json_decode($body);
    }

    public function typeFields()
    {
        $params = [
            'PageNumber' => 0,
            'PageSize' => 40,
            'orderby' => 'Id+asc',
        ];

        $this->set_headers(self::HEADER_JSON, $this->get_header_authorization());
        $url_service = $this->build_service_request('type_fields', [], $params);
        $resp = $this->make_request($url_service, $this->tokens);

        $code = wp_remote_retrieve_response_code($resp);
        $body = wp_remote_retrieve_body($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            $error = json_decode($body);
            error_log(print_r($resp, true));

            throw new \Exception();
        }

        return json_decode($body);
    }

    public function getGroups($args)
    {
        $params = [
            'PageNumber' => 0,
            'PageSize' => 30,
            'filterby' => sprintf("Name.Contains('%s')", $args->group),
            'orderby' => '"Name+asc',
        ];

        $this->set_headers(self::HEADER_JSON, $this->get_header_authorization());

        $url_service = $this->build_service_request('list_groups', [$args->list_id], $params);
        $resp = $this->make_request($url_service, $this->tokens);

        $code = wp_remote_retrieve_response_code($resp);
        $body = wp_remote_retrieve_body($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            $error = json_decode($body);
            error_log(print_r($error, true));

            throw new \Exception($error->ErrorDescription, $code);
        }

        return json_decode($body);
    }

    public function createGroup($args)
    {
        $this->headers = [
            'method' => 'POST',
            'data_format' => 'body',
            'body' => $args->body,
        ];

        $this->set_headers(self::HEADER_JSON, $this->get_header_authorization());

        $url_service = $this->build_service_request('create_group', [$args->list_id]);

        $resp = $this->make_request($url_service, $this->tokens, 'POST');
        $body = wp_remote_retrieve_body($resp);

        $code = wp_remote_retrieve_response_code($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            $error = json_decode($body);
            error_log(print_r($error, true));

            if (400 === $code) {
                return;
            }

            throw new \Exception($error->ErrorDescription, $code);
        }

        return json_decode($body);
    }

    public function renameGroup($args)
    {
        $this->headers = [
            'method' => 'PUT',
            'data_format' => 'body',
            'body' => $args->body,
        ];

        $this->set_headers(self::HEADER_JSON, $this->get_header_authorization());

        $url_service = $this->build_service_request('update_group', $args->params);

        $resp = $this->make_request($url_service, $this->tokens, 'PUT');

        $body = wp_remote_retrieve_body($resp);

        $code = wp_remote_retrieve_response_code($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            $error = json_decode($body);
            error_log(print_r($error, true));

            if (400 === $code) {
                return;
            }

            throw new \Exception($error->ErrorDescription, $code);
        }

        return json_decode($body);
    }

    // FOR PUBLIC PLUGIN PART
    public function addRecipient($args)
    {
        $params = [];

        $this->headers = [
            'method' => 'POST',
            'data_format' => 'body',
            'body' => $args->body,
        ];

        if ($args->confirm) {
            $params = [
                'ConfirmEmail' => 'true',
            ];
        }

        $this->set_headers(self::HEADER_JSON, $this->get_header_authorization());

        $url_service = $this->build_service_request('add_recipient', [$args->list_id], $params);

        $resp = $this->make_request($url_service, $this->tokens, 'POST');
        $body = wp_remote_retrieve_body($resp);

        $code = wp_remote_retrieve_response_code($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            $error = json_decode($body);
            error_log(print_r($error, true));

            throw new \Exception('', $code);
        }

        return $body;
    }

    public function addToGroup($args): void
    {
        $this->headers = [
            'method' => 'POST',
            'data_format' => 'body',
            'body' => $args->body,
        ];
        $params = [
            'confirmSubscription' => 'false',
        ];

        $this->set_headers(self::HEADER_JSON, $this->get_header_authorization());

        $url_service = $this->build_service_request('add_to_group', [$args->group_id, $args->recipient_id], $params);

        $resp = $this->make_request($url_service, $this->tokens, 'POST');
        $body = wp_remote_retrieve_body($resp);

        $code = wp_remote_retrieve_response_code($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            $error = json_decode($body);
            error_log($body);
            error_log(print_r($error, true));

            throw new \Exception('', $code);
        }
    }

    protected function get_header_authorization()
    {
        return ['Authorization' => 'Bearer '.$this->tokens->get_access_token()];
    }

    protected function refresh_token()
    {
        $grants = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->tokens->get_refresh_token(),
        ];

        $url_refresh = $this->build_service_request('token', [], $grants, $this->config->auth);

        $this->set_headers(self::HEADER_FORM, $this->get_header_authorization());

        $resp = $this->make_request($url_refresh);
        $code = wp_remote_retrieve_response_code($resp);
        $body = wp_remote_retrieve_body($resp);

        if (is_wp_error($resp) || 200 > $code || 300 <= $code) {
            if (is_wp_error($resp)) {
                error_log($resp->get_error_message($resp->get_error_code()));

                throw new \Exception('', $code);
            }
            $error = json_decode($body);
            error_log(print_r($error, true));

            throw new \Exception($error->error_description, $code);
        }

        return Mailup_Tokens::setTokens($body, Mailup_Model::get_option());
    }

    protected function make_request($url, $refresh = false, $verb = 'GET')
    {
        // DEBUG REQUEST PARAMETERS
        // error_log($url);
        // error_log($verb);
        // error_log(print_R($this->headers, true));

        $resp = 'GET' === $verb
            ? wp_remote_get($url, $this->headers)
            : wp_remote_post($url, $this->headers);

        $code = wp_remote_retrieve_response_code($resp);

        if ((is_wp_error($resp) && 'http_request_failed' === $resp->get_error_code()) || 504 === $code) {
            error_log(sprintf('%s: %s', WPMUP_PLUGIN_NAME, $resp->get_error_message()));

            throw new Exception($resp->get_error_message(), 504);
        }

        $body = wp_remote_retrieve_body($resp);

        if (in_array($code, [401, 403], true) && $refresh) {
            $original_headers = $this->headers;
            $this->refresh_token($this->tokens);

            $this->tokens = new Mailup_Tokens();
            $this->set_headers($this->get_header_authorization());

            return $this->make_request($url);
        }
        $this->headers = [];

        return $resp;
    }

    protected function build_service_request($service_name, $url_param = [], ...$args)
    {
        $url = vsprintf($this->config->services_url[$service_name], $url_param);

        if ($args) {
            $params = call_user_func_array('array_merge', $args);

            return sprintf('%s?%s', $url, http_build_query($params));
        }

        return $url;
    }

    protected function set_headers(...$args): void
    {
        if ($args) {
            $this->headers['headers'] = array_merge(...$args);
        }
    }

    private function load_dependencies(): void
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        include_once plugin_dir_path(__DIR__).'includes/class-mailup-configuration.php';
        $this->config = new Mailup_Platform_Configuration();
    }
}
