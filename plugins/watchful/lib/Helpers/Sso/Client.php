<?php

namespace Watchful\Helpers\Sso;

use stdClass;
use Watchful\Helpers\Connection;
use Watchful\Helpers\WatchfulEncrypt;
use WP_Error;

class Client
{
    const SSO_API_URL = 'https://app.watchful.net/api/v1/ssousers/authentification';

    /** @var Connection */
    private $connection_helper;

    /** @var string */
    private $secret_key;

    public function __construct()
    {
        $this->connection_helper = new Connection();

        $settings = get_option('watchfulSettings', '000');
        $this->secret_key = $settings['watchfulSecretKey'];
    }


    /**
     * @param string $username
     * @param string $password
     * @return stdClass|WP_Error
     */
    public function perform_api_authentication($username, $password)
    {
        $time = time();
        $encrypted_password = $this->encrypt_password($username, $password);

        $params = array(
            'username' => $username,
            'hashedpass' => $encrypted_password,
            'url' => get_site_url().'/',
            'hash' => $this->hash_secret_key($time),
            'time' => $time,
        );

        $url = self::SSO_API_URL.'?'.http_build_query($params);

        $response = $this->connection_helper->get_curl(array('url' => $url));

        $response_data = json_decode($response->data);

        if ($response->info['http_code'] !== 200 || !$response_data || empty($response_data->msg) || empty($response_data->msg->id)) {
            return $this->parse_error_response($response_data, $response->info['http_code']);
        }

        return $response_data->msg;
    }

    /**
     * @param string $username
     * @param string $password
     * @return string
     */
    private function encrypt_password($username, $password)
    {
        return WatchfulEncrypt::aes_encrypt($this->hash_password($username, $password), $this->secret_key, 256);
    }

    /**
     * @param string $username
     * @param string $password
     * @return string
     */
    private function hash_password($username, $password)
    {
        return base64_encode(hash_hmac('sha256', $password, $username.'@Wtch'));
    }

    /**
     * @param string $time
     * @return string
     */
    private function hash_secret_key($time)
    {
        return hash_hmac('sha256', $time, $this->secret_key);
    }

    /**
     * @param stdClass $response_data
     * @param int $error_code
     */
    private function parse_error_response($response_data, $error_code)
    {
        $message_prefix = '[Watchful SSO] ';

        if ($error_code === 401) {
            return new WP_Error(
                'invalid_username_password',
                $message_prefix  . (!empty($response_data->msg) ? $response_data->msg : 'Invalid username or password.')
            );
        }

        if ($error_code === 403) {
            return new WP_Error(
                'invalid_username',
                $message_prefix . (!empty($response_data->msg) ? $response_data->msg : 'The user is disabled.')
            );
        }

        return new WP_Error(
            'generic_error',
            $message_prefix . (!empty($response_data->msg) ? $response_data->msg : 'Generic error thrown during API request.')
        );
    }
}
