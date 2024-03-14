<?php
/**
 * Instamojo
 * used to manage Instamojo API calls
 * 
 */
include __DIR__ . DIRECTORY_SEPARATOR . "ValidationException.php";

class Instamojo
{

    private $api_endpoint;

    private $auth_endpoint;

    private $auth_headers;

    private $access_token;

    private $client_id;

    private $client_secret;

    function __construct($client_id, $client_secret, $test_mode)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        
        if ($test_mode)
            $this->api_endpoint = "https://test.instamojo.com/v2/";
        else
            $this->api_endpoint = "https://api.instamojo.com/v2/";
        if ($test_mode)
            $this->auth_endpoint = "https://test.instamojo.com/oauth2/token/";
        else
            $this->auth_endpoint = "https://api.instamojo.com/oauth2/token/";
        
        $this->getAccessToken();
    }

    private function getResponseBody($response)
    {
        if (is_array($response) && isset($response['body'])) {
            return $response['body'];
        }
        throw new Exception("Something went wrong.");
    }

    public function getAccessToken()
    {
        $data = array();
        $data['client_id'] = $this->client_id;
        $data['client_secret'] = $this->client_secret;
        $data['scopes'] = "all";
        $data['grant_type'] = "client_credentials";
        
        $response = wp_remote_post($this->auth_endpoint, array(
            'body' => $data
        ));
        $result = wp_remote_retrieve_body($response);
        if ($result) {
            $result = json_decode($result);
            if (isset($result->error)) {
                throw new ValidationException("The Authorization request failed with message '$result->error'",
                    array(
                        "Payment Gateway Authorization Failed."
                    ), $result);
            } else
                $this->access_token = $result->access_token;
        }
        if ($result->access_token == "") {
            throw new Exception("Something went wrong. Please try again later.");
        }
        $this->auth_headers = "Authorization:Bearer $this->access_token";
    }

    public function createOrderPayment($data)
    {
        $endpoint = $this->api_endpoint . "gateway/orders/";
        $response = wp_remote_post($endpoint,
            array(
                'body' => $data,
                'headers' => $this->auth_headers
            ));
        $result = wp_remote_retrieve_body($response);
        $result = json_decode($result);
        if (isset($result->order)) {
            return $result;
        } else {
            $errors = array();
            if (isset($result->message))
                throw new ValidationException("Validation Error with message: $result->message",
                    array(
                        $result->message
                    ), $result);
            
            foreach ($result as $k => $v) {
                if (is_array($v))
                    $errors[] = $v[0];
            }
            if ($errors)
                throw new ValidationException("Validation Error Occured with following Errors : ", $errors, $result);
        }
    }

    public function createPaymentRequest($data)
    {
        $endpoint = $this->api_endpoint . "payment_requests/";
        $response = wp_remote_post($endpoint,
            array(
                'body' => $data,
                'headers' => $this->auth_headers
            ));
        $result = wp_remote_retrieve_body($response);
        
        $result = json_decode($result);
        if (isset($result->id)) {
            return $result;
        } else if (isset($result)) {
            $errors = array();
            if (isset($result->message))
                throw new ValidationException("Validation Error with message: $result->message",
                    array(
                        $result->message
                    ), $result);
            
            foreach ($result as $k => $v) {
                if (is_array($v) && isset($v[0]))
                    $errors[] = $v[0];
            }
            if ($errors)
                throw new ValidationException("Validation Error Occured with following Errors : ", $errors, $result);
        }
    }

    public function getOrderById($id)
    {
        $endpoint = $this->api_endpoint . "gateway/orders/id:$id/";
        
        $response = wp_remote_get($endpoint, array(
            'headers' => $this->auth_headers
        ));
        $result = wp_remote_retrieve_body($response);
        
        $result = json_decode($result);
        if (isset($result->id) and $result->id)
            return $result;
        else
            throw new Exception("Unable to Fetch Payment Request id:'$id' Server Responds " . print_R($result, true));
    }

    public function getPaymentRequestById($id)
    {
        $endpoint = $this->api_endpoint . "payment_requests/$id/";
        $response = wp_remote_get($endpoint, array(
            'headers' => $this->auth_headers
        ));
        $result = wp_remote_retrieve_body($response);
        
        $result = json_decode($result);
        if (isset($result->id) and $result->id)
            return $result;
        else
            throw new Exception("Unable to Fetch Payment Request id:'$id' Server Responds " . print_R($result, true));
    }

    public function getPaymentById($id)
    {
        $endpoint = $this->api_endpoint . "payments/$id/";
        
        $response = wp_remote_get($endpoint, array(
            'headers' => $this->auth_headers
        ));
        $result = wp_remote_retrieve_body($response);
        
        $result = json_decode($result);
        if (isset($result->id) and $result->id)
            return $result;
        else
            throw new Exception("Unable to Fetch Payment id:'$id' Server Responds " . print_R($result, true));
    }

    public function getPaymentStatus($payment_id, $payments)
    {
        foreach ($payments as $payment) {
            if ($payment->id == $payment_id) {
                return $payment->status;
            }
        }
    }
}