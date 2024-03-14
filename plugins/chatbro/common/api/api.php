<?php
class CBroApi extends CBroBackendable
{
    const api_url = 'https://www.chatbro.com/api/v1/';
    const platform_id = 1000;

    protected function __construct($backend)
    {
        parent::__construct($backend);
    }

    private static function delete_access_token()
    {
        self::get_backend()->delete_access_token();
    }

    private static function delete_refresh_token()
    {
        self::get_backend()->delete_refresh_token();
    }

    private static function set_access_token($access_token)
    {
        self::get_backend()->set_access_token($access_token);
    }

    private static function set_refresh_token($refresh_token)
    {
        self::get_backend()->set_refresh_token($refresh_token);
    }

    private static function get_access_token()
    {
        $access_token = json_decode(self::get_backend()->get_access_token(), false);

        if (!is_null($access_token) && isset($access_token->value) && isset($access_token->value)) {
            return new AccessToken(
                $access_token->value,
                $access_token->expiresIn
            );
        } else {
            return null;
        }
    }

    private static function get_refresh_token()
    {
        $refresh_token = json_decode(self::get_backend()->get_refresh_token(), false);

        if (!is_null($refresh_token) && isset($refresh_token->value) && isset($refresh_token->value)) {
            return new RefreshToken(
                $refresh_token->value,
                $refresh_token->expiresIn
            );
        } else {
            return null;
        }
    }

    private static function disable_api()
    {
        self::make_api_request('/tokens/delete/', false);
    }

    public static function remove_tokens()
    {
        self::disable_api();
        self::delete_access_token();
        self::delete_refresh_token();
    }

    private static function authorization()
    {
        $args = array(
            'headers' => array(
                'Account-Platform' => self::platform_id,
                'Account-Id' => CBroSettings::get(CBroSettings::guid),
                'Account-Secret' => CBroSettings::get(CBroSettings::secret),
            ),
        );

        $response = wp_remote_post(self::api_url . '/tokens/authorization/', $args);

        if (is_wp_error($response)) {
            throw new CBroApiError(__("The service ChatBro is currently unavailable. Please try again later.", "chatbro"));
        }

        $httpcode = wp_remote_retrieve_response_code($response);
        $response_data = wp_remote_retrieve_body($response);

        if ($httpcode == 502 || $httpcode == 404) {
            throw new CBroApiError(__("The service ChatBro is currently unavailable. Please try again later.", "chatbro"));
        }

        $response_data = json_decode($response_data, true);

        //{"success":false,"status":401,"error":"invalid_account_id","code":1006,"description":"The account id provided is invalid"}
        //{"success":false,"status":401,"error":"invalid_account_secret","code":1005,"description":"The account secret provided is invalid."}
        //{"success": false, "status":401,"error":"invalid_credentials","code":1012,"description":"The credentials provided is incorrect."}
        if (isset($response_data['error']) && ($response_data['code'] == 1006 || $response_data['code'] == 1005 || $response_data['code'] == 1012)) {
            throw new CBroApiError(__("The credentials provided is incorrect or invalid.", "chatbro"));
        }

        if (isset($response_data['accessToken']) && isset($response_data['refreshToken'])) {
            $access_token = new AccessToken(
                $response_data['accessToken']['value'],
                $response_data['accessToken']['expiresIn']
            );

            $refresh_token = new RefreshToken(
                $response_data['refreshToken']['value'],
                $response_data['refreshToken']['expiresIn']
            );

            self::set_access_token(json_encode($access_token));
            self::set_refresh_token(json_encode($refresh_token));

            return $access_token;
        } else {
            throw new CBroApiError(__("Something went wrong. Try again later.", "chatbro"));
        }
    }

