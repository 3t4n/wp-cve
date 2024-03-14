<?php
require_once clickcease_plugin_PLUGIN_PATH . 'classes/enums.php';

const ALLOWED_CODES = [
    AllowedCodes::VALID,
    AllowedCodes::ABNORMAL_RATE_LIMIT,
    AllowedCodes::CLICK_HIJACKING,
    AllowedCodes::CRAWLERS,
    AllowedCodes::DATA_CENTER,
    AllowedCodes::FREQUENCY_CAPPING,
    AllowedCodes::GOOD_BOT,
    AllowedCodes::PROXY,
    AllowedCodes::VPN,
];
const CC_BZ_URL = 'https://botzapping.eu.cheq-platform.com/authorize/plugin';
const CC_RTI_URL = 'https://rti-global.cheqzone.com/v1/realtime-interception';
class RTI_Service
{
    public function is_monitoring_with_botzapping($api_key, $tag_hash, $secret)
    {
        $params = array('tagHash' => $tag_hash, 'apiKey' => $api_key, 'secretKey' => $secret, 'newVersion' => true);
        $url = Urls::BOTZAPPING . '/plugin/monitoring';
        $request =  [
            'method' => 'GET',
            'timeout' => 10,
            'redirection' => 5,
            'headers' => [
                'Content-Type' => 'application/json',
                'Origin' => "plugin",
                'Authorization' => $api_key,
            ]
        ];
        $apiResponse = wp_remote_get($url . "?" . http_build_query($params), $request);
        if (is_wp_error($apiResponse)) {
            $error_message = $apiResponse->get_error_message();
            $this->send_error($request, $error_message);
        }
        $response_code = wp_remote_retrieve_response_code($apiResponse);
        $response_body = wp_remote_retrieve_body($apiResponse);
        $isMonitoring = false;
        if ($response_body && $response_code == "200") {
            $isMonitoring = json_decode($response_body)->isMonitoring;
        } else if (!is_wp_error($apiResponse)) {
            $this->send_error($request, $response_body);
        }
        return $response_code == "200" && $isMonitoring;
    }
    public function auth_with_botzapping($api_key, $tag_hash, $secret)
    {
        $data = new stdClass();
        $data->tagHash = $tag_hash;
        $data->apiKey = $api_key;
        $data->secretKey = $secret;
        $data->newVersion = true;
        $dataStr = json_encode($data);

        $request =  [
            'method' => 'POST',
            'timeout' => 10,
            'redirection' => 5,
            'headers' => [
                'Content-Type' => 'application/json',
                'Origin' => "plugin",
                'Authorization' => $api_key,
            ],
            'body' => $dataStr,
        ];

        $apiResponse = wp_remote_post(Urls::BOTZAPPING . '/authorize/plugin', $request);

        if (is_wp_error($apiResponse)) {
            $error_message = $apiResponse->get_error_message();
            $this->send_error($request, $error_message);
        }

        $response_code = wp_remote_retrieve_response_code($apiResponse);
        $response_body = wp_remote_retrieve_body($apiResponse);
        if ($response_code !== HTTPCode::SUCCESS && !is_wp_error($apiResponse)) {
            $this->send_error($request, $response_body);
        }
        return $response_code !== HTTPCode::SUCCESS ? "" : $response_body;
    }
    public function auth_with_rti($api_key, $request_url, $event_type, $tag_hash)
    {
        $res = [
            "is_valid" => true,
            "output" => [],
        ];
        $error_message = "";

        try {
            $request = $this->create_rti_request($api_key, $request_url, $event_type, $tag_hash);
            $apiResponse = wp_remote_post(Urls::RTI_SERVER_EUROPE, $request);

            if (is_wp_error($apiResponse)) {
                $error_message = $apiResponse->get_error_message();
                $this->send_error($request, $error_message);
            }
            if (!$error_message) {
                $response_code = wp_remote_retrieve_response_code($apiResponse);
                $response_body = wp_remote_retrieve_body($apiResponse);
                if ($response_code !== HTTPCode::SUCCESS && !is_wp_error($apiResponse)) {
                    $this->send_error($request, $response_body);
                } else if ($response_code == HTTPCode::SUCCESS) {
                    $res = $this->validate_rti_response($apiResponse, $request);
                }
            }
        } catch (Exception $e) { {
                LogService::log("Plugin", "", "", "", "", "", "", "", ErrorCodes::ERROR, $e->getMessage());
            }
        }
        return $res;
    }

