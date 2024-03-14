<?php

class SharelinkApi {
    protected $base = SHARELINK_APP_BASE_URL.'/api/v1/plugin-api/';
    protected $ch;

    public function __construct() {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    }

    public function close() {
        curl_close($this->ch);
    }

    public function get($endpoint) {
        curl_setopt($this->ch, CURLOPT_URL, $this->base . $endpoint);
        curl_setopt($this->ch, CURLOPT_FAILONERROR, true);
        curl_setopt($this->ch, CURLOPT_REFERER, site_url());
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
            'accept: application/json',
            'cache-control: no-cache',
        ));
        $result = curl_exec($this->ch);
        $httpcode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        if (curl_error($this->ch)) {
            $error_msg = curl_error($this->ch);

            return $httpcode;
        }

        $this->close();
        
        return $httpcode;
    }

    public function getWidgets() {
        curl_setopt($this->ch, CURLOPT_URL, $this->base . SharelinkOptions::getLicense());
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->defaultHeaders());
        curl_setopt($this->ch, CURLOPT_REFERER, site_url());
        $result = curl_exec($this->ch);
        $this->close();

        return json_decode($result, true);
    }

    protected function defaultHeaders() {
        return array(
            'accept: application/json',
            'cache-control: no-cache',
        );
    }
}