    private static function refresh_access_token()
    {
        $refresh_token = self::get_refresh_token();

        if (is_null($refresh_token)) {
            return self::authorization();
        }

        $args = array(
            'headers' => array(
                'Account-Platform' => self::platform_id,
                'Account-Secret' => CBroSettings::get(CBroSettings::secret),
                'Refresh-Token' => $refresh_token->get_value(),
            ),
        );

        $response = wp_remote_post(self::api_url . '/tokens/refresh/', $args);

        if (is_wp_error($response)) {
            throw new CBroApiError(__("The service ChatBro is currently unavailable. Please try again later.", "chatbro"));
        }

        $httpcode = wp_remote_retrieve_response_code($response);
        $response_data = wp_remote_retrieve_body($response);

        if ($httpcode == 502 || $httpcode == 404) {
            throw new CBroApiError(__("The service ChatBro is currently unavailable. Please try again later.", "chatbro"));
        }

        $response_data = json_decode($response_data, true);

        if (isset($response_data['accessToken'])) {
            $access_token = new AccessToken(
                $response_data['accessToken']['value'],
                $response_data['accessToken']['expiresIn']
            );
            self::set_access_token(json_encode($access_token));

            return $access_token;
        }

        //{"success": false, "status":401,"error":"invalid_refresh_token","code":1004,"description":"The refresh token provided is invalid or has expired."}
        if (isset($response_data['error']) && $response_data['code'] == 1004) {
            return self::authorization();
        }

        throw new CBroApiError(__("Something went wrong. Try again later.", "chatbro"));
    }

    private static function make_request($access_token, $endpoint, $data)
    {
        $args = array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token->get_value(),
            ),
        );

        if ($data) {
            $args['body'] = json_encode($data);
        }

        $response = wp_remote_post(self::api_url . $endpoint, $args);

        if (is_wp_error($response)) {
            throw new CBroApiError(__("The service ChatBro is currently unavailable. Please try again later.", "chatbro"));
        }

        $httpcode = wp_remote_retrieve_response_code($response);

        if ($httpcode == 502 || $httpcode == 404) {
            throw new CBroApiError(__("The service ChatBro is currently unavailable. Please try again later.", "chatbro"));
        }

        $response_data = wp_remote_retrieve_body($response);

        return json_decode($response_data, true);
    }

    public static function get_token()
    {
        $access_token = self::get_access_token();

        if (is_null($access_token)) {
            $access_token = self::authorization();
        }

        if ($access_token->is_expired()) {
            $access_token = self::refresh_access_token();
        }

        return $access_token;
    }

    public static function make_api_request($endpoint, $data)
    {
        $access_token = self::get_token();
        $response_data = self::make_request($access_token, $endpoint, $data);

        //{"success": false, "status":401,"error":"invalid_access_token","code":1003,"description":"The access token provided is invalid or has expired."}
        if (isset($response_data['error']) && isset($response_data['code']) && $response_data['code'] == 1003) {
            $access_token = self::refresh_access_token();
            $response_data = self::make_request($access_token, $endpoint, $data);
        }

        //{"success": false, "status":403,"error":"delete_chat_exception","code":1011,"description":"Can't delete chat because it has child chats."}
        if (isset($response_data['error']) && isset($response_data['code']) && $response_data['code'] == 1011) {
            $reply = array('error' => true);
            $reply['useforce'] = true;
            $reply['message'] = __("This chat has child chats. By deleting it, you will delete them too. This action cannot be undone. Are you sure you want to continue?", "chatbro");
            return $reply;
        }

        if (isset($response_data['success'])) {
            return $response_data;
        }

        throw new CBroApiError(__("Something went wrong. Try again later.", "chatbro"));
    }
}

class CBroApiError extends Exception
{
    public function __construct($msg = null)
    {
        parent::__construct($msg);
    }
}

class Token implements JsonSerializable
{
    private $value;
    private $expires_in;


    public function __construct($value, $expires_in)
    {
        $this->value = $value;
        $this->expires_in = $expires_in;
    }

    public function get_value()
    {
        return $this->value;
    }

    public function get_expires_in()
    {
        return $this->expires_in;
    }

    public function is_expired()
    {
        return time() > $this->get_expires_in();
    }

    public function jsonSerialize()
    {
        return [
            'value' => $this->get_value(),
            'expiresIn' => $this->get_expires_in(),
        ];
    }
}

class AccessToken extends Token implements JsonSerializable
{
}
class RefreshToken extends Token implements JsonSerializable
{
}



?>