    public function validate_rti_response($response, $request_params)
    {
        $res = [
            "is_valid" => true,
            "output" => [],
        ];
        if (!empty($response)) {
            $response = json_decode($response["body"]);
            if (!isset($response->setCookie)) {
                LogService::log("Server", "", "", "", "", "", "", "", ErrorCodes::RTI_SERVER_MISSING_COOKIE);
            } else {
                $cookie_values = explode(';', $response->setCookie);
                $cookie_name_value = explode('=', $cookie_values[0], 2);
                if (isset($cookie_name_value) && isset($cookie_values) && count($cookie_name_value) > 1 && count($cookie_values) > 2) {
                    setcookie($cookie_name_value[0] . '_en', $cookie_name_value[1], strtotime(explode('=', $cookie_values[1])[1]), explode('=', $cookie_values[3])[1]);
                    setrawcookie($cookie_name_value[0], $cookie_name_value[1], strtotime(explode('=', $cookie_values[1])[1]), explode('=', $cookie_values[3])[1]);
                } else {
                    LogService::log("Server", "", "", "", "", "", "", "", ErrorCodes::RTI_SERVER_INCORRECT_COOKIE);
                }
            }
            if (!isset($response->version) || !is_numeric($response->version)) {
                update_option('cheq_invalid_secret', true);
                LogService::log("Server", "", "", "", "", "", "", "", ErrorCodes::RTI_SERVER_AUTH_ERROR);
            } else {
                update_option('cheq_invalid_secret', false);
            }

            if (!isset($response->threatTypeCode)) {
                LogService::log("Server", "", "", "", "", "", "", "", ErrorCodes::RTI_SERVER_MISSING_THREAT_TYPE);
            } elseif (!in_array($response->threatTypeCode, ALLOWED_CODES) && $response->isInvalid) {
                $res = [
                    "is_valid" => false,
                    "output" => $response,
                ];
            } else {
                if (!isset($response)) {
                    LogService::log("Server", "", "", "", "", "", "", "", ErrorCodes::RTI_SERVER_EMPTY_RESPONSE_FORMAT);
                } elseif (!is_object($response)) {
                    LogService::log("Server", "", "", "", "", "", "", "", ErrorCodes::RTI_SERVER_INVALID_RESPONSE_FORMAT);
                }
            }
        } else {
            $this->send_error($request_params, $response);
        }


        return $res;
    }
    public function validateRTIClient()
    {
        if (isset($_GET['clickcease']) && $_GET['clickcease'] == 'valid') {
            return;
        }

        // Validate nonce
        if (!check_ajax_referer("cc_ajax_nonce", "security")) {
            echo json_encode([
                "status" => 400,
                "message" => "Request could not be validated",
            ]);
            exit();
        }

        // If the user has been redirected and he is currently on the
        if (isset($_GET['redirected']) && $_GET['redirected'] == 'true') {
            echo json_encode([
                "status" => 403,
                "message" => "",
            ]);
            exit();
        }

        // Validate clickcease hash
        if (!isset($_POST['cheq_hash']) || empty($_POST['cheq_hash'])) {
            echo json_encode([
                "status" => 400,
                "error" => "No hash",
            ]);
            exit();
        }

        $message = str_replace(' ', '+', $_POST['cheq_hash']);
        $ciphering = 'AES-192-CTR';
        $options = 0;
        $iv = substr($message, 0, 16);
        $encrypted_message = substr($message, 16);
        $secret_key = get_option('clickcease_secret_key', '');

        $decrypted_message = openssl_decrypt($encrypted_message, $ciphering, $secret_key, $options, $iv);

        $output = explode(":", $decrypted_message);

        if (count($output) > 1 && !is_numeric($output[0])) {
            update_option('cheq_invalid_secret', true);
        } else {
            update_option('cheq_invalid_secret', false);
        }

        $required_action = "";
        if (count($output) < 4) {
            update_option('cheq_invalid_secret', true);
            $msg = "Secret =" . $secret_key . ",Output=" . print_r($output, true);
            LogService::log("Front", "", "", "", "", "", "", "", ErrorCodes::INCORRECT_SECRET_KEY, $msg);
        } else {
            $is_monitoring = get_option('monitoring', false);
            update_option('cheq_invalid_secret', false);
            if (!$is_monitoring) {
                if (intval($output[2]) == 7 || intval($output[2]) == 16) {
                    $required_action = "clearhtml";
                } elseif (!in_array($output[2], ALLOWED_CODES) && $output[1]) {
                    $required_action = "blockuser";
                }
            }
        }

        // Send response to Ajax
        echo json_encode([
            "status" => 200,
            "message" => [
                "action" => $required_action,
            ],
        ]);
        exit();
    }

    public function updateUserStatus($api_key, $client_id, $status)
    {
        $data = new stdClass();
        $data->Address = Utils::getDomain();
        $data->State = $status;
        $data->ClientId = $client_id;
        $dataStr = json_encode($data);

        $request =  $this->get_clickcease_request($api_key, $dataStr);
        // add route
        $apiResponse = wp_remote_post(Urls::CLICKCEASE_BOTZAPPING . '/State', $request);
        $response_code = $this->get_response_code($request, $apiResponse);

        return $response_code == HTTPCode::SUCCESS;
    }

