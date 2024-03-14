<?php

class CloudFiltCodesFormHandler {
    private $url;
    private $params = array();
    private $fieldsPrefix;
    private $curlUrl = 'https://api.cloudfilt.com/checkcms/wordpress.php';
    private $error = array();

    public function __construct($fieldsPrefix) {
        $this->url = get_site_url();
        $this->fieldsPrefix = $fieldsPrefix;
    }

    public function handleForm($params) {
        if(!is_array($params) || !isset($params['option_page']) || $params['option_page'] !== 'cloudfilt_codes_settings' || $params['action'] !== 'update') {
            return null;
        }

        if(!$this->params = $this->checkParams($params)) {
            $this->error[] = 'Incorrect params.';
            return false;
        }

        $response = $this->curlRequest();

        if($response != 'error' && ((isset($response['response']) && $response['response'] == '200') || $response['response']['code'] == '200')) {
            if(isset($response['body'])) {
                $body = json_decode($response['body']);

                if(json_last_error() == JSON_ERROR_NONE && $body->status === 'OK') {
                    update_option($this->fieldsPrefix . 'site_id', $body->site);
                    return true;
                }
            }

            $this->error[] = 'Params rejected.';
            return false;
        } else {
            $this->error[] = 'Params checking failed.';
            return false;
        }
    }

    public function checkParams($params) {
        $filteredParams = array();

        foreach ($params as $key => $param) {
            if(empty($param)) {
                return false;
            }

            $filteredParams[$key] = filter_var($param, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $filteredParams;
    }

    public function curlRequest() {
        $response = wp_remote_post(
            $this->curlUrl,
            array(
                'method'      => 'POST',
                'timeout'     => 30,
                'redirection' => 5,
                'httpversion' => '1.1',
                'sslverify'   => false,
                'blocking'    => true,
                'headers'     => array(),
                'body'        => $this->requestParams(),
                'cookies'     => array(),
                'user-agent'  => 'plugin-wordpress'
            )
        );

        if(is_wp_error($response)) {
            switch ($response->get_error_code()) {
                case 'http_request_failed':
                    $this->error[] = 'Error during request: Connection failed (cURL error 7). Please check that your server & firewall allows to be connected to port 443. <a href="https://app.cloudfilt.com/faq" target="_blank">FAQ</a>';
                    break;
                default:
                    $this->error[] = 'Error during request: '.$response->get_error_code().' - '. $response->get_error_message().'. <a href="https://app.cloudfilt.com/faq" target="_blank">FAQ</a>';
                    break;
            }

            return 'error';
        }

        return($response);
    }

    public function requestParams() {
        $requestParams = array(
            'url' => $this->url
        );

        foreach ($this->params as $key => $param) {
            if(strpos($key, $this->fieldsPrefix) !== false) {
                $key = str_replace($this->fieldsPrefix, '', $key);
                $requestParams[$key] = $param;
            }
        }

        return $requestParams;
    }

    public function getParam($name) {
        if(isset($this->params[$name])) {
            return $this->params[$name];
        }
    }

    public function getError() {
        return $this->error;
    }
}

new CloudFiltCodes();

?>