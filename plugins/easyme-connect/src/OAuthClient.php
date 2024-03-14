<?php

namespace EasyMe;

use Exception;

class OAuthClient {

    const EM_OAUTH_STATE_KEY = 'oauthstate';
    const EM_OAUTH_STATE_EXPIRES_KEY = 'oauthstate_time_set';
    const EM_OAUTH_OPENID_CONFIG = 'oauth_openid_config';

    private $_clientArgs = [];
    private $_accessToken = NULL;

    public function __construct($type) {

        $cfg = self::getOpenIdConfig();

        $args = [
            'access_token_uri' => $cfg['token_endpoint'],
            'revoke_uri' => $cfg['revocation_endpoint']
        ];
        
        switch($type) {

        case 'site':
            $args['client_id'] = 'easyme_connect_wp';
            $args['auth_url'] = $cfg['authorization_endpoint'];
            $args['redirect_uri'] = admin_url('admin.php?' . http_build_query(['page' => WP::$_menuSlugs['main']]));
            $args['info_url'] = WP::getEasyMeServer('api') . '/wp/info';
            $args['scope'] = 'connect-wp';
            break;
            
        case 'otp':
            $args['client_id'] = 'easyme_connect_client';
            $args['info_url'] = WP::getEasyMeServer('api') . '/connect/client/info';
            break;

        default:
            throw new Exception('Invalid OAuth client type: ' . $type);

        }

        $this->_clientArgs = $args;
        
    }

    public function setAccessToken($token) {
        $this->_accessToken = $token;
    }
    
    public function getAuthUrl($args = []) {

        $args['state'] = self::getState();
        $args['client_id'] = $this->_clientArgs['client_id'];
        $args['scope'] = $this->_clientArgs['scope'];        
        $args['redirect_uri'] = $this->_clientArgs['redirect_uri'];
        $args['response_type'] = 'code';

        return $this->_clientArgs['auth_url'] . '?' . http_build_query($args);

    }

    public function revokeToken() {
        $this->get($this->_clientArgs['revoke_uri']);
    }

    public static function getState() {

        if(is_admin()) {
            $state = get_transient(self::EM_OAUTH_STATE_KEY);
            $set = get_transient(self::EM_OAUTH_STATE_EXPIRES_KEY);
        
            if(!$state || !$set || $set < time()) {
                $state = wp_generate_uuid4();
                set_transient(self::EM_OAUTH_STATE_KEY, $state, 3600);
                set_transient(self::EM_OAUTH_STATE_EXPIRES_KEY, (time() + 3000), 3600);            
            }
        } else {
            $state = Auth::getSessionValue(self::EM_OAUTH_STATE_KEY);
            if(!$state) {
                $state = wp_generate_uuid4();
                Auth::setSessionValue(self::EM_OAUTH_STATE_KEY, $state);
            }
        }

        return $state;
        
    }

    public function deleteState() {

        if(is_admin()) {
            delete_transient(self::EM_OAUTH_STATE_KEY);
            delete_transient(self::EM_OAUTH_STATE_EXPIRES_KEY);                        
        } else {
            Auth::setSessionValue(self::EM_OAUTH_STATE_KEY, FALSE);
        }
        
    }
    
    public function handleAuthCodeReturn() {

        if(self::getState() != filter_input(INPUT_GET, 'state')) {
            throw new Exception('Invalid state. Possible CSRF attack.', 400);
        }
        
        $token = $this->getAccessToken([
            'grant_type' => 'authorization_code',
            'code' => filter_input(INPUT_GET, 'code')
        ]);

        $this->deleteState();
        
        return $token;

    }

    public function getAccessToken($args) {
        
        $args['client_id'] = $this->_clientArgs['client_id'];
        $args['redirect_uri'] = $this->_clientArgs['redirect_uri'];
        
        return $this->post($this->_clientArgs['access_token_uri'], $args);

    }

    public function refreshToken($token) {

        $token = $this->post($this->_clientArgs['access_token_uri'], [
            'grant_type' => 'refresh_token',
            'refresh_token' => $token,
            'client_id' => $this->_clientArgs['client_id']
        ]);

        return $token;

    }

    public function getTokenInfo() {
        return $this->get( $this->_clientArgs['info_url'] );
    }
    
    
    public function post($path, $args) {
        return $this->sendRequest('post', $path, $args);
    }

    public function get($path, $args = []) {
        return $this->sendRequest('get', $path, $args);
    }

    private function getOpenIdConfig() {

        $trs = get_transient(self::EM_OAUTH_OPENID_CONFIG);
        
        if(empty($trs) || FALSE === ($cfg = json_decode($trs, TRUE))) {

            $http = wp_remote_get(WP::getEasyMeServer('oauth') . '/.well-known/openid-configuration');
            if(is_wp_error($http) || FALSE === ($body = json_decode($http['body'], TRUE))) {
                WP::handleException( new Exception($http->get_error_message()), TRUE );
            }

            set_transient(self::EM_OAUTH_OPENID_CONFIG, $http['body'], 86400);

            return self::getOpenIdConfig();
            
        }

        return $cfg;
        
    }
    
    private function getHeaders() {

        global $wp_version;
        
        $hdrs = [
            'X-EasyMe-WordPress-Host' => parse_url(get_site_url(), PHP_URL_HOST),
            'X-EasyMe-WordPress-Version' => $wp_version           
        ];

        if(FALSE !== ($pData = WP::getPluginData())) {
            $hdrs['X-EasyMe-Wordpress-Plugin-Version'] = $pData['Version'];
        }
        
        if($this->_accessToken) {
            $hdrs['Authorization'] = 'Bearer ' . $this->_accessToken;
        }

        return $hdrs;

    }
    
    private function sendRequest($method, $url, $args = []) {

        if(0 === strpos($url, '/')) {
            $url = WP::getEasyMeServer() . $url;
        }

        $headers = $this->getHeaders();
        if(array_key_exists('refresh_token', $args)) {
            unset($headers['Authorization']);
        }
        
        switch($method) {

        case 'post':
            $http = wp_remote_post($url, [
                'headers' => $headers,
                'body' => $args
            ]);
            break;

        case 'get':
        default:
            $http = wp_remote_get($url, ['headers' => $headers]);

        }

        if(is_wp_error($http)) {
            WP::handleException( new Exception($http->get_error_message()), TRUE );
        }

        if(200 != $http['response']['code']) {
            if(FALSE !== ($body = json_decode($http['body'], TRUE))) {
                if($body['msg']) {
                    $msg = $body['msg'];
                } elseif($body['message']) {
                    $msg = $body['message'];
                } else {
                    $msg = $http['body'];
                }
                WP::handleException( new Exception($msg, $http['response']['code']), TRUE );
            } else {
                WP::handleException( new Exception($http['body'], $http['response']['code']), TRUE );
            }
        }
        
        return (!empty($http['body']) ? json_decode($http['body'], TRUE) : NULL);
        
    }
    
}

?>