    public function update_bz_domain($api_key, $client_id, $plugin_latests_version)
    {
        $data = new stdClass();
        $data->Address = Utils::getDomain();
        $data->PluginLatestsVersion = $plugin_latests_version;
        $data->ClientId = $client_id;
        $dataStr = json_encode($data);

        $request =  $this->get_clickcease_request($api_key, $dataStr);

        // add route
        $apiResponse = wp_remote_post(Urls::CLICKCEASE_BOTZAPPING . '/update', $request);
        $response_code = $this->get_response_code($request, $apiResponse);

        return $response_code == HTTPCode::SUCCESS;
    }
    public  function update_user_status($state)
    {
        $clickcease_api_key = get_option('clickcease_api_key', '');
        $client_id = get_option('clickcease_client_id', '');
        if ($client_id !== '') {
            $this->updateUserStatus($clickcease_api_key, $client_id, $state);
        }
    }

    public function get_active_domain($api_key)
    {
        $res = "";
        $request =  [
            'method' => 'GET',
            'timeout' => 10,
            'redirection' => 5,
            'headers' => [
                'Content-Type' => 'application/json',
                'Origin' => "plugin",
                'Authorization' => $api_key,
            ],
        ];

        $apiResponse = wp_remote_get(Urls::BOTZAPPING . '/plugin/active-domain', $request);
        $error_message = "";
        if (is_wp_error($apiResponse)) {
            $error_message = $apiResponse->get_error_message();
            $this->send_error($request, $error_message);
        }
        $status_code = $this->get_response_code($request, $apiResponse);

        if (!$error_message && $status_code == HTTPCode::SUCCESS) {
            $res = wp_remote_retrieve_body($apiResponse);
        }
        return $res;
    }
    private function get_clickcease_request($api_key, $dataStr)
    {
        $request =  [
            'method' => 'POST',
            'timeout' => 10,
            'redirection' => 5,
            'headers' => [
                'Content-Type' => 'application/json',
                'Origin' => "plugin",
                'Authorization' => $api_key,
            ],
            'body' => $dataStr,
        ];
        return $request;
    }

    private function get_response_code($request, $apiResponse)
    {
        $response_code = 200;
        if (is_wp_error($apiResponse)) {
            $error_message = $apiResponse->get_error_message();
            $this->send_error($request, $error_message);
        }
        $response_code = wp_remote_retrieve_response_code($apiResponse);
        if ($response_code !== HTTPCode::SUCCESS && !is_wp_error($apiResponse)) {
            $response_body = wp_remote_retrieve_body($apiResponse);
            $this->send_error($request, $response_body);
        }
        return $response_code;
    }

    private function send_error($request_params, $error)
    {
        if (is_array($error))
            $error = json_encode($error);
        $request = json_encode($request_params);
        $msg = "{\"Request\":{$request} , \"Error\":\"{$error}\"}";
        LogService::log("Plugin", "", "", "", "", "", "", "", ErrorCodes::ERROR, $msg);
    }
    private function create_rti_request($api_key, $request_url, $event_type, $tag_hash)
    {

        $domain = Utils::get_host();
        $client_ip = Utils::get_the_user_ip();


        $request_params = [
            'ApiKey' => $api_key,
            'ClientIP' => $client_ip,
            'RequestURL' => $request_url,
            'ResourceType' => 'text/html',
            'Method' => 'GET',
            'Host' => strtok($domain, '/'),
            'UserAgent' => Utils::getServerVariable('HTTP_USER_AGENT'),
            'Accept' => Utils::getServerVariable('HTTP_ACCEPT'),
            'AcceptLanguage' => Utils::getServerVariable('HTTP_ACCEPT_LANGUAGE'),
            'AcceptEncoding' => Utils::getServerVariable('HTTP_ACCEPT_ENCODING'),
            'HeaderNames' => 'Host,User-Agent,Accept,Accept-Langauge,Accept-Encoding',
            'EventType' => $event_type,
            'TagHash' => $tag_hash,
        ];

        if ($event_type == 'page_load') {
            $request_params['HeaderNames'] = 'Host,User-Agent,Accept,Accept-Langauge,Accept-Encoding,Cookie';
            $request_params['CheqCookie'] = Utils::getCookieVariable('_cheq_rti_en');
            $request_params['Referer'] = Utils::getServerVariable('HTTP_REFERER');
            $request_params['Connection'] = Utils::getServerVariable('HTTP_CONNECTION');
        }

        $request =  [
            'method' => 'POST',
            'timeout' => 2,
            'redirection' => 10,
            'httpversion' => '1.1',
            'body' => $request_params,
        ];
        return $request;
    }
}

require_once clickcease_plugin_PLUGIN_PATH . 'classes/logService.php';
require_once clickcease_plugin_PLUGIN_PATH . 'classes/formService.php';
require_once clickcease_plugin_PLUGIN_PATH . 'classes/utils.php';
require_once clickcease_plugin_PLUGIN_PATH . 'classes/enums.php';
