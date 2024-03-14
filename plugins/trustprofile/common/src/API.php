<?php
namespace Valued\WordPress;

use Exception;
use Requests;

if (!defined('REQUESTS_SILENCE_PSR0_DEPRECATIONS')) {
    define('REQUESTS_SILENCE_PSR0_DEPRECATIONS', true);
}

class API {
    private $api_domain;

    private $shop_id;

    private $api_key;

    public function __construct($api_domain, $shop_id, $api_key) {
        $this->api_domain = (string) $api_domain;
        $this->shop_id = (string) $shop_id;
        $this->api_key = (string) $api_key;
    }

    public function getReviews(string $last_synced = null): \SimpleXMLElement {
        $params = [
            'id' => $this->shop_id,
            'code' => $this->api_key,
            'detailed' => true,
            'last_synced' => $last_synced,
        ];
        $url = $this->buildURL('https://' . $this->api_domain . '/api/1.0/product_reviews.xml', $params);
        $response = Requests::get($url);
        if (empty($response->status_code)) {
            throw new WebwinkelKeurAPIError($url, "Could not retrieve product reviews: no response");
        }
        if ($response->status_code < 200 || $response->status_code >= 300) {
            throw new WebwinkelKeurAPIError($url, "Could not retrieve product reviews: got HTTP {$response->status_code}");
        }
        $document = simplexml_load_string($response->body);
        if (!$document) {
            throw new WebwinkelKeurAPIError($url, "Could not retrieve product reviews: could not parse response as XML");
        }
        if ($document->getName() === 'response' && (string) $document->status === 'error') {
            throw new WebwinkelKeurAPIError($url, "Could not retrieve product reviews: {$document->message}");
        }
        if ($document->getName() !== 'feed') {
            throw new WebwinkelKeurAPIError($url, "Could not retrieve product reviews: unexpected root tag: {$document->getName()}");
        }
        return $document->reviews->review;
    }

    public function invite(array $data) {
        $credentials = [
            'id'   => $this->shop_id,
            'code' => $this->api_key,
        ];

        $url = $this->buildURL('https://' . $this->api_domain . '/api/1.0/invitations.json', $credentials);

        $response = Requests::post($url, [], $data);
        $response->throw_for_status();

        $result = json_decode($response->body);
        if (isset($result->status) && $result->status == 'success') {
            return true;
        }
        throw new WebwinkelKeurAPIError(
            $url,
            isset($result->message) ? $result->message : $response->body
        );
    }

    private function buildURL($address, $parameters) {
        $query_string = http_build_query($parameters);
        if (strpos($address, '?') === false) {
            return $address . '?' . $query_string;
        }
        return $address . '&' . $query_string;
    }

    public function hasConsent(string $order_number): bool {
        $params = [
            'orderNumber' => $order_number,
            'id' => $this->shop_id,
            'code' => $this->api_key,
        ];
        $permission_url = $this->buildURL('https://' . $this->api_domain . '/api/2.0/order_permissions.json', $params);
        $permission_response = Requests::get($permission_url);
        $response_code = $permission_response->status_code;
        $permission = json_decode($permission_response->body);
        if ($response_code != 200) {
            throw new WebwinkelKeurAPIError(
                $permission_url,
                $permission->message ?? 'There was an error retrieving the order consent.'
            );
        }

        return $permission->has_consent;
    }
}

class WebwinkelKeurAPIError extends Exception {
    private $url;

    public function __construct($url, $message) {
        $this->url = $url;
        parent::__construct($message);
    }

    public function getURL() {
        return $this->url;
    }
}