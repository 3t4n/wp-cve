<?php

namespace WpLHLAdminUi\LicenseKeys;

class LicenseKeyFastCheck {

    private $server;
    private $key;
    private $error_code = 0;
    private $error;

    public function __construct($server, $key) {
        $this->server = $server;
        $this->key = $key;
        $this->error = new \WP_Error();
    }

    public function checkForLicense() {

        $url = $this->server . "/" . $this->key . ".json";

        // Use WordPress HTTP API to make an HTTP request to the URL
        $response = wp_safe_remote_get($url);

        // Check if the request was successful
        if (is_wp_error($response)) {
            $this->error_code = -1;
            return $this->error_code; // Failed to make the request
        }

        // Get the HTTP status code
        $status_code = wp_remote_retrieve_response_code($response);

        // Check if the status code indicates a successful response (e.g., 200 OK)
        if ($status_code === 200) {
            // Check if the response content is valid JSON
            $json_data = json_decode(wp_remote_retrieve_body($response));

            if (json_last_error() === JSON_ERROR_NONE) {

                if (!empty($json_data)) {
                    return 1; // JSON file exists and is valid
                }

                $this->error_code = -3;
                return $this->error_code;
            }
        }

        $this->error_code = -2;
        return $this->error_code; // JSON file doesn't exist or is not valid JSON
    }

    /**
     * Return Error response if exists
     */
    public function getFastCheckError() {
        if (!$this->error_code < 0) {
            return false;
        }
        switch ($this->error_code) {
            case -1:
                $this->error->add("failed_request", __("Failed to make the request"), array('status' => 404));
                break;

            case -2:
                $this->error->add("invalid_response", __("JSON file doesn't exist or is not valid JSON"), array('status' => 404));
                break;

            case -3:
                $this->error->add("invalid_license", __("Invalid License"), array('status' => 404));
                break;

            default:
                $this->error->add("bad_response", __("Bad Response"), array('status' => 404));
                break;
        }
        return $this->error;
    }
}
