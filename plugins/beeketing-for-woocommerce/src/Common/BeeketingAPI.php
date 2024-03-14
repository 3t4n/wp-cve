<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/12/18
 * Time: 6:55 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common;

use BeeketingConnect_beeketing_woocommerce\Common\Data\CommonHelper;
use Buzz\Message\Response;

class BeeketingAPI
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * Path to BKGO api endpoint
     * @var $goAPIEndpoint
     */
    private $goAPIEndpoint;

    /**
     * Path to legacy PHP api endpoint
     * @var $phpAPIEndpoint
     */
    private $phpAPIEndpoint;

    /**
     * Current app code
     *
     * @var string
     */
    private $appCode;

    /**
     * @var string
     */
    public $apiKey;

    /**
     * Set app code
     *
     * @param $appCode
     */
    public function setAppCode($appCode)
    {
        $this->appCode = $appCode;
    }

    /**
     * Set api key
     * @param $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get api key
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * BeeketingAPI constructor.
     * @param $goAPIEndpoint
     * @param $phpAPIEndpoint
     */
    public function __construct($goAPIEndpoint, $phpAPIEndpoint)
    {
        $this->goAPIEndpoint = $goAPIEndpoint;
        $this->phpAPIEndpoint = $phpAPIEndpoint;
    }

    /**
     * Send api request
     *
     * @param $method
     * @param $url
     * @param $content
     * @param array $headers
     * @param bool $useGoApi
     * @return array|bool|mixed
     */
    public function sendRequest($method, $url, $content = [], $headers = [], $useGoApi = true)
    {
        if (!$this->apiKey) {
            return false;
        }

        $headers = $this->getRequestHeaders($headers);
        $url = $this->getApiUrl($url, $useGoApi);

        return $this->doRequest($method, $url, $headers, $content);
    }

    /**
     * @param $url
     * @param $useGoApi
     * @return string
     */
    public function getApiUrl($url, $useGoApi)
    {
        return $useGoApi
            ? $this->goAPIEndpoint . '/v1/' . $url
            : $this->phpAPIEndpoint . '/rest-api/v1/' . $url . '.json';
    }

    /**
     * @param array $extraHeaders
     * @return array
     */
    private function getRequestHeaders($extraHeaders = [])
    {
        return array_merge([
            'Content-Type' => 'application/json',
            'X-Beeketing-Key' => $this->apiKey,
            'X-Beeketing-Access-Token-By-Shop-API-Key' => $this->apiKey,
            'X-Beeketing-Plugin-Version' => 999, // Fixed value
        ], $extraHeaders);
    }

    /**
     * Response error
     *
     * @param $message
     * @return array
     */
    private function responseError($message)
    {
        return [
            'errors' => $message,
        ];
    }

    /**
     * Disable app
     * @param $appCode
     * @param $shopId
     * @return bool
     */
    public function disableApp($appCode, $shopId)
    {
        $result = $this->sendRequest(
            self::METHOD_PUT,
            "appshop/disable/${appCode}?shop_id=${shopId}"
        );
        if ($result && isset($result['success'])) {
            return $result['success'];
        }

        return false;
    }

    /**
     * Get app shops
     * @param $userId
     * @param $shopId
     * @return array
     */
    public function getAppShops($userId, $shopId)
    {
        return $this->sendRequest(
            self::METHOD_GET,
            "user/appshops?user_id=${userId}&shop_id=${shopId}"
        );
    }

    /**
     * Update shop info
     * @param $params
     * @return bool
     */
    public function updateShopInfo($params = [])
    {
        $apikey = $this->apiKey;
        $result = $this->sendRequest(
            self::METHOD_PUT,
            "appshop/update-shop/${apikey}",
             $params,
             []
        );
        if ($result['success']) return true;
        return false;
    }

    /**
     * Get shop by apikey
     * @return bool
     */
    public function getShopByApikey()
    {
        $apikey = $this->apiKey;
        return $this->sendRequest(
            self::METHOD_GET,
            "appshop/get-shop-by-api-key?api_key=${apikey}"
        );
    }

    /**
     * @param $method
     * @param $url
     * @param $content
     * @return array|mixed|object
     */
    protected function sendNormalRequest($method, $url, $content)
    {
        $headers = $this->getRequestHeaders([]);
        if (!$this->apiKey) {
            unset($headers['X-Beeketing-Key']);
            unset($headers['X-Beeketing-Access-Token-By-Shop-API-Key']);
        }

        $url = $this->getApiUrl($url, true);
        return $this->doRequest($method, $url, $headers, $content);
    }

    /**
     * @param $method
     * @param $url
     * @param $headers
     * @param $content
     * @return array|mixed|object
     */
    protected function doRequest($method, $url, $headers, $content)
    {
        // Json encode array content
        if ($content) {
            if ($method == self::METHOD_GET) {
                $url = CommonHelper::addQueryArg($content, $url);
            } else {
                $content = json_encode($content);
            }
        }

        try {
            // Create browser to send request
            $browser = CommonHelper::createBrowser();

            /** @var Response|boolean $response */
            $response = false;
            switch ($method) {
                case self::METHOD_GET:
                    $response = $browser->get($url, $headers);
                    break;
                case self::METHOD_POST:
                    $response = $browser->post($url, $headers, $content);
                    break;
                case self::METHOD_PUT:
                    $response = $browser->put($url, $headers, $content);
                    break;
                case self::METHOD_DELETE:
                    $response = $browser->delete($url, $headers, $content);
                    break;
            }

            if ($response && in_array($response->getStatusCode(), [200, 201])) {
                return json_decode($response->getContent(), true);
            }

        } catch (\Exception $e) {
            return $this->responseError($e->getMessage());
        }

        return $this->responseError('Oops, Something went wrong!, Invalid response.');
    }

    /**
     * For Woocommerce only
     * @param $event
     * @param $params
     * @return bool
     */
    public function trackEvent($event, $params)
    {
        $currentUser = wp_get_current_user();
        $email = $currentUser->user_email;

        $response = $this->sendNormalRequest(
            self::METHOD_POST,
            'woocommerce/track',
            [
                'event' => $event,
                'email' => $email,
                'params' => array_merge([
                    'email' => $email,
                    'shop_domain' => CommonHelper::getShopURL()
                ], $params),
            ]
        );

        if ($response === false) {
            return false;
        }

        if (isset($response['success']) && $response['success']) {
            return true;
        }

        return false;
    }
}
